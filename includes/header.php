<?php
require_once __DIR__ . '/db.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$logout_path = BASE_URL . '/logout.php';
$current_page = basename($_SERVER['PHP_SELF'] ?? '');
$usuario = htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8');
$rol = htmlspecialchars($_SESSION['rol'] ?? 'Usuario', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0f766e">
<title>DDE El Progreso</title>
<link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL . '/css/style.css', ENT_QUOTES, 'UTF-8') ?>">
<link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL . '/js/datatables.min.css', ENT_QUOTES, 'UTF-8') ?>">
<script src="<?= htmlspecialchars(BASE_URL . '/js/jquery.min.js', ENT_QUOTES, 'UTF-8') ?>"></script>
<script src="<?= htmlspecialchars(BASE_URL . '/js/datatables.min.js', ENT_QUOTES, 'UTF-8') ?>"></script>
</head>
<body>
<a class="skip-link" href="#contenido">Saltar al contenido</a>
<header class="app-header">
    <div class="header-inner">
        <a class="brand" href="<?= htmlspecialchars(BASE_URL . '/menu.php', ENT_QUOTES, 'UTF-8') ?>" aria-label="Ir al inicio">
            <span class="brand-mark" aria-hidden="true">DDE</span>
            <span class="brand-copy"><strong>El Progreso</strong><small>Gestión educativa</small></span>
        </a>
        <nav class="main-nav" aria-label="Navegación principal">
            <a class="nav-link <?= $current_page === 'menu.php' ? 'is-active' : '' ?>" href="<?= htmlspecialchars(BASE_URL . '/menu.php', ENT_QUOTES, 'UTF-8') ?>">Inicio</a>
            <a class="nav-link" href="<?= htmlspecialchars(BASE_URL . '/docentes/listar.php', ENT_QUOTES, 'UTF-8') ?>">Docentes</a>
            <a class="nav-link" href="<?= htmlspecialchars(BASE_URL . '/nombramientos/listar.php', ENT_QUOTES, 'UTF-8') ?>">Nombramientos</a>
            <a class="nav-link" href="<?= htmlspecialchars(BASE_URL . '/reportes/matriz.php', ENT_QUOTES, 'UTF-8') ?>">Reportes</a>
        </nav>
        <div class="user-menu">
            <span class="user-chip"><span class="user-avatar" aria-hidden="true">●</span><span><strong><?= $usuario ?></strong><small><?= $rol ?></small></span></span>
            <a class="button button-ghost button-small" href="<?= htmlspecialchars($logout_path, ENT_QUOTES, 'UTF-8') ?>">Salir</a>
        </div>
    </div>
</header>
<main id="contenido" class="container">
