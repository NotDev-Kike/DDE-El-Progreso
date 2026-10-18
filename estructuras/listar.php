<?php
include "../includes/header.php";
include "../includes/db.php";
?>

<div style="margin-bottom: 15px;">
    <a class="button" href="../menu.php" style="background-color:#0066cc;">🏠 Volver al Menú</a>
    <a class="button" href="agregar.php" style="background-color:#28a745; margin-left:8px;">➕ Agregar Estructura</a>
</div>

<h2>Lista de Estructuras</h2>

<table id="tablaEstructuras" class="display nowrap" style="width:100%; font-size:13px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Nivel</th>
            <th>Tipo Plaza</th>
            <th>Estado</th>
            <th>Docente</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT e.*, d.nombre_docente FROM estructuras e LEFT JOIN docentes d ON e.id_docente = d.id_docente ORDER BY e.id_estructura ASC";
    $res = $conn->query($sql);
    while($r = $res->fetch_assoc()):
    ?>
        <tr>
            <td><?= $r['id_estructura']; ?></td>
            <td><?= htmlspecialchars($r['codigo']); ?></td>
            <td><?= htmlspecialchars($r['nivel']); ?></td>
            <td><?= htmlspecialchars($r['tipo_plaza']); ?></td>
            <td><?= $r['id_docente'] ? "Ocupada" : "Libre"; ?></td>
            <td><?= $r['id_docente'] ? htmlspecialchars($r['nombre_docente']) : ''; ?></td>
            <td>
                <a class="button" href="modificar.php?id=<?= $r['id_estructura']; ?>" style="background:#ffc107; padding:6px; color:#000;">✏️</a>
                <?php if(!$r['id_docente']): ?>
                    <a class="button" href="asignar.php?id=<?= $r['id_estructura']; ?>" style="background:#17a2b8; padding:6px; color:#fff;">Asignar</a>
                <?php else: ?>
                    <a class="button" href="liberar.php?id=<?= $r['id_estructura']; ?>" style="background:#dc3545; padding:6px; color:#fff;" onclick="return confirm('Liberar estructura?');">Liberar</a>
                <?php endif; ?>
                <a class="button" href="eliminar.php?id=<?= $r['id_estructura']; ?>" style="background:#6c757d; padding:6px; color:#fff;" onclick="return confirm('Eliminar estructura?');">Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<script>
$(document).ready(function(){
    $('#tablaEstructuras').DataTable({ scrollX:true, language:{ url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" } });
});
</script>

<?php include "../includes/footer.php"; ?>
