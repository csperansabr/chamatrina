<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['participante_id'])) {
    header('Location: ' . BASE_URL . '/anamnese/');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($login && $senha) {
        $cpf = preg_replace('/\D/', '', $login);

        if (strlen($cpf) === 11) {
            $stmt = $pdo->prepare("SELECT id, nome, senha FROM participantes WHERE cpf = ? LIMIT 1");
            $stmt->execute([$cpf]);
        } else {
            $stmt = $pdo->prepare("SELECT id, nome, senha FROM participantes WHERE email = ? LIMIT 1");
            $stmt->execute([$login]);
        }

        $p = $stmt->fetch();
        if ($p && password_verify($senha, $p['senha'])) {
            $_SESSION['participante_id']   = $p['id'];
            $_SESSION['participante_nome'] = $p['nome'];
            header('Location: ' . BASE_URL . '/anamnese/');
            exit;
        }
    }
    $erro = 'E-mail, CPF ou senha incorretos.';
}

$title       = 'Acesso — Ficha de Anamnese';
$description = 'Acesse sua ficha de anamnese para cerimônias da Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/anamnese/login.php';
include __DIR__ . '/../includes/layout-top.php';
?>

<div class="container">
    <div class="anamnese-acesso">
        <img src="<?= BASE_URL ?>/img/logo.png" alt="ChamaTrina" class="anamnese-logo">
        <h1>Ficha de Anamnese</h1>
        <p>Acesse com seu e-mail ou CPF para preencher ou atualizar sua ficha.</p>

        <?php if ($erro): ?>
            <div class="anamnese-alerta anamnese-alerta-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" class="anamnese-form-acesso">
            <div class="campo">
                <label>E-mail ou CPF</label>
                <input type="text" name="login" required autofocus
                       placeholder="seu@email.com ou 000.000.000-00"
                       value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
            </div>
            <div class="campo">
                <label>Senha</label>
                <input type="password" name="senha" required placeholder="Sua senha">
            </div>
            <button type="submit" class="btn whatsapp" style="width:100%;justify-content:center;margin-top:5px;">
                Entrar
            </button>
        </form>

        <p style="margin-top:14px;text-align:center;font-size:13px;">
            <a href="<?= BASE_URL ?>/anamnese/recuperar.php" style="color:#8b5cf6;">Esqueci minha senha</a>
        </p>

        <p style="margin-top:10px;text-align:center;color:#aaa;font-size:14px;">
            Primeira vez?
            <a href="<?= BASE_URL ?>/anamnese/registro.php" style="color:#25D366;">Crie sua conta</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../includes/layout-bottom.php'; ?>
