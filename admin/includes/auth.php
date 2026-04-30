<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logado'])) {
    header('Location: ' . (defined('BASE_URL') ? BASE_URL : '') . '/admin/login.php');
    exit;
}
