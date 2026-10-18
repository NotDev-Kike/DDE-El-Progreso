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
if(!isset($_SESSION['usuario'])){
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$logout_path = BASE_URL . '/logout.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>DDE El Progreso</title>

<link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL . '/css/style.css', ENT_QUOTES, 'UTF-8') ?>">

<link rel="stylesheet" href="<?= htmlspecialchars(BASE_URL . '/js/datatables.min.css', ENT_QUOTES, 'UTF-8') ?>">

<script src="<?= htmlspecialchars(BASE_URL . '/js/jquery.min.js', ENT_QUOTES, 'UTF-8') ?>"></script>
<script src="<?= htmlspecialchars(BASE_URL . '/js/datatables.min.js', ENT_QUOTES, 'UTF-8') ?>"></script>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #d4edda;
        margin: 0;
        padding: 0;
    }

    .header-bar {
        background-color: #28a745;
        color: white;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-bar a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        padding: 6px 12px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .header-bar a:hover {
        background-color: #218838;
    }

    hr {
        border: none;
        border-top: 2px solid #c3e6cb;
        margin: 0;
    }
</style>
</head>
<body>
<div class="header-bar">
    <p>👤 Usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <a href="<?php echo $logout_path; ?>">🚪 Salir</a>
</div>
<hr>
<div class="container">
