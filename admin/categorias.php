<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$erro = '';
$msg  = $_GET['msg'] ?? '';

// Salvar nova categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $slug  = trim($_POST['slug'] ?? '');
    $ordem = (int) ($_POST['ordem'] ?? 0);

    if ($nome && $slug) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO categorias_eventos (nome, slug, ordem) VALUES (?, ?, ?)"
            );
            $stmt->execute([$nome, $slug, $ordem]);
            header('Location: ' . BASE_URL . '/admin/categorias.php?msg=salvo');
            exit;
        } catch (PDOException $e) {
            $erro = 'Slug já existe. Use um identificador único.';
        }
    } else {
        $erro = 'Preencha o nome e o identificador.';
    }
}

// Excluir
if (isset($_GET['excluir'])) {
    $delId = (int) $_GET['excluir'];
    $uso   = $pdo->prepare("SELECT COUNT(*) FROM eventos WHERE categoria_id = ?");
    $uso->execute([$delId]);
    if ($uso->fetchColumn() > 0) {
        $erro = 'Não é possível excluir: existem eventos nesta categoria.';
    } else {
        $pdo->prepare("DELETE FROM categorias_eventos WHERE id = ?")->execute([$delId]);
        header('Location: ' . BASE_URL . '/admin/categorias.php?msg=excluido');
        exit;
    }
}

$categorias = $pdo->query(
    "SELECT c.*, (SELECT COUNT(*) FROM eventos e WHERE e.categoria_id = c.id) AS total
     FROM categorias_eventos c ORDER BY c.ordem, c.nome"
)->fetchAll();

adminHeader('Categorias', 'categorias');
?>

<?php if ($msg === 'salvo'): ?>
    <div class="alert alert-success">Categoria salva.</div>
<?php elseif ($msg === 'excluido'): ?>
    <div class="alert alert-success">Categoria excluída.</div>
<?php endif; ?>

<?php if ($erro): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 340px;gap:25px;align-items:start;">

    <div class="table-box">
        <div class="table-header"><h2>Categorias cadastradas</h2></div>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Identificador</th>
                    <th>Ordem</th>
                    <th>Eventos</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($categorias as $cat): ?>
                <tr>
                    <td><?= htmlspecialchars($cat['nome']) ?></td>
                    <td style="color:#64748b;font-size:12px;"><?= htmlspecialchars($cat['slug']) ?></td>
                    <td><?= $cat['ordem'] ?></td>
                    <td><?= $cat['total'] ?></td>
                    <td>
                        <?php if ($cat['total'] == 0): ?>
                            <a href="?excluir=<?= $cat['id'] ?>"
                               class="btn-admin btn-danger btn-sm"
                               onclick="return confirm('Excluir esta categoria?')">Excluir</a>
                        <?php else: ?>
                            <span style="font-size:12px;color:#64748b;">Em uso</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="form-card">
        <h2 style="font-size:15px;margin-bottom:20px;">Nova categoria</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" required placeholder="Ex: Sagrado Masculino">
            </div>
            <div class="form-group">
                <label>Identificador (slug)</label>
                <input type="text" name="slug" required placeholder="Ex: sagrado-masculino"
                       pattern="[a-z0-9\-]+" title="Apenas letras minúsculas, números e hífens">
                <p style="font-size:11px;color:#64748b;margin-top:4px;">Sem espaços, acentos ou maiúsculas.</p>
            </div>
            <div class="form-group">
                <label>Ordem de exibição</label>
                <input type="number" name="ordem" min="0" value="0">
            </div>
            <div class="form-actions" style="border:none;padding:0;margin-top:15px;">
                <button type="submit" class="btn-admin btn-primary">Adicionar</button>
            </div>
        </form>
    </div>

</div>

<?php adminFooter(); ?>
