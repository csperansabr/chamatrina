<?php
require_once __DIR__ . '/includes/config.php';

$title       = 'Política de Privacidade';
$description = 'Política de Privacidade da Fraternidade Essência da Chama Trina — uso, armazenamento e exclusão de dados pessoais.';
$url         = BASE_URL . '/politica-privacidade.php';

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about" style="max-width:720px;margin:60px auto 40px;">
        <h2>Política de Privacidade</h2>
        <p style="color:var(--text-dim);font-size:13px;margin-top:8px;">
            Fraternidade Essência da Chama Trina — última atualização: <?= date('d/m/Y') ?>
        </p>
    </div>

    <div class="form-box" style="max-width:720px;margin:0 auto 80px;line-height:1.8;">

        <h3 style="margin-bottom:12px;">1. Uso dos dados</h3>
        <p>Os dados pessoais coletados neste site são utilizados exclusivamente para fins de atendimento espiritual — benzimentos, consultas, limpezas e demais serviços oferecidos pela Fraternidade Essência da Chama Trina. Nenhum dado é utilizado para fins comerciais, publicitários ou repassado a terceiros.</p>

        <h3 style="margin:28px 0 12px;">2. Compartilhamento</h3>
        <p>Não compartilhamos seus dados com terceiros sob nenhuma circunstância. As informações fornecidas são de uso interno e restrito à equipe de médiuns e coordenadores da Fraternidade.</p>

        <h3 style="margin:28px 0 12px;">3. Dados coletados</h3>
        <p>Para a realização de atendimentos online (benzimentos), coletamos:</p>
        <div class="list" style="margin-top:10px;">
            <div>• Nome completo</div>
            <div>• Endereço de e-mail</div>
            <div>• Número de WhatsApp</div>
            <div>• Data de nascimento</div>
            <div>• Nome da mãe</div>
            <div>• Endereço completo</div>
            <div>• Intenção e propósito do atendimento</div>
        </div>

        <h3 style="margin:28px 0 12px;">4. Retenção de dados</h3>
        <p>Os dados pessoais relacionados a atendimentos concluídos são excluídos automaticamente após <strong>90 dias</strong> da data de conclusão. Mantemos apenas registros estatísticos anônimos (tipo de atendimento e datas) para fins de controle interno, sem qualquer dado pessoal identificável.</p>

        <h3 style="margin:28px 0 12px;">5. Seus direitos (LGPD)</h3>
        <p>Em conformidade com a Lei Geral de Proteção de Dados (LGPD — Lei nº 13.709/2018), você tem direito a:</p>
        <div class="list" style="margin-top:10px;">
            <div>• Solicitar acesso aos seus dados pessoais</div>
            <div>• Solicitar a correção de dados incorretos ou desatualizados</div>
            <div>• Solicitar a exclusão dos seus dados a qualquer momento</div>
            <div>• Revogar o consentimento dado anteriormente</div>
            <div>• Obter informações sobre com quem seus dados foram compartilhados</div>
        </div>
        <p style="margin-top:14px;">
            Para exercer qualquer um desses direitos, entre em contato:<br>
            E-mail: <strong>contato@chamatrina.org.br</strong><br>
            WhatsApp: <strong>(51) 99256-3279</strong>
        </p>

        <h3 style="margin:28px 0 12px;">6. Segurança</h3>
        <p>Adotamos medidas técnicas de segurança para proteger seus dados contra acesso não autorizado, perda ou alteração indevida. O acesso ao painel administrativo é protegido por autenticação e restrito à equipe interna.</p>

        <h3 style="margin:28px 0 12px;">7. Consentimento</h3>
        <p>Ao preencher e enviar o formulário de solicitação de atendimento, você declara ter lido e concordado com esta Política de Privacidade e autoriza o uso dos seus dados para fins de atendimento espiritual.</p>

    </div>

</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
