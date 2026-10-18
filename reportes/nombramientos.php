<?php
include "../includes/db.php";
include "../includes/header.php";
?>

<h2 style="text-align:center; color:#155724;">📋 Reporte Interactivo de Nombramientos</h2>
<p style="text-align:center; color:#333;">Visualiza los datos de los nombramientos con estadísticas y gráficos dinámicos.</p>

<hr style="margin:20px 0;">

<div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
    <?php
    $totalNombramientos = $conn->query("SELECT COUNT(*) AS total FROM nombramientos")->fetch_assoc()['total'];
    $nombresUnicos = $conn->query("SELECT COUNT(DISTINCT nombre_nombramiento) AS total FROM nombramientos")->fetch_assoc()['total'];

    $dataInicial = $conn->query("
        SELECT LEFT(nombre_nombramiento, 1) AS inicial, COUNT(*) AS cantidad
        FROM nombramientos
        GROUP BY inicial
        ORDER BY inicial ASC
    ");
    $iniciales = [];
    $cantidades = [];
    while($row = $dataInicial->fetch_assoc()) {
        $iniciales[] = $row['inicial'];
        $cantidades[] = $row['cantidad'];
    }
    ?>

    <div style="background:#e9f7ef; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $totalNombramientos; ?></h3>
        <p>Total de Nombramientos</p>
    </div>

    <div style="background:#cfe2ff; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $nombresUnicos; ?></h3>
        <p>Nombramientos Únicos</p>
    </div>
</div>

<div style="width:95%; margin:auto;" class="table-container">
    <table id="tablaNombramientos" class="display" style="width:100%;">
        <thead style="background-color:#006400; color:white;">
            <tr>
                <th>ID</th>
                <th>Nombre del Nombramiento</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM nombramientos ORDER BY id_nombramiento ASC";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['id_nombramiento']); ?></td>
                <td><?= htmlspecialchars($row['nombre_nombramiento']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div style="max-width:900px; margin:40px auto;">
    <canvas id="chartNombramientos"></canvas>
</div>

<div style="text-align:center; margin-top:30px;">
    <a class="button" href="../menu.php" style="background-color:#0066cc;">← Volver al Menú</a>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {

    $('#tablaNombramientos').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        pageLength: 10
    });

    const ctx = document.getElementById('chartNombramientos');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($iniciales); ?>,
            datasets: [{
                label: 'Cantidad de nombramientos por inicial',
                data: <?= json_encode($cantidades); ?>,
                borderWidth: 1,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(0, 128, 128, 0.8)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Distribución por inicial del nombre del nombramiento' }
            },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>

<style>
.table-container {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.dataTables_wrapper {
    font-size: 14px;
}
</style>

<?php include "../includes/footer.php"; ?>