<?php
/**
 * Migração única: adiciona coluna imagem_thumb à tabela blog_posts.
 * Execute uma vez acessando /admin/migrar-imagem-thumb.php e depois delete o arquivo.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$msg = '';
$ok  = false;

try {
    // Verifica se a coluna já existe
    $cols = $pdo->query("SHOW COLUMNS FROM blog_posts LIKE 'imagem_thumb'")->fetchAll();
    if ($cols) {
        $msg = 'A coluna <strong>imagem_thumb</strong> já existe — nenhuma alteração necessária.';
        $ok  = true;
    } else {
        $pdo->exec("ALTER TABLE blog_posts ADD COLUMN imagem_thumb VARCHAR(255) NULL AFTER imagem_capa");
        $msg = 'Coluna <strong>imagem_thumb</strong> adicionada com sucesso!<br>Pode apagar este arquivo agora.';
        $ok  = true;
    }
} catch (\PDOException $e) {
    $msg = 'Erro: ' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Migração — imagem_thumb</title>
<style>body{font-family:sans-serif;background:#0f172a;color:#e2e8f0;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
.box{background:#1e293b;border-radius:12px;padding:40px;max-width:480px;text-align:center;}
.ok{color:#4ade80;} .err{color:#f87171;}
a{color:#c4b5fd;}</style></head>
<body>
<div class="box">
    <h2>Migração: imagem_thumb</h2>
    <p class="<?= $ok ? 'ok' : 'err' ?>"><?= $msg ?></p>
    <p><a href="<?= BASE_URL ?>/admin/blog-post-form.php">← Criar/editar posts</a></p>
</div>
</body>
</html>
