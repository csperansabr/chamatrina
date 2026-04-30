<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$msgs = [];

// Verifica e adiciona cada coluna separadamente (compatível com MySQL 5.7)
$colunas = [
    'reset_token'  => "VARCHAR(64)  NULL DEFAULT NULL",
    'reset_expira' => "DATETIME     NULL DEFAULT NULL",
];

foreach ($colunas as $coluna => $definicao) {
    $check = $pdo->prepare("
        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'participantes'
          AND COLUMN_NAME  = ?
    ");
    $check->execute([$coluna]);

    if ($check->fetchColumn() > 0) {
        $msgs[] = ['ok', "Coluna <strong>{$coluna}</strong> já existia — nada alterado."];
    } else {
        $pdo->exec("ALTER TABLE participantes ADD COLUMN {$coluna} {$definicao}");
        $msgs[] = ['ok', "Coluna <strong>{$coluna}</strong> adicionada com sucesso."];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Setup Reset</title></head>
<body style="font-family:sans-serif;padding:40px;background:#0f172a;color:#e2e8f0;">
<h2 style="color:#8b5cf6;">Setup: recuperação de senha</h2>
<?php foreach ($msgs as [$tipo, $msg]): ?>
    <p style="color:<?= $tipo === 'ok' ? '#4ade80' : '#f87171' ?>;">
        <?= $tipo === 'ok' ? '✔' : '✖' ?> <?= $msg ?>
    </p>
<?php endforeach; ?>
<p style="margin-top:30px;color:#94a3b8;font-size:13px;">Após confirmar que está tudo OK, apague este arquivo do servidor.</p>
</body>
</html>
