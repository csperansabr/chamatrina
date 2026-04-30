<?php
include __DIR__ . '/includes/config.php';

$title       = 'Vivências Espirituais';
$description = 'Participe de rituais e vivências com Ayahuasca, Umbanda e práticas xamânicas com fundamento e responsabilidade.';
$url         = BASE_URL . '/vivencias.php';

include __DIR__ . '/includes/layout-top.php';
?>
<div class="hero-vivencias">

    <h1>Vivências Espirituais com Propósito</h1>

    <p>
        Um espaço de desenvolvimento espiritual real, com fundamento, responsabilidade e verdade.
    </p>
</div>

<div class="container">

    <h1>Vivências</h1>

    <p class="lead">
        Trabalhos espirituais conduzidos com responsabilidade, fundamento e propósito.
    </p>

    <div class="vivencias-nav">
        <a href="#masculino">Sagrado Masculino</a>
        <a href="#feminino">Sagrado Feminino</a>
        <a href="#misto">Cerimônias Mistas</a>
    </div>

    <section id="masculino" class="card-vivencia">

        <img src="/img/capa_masculino.png" alt="Sagrado Masculino">

        <div class="content">

            <h2>Sagrado Masculino</h2>

            <p>
                Um chamado para homens que reconhecem que precisam retomar direção, responsabilidade e presença.
            </p>

            <a href="contato.php" class="btn">
                Consultar disponibilidade
            </a>

        </div>

    </section>

    <section id="feminino" class="card-vivencia">

        <img src="/img/capa_feminino.png" alt="Sagrado Feminino">

        <div class="content">

            <h2>Sagrado Feminino</h2>

            <p>
                Espaço de acolhimento, reconexão e fortalecimento da essência feminina.
            </p>
            <a href="contato.php" class="btn">
                Consultar disponibilidade
            </a>
        </div>

    </section>

    <section id="misto" class="card-vivencia">

        <img src="/img/capa_cerimoniamista.png" alt="Cerimônias Mistas">

        <div class="content">

            <h2>Cerimônias Mistas</h2>

            <p>
                Trabalhos coletivos que integram diferentes energias em um mesmo propósito de desenvolvimento.
            </p>
            <a href="contato.php" class="btn">
                Consultar disponibilidade
            </a>

        </div>

    </section>
    <div class="prova-social">

        <h2>Vivências que transformam</h2>

        <p>
            Cada encontro é conduzido com seriedade, respeito e responsabilidade espiritual.
            Não se trata de experiência superficial — é processo.
        </p>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>