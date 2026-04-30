<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

// Stats
$totalEventos = 0;
$totalCats    = 0;
$proximosEvts = [];

try {
    $totalEventos = $pdo->query("SELECT COUNT(*) FROM eventos WHERE status = 'ativo'")->fetchColumn();
    $totalCats    = $pdo->query("SELECT COUNT(*) FROM categorias_eventos")->fetchColumn();
    $proximosEvts = $pdo->query(
        "SELECT e.titulo, e.data_evento, c.nome AS categoria
         FROM eventos e
         JOIN categorias_eventos c ON c.id = e.categoria_id
         WHERE e.status = 'ativo' AND e.data_evento >= NOW()
         ORDER BY e.data_evento ASC LIMIT 5"
    )->fetchAll();
} catch (\PDOException $e) {
    // Tabelas ainda não criadas — dashboard exibe zeros
}

adminHeader('Dashboard', 'dashboard');
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="num"><?= $totalEventos ?></div>
        <div class="label">Eventos ativos</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= $totalCats ?></div>
        <div class="label">Categorias</div>
    </div>
</div>

<div class="table-box">
    <div class="table-header">
        <h2>Próximos eventos</h2>
        <a href="<?= BASE_URL ?>/admin/evento-form.php" class="btn-admin btn-primary">+ Novo evento</a>
    </div>

    <?php if ($proximosEvts): ?>
    <table>
        <thead>
            <tr>
                <th>Evento</th>
                <th>Categoria</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($proximosEvts as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['titulo']) ?></td>
                <td><?= htmlspecialchars($e['categoria']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($e['data_evento'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="padding:20px;color:#64748b;">Nenhum evento futuro cadastrado.</p>
    <?php endif; ?>
</div>

<?php adminFooter(); ?>
