<?php $current = basename($_SERVER['PHP_SELF']); ?>

<header class="navbar">
<div class="nav-container">

    <ul class="menu">
        <li><a href="<?= BASE_URL ?>/index.php" class="<?= $current == 'index.php' ? 'active' : '' ?>">Início</a></li>

        <li><a href="<?= BASE_URL ?>/sobre.php" class="<?= $current == 'sobre.php' ? 'active' : '' ?>">Sobre</a></li>
			
        <!--<li><a href="<?= BASE_URL ?>/vivencias.php" class="<?= $current == 'vivencias.php' ? 'active' : '' ?>">Vivências</a></li>-->
        <li class="dropdown">
            <a href="#">Trabalhos ▾</a>

            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>/vivencias.php">Vivências</a></li>
                <li><a href="<?= BASE_URL ?>/cursos.php">Cursos</a></li>
                <li><a href="<?= BASE_URL ?>/atendimentos.php">Atendimentos</a></li>
            </ul>
        </li>

        <li><a href="<?= BASE_URL ?>/eventos.php" class="<?= $current == 'eventos.php' ? 'active' : '' ?>">Eventos</a></li>

        <li><a href="<?= BASE_URL ?>/galeria.php" class="<?= $current == 'galeria.php' ? 'active' : '' ?>">Galeria</a></li>

        <li><a href="<?= BASE_URL ?>/contato.php" class="<?= $current == 'contato.php' ? 'active' : '' ?>">Contato</a></li>
    </ul>

    <img src="img/logo.png" class="logo-nav">

</div>
</header>