<?php
include "../includes/header.php";
include "../includes/db.php";
?>

<div style="margin-bottom: 15px;">
    <a class="button" href="../menu.php" style="background-color:#0066cc;">🏠 Volver al Menú</a>
</div>

<h2>Lista de Nombramientos</h2>

<div style="margin-bottom: 15px;">
    <a class="button" href="agregar.php" style="background-color:#28a745;">➕ Agregar Nombramiento</a>
</div>

<table id="tablaNombramientos" class="display nowrap" style="width:100%; font-size:13px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM nombramientos ORDER BY id_nombramiento ASC";
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0):
            while($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['id_nombramiento']); ?></td>
            <td style="white-space: normal; word-break: break-word;"><?= htmlspecialchars($row['nombre_nombramiento']); ?></td>
            <td>
                <a class="button" href="modificar.php?id=<?= $row['id_nombramiento']; ?>" style="background-color:#ffc107; display:inline-block; margin-right:5px;">✏️ Modificar</a>
                <a class="button" href="eliminar.php?id=<?= $row['id_nombramiento']; ?>" style="background-color:#dc3545; display:inline-block;" onclick="return confirm('¿Seguro que quieres eliminar este nombramiento?');">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
        <tr>
            <td colspan="3">No hay registros en la tabla Nombramientos.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
$(document).ready(function() {
    if (!$.fn.DataTable.isDataTable('#tablaNombramientos')) {
        $('#tablaNombramientos').DataTable({
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