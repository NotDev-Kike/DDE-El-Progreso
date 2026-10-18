<?php
    include "../includes/header.php";
    include "../includes/db.php";

    // Filtros
    $centro = isset($_GET['centro']) ? trim($_GET['centro']) : '';
    $mes = isset($_GET['mes']) ? intval($_GET['mes']) : '';
    $tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';

    // Construir la consulta base
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

    // Aplicar filtros de manera individual
    $where_conditions = [];
    $params = [];
    $types = '';

    if($centro !== '') {
        $where_conditions[] = "centro LIKE CONCAT('%', ?, '%')";
        $params[] = $centro;
        $types .= 's';
    }

    if($mes !== '') {
        $where_conditions[] = "MONTH(fecha_inicio) = ?";
        $params[] = $mes;
        $types .= 'i'; // El mes es integer
    }

    if($tipo !== '') {
        $where_conditions[] = "tipo_nombramiento = ?";
        $params[] = $tipo;
        $types .= 's';
    }

    // Construir la consulta final
    $sql = $sql_base;

    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    $sql .= " ORDER BY fecha_inicio ASC";

    // Debug: mostrar la consulta SQL (puedes comentar esto después)
    // echo "<div style='background:#f8f9fa; padding:10px; margin:10px 0; border-radius:5px;'>";
    // echo "<strong>SQL:</strong> " . htmlspecialchars($sql);
    // echo "<br><strong>Params:</strong> " . implode(', ', $params);
    // echo "<br><strong>Types:</strong> " . $types;
    // echo "</div>";

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters si hay
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // Mostrar error si hay problema con la consulta
        echo "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:6px; margin:20px;'>";
        echo "<strong>Error en la consulta:</strong> " . $conn->error;
        echo "</div>";
        $result = false;
    }

    $rows = [];
    $totalHoras = 0;
    if($result && $result->num_rows > 0){
        while($r = $result->fetch_assoc()){
            $rows[] = $r;
            $totalHoras += (int)$r['numero_horas'];
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz Consolidada</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: Arial, sans-serif; }
        body { background-color:#f5f5f5; color:#333; }
        .main-content { padding:20px; }
        .navigation { text-align:center; margin-bottom:20px; }
        .btn { display:inline-block; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:bold; margin:0 5px; border:none; cursor:pointer; }
        .btn-menu { background-color:#0066cc; color:white; }
        .btn-refresh { background-color:#6c757d; color:white; }
        .btn-clear { background-color:#ffc107; color:#212529; }
        .container { max-width:1400px; margin:0 auto; background:white; box-shadow:0 0 10px rgba(0,0,0,0.1); padding:20px; border-radius:8px; }
        
        .filtros-container { background:#f8f9fa; padding:20px; border-radius:6px; margin-bottom:20px; border:1px solid #e9ecef; }
        .filtros-container form { display:flex; gap:15px; align-items:end; flex-wrap:wrap; }
        .form-group { display:flex; flex-direction:column; min-width:150px; }
        .form-group label { font-weight:bold; margin-bottom:5px; font-size:14px; color:#495057; }
        .form-group input, .form-group select { padding:10px 12px; border:1px solid #ced4da; border-radius:4px; font-size:14px; height:42px; }
        .btn-filter { background:#28a745; color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer; font-weight:bold; height:42px; min-width:120px; }
        .btn-filter:hover { background:#218838; }
        
        .total-horas { background:#e7f3ff; padding:15px; border-radius:6px; margin-bottom:20px; border-left:4px solid #007bff; font-size:16px; font-weight:bold; }
        
        table { width:100%; border-collapse:collapse; margin:20px 0; background:white; }
        th, td { border:1px solid #ddd; padding:12px; text-align:left; }
        th { background:#2c3e50; color:white; font-weight:bold; font-size:14px; }
        td { background:#fff; color:#333; font-size:13px; }
        
        .badge-origen { padding:4px 8px; border-radius:4px; font-size:11px; font-weight:bold; }
        .badge-basica { background:#e8f5e8; color:#2e7d32; }
        .badge-media { background:#e3f2fd; color:#1565c0; }
        .badge-cancelacion { background:#ffebee; color:#c62828; }
        
        .no-data { text-align:center; padding:40px; color:#666; }
        .dataTables_wrapper { margin-top:20px; }
        
        /* Estilos para DataTables */
        .dataTables_filter input { padding:6px; border:1px solid #ccc; border-radius:4px; }
        .dataTables_length select { padding:4px; border:1px solid #ccc; border-radius:4px; }
        
        /* Mensaje de no datos */
        .dataTables_empty { text-align:center !important; padding:40px !important; }
        
        /* Estilos para tipos de acción */
        .tipo-suspension { background:#fff3cd; color:#856404; padding:4px 8px; border-radius:3px; font-size:12px; font-weight:bold; }
        .tipo-cancelacion { background:#f8d7da; color:#721c24; padding:4px 8px; border-radius:3px; font-size:12px; font-weight:bold; }
        .tipo-destitucion { background:#d4edda; color:#155724; padding:4px 8px; border-radius:3px; font-size:12px; font-weight:bold; }
        .tipo-normal { background:#e3f2fd; color:#1976d2; padding:4px 8px; border-radius:3px; font-size:12px; font-weight:bold; }
        
        .filtro-activo { background:#d1ecf1 !important; border-left:4px solid #17a2b8 !important; }
        
        /* Mejoras visuales */
        .table-container { overflow-x: auto; }
        .dataTables_scroll { margin-top: 10px; }
    </style>
    </head>
    <body>

    <div class="main-content">
        <div class="navigation">
            <a href="../menu.php" class="btn btn-menu">🏠 Volver al Menú</a>
            <a href="listar.php" class="btn btn-refresh">🔄 Refrescar</a>
            <a href="listar.php" class="btn btn-clear">🧹 Limpiar Filtros</a>
        </div>

        <div class="container">
            <h1 style="margin-bottom:20px; color:#2c3e50; border-bottom:2px solid #007bff; padding-bottom:10px;">
                📊 Matriz Consolidada (Consulta Completa)
            </h1>
            
            <!-- Mostrar filtros activos -->
            <?php if($centro !== '' || $mes !== '' || $tipo !== ''): ?>
            <div style="background:#e7f3ff; padding:15px; border-radius:6px; margin-bottom:20px; border-left:4px solid #17a2b8;">
                <strong>Filtros aplicados:</strong>
                <?php 
                $filtros = [];
                if($centro !== '') $filtros[] = "Centro: <strong>" . htmlspecialchars($centro) . "</strong>";
                if($mes !== '') $filtros[] = "Mes: <strong>" . htmlspecialchars($mes) . "</strong>";
                if($tipo !== '') $filtros[] = "Tipo: <strong>" . htmlspecialchars($tipo) . "</strong>";
                echo implode(' | ', $filtros);
                ?>
            </div>
            <?php endif; ?>
            
            <div class="filtros-container">
                <form method="get" id="filtrosForm">
                    <div class="form-group <?= $centro !== '' ? 'filtro-activo' : '' ?>">
                        <label for="centro">Centro Educativo:</label>
                        <input type="text" id="centro" name="centro" value="<?= htmlspecialchars($centro) ?>" 
                            placeholder="Ej: Instituto Central...">
                    </div>
                    
                    <div class="form-group <?= $mes !== '' ? 'filtro-activo' : '' ?>">
                        <label for="mes">Mes de Inicio:</label>
                        <select id="mes" name="mes">
                            <option value="">Todos los meses</option>
                            <?php 
                            $meses = [
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                            ];
                            foreach($meses as $num => $nombre): ?>
                                <option value="<?= $num ?>" <?= $mes == $num ? 'selected' : '' ?>>
                                    <?= $nombre ?> (<?= $num ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group <?= $tipo !== '' ? 'filtro-activo' : '' ?>">
                        <label for="tipo">Tipo:</label>
                        <select id="tipo" name="tipo">
                            <option value="">Todos los tipos</option>
                            <option value="INTERINO" <?= $tipo=='INTERINO'?'selected':'' ?>>INTERINO</option>
                            <option value="PERMANENTE" <?= $tipo=='PERMANENTE'?'selected':'' ?>>PERMANENTE</option>
                            <option value="Suspensión" <?= $tipo=='Suspensión'?'selected':'' ?>>Suspensión</option>
                            <option value="Cancelación" <?= $tipo=='Cancelación'?'selected':'' ?>>Cancelación</option>
                            <option value="Destitución" <?= $tipo=='Destitución'?'selected':'' ?>>Destitución</option>
                        </select>
                    </div>
    
                    <div class="form-group">
                        <button type="submit" class="btn-filter">🔍 Aplicar Filtros</button>
                    </div>
                </form>
            </div>

            <div class="total-horas">
                ⏱️ <strong>Total de Horas Consolidado:</strong> 
                <span style="color:#007bff; font-size:18px;"><?= number_format($totalHoras) ?></span> horas
                <?php if(!empty($rows)): ?>
                    <span style="color:#28a745; font-size:14px; margin-left:15px;">
                        (<?= count($rows) ?> registro<?= count($rows) !== 1 ? 's' : '' ?> encontrado<?= count($rows) !== 1 ? 's' : '' ?>)
                    </span>
                <?php endif; ?>
            </div>

            <div class="table-container">
                <table id="tablaMatriz" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Docente</th>
                            <th>Identidad</th>
                            <th>Tipo</th>
                            <th>Centro Educativo</th>
                            <th>Cargo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Vencimiento</th>
                            <th>Horas</th>
                            <th>Origen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($rows)): ?>
                        <?php foreach($rows as $row): ?>
                        <tr>
                            <td style="font-weight:bold;"><?= $row['id_registro'] ?></td>
                            <td style="font-weight:500;"><?= htmlspecialchars($row['nombre_docente'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['identidad'] ?? 'N/A') ?></td>
                            <td>
                                <?php
                                $tipo = $row['tipo_nombramiento'] ?? 'N/A';
                                $tipoClass = 'tipo-normal';
                                if ($tipo == 'Suspensión') {
                                    $tipoClass = 'tipo-suspension';
                                } elseif ($tipo == 'Cancelación') {
                                    $tipoClass = 'tipo-cancelacion';
                                } elseif ($tipo == 'Destitución') {
                                    $tipoClass = 'tipo-destitucion';
                                } elseif (in_array($tipo, ['INTERINO', 'PERMANENTE'])) {
                                    $tipoClass = 'tipo-normal';
                                }
                                ?>
                                <span class="<?= $tipoClass ?>">
                                    <?= htmlspecialchars($tipo) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['centro'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['cargo'] ?? 'N/A') ?></td>
                            <td style="color:#28a745; font-weight:500;">
                                <?= $row['fecha_inicio'] ? date('d/m/Y', strtotime($row['fecha_inicio'])) : 'N/A' ?>
                            </td>
                            <td style="color:#dc3545; font-weight:500;">
                                <?= $row['fecha_vencimiento'] ? date('d/m/Y', strtotime($row['fecha_vencimiento'])) : 'N/A' ?>
                            </td>
                            <td style="text-align:center; font-weight:bold; color:#007bff;">
                                <?= (int)($row['numero_horas'] ?? 0) ?>
                            </td>
                            <td>
                                <?php 
                                $origen = $row['origen'] ?? 'N/A';
                                $badgeClass = '';
                                if (strpos($origen, 'Básica') !== false) {
                                    $badgeClass = 'badge-basica';
                                } elseif (strpos($origen, 'Media') !== false) {
                                    $badgeClass = 'badge-media';
                                } elseif (strpos($origen, 'Cancelación') !== false) {
                                    $badgeClass = 'badge-cancelacion';
                                }
                                ?>
                                <span class="badge-origen <?= $badgeClass ?>">
                                    <?= htmlspecialchars($origen) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fila vacía para DataTables -->
                        <tr>
                            <td colspan="10" class="no-data">No hay datos disponibles</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Inicializar DataTables
        var table = $('#tablaMatriz').DataTable({
            scrollX: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
            language: {
                "decimal": "",
                "emptyTable": "No hay datos disponibles en la tabla",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros coincidentes",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": activar para ordenar columna ascendente",
                    "sortDescending": ": activar para ordenar columna descendente"
                }
            },
            dom: '<"top"lf>rt<"bottom"ip><"clear">',
            columnDefs: [
                { targets: [0, 8], width: '80px' },
                { targets: [3, 9], width: '120px' },
                { targets: [6, 7], width: '110px' }
            ],
            order: [[6, 'asc']] // Ordenar por fecha de inicio por defecto
        });

        // Mejorar la experiencia de búsqueda
        $('#centro').on('keyup', function() {
            table.column(4).search(this.value).draw();
        });

        // Auto-submit del formulario cuando cambian los selects
        $('#mes, #tipo').on('change', function() {
            $('#filtrosForm').submit();
        });
    });
    </script>

    </body>
    </html>
    <?php include "../includes/footer.php"; ?>