<?php
require_once __DIR__ . '/../includes/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

unset($_SESSION['participante_id'], $_SESSION['participante_nome']);
session_destroy();

header('Location: ' . BASE_URL . '/anamnese/login.php');
exit;
