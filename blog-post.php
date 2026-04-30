<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$slug = trim($_GET['slug'] ?? '');
if (!$slug) {
    header('Location: ' . BASE_URL . '/blog.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, c.nome AS cat_nome, c.slug AS cat_slug,
           u.nome AS autor_nome, u.email AS autor_email
    FROM blog_posts p
    LEFT JOIN blog_categorias c ON c.id = p.categoria_id
    LEFT JOIN admin_usuarios  u ON u.id = p.autor_id
    WHERE p.slug = ?
      AND (p.status = 'publicado' OR (p.status = 'agendado' AND p.publicado_em <= NOW()))
    LIMIT 1
");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header('HTTP/1.0 404 Not Found');
    header('Location: ' . BASE_URL . '/blog.php');
    exit;
}

// Comentários aprovados
$comentarios = $pdo->prepare("
    SELECT * FROM blog_comentarios
    WHERE post_id = ? AND status = 'aprovado'
    ORDER BY criado_em ASC
");
$comentarios->execute([$post['id']]);
$comentarios = $comentarios->fetchAll();

// Msg de comentário enviado
$msgComentario = $_GET['comentario'] ?? '';

// Posts relacionados (mesma categoria, exceto o atual)
$relacionados = [];
if ($post['categoria_id']) {
    $rel = $pdo->prepare("
        SELECT id, titulo, slug, imagem_capa, publicado_em
        FROM blog_posts
        WHERE categoria_id = ? AND id != ?
          AND (status = 'publicado' OR (status = 'agendado' AND publicado_em <= NOW()))
        ORDER BY publicado_em DESC LIMIT 3
    ");
    $rel->execute([$post['categoria_id'], $post['id']]);
    $relacionados = $rel->fetchAll();
}

$dataPost    = $post['publicado_em'] ?? $post['criado_em'];
$autorExibir = $post['autor_nome'] ?: $post['autor_email'];

$title       = htmlspecialchars($post['titulo']) . ' — Blog — ' . SITE_NAME;
$description = $post['resumo'] ? htmlspecialchars($post['resumo']) : 'Leia no blog da Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/blog-post.php?slug=' . urlencode($slug);
include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">
<div class="blog-post-wrap">

    <!-- Breadcrumb -->
    <nav class="blog-breadcrumb">
        <a href="<?= BASE_URL ?>/blog.php">Blog</a>
        <?php if ($post['cat_nome']): ?>
            <span>›</span>
            <a href="<?= BASE_URL ?>/blog.php?cat=<?= urlencode($post['cat_slug']) ?>">
                <?= htmlspecialchars($post['cat_nome']) ?>
            </a>
        <?php endif; ?>
        <span>›</span>
        <span><?= htmlspecialchars($post['titulo']) ?></span>
    </nav>

    <!-- Imagem de capa -->
    <?php if ($post['imagem_capa']): ?>
        <img src="<?= BASE_URL . '/' . htmlspecialchars($post['imagem_capa']) ?>"
             alt="<?= htmlspecialchars($post['titulo']) ?>"
             class="blog-post-capa">
    <?php endif; ?>

    <!-- Cabeçalho do post -->
    <header class="blog-post-header">
        <?php if ($post['cat_nome']): ?>
            <a href="<?= BASE_URL ?>/blog.php?cat=<?= urlencode($post['cat_slug']) ?>"
               class="blog-card-cat"><?= htmlspecialchars($post['cat_nome']) ?></a>
        <?php endif; ?>
        <h1 class="blog-post-titulo"><?= htmlspecialchars($post['titulo']) ?></h1>
        <div class="blog-post-meta">
            <span>Por <?= htmlspecialchars($autorExibir) ?></span>
            <span>·</span>
            <span><?= date('d \d\e F \d\e Y', strtotime($dataPost)) ?></span>
            <span>·</span>
            <span><?= count($comentarios) ?> comentário(s)</span>
        </div>
    </header>

    <!-- Conteúdo -->
    <div class="blog-post-conteudo">
        <?= $post['conteudo'] ?>
    </div>

    <!-- Compartilhar -->
    <div class="blog-post-compartilhar">
        <span>Compartilhar:</span>
        <a href="https://wa.me/?text=<?= urlencode($post['titulo'] . ' — ' . $url) ?>"
           target="_blank" rel="noopener">WhatsApp</a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($url) ?>"
           target="_blank" rel="noopener">Facebook</a>
    </div>

    <!-- Comentários -->
    <section class="blog-comentarios" id="comentarios">
        <h2 class="blog-comentarios-titulo">
            <?= count($comentarios) ?> comentário<?= count($comentarios) !== 1 ? 's' : '' ?>
        </h2>

        <?php if ($msgComentario === 'ok'): ?>
            <div class="anamnese-alerta anamnese-alerta-ok" style="margin-bottom:20px;">
                Comentário enviado! Ele ficará visível após aprovação.
            </div>
        <?php elseif ($msgComentario === 'erro'): ?>
            <div class="anamnese-alerta anamnese-alerta-erro" style="margin-bottom:20px;">
                Erro ao enviar o comentário. Tente novamente.
            </div>
        <?php endif; ?>

        <!-- Lista de comentários -->
        <?php foreach ($comentarios as $c): ?>
        <div class="blog-comentario">
            <div class="blog-comentario-autor">
                <strong><?= htmlspecialchars($c['nome']) ?></strong>
                <span><?= date('d/m/Y \à\s H:i', strtotime($c['criado_em'])) ?></span>
            </div>
            <p><?= nl2br(htmlspecialchars($c['comentario'])) ?></p>
        </div>
        <?php endforeach; ?>

        <!-- Formulário de comentário -->
        <div class="blog-comentario-form">
            <h3>Deixe um comentário</h3>
            <p style="color:#94a3b8;font-size:14px;margin-bottom:20px;">
                Seu comentário será publicado após aprovação.
            </p>
            <form method="POST" action="<?= BASE_URL ?>/blog-comentario.php">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <input type="hidden" name="slug"    value="<?= htmlspecialchars($slug) ?>">
                <div class="campos-grid">
                    <div class="campo">
                        <label>Nome *</label>
                        <input type="text" name="nome" required>
                    </div>
                    <div class="campo">
                        <label>E-mail * <span style="color:#64748b;font-size:12px;">(não será exibido)</span></label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                <div class="campo" style="margin-top:12px;">
                    <label>Comentário *</label>
                    <textarea name="comentario" required rows="5" placeholder="Escreva seu comentário…"></textarea>
                </div>
                <button type="submit" class="btn whatsapp" style="margin-top:16px;display:inline-flex;">
                    Enviar comentário
                </button>
            </form>
        </div>
    </section>

    <!-- Posts relacionados -->
    <?php if (!empty($relacionados)): ?>
    <section style="margin-top:50px;">
        <h2 style="font-size:18px;margin-bottom:24px;">Posts relacionados</h2>
        <div class="blog-grid" style="grid-template-columns:repeat(auto-fill,minmax(240px,1fr));">
            <?php foreach ($relacionados as $r): ?>
            <article class="blog-card">
                <a href="<?= BASE_URL ?>/blog-post.php?slug=<?= urlencode($r['slug']) ?>" class="blog-card-img-link">
                    <?php if ($r['imagem_capa']): ?>
                        <img src="<?= BASE_URL . '/' . htmlspecialchars($r['imagem_capa']) ?>"
                             alt="<?= htmlspecialchars($r['titulo']) ?>" class="blog-card-img">
                    <?php else: ?>
                        <div class="blog-card-img-placeholder"></div>
                    <?php endif; ?>
                </a>
                <div class="blog-card-body">
                    <h3 class="blog-card-titulo" style="font-size:15px;">
                        <a href="<?= BASE_URL ?>/blog-post.php?slug=<?= urlencode($r['slug']) ?>">
                            <?= htmlspecialchars($r['titulo']) ?>
                        </a>
                    </h3>
                    <div class="blog-card-meta">
                        <span><?= date('d/m/Y', strtotime($r['publicado_em'] ?? $r['criado_em'])) ?></span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</div>
</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
