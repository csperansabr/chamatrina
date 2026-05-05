<?php
require_once __DIR__ . '/includes/config.php';

$status = $_GET['status'] ?? null;
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

            <?php if ($status === 'ok'): ?>
            <div class="form-aviso form-aviso--ok">Mensagem enviada com sucesso! Entraremos em contato em breve.</div>
            <?php elseif ($status === 'erro'): ?>
            <div class="form-aviso form-aviso--erro">Não foi possível enviar sua mensagem. Tente novamente ou fale pelo WhatsApp.</div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/contato-enviar.php" method="POST">

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

                <div class="campo" style="margin-top:20px;">
                    <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-weight:normal;font-size:14px;color:var(--text);">
                        <input type="checkbox" name="lgpd_aceite" required
                               style="width:18px;height:18px;flex-shrink:0;margin-top:2px;accent-color:var(--violet);">
                        <span>
                            Autorizo o uso dos meus dados para retorno de contato, conforme a
                            <a href="<?= BASE_URL ?>/politica-privacidade.php" target="_blank"
                               style="color:var(--violet-lite);text-decoration:underline;">Política de Privacidade</a>.
                        </span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Enviar mensagem</button>

            </form>
        </div>

    </div>

</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
