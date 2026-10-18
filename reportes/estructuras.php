<?php
include "../includes/db.php";
include "../includes/header.php";
?>

<h2 style="text-align:center; color:#155724;">📊 Panel de Reportes - Estructuras</h2>
<p style="text-align:center; color:#333;">Visualiza un resumen estadístico de las estructuras registradas y su ocupación.</p>

<hr style="margin:20px 0;">

<div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
    <?php
    // Total de estructuras
    $total = $conn->query("SELECT COUNT(*) AS total FROM estructuras")->fetch_assoc()['total'];
    // Estructuras ocupadas
    $ocupadas = $conn->query("SELECT COUNT(*) AS total FROM estructuras WHERE asignada = 1")->fetch_assoc()['total'];
    // Estructuras libres
    $libres = $conn->query("SELECT COUNT(*) AS total FROM estructuras WHERE asignada = 0")->fetch_assoc()['total'];
    // Total de docentes distintos asignados
    $docentes = $conn->query("SELECT COUNT(DISTINCT id_docente) AS total FROM estructuras WHERE id_docente IS NOT NULL")->fetch_assoc()['total'];
    ?>

    <div style="background:#e9f7ef; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $total; ?></h3>
        <p>Total de Estructuras</p>
    </div>

    <div style="background:#ffeeba; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $ocupadas; ?></h3>
        <p>Estructuras Asignadas</p>
    </div>

    <div style="background:#cfe2ff; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $libres; ?></h3>
        <p>Estructuras Libres</p>
    </div>

    <div style="background:#e2f0cb; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= $docentes; ?></h3>
        <p>Docentes Asignados</p>
    </div>
</div>

<div style="max-width:900px; margin:auto;">
    <canvas id="chartTipos"></canvas>
    <canvas id="chartNiveles" style="margin-top:40px;"></canvas>
</div>

<div style="text-align:center; margin-top:30px;">
    <a class="button" href="../menu.php" style="background-color:#0066cc;">🏠 Volver al Menú</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php
// Datos para gráfico de tipo de plaza
$dataTipos = $conn->query("
    SELECT tipo_plaza, COUNT(*) AS cantidad
    FROM estructuras
    GROUP BY tipo_plaza
");
$tipos = [];
$cantTipos = [];
while($row = $dataTipos->fetch_assoc()) {
    $tipos[] = $row['tipo_plaza'];
    $cantTipos[] = $row['cantidad'];
}

// Datos para gráfico por nivel
$dataNiveles = $conn->query("
    SELECT nivel, COUNT(*) AS cantidad
    FROM estructuras
    GROUP BY nivel
");
$niveles = [];
$cantNiveles = [];
while($row = $dataNiveles->fetch_assoc()) {
    $niveles[] = $row['nivel'];
    $cantNiveles[] = $row['cantidad'];
}
?>

// Gráfico de tipo de plaza
const ctx1 = document.getElementById('chartTipos');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: <?= json_encode($tipos); ?>,
        datasets: [{
            label: 'Cantidad de estructuras por tipo de plaza',
            data: <?= json_encode($cantTipos); ?>,
            borderWidth: 1,
            backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: true, text: 'Distribución por tipo de plaza' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Gráfico de nivel
const ctx2 = document.getElementById('chartNiveles');
new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: <?= json_encode($niveles); ?>,
        datasets: [{
            data: <?= json_encode($cantNiveles); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(54, 162, 235, 0.6)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Distribución por nivel' }
        }
    }
});
</script>

<?php include "../includes/footer.php"; ?>
