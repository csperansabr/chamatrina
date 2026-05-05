<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/atendimentos.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id) {
    $pdo->prepare("DELETE FROM atendimentos WHERE id = ?")->execute([$id]);
}

header('Location: ' . BASE_URL . '/admin/atendimentos.php?ok=excluido');
exit;
