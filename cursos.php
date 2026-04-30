<?php
include __DIR__ . '/includes/config.php';

$title       = 'Cursos e Workshops';
$description = 'Cursos e workshops de Umbanda e Medicinas da Floresta: Ervas, Banhos, Defumações e Cachimbo. Conhecimento ancestral com fundamento.';
$url         = BASE_URL . '/cursos.php';

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about">
        <h2>Cursos e Workshops</h2>

        <p>O conhecimento é uma das maiores formas de cura. Na Fraternidade Essência da Chama Trina, oferecemos cursos e workshops que unem sabedoria ancestral, prática e espiritualidade.</p>

        <p>Cada encontro é uma oportunidade de se aprofundar nos ensinamentos da Umbanda e das tradições sagradas da floresta, com fundamento, responsabilidade e muito cuidado.</p>
    </div>

    <div class="section">
        <h2>O que oferecemos</h2>

        <div class="team-grid">

            <div class="team-card">
                <h3>Curso de Ervas</h3>
                <span>Medicina da Terra</span>
                <p>
                    Estudo das ervas sagradas utilizadas na Umbanda e nas tradições ancestrais brasileiras. Aprenda a identificar, preparar e utilizar as ervas para cura, proteção e equilíbrio espiritual.
                </p>
            </div>

            <div class="team-card">
                <h3>Workshop de Banhos</h3>
                <span>Limpeza e Harmonização</span>
                <p>
                    Aprenda a preparar banhos de ervas com finalidades específicas: limpeza energética, atração, amor, proteção e equilíbrio. Fundamentação espiritual e prática.
                </p>
            </div>

            <div class="team-card">
                <h3>Workshop de Defumações</h3>
                <span>Purificação e Proteção</span>
                <p>
                    Técnicas e fundamentos das defumações utilizadas na Umbanda. Como preparar incensos, resinas e ervas para purificar ambientes, pessoas e energias.
                </p>
            </div>

            <div class="team-card">
                <h3>Workshop de Cachimbo</h3>
                <span>Medicina Sagrada</span>
                <p>
                    Introdução ao uso sagrado do cachimbo como medicina e ferramenta espiritual. Historia, fundamentos, cuidados e protocolo de uso consciente e respeitoso.
                </p>
            </div>

        </div>
    </div>

    <div class="section">
        <h2>Informações importantes</h2>

        <div class="list">
            <div>• Os cursos e workshops são realizados presencialmente em Canoas/RS ou mediante combinação de local</div>
            <div>• As turmas são abertas conforme demanda — inscreva-se para receber avisos</div>
            <div>• Também é possível levar um workshop até o seu espaço ou grupo — consulte disponibilidade</div>
            <div>• Vagas limitadas para garantir qualidade e atenção individual</div>
        </div>
    </div>

    <div class="form-box">
        <h2>Tenho interesse em um curso ou workshop</h2>
        <p>Entre em contato e nos diga qual curso te interessa. Vamos combinar data, local e formato para a melhor experiência.</p>
        <div style="display:flex; gap:1rem; flex-wrap:wrap; justify-content:center; margin-top:1rem;">
            <a href="<?= WHATSAPP ?>" target="_blank" class="btn whatsapp">Falar pelo WhatsApp</a>
            <a href="contato.php" class="btn">Formulário de Contato</a>
        </div>
    </div>

</div>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
