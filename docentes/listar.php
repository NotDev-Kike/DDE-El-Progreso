<?php
include "../includes/header.php";
include "../includes/db.php";
?>

<div style="margin-bottom: 15px;">
    <a class="button" href="../menu.php" style="background-color:#0066cc;">🏠 Volver al Menú</a>
</div>

<h2>Lista de Docentes</h2>

<div style="margin-bottom: 15px;">
    <a class="button" href="agregar.php" style="background-color:#28a745;">➕ Agregar Docente</a>
</div>

<table id="tablaDocentes" class="display nowrap" style="width:100%; font-size:13px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre del Docente</th>
            <th>Identidad</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT id_docente, nombre_docente, identidad FROM docentes ORDER BY id_docente ASC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['id_docente']); ?></td>
            <td style="white-space: normal; word-break: break-word;"><?= htmlspecialchars($row['nombre_docente']); ?></td>
            <td><?= htmlspecialchars($row['identidad']); ?></td>
            <td>
                <a class="button" href="consultar.php?id=<?= $row['id_docente']; ?>" style="background-color:#17a2b8; color:#fff; padding:5px 10px; border-radius:5px; margin-right:5px;">🔍 Consultar</a>
                <a class="button" href="modificar.php?id=<?= $row['id_docente']; ?>" style="background-color:#ffc107; color:#fff; padding:5px 10px; border-radius:5px; margin-right:5px;">✏️ Modificar</a>
                <a class="button" href="eliminar.php?id=<?= $row['id_docente']; ?>" style="background-color:#dc3545; color:#fff; padding:5px 10px; border-radius:5px;" onclick="return confirm('¿Seguro que quieres eliminar este docente?');">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
        <tr>
            <td colspan="4">No hay registros en la tabla Docentes.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
$(document).ready(function() {
    if (!$.fn.DataTable.isDataTable('#tablaDocentes')) {
        $('#tablaDocentes').DataTable({
            scrollX: true,
            autoWidth: false,
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
            pageLength: 10,
            order: [[0, 'asc']]
        });
    }
});
</script>

<?php include "../includes/footer.php"; ?>
