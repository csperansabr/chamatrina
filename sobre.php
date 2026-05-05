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

    <!-- Transparência Institucional / CNPJ -->
    <div class="about" style="margin-top:32px;padding-top:28px;border-top:1px solid rgba(255,255,255,0.07);">
        <h3 style="font-size:15px;font-weight:700;letter-spacing:.03em;margin-bottom:10px;color:var(--violet-lite);">Transparência Institucional</h3>
        <p style="margin-bottom:16px;">A Fraternidade Essência da Chama Trina está registrada no CNPJ sob o número <strong>55.343.450/0001-79</strong>. Consulte os dados cadastrais oficiais diretamente na base da Receita Federal:</p>

        <button id="btn-cnpj" onclick="consultarCNPJ()"
                style="display:inline-flex;align-items:center;gap:8px;background:rgba(139,92,246,0.12);border:1px solid rgba(139,92,246,0.35);color:#a78bfa;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:background .2s;">
            <svg id="btn-cnpj-ico" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span id="btn-cnpj-txt">Consultar na Receita Federal</span>
        </button>

        <div id="cnpj-resultado" style="display:none;margin-top:20px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:20px 22px;font-size:13px;line-height:1.7;color:#cbd5e1;">
        </div>
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

<script>
(function () {
    var consultado = false;

    window.consultarCNPJ = function () {
        if (consultado) {
            var box = document.getElementById('cnpj-resultado');
            box.style.display = box.style.display === 'none' ? 'block' : 'none';
            return;
        }

        var btn = document.getElementById('btn-cnpj');
        var txt = document.getElementById('btn-cnpj-txt');
        var ico = document.getElementById('btn-cnpj-ico');
        var box = document.getElementById('cnpj-resultado');

        txt.textContent = 'Consultando…';
        btn.disabled    = true;
        btn.style.opacity = '0.6';
        ico.innerHTML   = '<circle cx="12" cy="12" r="9" stroke-dasharray="28 57" stroke-linecap="round"><animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur=".8s" repeatCount="indefinite"/></circle>';

        fetch('<?= BASE_URL ?>/cnpj-consulta.php')
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.erro) throw new Error(d.erro);
                box.innerHTML = renderCNPJ(d);
                box.style.display = 'block';
                consultado = true;
                txt.textContent  = 'Ocultar dados';
                btn.disabled     = false;
                btn.style.opacity = '1';
                ico.innerHTML    = '<polyline points="20 6 9 17 4 12"/>';
            })
            .catch(function (e) {
                box.innerHTML    = '<span style="color:#f87171;">⚠ ' + (e.message || 'Erro ao consultar. Tente novamente.') + '</span>';
                box.style.display = 'block';
                txt.textContent  = 'Tentar novamente';
                btn.disabled     = false;
                btn.style.opacity = '1';
                ico.innerHTML    = '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>';
            });
    };

    function fmt(v) { return v ? String(v).trim() : ''; }

    function fmtCNPJ(c) {
        c = String(c).replace(/\D/g, '').padStart(14, '0');
        return c.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
    }

    function fmtData(d) {
        if (!d) return '—';
        var m = String(d).match(/^(\d{4})-(\d{2})-(\d{2})/);
        return m ? m[3] + '/' + m[2] + '/' + m[1] : d;
    }

    function fmtEndereco(d) {
        var partes = [];
        if (d.logradouro) partes.push(fmt(d.logradouro) + (d.numero ? ', ' + fmt(d.numero) : ''));
        if (d.complemento) partes.push(fmt(d.complemento));
        if (d.bairro)      partes.push(fmt(d.bairro));
        if (d.municipio)   partes.push(fmt(d.municipio) + (d.uf ? ' / ' + fmt(d.uf) : ''));
        if (d.cep)         partes.push('CEP ' + String(d.cep).replace(/^(\d{5})(\d{3})$/, '$1-$2'));
        return partes.join(' — ') || '—';
    }

    function renderCNPJ(d) {
        var ativa   = d.descricao_situacao_cadastral && d.descricao_situacao_cadastral.toUpperCase().indexOf('ATIVA') !== -1;
        var corSit  = ativa ? '#4ade80' : '#f87171';
        var bgSit   = ativa ? 'rgba(74,222,128,0.1)' : 'rgba(248,113,113,0.1)';
        var brdSit  = ativa ? 'rgba(74,222,128,0.25)' : 'rgba(248,113,113,0.25)';

        var atv = (d.atividade_principal && d.atividade_principal.length)
            ? d.atividade_principal[0].descricao
            : '—';

        var rows = [
            ['Razão Social',    fmt(d.razao_social)  || '—'],
            ['CNPJ',            fmtCNPJ(d.cnpj)],
            ['Natureza Jurídica', fmt(d.natureza_juridica) || '—'],
            ['Abertura',        fmtData(d.data_inicio_atividade)],
            ['Atividade Principal', fmt(atv) || '—'],
            ['Endereço',        fmtEndereco(d)],
        ];

        var html = '<div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;">';
        html    += '<span style="background:' + bgSit + ';color:' + corSit + ';border:1px solid ' + brdSit + ';padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;">';
        html    += fmt(d.descricao_situacao_cadastral) || (ativa ? 'ATIVA' : 'INATIVA');
        html    += '</span>';
        if (d.data_situacao_cadastral) {
            html += '<span style="color:#64748b;font-size:12px;">desde ' + fmtData(d.data_situacao_cadastral) + '</span>';
        }
        html += '</div>';

        html += '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:10px 24px;">';
        rows.forEach(function (r) {
            html += '<div><span style="display:block;font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px;">' + r[0] + '</span>';
            html += '<span style="color:#e2e8f0;">' + r[1] + '</span></div>';
        });
        html += '</div>';

        html += '<p style="margin-top:14px;font-size:11px;color:#475569;">Dados consultados em tempo real na base da Receita Federal via <a href="https://brasilapi.com.br" target="_blank" rel="noopener" style="color:#7c6aaa;">Brasil API</a>.</p>';

        return html;
    }
})();
</script>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
