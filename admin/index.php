<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

// Semáforo de rotinas
$semaforoStatus = 'verde';
$semaforoMsg    = 'Sistema OK';
try {
    $rotinasMonitor = ['alerta-pendentes', 'limpeza-dados', 'verificar-rotinas', 'encerrar-eventos'];
    foreach ($rotinasMonitor as $rotinaItem) {
        $stmtR = $pdo->prepare("SELECT status, data_execucao FROM rotinas_execucao WHERE nome_rotina = ? ORDER BY data_execucao DESC LIMIT 1");
        $stmtR->execute([$rotinaItem]);
        $ultimaR = $stmtR->fetch();
        if (!$ultimaR) {
            if ($semaforoStatus === 'verde') { $semaforoStatus = 'amarelo'; $semaforoMsg = 'Atenção — rotina sem registro'; }
        } elseif ($ultimaR['status'] === 'erro') {
            $semaforoStatus = 'vermelho'; $semaforoMsg = 'Problema — erro em rotina automática';
            break;
        } elseif (strtotime($ultimaR['data_execucao']) < strtotime('-24 hours')) {
            if ($semaforoStatus !== 'vermelho') { $semaforoStatus = 'amarelo'; $semaforoMsg = 'Atenção — rotina atrasada'; }
        }
    }
} catch (\PDOException $e) {
    // Tabela ainda não criada
}

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

$semaforoCores = [
    'verde'    => ['cor' => '#4ade80', 'bg' => 'rgba(74,222,128,0.1)',  'borda' => 'rgba(74,222,128,0.3)',  'ico' => '🟢'],
    'amarelo'  => ['cor' => '#fbbf24', 'bg' => 'rgba(234,179,8,0.1)',   'borda' => 'rgba(234,179,8,0.3)',   'ico' => '🟡'],
    'vermelho' => ['cor' => '#f87171', 'bg' => 'rgba(248,113,113,0.1)', 'borda' => 'rgba(248,113,113,0.3)', 'ico' => '🔴'],
];
$sc = $semaforoCores[$semaforoStatus];
?>

<a href="<?= BASE_URL ?>/admin/rotinas.php" style="display:block;text-decoration:none;margin-bottom:24px;">
    <div style="background:<?= $sc['bg'] ?>;border:1px solid <?= $sc['borda'] ?>;border-radius:12px;padding:14px 20px;display:flex;align-items:center;gap:12px;">
        <span style="font-size:20px;"><?= $sc['ico'] ?></span>
        <span style="font-size:14px;font-weight:700;color:<?= $sc['cor'] ?>;"><?= $semaforoMsg ?></span>
        <span style="font-size:12px;color:#64748b;margin-left:auto;">Ver monitoramento →</span>
    </div>
</a>

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
