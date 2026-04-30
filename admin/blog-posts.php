<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$busca  = trim($_GET['busca']  ?? '');
$catFil = (int)($_GET['cat']   ?? 0);
$status = $_GET['status']      ?? '';

$where  = ['1=1'];
$params = [];

if ($busca) {
    $where[]  = 'p.titulo LIKE ?';
    $params[] = "%$busca%";
}
if ($catFil) {
    $where[]  = 'p.categoria_id = ?';
    $params[] = $catFil;
}
if (in_array($status, ['rascunho','publicado','agendado'])) {
    $where[]  = 'p.status = ?';
    $params[] = $status;
}

$sql = "
    SELECT p.*, c.nome AS categoria_nome, u.nome AS autor_nome, u.email AS autor_email,
           (SELECT COUNT(*) FROM blog_comentarios co WHERE co.post_id = p.id AND co.status = 'pendente') AS pendentes
    FROM blog_posts p
    LEFT JOIN blog_categorias c ON c.id = p.categoria_id
    LEFT JOIN admin_usuarios  u ON u.id = p.autor_id
    WHERE " . implode(' AND ', $where) . "
    ORDER BY p.criado_em DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

$categorias = $pdo->query("SELECT * FROM blog_categorias ORDER BY nome")->fetchAll();

adminHeader('Posts do Blog', 'blog-posts');
?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <p style="color:#94a3b8;margin:0;"><?= count($posts) ?> post(s) encontrado(s)</p>
    <a href="blog-post-form.php" class="btn-admin btn-primary">+ Novo post</a>
</div>

<!-- Filtros -->
<form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
    <input type="text" name="busca" placeholder="Buscar por título…"
           value="<?= htmlspecialchars($busca) ?>"
           style="flex:1;min-width:200px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:8px;color:#f1f5f9;padding:9px 14px;font-size:14px;">
    <select name="cat" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:8px;color:#f1f5f9;padding:9px 14px;font-size:14px;">
        <option value="">Todas as categorias</option>
        <?php foreach ($categorias as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $catFil == $c['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="status" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:8px;color:#f1f5f9;padding:9px 14px;font-size:14px;">
        <option value="">Todos os status</option>
        <option value="publicado" <?= $status === 'publicado' ? 'selected' : '' ?>>Publicado</option>
        <option value="rascunho"  <?= $status === 'rascunho'  ? 'selected' : '' ?>>Rascunho</option>
        <option value="agendado"  <?= $status === 'agendado'  ? 'selected' : '' ?>>Agendado</option>
    </select>
    <button type="submit" class="btn-action btn-edit">Filtrar</button>
    <?php if ($busca || $catFil || $status): ?>
        <a href="blog-posts.php" class="btn-action" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);color:#94a3b8;text-decoration:none;display:inline-flex;align-items:center;">Limpar</a>
    <?php endif; ?>
</form>

<?php if (empty($posts)): ?>
    <div style="text-align:center;padding:60px 0;color:#64748b;">
        <p>Nenhum post encontrado. <a href="blog-post-form.php" style="color:#8b5cf6;">Criar o primeiro post</a>.</p>
    </div>
<?php else: ?>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoria</th>
                <th>Autor</th>
                <th>Status</th>
                <th>Publicado em</th>
                <th>Coment.</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $p): ?>
        <tr>
            <td>
                <strong style="font-size:14px;"><?= htmlspecialchars($p['titulo']) ?></strong>
                <?php if ($p['imagem_capa']): ?>
                    <span style="font-size:11px;color:#64748b;display:block;">📷 com imagem</span>
                <?php endif; ?>
            </td>
            <td style="color:#94a3b8;font-size:13px;"><?= htmlspecialchars($p['categoria_nome'] ?? '—') ?></td>
            <td style="color:#94a3b8;font-size:13px;">
                <?= htmlspecialchars($p['autor_nome'] ?: $p['autor_email']) ?>
            </td>
            <td>
                <?php
                $badges = [
                    'publicado' => ['#4ade80','rgba(34,197,94,0.12)','Publicado'],
                    'rascunho'  => ['#94a3b8','rgba(148,163,184,0.12)','Rascunho'],
                    'agendado'  => ['#fbbf24','rgba(234,179,8,0.12)','Agendado'],
                ];
                [$cor,$bg,$label] = $badges[$p['status']] ?? ['#94a3b8','rgba(0,0,0,0.1)','—'];
                ?>
                <span style="background:<?= $bg ?>;color:<?= $cor ?>;padding:3px 10px;border-radius:12px;font-size:12px;">
                    <?= $label ?>
                </span>
            </td>
            <td style="color:#94a3b8;font-size:13px;">
                <?= $p['publicado_em'] ? date('d/m/Y H:i', strtotime($p['publicado_em'])) : '—' ?>
            </td>
            <td>
                <a href="blog-comentarios.php?post=<?= $p['id'] ?>" style="color:#94a3b8;font-size:13px;text-decoration:none;">
                    <?php if ($p['pendentes'] > 0): ?>
                        <span style="color:#fbbf24;font-weight:700;"><?= $p['pendentes'] ?> pend.</span>
                    <?php else: ?>
                        ver
                    <?php endif; ?>
                </a>
            </td>
            <td style="display:flex;gap:6px;flex-wrap:wrap;">
                <?php if ($p['status'] === 'publicado'): ?>
                    <a href="<?= BASE_URL ?>/blog-post.php?slug=<?= urlencode($p['slug']) ?>"
                       target="_blank" class="btn-action" style="background:rgba(34,197,94,0.1);border-color:rgba(34,197,94,0.3);color:#4ade80;text-decoration:none;">Ver</a>
                <?php endif; ?>
                <a href="blog-post-form.php?id=<?= $p['id'] ?>" class="btn-action btn-edit">Editar</a>
                <form method="POST" action="blog-post-excluir.php" onsubmit="return confirm('Excluir este post permanentemente?')">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button type="submit" class="btn-action btn-delete">Excluir</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php adminFooter(); ?>
