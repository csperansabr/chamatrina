<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

// Ações POST (aprovar / spam / excluir)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $cid  = (int)($_POST['id'] ?? 0);
    if ($cid) {
        if ($acao === 'aprovar') {
            $pdo->prepare("UPDATE blog_comentarios SET status = 'aprovado' WHERE id = ?")
                ->execute([$cid]);
        } elseif ($acao === 'spam') {
            $pdo->prepare("UPDATE blog_comentarios SET status = 'spam' WHERE id = ?")
                ->execute([$cid]);
        } elseif ($acao === 'excluir') {
            $pdo->prepare("DELETE FROM blog_comentarios WHERE id = ?")->execute([$cid]);
        }
    }
    $redir = 'blog-comentarios.php' . (!empty($_POST['post_id']) ? '?post=' . (int)$_POST['post_id'] : '');
    header('Location: ' . BASE_URL . '/admin/' . $redir);
    exit;
}

$postFil = (int)($_GET['post']    ?? 0);
$status  = $_GET['status']        ?? 'pendente';

$where  = ['1=1'];
$params = [];

if ($postFil) {
    $where[]  = 'c.post_id = ?';
    $params[] = $postFil;
}
if (in_array($status, ['pendente','aprovado','spam'])) {
    $where[]  = 'c.status = ?';
    $params[] = $status;
}

$sql = "
    SELECT c.*, p.titulo AS post_titulo, p.slug AS post_slug
    FROM blog_comentarios c
    JOIN blog_posts p ON p.id = c.post_id
    WHERE " . implode(' AND ', $where) . "
    ORDER BY c.criado_em DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$comentarios = $stmt->fetchAll();

$pendentes = $pdo->query("SELECT COUNT(*) FROM blog_comentarios WHERE status = 'pendente'")->fetchColumn();

adminHeader('Comentários do Blog', 'blog-comentarios');
?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <?php if ($pendentes > 0): ?>
        <span style="background:rgba(234,179,8,0.12);color:#fbbf24;padding:6px 14px;border-radius:12px;font-size:13px;">
            <?= $pendentes ?> comentário(s) aguardando moderação
        </span>
    <?php else: ?>
        <span style="color:#4ade80;font-size:13px;">Nenhum comentário pendente</span>
    <?php endif; ?>
</div>

<!-- Filtros -->
<form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
    <?php if ($postFil): ?>
        <input type="hidden" name="post" value="<?= $postFil ?>">
    <?php endif; ?>
    <select name="status" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:8px;color:#f1f5f9;padding:9px 14px;font-size:14px;">
        <option value="pendente"  <?= $status === 'pendente'  ? 'selected' : '' ?>>Pendentes</option>
        <option value="aprovado"  <?= $status === 'aprovado'  ? 'selected' : '' ?>>Aprovados</option>
        <option value="spam"      <?= $status === 'spam'      ? 'selected' : '' ?>>Spam</option>
    </select>
    <button type="submit" class="btn-action btn-edit">Filtrar</button>
    <?php if ($postFil): ?>
        <a href="blog-comentarios.php" class="btn-action" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);color:#94a3b8;text-decoration:none;display:inline-flex;align-items:center;">Ver todos os posts</a>
    <?php endif; ?>
</form>

<?php if (empty($comentarios)): ?>
    <div style="text-align:center;padding:60px 0;color:#64748b;">
        <p>Nenhum comentário encontrado com este filtro.</p>
    </div>
<?php else: ?>
    <?php foreach ($comentarios as $c): ?>
    <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:20px 24px;margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:12px;">
            <div>
                <strong style="font-size:15px;"><?= htmlspecialchars($c['nome']) ?></strong>
                <span style="color:#64748b;font-size:13px;margin-left:10px;"><?= htmlspecialchars($c['email']) ?></span>
                <span style="color:#475569;font-size:12px;margin-left:10px;"><?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?></span>
            </div>
            <div style="font-size:12px;color:#94a3b8;">
                Post: <a href="blog-post-form.php?id=<?= $c['post_id'] ?>" style="color:#8b5cf6;"><?= htmlspecialchars($c['post_titulo']) ?></a>
            </div>
        </div>

        <p style="color:#cbd5e1;font-size:14px;line-height:1.6;margin:0 0 16px;">
            <?= nl2br(htmlspecialchars($c['comentario'])) ?>
        </p>

        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <?php if ($c['status'] !== 'aprovado'): ?>
                <form method="POST">
                    <input type="hidden" name="acao"    value="aprovar">
                    <input type="hidden" name="id"      value="<?= $c['id'] ?>">
                    <input type="hidden" name="post_id" value="<?= $postFil ?>">
                    <button type="submit" class="btn-action btn-edit" style="background:rgba(34,197,94,0.1);border-color:rgba(34,197,94,0.3);color:#4ade80;">✔ Aprovar</button>
                </form>
            <?php endif; ?>
            <?php if ($c['status'] !== 'spam'): ?>
                <form method="POST">
                    <input type="hidden" name="acao"    value="spam">
                    <input type="hidden" name="id"      value="<?= $c['id'] ?>">
                    <input type="hidden" name="post_id" value="<?= $postFil ?>">
                    <button type="submit" class="btn-action" style="background:rgba(234,179,8,0.1);border-color:rgba(234,179,8,0.3);color:#fbbf24;">Marcar spam</button>
                </form>
            <?php endif; ?>
            <form method="POST" onsubmit="return confirm('Excluir este comentário?')">
                <input type="hidden" name="acao"    value="excluir">
                <input type="hidden" name="id"      value="<?= $c['id'] ?>">
                <input type="hidden" name="post_id" value="<?= $postFil ?>">
                <button type="submit" class="btn-action btn-delete">Excluir</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php adminFooter(); ?>
