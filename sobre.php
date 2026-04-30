<?php
require_once __DIR__ . '/includes/config.php';

$title       = 'Sobre a Chama Trina';
$description = 'Conheça a proposta espiritual da Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/sobre.php';

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about">
        <h2>Sobre a Chama Trina</h2>

        <p>A Chama Trina é um espaço de desenvolvimento espiritual e terapêutico que integra, de forma consciente e responsável, fundamentos da Umbanda, práticas xamânicas e o uso ritualístico das Medicinas da Floresta — Ayahuasca, Rapé, Sananga e Tabaco.</p>

        <p>A Fraternidade nasce com o propósito de oferecer um caminho estruturado de autoconhecimento, expansão de consciência e fortalecimento espiritual, onde cada indivíduo é conduzido a assumir responsabilidade pelo próprio processo — sem dependência, sem promessas vazias e sem atalhos.</p>

        <p>Os trabalhos realizados envolvem rituais espirituais, atendimentos, estudos e vivências, conduzidos com seriedade, ética e fundamento. A espiritualidade, aqui, não é tratada como fuga da realidade, mas como ferramenta de alinhamento, equilíbrio e transformação prática na vida do indivíduo.</p>

        <p>A integração entre Umbanda e Xamanismo permite uma abordagem ampla e profunda, respeitando tradições ancestrais e promovendo uma vivência consciente da espiritualidade. O uso da Ayahuasca ocorre exclusivamente em contexto ritualístico, com preparo, orientação e responsabilidade.</p>

        <p>A Fraternidade Essência da Chama Trina é um ponto de encontro entre espiritualidade e consciência — onde o desenvolvimento não é terceirizado, é construído com presença, disciplina e propósito.</p>
    </div>

    <div class="section">
        <h2 style="font-size:20px;font-weight:800;margin-bottom:16px;">Esse caminho é para você se…</h2>
        <div class="list">
            <div>• Sente que precisa de direcionamento espiritual</div>
            <div>• Busca evolução além do superficial</div>
            <div>• Quer compreender sua mediunidade</div>
            <div>• Procura um caminho com fundamento e responsabilidade</div>
        </div>
    </div>

    <div class="form-box" style="text-align:center;margin-bottom:0;">
        <h2>Pronto para iniciar seu caminho?</h2>
        <p>Entre em contato e conheça como funciona a Fraternidade.</p>
        <a href="<?= BASE_URL ?>/contato.php" class="btn btn-primary" style="margin-top:10px;">
            Enviar mensagem
        </a>
    </div>

</div>

<!-- EQUIPE -->
<div class="team">
    <h2>Quem conduz a Chama Trina</h2>
    <p class="team-intro">
        A Fraternidade Essência da Chama Trina é sustentada por pessoas comprometidas com um caminho espiritual sério, responsável e fundamentado.
    </p>

    <div class="team-grid">

        <div class="team-card">
            <img src="<?= BASE_URL ?>/img/equipe/lari.jpg" alt="Lari">
            <h3>Lari</h3>
            <span>Equipe de Apoio e Organização</span>
            <p>Sincera, transparente e acolhedora, Lari atua como um dos pilares de apoio da Fraternidade. Sua conexão com a Umbanda vem desde a infância, consolidando sua atuação como ogã e sustentando a organização dos trabalhos.</p>
        </div>

        <div class="team-card">
            <img src="<?= BASE_URL ?>/img/equipe/zeli.jpg" alt="Zeli">
            <h3>Zeli</h3>
            <span>Sacerdotisa e Guardiã dos Fundamentos</span>
            <p>Sacerdotisa, erveira e benzedeira, conduz os trabalhos com firmeza, acolhimento e profundo respeito às tradições. Atua no equilíbrio espiritual e na sustentação energética da Fraternidade.</p>
        </div>

        <div class="team-card">
            <img src="<?= BASE_URL ?>/img/equipe/cleiton.jpg" alt="Cleiton">
            <h3>Cleiton</h3>
            <span>Dirigente Espiritual e Facilitador</span>
            <p>Responsável pela condução dos trabalhos, integra fundamentos da Umbanda, práticas xamânicas e Medicinas da Floresta. Atua no desenvolvimento espiritual com foco em consciência, responsabilidade e transformação prática.</p>
        </div>

    </div>
</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
