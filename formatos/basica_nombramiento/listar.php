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
</div>

<h1 style="text-align:center; color:#155724;">📋 Lista de Nombramientos - Educación Básica</h1>

<style>
.table-container {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin: 20px auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    overflow-x: auto;
}
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
}
.data-table th {
    background: #2c3e50;
    color: white;
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
    font-size: 14px;
}
.data-table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}
.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    font-weight: bold;
    text-decoration: none;
    display: inline-block;
    margin: 2px;
    transition: all 0.3s ease;
}
.btn-primary {
    background: #007bff;
    color: white;
}
.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-1px);
}
.btn-danger {
    background: #dc3545;
    color: white;
}
.btn-danger:hover {
    background: #c82333;
    transform: translateY(-1px);
}
.btn-success {
    background: #28a745;
    color: white;
}
.btn-success:hover {
    background: #218838;
    transform: translateY(-1px);
}
.alert {
    padding: 12px;
    border-radius: 5px;
    margin: 10px 0;
    text-align: center;
}
.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<?php
// Mostrar mensajes de éxito o error
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">✅ Nombramiento guardado correctamente</div>';
}
if (isset($_GET['updated'])) {
    echo '<div class="alert alert-success">✅ Nombramiento actualizado correctamente</div>';
}
if (isset($_GET['deleted'])) {
    echo '<div class="alert alert-success">✅ Nombramiento eliminado correctamente</div>';
}
if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger">❌ Error al procesar la solicitud</div>';
}

// Consulta para obtener los nombramientos
$sql = "SELECT fnb.*, 
               bip.jornada_trabajo, bip.requisito,
               bcc.centro_educativo as centro_cuenta, bcc.cargo as cargo_cuenta,
               bj.sustituye_a
        FROM formato_nombramiento_basica fnb
        LEFT JOIN basica_info_profesional bip ON fnb.id = bip.nombramiento_id
        LEFT JOIN basica_centro_cuenta bcc ON fnb.id = bcc.nombramiento_id
        LEFT JOIN basica_justificacion bj ON fnb.id = bj.nombramiento_id
        ORDER BY fnb.fecha_registro DESC";

$result = $conn->query($sql);
?>

<div class="table-container">
    <div style="text-align: right; margin-bottom: 15px;">
        <a href="agregar.php" class="btn btn-success">➕ Nuevo Nombramiento</a>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Docente</th>
                <th>Identidad</th>
                <th>Tipo de Nombramiento</th>
                <th>Nivel Educativo</th>
                <th>Fecha Inicio</th>
                <th>Centro Educativo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["nombre_docente"]) . "</td>";
                    echo "<td>" . $row["identidad"] . "</td>";
                    echo "<td>" . $row["tipo_nombramiento"] . "</td>";
                    echo "<td>" . $row["nivel_educativo"] . "</td>";
                    echo "<td>" . $row["fecha_inicio"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["centro_cuenta"]) . "</td>";
                    echo "<td>
                            <a href='modificar.php?id=" . $row["id"] . "' class='btn btn-primary'>✏️ Modificar</a>
                            <a href='eliminar.php?id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"¿Está seguro de eliminar este nombramiento?\")'>🗑️ Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align: center; padding: 20px; color: #666;'>No hay nombramientos registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
include "../../includes/footer.php";
?>