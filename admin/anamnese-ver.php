<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';

$pid = (int)($_GET['id'] ?? 0);
if (!$pid) {
    header('Location: ' . BASE_URL . '/admin/anamneses.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM participantes WHERE id = ?");
$stmt->execute([$pid]);
$p = $stmt->fetch();

if (!$p) {
    header('Location: ' . BASE_URL . '/admin/anamneses.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anamneses WHERE participante_id = ?");
$stmt->execute([$pid]);
$a = $stmt->fetch();

adminHeader('Ficha de ' . explode(' ', $p['nome'])[0], 'anamneses');

// Helpers
function campo(string $label, $valor, bool $vazio = false): void {
    $display = ($valor !== null && $valor !== '' && $valor !== '[]')
        ? htmlspecialchars((string)$valor)
        : '<span style="color:#475569;">—</span>';
    echo "<div class=\"ver-campo\"><span class=\"ver-label\">{$label}</span><span class=\"ver-valor\">{$display}</span></div>";
}

function campoJson(string $label, $json): void {
    $arr = json_decode($json ?? '[]', true) ?: [];
    $display = $arr
        ? htmlspecialchars(implode(', ', $arr))
        : '<span style="color:#475569;">—</span>';
    echo "<div class=\"ver-campo\"><span class=\"ver-label\">{$label}</span><span class=\"ver-valor\">{$display}</span></div>";
}

function campoSim(string $label, $valor): void {
    if ($valor === null) { campo($label, null); return; }
    $display = $valor ? 'Sim' : 'Não';
    echo "<div class=\"ver-campo\"><span class=\"ver-label\">{$label}</span><span class=\"ver-valor\">{$display}</span></div>";
}

function secao(string $titulo): void {
    echo "<h3 class=\"ver-secao\">{$titulo}</h3>";
}
?>

<style>
.ver-voltar { display:inline-flex; align-items:center; gap:6px; color:#8b5cf6; font-size:14px; text-decoration:none; margin-bottom:24px; }
.ver-voltar:hover { color:#c4b5fd; }

.ver-header {
    background: rgba(139,92,246,0.08);
    border: 1px solid rgba(139,92,246,0.2);
    border-radius: 14px;
    padding: 24px 28px;
    margin-bottom: 28px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px 40px;
    align-items: flex-start;
}

.ver-header-item strong { display:block; font-size:12px; color:#64748b; text-transform:uppercase; letter-spacing:.6px; margin-bottom:4px; }
.ver-header-item span  { font-size:15px; color:#e2e8f0; }

.ver-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
}
.ver-badge-ok    { background:rgba(34,197,94,0.12);  color:#4ade80; }
.ver-badge-inc   { background:rgba(234,179,8,0.12);  color:#fbbf24; }
.ver-badge-vazio { background:rgba(100,116,139,0.12);color:#64748b; }

.ver-secao {
    font-size: 14px;
    font-weight: 700;
    color: #8b5cf6;
    text-transform: uppercase;
    letter-spacing: .7px;
    margin: 30px 0 14px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(139,92,246,0.2);
}

.ver-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 8px 24px;
}

.ver-campo {
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.ver-label {
    font-size: 11px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.ver-valor {
    font-size: 14px;
    color: #cbd5e1;
    line-height: 1.5;
}

.ver-campo-full {
    grid-column: 1 / -1;
}

@media(max-width:600px) {
    .ver-grid { grid-template-columns: 1fr; }
    .ver-campo-full { grid-column: 1; }
}
</style>

<a href="<?= BASE_URL ?>/admin/anamneses.php" class="ver-voltar">← Voltar à lista</a>

<!-- Cabeçalho do participante -->
<div class="ver-header">
    <div class="ver-header-item">
        <strong>Nome</strong>
        <span><?= htmlspecialchars($p['nome']) ?></span>
    </div>
    <div class="ver-header-item">
        <strong>E-mail</strong>
        <span><?= htmlspecialchars($p['email']) ?></span>
    </div>
    <div class="ver-header-item">
        <strong>CPF</strong>
        <span><?= chunk_split($p['cpf'], 3, '.') ?></span>
    </div>
    <div class="ver-header-item">
        <strong>Nascimento</strong>
        <span><?= $p['data_nascimento'] ? date('d/m/Y', strtotime($p['data_nascimento'])) : '—' ?></span>
    </div>
    <div class="ver-header-item">
        <strong>WhatsApp</strong>
        <span>
            <?php if ($p['whatsapp']): ?>
                <a href="https://wa.me/55<?= preg_replace('/\D/', '', $p['whatsapp']) ?>"
                   target="_blank" style="color:#25D366;"><?= htmlspecialchars($p['whatsapp']) ?></a>
            <?php else: echo '—'; endif; ?>
        </span>
    </div>
    <div class="ver-header-item">
        <strong>Ficha</strong>
        <?php if (!$a): ?>
            <span class="ver-badge ver-badge-vazio">Não preenchida</span>
        <?php elseif ($a['status'] === 'completo'): ?>
            <span class="ver-badge ver-badge-ok">Completa</span>
        <?php else: ?>
            <span class="ver-badge ver-badge-inc">Incompleta</span>
        <?php endif; ?>
    </div>
    <?php if ($a && $a['atualizado_em']): ?>
    <div class="ver-header-item">
        <strong>Última atualização</strong>
        <span><?= date('d/m/Y \à\s H:i', strtotime($a['atualizado_em'])) ?></span>
    </div>
    <?php endif; ?>
    <?php if ($a && $a['data_aceite']): ?>
    <div class="ver-header-item">
        <strong>Aceite do Termo</strong>
        <span><?= date('d/m/Y \à\s H:i', strtotime($a['data_aceite'])) ?></span>
    </div>
    <?php endif; ?>
</div>

<?php if (!$a): ?>
    <div style="text-align:center;padding:60px 0;color:#64748b;">
        <p>Este participante ainda não preencheu a ficha de anamnese.</p>
    </div>
<?php else: ?>

<!-- ===== FICHA CADASTRAL ===== -->
<?php secao('Ficha Cadastral'); ?>
<div class="ver-grid">
    <?php campo('RG', $a['rg']); ?>
    <?php campo('Órgão Expedidor', $a['orgao_expedidor']); ?>
    <?php campo('Sexo', $a['sexo']); ?>
    <?php campo('Escolaridade', $a['escolaridade']); ?>
    <?php campo('Profissão', $a['profissao']); ?>
    <?php campo('Instagram', $a['instagram']); ?>
    <?php campo('Facebook', $a['facebook']); ?>
    <div class="ver-campo ver-campo-full">
        <span class="ver-label">Endereço</span>
        <span class="ver-valor">
            <?php
            $end = trim(implode(', ', array_filter([
                $a['end_rua'] . ($a['end_num'] ? ', ' . $a['end_num'] : ''),
                $a['end_bairro'],
                $a['end_cidade'],
                $a['end_estado'],
            ])));
            echo $end ?: '<span style="color:#475569;">—</span>';
            ?>
        </span>
    </div>
    <?php campo('Contato de emergência', $a['contato_emergencia']); ?>
    <?php campo('Tel. emergência', $a['contato_emergencia_tel']); ?>
</div>

<!-- ===== SEÇÃO 1 — FAMÍLIA ===== -->
<?php secao('1. Situação Familiar'); ?>
<div class="ver-grid">
    <?php campo('Estado civil', $a['estado_civil']); ?>
    <?php campoSim('Tem filhos', $a['tem_filhos']); ?>
    <?php campo('Quantidade de filhos', $a['qtd_filhos']); ?>
    <?php campo('Mora com', $a['mora_com']); ?>
</div>

<!-- ===== SEÇÃO 2 — TRABALHO ===== -->
<?php secao('2. Trabalho e Atividades'); ?>
<div class="ver-grid">
    <?php campo('Atividade profissional', $a['atividade_profissional']); ?>
    <?php campo('Gosta do trabalho?', $a['gosta_trabalho']); ?>
    <?php campo('Situação estável?', $a['estavel_trabalho']); ?>
    <div class="ver-campo ver-campo-full">
        <?php campo('Outras atividades / voluntariado', $a['outras_atividades']); ?>
    </div>
</div>

<!-- ===== SEÇÃO 3 — SAÚDE FÍSICA ===== -->
<?php secao('3. Saúde Física'); ?>
<div class="ver-grid">
    <?php campo('Doenças graves', $a['doencas_graves']); ?>
    <?php campo('Cirurgias', $a['cirurgias']); ?>
    <?php campo('Problemas de saúde atuais', $a['problemas_saude']); ?>
    <?php campoSim('Problema cardíaco', $a['prob_cardiaco']); ?>
    <?php campoSim('Diabetes', $a['diabetes']); ?>
    <?php campoSim('Úlceras', $a['ulceras']); ?>
    <?php campoSim('Grávida', $a['gravida']); ?>
    <?php campo('Meses de gravidez', $a['meses_gravida']); ?>
    <?php campo('Pressão arterial', $a['pressao_arterial']); ?>
    <?php campo('Último ECG / exame cardíaco', $a['ultimo_ecg']); ?>
    <?php campo('Tratamento médico atual', $a['tratamento_atual']); ?>
    <div class="ver-campo ver-campo-full">
        <?php campo('Medicamentos em uso', $a['medicamentos']); ?>
    </div>
    <?php campo('Consumo de álcool', $a['consumo_alcool']); ?>
    <?php campo('Uso de drogas', $a['consumo_drogas']); ?>
    <?php campo('Prejuízos por substâncias', $a['prejuizos_substancias']); ?>
    <?php campo('Dificuldade de controle / dependência', $a['dificuldade_controle']); ?>
</div>

<!-- ===== SEÇÃO 4 — EMOCIONAL ===== -->
<?php secao('4. Estado Emocional'); ?>
<div class="ver-grid">
    <div class="ver-campo ver-campo-full">
        <?php campoJson('Estado(s) emocional(is) atual(is)', $a['estado_emocional']); ?>
    </div>
</div>

<!-- ===== SEÇÃO 5 — SAÚDE MENTAL ===== -->
<?php secao('5. Saúde Mental'); ?>
<div class="ver-grid">
    <?php campo('Distúrbios psicológicos', $a['disturbios_psicologicos']); ?>
    <?php campo('Internações psiquiátricas', $a['internacoes_psiquiatricas']); ?>
    <div class="ver-campo ver-campo-full">
        <?php campoJson('Histórico familiar de transtornos mentais', $a['hist_familiar_mental']); ?>
    </div>
    <?php campo('Surto psicótico', $a['surto_psicotico']); ?>
    <?php campo('Alucinações / delírios', $a['alucinacoes']); ?>
    <?php campo('Experiências de quase morte', $a['experiencias_morte']); ?>
    <?php campo('Sensação de perseguição', $a['sensacao_perseguicao']); ?>
    <?php campo('Desordem de pensamentos', $a['desordem_pensamentos']); ?>
    <?php campo('Pensamentos acelerados', $a['pensamentos_acelerados']); ?>
</div>

<!-- ===== SEÇÃO 6 — DOMICÍLIO ===== -->
<?php secao('6. Situação do Domicílio'); ?>
<div class="ver-grid">
    <div class="ver-campo ver-campo-full">
        <?php campoJson('Problemas no domicílio', $a['problemas_domicilio']); ?>
    </div>
</div>

<!-- ===== SEÇÃO 7 — REATIVIDADE ===== -->
<?php secao('7. Reatividade e Comportamento'); ?>
<div class="ver-grid">
    <?php campo('Nível de reatividade (1–10)', $a['nivel_reatividade']); ?>
    <?php campo('Brigas físicas / agressividade', $a['brigas_fisicas']); ?>
</div>

<!-- ===== SEÇÃO 8 — ESPIRITUALIDADE ===== -->
<?php secao('8. Espiritualidade'); ?>
<div class="ver-grid">
    <?php campo('Pratica alguma religião / tradição', $a['pratica_religiao']); ?>
    <?php campo('O que busca espiritualmente', $a['busca_religiosa']); ?>
    <?php campo('Pratica meditação', $a['pratica_meditacao']); ?>
    <?php campo('Mediunidade', $a['mediunidade']); ?>
    <?php campo('Experiências espirituais marcantes', $a['exp_espirituais']); ?>
    <?php campo('Desenvolvimento espiritual ativo', $a['desenvolvimento_esp']); ?>
    <?php campo('Experiências com Ayahuasca', $a['exp_ayahuasca']); ?>
    <?php campo('Como soube da fraternidade', $a['como_soube']); ?>
    <div class="ver-campo ver-campo-full">
        <?php campoJson('O que busca no ritual', $a['busca_ritual']); ?>
    </div>
</div>

<!-- ===== SEÇÃO 9 — OBSERVAÇÕES ===== -->
<?php secao('9. Observações Gerais'); ?>
<div class="ver-grid">
    <div class="ver-campo ver-campo-full">
        <span class="ver-label">Observações</span>
        <span class="ver-valor" style="white-space:pre-line;">
            <?= ($a['observacoes'] !== '') ? htmlspecialchars($a['observacoes']) : '<span style="color:#475569;">—</span>' ?>
        </span>
    </div>
</div>

<?php endif; ?>

<div style="margin-top:40px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.07);">
    <a href="<?= BASE_URL ?>/admin/anamneses.php" class="ver-voltar">← Voltar à lista</a>
</div>

<?php adminFooter(); ?>
