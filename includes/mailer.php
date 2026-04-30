<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

/**
 * Envia um e-mail via SMTP autenticado (HostGator).
 *
 * @param string $para     E-mail do destinatário
 * @param string $nome     Nome do destinatário
 * @param string $assunto  Assunto da mensagem
 * @param string $corpo    Corpo em texto puro
 * @return bool            true = enviado | false = falhou
 */
function enviarEmail(string $para, string $nome, string $assunto, string $corpo): bool
{
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
        $mail->addAddress($para, $nome);

        $mail->Subject = $assunto;
        $mail->Body    = $corpo;
        $mail->isHTML(false);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('[mailer] Falha ao enviar para ' . $para . ': ' . $mail->ErrorInfo);
        return false;
    }
}
