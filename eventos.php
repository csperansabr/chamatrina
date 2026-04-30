<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

// Página dinâmica — impede cache de servidor e browser
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

$title       = 'Agenda — Fraternidade Chama Trina';
$description = 'Acompanhe a agenda de cerimônias, cursos e vivências da Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/eventos.php';

// Filtro por categoria
$slugFiltro = $_GET['categoria'] ?? '';

$categorias = [];
$futuros    = [];
$encerrados = [];

// Monta SQL de futuros com filtro opcional
$sql    = "SELECT e.*, c.nome AS categoria, c.slug AS cat_slug
           FROM eventos e
           JOIN categorias_eventos c ON c.id = e.categoria_id
           WHERE e.status = 'ativo' AND e.data_evento >= NOW()";
$params = [];
if ($slugFiltro) { $sql .= " AND c.slug = ?"; $params[] = $slugFiltro; }
$sql .= " ORDER BY e.data_evento ASC";

// Monta SQL de encerrados com filtro opcional
$sqlEnc    = "SELECT e.*, c.nome AS categoria
              FROM eventos e
              JOIN categorias_eventos c ON c.id = e.categoria_id
              WHERE (e.status = 'encerrado' OR (e.status = 'ativo' AND e.data_evento < NOW()))";
$paramsEnc = [];
if ($slugFiltro) { $sqlEnc .= " AND c.slug = ?"; $paramsEnc[] = $slugFiltro; }
$sqlEnc .= " ORDER BY e.data_evento DESC LIMIT 6";

// Cada query tem seu próprio try/catch — uma falha não afeta as demais
try {
    $categorias = $pdo->query(
        "SELECT DISTINCT c.nome, c.slug
         FROM categorias_eventos c
         JOIN eventos e ON e.categoria_id = c.id
         WHERE e.status = 'ativo'
         ORDER BY c.ordem, c.nome"
    )->fetchAll();
} catch (\PDOException $e) { $categorias = []; }

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $futuros = $stmt->fetchAll();
} catch (\PDOException $e) { $futuros = []; }

try {
    $stmtEnc = $pdo->prepare($sqlEnc);
    $stmtEnc->execute($paramsEnc);
    $encerrados = $stmtEnc->fetchAll();
} catch (\PDOException $e) { $encerrados = []; }

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about">
        <h2>Agenda</h2>
        <p>Acompanhe as próximas cerimônias, cursos e encontros da Fraternidade. Cada trabalho é conduzido com responsabilidade, fundamento e muito cuidado.</p>
    </div>

    <?php if ($categorias): ?>
    <div class="eventos-filtros">
        <a href="eventos.php" class="filtro-btn <?= !$slugFiltro ? 'ativo' : '' ?>">Todos</a>
        <?php foreach ($categorias as $cat): ?>
            <a href="eventos.php?categoria=<?= urlencode($cat['slug']) ?>"
               class="filtro-btn <?= $slugFiltro === $cat['slug'] ? 'ativo' : '' ?>">
                <?= htmlspecialchars($cat['nome']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($futuros): ?>
        <div class="eventos-grid">
        <?php foreach ($futuros as $e): ?>
            <div class="evento-card">
                <?php if ($e['imagem']): ?>
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($e['imagem']) ?>"
                         alt="<?= htmlspecialchars($e['titulo']) ?>"
                         onerror="this.style.display='none'">
                <?php endif; ?>
                <div class="evento-card-body">
                    <div class="evento-card-categoria"><?= htmlspecialchars($e['categoria']) ?></div>
                    <h3><?= htmlspecialchars($e['titulo']) ?></h3>
                    <?php if ($e['descricao']): ?>
                    <p class="evento-descricao" id="edesc-<?= $e['id'] ?>"><?= nl2br(htmlspecialchars($e['descricao'])) ?></p>
                    <button class="evento-ver-mais" onclick="eventoToggle(this,'edesc-<?= $e['id'] ?>')">Ler mais ↓</button>
                    <?php endif; ?>
                    <div class="evento-card-meta">
                        <span>📅 <?= date('d/m/Y', strtotime($e['data_evento'])) ?> às <?= date('H:i', strtotime($e['data_evento'])) ?>h</span>
                        <?php if ($e['local_nome']): ?>
                        <span>📍 <?= htmlspecialchars($e['local_nome']) ?></span>
                        <?php endif; ?>
                        <?php if ($e['vagas']): ?>
                        <span style="color:#fbbf24;">🎟 Vagas: <?= $e['vagas'] ?></span>
                        <?php endif; ?>
                    </div>
                    <a href="<?= WHATSAPP ?>?text=Olá,%20tenho%20interesse%20no%20evento:%20<?= urlencode($e['titulo']) ?>"
                       target="_blank" class="btn whatsapp" style="font-size:14px;padding:10px 16px;">
                        Tenho interesse
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="sem-eventos" style="padding:70px 0 50px;">
            <div style="font-size:52px;margin-bottom:20px;opacity:0.6;">🔥</div>
            <h3 style="font-size:20px;font-weight:800;margin-bottom:12px;color:var(--text);">
                A chama está sendo preparada
            </h3>
            <p style="max-width:480px;margin:0 auto 10px;color:var(--text-muted);line-height:1.7;">
                Nenhum evento está agendado para este momento — mas o fogo não se apaga.
                Novos trabalhos e cerimônias estão sendo preparados com cuidado e intenção.
            </p>
            <p style="max-width:440px;margin:0 auto 28px;color:var(--text-dim);font-size:14px;line-height:1.6;">
                Siga-nos no Instagram ou entre em contato pelo WhatsApp para ser o primeiro a saber quando a próxima vivência for aberta.
            </p>
            <div class="actions" style="justify-content:center;margin:0;">
                <a href="https://wa.me/5551992563279" target="_blank" rel="noopener" class="btn whatsapp">
                    Avisar-me pelo WhatsApp
                </a>
                <a href="https://instagram.com/fraternidadechamatrina" target="_blank" rel="noopener" class="btn btn-ghost">
                    Seguir no Instagram
                </a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($encerrados): ?>
    <div class="eventos-encerrados">
        <h3>Eventos encerrados</h3>
        <div class="eventos-grid">
        <?php foreach ($encerrados as $e): ?>
            <div class="evento-card" style="opacity:0.6;">
                <?php if ($e['imagem']): ?>
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($e['imagem']) ?>"
                         alt="<?= htmlspecialchars($e['titulo']) ?>"
                         onerror="this.style.display='none'">
                <?php endif; ?>
                <div class="evento-card-body">
                    <div class="evento-card-categoria"><?= htmlspecialchars($e['categoria']) ?></div>
                    <h3><?= htmlspecialchars($e['titulo']) ?></h3>
                    <div class="evento-card-meta">
                        <span>📅 <?= date('d/m/Y', strtotime($e['data_evento'])) ?></span>
                        <span style="color:#f87171;">Encerrado</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
// Exibe botão "Ler mais" apenas quando o texto está realmente cortado
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.evento-descricao').forEach(function (el) {
        if (el.scrollHeight > el.clientHeight + 2) {
            var btn = el.nextElementSibling;
            if (btn && btn.classList.contains('evento-ver-mais')) {
                btn.style.display = 'block';
            }
        }
    });
});

function eventoToggle(btn, id) {
    var el = document.getElementById(id);
    var expandindo = el.classList.toggle('expandida');
    btn.textContent = expandindo ? 'Ver menos ↑' : 'Ler mais ↓';
}
</script>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
