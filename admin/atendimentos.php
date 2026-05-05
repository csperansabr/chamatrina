<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$statusFil = $_GET['status'] ?? '';
$busca     = trim($_GET['busca'] ?? '');

$where  = ['1=1'];
$params = [];

if (in_array($statusFil, ['pendente', 'concluido'])) {
    $where[]  = 'status = ?';
    $params[] = $statusFil;
}
if ($busca) {
    $where[]  = '(nome LIKE ? OR email LIKE ?)';
    $params[] = "%{$busca}%";
    $params[] = "%{$busca}%";
}

$sql  = 'SELECT * FROM atendimentos WHERE ' . implode(' AND ', $where) . ' ORDER BY data_solicitacao DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$atendimentos = $stmt->fetchAll();

adminHeader('Atendimentos Online', 'atendimentos');
?>

<?php if (isset($_GET['ok'])): ?>
<div class="alert <?= $_GET['ok'] === 'nomail' ? 'alert-warning' : 'alert-success' ?>">
    <?php if ($_GET['ok'] === 'nomail'): ?>
        Atendimento concluído, mas não foi possível enviar o e-mail ao solicitante.
    <?php elseif ($_GET['ok'] === 'excluido'): ?>
        Atendimento excluído.
    <?php else: ?>
        Atendimento concluído e mensagem enviada ao solicitante.
    <?php endif; ?>
</div>
<?php endif; ?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <p style="color:#94a3b8;margin:0;"><?= count($atendimentos) ?> atendimento(s) encontrado(s)</p>
</div>

<form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
    <input type="text" name="busca" placeholder="Buscar por nome ou e-mail…"
           value="<?= htmlspecialchars($busca) ?>"
           style="flex:1;min-width:200px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:8px;color:#f1f5f9;padding:9px 14px;font-size:14px;">
    <select name="status" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:8px;color:#f1f5f9;padding:9px 14px;font-size:14px;">
        <option value="">Todos os status</option>
        <option value="pendente"  <?= $statusFil === 'pendente'  ? 'selected' : '' ?>>Pendente</option>
        <option value="concluido" <?= $statusFil === 'concluido' ? 'selected' : '' ?>>Concluído</option>
    </select>
    <button type="submit" class="btn-action btn-edit">Filtrar</button>
    <?php if ($busca || $statusFil): ?>
        <a href="atendimentos.php" class="btn-action"
           style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);color:#94a3b8;text-decoration:none;display:inline-flex;align-items:center;">
            Limpar
        </a>
    <?php endif; ?>
</form>

<?php if (empty($atendimentos)): ?>
    <div style="text-align:center;padding:60px 0;color:#64748b;">
        <p>Nenhum atendimento encontrado.</p>
    </div>
<?php else: ?>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Datas</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($atendimentos as $a):
            $badges = [
                'pendente'  => ['#fbbf24', 'rgba(234,179,8,0.12)', 'Pendente'],
                'concluido' => ['#4ade80', 'rgba(34,197,94,0.12)', 'Concluído'],
            ];
            [$cor, $bg, $label] = $badges[$a['status']] ?? ['#94a3b8', 'rgba(0,0,0,0.1)', '—'];
            $msgPadrao = "Informo que seu atendimento foi concluído conforme solicitado, e diante disso, observe os próximos dias.\n\nMuito obrigado por confiar em nosso trabalho.";
        ?>
        <tr>
            <td>
                <strong style="font-size:14px;"><?= htmlspecialchars($a['nome']) ?></strong>
                <span style="font-size:12px;color:#64748b;display:block;"><?= htmlspecialchars($a['email']) ?></span>
            </td>
            <td style="color:#94a3b8;font-size:13px;"><?= htmlspecialchars($a['tipo_atendimento']) ?></td>
            <td>
                <span style="background:<?= $bg ?>;color:<?= $cor ?>;padding:3px 10px;border-radius:12px;font-size:12px;">
                    <?= $label ?>
                </span>
            </td>
            <td style="font-size:13px;">
                <span style="color:#94a3b8;">Solicitado: <?= date('d/m/Y H:i', strtotime($a['data_solicitacao'])) ?></span>
                <?php if ($a['status'] === 'concluido' && $a['data_conclusao']): ?>
                <span style="color:#4ade80;display:block;margin-top:3px;">Concluído: <?= date('d/m/Y H:i', strtotime($a['data_conclusao'])) ?></span>
                <?php endif; ?>
            </td>
            <td style="display:flex;gap:6px;flex-wrap:wrap;">
                <a href="atendimento-ver.php?id=<?= $a['id'] ?>" class="btn-action btn-edit">Ver</a>
                <?php if ($a['status'] === 'pendente'): ?>
                <form method="POST" action="atendimento-concluir.php">
                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
                    <input type="hidden" name="msg_conclusao" value="<?= htmlspecialchars($msgPadrao) ?>">
                    <button type="submit" class="btn-action"
                            style="background:rgba(16,185,129,0.1);border-color:rgba(16,185,129,0.25);color:#4ade80;"
                            onclick="return confirm('Marcar como concluído?')">
                        Concluir
                    </button>
                </form>
                <?php endif; ?>
                <form method="POST" action="atendimento-excluir.php">
                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
                    <button type="submit" class="btn-action"
                            style="background:rgba(248,113,113,0.1);border-color:rgba(248,113,113,0.25);color:#f87171;"
                            onclick="return confirm('Excluir este atendimento permanentemente?')">
                        Excluir
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php adminFooter(); ?>
