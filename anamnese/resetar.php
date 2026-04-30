<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['participante_id'])) {
    header('Location: ' . BASE_URL . '/anamnese/');
    exit;
}

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$erro  = '';
$ok    = false;

// Buscar participante pelo token (não expirado)
$p = null;
if ($token) {
    $stmt = $pdo->prepare("
        SELECT id, nome FROM participantes
        WHERE reset_token = ?
          AND reset_expira > NOW()
        LIMIT 1
    ");
    $stmt->execute([$token]);
    $p = $stmt->fetch();
}

if (!$token || !$p) {
    $titulo = 'Link inválido ou expirado';
    $invalido = true;
} else {
    $invalido = false;
    $titulo   = 'Criar nova senha';
}

if (!$invalido && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha    = $_POST['senha']    ?? '';
    $confirma = $_POST['confirma'] ?? '';

    if (strlen($senha) < 8) {
        $erro = 'A senha deve ter ao menos 8 caracteres.';
    } elseif ($senha !== $confirma) {
        $erro = 'As senhas não coincidem.';
    } else {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $upd  = $pdo->prepare("
            UPDATE participantes
               SET senha = ?, reset_token = NULL, reset_expira = NULL
             WHERE id = ?
        ");
        $upd->execute([$hash, $p['id']]);

        // Loga o usuário automaticamente
        $_SESSION['participante_id']   = $p['id'];
        $_SESSION['participante_nome'] = $p['nome'];
        header('Location: ' . BASE_URL . '/anamnese/?msg=senha_ok');
        exit;
    }
}

$title       = 'Redefinir senha — Anamnese';
$description = 'Crie uma nova senha para acessar sua ficha de anamnese.';
$url         = BASE_URL . '/anamnese/resetar.php';
include __DIR__ . '/../includes/layout-top.php';
?>

<div class="container">
    <div class="anamnese-acesso">
        <img src="<?= BASE_URL ?>/img/logo.png" alt="ChamaTrina" class="anamnese-logo">
        <h1><?= $titulo ?></h1>

        <?php if ($invalido): ?>
            <div class="anamnese-alerta anamnese-alerta-erro">
                Este link de redefinição é inválido ou já expirou (validade de 1 hora).<br>
                Solicite um novo link na página de recuperação.
            </div>
            <p style="margin-top:20px;text-align:center;">
                <a href="<?= BASE_URL ?>/anamnese/recuperar.php" class="btn whatsapp" style="display:inline-flex;">
                    Solicitar novo link
                </a>
            </p>
        <?php else: ?>
            <p>Olá, <?= htmlspecialchars(explode(' ', $p['nome'])[0]) ?>! Escolha uma nova senha para sua conta.</p>

            <?php if ($erro): ?>
                <div class="anamnese-alerta anamnese-alerta-erro"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form method="POST" class="anamnese-form-acesso">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="campo">
                    <label>Nova senha (mínimo 8 caracteres)</label>
                    <input type="password" name="senha" required minlength="8" autofocus>
                </div>
                <div class="campo">
                    <label>Confirmar nova senha</label>
                    <input type="password" name="confirma" required minlength="8">
                </div>
                <button type="submit" class="btn whatsapp" style="width:100%;justify-content:center;margin-top:5px;">
                    Salvar nova senha
                </button>
            </form>
        <?php endif; ?>

        <p style="margin-top:20px;text-align:center;color:#aaa;font-size:14px;">
            <a href="<?= BASE_URL ?>/anamnese/login.php" style="color:#25D366;">← Voltar ao login</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../includes/layout-bottom.php'; ?>
