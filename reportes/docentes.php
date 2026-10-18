<?php
include "../includes/db.php";
include "../includes/header.php";
?>

<h2 style="text-align:center; color:#155724;">👩‍🏫 Reporte General de Docentes</h2>
<p style="text-align:center; color:#333;">Visualiza estadísticas generales sobre los docentes registrados en el sistema.</p>

<hr style="margin:20px 0;">

<div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
    <?php
    // Consultas CORREGIDAS con JOIN entre docentes y estructuras
    $total_docentes = $conn->query("SELECT COUNT(*) AS total FROM docentes")->fetch_assoc()['total'];
    
    // Docentes CON estructura asignada
    $con_estructura = $conn->query("
        SELECT COUNT(DISTINCT d.id_docente) AS total 
        FROM docentes d 
        INNER JOIN estructuras e ON d.id_docente = e.id_docente
        WHERE e.asignada = 1
    ")->fetch_assoc()['total'];
    
    // Docentes SIN estructura asignada
    $sin_estructura = $conn->query("
        SELECT COUNT(*) AS total 
        FROM docentes d 
        LEFT JOIN estructuras e ON d.id_docente = e.id_docente AND e.asignada = 1
        WHERE e.id_estructura IS NULL
    ")->fetch_assoc()['total'];
    
    // Estructuras diferentes asignadas
    $estructuras_diferentes = $conn->query("
        SELECT COUNT(DISTINCT e.codigo) AS total 
        FROM estructuras e 
        WHERE e.asignada = 1 AND e.id_docente IS NOT NULL
    ")->fetch_assoc()['total'];
    ?>

    <div style="background:#e9f7ef; padding:20px; border-radius:10px; text-align:center; width:220px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $total_docentes; ?></h3>
        <p>Total de Docentes</p>
    </div>

    <div style="background:#cfe2ff; padding:20px; border-radius:10px; text-align:center; width:220px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $con_estructura; ?></h3>
        <p>Con Estructura Asignada</p>
    </div>

    <div style="background:#ffeeba; padding:20px; border-radius:10px; text-align:center; width:220px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $sin_estructura; ?></h3>
        <p>Sin Estructura Asignada</p>
    </div>

    <div style="background:#e2f0cb; padding:20px; border-radius:10px; text-align:center; width:220px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $estructuras_diferentes; ?></h3>
        <p>Estructuras Diferentes</p>
    </div>
</div>

<div style="max-width:900px; margin:auto;">
    <canvas id="chartEstructuras"></canvas>
</div>

<div style="margin-top:50px;">
    <h3 style="text-align:center; color:#155724;">📋 Lista Completa de Docentes con sus Estructuras</h3>
    <table id="tablaDocentes" class="display nowrap" style="width:100%; font-size:13px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Identidad</th>
                <th>Estructura Asignada</th>
                <th>Código Estructura</th>
                <th>Nivel</th>
                <th>Tipo Plaza</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "
                SELECT 
                    d.id_docente,
                    d.nombre_docente,
                    d.identidad,
                    e.codigo as estructura_codigo,
                    e.nivel,
                    e.tipo_plaza,
                    CASE 
                        WHEN e.id_estructura IS NOT NULL AND e.asignada = 1 THEN 'SÍ'
                        ELSE 'NO'
                    END as tiene_estructura
                FROM docentes d 
                LEFT JOIN estructuras e ON d.id_docente = e.id_docente AND e.asignada = 1
                ORDER BY d.id_docente ASC
            ";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['id_docente']); ?></td>
                <td><?= htmlspecialchars($row['nombre_docente']); ?></td>
                <td><?= htmlspecialchars($row['identidad']); ?></td>
                <td><?= htmlspecialchars($row['tiene_estructura']); ?></td>
                <td><?= htmlspecialchars($row['estructura_codigo'] ?? 'N/A'); ?></td>
                <td><?= htmlspecialchars($row['nivel'] ?? 'N/A'); ?></td>
                <td><?= htmlspecialchars($row['tipo_plaza'] ?? 'N/A'); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div style="text-align:center; margin-top:30px;">
    <a class="button" href="../menu.php" style="background-color:#0066cc;">Volver al Menú</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaDocentes').DataTable({
        responsive: true,
        language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        pageLength: 10,
        order: [[0, 'asc']]
    });
});
</script>

<script>
<?php
// Gráfico: Distribución por tipo de plaza
$dataTipoPlaza = $conn->query("
    SELECT 
        COALESCE(tipo_plaza, 'SIN ASIGNAR') as tipo_plaza, 
        COUNT(*) AS cantidad
    FROM estructuras 
    WHERE asignada = 1 AND id_docente IS NOT NULL
    GROUP BY tipo_plaza
    ORDER BY cantidad DESC
");
$tipo_plaza_labels = [];
$tipo_plaza_count = [];
while($row = $dataTipoPlaza->fetch_assoc()) {
    $tipo_plaza_labels[] = $row['tipo_plaza'];
    $tipo_plaza_count[] = $row['cantidad'];
}
?>

const ctx = document.getElementById('chartEstructuras');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($tipo_plaza_labels); ?>,
        datasets: [{
            label: 'Docentes por tipo de plaza',
            data: <?= json_encode($tipo_plaza_count); ?>,
            backgroundColor: [
                'rgba(40, 167, 69, 0.6)',
                'rgba(255, 193, 7, 0.6)',
                'rgba(220, 53, 69, 0.6)',
                'rgba(108, 117, 125, 0.6)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Distribución de docentes por tipo de plaza'
            }
        }
    }
});
</script>

<?php include "../includes/footer.php"; ?>