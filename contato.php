<?php
require_once __DIR__ . '/includes/config.php';

$title       = 'Contato';
$description = 'Entre em contato com a Fraternidade Essência da Chama Trina para dúvidas, informações ou para iniciar seu caminho espiritual.';
$url         = BASE_URL . '/contato.php';

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about" style="text-align:center;max-width:620px;margin:60px auto 50px;">
        <h2>Entre em Contato</h2>
        <p>Tem dúvidas, quer saber mais sobre as vivências ou está pronto para iniciar seu caminho? Escolha a forma que preferir.</p>
    </div>

    <div class="contato-wrap">

        <div class="actions" style="margin-bottom:40px;">
            <a class="btn whatsapp" href="https://wa.me/5551992563279" target="_blank" rel="noopener">
                <img src="<?= BASE_URL ?>/img/whatsapp.png" class="icon" alt=""> Falar no WhatsApp
            </a>
            <a class="btn instagram" href="https://instagram.com/fraternidadechamatrina" target="_blank" rel="noopener">
                <img src="<?= BASE_URL ?>/img/instagram.png" class="icon" alt=""> Instagram
            </a>
        </div>

        <div class="form-box">
            <h2>Enviar mensagem</h2>
            <p>Preencha o formulário abaixo e entraremos em contato em breve.</p>

            <form action="https://formspree.io/f/xbdpjlbp" method="POST">

                <div class="campos-grid">
                    <div class="campo">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" placeholder="Seu nome completo" required>
                    </div>
                    <div class="campo">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                    </div>
                </div>

                <div class="campo">
                    <label for="whatsapp">WhatsApp</label>
                    <input type="tel" id="whatsapp" name="whatsapp" placeholder="(51) 9 9999-9999" required>
                </div>

                <div class="campo">
                    <label for="mensagem">Mensagem</label>
                    <textarea id="mensagem" name="mensagem" rows="5"
                        placeholder="O que você busca ou deseja desenvolver?" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Enviar mensagem</button>

            </form>
        </div>

    </div>

</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
