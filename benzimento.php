<?php
require_once __DIR__ . '/includes/config.php';

$status      = $_GET['status'] ?? null;
$title       = 'Benzimento Online';
$description = 'Solicite um benzimento online à Fraternidade Essência da Chama Trina. Trabalho espiritual de cuidado, limpeza e proteção realizado à distância.';
$url         = BASE_URL . '/benzimento.php';

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about" style="text-align:center;max-width:640px;margin:60px auto 50px;">
        <h2>Benzimento Online</h2>

        <p>
        O benzimento é um trabalho espiritual de cuidado, limpeza e proteção que pode ser realizado à distância, por meio da intenção, oração e conexão espiritual.
        </p>

        <p>
        Para que o atendimento seja conduzido com responsabilidade e direcionamento adequado, preencha o formulário com calma e sinceridade.
        Cada informação contribui para que o trabalho seja realizado de forma mais precisa ao seu momento.
        </p>

        <p style="margin-top:10px;font-weight:600;color:var(--violet-lite);">
        Se esse cuidado faz sentido para você, siga com o preenchimento.
        </p>

        <p style="font-size:14px;color:var(--text-light);margin-top:10px;">
        Após o envio, sua solicitação será analisada e você receberá o retorno com as orientações para continuidade do atendimento.
        </p>
    </div>

    <div class="form-box" style="max-width:680px;margin:0 auto 80px;">

        <?php if ($status === 'ok'): ?>
        <div class="form-aviso form-aviso--ok">Sua solicitação foi recebida com sucesso! Entraremos em contato em breve.</div>
        <?php elseif ($status === 'erro'): ?>
        <div class="form-aviso form-aviso--erro">Não foi possível enviar sua solicitação. Tente novamente ou fale pelo WhatsApp.</div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/benzimento-enviar.php" method="POST">

            <h3 style="font-size:15px;font-weight:700;margin-bottom:18px;color:var(--violet-lite);">Dados Pessoais</h3>

            <div class="campo">
                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome" placeholder="Seu nome completo" required>
            </div>

            <div class="campos-grid">
                <div class="campo">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                </div>
                <div class="campo">
                    <label for="whatsapp">WhatsApp</label>
                    <input type="tel" id="whatsapp" name="whatsapp" placeholder="(51) 9 9999-9999" required>
                </div>
            </div>

            <div class="campos-grid">
                <div class="campo">
                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" required>
                </div>
                <div class="campo">
                    <label for="nome_mae">Nome da mãe</label>
                    <input type="text" id="nome_mae" name="nome_mae" placeholder="Nome da sua mãe" required>
                </div>
            </div>

            <div class="campo">
                <label for="endereco">Endereço completo</label>
                <input type="text" id="endereco" name="endereco" placeholder="Rua, número, bairro, cidade e estado" required>
            </div>

            <h3 style="font-size:15px;font-weight:700;margin:24px 0 18px;color:var(--violet-lite);">Atendimento</h3>

            <div class="campo">
                <label for="tipo_atendimento">Tipo de atendimento</label>
                <input type="text" id="tipo_atendimento" value="Benzimento" disabled
                       style="opacity:.6;cursor:not-allowed;">
                <input type="hidden" name="tipo_atendimento" value="Benzimento">
            </div>

            <div class="campo">
                <label for="intencao">Intenção / propósito</label>
                <textarea id="intencao" name="intencao" rows="5"
                    placeholder="Descreva de forma simples o que você está vivendo ou buscando neste momento. Quanto mais claro, melhor será o direcionamento do atendimento." required></textarea>
            </div>

            <div class="campo" style="margin-top:20px;">
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-weight:normal;font-size:14px;color:var(--text);">
                    <input type="checkbox" name="lgpd_aceite" required
                           style="width:18px;height:18px;flex-shrink:0;margin-top:2px;accent-color:var(--violet);">
                    <span>
                        Autorizo o uso dos meus dados para fins de atendimento espiritual, conforme a
                        <a href="<?= BASE_URL ?>/politica-privacidade.php" target="_blank"
                           style="color:var(--violet-lite);text-decoration:underline;">Política de Privacidade</a>.
                    </span>
                </label>
            </div>

            <p style="font-size:13px;color:var(--text-light);margin-top:10px;text-align:center;">
                Seu pedido será tratado com respeito, confidencialidade e responsabilidade.
            </p>

            <button type="submit" class="btn btn-primary" style="margin-top:24px;width:100%;padding:14px;">
                Solicitar benzimento
            </button>

        </form>
    </div>

</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>