<?php
$title = "Galeria da Chama Trina";
$description = "Conheça os trabalhos práticos da Fraternidade Essência da Chama Trina.";
$url = "http://chamatrina.org.br/galeria.php";

include __DIR__ . '/includes/layout-top.php';
?>

<div class="container gallery-container">

<h1>Galeria de Vivências</h1>

<?php
$baseDir = "img/galeria/";
$folders = scandir($baseDir);

$allowed = ['jpg', 'jpeg', 'png', 'webp'];

foreach ($folders as $folder) {

    if ($folder != "." && $folder != "..") {

        $folderPath = $baseDir . $folder;

        if (is_dir($folderPath)) {

            echo "<h2 class='gallery-title'>" . ucwords(str_replace("-", " ", $folder)) . "</h2>";
            echo "<div class='gallery-grid'>";

            $images = scandir($folderPath);

            $index = 0;

            foreach ($images as $img) {

                if ($img != "." && $img != "..") {

                    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

                    if (in_array($ext, $allowed)) {

                        $path = $folderPath . "/" . $img;

                        if (file_exists($path)) {

                            $nome = pathinfo($img, PATHINFO_FILENAME);
							$legenda = ucwords(str_replace(["-", "_"], " ", $nome));

							echo "<img 
									src='/$path' 
									data-index='$index' 
									data-folder='$folder'
									data-caption='$legenda'
									onclick='openModal(this)' 
									loading='lazy'
									onerror=\"this.style.display='none'\"
								  >";

                            $index++;
                        }
                    }
                }
            }

            echo "</div>";
        }
    }
}
?>

</div>

<!-- MODAL -->
<div id="modal" class="modal">
    <span class="close" onclick="closeModal()">×</span>

    <img id="modal-img">

    <div id="modal-caption" class="modal-caption"></div>

    <div class="nav prev" onclick="prevImage()">❮</div>
    <div class="nav next" onclick="nextImage()">❯</div>
</div>

<script>
let images = [];
let captions = [];
let currentIndex = 0;

function openModal(element) {

    const folder = element.dataset.folder;

    const items = Array.from(document.querySelectorAll(`img[data-folder='${folder}']`));

    images = items.map(img => img.src);
    captions = items.map(img => img.dataset.caption);

    currentIndex = parseInt(element.dataset.index);

    document.getElementById("modal").style.display = "flex";
    updateImage();
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    updateImage();
}

function prevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateImage();
}

function updateImage() {
    document.getElementById("modal-img").src = images[currentIndex];
    document.getElementById("modal-caption").innerText = captions[currentIndex];
}

/* TECLADO */
document.addEventListener('keydown', function(e) {
    if (e.key === "Escape") closeModal();
    if (e.key === "ArrowRight") nextImage();
    if (e.key === "ArrowLeft") prevImage();
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>