<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

// Próximos eventos em destaque (até 3)
$eventos = $pdo->query("
    SELECT e.titulo, e.data_evento, c.nome AS categoria
    FROM eventos e
    LEFT JOIN categorias_eventos c ON c.id = e.categoria_id
    WHERE e.status = 'ativo' AND e.data_evento >= NOW()
    ORDER BY e.data_evento ASC
    LIMIT 3
")->fetchAll();

$title       = 'Fraternidade Essência da Chama Trina';
$description = 'Rituais e vivências com as Medicinas da Floresta, Umbanda e práticas xamânicas com fundamento e responsabilidade em Canoas/RS.';
$url         = BASE_URL . '/';
include __DIR__ . '/includes/layout-top.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-stars"></div>

    <div class="hero-content">
        <img src="<?= BASE_URL ?>/img/logo.png" alt="Fraternidade Chama Trina" class="hero-logo">

        <h1>Fraternidade Essência da<br><span class="gradient-text">Chama Trina</span></h1>

        <p>Um caminho de consciência, desenvolvimento e espiritualidade com fundamento — unindo Umbanda e Medicinas da Floresta em Canoas/RS.</p>

        <div class="hero-ctas">
            <a class="btn whatsapp" href="https://wa.me/5551992563279" target="_blank" rel="noopener">
                <img src="<?= BASE_URL ?>/img/whatsapp.png" class="icon" alt=""> Falar no WhatsApp
            </a>
            <a class="btn btn-ghost" href="<?= BASE_URL ?>/sobre.php">
                Conheça a fraternidade
            </a>
        </div>
    </div>

    <div class="hero-scroll" aria-hidden="true">Role</div>
</section>

<!-- PILARES -->
<div class="container">

    <div class="about" style="text-align:center;max-width:680px;margin:60px auto 50px;">
        <h2>Três chamas, um caminho</h2>
        <p>A Fraternidade é movida pela integração entre espiritualidade, cura e autoconhecimento. Cada trabalho é conduzido com responsabilidade, ética e profundo respeito pelas tradições.</p>
    </div>

    <div class="cards-grid">
        <div class="card">
            <div class="card-icon">🌿</div>
            <h3>Medicinas da Floresta</h3>
            <p>Cerimônias com Ayahuasca, Rapé, Tabaco e Sananga — integradas à Umbanda e conduzidas com rigor e cuidado.</p>
        </div>
        <div class="card">
            <div class="card-icon">🕯️</div>
            <h3>Umbanda</h3>
            <p>Vivências, giras, passes e atendimentos espirituais dentro da tradição umbandista com fundamento e seriedade.</p>
        </div>
        <div class="card">
            <div class="card-icon">✨</div>
            <h3>Desenvolvimento Espiritual</h3>
            <p>Cursos, workshops e círculos de estudo para quem busca ampliar a consciência e aprofundar a caminhada espiritual.</p>
        </div>
    </div>

    <!-- CTA DESTAQUE -->
    <div class="cta-destaque">
        <h2>Leve a vivência da Chama Trina até o seu espaço</h2>
        <p>
            Se você sente o chamado para vivenciar um trabalho espiritual com fundamento, consciência e responsabilidade, existe a possibilidade de levar essa experiência até você.
        </p>
        <p style="margin-bottom:28px;">Entre em contato e compreenda como esse caminho pode acontecer.</p>
        <a class="btn btn-primary" href="<?= BASE_URL ?>/contato.php">Entrar em contato</a>
    </div>

    <!-- PRÓXIMOS EVENTOS -->
    <?php if (!empty($eventos)): ?>
    <div class="section">
        <h2 style="font-size:22px;font-weight:800;margin-bottom:8px;">Próximos eventos</h2>
        <p class="lead">Veja o que está por vir e garanta sua participação.</p>
        <div class="eventos-grid">
            <?php foreach ($eventos as $ev): ?>
            <div class="evento-card">
                <div class="evento-card-placeholder">🔥</div>
                <div class="evento-card-body">
                    <span class="evento-card-categoria"><?= htmlspecialchars($ev['categoria'] ?? 'Evento') ?></span>
                    <h3><?= htmlspecialchars($ev['titulo']) ?></h3>
                    <div class="evento-card-meta">
                        <span>📅 <?= date('d/m/Y H:i', strtotime($ev['data_evento'])) ?></span>
                    </div>
                    <a href="<?= BASE_URL ?>/eventos.php" class="btn whatsapp" style="font-size:13px;padding:9px 18px;">
                        Ver detalhes
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <p style="text-align:center;margin-top:10px;">
            <a href="<?= BASE_URL ?>/eventos.php" class="link-destaque">Ver todos os eventos →</a>
        </p>
    </div>
    <?php endif; ?>

    <!-- LINKS RÁPIDOS -->
    <div class="actions" style="margin:50px 0 20px;">
        <a class="btn btn-primary" href="<?= BASE_URL ?>/eventos.php">Ver eventos</a>
        <a class="btn btn-ghost" href="<?= BASE_URL ?>/blog.php">Ler o blog</a>
        <a class="btn whatsapp" href="https://instagram.com/fraternidadechamatrina" target="_blank" rel="noopener">
            <img src="<?= BASE_URL ?>/img/instagram.png" class="icon" alt=""> Instagram
        </a>
    </div>

</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
