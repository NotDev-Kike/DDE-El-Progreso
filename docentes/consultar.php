<?php
include "../includes/header.php";
include "../includes/db.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id <= 0){
    echo "<p>ID inválido.</p><a class='button' href='listar.php'>Volver</a>";
    include "../includes/footer.php";
    exit;
}

// Obtener datos básicos del docente
$stmt = $conn->prepare("SELECT id_docente, nombre_docente, identidad, nombre_docente AS nombre FROM docentes WHERE id_docente = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$doc = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$doc){
    echo "<p>Docente no encontrado.</p><a class='button' href='listar.php'>Volver</a>";
    include "../includes/footer.php";
    exit;
}
?>

<div style="margin-bottom:15px;">
    <a class="button" href="listar.php" style="background-color:#0066cc;">← Volver a lista</a>
    <a class="button" href="../menu.php" style="background-color:#0066cc; margin-left:10px;">🏠 Menú</a>
</div>

<h2>Consulta: <?= htmlspecialchars($doc['nombre_docente']); ?> (ID <?= $doc['id_docente']; ?>)</h2>

<!-- PESTAÑAS SIMPLES -->
<div style="display:flex; gap:8px; margin-bottom:15px;">
    <button class="tabBtn" data-tab="datos" style="padding:8px 12px; background:#e9f7ef; border-radius:6px; border:none; cursor:pointer;">Datos</button>
    <button class="tabBtn" data-tab="estructuras" style="padding:8px 12px; background:#cfe2ff; border-radius:6px; border:none; cursor:pointer;">Estructuras asignadas</button>
    <button class="tabBtn" data-tab="nombramientos" style="padding:8px 12px; background:#fff3cd; border-radius:6px; border:none; cursor:pointer;">Nombramientos</button>
    <button class="tabBtn" data-tab="traslados" style="padding:8px 12px; background:#f8d7da; border-radius:6px; border:none; cursor:pointer;">Traslados</button>
    <button class="tabBtn" data-tab="unificado" style="padding:8px 12px; background:#e2f0cb; border-radius:6px; border:none; cursor:pointer;">Acciones / Unificado</button>
</div>

<!-- DATOS -->
<div id="datos" class="tab" style="display:block;">
    <h3>Datos Personales</h3>
    <table style="width:100%; max-width:800px;">
        <tr><td><strong>Nombre:</strong></td><td><?= htmlspecialchars($doc['nombre_docente']); ?></td></tr>
        <tr><td><strong>Identidad:</strong></td><td><?= htmlspecialchars($doc['identidad']); ?></td></tr>
        <tr><td><strong>ID Docente:</strong></td><td><?= $doc['id_docente']; ?></td></tr>
    </table>
</div>

<!-- ESTRUCTURAS -->
<div id="estructuras" class="tab" style="display:none;">
    <h3>Estructuras asignadas</h3>
    <p>Listado de todas las estructuras que actualmente apuntan a este docente (si existen).</p>
    <table id="tablaEstructuras" class="display nowrap" style="width:100%; font-size:13px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nivel</th>
                <th>Tipo Plaza</th>
                <th>Asignada</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // traer estructuras asignadas
        $stmt = $conn->prepare("SELECT id_estructura, codigo, nivel, tipo_plaza, id_docente FROM estructuras WHERE id_docente = ? ORDER BY id_estructura ASC");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        while($e = $res->fetch_assoc()):
        ?>
            <tr>
                <td><?= $e['id_estructura']; ?></td>
                <td><?= htmlspecialchars($e['codigo']); ?></td>
                <td><?= htmlspecialchars($e['nivel']); ?></td>
                <td><?= htmlspecialchars($e['tipo_plaza']); ?></td>
                <td><?= $e['id_docente'] ? "Ocupada" : "Libre"; ?></td>
                <td>
                    <a class="button" href="../estructuras/modificar.php?id=<?= $e['id_estructura']; ?>" style="background:#ffc107; padding:6px; color:#000;">✏️</a>
                    <a class="button" href="../estructuras/liberar.php?id=<?= $e['id_estructura']; ?>" style="background:#dc3545; padding:6px; color:#fff;" onclick="return confirm('Liberar estructura?');">Liberar</a>
                </td>
            </tr>
        <?php
        endwhile;
        $stmt->close();
        ?>
        </tbody>
    </table>
</div>

<!-- NOMBRAMIENTOS -->
<div id="nombramientos" class="tab" style="display:none;">
    <h3>Nombramientos (Básica + Media)</h3>

    <?php
    // nombramiento basica
    $stmt = $conn->prepare("SELECT id, tipo_nombramiento, centro_educativo_cuenta AS centro, cargo_cuenta AS cargo, fecha_inicio, fecha_vencimiento, numero_horas FROM formato_nombramiento_basica WHERE identidad = (SELECT identidad FROM docentes WHERE id_docente = ?) ORDER BY fecha_inicio DESC");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $resB = $stmt->get_result();
    ?>

    <h4>Nombramiento - Básica</h4>
    <table class="display nowrap" style="width:100%;">
        <thead><tr><th>ID</th><th>Tipo</th><th>Centro</th><th>Cargo</th><th>Inicio</th><th>Venc.</th><th>Hrs</th></tr></thead>
        <tbody>
        <?php while($r = $resB->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id']; ?></td>
                <td><?= htmlspecialchars($r['tipo_nombramiento']); ?></td>
                <td><?= htmlspecialchars($r['centro']); ?></td>
                <td><?= htmlspecialchars($r['cargo']); ?></td>
                <td><?= $r['fecha_inicio']; ?></td>
                <td><?= $r['fecha_vencimiento']; ?></td>
                <td><?= $r['numero_horas']; ?></td>
            </tr>
        <?php endwhile; $stmt->close(); ?>
        </tbody>
    </table>

    <?php
    // nombramiento media
    $stmt = $conn->prepare("SELECT id, tipo_nombramiento, centro_cuenta AS centro, cargo_cuenta AS cargo, fecha_inicio, fecha_vencimiento, numero_horas FROM formato_nombramiento_media WHERE identidad = (SELECT identidad FROM docentes WHERE id_docente = ?) ORDER BY fecha_inicio DESC");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $resM = $stmt->get_result();
    ?>

    <h4>Nombramiento - Media</h4>
    <table class="display nowrap" style="width:100%;">
        <thead><tr><th>ID</th><th>Tipo</th><th>Centro</th><th>Cargo</th><th>Inicio</th><th>Venc.</th><th>Hrs</th></tr></thead>
        <tbody>
        <?php while($r = $resM->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id']; ?></td>
                <td><?= htmlspecialchars($r['tipo_nombramiento']); ?></td>
                <td><?= htmlspecialchars($r['centro']); ?></td>
                <td><?= htmlspecialchars($r['cargo']); ?></td>
                <td><?= $r['fecha_inicio']; ?></td>
                <td><?= $r['fecha_vencimiento']; ?></td>
                <td><?= $r['numero_horas']; ?></td>
            </tr>
        <?php endwhile; $stmt->close(); ?>
        </tbody>
    </table>
</div>

<!-- TRASLADOS -->
<div id="traslados" class="tab" style="display:none;">
    <h3>Traslados (Básica + Media)</h3>

    <?php
    $stmt = $conn->prepare("SELECT id, tipo_nombramiento, centro_educativo_cuenta AS centro, cargo_cuenta AS cargo, fecha_inicio, fecha_vencimiento, numero_horas FROM formato_traslado_basica WHERE identidad = (SELECT identidad FROM docentes WHERE id_docente = ?) ORDER BY fecha_inicio DESC");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $resT1 = $stmt->get_result();
    ?>

    <h4>Traslado - Básica</h4>
    <table class="display nowrap" style="width:100%;">
        <thead><tr><th>ID</th><th>Tipo</th><th>Centro</th><th>Cargo</th><th>Inicio</th><th>Venc.</th><th>Hrs</th></tr></thead>
        <tbody>
        <?php while($r = $resT1->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id']; ?></td>
                <td><?= htmlspecialchars($r['tipo_nombramiento']); ?></td>
                <td><?= htmlspecialchars($r['centro']); ?></td>
                <td><?= htmlspecialchars($r['cargo']); ?></td>
                <td><?= $r['fecha_inicio']; ?></td>
                <td><?= $r['fecha_vencimiento']; ?></td>
                <td><?= $r['numero_horas']; ?></td>
            </tr>
        <?php endwhile; $stmt->close(); ?>

        <?php
        $stmt = $conn->prepare("SELECT id, tipo_nombramiento, centro_destino AS centro, cargo_destino AS cargo, fecha_inicio, fecha_vencimiento, numero_horas FROM formato_traslado_media WHERE identidad = (SELECT identidad FROM docentes WHERE id_docente = ?) ORDER BY fecha_inicio DESC");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $resT2 = $stmt->get_result();
        ?>

        </tbody>
    </table>

    <h4>Traslado - Media</h4>
    <table class="display nowrap" style="width:100%;">
        <thead><tr><th>ID</th><th>Tipo</th><th>Centro</th><th>Cargo</th><th>Inicio</th><th>Venc.</th><th>Hrs</th></tr></thead>
        <tbody>
        <?php while($r = $resT2->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id']; ?></td>
                <td><?= htmlspecialchars($r['tipo_nombramiento']); ?></td>
                <td><?= htmlspecialchars($r['centro']); ?></td>
                <td><?= htmlspecialchars($r['cargo']); ?></td>
                <td><?= $r['fecha_inicio']; ?></td>
                <td><?= $r['fecha_vencimiento']; ?></td>
                <td><?= $r['numero_horas']; ?></td>
            </tr>
        <?php endwhile; $stmt->close(); ?>
        </tbody>
    </table>
</div>

<!-- UNIFICADO -->
<div id="unificado" class="tab" style="display:none;">
    <h3>Registros Unificados (Formato Unificado)</h3>
    <?php
    $stmt = $conn->prepare("SELECT * FROM formato_unificado WHERE identidad = (SELECT identidad FROM docentes WHERE id_docente = ?) ORDER BY fecha DESC");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $resU = $stmt->get_result();
    ?>
    <table class="display nowrap" style="width:100%;">
        <thead><tr><th>ID</th><th>Tipo Acción</th><th>Centro</th><th>Cargo</th><th>Fecha</th></tr></thead>
        <tbody>
        <?php while($r = $resU->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id']; ?></td>
                <td><?= htmlspecialchars($r['tipo_accion']); ?></td>
                <td><?= htmlspecialchars($r['nombre_centro_educativo']); ?></td>
                <td><?= htmlspecialchars($r['nombre_cargo']); ?></td>
                <td><?= htmlspecialchars($r['fecha']); ?></td>
            </tr>
        <?php endwhile; $stmt->close(); ?>
        </tbody>
    </table>
</div>

<script>
$('.tabBtn').on('click', function(){
    var t = $(this).data('tab');
    $('.tab').hide();
    $('#'+t).show();
    // opcional: destacar botón
    $('.tabBtn').css('opacity',0.6);
    $(this).css('opacity',1);
});

$(document).ready(function(){
    $('#tablaEstructuras').DataTable({ scrollX:true, language:{ url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" } });
});
</script>

<?php include "../includes/footer.php"; ?>
