<?php
/**
 * Rotina horária: encerra automaticamente eventos cuja data de término (ou início) já passou.
 * Configurar no cPanel > Cron Jobs:
 *   0 * * * * php /home/cleit467/public_html/cron/encerrar-eventos.php
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$nomRotina = 'encerrar-eventos';

try {
    $stmt = $pdo->prepare("
        UPDATE eventos
        SET status = 'encerrado'
        WHERE status = 'ativo'
          AND (
              (data_evento_fim IS NOT NULL AND data_evento_fim < NOW())
              OR
              (data_evento_fim IS NULL     AND data_evento    < NOW())
          )
    ");
    $stmt->execute();
    $atualizados = $stmt->rowCount();

    $msg = $atualizados > 0
        ? "{$atualizados} evento(s) encerrado(s) automaticamente."
        : 'Nenhum evento para encerrar.';

    $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, 'sucesso', ?)")
        ->execute([$nomRotina, $msg]);

} catch (Exception $e) {
    try {
        $pdo->prepare("INSERT INTO rotinas_execucao (nome_rotina, status, mensagem) VALUES (?, 'erro', ?)")
            ->execute([$nomRotina, $e->getMessage()]);
    } catch (Exception $ex) {
        error_log('[encerrar-eventos] ' . $e->getMessage());
    }
}
