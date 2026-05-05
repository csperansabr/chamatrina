<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/atendimentos.php');
    exit;
}

$id           = (int)($_POST['id']           ?? 0);
$redir        = $_POST['redir']              ?? '';
$msgConclusao = trim($_POST['msg_conclusao'] ?? '');

if (!$id || $msgConclusao === '') {
    header('Location: ' . BASE_URL . '/admin/atendimentos.php');
    exit;
}

$stmt = $pdo->prepare("SELECT nome, email FROM atendimentos WHERE id = ? AND status = 'pendente'");
$stmt->execute([$id]);
$atendimento = $stmt->fetch();

if (!$atendimento) {
    $destino = $redir === 'ver'
        ? BASE_URL . '/admin/atendimento-ver.php?id=' . $id
        : BASE_URL . '/admin/atendimentos.php';
    header('Location: ' . $destino);
    exit;
}

// Montar e-mail HTML
$logoUrl  = BASE_URL . '/img/logo.png';
$baseUrl  = BASE_URL;
$nomeHtml = htmlspecialchars($atendimento['nome']);

$paragrafos = preg_split('/\n\n+/', trim($msgConclusao));
$htmlMsg = '';
foreach ($paragrafos as $p) {
    $p = trim($p);
    if ($p !== '') {
        $htmlMsg .= '<p style="margin:0 0 20px;color:#cbd5e1;font-size:15px;line-height:1.9;font-family:Georgia,\'Times New Roman\',serif;">'
                  . nl2br(htmlspecialchars($p)) . '</p>';
    }
}

$corpoHtml = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#0f172a;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f172a;">
  <tr>
    <td align="center" style="padding:40px 16px;">
      <table role="presentation" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#1e293b;border-radius:16px;overflow:hidden;border:1px solid #3730a3;">

        <!-- Cabeçalho com logo -->
        <tr>
          <td align="center" style="padding:40px 40px 32px;background-color:#1e1b4b;background:linear-gradient(160deg,#1e1b4b 0%,#2e1065 100%);border-bottom:2px solid #4c1d95;">
            <img src="{$logoUrl}" alt="Fraternidade Essência da Chama Trina" width="110" height="auto" style="display:block;margin:0 auto 20px;border:0;">
            <p style="margin:0;color:#a78bfa;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-family:Arial,Helvetica,sans-serif;">Fraternidade Essência da Chama Trina</p>
          </td>
        </tr>

        <!-- Corpo -->
        <tr>
          <td style="padding:40px 44px 36px;">
            <p style="margin:0 0 28px;color:#e2e8f0;font-size:20px;font-weight:600;font-family:Georgia,'Times New Roman',serif;">Olá, {$nomeHtml}!</p>
            {$htmlMsg}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="border-top:1px solid #3730a3;padding-top:28px;">
                  <p style="margin:0 0 4px;color:#a78bfa;font-size:14px;font-family:Arial,Helvetica,sans-serif;">Com carinho,</p>
                  <p style="margin:0;color:#e2e8f0;font-size:15px;font-weight:600;font-family:Arial,Helvetica,sans-serif;">Equipe Chama Trina</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Rodapé -->
        <tr>
          <td style="padding:16px 44px;background-color:#0f172a;border-top:1px solid #1e293b;">
            <p style="margin:0;font-size:12px;color:#475569;text-align:center;font-family:Arial,Helvetica,sans-serif;">
              <a href="{$baseUrl}" style="color:#7c3aed;text-decoration:none;">{$baseUrl}</a>
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
HTML;

$altBody = "Olá, {$atendimento['nome']}!\n\n{$msgConclusao}\n\nCom carinho,\nFragernidade Essência da Chama Trina\n{$baseUrl}";

$assunto = 'Seu atendimento foi concluído — Fraternidade Chama Trina';

$emailEnviado = enviarEmail($atendimento['email'], $atendimento['nome'], $assunto, $corpoHtml, true, $altBody);

$pdo->prepare("
    UPDATE atendimentos
    SET status = 'concluido', data_conclusao = NOW(), msg_conclusao = ?
    WHERE id = ? AND status = 'pendente'
")->execute([$msgConclusao, $id]);

$okParam = $emailEnviado ? 'ok=1' : 'ok=nomail';

if ($redir === 'ver') {
    header('Location: ' . BASE_URL . '/admin/atendimento-ver.php?id=' . $id . '&' . $okParam);
} else {
    header('Location: ' . BASE_URL . '/admin/atendimentos.php?' . $okParam);
}
exit;
