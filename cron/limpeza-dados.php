<?php
/**
 * Rotina diária: exclusão de atendimentos concluídos há mais de 90 dias (LGPD).
 * Configurar no cPanel > Cron Jobs:
 *   0 3 * * * php /home/cleit467/public_html/cron/limpeza-dados.php
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$nomRotina = 'limpeza-dados';

try {
    $stmt = $pdo->prepare("
        SELECT * FROM atendimentos
        WHERE status = 'concluido'
          AND data_conclusao <= DATE_SUB(NOW(), INTERVAL 90 DAY)
    ");
    $stmt->execute();
    $expirados = $stmt->fetchAll();

    if (empty($expirados)) {
        $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, 'sucesso', ?)")
            ->execute([$nomRotina, 'Nenhum registro elegível para exclusão.']);
        exit;
    }

    $logStmt = $pdo->prepare("
        INSERT INTO atendimentos_log (tipo_atendimento, data_solicitacao, data_conclusao, data_exclusao)
        VALUES (?, ?, ?, NOW())
    ");
    $delStmt = $pdo->prepare("DELETE FROM atendimentos WHERE id = ?");

    $excluidos = 0;
    foreach ($expirados as $a) {
        $logStmt->execute([$a['tipo_atendimento'], $a['data_solicitacao'], $a['data_conclusao']]);
        $delStmt->execute([$a['id']]);
        $excluidos++;
    }

    $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, 'sucesso', ?)")
        ->execute([$nomRotina, "{$excluidos} registro(s) excluído(s) conforme política de retenção (90 dias)."]);

} catch (Exception $e) {
    try {
        $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, 'erro', ?)")
            ->execute([$nomRotina, $e->getMessage()]);
    } catch (Exception $ex) {
        error_log('[limpeza-dados] ' . $e->getMessage());
    }
}
