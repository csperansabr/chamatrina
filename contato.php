<?php
$title = "Contato | Fraternidade Essência da Chama Trina";
$description = "Entre em contato conosco para dúvidas ou parceria.";
$url = "http://chamatrina.org.br/contato.php";

include __DIR__ . '/includes/layout-top.php';
?>
<div class="container">
<h1>Contato</h1>

<a class="btn whatsapp" href="https://wa.me/5551992563279">
Falar no WhatsApp
</a>

<form action="https://formspree.io/f/xbdpjlbp" method="POST" class="form-contato">

    <div class="form-row">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="E-mail" required>
    </div>

    <div class="form-row">
        <input type="tel" name="whatsapp" placeholder="WhatsApp" required>
    </div>

    <div class="form-row">
        <textarea name="mensagem" placeholder="O que você busca ou deseja desenvolver?" rows="5" required></textarea>
    </div>

    <button type="submit">Enviar Formulário</button>

</form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>