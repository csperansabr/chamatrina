<?php $current = basename($_SERVER['PHP_SELF']); ?>

<header class="navbar" id="navbar">
<div class="nav-container">

    <a href="<?= BASE_URL ?>/index.php" class="nav-logo">
        <img src="<?= BASE_URL ?>/img/logo.png" alt="Fraternidade Chama Trina">
    </a>

    <button class="nav-toggle" id="nav-toggle" aria-label="Abrir menu" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>

    <ul class="menu" id="nav-menu">
        <li><a href="<?= BASE_URL ?>/index.php"     class="<?= $current === 'index.php'     ? 'active' : '' ?>">Início</a></li>
        <li><a href="<?= BASE_URL ?>/sobre.php"     class="<?= $current === 'sobre.php'     ? 'active' : '' ?>">Sobre</a></li>

        <li class="dropdown">
            <a href="#">Trabalhos</a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>/vivencias.php">Vivências</a></li>
                <li><a href="<?= BASE_URL ?>/cursos.php">Cursos</a></li>
                <li><a href="<?= BASE_URL ?>/atendimentos.php">Atendimentos</a></li>
            </ul>
        </li>

        <li><a href="<?= BASE_URL ?>/eventos.php"   class="<?= $current === 'eventos.php'   ? 'active' : '' ?>">Eventos</a></li>
        <li><a href="<?= BASE_URL ?>/blog.php"      class="<?= $current === 'blog.php'       ? 'active' : '' ?>">Blog</a></li>
        <li><a href="<?= BASE_URL ?>/galeria.php"   class="<?= $current === 'galeria.php'   ? 'active' : '' ?>">Galeria</a></li>
        <li><a href="<?= BASE_URL ?>/contato.php"   class="<?= $current === 'contato.php'   ? 'active' : '' ?>">Contato</a></li>

        <li><a href="<?= BASE_URL ?>/anamnese/" class="nav-ficha">Minha Ficha</a></li>
    </ul>

</div>
</header>

<script>
(function () {
    var navbar  = document.getElementById('navbar');
    var toggle  = document.getElementById('nav-toggle');
    var menu    = document.getElementById('nav-menu');

    // Efeito de scroll
    window.addEventListener('scroll', function () {
        navbar.classList.toggle('scrolled', window.scrollY > 40);
    }, { passive: true });

    // Hamburger
    toggle.addEventListener('click', function () {
        var open = menu.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open);
    });

    // Fecha menu ao clicar em link (mobile)
    menu.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () {
            menu.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });
})();
</script>
