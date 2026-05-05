<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$rotinasNomes = ['alerta-pendentes', 'limpeza-dados', 'verificar-rotinas', 'encerrar-eventos'];

$rotinasLabels = [
    'alerta-pendentes'  => 'Alerta de Pendentes',
    'limpeza-dados'     => 'Limpeza de Dados (90d)',
    'verificar-rotinas' => 'Verificação de Rotinas',
    'encerrar-eventos'  => 'Encerrar Eventos',
];

$cronAgendamentos = [
    [
        'nome'       => 'alerta-pendentes',
        'arquivo'    => 'cron/alerta-pendentes.php',
        'frequencia' => 'Diário às 08h',
        'expressao'  => '0 8 * * *',
    ],
    [
        'nome'       => 'limpeza-dados',
        'arquivo'    => 'cron/limpeza-dados.php',
        'frequencia' => 'Diário às 03h',
        'expressao'  => '0 3 * * *',
    ],
    [
        'nome'       => 'verificar-rotinas',
        'arquivo'    => 'cron/verificar-rotinas.php',
        'frequencia' => 'Diário às 09h',
        'expressao'  => '0 9 * * *',
    ],
    [
        'nome'       => 'encerrar-eventos',
        'arquivo'    => 'cron/encerrar-eventos.php',
        'frequencia' => 'A cada hora',
        'expressao'  => '0 * * * *',
    ],
];

$ultimasExecucoes = [];
foreach ($rotinasNomes as $nome) {
    $stmt = $pdo->prepare("SELECT * FROM rotinas_execucao WHERE nome_rotina = ? ORDER BY data_execucao DESC LIMIT 1");
    $stmt->execute([$nome]);
    $ultimasExecucoes[$nome] = $stmt->fetch();
}

$historico = $pdo->query("SELECT * FROM rotinas_execucao ORDER BY data_execucao DESC LIMIT 30")->fetchAll();

adminHeader('Monitoramento de Rotinas', 'rotinas');
?>

<!-- Cards de status -->
<div class="stats-grid" style="grid-template-columns:repeat(auto-fill,minmax(200px,1fr));margin-bottom:30px;">
<?php foreach ($rotinasNomes as $nome):
    $ultima = $ultimasExecucoes[$nome];

    if (!$ultima) {
        $cor = '#64748b'; $bg = 'rgba(100,116,139,0.1)'; $ico = '⚪'; $label = 'Nunca executada';
    } elseif ($ultima['status'] === 'erro') {
        $cor = '#f87171'; $bg = 'rgba(248,113,113,0.1)'; $ico = '🔴'; $label = 'Erro';
    } elseif ($nome === 'encerrar-eventos'
              ? strtotime($ultima['data_execucao']) < strtotime('-2 hours')
              : strtotime($ultima['data_execucao']) < strtotime('-24 hours')) {
        $cor = '#fbbf24'; $bg = 'rgba(234,179,8,0.1)';   $ico = '🟡'; $label = 'Atraso';
    } else {
        $cor = '#4ade80'; $bg = 'rgba(74,222,128,0.1)';  $ico = '🟢'; $label = 'OK';
    }
?>
<div class="stat-card" style="border-left:3px solid <?= $cor ?>;">
    <div style="font-size:20px;margin-bottom:8px;"><?= $ico ?></div>
    <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:4px;"><?= $rotinasLabels[$nome] ?></div>
    <div style="font-size:12px;color:<?= $cor ?>;font-weight:600;margin-bottom:8px;"><?= $label ?></div>
    <?php if ($ultima): ?>
        <div style="font-size:11px;color:var(--dim);">
            Última exec.: <?= date('d/m/Y H:i', strtotime($ultima['data_execucao'])) ?>
        </div>
        <?php if ($ultima['mensagem']): ?>
        <div style="font-size:11px;color:var(--dim);margin-top:4px;word-break:break-word;">
            <?= htmlspecialchars(mb_substr($ultima['mensagem'], 0, 80)) ?>
        </div>
        <?php endif; ?>
    <?php else: ?>
        <div style="font-size:11px;color:var(--dim);">Nenhum registro ainda.</div>
    <?php endif; ?>
</div>
<?php endforeach; ?>
</div>

<!-- Agendamentos configurados -->
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;margin-bottom:30px;">
    <div style="padding:18px 20px 14px;border-bottom:1px solid var(--border);">
        <h2 style="font-size:15px;font-weight:700;">Agendamentos Cron</h2>
        <p style="font-size:12px;color:var(--dim);margin-top:4px;">Configurar em cPanel → Cron Jobs. Caminho base: <code style="font-size:11px;background:rgba(255,255,255,0.06);padding:2px 6px;border-radius:4px;">/home/cleit467/public_html/</code></p>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Rotina</th>
                    <th>Frequência</th>
                    <th>Expressão</th>
                    <th>Comando completo</th>
                    <th>Último status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cronAgendamentos as $cron):
                $ultima  = $ultimasExecucoes[$cron['nome']];
                $atrasoCutoff = $cron['nome'] === 'encerrar-eventos' ? '-2 hours' : '-24 hours';
                if (!$ultima) {
                    $sCor = '#64748b'; $sIco = '⚪'; $sLabel = 'Nunca executada';
                } elseif ($ultima['status'] === 'erro') {
                    $sCor = '#f87171'; $sIco = '🔴'; $sLabel = 'Erro';
                } elseif (strtotime($ultima['data_execucao']) < strtotime($atrasoCutoff)) {
                    $sCor = '#fbbf24'; $sIco = '🟡'; $sLabel = 'Atraso';
                } else {
                    $sCor = '#4ade80'; $sIco = '🟢'; $sLabel = 'OK';
                }
            ?>
            <tr>
                <td style="font-size:13px;font-weight:600;"><?= $rotinasLabels[$cron['nome']] ?></td>
                <td style="font-size:13px;color:#94a3b8;"><?= $cron['frequencia'] ?></td>
                <td>
                    <code style="font-size:12px;background:rgba(255,255,255,0.06);padding:3px 8px;border-radius:4px;color:#a78bfa;">
                        <?= $cron['expressao'] ?>
                    </code>
                </td>
                <td style="font-size:11px;color:#64748b;word-break:break-all;">
                    php /home/cleit467/public_html/<?= $cron['arquivo'] ?>
                </td>
                <td>
                    <span style="color:<?= $sCor ?>;font-size:13px;"><?= $sIco ?> <?= $sLabel ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Histórico de execuções -->
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;">
    <div style="padding:18px 20px 14px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
        <h2 style="font-size:15px;font-weight:700;">Histórico de execuções</h2>
        <span style="font-size:13px;color:var(--dim);">Últimas 30</span>
    </div>

    <?php if (empty($historico)): ?>
        <p style="padding:28px;color:var(--dim);font-size:14px;">Nenhuma execução registrada ainda.</p>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Rotina</th>
                    <th>Data / Hora</th>
                    <th>Status</th>
                    <th>Mensagem</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($historico as $h): ?>
            <tr>
                <td style="font-size:13px;"><?= htmlspecialchars($rotinasLabels[$h['nome_rotina']] ?? $h['nome_rotina']) ?></td>
                <td style="color:#94a3b8;font-size:13px;"><?= date('d/m/Y H:i:s', strtotime($h['data_execucao'])) ?></td>
                <td>
                    <?php if ($h['status'] === 'sucesso'): ?>
                        <span style="background:rgba(74,222,128,.1);color:#4ade80;padding:2px 10px;border-radius:10px;font-size:12px;">Sucesso</span>
                    <?php else: ?>
                        <span style="background:rgba(248,113,113,.1);color:#f87171;padding:2px 10px;border-radius:10px;font-size:12px;">Erro</span>
                    <?php endif; ?>
                </td>
                <td style="color:#94a3b8;font-size:12px;max-width:320px;">
                    <?= $h['mensagem'] ? htmlspecialchars(mb_substr($h['mensagem'], 0, 120)) : '—' ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php adminFooter(); ?>
