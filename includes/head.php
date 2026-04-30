<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/config.php';
}

$title       = $title       ?? SITE_NAME;
$description = $description ?? 'Fraternidade espiritual movida pela Umbanda. Vivências, atendimentos, cursos e Medicinas da Floresta em Canoas/RS.';
$url         = $url         ?? BASE_URL;
?>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $title ?> | <?= SITE_NAME ?></title>
<meta name="description" content="<?= $description ?>">
<link rel="canonical" href="<?= $url ?>">

<!-- FAVICONS -->
<link rel="icon" type="image/x-icon" href="/img/ico/favicon.ico">
<link rel="icon" type="image/png" sizes="16x16" href="/img/ico/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="/img/ico/favicon-32x32.png">
<link rel="apple-touch-icon" href="/img/ico/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="192x192" href="/img/ico/android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="512x512" href="/img/ico/android-chrome-512x512.png">

<?php $base = '/'; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $base ?>css/style.css">
<link rel="manifest" href="site.webmanifest">
<meta name="theme-color" content="#06090f">

<!-- Open Graph -->
<meta property="og:title" content="<?= $title ?>">
<meta property="og:description" content="<?= $description ?>">
<meta property="og:url" content="<?= $url ?>">
<meta property="og:type" content="website">

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VDS7NJM3E4"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-VDS7NJM3E4');
</script>

</head>