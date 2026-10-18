<?php include "includes/header.php"; ?>

<style>
    body {
        background-color: #eef6f1;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .banner {
        width: 100%;
        aspect-ratio: 16 / 4;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    h1 {
        text-align: center;
        color: #2c3e50;
        margin: 15px 0 25px 0;
        font-size: 32px;
        font-weight: 700;
    }

    h2.section-title {
        text-align: center !important;
        color: #2c3e50;
        margin: 25px 0 10px 0;
        font-size: 24px;
        font-weight: 600;
        position: relative;
    }

    h2.section-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background-color: #28a745;
        margin: 5px auto 0 auto;
        border-radius: 2px;
    }

    .menu-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 4px 8px;
        margin-bottom: 25px;
        padding: 0 10px;
    }

    .button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 8px;
        font-size: 14.5px;
        color: #fff;
        text-decoration: none;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        width: 100%;
        max-width: 200px;
        text-align: center;
        font-weight: 600;
    }

    .button .icon {
        font-size: 28px;
        margin-bottom: 5px;
    }

    .button.primary { background: linear-gradient(135deg, #28a745, #218838); }
    .button.secondary { background: linear-gradient(135deg, #17a2b8, #138496); }
    .button.report { background: linear-gradient(135deg, #ffc107, #e0a800); color: #000; }
    .button.user { background: linear-gradient(135deg, #6f42c1, #5936a2); }
    .button.format { background: linear-gradient(135deg, #007bff, #0056b3); }

    .button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    }

    footer {
        text-align: center;
        padding: 15px;
        background-color: #28a745;
        font-size: 15px;
        color: #fff;
        margin-top: 35px;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    @media (max-width: 768px) {
        .button {
            max-width: 170px;
            font-size: 13.5px;
            padding: 10px 6px;
        }
        .button .icon {
            font-size: 26px;
        }
    }
</style>

<div class="banner">
    <img src="imgs/banner.png" alt="Banner institucional">
</div>

<h1>Dirección Distrital de Educación - El Progreso</h1>

<h2 class="section-title">Sección Principal</h2>
<div class="menu-container">
    <a class="button primary" href="matriz/listar.php">
        <div class="icon">📊</div>
        Matriz
    </a>
</div>

<h2 class="section-title">Registros</h2>
<div class="menu-container">
    <a class="button secondary" href="docentes/listar.php">
        <div class="icon">👩‍🏫</div>
        Docentes
    </a>
    <a class="button secondary" href="nombramientos/listar.php">
        <div class="icon">📝</div>
        Nombramientos
    </a>
    <a class="button secondary" href="estructuras/listar.php">
        <div class="icon">🏛️</div>
        Estructuras
    </a>
    <a class="button user" href="usuarios/listar.php">
        <div class="icon">👤</div>
        Usuarios
    </a>
</div>

<h2 class="section-title">Reportes / Informes</h2>
<div class="menu-container">
    <a class="button report" href="reportes/matriz.php">
        <div class="icon">📈</div>
        Reportes Matriz
    </a>
    <a class="button report" href="reportes/docentes.php">
        <div class="icon">📊</div>
        Reportes Docentes
    </a>
    <a class="button report" href="reportes/nombramientos.php">
        <div class="icon">🗂️</div>
        Reportes Nombramientos
    </a>
    <a class="button report" href="reportes/estructuras.php">
        <div class="icon">🏛️</div>
        Reportes Estructuras
    </a>
</div>

<h2 class="section-title">Nombramientos - Pre-Basica / Basica</h2>
<div class="menu-container">
    <a class="button format" href="formatos/basica_nombramiento/listar.php">
        <div class="icon">📄</div>
        Nombramiento
    </a>
    <a class="button format" href="formatos/basica_traslado/listar.php">
        <div class="icon">🚛</div>
        Traslado Docente
    </a>
</div>

<h2 class="section-title">Nombramientos - Media</h2>
<div class="menu-container">
    <a class="button format" href="formatos/media_nombramiento/listar.php">
        <div class="icon">📄</div>
        Nombramiento
    </a>
    <a class="button format" href="formatos/media_traslado/listar.php">
        <div class="icon">🚛</div>
        Traslado Docente
    </a>
</div>

<h2 class="section-title">Cancelaciones - PreBasica/básica/media</h2>
<div class="menu-container">
    <a class="button format" href="formatos/formato_cancelaciones/listar.php">
        <div class="icon">🧾</div>
        General
    </a>
</div>

<h2 class="section-title">Licencias - PreBasica/básica/media</h2>
<div class="menu-container">
    <a class="button format" href="#">
        <div class="icon">🧾</div>
        (En construcción)
    </a>
</div>

<footer>
    &copy; Jose Enrique Pineda Rivera
</footer>

<?php include "includes/footer.php"; ?>
