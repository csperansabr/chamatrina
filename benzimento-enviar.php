<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/benzimento.php');
    exit;
}

$nome           = trim(strip_tags($_POST['nome']            ?? ''));
$email          = trim(strip_tags($_POST['email']           ?? ''));
$whatsapp       = trim(strip_tags($_POST['whatsapp']        ?? ''));
$dataNascimento = trim($_POST['data_nascimento']            ?? '');
$nomeMae        = trim(strip_tags($_POST['nome_mae']        ?? ''));
$endereco       = trim(strip_tags($_POST['endereco']        ?? ''));
$tipoAtendimento = 'Benzimento';
$intencao       = trim(strip_tags($_POST['intencao']        ?? ''));
$lgpdAceite     = !empty($_POST['lgpd_aceite']);

if (!$nome || !$email || !$whatsapp || !$dataNascimento || !$nomeMae ||
    !$endereco || !$intencao || !$lgpdAceite || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . BASE_URL . '/benzimento.php?status=erro');
    exit;
}

$dataNasc = DateTime::createFromFormat('Y-m-d', $dataNascimento);
if (!$dataNasc) {
    header('Location: ' . BASE_URL . '/benzimento.php?status=erro');
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO atendimentos
            (nome, email, whatsapp, data_nascimento, nome_mae, endereco, tipo_atendimento, intencao, status, data_solicitacao)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendente', NOW())
    ");
    $stmt->execute([$nome, $email, $whatsapp, $dataNascimento, $nomeMae, $endereco, $tipoAtendimento, $intencao]);
} catch (PDOException $e) {
    error_log('[benzimento] Erro ao salvar: ' . $e->getMessage());
    header('Location: ' . BASE_URL . '/benzimento.php?status=erro');
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/includes/phpmailer/Exception.php';
require_once __DIR__ . '/includes/phpmailer/PHPMailer.php';
require_once __DIR__ . '/includes/phpmailer/SMTP.php';

$dataNascFormatada = $dataNasc->format('d/m/Y');

$corpo  = "Nova solicitação de Benzimento Online\n";
$corpo .= "======================================\n\n";
$corpo .= "Nome: {$nome}\n";
$corpo .= "E-mail: {$email}\n";
$corpo .= "WhatsApp: {$whatsapp}\n";
$corpo .= "Data de nascimento: {$dataNascFormatada}\n";
$corpo .= "Nome da mãe: {$nomeMae}\n";
$corpo .= "Endereço: {$endereco}\n\n";
$corpo .= "Intenção / Propósito:\n{$intencao}\n\n";
$corpo .= "Data da solicitação: " . date('d/m/Y H:i') . "\n";
$corpo .= "Status: PENDENTE\n\n";
$corpo .= "Gerenciar: " . BASE_URL . "/admin/atendimentos.php\n";

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

    $mail->Subject = "Benzimento — {$nome}";
    $mail->Body    = $corpo;
    $mail->isHTML(false);

    $mail->send();
} catch (Exception $e) {
    error_log('[benzimento] Falha ao enviar e-mail: ' . $mail->ErrorInfo);
}

header('Location: ' . BASE_URL . '/benzimento.php?status=ok');
exit;
