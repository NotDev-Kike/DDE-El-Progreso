<?php
include "../../includes/header.php";
include '../../includes/db.php';

$query = "SELECT * FROM formato_traslado_basica ORDER BY fecha_registro DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Traslados Docentes BÁSICA</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family: Arial, sans-serif; }
    body { background-color:#f5f5f5; color:#333; }
    .main-content { padding:20px; }
    .navigation { text-align:center; margin-bottom:20px; }
    .btn { display:inline-block; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:bold; margin:0 5px; border:none; cursor:pointer; }
    .btn-back { background-color:#555; color:white; }
    .btn-menu { background-color:#0066cc; color:white; }
    .btn-add { background-color:#27ae60; color:white; text-decoration:none; padding:10px 20px; display:inline-block; margin:10px 0; border-radius:6px; }
    .container { max-width:1200px; margin:0 auto; background:white; box-shadow:0 0 10px rgba(0,0,0,0.1); padding:20px; border-radius:8px; }
    table { width:100%; border-collapse:collapse; margin:20px 0; background:white; }
    th, td { border:1px solid #ddd; padding:12px; text-align:left; }
    th { background:#2c3e50; color:white; font-weight:bold; font-size:14px; }
    td { background:#fff; color:#333; font-size:13px; }
    .actions { white-space: nowrap; }
    .actions a { margin-right:8px; text-decoration:none; padding:6px 12px; border-radius:4px; font-size:12px; display:inline-block; }
    .edit { background:#3498db; color:white; }
    .edit:hover { background:#2980b9; }
    .delete { background:#e74c3c; color:white; }
    .delete:hover { background:#c0392b; }
    .success { background:#d4edda; color:#155724; padding:12px; margin:10px 0; border-radius:4px; border:1px solid #c3e6cb; }
    .basica-badge { background:#007bff; color:white; padding:4px 10px; border-radius:4px; font-size:12px; margin-left:8px; font-weight:bold; }
    
    /* Estilos para cuando no hay datos */
    .no-data { text-align:center; padding:50px; color:#666; background:#f9f9f9; border-radius:8px; margin:20px 0; }
    .no-data div { font-size:64px; margin-bottom:15px; }
    .no-data h3 { color:#555; margin-bottom:10px; font-size:20px; }
    .no-data p { color:#777; margin-bottom:20px; font-size:14px; }
    
    /* Mejoras visuales */
    .table-container { overflow-x: auto; }
    .badge-nombramiento { background:#e3f2fd; color:#1976d2; padding:6px 12px; border-radius:4px; font-size:12px; font-weight:bold; }
    .badge-nivel { background:#e8f5e9; color:#2e7d32; padding:4px 8px; border-radius:4px; font-size:11px; font-weight:bold; margin-left:5px; }
    .total-registros { background:#f8f9fa; padding:10px; border-radius:4px; text-align:center; margin-top:20px; border:1px solid #e9ecef; }
    tr:hover td { background:#f8f9fa; }
    
    /* Estilos específicos para columnas */
    .col-id { width: 60px; }
    .col-nombre { width: 200px; }
    .col-nombramiento { width: 120px; }
    .col-fecha { width: 120px; }
    .col-nivel { width: 100px; }
    .col-identidad { width: 140px; }
    .col-acciones { width: 150px; text-align:center; }
</style>
</head>
<body>

<!-- Aquí se incluirá la barra verde del header -->

<div class="main-content">
    <div class="navigation">
        <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-back">🔙 Volver Atrás</a>
        <a href="../../menu.php" class="btn btn-menu">🏠 Volver al Menú</a>
    </div>

    <div class="container">
        <h1 style="margin-bottom:20px; color:#2c3e50; border-bottom:2px solid #007bff; padding-bottom:10px;">
            Lista de Traslados Docentes <span class="basica-badge">BÁSICA</span>
        </h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success">✅ Traslado BÁSICA guardado exitosamente.</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['updated'])): ?>
            <div class="success">✅ Traslado BÁSICA actualizado exitosamente.</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['deleted'])): ?>
            <div class="success">✅ Traslado BÁSICA eliminado exitosamente.</div>
        <?php endif; ?>
        
        <div style="text-align:center; margin:20px 0;">
            <a href="agregar.php" class="btn-add">➕ Agregar Nuevo Traslado BÁSICA</a>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th style="width: 200px;">Nombre del Docente</th>
                        <th style="width: 140px;">Identidad</th>
                        <th style="width: 120px;">Tipo Nombramiento</th>
                        <th style="width: 100px;">Nivel Educativo</th>
                        <th style="width: 120px;">Fecha Inicio</th>
                        <th style="width: 150px; text-align:center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="font-weight:bold; color:#2c3e50;"><?= $row['id'] ?></td>
                        <td style="font-weight:500;"><?= htmlspecialchars($row['nombre_docente']) ?></td>
                        <td style="font-family: monospace; letter-spacing: 1px;">
                            <?= htmlspecialchars($row['identidad']) ?>
                        </td>
                        <td>
                            <span class="badge-nombramiento">
                                <?= $row['tipo_nombramiento'] ?>
                            </span>
                        </td>
                        <td>
                            <?= htmlspecialchars($row['nivel_educativo']) ?>
                            <span class="badge-nivel">BÁSICA</span>
                        </td>
                        <td style="color:#27ae60; font-weight:500;">
                            <?= $row['fecha_inicio'] ? date('d/m/Y', strtotime($row['fecha_inicio'])) : 'No especificada' ?>
                        </td>
                        <td class="actions" style="text-align:center;">
                            <a href="modificar.php?id=<?= $row['id'] ?>" class="edit" title="Modificar traslado">✏️ Modificar</a>
                            <a href="eliminar.php?id=<?= $row['id'] ?>" class="delete" title="Eliminar traslado" onclick="return confirm('¿Está seguro de eliminar este traslado BÁSICA?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="no-data">
            <div>📋</div>
            <h3>No hay traslados BÁSICA registrados</h3>
            <p>No se encontraron registros de traslados para educación básica en el sistema.</p>
            <a href="agregar.php" class="btn-add" style="margin-top:15px;">➕ Agregar el primer traslado</a>
        </div>
        <?php endif; ?>
        
        <div class="total-registros">
            <strong>Total de registros:</strong> <span style="color:#007bff; font-weight:bold;"><?= $result->num_rows ?></span> traslado(s) BÁSICA
        </div>
    </div>
</div>

<script>
// Mejorar la experiencia de usuario
document.addEventListener('DOMContentLoaded', function() {
    // Agregar efecto hover a las filas
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
            this.style.transition = 'all 0.3s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Confirmación antes de eliminar
    const deleteLinks = document.querySelectorAll('.delete');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro de eliminar este traslado BÁSICA? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
});
</script>

</body>
</html>