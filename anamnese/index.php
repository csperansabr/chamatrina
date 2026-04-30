<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$id = $_SESSION['participante_id'];

// Buscar dados do participante
$stmt = $pdo->prepare("SELECT * FROM participantes WHERE id = ?");
$stmt->execute([$id]);
$participante = $stmt->fetch();

// Buscar ficha
$stmt = $pdo->prepare("SELECT status, atualizado_em FROM anamneses WHERE participante_id = ?");
$stmt->execute([$id]);
$ficha = $stmt->fetch();

$title       = 'Minha Ficha — Anamnese';
$description = 'Área do participante — Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/anamnese/';
include __DIR__ . '/../includes/layout-top.php';
?>

<div class="container">
    <div class="about">
        <h2>Olá, <?= htmlspecialchars(explode(' ', $participante['nome'])[0]) ?></h2>
        <p>Bem-vindo(a) à sua área de participante da Fraternidade Essência da Chama Trina.</p>
    </div>

    <div class="form-box">
        <?php if (!$ficha): ?>
            <h2>Sua ficha ainda não foi preenchida</h2>
            <p>A ficha de anamnese é obrigatória para participar de cerimônias com Medicinas da Floresta. Preencha com atenção e honestidade — todas as informações são confidenciais.</p>
            <a href="<?= BASE_URL ?>/anamnese/ficha.php" class="btn whatsapp" style="display:inline-flex;margin-top:10px;">
                Preencher ficha agora
            </a>
        <?php elseif ($ficha['status'] === 'incompleto'): ?>
            <h2>Sua ficha está incompleta</h2>
            <p>Você iniciou o preenchimento mas ainda não aceitou o Termo de Responsabilidade. Continue de onde parou.</p>
            <p style="font-size:13px;color:#aaa;margin-top:8px;">
                Última atualização: <?= date('d/m/Y \à\s H:i', strtotime($ficha['atualizado_em'])) ?>
            </p>
            <a href="<?= BASE_URL ?>/anamnese/ficha.php" class="btn whatsapp" style="display:inline-flex;margin-top:10px;">
                Continuar preenchimento
            </a>
        <?php else: ?>
            <h2>Ficha completa</h2>
            <p>Sua ficha de anamnese está preenchida e enviada. Você pode atualizá-la a qualquer momento.</p>
            <p style="font-size:13px;color:#aaa;margin-top:8px;">
                Última atualização: <?= date('d/m/Y \à\s H:i', strtotime($ficha['atualizado_em'])) ?>
            </p>
            <a href="<?= BASE_URL ?>/anamnese/ficha.php" class="btn" style="display:inline-flex;margin-top:10px;background:rgba(255,255,255,0.1);">
                Ver / Editar ficha
            </a>
        <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:30px;">
        <a href="<?= BASE_URL ?>/anamnese/logout.php" style="color:#aaa;font-size:14px;">Sair da conta</a>
    </div>
</div>

<?php include __DIR__ . '/../includes/layout-bottom.php'; ?>
