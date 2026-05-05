<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/contato.php');
    exit;
}

$nome       = trim(strip_tags($_POST['nome']       ?? ''));
$email      = trim(strip_tags($_POST['email']      ?? ''));
$whatsapp   = trim(strip_tags($_POST['whatsapp']   ?? ''));
$mensagem   = trim(strip_tags($_POST['mensagem']   ?? ''));
$lgpdAceite = !empty($_POST['lgpd_aceite']);

if (!$nome || !$email || !$whatsapp || !$mensagem || !$lgpdAceite || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . BASE_URL . '/contato.php?status=erro');
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/includes/phpmailer/Exception.php';
require_once __DIR__ . '/includes/phpmailer/PHPMailer.php';
require_once __DIR__ . '/includes/phpmailer/SMTP.php';

$corpo = "Nome: {$nome}\nE-mail: {$email}\nWhatsApp: {$whatsapp}\n\nMensagem:\n{$mensagem}";

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = MAIL_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = MAIL_USUARIO;
    $mail->Password   = MAIL_SENHA;
    $mail->SMTPSecure = (MAIL_PORT === 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = MAIL_PORT;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom(MAIL_USUARIO, MAIL_NOME);
    $mail->addReplyTo($email, $nome);
    $mail->addAddress('contato@chamatrina.org.br', MAIL_NOME);

    $mail->Subject = "Contato via site — {$nome}";
    $mail->Body    = $corpo;
    $mail->isHTML(false);

    $mail->send();
    header('Location: ' . BASE_URL . '/contato.php?status=ok');
} catch (Exception $e) {
    error_log('[contato] ' . $mail->ErrorInfo);
    header('Location: ' . BASE_URL . '/contato.php?status=erro');
}
exit;
