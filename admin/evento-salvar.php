<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/eventos.php');
    exit;
}

$id          = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$titulo      = trim($_POST['titulo']        ?? '');
$categoriaId = (int) ($_POST['categoria_id'] ?? 0);
$dataEvento  = trim($_POST['data_evento']   ?? '');
$localNome   = trim($_POST['local_nome']    ?? '');
$localEnd    = trim($_POST['local_endereco']?? '');
$descricao   = trim($_POST['descricao']     ?? '');
$vagas       = $_POST['vagas'] !== '' ? (int) $_POST['vagas'] : null;
$status      = in_array($_POST['status'] ?? '', ['ativo','inativo','encerrado'])
               ? $_POST['status'] : 'ativo';

if (!$titulo || !$categoriaId || !$dataEvento) {
    header('Location: ' . BASE_URL . '/admin/evento-form.php' . ($id ? "?id=$id" : ''));
    exit;
}

// Tratar upload de imagem
$imagem = null;
if (!empty($_FILES['imagem']['name'])) {
    $ext       = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $permitidos = ['jpg','jpeg','png','webp'];
    if (in_array($ext, $permitidos) && $_FILES['imagem']['size'] <= 5 * 1024 * 1024) {
        $dir = __DIR__ . '/../img/eventos/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $nomeArq = 'evento_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $dir . $nomeArq)) {
            $imagem = 'img/eventos/' . $nomeArq;
        }
    }
}

if ($id) {
    // Buscar imagem atual se não enviou nova
    if ($imagem === null) {
        $stmt = $pdo->prepare("SELECT imagem FROM eventos WHERE id = ?");
        $stmt->execute([$id]);
        $atual = $stmt->fetch();
        $imagem = $atual['imagem'] ?? null;
    }

    $stmt = $pdo->prepare(
        "UPDATE eventos SET
            categoria_id   = ?,
            titulo         = ?,
            descricao      = ?,
            data_evento    = ?,
            local_nome     = ?,
            local_endereco = ?,
            imagem         = ?,
            vagas          = ?,
            status         = ?
         WHERE id = ?"
    );
    $stmt->execute([
        $categoriaId, $titulo, $descricao, $dataEvento,
        $localNome, $localEnd, $imagem, $vagas, $status, $id
    ]);
} else {
    $stmt = $pdo->prepare(
        "INSERT INTO eventos
            (categoria_id, titulo, descricao, data_evento, local_nome, local_endereco, imagem, vagas, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $categoriaId, $titulo, $descricao, $dataEvento,
        $localNome, $localEnd, $imagem, $vagas, $status
    ]);
}

header('Location: ' . BASE_URL . '/admin/eventos.php?msg=salvo');
exit;
