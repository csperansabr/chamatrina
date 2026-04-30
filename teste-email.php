<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/phpmailer/Exception.php';
require_once __DIR__ . '/includes/phpmailer/PHPMailer.php';
require_once __DIR__ . '/includes/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Só executa se acessado diretamente com a chave correta (segurança mínima)
if (($_GET['chave'] ?? '') !== 'chamatrina2026') {
    http_response_code(403);
    exit('Acesso negado. Use: teste-email.php?chave=chamatrina2026');
}

$destinatario = $_GET['para'] ?? MAIL_USUARIO;
$enviado      = null;
$erro_detalhes = '';

if (isset($_GET['enviar'])) {
    try {

        $mail = new PHPMailer(true);
        $mail->SMTPDebug  = SMTP::DEBUG_SERVER; // Captura log detalhado
        $mail->Debugoutput = function($str, $level) use (&$erro_detalhes) {
            $erro_detalhes .= htmlspecialchars($str) . "\n";
        };

        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USUARIO;
        $mail->Password   = MAIL_SENHA;
        $mail->SMTPSecure = (MAIL_PORT === 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(MAIL_USUARIO, MAIL_NOME);
        $mail->addAddress($destinatario);
        $mail->Subject = 'Teste de e-mail — Chama Trina';
        $mail->Body    = "Este é um e-mail de teste enviado pelo sistema da Fraternidade Essência da Chama Trina.\n\nSe você recebeu esta mensagem, o envio de e-mails está funcionando corretamente.\n\nchamatrina.org.br";
        $mail->isHTML(false);

        $mail->send();
        $enviado = true;
    } catch (Exception $e) {
        $enviado = false;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Teste de E-mail — ChamaTrina</title>
<style>
    body { font-family: Arial, sans-serif; background: #0f172a; color: #e2e8f0; padding: 40px; max-width: 700px; margin: 0 auto; }
    h1   { color: #8b5cf6; font-size: 20px; margin-bottom: 6px; }
    h2   { font-size: 15px; color: #94a3b8; font-weight: normal; margin-bottom: 32px; }
    .card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 24px; margin-bottom: 20px; }
    .card h3 { font-size: 13px; text-transform: uppercase; letter-spacing: .6px; color: #64748b; margin-bottom: 16px; }
    .linha { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 14px; }
    .linha:last-child { border: none; }
    .linha span:first-child { color: #94a3b8; }
    .linha span:last-child  { color: #e2e8f0; font-weight: 600; }
    .ok   { color: #4ade80 !important; }
    .erro { color: #f87171 !important; }
    form  { margin-top: 20px; }
    input[type=text] { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; color: #f1f5f9; padding: 10px 14px; font-size: 14px; width: 300px; margin-right: 8px; }
    button { background: #8b5cf6; color: #fff; border: none; border-radius: 8px; padding: 10px 20px; font-size: 14px; cursor: pointer; font-weight: 600; }
    button:hover { background: #7c3aed; }
    .log  { background: #0a0f1e; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 16px; font-size: 12px; font-family: monospace; color: #94a3b8; white-space: pre-wrap; max-height: 300px; overflow-y: auto; margin-top: 16px; }
    .aviso { background: rgba(234,179,8,0.1); border: 1px solid rgba(234,179,8,0.3); border-radius: 10px; padding: 14px 18px; font-size: 13px; color: #fbbf24; margin-top: 24px; }
</style>
</head>
<body>

<h1>Diagnóstico de E-mail</h1>
<h2>Fraternidade Essência da Chama Trina — chamatrina.org.br</h2>

<!-- Configuração atual -->
<div class="card">
    <h3>Configuração SMTP (config.php)</h3>
    <div class="linha"><span>Servidor (MAIL_HOST)</span><span><?= htmlspecialchars(MAIL_HOST) ?></span></div>
    <div class="linha"><span>Porta (MAIL_PORT)</span><span><?= MAIL_PORT ?> <?= MAIL_PORT === 465 ? '(SSL)' : '(TLS/STARTTLS)' ?></span></div>
    <div class="linha"><span>Usuário (MAIL_USUARIO)</span><span><?= htmlspecialchars(MAIL_USUARIO) ?></span></div>
    <div class="linha"><span>Senha (MAIL_SENHA)</span><span><?= str_repeat('●', min(strlen(MAIL_SENHA), 10)) ?></span></div>
    <div class="linha"><span>Nome remetente (MAIL_NOME)</span><span><?= htmlspecialchars(MAIL_NOME) ?></span></div>
</div>

<!-- Resultado do envio -->
<?php if ($enviado === true): ?>
<div class="card">
    <h3>Resultado</h3>
    <div class="linha">
        <span>Status</span>
        <span class="ok">✔ E-mail enviado com sucesso!</span>
    </div>
    <div class="linha">
        <span>Enviado para</span>
        <span><?= htmlspecialchars($destinatario) ?></span>
    </div>
    <p style="color:#94a3b8;font-size:13px;margin-top:12px;">
        Verifique a caixa de entrada (e a pasta de spam) do endereço acima. Se chegou, está tudo funcionando.
    </p>
</div>
<?php elseif ($enviado === false): ?>
<div class="card">
    <h3>Resultado</h3>
    <div class="linha">
        <span>Status</span>
        <span class="erro">✖ Falha no envio</span>
    </div>
    <?php if ($erro_detalhes): ?>
    <p style="color:#94a3b8;font-size:13px;margin-top:12px;">Log detalhado da conexão SMTP:</p>
    <div class="log"><?= $erro_detalhes ?></div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Formulário de teste -->
<div class="card">
    <h3>Enviar e-mail de teste</h3>
    <p style="font-size:13px;color:#94a3b8;margin-bottom:16px;">
        Informe o endereço que deve receber o e-mail de teste e clique em Enviar.
    </p>
    <form method="GET">
        <input type="hidden" name="chave" value="chamatrina2026">
        <input type="hidden" name="enviar" value="1">
        <input type="text" name="para"
               placeholder="seu@email.com"
               value="<?= htmlspecialchars($destinatario) ?>">
        <button type="submit">Enviar teste</button>
    </form>
</div>

<div class="aviso">
    ⚠ <strong>Atenção:</strong> apague este arquivo do servidor após concluir os testes.<br>
    Ele expõe informações de configuração e não deve ficar acessível publicamente.
</div>

</body>
</html>
