<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$catSlug = trim($_GET['cat'] ?? '');
$pagina  = max(1, (int)($_GET['p'] ?? 1));
$porPag  = 9;
$offset  = ($pagina - 1) * $porPag;

// Categoria ativa
$catAtiva   = null;
$posts      = [];
$categorias = [];
$totalPosts = 0;
$totalPags  = 1;

$wherePost  = "status = 'publicado' OR (status = 'agendado' AND publicado_em <= NOW())";
$paramsPost = [];

try {
    if ($catSlug) {
        $stmt = $pdo->prepare("SELECT * FROM blog_categorias WHERE slug = ?");
        $stmt->execute([$catSlug]);
        $catAtiva = $stmt->fetch();
    }
    if ($catAtiva) {
        $wherePost .= " AND categoria_id = ?";
        $paramsPost[] = $catAtiva['id'];
    }
} catch (\PDOException $e) { $catAtiva = null; }

try {
    $total = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE $wherePost");
    $total->execute($paramsPost);
    $totalPosts = (int)$total->fetchColumn();
    $totalPags  = max(1, (int)ceil($totalPosts / $porPag));

    $sql = "
        SELECT p.*, c.nome AS cat_nome, c.slug AS cat_slug,
               u.nome AS autor_nome, u.email AS autor_email,
               (SELECT COUNT(*) FROM blog_comentarios co
                WHERE co.post_id = p.id AND co.status = 'aprovado') AS total_comentarios
        FROM blog_posts p
        LEFT JOIN blog_categorias c ON c.id = p.categoria_id
        LEFT JOIN admin_usuarios  u ON u.id = p.autor_id
        WHERE $wherePost
        ORDER BY p.publicado_em DESC, p.criado_em DESC
        LIMIT $porPag OFFSET $offset
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($paramsPost);
    $posts = $stmt->fetchAll();
} catch (\PDOException $e) { $posts = []; }

try {
    $categorias = $pdo->query("
        SELECT c.*, COUNT(p.id) AS total
        FROM blog_categorias c
        LEFT JOIN blog_posts p ON p.categoria_id = c.id
            AND (p.status = 'publicado' OR (p.status = 'agendado' AND p.publicado_em <= NOW()))
        GROUP BY c.id HAVING total > 0 ORDER BY c.nome
    ")->fetchAll();
} catch (\PDOException $e) { $categorias = []; }

$title       = ($catAtiva ? htmlspecialchars($catAtiva['nome']) . ' — ' : '') . 'Blog — ' . SITE_NAME;
$description = 'Artigos, reflexões e notícias da Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/blog.php';
include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about">
        <h2>Blog</h2>
        <p>Artigos, reflexões e notícias da fraternidade.</p>
    </div>

    <!-- Filtro de categorias -->
    <?php if (!empty($categorias)): ?>
    <div class="eventos-filtros" style="margin-bottom:32px;">
        <a href="<?= BASE_URL ?>/blog.php"
           class="filtro-btn <?= !$catSlug ? 'ativo' : '' ?>">Todos</a>
        <?php foreach ($categorias as $c): ?>
            <a href="<?= BASE_URL ?>/blog.php?cat=<?= urlencode($c['slug']) ?>"
               class="filtro-btn <?= $catSlug === $c['slug'] ? 'ativo' : '' ?>">
                <?= htmlspecialchars($c['nome']) ?>
                <span style="opacity:.6;font-size:12px;">(<?= $c['total'] ?>)</span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Grid de posts -->
    <?php if (empty($posts)): ?>
        <div class="sem-eventos">
            <p>Nenhum post publicado<?= $catAtiva ? ' nesta categoria' : '' ?> ainda.</p>
        </div>
    <?php else: ?>
    <div class="blog-grid">
        <?php foreach ($posts as $post): ?>
        <article class="blog-card">
            <a href="<?= BASE_URL ?>/blog-post.php?slug=<?= urlencode($post['slug']) ?>" class="blog-card-img-link">
                <?php if ($post['imagem_capa']): ?>
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($post['imagem_capa']) ?>"
                         alt="<?= htmlspecialchars($post['titulo']) ?>" class="blog-card-img">
                <?php else: ?>
                    <div class="blog-card-img-placeholder"></div>
                <?php endif; ?>
            </a>
            <div class="blog-card-body">
                <?php if ($post['cat_nome']): ?>
                    <a href="<?= BASE_URL ?>/blog.php?cat=<?= urlencode($post['cat_slug']) ?>"
                       class="blog-card-cat"><?= htmlspecialchars($post['cat_nome']) ?></a>
                <?php endif; ?>
                <h3 class="blog-card-titulo">
                    <a href="<?= BASE_URL ?>/blog-post.php?slug=<?= urlencode($post['slug']) ?>">
                        <?= htmlspecialchars($post['titulo']) ?>
                    </a>
                </h3>
                <?php if ($post['resumo']): ?>
                    <p class="blog-card-resumo"><?= htmlspecialchars($post['resumo']) ?></p>
                <?php endif; ?>
                <div class="blog-card-meta">
                    <span><?= date('d/m/Y', strtotime($post['publicado_em'] ?? $post['criado_em'])) ?></span>
                    <span><?= htmlspecialchars($post['autor_nome'] ?: $post['autor_email']) ?></span>
                    <?php if ($post['total_comentarios'] > 0): ?>
                        <span><?= $post['total_comentarios'] ?> comentário(s)</span>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <!-- Paginação -->
    <?php if ($totalPags > 1): ?>
    <div class="blog-paginacao">
        <?php for ($i = 1; $i <= $totalPags; $i++): ?>
            <a href="?<?= $catSlug ? 'cat=' . urlencode($catSlug) . '&' : '' ?>p=<?= $i ?>"
               class="blog-pag-btn <?= $i === $pagina ? 'ativo' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>

</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
