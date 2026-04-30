<?php
require_once __DIR__ . '/includes/config.php';

$title       = 'Galeria da Chama Trina';
$description = 'Conheça os trabalhos e vivências da Fraternidade Essência da Chama Trina.';
$url         = BASE_URL . '/galeria.php';

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container">

    <div class="about" style="text-align:center;max-width:680px;margin:60px auto 40px;">
        <h2>Galeria de Vivências</h2>
        <p>Registros dos trabalhos, cerimônias e momentos da Fraternidade Essência da Chama Trina.</p>
    </div>

<?php
$baseDir = __DIR__ . '/img/galeria/';

if (is_dir($baseDir)):
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $folders = array_filter(scandir($baseDir), fn($f) => $f !== '.' && $f !== '..' && is_dir($baseDir . $f));

    if (empty($folders)):
?>
    <p class="lead" style="text-align:center;">Nenhuma imagem disponível no momento.</p>
<?php
    else:
        foreach ($folders as $folder):
            $folderPath = $baseDir . $folder;
            $images     = array_filter(
                scandir($folderPath),
                fn($img) => $img !== '.' && $img !== '..' && in_array(strtolower(pathinfo($img, PATHINFO_EXTENSION)), $allowed) && file_exists($folderPath . '/' . $img)
            );
            if (empty($images)) continue;
?>
    <h2 class="gallery-title"><?= htmlspecialchars(ucwords(str_replace(['-', '_'], ' ', $folder))) ?></h2>
    <div class="gallery-grid">
<?php
            $index = 0;
            foreach ($images as $img):
                $path    = 'img/galeria/' . $folder . '/' . $img;
                $legenda = ucwords(str_replace(['-', '_'], ' ', pathinfo($img, PATHINFO_FILENAME)));
?>
        <img
            src="<?= BASE_URL . '/' . htmlspecialchars($path) ?>"
            data-index="<?= $index ?>"
            data-folder="<?= htmlspecialchars($folder) ?>"
            data-caption="<?= htmlspecialchars($legenda) ?>"
            onclick="openModal(this)"
            loading="lazy"
            alt="<?= htmlspecialchars($legenda) ?>"
            onerror="this.style.display='none'">
<?php
                $index++;
            endforeach;
?>
    </div>
<?php
        endforeach;
    endif;
else:
?>
    <p class="lead" style="text-align:center;">Galeria em preparação.</p>
<?php endif; ?>

</div>

<!-- MODAL -->
<div id="modal" class="modal">
    <span class="close" onclick="closeModal()">×</span>
    <img id="modal-img" src="" alt="">
    <div id="modal-caption" class="modal-caption"></div>
    <div class="nav prev" onclick="prevImage()">❮</div>
    <div class="nav next" onclick="nextImage()">❯</div>
</div>

<script>
var images = [], captions = [], currentIndex = 0;

function openModal(el) {
    var folder = el.dataset.folder;
    var items  = Array.from(document.querySelectorAll('img[data-folder="' + folder + '"]'));
    images     = items.map(function(i){ return i.src; });
    captions   = items.map(function(i){ return i.dataset.caption; });
    currentIndex = parseInt(el.dataset.index);
    document.getElementById('modal').style.display = 'flex';
    updateImage();
}
function closeModal() { document.getElementById('modal').style.display = 'none'; }
function nextImage()  { currentIndex = (currentIndex + 1) % images.length; updateImage(); }
function prevImage()  { currentIndex = (currentIndex - 1 + images.length) % images.length; updateImage(); }
function updateImage() {
    document.getElementById('modal-img').src         = images[currentIndex];
    document.getElementById('modal-caption').innerText = captions[currentIndex];
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape')     closeModal();
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft')  prevImage();
});
</script>

<?php include __DIR__ . '/includes/layout-bottom.php'; ?>
