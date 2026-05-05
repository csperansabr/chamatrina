<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: ' . BASE_URL . '/admin/atendimentos.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM atendimentos WHERE id = ?");
$stmt->execute([$id]);
$atendimento = $stmt->fetch();

if (!$atendimento) {
    header('Location: ' . BASE_URL . '/admin/atendimentos.php');
    exit;
}

adminHeader('Atendimento #' . $id, 'atendimentos');
?>

<div style="margin-bottom:20px;">
    <a href="atendimentos.php" style="color:#94a3b8;font-size:13px;text-decoration:none;">← Voltar para listagem</a>
</div>

<?php if (isset($_GET['ok'])): ?>
<div class="alert <?= $_GET['ok'] === 'nomail' ? 'alert-warning' : 'alert-success' ?>">
    <?= $_GET['ok'] === 'nomail'
        ? 'Atendimento concluído, mas não foi possível enviar o e-mail ao solicitante.'
        : 'Atendimento concluído e mensagem enviada ao solicitante.' ?>
</div>
<?php endif; ?>

<div class="form-card" style="max-width:700px;">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;padding-bottom:20px;border-bottom:1px solid var(--border);">
        <h2 style="font-size:18px;"><?= htmlspecialchars($atendimento['nome']) ?></h2>
        <?php
        $badges = [
            'pendente'  => ['#fbbf24', 'rgba(234,179,8,0.12)',  'Pendente'],
            'concluido' => ['#4ade80', 'rgba(34,197,94,0.12)',  'Concluído'],
        ];
        [$cor, $bg, $label] = $badges[$atendimento['status']] ?? ['#94a3b8', 'rgba(0,0,0,0.1)', '—'];
        ?>
        <span style="background:<?= $bg ?>;color:<?= $cor ?>;padding:5px 14px;border-radius:12px;font-size:13px;font-weight:600;">
            <?= $label ?>
        </span>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px 28px;">

        <div>
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">E-mail</div>
            <div style="font-size:14px;"><?= htmlspecialchars($atendimento['email']) ?></div>
        </div>

        <div>
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">WhatsApp</div>
            <div style="font-size:14px;"><?= htmlspecialchars($atendimento['whatsapp']) ?></div>
        </div>

        <div>
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Data de nascimento</div>
            <div style="font-size:14px;"><?= date('d/m/Y', strtotime($atendimento['data_nascimento'])) ?></div>
        </div>

        <div>
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Nome da mãe</div>
            <div style="font-size:14px;"><?= htmlspecialchars($atendimento['nome_mae']) ?></div>
        </div>

        <div style="grid-column:1/-1;">
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Endereço</div>
            <div style="font-size:14px;"><?= htmlspecialchars($atendimento['endereco']) ?></div>
        </div>

        <div>
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Tipo de atendimento</div>
            <div style="font-size:14px;"><?= htmlspecialchars($atendimento['tipo_atendimento']) ?></div>
        </div>

        <div>
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Solicitado em</div>
            <div style="font-size:14px;"><?= date('d/m/Y H:i', strtotime($atendimento['data_solicitacao'])) ?></div>
        </div>

        <?php if ($atendimento['data_conclusao']): ?>
        <div>
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Concluído em</div>
            <div style="font-size:14px;"><?= date('d/m/Y H:i', strtotime($atendimento['data_conclusao'])) ?></div>
        </div>
        <?php endif; ?>


        <div style="grid-column:1/-1;">
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Intenção / Propósito</div>
            <div style="font-size:14px;background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:8px;padding:14px;line-height:1.8;white-space:pre-wrap;"><?= htmlspecialchars($atendimento['intencao']) ?></div>
        </div>

    </div>

    <?php if ($atendimento['status'] === 'pendente'): ?>
    <?php
    $msgPadrao = "Informo que seu atendimento foi concluído conforme solicitado, e diante disso, observe os próximos dias.\n\nMuito obrigado por confiar em nosso trabalho.";
    ?>
    <div style="margin-top:28px;padding-top:20px;border-top:1px solid var(--border);">
        <div style="font-size:13px;font-weight:600;color:#e2e8f0;margin-bottom:14px;">Concluir atendimento</div>
        <form method="POST" action="atendimento-concluir.php">
            <input type="hidden" name="id" value="<?= $atendimento['id'] ?>">
            <input type="hidden" name="redir" value="ver">
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:8px;">
                    Mensagem para o solicitante
                </label>
                <textarea name="msg_conclusao" required rows="8"
                    style="width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:8px;padding:12px;color:#e2e8f0;font-size:14px;line-height:1.7;resize:vertical;box-sizing:border-box;"
                    ><?= htmlspecialchars($msgPadrao) ?></textarea>
            </div>
            <button type="submit" class="btn-admin btn-primary">Enviar mensagem e concluir</button>
        </form>
    </div>
    <?php endif; ?>

</div>

<?php adminFooter(); ?>
