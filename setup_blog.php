<?php
/**
 * INSTALAÇÃO DO BLOG — Execute UMA VEZ e depois delete este arquivo.
 * Acesse: https://chamatrina.org.br/setup_blog.php
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$passos = [];
$erros  = [];

// Coluna nome em admin_usuarios (MySQL 5.7: verifica antes de adicionar)
try {
    $check = $pdo->prepare("
        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'admin_usuarios' AND COLUMN_NAME = 'nome'
    ");
    $check->execute();
    if (!$check->fetchColumn()) {
        $pdo->exec("ALTER TABLE admin_usuarios ADD COLUMN nome VARCHAR(100) NULL AFTER email");
        $passos[] = '✔ Coluna <strong>nome</strong> adicionada em admin_usuarios.';
    } else {
        $passos[] = '✔ Coluna <strong>nome</strong> já existia em admin_usuarios.';
    }
} catch (PDOException $e) {
    $erros[] = 'Erro ao alterar admin_usuarios: ' . $e->getMessage();
}

// Tabela blog_categorias
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS blog_categorias (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        nome      VARCHAR(100) NOT NULL,
        slug      VARCHAR(100) NOT NULL UNIQUE,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>blog_categorias</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em blog_categorias: ' . $e->getMessage();
}

// Tabela blog_posts
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS blog_posts (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        titulo       VARCHAR(255) NOT NULL,
        slug         VARCHAR(255) NOT NULL UNIQUE,
        resumo       TEXT,
        conteudo     LONGTEXT,
        imagem_capa  VARCHAR(255),
        categoria_id INT,
        autor_id     INT NOT NULL,
        status       ENUM('rascunho','publicado','agendado') DEFAULT 'rascunho',
        publicado_em DATETIME,
        criado_em    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (categoria_id) REFERENCES blog_categorias(id) ON DELETE SET NULL,
        FOREIGN KEY (autor_id)     REFERENCES admin_usuarios(id)  ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>blog_posts</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em blog_posts: ' . $e->getMessage();
}

// Tabela blog_comentarios
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS blog_comentarios (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        post_id    INT NOT NULL,
        nome       VARCHAR(100) NOT NULL,
        email      VARCHAR(150) NOT NULL,
        comentario TEXT NOT NULL,
        status     ENUM('pendente','aprovado','spam') DEFAULT 'pendente',
        criado_em  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>blog_comentarios</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em blog_comentarios: ' . $e->getMessage();
}

// Categorias padrão
$cats = [
    ['Umbanda',              'umbanda'],
    ['Medicinas da Floresta','medicinas-da-floresta'],
    ['Espiritualidade',      'espiritualidade'],
    ['Reflexões',            'reflexoes'],
    ['Notícias',             'noticias'],
];
foreach ($cats as [$nome, $slug]) {
    try {
        $pdo->prepare("INSERT IGNORE INTO blog_categorias (nome, slug) VALUES (?, ?)")
            ->execute([$nome, $slug]);
    } catch (PDOException $e) { /* ignora duplicatas */ }
}
$passos[] = '✔ Categorias padrão de blog inseridas.';

// Pasta de imagens
$dir = __DIR__ . '/img/blog/';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
    $passos[] = '✔ Pasta <strong>img/blog/</strong> criada.';
} else {
    $passos[] = '✔ Pasta <strong>img/blog/</strong> já existia.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Setup Blog — ChamaTrina</title>
<style>
  body{font-family:Arial,sans-serif;background:#0f172a;color:#fff;padding:40px;max-width:620px;margin:auto}
  h1{color:#8b5cf6}
  .ok{color:#4ade80;margin:8px 0}
  .err{color:#f87171;margin:8px 0}
  .box{background:rgba(255,255,255,0.07);padding:25px;border-radius:12px;margin-top:25px}
  .warn{color:#fbbf24;font-weight:bold;margin-top:16px}
</style>
</head>
<body>
<h1>Instalação do Blog — ChamaTrina</h1>

<?php foreach ($passos as $p): ?>
    <p class="ok"><?= $p ?></p>
<?php endforeach; ?>
<?php foreach ($erros as $e): ?>
    <p class="err">✘ <?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<div class="box">
<?php if (empty($erros)): ?>
    <h2>Instalação concluída!</h2>
    <p>O sistema de blog está pronto. Acesse o painel para criar categorias e publicar posts.</p>
    <p><a href="<?= BASE_URL ?>/admin/blog-posts.php" style="color:#8b5cf6;">→ Ir para gerenciar posts</a></p>
    <p class="warn">⚠ Delete este arquivo do servidor após a instalação.</p>
<?php else: ?>
    <p style="color:#f87171;">Ocorreram erros. Verifique as configurações e tente novamente.</p>
<?php endif; ?>
</div>
</body>
</html>
