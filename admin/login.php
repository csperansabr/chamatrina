<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['admin_logado'])) {
    header('Location: ' . BASE_URL . '/admin/');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email && $senha) {
        $stmt = $pdo->prepare("SELECT id, senha FROM admin_usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($senha, $admin['senha'])) {
            $_SESSION['admin_logado'] = $admin['id'];
            header('Location: ' . BASE_URL . '/admin/');
            exit;
        }
    }
    $erro = 'E-mail ou senha incorretos.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Admin ChamaTrina</title>
<link rel="stylesheet" href="/admin/css/admin.css">
</head>
<body>

<div class="login-wrap">
    <div class="login-box">
        <img src="<?= BASE_URL ?>/img/logo.png" alt="ChamaTrina">
        <h1>Painel Administrativo</h1>

        <?php if ($erro): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" required autofocus
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required>
            </div>
            <button type="submit" class="btn-admin btn-primary" style="width:100%;justify-content:center;">
                Entrar
            </button>
        </form>
    </div>
</div>

</body>
</html>
