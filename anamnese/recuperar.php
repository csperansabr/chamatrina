<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['participante_id'])) {
    header('Location: ' . BASE_URL . '/anamnese/');
    exit;
}

$erro  = '';
$ok    = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $cpf   = preg_replace('/\D/', '', $login);

    if (!$login) {
        $erro = 'Informe seu e-mail ou CPF.';
    } else {
        // Buscar participante por e-mail ou CPF
        if (strlen($cpf) === 11) {
            $stmt = $pdo->prepare("SELECT id, nome, email FROM participantes WHERE cpf = ? LIMIT 1");
            $stmt->execute([$cpf]);
        } else {
            $stmt = $pdo->prepare("SELECT id, nome, email FROM participantes WHERE email = ? LIMIT 1");
            $stmt->execute([$login]);
        }

        $p = $stmt->fetch();

        if ($p) {
            // Gerar token seguro (64 chars hex)
            $token  = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $upd = $pdo->prepare("UPDATE participantes SET reset_token = ?, reset_expira = ? WHERE id = ?");
            $upd->execute([$token, $expira, $p['id']]);

            $link    = BASE_URL . '/anamnese/resetar.php?token=' . $token;
            $nome    = explode(' ', $p['nome'])[0];
            $assunto = 'Redefinição de senha — Chama Trina';

            $corpo = "Olá, {$nome}!\n\n"
                   . "Recebemos uma solicitação para redefinir a senha da sua conta na Fraternidade Essência da Chama Trina.\n\n"
                   . "Clique no link abaixo para criar uma nova senha. O link é válido por 1 hora:\n\n"
                   . "{$link}\n\n"
                   . "Se você não solicitou a redefinição, ignore este e-mail. Sua senha atual continua a mesma.\n\n"
                   . "Fraternidade Essência da Chama Trina\n"
                   . "chamatrina.org.br";

            enviarEmail($p['email'], $nome, $assunto, $corpo);
        }

        // Resposta idêntica independente de achar ou não (evita enumeração de usuários)
        $ok = true;
    }
}

$title       = 'Recuperar acesso — Anamnese';
$description = 'Recupere o acesso à sua ficha de anamnese.';
$url         = BASE_URL . '/anamnese/recuperar.php';
include __DIR__ . '/../includes/layout-top.php';
?>

<div class="container">
    <div class="anamnese-acesso">
        <img src="<?= BASE_URL ?>/img/logo.png" alt="ChamaTrina" class="anamnese-logo">
        <h1>Recuperar acesso</h1>
        <p>Informe seu e-mail ou CPF cadastrado. Enviaremos um link para redefinir sua senha.</p>

        <?php if ($erro): ?>
            <div class="anamnese-alerta anamnese-alerta-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if ($ok): ?>
            <div class="anamnese-alerta anamnese-alerta-ok">
                Se encontrarmos uma conta com esse dado, você receberá um e-mail com o link de redefinição em breve. Verifique também a caixa de spam.
            </div>
        <?php else: ?>
            <form method="POST" class="anamnese-form-acesso">
                <div class="campo">
                    <label>E-mail ou CPF cadastrado</label>
                    <input type="text" name="login" required autofocus
                           placeholder="seu@email.com ou 000.000.000-00"
                           value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
                </div>
                <button type="submit" class="btn whatsapp" style="width:100%;justify-content:center;margin-top:5px;">
                    Enviar link de redefinição
                </button>
            </form>
        <?php endif; ?>

        <p style="margin-top:20px;text-align:center;color:#aaa;font-size:14px;">
            <a href="<?= BASE_URL ?>/anamnese/login.php" style="color:#25D366;">← Voltar ao login</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../includes/layout-bottom.php'; ?>
