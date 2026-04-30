<?php
/**
 * SCRIPT DE INSTALAÇÃO — FASE 3 — Execute UMA VEZ e depois delete este arquivo.
 * Acesse: https://chamatrina.org.br/setup_anamnese.php
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$erros  = [];
$passos = [];

// Tabela de participantes
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS participantes (
        id              INT AUTO_INCREMENT PRIMARY KEY,
        nome            VARCHAR(255) NOT NULL,
        email           VARCHAR(255) NOT NULL UNIQUE,
        cpf             VARCHAR(11)  NOT NULL UNIQUE,
        senha           VARCHAR(255) NOT NULL,
        data_nascimento DATE NOT NULL,
        whatsapp        VARCHAR(20),
        criado_em       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>participantes</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em participantes: ' . $e->getMessage();
}

// Tabela de anamneses
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS anamneses (
        id                      INT AUTO_INCREMENT PRIMARY KEY,
        participante_id         INT NOT NULL UNIQUE,

        -- Ficha Cadastral (complementar)
        rg                      VARCHAR(30),
        orgao_expedidor         VARCHAR(30),
        instagram               VARCHAR(100),
        facebook                VARCHAR(100),
        escolaridade            VARCHAR(100),
        profissao               VARCHAR(100),
        sexo                    VARCHAR(30),
        end_rua                 VARCHAR(255),
        end_num                 VARCHAR(20),
        end_bairro              VARCHAR(100),
        end_cidade              VARCHAR(100),
        end_estado              VARCHAR(2),
        contato_emergencia      VARCHAR(255),
        contato_emergencia_tel  VARCHAR(20),

        -- Seção 1 — Vida Familiar
        estado_civil            VARCHAR(80),
        tem_filhos              TINYINT(1),
        qtd_filhos              INT,
        mora_com                VARCHAR(255),

        -- Seção 2 — Vida Profissional
        atividade_profissional  VARCHAR(255),
        gosta_trabalho          TEXT,
        estavel_trabalho        TEXT,
        outras_atividades       TEXT,

        -- Seção 3 — Saúde Física
        doencas_graves          TEXT,
        cirurgias               TEXT,
        problemas_saude         TEXT,
        prob_cardiaco           TINYINT(1),
        diabetes                TINYINT(1),
        ulceras                 TINYINT(1),
        gravida                 TINYINT(1),
        meses_gravida           INT,
        pressao_arterial        ENUM('baixa','normal','alta'),
        ultimo_ecg              VARCHAR(20),
        tratamento_atual        TEXT,
        medicamentos            TEXT,
        consumo_alcool          TEXT,
        consumo_drogas          TEXT,
        prejuizos_substancias   TEXT,
        dificuldade_controle    TEXT,

        -- Seção 4 — Estado Emocional (JSON)
        estado_emocional        TEXT,

        -- Seção 5 — Saúde Mental
        disturbios_psicologicos TEXT,
        internacoes_psiquiatricas TEXT,
        hist_familiar_mental    TEXT,
        surto_psicotico         TEXT,
        alucinacoes             TEXT,
        experiencias_morte      TEXT,
        sensacao_perseguicao    TEXT,
        desordem_pensamentos    TEXT,
        pensamentos_acelerados  TEXT,

        -- Seção 6 — Problemas no Domicílio (JSON)
        problemas_domicilio     TEXT,

        -- Seção 7 — Reatividade
        nivel_reatividade       INT,
        brigas_fisicas          TEXT,

        -- Seção 8 — Espiritualidade
        pratica_religiao        VARCHAR(255),
        busca_religiosa         TEXT,
        pratica_meditacao       TEXT,
        mediunidade             TEXT,
        exp_espirituais         TEXT,
        desenvolvimento_esp     TEXT,
        exp_ayahuasca           TEXT,
        como_soube              TEXT,
        busca_ritual            VARCHAR(255),

        -- Seção 9 — Observações Gerais
        observacoes             TEXT,

        -- Seção 10 — Termo
        aceite_termo            TINYINT(1) DEFAULT 0,
        data_aceite             DATETIME,

        status                  ENUM('incompleto','completo') DEFAULT 'incompleto',
        criado_em               TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        atualizado_em           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        FOREIGN KEY (participante_id) REFERENCES participantes(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $passos[] = '✔ Tabela <strong>anamneses</strong> criada.';
} catch (PDOException $e) {
    $erros[] = 'Erro em anamneses: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Setup Anamnese — ChamaTrina</title>
<style>
  body { font-family: Arial; background:#0f172a; color:#fff; padding:40px; max-width:600px; margin:auto; }
  h1   { color:#25D366; }
  .ok  { color:#4ade80; margin:8px 0; }
  .err { color:#f87171; margin:8px 0; }
  .box { background:rgba(255,255,255,0.07); padding:25px; border-radius:12px; margin-top:25px; }
  .warn { color:#fbbf24; font-weight:bold; margin-top:15px; }
</style>
</head>
<body>
<h1>Instalação — Fase 3 (Anamnese)</h1>
<?php foreach ($passos as $p): ?><p class="ok"><?= $p ?></p><?php endforeach; ?>
<?php foreach ($erros  as $e): ?><p class="err">✘ <?= htmlspecialchars($e) ?></p><?php endforeach; ?>
<?php if (empty($erros)): ?>
<div class="box">
    <h2>Concluído!</h2>
    <p>Tabelas de participantes e anamneses criadas com sucesso.</p>
    <p class="warn">⚠ Delete este arquivo (setup_anamnese.php) do servidor imediatamente.</p>
    <p style="margin-top:15px;"><a href="<?= BASE_URL ?>/anamnese/" style="color:#25D366;">→ Ir para a área de anamnese</a></p>
</div>
<?php else: ?>
<div class="box"><p style="color:#f87171;">Verifique as credenciais do banco em includes/config.php.</p></div>
<?php endif; ?>
</body>
</html>
