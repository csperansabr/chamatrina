<?php
/**
 * INSTALAÇÃO — Atendimentos Online — Execute UMA VEZ e depois delete este arquivo.
 * Acesse: https://chamatrina.org.br/setup_atendimentos.php
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$passos = [];
$erros  = [];

// Tabela principal de atendimentos
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS atendimentos (
        id               INT AUTO_INCREMENT PRIMARY KEY,
        nome             VARCHAR(255) NOT NULL,
        email            VARCHAR(255) NOT NULL,
        whatsapp         VARCHAR(30)  NOT NULL,
        data_nascimento  DATE         NOT NULL,
        nome_mae         VARCHAR(255) NOT NULL,
        endereco         TEXT         NOT NULL,
        tipo_atendimento VARCHAR(100) NOT NULL DEFAULT 'Benzimento',
        intencao         TEXT         NOT NULL,
        status           ENUM('pendente','concluido') NOT NULL DEFAULT 'pendente',
        data_solicitacao DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        data_conclusao   DATETIME     NULL,
        msg_conclusao    TEXT         NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>atendimentos</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em atendimentos: ' . $e->getMessage();
}

// Migração: adicionar coluna msg_conclusao caso a tabela já existia antes desta versão
try {
    $pdo->exec("ALTER TABLE atendimentos ADD COLUMN msg_conclusao TEXT NULL AFTER data_conclusao");
    $passos[] = '✔ Coluna <strong>msg_conclusao</strong> adicionada.';
} catch (PDOException $e) {
    $passos[] = '✔ Coluna <strong>msg_conclusao</strong> já existia — nenhuma ação necessária.';
}

// Tabela de log anônimo (LGPD — sem dados pessoais)
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS atendimentos_log (
        id               INT AUTO_INCREMENT PRIMARY KEY,
        tipo_atendimento VARCHAR(100) NOT NULL,
        data_solicitacao DATETIME     NOT NULL,
        data_conclusao   DATETIME     NULL,
        data_exclusao    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>atendimentos_log</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em atendimentos_log: ' . $e->getMessage();
}

// Tabela de monitoramento de rotinas
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS rotinas_execucao (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        nome_rotina   VARCHAR(100) NOT NULL,
        data_execucao DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        status        ENUM('sucesso','erro') NOT NULL,
        mensagem      TEXT         NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>rotinas_execucao</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em rotinas_execucao: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Setup Atendimentos — ChamaTrina</title>
<style>
  body { font-family: Arial, sans-serif; background: #0f172a; color: #fff; padding: 40px; max-width: 620px; margin: auto; }
  h1   { color: #8b5cf6; }
  .ok  { color: #4ade80; margin: 8px 0; }
  .err { color: #f87171; margin: 8px 0; }
  .box { background: rgba(255,255,255,0.07); padding: 25px; border-radius: 12px; margin-top: 25px; }
  .warn { color: #fbbf24; font-weight: bold; margin-top: 16px; }
</style>
</head>
<body>
<h1>Instalação — Atendimentos Online</h1>

<?php foreach ($passos as $p): ?>
    <p class="ok"><?= $p ?></p>
<?php endforeach; ?>
<?php foreach ($erros as $e): ?>
    <p class="err">✘ <?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<div class="box">
<?php if (empty($erros)): ?>
    <h2>Instalação concluída!</h2>
    <p>Tabelas de atendimentos, log e rotinas criadas com sucesso.</p>
    <p><a href="<?= BASE_URL ?>/admin/atendimentos.php" style="color:#8b5cf6;">→ Ir para gerenciar atendimentos</a></p>
    <p class="warn">⚠ Delete este arquivo (setup_atendimentos.php) do servidor imediatamente.</p>
<?php else: ?>
    <p style="color:#f87171;">Ocorreram erros. Verifique as configurações e tente novamente.</p>
<?php endif; ?>
</div>
</body>
</html>
