<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id) {
    // Remover imagem física se existir
    $stmt = $pdo->prepare("SELECT imagem FROM eventos WHERE id = ?");
    $stmt->execute([$id]);
    $evento = $stmt->fetch();
    if ($evento && $evento['imagem']) {
        $caminho = __DIR__ . '/../' . $evento['imagem'];
        if (file_exists($caminho)) {
            unlink($caminho);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: ' . BASE_URL . '/admin/eventos.php?msg=excluido');
exit;
