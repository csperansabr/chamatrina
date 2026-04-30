<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/anamnese/');
    exit;
}

$pid  = $_SESSION['participante_id'];
$acao = $_POST['acao'] ?? 'rascunho';

$aceite = !empty($_POST['aceite_termo']) ? 1 : 0;
$status = ($acao === 'enviar' && $aceite) ? 'completo' : 'incompleto';

$data = [
    // Ficha Cadastral
    'rg'                         => trim($_POST['rg'] ?? ''),
    'orgao_expedidor'            => trim($_POST['orgao_expedidor'] ?? ''),
    'instagram'                  => trim($_POST['instagram'] ?? ''),
    'facebook'                   => trim($_POST['facebook'] ?? ''),
    'escolaridade'               => trim($_POST['escolaridade'] ?? ''),
    'profissao'                  => trim($_POST['profissao'] ?? ''),
    'sexo'                       => trim($_POST['sexo'] ?? ''),
    'end_rua'                    => trim($_POST['end_rua'] ?? ''),
    'end_num'                    => trim($_POST['end_num'] ?? ''),
    'end_bairro'                 => trim($_POST['end_bairro'] ?? ''),
    'end_cidade'                 => trim($_POST['end_cidade'] ?? ''),
    'end_estado'                 => strtoupper(trim($_POST['end_estado'] ?? '')),
    'contato_emergencia'         => trim($_POST['contato_emergencia'] ?? ''),
    'contato_emergencia_tel'     => trim($_POST['contato_emergencia_tel'] ?? ''),
    // Seção 1
    'estado_civil'               => trim($_POST['estado_civil'] ?? ''),
    'tem_filhos'                 => isset($_POST['tem_filhos']) ? (int)$_POST['tem_filhos'] : null,
    'qtd_filhos'                 => $_POST['qtd_filhos'] !== '' ? (int)$_POST['qtd_filhos'] : null,
    'mora_com'                   => trim($_POST['mora_com'] ?? ''),
    // Seção 2
    'atividade_profissional'     => trim($_POST['atividade_profissional'] ?? ''),
    'gosta_trabalho'             => trim($_POST['gosta_trabalho'] ?? ''),
    'estavel_trabalho'           => trim($_POST['estavel_trabalho'] ?? ''),
    'outras_atividades'          => trim($_POST['outras_atividades'] ?? ''),
    // Seção 3
    'doencas_graves'             => trim($_POST['doencas_graves'] ?? ''),
    'cirurgias'                  => trim($_POST['cirurgias'] ?? ''),
    'problemas_saude'            => trim($_POST['problemas_saude'] ?? ''),
    'prob_cardiaco'              => isset($_POST['prob_cardiaco']) ? (int)$_POST['prob_cardiaco'] : null,
    'diabetes'                   => isset($_POST['diabetes']) ? (int)$_POST['diabetes'] : null,
    'ulceras'                    => isset($_POST['ulceras']) ? (int)$_POST['ulceras'] : null,
    'gravida'                    => isset($_POST['gravida']) ? (int)$_POST['gravida'] : null,
    'meses_gravida'              => $_POST['meses_gravida'] !== '' ? (int)$_POST['meses_gravida'] : null,
    'pressao_arterial'           => in_array($_POST['pressao_arterial'] ?? '', ['baixa','normal','alta']) ? $_POST['pressao_arterial'] : null,
    'ultimo_ecg'                 => trim($_POST['ultimo_ecg'] ?? ''),
    'tratamento_atual'           => trim($_POST['tratamento_atual'] ?? ''),
    'medicamentos'               => trim($_POST['medicamentos'] ?? ''),
    'consumo_alcool'             => trim($_POST['consumo_alcool'] ?? ''),
    'consumo_drogas'             => trim($_POST['consumo_drogas'] ?? ''),
    'prejuizos_substancias'      => trim($_POST['prejuizos_substancias'] ?? ''),
    'dificuldade_controle'       => trim($_POST['dificuldade_controle'] ?? ''),
    // Seção 4 (JSON)
    'estado_emocional'           => json_encode($_POST['estado_emocional'] ?? []),
    // Seção 5
    'disturbios_psicologicos'    => trim($_POST['disturbios_psicologicos'] ?? ''),
    'internacoes_psiquiatricas'  => trim($_POST['internacoes_psiquiatricas'] ?? ''),
    'hist_familiar_mental'       => json_encode($_POST['hist_familiar_mental'] ?? []),
    'surto_psicotico'            => trim($_POST['surto_psicotico'] ?? ''),
    'alucinacoes'                => trim($_POST['alucinacoes'] ?? ''),
    'experiencias_morte'         => trim($_POST['experiencias_morte'] ?? ''),
    'sensacao_perseguicao'       => trim($_POST['sensacao_perseguicao'] ?? ''),
    'desordem_pensamentos'       => trim($_POST['desordem_pensamentos'] ?? ''),
    'pensamentos_acelerados'     => trim($_POST['pensamentos_acelerados'] ?? ''),
    // Seção 6 (JSON)
    'problemas_domicilio'        => json_encode($_POST['problemas_domicilio'] ?? []),
    // Seção 7
    'nivel_reatividade'          => (int)($_POST['nivel_reatividade'] ?? 5),
    'brigas_fisicas'             => trim($_POST['brigas_fisicas'] ?? ''),
    // Seção 8
    'pratica_religiao'           => trim($_POST['pratica_religiao'] ?? ''),
    'busca_religiosa'            => trim($_POST['busca_religiosa'] ?? ''),
    'pratica_meditacao'          => trim($_POST['pratica_meditacao'] ?? ''),
    'mediunidade'                => trim($_POST['mediunidade'] ?? ''),
    'exp_espirituais'            => trim($_POST['exp_espirituais'] ?? ''),
    'desenvolvimento_esp'        => trim($_POST['desenvolvimento_esp'] ?? ''),
    'exp_ayahuasca'              => trim($_POST['exp_ayahuasca'] ?? ''),
    'como_soube'                 => trim($_POST['como_soube'] ?? ''),
    'busca_ritual'               => json_encode($_POST['busca_ritual'] ?? []),
    // Seção 9
    'observacoes'                => trim($_POST['observacoes'] ?? ''),
    // Termo
    'aceite_termo'               => $aceite,
    'data_aceite'                => $aceite ? date('Y-m-d H:i:s') : null,
    'status'                     => $status,
    'participante_id'            => $pid,
];

// Verificar se já existe ficha
$existe = $pdo->prepare("SELECT id FROM anamneses WHERE participante_id = ?");
$existe->execute([$pid]);

if ($existe->fetch()) {
    $sets  = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
    $stmt  = $pdo->prepare("UPDATE anamneses SET $sets WHERE participante_id = :participante_id");
} else {
    $cols  = implode(', ', array_keys($data));
    $phs   = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));
    $stmt  = $pdo->prepare("INSERT INTO anamneses ($cols) VALUES ($phs)");
}

$stmt->execute($data);

$msg = $status === 'completo' ? 'completo' : 'salvo';
header('Location: ' . BASE_URL . '/anamnese/ficha.php?msg=' . $msg);
exit;
