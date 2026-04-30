<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/blog-posts.php');
    exit;
}

$id          = (int)($_POST['id']          ?? 0);
$titulo      = trim($_POST['titulo']       ?? '');
$slug        = trim($_POST['slug']         ?? '');
$resumo      = trim($_POST['resumo']       ?? '');
$conteudo    = $_POST['conteudo']          ?? '';
$catId       = $_POST['categoria_id'] !== '' ? (int)$_POST['categoria_id'] : null;
$autorId     = (int)($_POST['autor_id']    ?? 0);
$status      = in_array($_POST['status'] ?? '', ['rascunho','publicado','agendado'])
               ? $_POST['status'] : 'rascunho';
$publicadoEm = ($status !== 'rascunho' && !empty($_POST['publicado_em']))
               ? date('Y-m-d H:i:s', strtotime($_POST['publicado_em']))
               : ($status === 'publicado' ? date('Y-m-d H:i:s') : null);

// Slug único
$slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
if (!$slug) {
    $slug = preg_replace('/\s+/', '-', strtolower(trim($titulo)));
    $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
}

// Verifica unicidade do slug
$checkSlug = $pdo->prepare("SELECT id FROM blog_posts WHERE slug = ? AND id != ?");
$checkSlug->execute([$slug, $id]);
if ($checkSlug->fetch()) {
    $slug .= '-' . time();
}

// Upload de imagem
$imagemCapa = null;
if ($id) {
    $stmt = $pdo->prepare("SELECT imagem_capa FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $imagemCapa = $stmt->fetchColumn();
}

if (!empty($_POST['remover_imagem']) && $imagemCapa) {
    $path = __DIR__ . '/../' . $imagemCapa;
    if (file_exists($path)) unlink($path);
    $imagemCapa = null;
}

if (!empty($_FILES['imagem_capa']['tmp_name'])) {
    $file = $_FILES['imagem_capa'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','webp']) && $file['size'] <= 5 * 1024 * 1024) {
        $nome = 'blog-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = __DIR__ . '/../img/blog/' . $nome;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            // Remove imagem antiga
            if ($imagemCapa && file_exists(__DIR__ . '/../' . $imagemCapa)) {
                unlink(__DIR__ . '/../' . $imagemCapa);
            }
            $imagemCapa = 'img/blog/' . $nome;
        }
    }
}

if ($id) {
    $stmt = $pdo->prepare("
        UPDATE blog_posts SET
            titulo = ?, slug = ?, resumo = ?, conteudo = ?,
            imagem_capa = ?, categoria_id = ?, autor_id = ?,
            status = ?, publicado_em = ?
        WHERE id = ?
    ");
    $stmt->execute([$titulo, $slug, $resumo, $conteudo,
                    $imagemCapa, $catId, $autorId,
                    $status, $publicadoEm, $id]);
} else {
    $stmt = $pdo->prepare("
        INSERT INTO blog_posts
            (titulo, slug, resumo, conteudo, imagem_capa, categoria_id, autor_id, status, publicado_em)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$titulo, $slug, $resumo, $conteudo,
                    $imagemCapa, $catId, $autorId,
                    $status, $publicadoEm]);
}

header('Location: ' . BASE_URL . '/admin/blog-posts.php?ok=1');
exit;
