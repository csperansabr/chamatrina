<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$msg = $_GET['msg'] ?? '';

$eventos = $pdo->query(
    "SELECT e.*, c.nome AS categoria
     FROM eventos e
     JOIN categorias_eventos c ON c.id = e.categoria_id
     ORDER BY e.data_evento DESC"
)->fetchAll();

adminHeader('Eventos', 'eventos');
?>

<?php if ($msg === 'salvo'): ?>
    <div class="alert alert-success">Evento salvo com sucesso.</div>
<?php elseif ($msg === 'excluido'): ?>
    <div class="alert alert-success">Evento excluído.</div>
<?php endif; ?>

<div class="table-box">
    <div class="table-header">
        <h2>Todos os eventos</h2>
        <a href="<?= BASE_URL ?>/admin/evento-form.php" class="btn-admin btn-primary">+ Novo evento</a>
    </div>

    <?php if ($eventos): ?>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoria</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($eventos as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['titulo']) ?></td>
                <td><?= htmlspecialchars($e['categoria']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($e['data_evento'])) ?></td>
                <td><span class="badge badge-<?= $e['status'] ?>"><?= ucfirst($e['status']) ?></span></td>
                <td style="display:flex;gap:8px;">
                    <a href="<?= BASE_URL ?>/admin/evento-form.php?id=<?= $e['id'] ?>"
                       class="btn-admin btn-secondary btn-sm">Editar</a>
                    <a href="<?= BASE_URL ?>/admin/evento-excluir.php?id=<?= $e['id'] ?>"
                       class="btn-admin btn-danger btn-sm"
                       onclick="return confirm('Excluir este evento?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="padding:20px;color:#64748b;">Nenhum evento cadastrado ainda.</p>
    <?php endif; ?>
</div>

<?php adminFooter(); ?>
