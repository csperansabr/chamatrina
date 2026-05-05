<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/blog-posts.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare("SELECT imagem_capa, imagem_thumb FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    foreach (['imagem_capa', 'imagem_thumb'] as $campo) {
        if (!empty($row[$campo])) {
            $path = __DIR__ . '/../' . $row[$campo];
            if (file_exists($path)) unlink($path);
        }
    }

    $pdo->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$id]);
}

header('Location: ' . BASE_URL . '/admin/blog-posts.php');
exit;
