<?php
/**
 * Rotina diária: alerta de atendimentos pendentes há mais de 3 dias.
 * Configurar no cPanel > Cron Jobs:
 *   0 8 * * * php /home/cleit467/public_html/cron/alerta-pendentes.php
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

$nomRotina = 'alerta-pendentes';

try {
    $stmt = $pdo->prepare("
        SELECT * FROM atendimentos
        WHERE status = 'pendente'
          AND data_solicitacao <= DATE_SUB(NOW(), INTERVAL 3 DAY)
        ORDER BY data_solicitacao ASC
    ");
    $stmt->execute();
    $pendentes = $stmt->fetchAll();

    if (empty($pendentes)) {
        $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, 'sucesso', ?)")
            ->execute([$nomRotina, 'Nenhum atendimento pendente há mais de 3 dias.']);
        exit;
    }

    $corpo  = "ALERTA — Atendimentos pendentes há mais de 3 dias\n";
    $corpo .= "==================================================\n\n";
    foreach ($pendentes as $a) {
        $corpo .= "• {$a['nome']} ({$a['tipo_atendimento']})\n";
        $corpo .= "  WhatsApp: {$a['whatsapp']}\n";
        $corpo .= "  Solicitado em: " . date('d/m/Y H:i', strtotime($a['data_solicitacao'])) . "\n\n";
    }
    $corpo .= "Total: " . count($pendentes) . " atendimento(s) pendente(s).\n";
    $corpo .= "Acesse o painel: " . BASE_URL . "/admin/atendimentos.php?status=pendente\n";

    $enviado = enviarEmail(
        'contato@chamatrina.org.br',
        MAIL_NOME,
        'Alerta — Atendimentos pendentes há mais de 3 dias',
        $corpo
    );

    $msg = $enviado
        ? count($pendentes) . ' pendente(s). Alerta enviado com sucesso.'
        : count($pendentes) . ' pendente(s). Falha ao enviar e-mail de alerta.';

    $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, ?, ?)")
        ->execute([$nomRotina, $enviado ? 'sucesso' : 'erro', $msg]);

} catch (Exception $e) {
    try {
        $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, 'erro', ?)")
            ->execute([$nomRotina, $e->getMessage()]);
    } catch (Exception $ex) {
        error_log('[alerta-pendentes] ' . $e->getMessage());
    }
}
