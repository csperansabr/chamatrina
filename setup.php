<?php
/**
 * SCRIPT DE INSTALAÇÃO — Execute UMA VEZ e depois delete este arquivo.
 * Acesse: http://chamatrina.org.br/setup.php
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$erros  = [];
$passos = [];

// Tabela de categorias de eventos
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS categorias_eventos (
        id       INT AUTO_INCREMENT PRIMARY KEY,
        nome     VARCHAR(100) NOT NULL,
        slug     VARCHAR(100) NOT NULL UNIQUE,
        ordem    INT DEFAULT 0,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>categorias_eventos</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em categorias_eventos: ' . $e->getMessage();
}

// Tabela de eventos
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS eventos (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        categoria_id  INT NOT NULL,
        titulo        VARCHAR(255) NOT NULL,
        descricao     TEXT,
        data_evento   DATETIME NOT NULL,
        local_nome    VARCHAR(255),
        local_endereco TEXT,
        imagem        VARCHAR(255),
        vagas         INT DEFAULT NULL,
        status        ENUM('ativo','inativo','encerrado') DEFAULT 'ativo',
        criado_em     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (categoria_id) REFERENCES categorias_eventos(id) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>eventos</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em eventos: ' . $e->getMessage();
}

// Tabela de administradores
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_usuarios (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        email     VARCHAR(255) NOT NULL UNIQUE,
        senha     VARCHAR(255) NOT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>admin_usuarios</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em admin_usuarios: ' . $e->getMessage();
}

// Categorias padrão
$categorias = [
    ['Cerimônias com Medicinas da Floresta', 'cerimonias-medicinas', 1],
    ['Sagrado Masculino',                    'sagrado-masculino',    2],
    ['Sagrado Feminino',                     'sagrado-feminino',     3],
    ['Cerimônias Mistas',                    'cerimonias-mistas',    4],
    ['Cursos e Workshops',                   'cursos-workshops',     5],
    ['Atendimentos em Grupo',                'atendimentos-grupo',   6],
];

foreach ($categorias as [$nome, $slug, $ordem]) {
    try {
        $stmt = $pdo->prepare(
            "INSERT IGNORE INTO categorias_eventos (nome, slug, ordem) VALUES (?, ?, ?)"
        );
        $stmt->execute([$nome, $slug, $ordem]);
    } catch (PDOException $e) {
        $erros[] = "Erro ao inserir categoria '$nome': " . $e->getMessage();
    }
}
$passos[] = '✔ Categorias padrão inseridas.';

// Usuário admin padrão
$emailAdmin = 'admin@chamatrina.org.br';
$senhaAdmin = password_hash('ChamaTrina@2026', PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare(
        "INSERT IGNORE INTO admin_usuarios (email, senha) VALUES (?, ?)"
    );
    $stmt->execute([$emailAdmin, $senhaAdmin]);
    $passos[] = '✔ Usuário admin criado.';
} catch (PDOException $e) {
    $erros[] = 'Erro ao criar admin: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Instalação — ChamaTrina</title>
<style>
  body { font-family: Arial, sans-serif; background: #0f172a; color: #fff; padding: 40px; max-width: 600px; margin: auto; }
  h1   { color: #25D366; }
  .ok  { color: #4ade80; margin: 8px 0; }
  .err { color: #f87171; margin: 8px 0; }
  .box { background: rgba(255,255,255,0.07); padding: 25px; border-radius: 12px; margin-top: 25px; }
  .cred { background: rgba(255,255,255,0.12); padding: 15px; border-radius: 8px; margin: 15px 0; font-family: monospace; }
  .warn { color: #fbbf24; font-weight: bold; margin-top: 20px; }
</style>
</head>
<body>
<h1>Instalação do Sistema ChamaTrina</h1>

<?php foreach ($passos as $p): ?>
    <p class="ok"><?= $p ?></p>
<?php endforeach; ?>

<?php foreach ($erros as $e): ?>
    <p class="err">✘ <?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<?php if (empty($erros)): ?>
<div class="box">
    <h2>Instalação concluída!</h2>
    <p>Acesse o painel administrativo com as credenciais abaixo:</p>
    <div class="cred">
        E-mail: admin@chamatrina.org.br<br>
        Senha:  ChamaTrina@2026
    </div>
    <p class="warn">⚠ IMPORTANTE: Delete este arquivo (setup.php) imediatamente após acessar o painel e altere a senha.</p>
    <p><a href="<?= BASE_URL ?>/admin/login.php" style="color:#25D366;">→ Ir para o painel admin</a></p>
</div>
<?php else: ?>
<div class="box">
    <p style="color:#f87171;">Ocorreram erros. Verifique as credenciais do banco em <strong>includes/config.php</strong> e tente novamente.</p>
</div>
<?php endif; ?>
</body>
</html>
