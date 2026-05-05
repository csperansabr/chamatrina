<?php
/**
 * Rotina diária: verifica se as outras rotinas executaram nas últimas 24h e envia alerta em caso de falha.
 * Configurar no cPanel > Cron Jobs:
 *   0 9 * * * php /home/cleit467/public_html/cron/verificar-rotinas.php
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

$nomRotina          = 'verificar-rotinas';
$rotinasMonitoradas = ['alerta-pendentes', 'limpeza-dados', 'encerrar-eventos'];
$alertas            = [];

try {
    foreach ($rotinasMonitoradas as $rotina) {
        $stmt = $pdo->prepare("
            SELECT status, data_execucao, mensagem FROM rotinas_execucao
            WHERE nome_rotina = ?
            ORDER BY data_execucao DESC LIMIT 1
        ");
        $stmt->execute([$rotina]);
        $ultima = $stmt->fetch();

        if (!$ultima) {
            $alertas[] = "• [{$rotina}]: nunca executada.";
        } elseif ($ultima['status'] === 'erro') {
            $alertas[] = "• [{$rotina}]: último status ERRO em "
                . date('d/m/Y H:i', strtotime($ultima['data_execucao']))
                . " — " . $ultima['mensagem'];
        } elseif (strtotime($ultima['data_execucao']) < strtotime('-24 hours')) {
            $alertas[] = "• [{$rotina}]: última execução em "
                . date('d/m/Y H:i', strtotime($ultima['data_execucao']))
                . " (há mais de 24h sem execução).";
        }
    }

    if (!empty($alertas)) {
        $corpo  = "ALERTA — Falha ou atraso em rotinas automáticas\n";
        $corpo .= "=================================================\n\n";
        $corpo .= implode("\n", $alertas) . "\n\n";
        $corpo .= "Acesse o painel: " . BASE_URL . "/admin/rotinas.php\n";

        $enviado = enviarEmail(
            'contato@chamatrina.org.br',
            MAIL_NOME,
            'Alerta — Falha em rotina automática — Chama Trina',
            $corpo
        );

        $msg = ($enviado ? 'Alerta enviado. ' : 'Falha ao enviar alerta. ')
             . implode(' | ', $alertas);

        $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, ?, ?)")
            ->execute([$nomRotina, $enviado ? 'sucesso' : 'erro', mb_substr($msg, 0, 500)]);
    } else {
        $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, 'sucesso', ?)")
            ->execute([$nomRotina, 'Todas as rotinas executaram com sucesso nas últimas 24h.']);
    }

} catch (Exception $e) {
    try {
        $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, 'erro', ?)")
            ->execute([$nomRotina, $e->getMessage()]);
    } catch (Exception $ex) {
        error_log('[verificar-rotinas] ' . $e->getMessage());
    }
}
