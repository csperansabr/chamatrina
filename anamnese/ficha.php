<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$pid = $_SESSION['participante_id'];

$stmt = $pdo->prepare("SELECT * FROM participantes WHERE id = ?");
$stmt->execute([$pid]);
$p = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM anamneses WHERE participante_id = ?");
$stmt->execute([$pid]);
$f = $stmt->fetch() ?: [];

$msg = $_GET['msg'] ?? '';

// Helper para pré-preencher campos
function v(array $f, string $key, $default = ''): string {
    return htmlspecialchars($f[$key] ?? $default);
}
function checked(array $f, string $key, string $val): string {
    $arr = json_decode($f[$key] ?? '[]', true) ?: [];
    return in_array($val, $arr) ? 'checked' : '';
}
function sim(array $f, string $key): string {
    return ($f[$key] ?? '') == 1 ? 'checked' : '';
}
function nao(array $f, string $key): string {
    return isset($f[$key]) && $f[$key] == 0 ? 'checked' : '';
}
function sel(array $f, string $key, string $val): string {
    return ($f[$key] ?? '') === $val ? 'selected' : '';
}

$title       = 'Ficha de Anamnese';
$description = 'Formulário de anamnese para participação em cerimônias da Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/anamnese/ficha.php';
include __DIR__ . '/../includes/layout-top.php';
?>

<div class="container">

<?php if ($msg === 'salvo'): ?>
    <div class="anamnese-alerta anamnese-alerta-ok">Ficha salva com sucesso!</div>
<?php elseif ($msg === 'completo'): ?>
    <div class="anamnese-alerta anamnese-alerta-ok">Ficha enviada com sucesso! Obrigado pelo preenchimento.</div>
<?php endif; ?>

<div class="about">
    <h2>Ficha de Anamnese</h2>
    <p>Preencha com atenção e honestidade. Todas as informações são <strong>confidenciais</strong> e acessadas apenas pela equipe da Fraternidade. Você pode salvar e retornar para editar a qualquer momento.</p>
</div>

<!-- Navegação por seções -->
<div class="anamnese-tabs" id="anamnese-tabs">
    <button type="button" class="anamnese-tab ativo" data-secao="0">Cadastro</button>
    <button type="button" class="anamnese-tab" data-secao="1">Família</button>
    <button type="button" class="anamnese-tab" data-secao="2">Trabalho</button>
    <button type="button" class="anamnese-tab" data-secao="3">Saúde Física</button>
    <button type="button" class="anamnese-tab" data-secao="4">Emocional</button>
    <button type="button" class="anamnese-tab" data-secao="5">Saúde Mental</button>
    <button type="button" class="anamnese-tab" data-secao="6">Domicílio</button>
    <button type="button" class="anamnese-tab" data-secao="7">Reatividade</button>
    <button type="button" class="anamnese-tab" data-secao="8">Espiritualidade</button>
    <button type="button" class="anamnese-tab" data-secao="9">Termo</button>
</div>

<form method="POST" action="<?= BASE_URL ?>/anamnese/salvar.php" class="anamnese-form">

<!-- ===== SEÇÃO 0 — FICHA CADASTRAL ===== -->
<div class="anamnese-secao ativa" id="secao-0">
    <h3 class="secao-titulo">Dados Cadastrais</h3>

    <div class="campos-grid">
        <div class="campo campo-full">
            <label>Nome completo</label>
            <input type="text" value="<?= htmlspecialchars($p['nome']) ?>" disabled class="campo-disabled">
        </div>
        <div class="campo">
            <label>E-mail</label>
            <input type="text" value="<?= htmlspecialchars($p['email']) ?>" disabled class="campo-disabled">
        </div>
        <div class="campo">
            <label>CPF</label>
            <input type="text" value="<?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $p['cpf']) ?>" disabled class="campo-disabled">
        </div>
        <div class="campo">
            <label>Data de nascimento</label>
            <input type="text" value="<?= date('d/m/Y', strtotime($p['data_nascimento'])) ?>" disabled class="campo-disabled">
        </div>
        <div class="campo">
            <label>WhatsApp</label>
            <input type="text" value="<?= htmlspecialchars($p['whatsapp']) ?>" disabled class="campo-disabled">
        </div>
        <div class="campo">
            <label>RG</label>
            <input type="text" name="rg" value="<?= v($f,'rg') ?>">
        </div>
        <div class="campo">
            <label>Órgão expedidor</label>
            <input type="text" name="orgao_expedidor" value="<?= v($f,'orgao_expedidor') ?>">
        </div>
        <div class="campo">
            <label>Sexo</label>
            <select name="sexo">
                <option value="">Selecione...</option>
                <option value="Feminino"    <?= sel($f,'sexo','Feminino') ?>>Feminino</option>
                <option value="Masculino"   <?= sel($f,'sexo','Masculino') ?>>Masculino</option>
                <option value="Não-binário" <?= sel($f,'sexo','Não-binário') ?>>Não-binário</option>
                <option value="Prefiro não informar" <?= sel($f,'sexo','Prefiro não informar') ?>>Prefiro não informar</option>
            </select>
        </div>
        <div class="campo">
            <label>Escolaridade</label>
            <select name="escolaridade">
                <option value="">Selecione...</option>
                <?php foreach (['Ensino Fundamental','Ensino Médio','Ensino Superior incompleto','Ensino Superior completo','Pós-graduação'] as $op): ?>
                <option value="<?= $op ?>" <?= sel($f,'escolaridade',$op) ?>><?= $op ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="campo">
            <label>Profissão</label>
            <input type="text" name="profissao" value="<?= v($f,'profissao') ?>">
        </div>
        <div class="campo">
            <label>Instagram (opcional)</label>
            <input type="text" name="instagram" value="<?= v($f,'instagram') ?>" placeholder="@usuario">
        </div>
        <div class="campo">
            <label>Facebook (opcional)</label>
            <input type="text" name="facebook" value="<?= v($f,'facebook') ?>">
        </div>
    </div>

    <h4 class="subcampo-titulo">Endereço</h4>
    <div class="campos-grid">
        <div class="campo campo-full">
            <label>Rua / Avenida</label>
            <input type="text" name="end_rua" value="<?= v($f,'end_rua') ?>">
        </div>
        <div class="campo campo-sm">
            <label>Número</label>
            <input type="text" name="end_num" value="<?= v($f,'end_num') ?>">
        </div>
        <div class="campo">
            <label>Bairro</label>
            <input type="text" name="end_bairro" value="<?= v($f,'end_bairro') ?>">
        </div>
        <div class="campo">
            <label>Cidade</label>
            <input type="text" name="end_cidade" value="<?= v($f,'end_cidade') ?>">
        </div>
        <div class="campo campo-sm">
            <label>Estado (UF)</label>
            <input type="text" name="end_estado" maxlength="2" value="<?= v($f,'end_estado') ?>" placeholder="RS">
        </div>
    </div>

    <h4 class="subcampo-titulo">Contato de emergência</h4>
    <div class="campos-grid">
        <div class="campo">
            <label>Nome do familiar</label>
            <input type="text" name="contato_emergencia" value="<?= v($f,'contato_emergencia') ?>">
        </div>
        <div class="campo">
            <label>Telefone do familiar</label>
            <input type="tel" name="contato_emergencia_tel" value="<?= v($f,'contato_emergencia_tel') ?>">
        </div>
    </div>

    <div class="secao-nav">
        <span></span>
        <button type="button" class="btn proxima-secao">Próxima →</button>
    </div>
</div>

<!-- ===== SEÇÃO 1 — VIDA FAMILIAR ===== -->
<div class="anamnese-secao" id="secao-1">
    <h3 class="secao-titulo">1. Vida Familiar</h3>
    <div class="campos-grid">
        <div class="campo">
            <label>Estado civil ou de convivência</label>
            <select name="estado_civil">
                <option value="">Selecione...</option>
                <?php foreach (['Solteiro(a)','Casado(a)','União estável','Separado(a)','Divorciado(a)','Viúvo(a)'] as $op): ?>
                <option value="<?= $op ?>" <?= sel($f,'estado_civil',$op) ?>><?= $op ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="campo">
            <label>Tem filhos?</label>
            <div class="radio-group">
                <label><input type="radio" name="tem_filhos" value="1" <?= sim($f,'tem_filhos') ?>> Sim</label>
                <label><input type="radio" name="tem_filhos" value="0" <?= nao($f,'tem_filhos') ?>> Não</label>
            </div>
        </div>
        <div class="campo">
            <label>Quantos filhos?</label>
            <input type="number" name="qtd_filhos" min="0" value="<?= v($f,'qtd_filhos') ?>">
        </div>
        <div class="campo campo-full">
            <label>Mora com quem?</label>
            <input type="text" name="mora_com" value="<?= v($f,'mora_com') ?>" placeholder="Ex: cônjuge, filhos, sozinho(a)...">
        </div>
    </div>
    <div class="secao-nav">
        <button type="button" class="btn secao-anterior">← Anterior</button>
        <button type="button" class="btn proxima-secao">Próxima →</button>
    </div>
</div>

<!-- ===== SEÇÃO 2 — VIDA PROFISSIONAL ===== -->
<div class="anamnese-secao" id="secao-2">
    <h3 class="secao-titulo">2. Vida Profissional</h3>
    <div class="campos-grid">
        <div class="campo campo-full">
            <label>Atividade profissional</label>
            <input type="text" name="atividade_profissional" value="<?= v($f,'atividade_profissional') ?>">
        </div>
        <div class="campo campo-full">
            <label>Você gosta do que faz?</label>
            <textarea name="gosta_trabalho" rows="2"><?= v($f,'gosta_trabalho') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Você se sente estável no seu trabalho?</label>
            <textarea name="estavel_trabalho" rows="2"><?= v($f,'estavel_trabalho') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Outras atividades</label>
            <textarea name="outras_atividades" rows="2"><?= v($f,'outras_atividades') ?></textarea>
        </div>
    </div>
    <div class="secao-nav">
        <button type="button" class="btn secao-anterior">← Anterior</button>
        <button type="button" class="btn proxima-secao">Próxima →</button>
    </div>
</div>

<!-- ===== SEÇÃO 3 — SAÚDE FÍSICA ===== -->
<div class="anamnese-secao" id="secao-3">
    <h3 class="secao-titulo">3. Saúde e Comportamento</h3>
    <div class="campos-grid">
        <div class="campo campo-full">
            <label>Você já teve alguma doença grave? Qual? Quando?</label>
            <textarea name="doencas_graves" rows="2"><?= v($f,'doencas_graves') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Já fez alguma cirurgia? Qual? Quando?</label>
            <textarea name="cirurgias" rows="2"><?= v($f,'cirurgias') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Tem atualmente algum problema de saúde? (cérebro, fígado, coração, pulmão, pressão, etc.)</label>
            <textarea name="problemas_saude" rows="2"><?= v($f,'problemas_saude') ?></textarea>
        </div>
        <div class="campo">
            <label>Tem problemas cardíacos?</label>
            <div class="radio-group">
                <label><input type="radio" name="prob_cardiaco" value="1" <?= sim($f,'prob_cardiaco') ?>> Sim</label>
                <label><input type="radio" name="prob_cardiaco" value="0" <?= nao($f,'prob_cardiaco') ?>> Não</label>
            </div>
        </div>
        <div class="campo">
            <label>Tem diabetes?</label>
            <div class="radio-group">
                <label><input type="radio" name="diabetes" value="1" <?= sim($f,'diabetes') ?>> Sim</label>
                <label><input type="radio" name="diabetes" value="0" <?= nao($f,'diabetes') ?>> Não</label>
            </div>
        </div>
        <div class="campo">
            <label>Tem úlceras?</label>
            <div class="radio-group">
                <label><input type="radio" name="ulceras" value="1" <?= sim($f,'ulceras') ?>> Sim</label>
                <label><input type="radio" name="ulceras" value="0" <?= nao($f,'ulceras') ?>> Não</label>
            </div>
        </div>
        <div class="campo">
            <label>Está grávida?</label>
            <div class="radio-group">
                <label><input type="radio" name="gravida" value="1" <?= sim($f,'gravida') ?>> Sim</label>
                <label><input type="radio" name="gravida" value="0" <?= nao($f,'gravida') ?>> Não</label>
            </div>
        </div>
        <div class="campo">
            <label>Se sim, de quantos meses?</label>
            <input type="number" name="meses_gravida" min="1" max="9" value="<?= v($f,'meses_gravida') ?>">
        </div>
        <div class="campo">
            <label>Pressão arterial</label>
            <div class="radio-group">
                <label><input type="radio" name="pressao_arterial" value="baixa"  <?= sel($f,'pressao_arterial','baixa')  ? 'checked' : '' ?>> Baixa</label>
                <label><input type="radio" name="pressao_arterial" value="normal" <?= sel($f,'pressao_arterial','normal') ? 'checked' : '' ?>> Normal</label>
                <label><input type="radio" name="pressao_arterial" value="alta"   <?= sel($f,'pressao_arterial','alta')   ? 'checked' : '' ?>> Alta</label>
            </div>
        </div>
        <div class="campo">
            <label>Data do último eletrocardiograma</label>
            <input type="text" name="ultimo_ecg" value="<?= v($f,'ultimo_ecg') ?>" placeholder="MM/AAAA ou 'nunca fiz'">
        </div>
        <div class="campo campo-full">
            <label>Está fazendo algum tratamento de saúde? Qual?</label>
            <textarea name="tratamento_atual" rows="2"><?= v($f,'tratamento_atual') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Faz uso de algum medicamento? Qual? Qual a dose? Para que é indicado?</label>
            <textarea name="medicamentos" rows="3"><?= v($f,'medicamentos') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Você bebe? Com que frequência?</label>
            <textarea name="consumo_alcool" rows="2"><?= v($f,'consumo_alcool') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Consome ou já consumiu algum tipo de droga? Qual? Com que frequência?</label>
            <textarea name="consumo_drogas" rows="2"><?= v($f,'consumo_drogas') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Seu uso de bebida ou droga trouxe prejuízos à sua vida? Quais?</label>
            <textarea name="prejuizos_substancias" rows="2"><?= v($f,'prejuizos_substancias') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Já sentiu dificuldade em controlar esse uso?</label>
            <textarea name="dificuldade_controle" rows="2"><?= v($f,'dificuldade_controle') ?></textarea>
        </div>
    </div>
    <div class="secao-nav">
        <button type="button" class="btn secao-anterior">← Anterior</button>
        <button type="button" class="btn proxima-secao">Próxima →</button>
    </div>
</div>

<!-- ===== SEÇÃO 4 — ESTADO EMOCIONAL ===== -->
<div class="anamnese-secao" id="secao-4">
    <h3 class="secao-titulo">4. Estado Emocional Atual</h3>
    <p style="color:#aaa;margin-bottom:20px;">Marque todos os que se aplicam ao seu estado atual:</p>
    <div class="checkboxes-grid">
        <?php
        $emocoes = ['Depressivo(a)','Ansioso(a)','Calmo(a)','Preocupado(a)','Angustiado(a)',
                    'Desmotivado(a)','Irritado(a)','Alegre','Com falta de concentração',
                    'Com insônia','Irrequieto(a)','Normal'];
        foreach ($emocoes as $e): ?>
            <label class="checkbox-item">
                <input type="checkbox" name="estado_emocional[]" value="<?= $e ?>"
                       <?= checked($f,'estado_emocional',$e) ?>>
                <?= $e ?>
            </label>
        <?php endforeach; ?>
    </div>
    <div class="secao-nav">
        <button type="button" class="btn secao-anterior">← Anterior</button>
        <button type="button" class="btn proxima-secao">Próxima →</button>
    </div>
</div>

<!-- ===== SEÇÃO 5 — SAÚDE MENTAL ===== -->
<div class="anamnese-secao" id="secao-5">
    <h3 class="secao-titulo">5. Saúde Mental e Histórico Psiquiátrico</h3>
    <div class="campos-grid">
        <div class="campo campo-full">
            <label>Você ou alguém da família possui/possuiu distúrbios psicológicos? Quem? Qual problema? Em que nível hoje (0 a 10)?</label>
            <textarea name="disturbios_psicologicos" rows="3"><?= v($f,'disturbios_psicologicos') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Você ou alguém da família já foi internado em instituição psiquiátrica? Quem? Onde? Por quê?</label>
            <textarea name="internacoes_psiquiatricas" rows="3"><?= v($f,'internacoes_psiquiatricas') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Histórico familiar — marque os que existem na família e informe o grau de parentesco:</label>
            <div class="checkboxes-grid" style="margin-bottom:10px;">
                <?php foreach (['Problemas cardíacos','Esquizofrenia','Alcoolismo'] as $op): ?>
                    <label class="checkbox-item">
                        <input type="checkbox" name="hist_familiar_mental[]" value="<?= $op ?>"
                               <?= checked($f,'hist_familiar_mental',$op) ?>>
                        <?= $op ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <input type="text" name="hist_familiar_mental_parentesco"
                   value="<?= v($f,'hist_familiar_mental_parentesco') ?>"
                   placeholder="Grau de parentesco (ex: pai, mãe, irmão)">
        </div>
        <div class="campo campo-full">
            <label>Já teve algum surto psicótico? Como foi?</label>
            <textarea name="surto_psicotico" rows="2"><?= v($f,'surto_psicotico') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Já teve experiência de ver ou ouvir coisas que os outros não podiam? Vê vultos?</label>
            <textarea name="alucinacoes" rows="2"><?= v($f,'alucinacoes') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Já teve alguma passagem marcante como sensação de morte, projeções, desdobramentos ou regressões?</label>
            <textarea name="experiencias_morte" rows="2"><?= v($f,'experiencias_morte') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Já se sentiu perseguido ou ameaçado por alguém?</label>
            <textarea name="sensacao_perseguicao" rows="2"><?= v($f,'sensacao_perseguicao') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Já teve a sensação de não conseguir ordenar os pensamentos em sua cabeça, por horas ou dias?</label>
            <textarea name="desordem_pensamentos" rows="2"><?= v($f,'desordem_pensamentos') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Já viveu alguma situação em que seus pensamentos estavam muito acelerados, que você não conseguia acompanhá-los?</label>
            <textarea name="pensamentos_acelerados" rows="2"><?= v($f,'pensamentos_acelerados') ?></textarea>
        </div>
    </div>
    <div class="secao-nav">
        <button type="button" class="btn secao-anterior">← Anterior</button>
        <button type="button" class="btn proxima-secao">Próxima →</button>
    </div>
</div>

<!-- ===== SEÇÃO 6 — PROBLEMAS NO DOMICÍLIO ===== -->
<div class="anamnese-secao" id="secao-6">
    <h3 class="secao-titulo">6. Problemas no Ambiente Doméstico</h3>
    <p style="color:#aaa;margin-bottom:20px;">Quais destes problemas existem em sua casa? (Marque todos que se aplicam)</p>
    <div class="checkboxes-grid">
        <?php foreach (['Alcoolismo','Consumo de drogas','Doenças','Brigas constantes','Instabilidade econômica','Problemas legais','Problemas psicológicos','Outros'] as $op): ?>
            <label class="checkbox-item">
                <input type="checkbox" name="problemas_domicilio[]" value="<?= $op ?>"
                       <?= checked($f,'problemas_domicilio',$op) ?>>
                <?= $op ?>
            </label>
        <?php endforeach; ?>
    </div>
    <div class="secao-nav">
        <button type="button" class="btn secao-anterior">← Anterior</button>
        <button type="button" class="btn proxima-secao">Próxima →</button>
    </div>
</div>

<!-- ===== SEÇÃO 7 — REATIVIDADE ===== -->
<div class="anamnese-secao" id="secao-7">
    <h3 class="secao-titulo">7. Reatividade e Agressividade</h3>
    <div class="campos-grid">
        <div class="campo campo-full">
            <label>De 0 a 10, quanto você se considera reativo (pavio curto)? <strong><?= v($f,'nivel_reatividade','5') ?></strong></label>
            <input type="range" name="nivel_reatividade" min="0" max="10"
                   value="<?= v($f,'nivel_reatividade','5') ?>"
                   oninput="this.previousElementSibling.querySelector('strong').textContent = this.value">
            <div style="display:flex;justify-content:space-between;font-size:12px;color:#aaa;margin-top:4px;">
                <span>0 — Nada reativo</span><span>10 — Muito reativo</span>
            </div>
        </div>
        <div class="campo campo-full">
            <label>Você já brigou fisicamente com alguém? Se sim, quantas vezes? Por quê?</label>
            <textarea name="brigas_fisicas" rows="3"><?= v($f,'brigas_fisicas') ?></textarea>
        </div>
    </div>
    <div class="secao-nav">
        <button type="button" class="btn secao-anterior">← Anterior</button>
        <button type="button" class="btn proxima-secao">Próxima →</button>
    </div>
</div>

<!-- ===== SEÇÃO 8 — ESPIRITUALIDADE ===== -->
<div class="anamnese-secao" id="secao-8">
    <h3 class="secao-titulo">8. Espiritualidade</h3>
    <div class="campos-grid">
        <div class="campo campo-full">
            <label>Você atualmente pratica alguma religião? Qual?</label>
            <input type="text" name="pratica_religiao" value="<?= v($f,'pratica_religiao') ?>">
        </div>
        <div class="campo campo-full">
            <label>O que você busca em sua prática religiosa?</label>
            <textarea name="busca_religiosa" rows="2"><?= v($f,'busca_religiosa') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Pratica algum tipo de meditação ou prática espiritual? Qual?</label>
            <textarea name="pratica_meditacao" rows="2"><?= v($f,'pratica_meditacao') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Tem algum grau de mediunidade? É possível descrever?</label>
            <textarea name="mediunidade" rows="2"><?= v($f,'mediunidade') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Já teve alguma experiência espiritual marcante? Como foi?</label>
            <textarea name="exp_espirituais" rows="3"><?= v($f,'exp_espirituais') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Você acredita que o desenvolvimento espiritual pode te ajudar? Em que?</label>
            <textarea name="desenvolvimento_esp" rows="2"><?= v($f,'desenvolvimento_esp') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Já tomou Ayahuasca ou outras Medicinas da Floresta? Se sim, em que ocasião e como foi?</label>
            <textarea name="exp_ayahuasca" rows="3"><?= v($f,'exp_ayahuasca') ?></textarea>
        </div>
        <div class="campo campo-full">
            <label>Como soube da Fraternidade Essência da Chama Trina?</label>
            <input type="text" name="como_soube" value="<?= v($f,'como_soube') ?>">
        </div>
        <div class="campo campo-full">
            <label>O que você está buscando neste ritual?</label>
            <div class="checkboxes-grid" style="margin-bottom:10px;">
                <?php foreach (['Religião','Autoconhecimento','Espiritualidade','Curiosidade','Outros'] as $op): ?>
                    <label class="checkbox-item">
                        <input type="checkbox" name="busca_ritual[]" value="<?= $op ?>"
                               <?= checked($f,'busca_ritual',$op) ?>>
                        <?= $op ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="secao-nav">
        <button type="button" class="btn secao-anterior">← Anterior</button>
        <button type="button" class="btn proxima-secao">Próxima →</button>
    </div>
</div>

<!-- ===== SEÇÃO 9 — OBSERVAÇÕES E TERMO ===== -->
<div class="anamnese-secao" id="secao-9">
    <h3 class="secao-titulo">9. Observações Gerais</h3>
    <div class="campo campo-full" style="margin-bottom:35px;">
        <label>Se quiser incluir algo mais que não foi perguntado acima:</label>
        <textarea name="observacoes" rows="4"><?= v($f,'observacoes') ?></textarea>
    </div>

    <h3 class="secao-titulo">10. Termo de Responsabilidade e Uso de Imagem</h3>
    <div class="anamnese-termo">
        <p>Eu, abaixo identificado(a), venho de livre e espontânea vontade solicitar o ingresso às cerimônias espirituais da <strong>Fraternidade Essência da Chama Trina</strong>.</p>
        <p>Declaro que participei ou serei informado(a) da reunião obrigatória, onde tomarei ciência da natureza destes trabalhos, da preparação exigida nos dias anteriores ao ritual, e da condição expressa de permanecer no local até o encerramento do ritual.</p>
        <p>Declaro estar ciente da <strong>proibição de portar ou usar quaisquer substâncias proscritas pela lei penal brasileira, bebidas alcoólicas, armas brancas ou de fogo</strong> durante as atividades.</p>
        <p>Declaro que <strong>não fotografarei, filmarei ou gravarei</strong> o ritual, na parte ou no todo.</p>
        <p>Declaro que sou maior de 18 anos, estou em pleno gozo das minhas faculdades mentais, e que venho participar por livre e espontânea vontade, responsabilizando-me pelas consequências de minha participação.</p>
        <p>Declaro ainda que <strong>todos os dados acima preenchidos são verdadeiros</strong> e autorizo a Fraternidade Essência da Chama Trina a utilizar fotografias e filmagens dos rituais, nos quais posso aparecer, para fins de divulgação espiritual e valorização das práticas sagradas.</p>
        <p>Estou ciente de que todas as medidas adotadas visam minha segurança e bem-estar.</p>
    </div>

    <label class="checkbox-item anamnese-aceite">
        <input type="checkbox" name="aceite_termo" value="1" <?= !empty($f['aceite_termo']) ? 'checked' : '' ?> required>
        <span>Li e aceito o Termo de Responsabilidade e Uso de Imagem acima.</span>
    </label>

    <div class="secao-nav" style="margin-top:30px;">
        <button type="button" class="btn secao-anterior">← Anterior</button>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button type="submit" name="acao" value="rascunho" class="btn" style="background:rgba(255,255,255,0.1);">
                Salvar rascunho
            </button>
            <button type="submit" name="acao" value="enviar" class="btn whatsapp">
                Enviar ficha completa
            </button>
        </div>
    </div>
</div>

</form>
</div>

<script>
const tabs  = document.querySelectorAll('.anamnese-tab');
const secoes = document.querySelectorAll('.anamnese-secao');

function irPara(n) {
    tabs.forEach(t => t.classList.toggle('ativo', +t.dataset.secao === n));
    secoes.forEach(s => s.classList.toggle('ativa', s.id === 'secao-' + n));
    window.scrollTo({ top: document.getElementById('anamnese-tabs').offsetTop - 80, behavior: 'smooth' });
}

tabs.forEach(t => t.addEventListener('click', () => irPara(+t.dataset.secao)));

document.querySelectorAll('.proxima-secao').forEach(btn => {
    btn.addEventListener('click', () => {
        const atual = +document.querySelector('.anamnese-secao.ativa').id.replace('secao-', '');
        irPara(Math.min(atual + 1, 9));
    });
});

document.querySelectorAll('.secao-anterior').forEach(btn => {
    btn.addEventListener('click', () => {
        const atual = +document.querySelector('.anamnese-secao.ativa').id.replace('secao-', '');
        irPara(Math.max(atual - 1, 0));
    });
});
</script>

<?php include __DIR__ . '/../includes/layout-bottom.php'; ?>
