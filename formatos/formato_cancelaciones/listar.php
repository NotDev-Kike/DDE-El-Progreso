<?php include "../../includes/header.php"; ?>
<?php include "../../includes/db.php"; ?>

<div style="margin-bottom: 15px; text-align: center;">
    <a href="javascript:void(0);" onclick="window.history.back();" 
       style="display: inline-block; background-color: #555; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; margin-right: 8px; font-weight: bold;">
        🔙 Volver Atrás
    </a>
    <a href="../../menu.php" 
       style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; margin-right: 8px; font-weight: bold;">
        🏠 Volver al Menú
    </a>
    <a href="agregar.php" 
       style="display: inline-block; background-color: #28a745; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; font-weight: bold;">
        ➕ Agregar Nuevo
    </a>
</div>

<h1 style="text-align:center; color:#155724;">📋 Lista de Formatos de Cancelación</h1>

<style>
.table-container {
    overflow-x: auto;
    margin: 20px 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}
table, th, td {
    border: 1px solid #ddd;
}
th, td {
    padding: 12px;
    text-align: left;
    color: #333; /* Color de texto oscuro para mejor visibilidad */
}
th {
    background-color: #2c3e50;
    color: white; /* Texto blanco para los encabezados */
    font-weight: bold;
}
tr:nth-child(even) {
    background-color: #f8f9fa;
}
tr:hover {
    background-color: #e9ecef;
}
.actions {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}
.btn {
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: bold;
    display: inline-block;
    text-align: center;
    min-width: 80px;
}
.btn-edit {
    background-color: #ffc107;
    color: #212529;
}
.btn-edit:hover {
    background-color: #e0a800;
    transform: translateY(-1px);
}
.btn-delete {
    background-color: #dc3545;
    color: white;
}
.btn-delete:hover {
    background-color: #c82333;
    transform: translateY(-1px);
}
.btn-view {
    background-color: #17a2b8;
    color: white;
}
.btn-view:hover {
    background-color: #138496;
    transform: translateY(-1px);
}
.success {
    color: #155724;
    margin: 15px 0;
    padding: 15px;
    background: #d4edda;
    border-radius: 5px;
    text-align: center;
    border: 1px solid #c3e6cb;
    font-weight: bold;
}
.empty-state {
    text-align: center;
    padding: 40px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 20px 0;
    color: #6c757d;
    border: 2px dashed #dee2e6;
}
.empty-state h3 {
    color: #495057;
    margin-bottom: 10px;
}
</style>

<?php
// Mostrar mensaje de éxito
if (isset($_GET['success'])) {
    echo '<div class="success">✅ Formato guardado exitosamente.</div>';
}

// Obtener todos los formatos de cancelación
$sql = "SELECT * FROM formato_cancelacion ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="table-container">';
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>No. Identidad</th>';
    echo '<th>Nombre</th>';
    echo '<th>Tipo de Acción</th>';
    echo '<th>Centro Educativo</th>';
    echo '<th>Fecha</th>';
    echo '<th>Acciones</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td><strong>' . $row['id'] . '</strong></td>';
        echo '<td>' . htmlspecialchars($row['no_identidad']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nombre_propuesto']) . '</td>';
        echo '<td><span style="padding: 4px 8px; border-radius: 3px; background: #e3f2fd; color: #1976d2; font-weight: bold;">' . htmlspecialchars($row['tipo_accion']) . '</span></td>';
        echo '<td>' . htmlspecialchars($row['nombre_centro_educativo']) . '</td>';
        echo '<td>' . date('d/m/Y', strtotime($row['fecha'])) . '</td>';
        echo '<td class="actions">';
        echo '<a href="modificar.php?id=' . $row['id'] . '" class="btn btn-edit">✏️ Modificar</a>';
        echo '<a href="eliminar.php?id=' . $row['id'] . '" class="btn btn-delete" onclick="return confirm(\'¿Está seguro de eliminar este formato?\')">🗑️ Eliminar</a>';
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<div class="empty-state">';
    echo '<h3>📭 No hay formatos registrados</h3>';
    echo '<p>No se han encontrado formatos de cancelación en el sistema.</p>';
    echo '<p>Haga clic en el botón "Agregar Nuevo" para crear el primer formato.</p>';
    echo '</div>';
}

$conn->close();
?>

<?php include "../../includes/footer.php"; ?>