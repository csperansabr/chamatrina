<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/blog.php');
    exit;
}

$postId    = (int)($_POST['post_id']    ?? 0);
$slug      = trim($_POST['slug']        ?? '');
$nome      = trim($_POST['nome']        ?? '');
$email     = trim($_POST['email']       ?? '');
$comentario = trim($_POST['comentario'] ?? '');

$redir = BASE_URL . '/blog-post.php?slug=' . urlencode($slug) . '#comentarios';

if (!$postId || !$nome || !$email || !$comentario || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . $redir . '&comentario=erro');
    exit;
}

// Verifica se o post existe e está publicado
$stmt = $pdo->prepare("
    SELECT id, titulo FROM blog_posts
    WHERE id = ? AND (status = 'publicado' OR (status = 'agendado' AND publicado_em <= NOW()))
    LIMIT 1
");
$stmt->execute([$postId]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: ' . BASE_URL . '/blog.php');
    exit;
}

try {
    $pdo->prepare("
        INSERT INTO blog_comentarios (post_id, nome, email, comentario, status)
        VALUES (?, ?, ?, ?, 'pendente')
    ")->execute([$postId, $nome, $email, $comentario]);

    // Notifica o admin por e-mail
    $linkAdmin = BASE_URL . '/admin/blog-comentarios.php?post=' . $postId . '&status=pendente';
    enviarEmail(
        MAIL_USUARIO,
        MAIL_NOME,
        'Novo comentário aguardando moderação — ' . $post['titulo'],
        "Olá!\n\nUm novo comentário foi enviado no post:\n\"{$post['titulo']}\"\n\n"
        . "Autor: {$nome} ({$email})\n\nComentário:\n{$comentario}\n\n"
        . "Modere em:\n{$linkAdmin}\n\nchamatrina.org.br"
    );

    header('Location: ' . $redir . '&comentario=ok');
} catch (PDOException $e) {
    header('Location: ' . $redir . '&comentario=erro');
}
exit;
