<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$title       = 'Eventos e Vivências';
$description = 'Próximos eventos, cerimônias, cursos e vivências da Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/eventos.php';

// Filtro por categoria
$slugFiltro = $_GET['categoria'] ?? '';

// Categorias com ao menos um evento ativo
$categorias = $pdo->query(
    "SELECT DISTINCT c.nome, c.slug
     FROM categorias_eventos c
     JOIN eventos e ON e.categoria_id = c.id
     WHERE e.status = 'ativo'
     ORDER BY c.ordem, c.nome"
)->fetchAll();

// Buscar eventos futuros
$sql    = "SELECT e.*, c.nome AS categoria, c.slug AS cat_slug
           FROM eventos e
           JOIN categorias_eventos c ON c.id = e.categoria_id
           WHERE e.status = 'ativo' AND e.data_evento >= NOW()";
$params = [];

if ($slugFiltro) {
    $sql    .= " AND c.slug = ?";
    $params[] = $slugFiltro;
}

$sql .= " ORDER BY e.data_evento ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$futuros = $stmt->fetchAll();

// Buscar eventos encerrados (últimos 6)
$sqlEnc = "SELECT e.*, c.nome AS categoria
           FROM eventos e
           JOIN categorias_eventos c ON c.id = e.categoria_id
           WHERE (e.status = 'encerrado' OR (e.status = 'ativo' AND e.data_evento < NOW()))";
$paramsEnc = [];
if ($slugFiltro) {
    $sqlEnc    .= " AND c.slug = ?";
    $paramsEnc[] = $slugFiltro;
}
$sqlEnc .= " ORDER BY e.data_evento DESC LIMIT 6";
$stmtEnc = $pdo->prepare($sqlEnc);
$stmtEnc->execute($paramsEnc);
$encerrados = $stmtEnc->fetchAll();

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about">
        <h2>Eventos e Vivências</h2>
        <p>Acompanhe nossa agenda de cerimônias, cursos e encontros. Cada evento é conduzido com responsabilidade, fundamento e muito cuidado.</p>
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
                    <div class="evento-card-titulo"><?= htmlspecialchars($e['titulo']) ?></div>
                    <div class="evento-card-data">
                        📅 <?= date('d \d\e F \d\e Y \à\s H:i', strtotime($e['data_evento'])) ?>h
                    </div>
                    <?php if ($e['local_nome']): ?>
                    <div class="evento-card-local">📍 <?= htmlspecialchars($e['local_nome']) ?></div>
                    <?php endif; ?>
                    <?php if ($e['descricao']): ?>
                    <div class="evento-card-descricao"><?= nl2br(htmlspecialchars($e['descricao'])) ?></div>
                    <?php endif; ?>
                    <?php if ($e['vagas']): ?>
                    <div style="font-size:13px;color:#fbbf24;margin-bottom:12px;">
                        Vagas limitadas: <?= $e['vagas'] ?>
                    </div>
                    <?php endif; ?>
                    <a href="<?= WHATSAPP ?>?text=Olá,%20tenho%20interesse%20no%20evento:%20<?= urlencode($e['titulo']) ?>"
                       target="_blank" class="btn whatsapp" style="font-size:14px;padding:10px 16px;display:inline-flex;">
                        Tenho interesse
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="sem-eventos">
            <p>Nenhum evento programado no momento.</p>
            <p style="margin-top:10px;font-size:14px;">
                Fique atento às novidades ou
                <a href="contato.php" style="color:#25D366;">entre em contato</a> para saber mais.
            </p>
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
                    <div class="evento-card-titulo"><?= htmlspecialchars($e['titulo']) ?></div>
                    <div class="evento-card-data">
                        <?= date('d/m/Y', strtotime($e['data_evento'])) ?>
                    </div>
                    <span style="font-size:12px;color:#f87171;">Encerrado</span>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
