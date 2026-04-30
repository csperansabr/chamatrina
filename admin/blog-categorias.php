<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$erro = '';
$ok   = '';

// Ações POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $nome = trim($_POST['nome'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $id   = (int)($_POST['id'] ?? 0);

    if (in_array($acao, ['criar', 'editar'])) {
        if (!$nome || !$slug) {
            $erro = 'Nome e slug são obrigatórios.';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            $erro = 'Slug inválido. Use apenas letras minúsculas, números e hífens.';
        } else {
            try {
                if ($acao === 'criar') {
                    $pdo->prepare("INSERT INTO blog_categorias (nome, slug) VALUES (?, ?)")
                        ->execute([$nome, $slug]);
                    $ok = 'Categoria criada com sucesso.';
                } else {
                    $pdo->prepare("UPDATE blog_categorias SET nome = ?, slug = ? WHERE id = ?")
                        ->execute([$nome, $slug, $id]);
                    $ok = 'Categoria atualizada.';
                }
            } catch (PDOException $e) {
                $erro = 'Slug já em uso por outra categoria.';
            }
        }
    }

    if ($acao === 'excluir' && $id) {
        $uso = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE categoria_id = ?");
        $uso->execute([$id]);
        if ($uso->fetchColumn() > 0) {
            $erro = 'Não é possível excluir: há posts vinculados a esta categoria.';
        } else {
            $pdo->prepare("DELETE FROM blog_categorias WHERE id = ?")->execute([$id]);
            $ok = 'Categoria excluída.';
        }
    }
}

$categorias = $pdo->query("SELECT * FROM blog_categorias ORDER BY nome")->fetchAll();
$editar     = null;
if (isset($_GET['editar'])) {
    foreach ($categorias as $c) {
        if ($c['id'] == (int)$_GET['editar']) { $editar = $c; break; }
    }
}

adminHeader('Categorias do Blog', 'blog-categorias');
?>

<?php if ($erro): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>
<?php if ($ok): ?>
    <div class="alert alert-success"><?= htmlspecialchars($ok) ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;">

    <!-- Lista -->
    <div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Nome</th><th>Slug</th><th>Posts</th><th>Ações</th></tr>
                </thead>
                <tbody>
                <?php if (empty($categorias)): ?>
                    <tr><td colspan="4" style="color:#64748b;text-align:center;padding:30px;">Nenhuma categoria cadastrada.</td></tr>
                <?php else: ?>
                    <?php foreach ($categorias as $c):
                        $total = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE categoria_id = ?");
                        $total->execute([$c['id']]);
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nome']) ?></td>
                        <td><code style="font-size:12px;color:#94a3b8;"><?= htmlspecialchars($c['slug']) ?></code></td>
                        <td><?= $total->fetchColumn() ?></td>
                        <td style="display:flex;gap:6px;">
                            <a href="?editar=<?= $c['id'] ?>" class="btn-action btn-edit">Editar</a>
                            <form method="POST" onsubmit="return confirm('Excluir esta categoria?')">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id"   value="<?= $c['id'] ?>">
                                <button type="submit" class="btn-action btn-delete">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Formulário -->
    <div class="form-card">
        <h3 style="margin-bottom:18px;font-size:15px;">
            <?= $editar ? 'Editar categoria' : 'Nova categoria' ?>
        </h3>
        <form method="POST">
            <input type="hidden" name="acao" value="<?= $editar ? 'editar' : 'criar' ?>">
            <?php if ($editar): ?>
                <input type="hidden" name="id" value="<?= $editar['id'] ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" required
                       value="<?= htmlspecialchars($editar['nome'] ?? '') ?>"
                       id="input-nome">
            </div>
            <div class="form-group">
                <label>Slug <span style="color:#64748b;font-size:12px;">(gerado automaticamente)</span></label>
                <input type="text" name="slug" required
                       value="<?= htmlspecialchars($editar['slug'] ?? '') ?>"
                       id="input-slug" pattern="[a-z0-9-]+">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-admin btn-primary">
                    <?= $editar ? 'Salvar' : 'Criar categoria' ?>
                </button>
                <?php if ($editar): ?>
                    <a href="blog-categorias.php" class="btn-admin btn-secondary">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

</div>

<script>
const nome = document.getElementById('input-nome');
const slug = document.getElementById('input-slug');
nome.addEventListener('input', function () {
    if (<?= $editar ? 'false' : 'true' ?>) {
        slug.value = this.value.toLowerCase()
            .normalize('NFD').replace(/[̀-ͯ]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
    }
});
</script>

<?php adminFooter(); ?>
