<?php
include __DIR__ . '/includes/config.php';

$title       = 'Atendimentos Espirituais';
$description = 'Consultas espirituais, limpezas, passes e atendimentos mediúnicos — presencial e online, por agendamento.';
$url         = BASE_URL . '/atendimentos.php';

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about">
        <h2>Atendimentos Espirituais</h2>

        <p>Na Fraternidade Essência da Chama Trina, os atendimentos espirituais são realizados com amor, respeito e comprometimento. Seja você alguém em busca de orientação, equilíbrio ou simplesmente acolhimento, nossa equipe está disponível para te receber com escuta e cuidado.</p>

        <p>Os atendimentos acontecem de forma <strong>presencial em Canoas/RS</strong> ou de forma <strong>online</strong>, sempre mediante agendamento prévio. Cada atendimento é único e conduzido de acordo com a necessidade de cada pessoa.</p>
    </div>

    <div class="section">
        <h2>O que oferecemos</h2>

        <div class="team-grid">

            <div class="team-card">
                <h3>Consulta Espiritual</h3>
                <span>Individual e sigilosa</span>
                <p>
                    Espaço de escuta e orientação espiritual. A consulta permite compreender bloqueios, padrões repetitivos e receber direcionamento dos guias e mentores espirituais.
                </p>
            </div>

            <div class="team-card">
                <h3>Limpeza Espiritual</h3>
                <span>Equilíbrio e leveza</span>
                <p>
                    Trabalho de descarrego e harmonização energética. Indicado para momentos de peso, estagnação ou sensação de desequilíbrio no corpo e na vida.
                </p>
            </div>

            <div class="team-card">
                <h3>Passe Espiritual</h3>
                <span>Cura e reequilíbrio</span>
                <p>
                    Transmissão de energia através de médiuns treinados. O passe atua no campo energético do indivíduo, promovendo alívio, equilíbrio e fortalecimento espiritual.
                </p>
            </div>

            <div class="team-card">
                <h3>Atendimento Mediúnico</h3>
                <span>Mensagens e orientação</span>
                <p>
                    Atendimento conduzido com o auxílio de entidades e guias espirituais. Espaço de mensagens, orientações e cura para questões de vida, saúde e espiritualidade.
                </p>
            </div>

            <div class="team-card">
                <h3>Benzimento Online</h3>
                <span>Cura e proteção à distância</span>
                <p>
                    Trabalho espiritual de cura, proteção e limpeza energética realizado à distância. Solicite pelo formulário online com seus dados e intenção — o benzimento é feito com amor e dedicação.
                </p>
                <a href="<?= BASE_URL ?>/benzimento.php" class="btn btn-ghost" style="margin-top:16px;display:inline-block;">
                    Solicitar Benzimento
                </a>
            </div>

        </div>
    </div>

    <div class="section">
        <h2>Como funciona</h2>

        <div class="list">
            <div>• Atendimentos realizados de forma presencial em Canoas/RS ou online por videochamada</div>
            <div>• Agendamento prévio obrigatório via WhatsApp ou formulário de contato</div>
            <div>• Sessões individuais e sigilosas</div>
            <div>• Duração variável conforme o tipo e a necessidade de cada atendimento</div>
        </div>
    </div>

    <div class="form-box">
        <h2>Agende seu atendimento</h2>
        <p>Entre em contato pelo WhatsApp ou pelo formulário e nossa equipe retornará para combinar data, horário e modalidade.</p>
        <div style="display:flex; gap:1rem; flex-wrap:wrap; justify-content:center; margin-top:1rem;">
            <a href="<?= WHATSAPP ?>" target="_blank" class="btn whatsapp">Agendar pelo WhatsApp</a>
            <a href="<?= BASE_URL ?>/contato.php" class="btn btn-ghost">Formulário de Contato</a>
        </div>
    </div>

</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
