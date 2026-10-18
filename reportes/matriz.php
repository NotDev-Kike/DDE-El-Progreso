<?php
include "../includes/db.php";
include "../includes/header.php";
?>

<h2 style="text-align:center; color:#155724;">📊 Panel de Reportes - Matriz Consolidada</h2>
<p style="text-align:center; color:#333;">Visualiza un resumen estadístico de la información consolidada de la matriz educativa.</p>

<hr style="margin:20px 0;">

<div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
    <?php
    // Consulta base igual a listar.php
    $sql_base = "
    SELECT * FROM (
        -- Nombramiento Básica
        SELECT 
            fnb.id AS id_registro,
            fnb.nombre_docente,
            fnb.identidad,
            fnb.tipo_nombramiento,
            bcc.centro_educativo AS centro,
            bcc.cargo AS cargo,
            fnb.fecha_inicio,
            fnb.fecha_vencimiento,
            IFNULL(fnb.numero_horas,0) AS numero_horas,
            'Nombramiento Básica' AS origen
        FROM formato_nombramiento_basica fnb
        LEFT JOIN basica_centro_cuenta bcc ON fnb.id = bcc.nombramiento_id

        UNION ALL

        -- Nombramiento Media
        SELECT 
            id AS id_registro,
            nombre_docente,
            identidad,
            tipo_nombramiento,
            centro_cuenta AS centro,
            cargo_cuenta AS cargo,
            fecha_inicio,
            fecha_vencimiento,
            IFNULL(numero_horas,0) AS numero_horas,
            'Nombramiento Media' AS origen
        FROM formato_nombramiento_media

        UNION ALL

        -- Traslado Básica
        SELECT 
            id AS id_registro,
            nombre_docente,
            identidad,
            tipo_nombramiento,
            centro_educativo_cuenta AS centro,
            cargo_cuenta AS cargo,
            fecha_inicio,
            fecha_vencimiento,
            IFNULL(numero_horas,0) AS numero_horas,
            'Traslado Básica' AS origen
        FROM formato_traslado_basica

        UNION ALL

        -- Traslado Media
        SELECT 
            td.id AS id_registro,
            td.nombre_docente,
            td.identidad,
            td.tipo_nombramiento,
            COALESCE(ce_destino.centro_educativo, ce_origen.centro_educativo) AS centro,
            COALESCE(ce_destino.cargo, ce_origen.cargo) AS cargo,
            td.fecha_inicio,
            td.fecha_vencimiento,
            IFNULL(td.numero_horas,0) AS numero_horas,
            'Traslado Media' AS origen
        FROM formato_traslado_media td
        LEFT JOIN centros_educativos_media ce_origen ON td.id = ce_origen.traslado_id AND ce_origen.tipo = 'ORIGEN'
        LEFT JOIN centros_educativos_media ce_destino ON td.id = ce_destino.traslado_id AND ce_destino.tipo = 'DESTINO'

        UNION ALL

        -- Cancelaciones
        SELECT 
            fc.id AS id_registro,
            fc.nombre_propuesto AS nombre_docente,
            fc.no_identidad AS identidad,
            fc.tipo_accion AS tipo_nombramiento,
            fc.nombre_centro_educativo AS centro,
            fc.nombre_cargo AS cargo,
            fc.fecha AS fecha_inicio,
            NULL AS fecha_vencimiento,
            IFNULL(fc.total_horas_semanales,0) AS numero_horas,
            CONCAT('Cancelación - ', fc.tipo_accion) AS origen
        FROM formato_cancelacion fc
    ) AS matriz
    ";

    // Estadísticas basadas en la matriz consolidada
    $total_registros = $conn->query("SELECT COUNT(*) AS total FROM ($sql_base) AS total_query")->fetch_assoc()['total'];
    
    $docentes_unicos = $conn->query("
        SELECT COUNT(DISTINCT nombre_docente) AS total 
        FROM ($sql_base) AS docentes_query
    ")->fetch_assoc()['total'];
    
    $total_horas = $conn->query("
        SELECT SUM(numero_horas) AS total 
        FROM ($sql_base) AS horas_query
    ")->fetch_assoc()['total'];
    
    $activos = $conn->query("
        SELECT COUNT(*) AS total 
        FROM ($sql_base) AS activos_query 
        WHERE fecha_vencimiento IS NULL OR fecha_vencimiento > CURDATE()
    ")->fetch_assoc()['total'];
    ?>

    <div style="background:#e9f7ef; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= number_format($total_registros); ?></h3>
        <p>Total de Registros</p>
    </div>

    <div style="background:#e2f0cb; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= number_format($docentes_unicos); ?></h3>
        <p>Docentes Únicos</p>
    </div>

    <div style="background:#cfe2ff; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= number_format($total_horas); ?></h3>
        <p>Total Horas/Semana</p>
    </div>

    <div style="background:#ffeeba; padding:20px; border-radius:10px; text-align:center; width:200px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
        <h3><?= number_format($activos); ?></h3>
        <p>Registros Activos</p>
    </div>
</div>

<div style="display:flex; flex-wrap:wrap; gap:40px; justify-content:center; margin:40px 0;">
    <div style="flex:1; min-width:400px; max-width:500px;">
        <canvas id="chartOrigen"></canvas>
    </div>
    <div style="flex:1; min-width:400px; max-width:500px;">
        <canvas id="chartTipo"></canvas>
    </div>
</div>

<div style="max-width:900px; margin:auto;">
    <canvas id="chartCentros"></canvas>
</div>

<div style="text-align:center; margin-top:30px;">
    <a class="button" href="../menu.php" style="background-color:#0066cc;">🏠 Volver al Menú</a>
    <a class="button" href="<?= htmlspecialchars(BASE_URL . '/matriz/listar.php', ENT_QUOTES, 'UTF-8') ?>" style="background-color:#28a745; margin-left:10px;">📋 Ver Lista Completa</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php
// Datos para gráfico por origen (Básica/Media/Cancelación)
$dataOrigen = $conn->query("
    SELECT 
        CASE 
            WHEN origen LIKE '%Básica%' THEN 'Básica'
            WHEN origen LIKE '%Media%' THEN 'Media' 
            WHEN origen LIKE '%Cancelación%' THEN 'Cancelación'
            ELSE 'Otros'
        END AS categoria,
        COUNT(*) AS cantidad
    FROM ($sql_base) AS origen_query
    GROUP BY categoria
    ORDER BY cantidad DESC
");
$origen_labels = [];
$origen_count = [];
while($row = $dataOrigen->fetch_assoc()) {
    $origen_labels[] = $row['categoria'];
    $origen_count[] = $row['cantidad'];
}

// Datos para gráfico por tipo de nombramiento
$dataTipo = $conn->query("
    SELECT 
        COALESCE(tipo_nombramiento, 'NO ESPECIFICADO') AS tipo,
        COUNT(*) AS cantidad
    FROM ($sql_base) AS tipo_query
    GROUP BY tipo_nombramiento
    ORDER BY cantidad DESC
    LIMIT 10
");
$tipo_labels = [];
$tipo_count = [];
while($row = $dataTipo->fetch_assoc()) {
    $tipo_labels[] = $row['tipo'];
    $tipo_count[] = $row['cantidad'];
}

// Datos para gráfico de centros más frecuentes
$dataCentros = $conn->query("
    SELECT 
        centro,
        COUNT(*) AS cantidad
    FROM ($sql_base) AS centros_query
    WHERE centro IS NOT NULL AND centro != ''
    GROUP BY centro
    ORDER BY cantidad DESC
    LIMIT 8
");
$centros_labels = [];
$centros_count = [];
while($row = $dataCentros->fetch_assoc()) {
    $centros_labels[] = $row['centro'];
    $centros_count[] = $row['cantidad'];
}
?>

// Gráfico por origen (Básica/Media/Cancelación)
const ctx1 = document.getElementById('chartOrigen');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($origen_labels); ?>,
        datasets: [{
            data: <?= json_encode($origen_count); ?>,
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 206, 86, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            title: { 
                display: true, 
                text: 'Distribución por Nivel Educativo',
                font: { size: 16 }
            }
        }
    }
});

// Gráfico por tipo de nombramiento
const ctx2 = document.getElementById('chartTipo');
new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: <?= json_encode($tipo_labels); ?>,
        datasets: [{
            data: <?= json_encode($tipo_count); ?>,
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(108, 117, 125, 0.8)',
                'rgba(0, 123, 255, 0.8)',
                'rgba(23, 162, 184, 0.8)',
                'rgba(102, 16, 242, 0.8)',
                'rgba(253, 126, 20, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            title: { 
                display: true, 
                text: 'Distribución por Tipo de Nombramiento',
                font: { size: 16 }
            }
        }
    }
});

// Gráfico de centros más frecuentes
const ctx3 = document.getElementById('chartCentros');
new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: <?= json_encode($centros_labels); ?>,
        datasets: [{
            label: 'Cantidad de registros',
            data: <?= json_encode($centros_count); ?>,
            backgroundColor: 'rgba(153, 102, 255, 0.6)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { 
                display: true, 
                text: 'Centros Educativos con Más Registros',
                font: { size: 16 }
            }
        },
        scales: {
            y: { 
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Cantidad de Registros'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Centros Educativos'
                }
            }
        }
    }
});
</script>

<style>
.button {
    display: inline-block;
    padding: 12px 24px;
    background-color: #0066cc;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}
.button:hover {
    background-color: #0056b3;
    color: white;
    text-decoration: none;
}
</style>

<?php include "../includes/footer.php"; ?>
