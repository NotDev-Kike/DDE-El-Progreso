<?php
include "../includes/db.php";
include "../includes/header.php";
?>

<h2 style="text-align:center;">Lista de Usuarios</h2>

<div style="margin-bottom: 15px; text-align:center;">
    <a class="button" href="../menu.php" style="background-color:#0066cc; margin-right:10px;">🏠 Volver al Menú</a>
</div>

<div style="margin-bottom: 15px; text-align:center;">
    <a class="button" href="agregar.php" style="background-color:#28a745;">➕ Agregar Usuario</a>
</div>

<table id="tablaUsuarios" class="display nowrap" style="width:100%; font-size:13px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nombre Completo</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM usuarios ORDER BY id_usuario ASC";
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0):
            while($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['id_usuario']); ?></td>
            <td><?= htmlspecialchars($row['usuario']); ?></td>
            <td><?= htmlspecialchars($row['nombre_completo']); ?></td>
            <td><?= $row['rol'] === 'admin' ? 'Administrador' : 'Usuario'; ?></td>
            <td>
                <a class="button" href="modificar.php?id=<?= $row['id_usuario']; ?>" style="background-color:#ffc107; margin-right:5px;">✏️ Modificar</a>
                <a class="button" href="eliminar.php?id=<?= $row['id_usuario']; ?>" style="background-color:#dc3545;" onclick="return confirm('¿Seguro que quieres eliminar este usuario?');">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
        <tr>
            <td colspan="5">No hay usuarios registrados.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
$(document).ready(function() {
    if (!$.fn.DataTable.isDataTable('#tablaUsuarios')) {
        $('#tablaUsuarios').DataTable({
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
