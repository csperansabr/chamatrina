<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['participante_id'])) {
    header('Location: ' . (defined('BASE_URL') ? BASE_URL : '') . '/anamnese/login.php');
    exit;
}
