<?php
// Uso: include este arquivo passando $paginaAtiva = 'eventos' | 'categorias' | 'dashboard'
// Chame adminHeader($titulo, $paginaAtiva) para abrir e adminFooter() para fechar.

function adminHeader(string $titulo, string $paginaAtiva = ''): void {
    $base = defined('BASE_URL') ? BASE_URL : '';
    $nav  = [
        'dashboard'   => ['label' => 'Dashboard',    'url' => $base . '/admin/'],
        'eventos'     => ['label' => 'Eventos',       'url' => $base . '/admin/eventos.php'],
        'categorias'  => ['label' => 'Categorias',    'url' => $base . '/admin/categorias.php'],
        'blog-posts'       => ['label' => 'Blog — Posts',       'url' => $base . '/admin/blog-posts.php'],
        'blog-categorias'  => ['label' => 'Blog — Categorias',  'url' => $base . '/admin/blog-categorias.php'],
        'blog-comentarios' => ['label' => 'Blog — Comentários', 'url' => $base . '/admin/blog-comentarios.php'],
        'anamneses'        => ['label' => 'Anamneses',          'url' => $base . '/admin/anamneses.php'],
        'atendimentos'     => ['label' => 'Atendimentos',        'url' => $base . '/admin/atendimentos.php'],
        'rotinas'          => ['label' => 'Rotinas',             'url' => $base . '/admin/rotinas.php'],
    ];
    ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($titulo) ?> — Admin ChamaTrina</title>
<link rel="icon" type="image/x-icon" href="/img/ico/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="/img/ico/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/img/ico/favicon-16x16.png">
<link rel="stylesheet" href="/admin/css/admin.css">
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="<?= $base ?>/img/logo.png" alt="ChamaTrina">
        <span>Painel Administrativo</span>
    </div>
    <nav>
        <?php foreach ($nav as $key => $item): ?>
        <a href="<?= $item['url'] ?>" class="<?= $paginaAtiva === $key ? 'ativo' : '' ?>">
            <?= $item['label'] ?>
        </a>
        <?php endforeach; ?>
        <a href="<?= $base ?>" target="_blank" style="margin-top:10px;">↗ Ver site</a>
    </nav>
    <div class="sidebar-bottom">
        <a href="<?= $base ?>/admin/logout.php">Sair</a>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <h1><?= htmlspecialchars($titulo) ?></h1>
    </div>
    <div class="content">
    <?php
}

function adminFooter(): void {
    ?>
    </div><!-- .content -->
</div><!-- .main -->
</body>
</html>
    <?php
}
