<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$cronScripts = [
    'alerta-pendentes'  => ['label' => 'Alerta de Pendentes',     'arquivo' => 'cron/alerta-pendentes.php',  'freq' => '0 8 * * *'],
    'limpeza-dados'     => ['label' => 'Limpeza de Dados (90d)',  'arquivo' => 'cron/limpeza-dados.php',     'freq' => '0 3 * * *'],
    'verificar-rotinas' => ['label' => 'Verificação de Rotinas',  'arquivo' => 'cron/verificar-rotinas.php', 'freq' => '0 9 * * *'],
    'encerrar-eventos'  => ['label' => 'Encerrar Eventos',        'arquivo' => 'cron/encerrar-eventos.php',  'freq' => '0 * * * *'],
];

$resultado   = null;
$execDisp    = function_exists('exec') || function_exists('shell_exec') || function_exists('popen');
$phpBin      = PHP_BINARY;
$raiz        = realpath(__DIR__ . '/..');

// Executar rotina solicitada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['rotina'])) {
    $rotina = $_POST['rotina'];
    if (isset($cronScripts[$rotina])) {
        $script  = $raiz . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cronScripts[$rotina]['arquivo']);
        $saida   = '';
        $sucesso = false;

        if (function_exists('exec')) {
            exec('"' . $phpBin . '" "' . $script . '" 2>&1', $linhas, $code);
            $saida   = implode("\n", $linhas);
            $sucesso = ($code === 0);
        } elseif (function_exists('shell_exec')) {
            $saida   = (string) shell_exec('"' . $phpBin . '" "' . $script . '" 2>&1');
            $sucesso = true;
        } elseif (function_exists('popen')) {
            $h = popen('"' . $phpBin . '" "' . $script . '" 2>&1', 'r');
            $saida   = (string) stream_get_contents($h);
            pclose($h);
            $sucesso = true;
        }

        // Ler último registro gerado pela rotina
        $ultimoLog = null;
        try {
            $stmt = $pdo->prepare("SELECT * FROM rotinas_execucao WHERE nome_rotina = ? ORDER BY data_execucao DESC LIMIT 1");
            $stmt->execute([$rotina]);
            $ultimoLog = $stmt->fetch();
        } catch (\PDOException $e) {}

        $resultado = compact('rotina', 'saida', 'sucesso', 'ultimoLog', 'script');
    }
}

adminHeader('Testar Rotinas', 'rotinas');
?>

<div style="margin-bottom:20px;">
    <a href="rotinas.php" style="color:#94a3b8;font-size:13px;text-decoration:none;">← Voltar para monitoramento</a>
</div>

<?php if ($resultado): ?>
<!-- Resultado da execução -->
<?php $r = $resultado; $cron = $cronScripts[$r['rotina']]; ?>
<div class="alert <?= ($r['sucesso'] && $r['ultimoLog'] && $r['ultimoLog']['status'] === 'sucesso') ? 'alert-success' : 'alert-warning' ?>"
     style="margin-bottom:24px;">
    <strong><?= htmlspecialchars($cron['label']) ?></strong> — executada manualmente.
    <?php if ($r['ultimoLog']): ?>
        Status registrado: <strong><?= $r['ultimoLog']['status'] === 'sucesso' ? '✔ Sucesso' : '✘ Erro' ?></strong>
        — <?= htmlspecialchars($r['ultimoLog']['mensagem']) ?>
    <?php endif; ?>
</div>

<?php if ($r['saida'] !== ''): ?>
<div style="background:rgba(0,0,0,0.3);border:1px solid var(--border);border-radius:8px;padding:16px;margin-bottom:28px;font-family:monospace;font-size:12px;color:#94a3b8;white-space:pre-wrap;overflow-x:auto;">
<?= htmlspecialchars($r['saida']) ?>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if (!$execDisp): ?>
<!-- Aviso: exec desativado -->
<div class="alert alert-warning" style="margin-bottom:28px;">
    <strong>Execução via browser indisponível.</strong> As funções <code>exec</code>, <code>shell_exec</code> e <code>popen</code> estão desativadas neste servidor.
    Use o <strong>Terminal do cPanel</strong> ou configure os Cron Jobs para executar os scripts.
</div>
<?php endif; ?>

<!-- Checklist de diagnóstico -->
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:28px;">
    <h2 style="font-size:15px;font-weight:700;margin-bottom:16px;">Checklist de diagnóstico</h2>
    <div style="display:flex;flex-direction:column;gap:10px;font-size:14px;color:#cbd5e1;">
        <div>
            <?php
            $tabExiste = false;
            try { $pdo->query("SELECT 1 FROM rotinas_execucao LIMIT 1"); $tabExiste = true; } catch (\PDOException $e) {}
            $totalLogs = 0;
            if ($tabExiste) { try { $totalLogs = (int)$pdo->query("SELECT COUNT(*) FROM rotinas_execucao")->fetchColumn(); } catch (\PDOException $e) {} }
            ?>
            <?= $tabExiste ? '✔' : '✘' ?>
            <strong>Tabela <code>rotinas_execucao</code>:</strong>
            <?= $tabExiste ? "{$totalLogs} registro(s) — tabela OK" : 'Não encontrada — execute setup_atendimentos.php no servidor' ?>
        </div>
        <div>
            <?php $arquivosOk = true; foreach ($cronScripts as $c) { if (!file_exists($raiz . '/' . $c['arquivo'])) $arquivosOk = false; } ?>
            <?= $arquivosOk ? '✔' : '✘' ?>
            <strong>Arquivos cron no servidor:</strong>
            <?= $arquivosOk ? 'Todos presentes' : 'Um ou mais arquivos ausentes — verifique o upload via FTP' ?>
        </div>
        <div>
            <?= $execDisp ? '✔' : '⚠' ?>
            <strong>Execução PHP via browser:</strong>
            <?= $execDisp ? 'Disponível (exec/shell_exec/popen habilitado)' : 'Indisponível — use o Terminal do cPanel' ?>
        </div>
        <div>
            ℹ <strong>PHP Binary:</strong> <code style="font-size:12px;background:rgba(255,255,255,0.06);padding:2px 6px;border-radius:4px;"><?= htmlspecialchars($phpBin) ?></code>
        </div>
    </div>
</div>

<!-- Executar manualmente -->
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;margin-bottom:28px;">
    <div style="padding:18px 20px 14px;border-bottom:1px solid var(--border);">
        <h2 style="font-size:15px;font-weight:700;">Executar manualmente</h2>
        <p style="font-size:12px;color:var(--dim);margin-top:4px;">Dispara o script imediatamente e registra o resultado em <code>rotinas_execucao</code>.</p>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Rotina</th>
                    <th>Último registro</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cronScripts as $nome => $cron):
                $ultima = null;
                try {
                    $stmt = $pdo->prepare("SELECT * FROM rotinas_execucao WHERE nome_rotina = ? ORDER BY data_execucao DESC LIMIT 1");
                    $stmt->execute([$nome]);
                    $ultima = $stmt->fetch();
                } catch (\PDOException $e) {}

                $arquivoExiste = file_exists($raiz . '/' . $cron['arquivo']);
            ?>
            <tr>
                <td>
                    <div style="font-size:13px;font-weight:600;"><?= $cron['label'] ?></div>
                    <div style="font-size:11px;color:#64748b;margin-top:2px;"><?= $cron['arquivo'] ?></div>
                </td>
                <td style="font-size:12px;">
                    <?php if (!$arquivoExiste): ?>
                        <span style="color:#f87171;">Arquivo não encontrado no servidor</span>
                    <?php elseif ($ultima): ?>
                        <span style="color:<?= $ultima['status'] === 'sucesso' ? '#4ade80' : '#f87171' ?>;">
                            <?= $ultima['status'] === 'sucesso' ? '✔' : '✘' ?>
                            <?= date('d/m/Y H:i', strtotime($ultima['data_execucao'])) ?>
                        </span>
                        <span style="display:block;color:#64748b;margin-top:2px;"><?= htmlspecialchars(mb_substr($ultima['mensagem'], 0, 60)) ?></span>
                    <?php else: ?>
                        <span style="color:#64748b;">Nunca executada</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($arquivoExiste && $execDisp): ?>
                    <form method="POST">
                        <input type="hidden" name="rotina" value="<?= $nome ?>">
                        <button type="submit" class="btn-action btn-edit"
                                onclick="return confirm('Executar <?= htmlspecialchars($cron['label']) ?> agora?')">
                            ▶ Executar agora
                        </button>
                    </form>
                    <?php elseif (!$execDisp): ?>
                        <span style="font-size:12px;color:#64748b;">Use o Terminal</span>
                    <?php else: ?>
                        <span style="font-size:12px;color:#f87171;">Arquivo ausente</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Comandos para Terminal cPanel -->
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;">
    <h2 style="font-size:15px;font-weight:700;margin-bottom:6px;">Executar via Terminal do cPanel</h2>
    <p style="font-size:13px;color:var(--dim);margin-bottom:16px;">
        Acesse <strong>cPanel → Terminal</strong> e cole um dos comandos abaixo para testar cada script individualmente e ver erros em tempo real.
    </p>
    <?php foreach ($cronScripts as $nome => $cron): ?>
    <div style="margin-bottom:12px;">
        <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;"><?= $cron['label'] ?></div>
        <code style="display:block;background:rgba(0,0,0,0.3);border:1px solid var(--border);border-radius:6px;padding:10px 14px;font-size:12px;color:#a78bfa;word-break:break-all;">
            <?= htmlspecialchars($phpBin) ?> /home/cleit467/public_html/<?= $cron['arquivo'] ?>
        </code>
    </div>
    <?php endforeach; ?>
    <p style="font-size:12px;color:var(--dim);margin-top:16px;">
        Se o PHP binary acima não funcionar, tente: <code style="background:rgba(255,255,255,0.06);padding:2px 6px;border-radius:4px;">php /home/cleit467/public_html/cron/encerrar-eventos.php</code>
    </p>
</div>

<?php adminFooter(); ?>
