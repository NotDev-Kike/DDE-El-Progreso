<?php include "includes/header.php"; ?>
<section class="page-header">
    <div>
        <p class="eyebrow">Panel principal</p>
        <h1>¿Qué necesitas gestionar hoy?</h1>
        <p class="page-description">Accede rápidamente a los registros, formatos y reportes de la Dirección Distrital.</p>
    </div>
    <a class="button" href="<?= htmlspecialchars(BASE_URL . '/matriz/listar.php', ENT_QUOTES, 'UTF-8') ?>">Abrir matriz</a>
</section>
<section class="dashboard-grid" aria-label="Módulos del sistema">
    <a class="dashboard-card dashboard-card-featured" href="matriz/listar.php"><span class="dashboard-icon">▦</span><span><strong>Matriz</strong><small>Consulta y administra la información central.</small></span><b>→</b></a>
    <a class="dashboard-card" href="docentes/listar.php"><span class="dashboard-icon">◎</span><span><strong>Docentes</strong><small>Directorio y datos del personal.</small></span><b>→</b></a>
    <a class="dashboard-card" href="nombramientos/listar.php"><span class="dashboard-icon">□</span><span><strong>Nombramientos</strong><small>Gestiona nombramientos y asignaciones.</small></span><b>→</b></a>
    <a class="dashboard-card" href="estructuras/listar.php"><span class="dashboard-icon">⌂</span><span><strong>Estructuras</strong><small>Organización de centros y plazas.</small></span><b>→</b></a>
    <a class="dashboard-card" href="usuarios/listar.php"><span class="dashboard-icon">●</span><span><strong>Usuarios</strong><small>Administra accesos del sistema.</small></span><b>→</b></a>
</section>
<section class="dashboard-section">
    <div class="section-heading"><div><p class="eyebrow">Documentos</p><h2>Formatos frecuentes</h2></div></div>
    <div class="quick-links">
        <a href="formatos/basica_nombramiento/listar.php">Nombramiento básica <span>→</span></a>
        <a href="formatos/basica_traslado/listar.php">Traslado docente <span>→</span></a>
        <a href="formatos/media_nombramiento/listar.php">Nombramiento media <span>→</span></a>
        <a href="formatos/formato_cancelaciones/listar.php">Cancelaciones <span>→</span></a>
    </div>
</section>
<section class="dashboard-section">
    <div class="section-heading"><div><p class="eyebrow">Información</p><h2>Reportes</h2></div><span class="section-note">Consulta resumida del sistema</span></div>
    <div class="quick-links">
        <a href="reportes/matriz.php">Reporte de matriz <span>→</span></a>
        <a href="reportes/docentes.php">Reporte de docentes <span>→</span></a>
        <a href="reportes/nombramientos.php">Reporte de nombramientos <span>→</span></a>
        <a href="reportes/estructuras.php">Reporte de estructuras <span>→</span></a>
    </div>
</section>
<style>
.dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: .85rem; margin-bottom: 2.5rem; }
.dashboard-card { display: flex; align-items: center; gap: .8rem; min-height: 118px; padding: 1rem; border: 1px solid var(--line); border-radius: 1rem; background: var(--surface); color: var(--ink); text-decoration: none; box-shadow: var(--shadow); transition: transform .2s, border-color .2s; }
.dashboard-card:hover { transform: translateY(-3px); border-color: #8ed5cc; }
.dashboard-card-featured { grid-column: span 2; background: linear-gradient(135deg, #0f766e, #155e75); color: #fff; border: 0; }
.dashboard-card span:nth-child(2) { display: grid; gap: .2rem; flex: 1; }
.dashboard-card strong { font-size: 1rem; } .dashboard-card small { color: var(--muted); line-height: 1.35; }
.dashboard-card-featured small, .dashboard-card-featured b { color: #d9fffa; } .dashboard-card b { color: var(--brand); font-size: 1.2rem; }
.dashboard-icon { display: grid; place-items: center; width: 42px; height: 42px; border-radius: .7rem; background: var(--brand-soft); color: var(--brand-dark); font-size: 1.35rem; font-weight: 800; }
.dashboard-card-featured .dashboard-icon { background: rgba(255,255,255,.16); color: #fff; }
.dashboard-section { margin-top: 2.25rem; } .section-heading { display: flex; justify-content: space-between; align-items: end; margin-bottom: .75rem; }
.section-note { color: var(--muted); font-size: .85rem; } .quick-links { display: grid; grid-template-columns: repeat(4, 1fr); gap: .75rem; }
.quick-links a { display: flex; justify-content: space-between; gap: .5rem; padding: 1rem; border: 1px solid var(--line); border-radius: .75rem; background: #fff; color: var(--ink); text-decoration: none; font-weight: 700; }
.quick-links a:hover { border-color: #8ed5cc; background: #fbfefd; } .quick-links span { color: var(--brand); }
@media (max-width: 900px) { .dashboard-grid { grid-template-columns: repeat(2, 1fr); } .dashboard-card-featured { grid-column: span 2; } .quick-links { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .dashboard-grid, .quick-links { grid-template-columns: 1fr; } .dashboard-card-featured { grid-column: auto; } }
</style>
<?php include "includes/footer.php"; ?>
