<?php include "../../includes/header.php"; ?>
<?php include "../../includes/db.php"; ?>

<div style="margin-bottom: 15px; text-align: center;">
    <a href="javascript:void(0);" onclick="window.history.back();" 
       style="display: inline-block; background-color: #555; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; margin-right: 8px; font-weight: bold;">
        🔙 Volver Atrás
    </a>
</div>

<h1 style="text-align:center; color:#155724;">✏️ Modificar Formato de Cancelación</h1>

<style>
.form-container {
    background: #f9f9f9;
    border-radius: 10px;
    padding: 20px;
    max-width: 1000px;
    margin: 20px auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
fieldset {
    border: 2px solid #28a745;
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 8px;
    background: white;
}
legend {
    font-weight: bold;
    color: #155724;
    background: #e9f7ef;
    padding: 8px 15px;
    border-radius: 5px;
    border: 1px solid #28a745;
}
label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
    color: #333;
}
input[type="text"],
input[type="number"],
input[type="date"],
select {
    width: 100%;
    padding: 8px;
    margin-top: 4px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}
input[type="submit"] {
    background-color: #28a745;
    color: white;
    cursor: pointer;
    border: none;
    padding: 12px 25px;
    font-size: 16px;
    border-radius: 8px;
    display: block;
    margin: 20px auto;
    font-weight: bold;
    transition: all 0.3s ease;
}
input[type="submit"]:hover {
    background-color: #218838;
    transform: translateY(-2px);
}
.radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin: 10px 0;
}
.radio-group label {
    display: flex;
    align-items: center;
    font-weight: normal;
    margin: 5px 0;
}
.radio-group input[type="radio"] {
    width: auto;
    margin-right: 8px;
}
.checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin: 10px 0;
}
.checkbox-group label {
    display: flex;
    align-items: center;
    font-weight: normal;
    margin: 5px 0;
}
.checkbox-group input[type="checkbox"] {
    width: auto;
    margin-right: 8px;
}
.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
}
.form-group {
    flex: 1;
}
.table-container {
    width: 100%;
    overflow-x: auto;
    margin: 15px 0;
}
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
}
.data-table th {
    background: #2c3e50;
    color: white;
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
    font-size: 12px;
}
.data-table td {
    padding: 8px;
    border: 1px solid #ddd;
    text-align: center;
}
.data-table input {
    width: 100%;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
}
.btn-small {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    font-weight: bold;
    transition: all 0.3s ease;
}
.btn-add {
    background: #28a745;
    color: white;
}
.btn-add:hover {
    background: #218838;
}
.btn-remove {
    background: #dc3545;
    color: white;
}
.btn-remove:hover {
    background: #c82333;
}
.required::after {
    content: " *";
    color: #dc3545;
}
.form-section {
    background: #e9f7ef;
    padding: 10px;
    border-radius: 5px;
    margin: 10px 0;
    border-left: 4px solid #28a745;
}
</style>

<?php
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>alert('ID no válido'); window.location.href='listar.php';</script>";
    exit;
}

// Obtener datos del formato de cancelación
$sql = "SELECT * FROM formato_cancelacion WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cancelacion = $result->fetch_assoc();
$stmt->close();

if (!$cancelacion) {
    echo "<script>alert('Formato no encontrado'); window.location.href='listar.php';</script>";
    exit;
}

// Obtener datos relacionados (similar al código anterior)
$ciclos = [];
$sql_ciclos = "SELECT * FROM formato_ciclo_cancelacion WHERE formato_cancelacion_id = ?";
$stmt_ciclos = $conn->prepare($sql_ciclos);
$stmt_ciclos->bind_param("i", $id);
$stmt_ciclos->execute();
$result_ciclos = $stmt_ciclos->get_result();
if ($result_ciclos->num_rows > 0) {
    $ciclos = $result_ciclos->fetch_assoc();
}
$stmt_ciclos->close();

$estructuras = [];
$sql_estructura = "SELECT * FROM formato_estructura_presupuestaria_cancelacion WHERE formato_cancelacion_id = ?";
$stmt_estructura = $conn->prepare($sql_estructura);
$stmt_estructura->bind_param("i", $id);
$stmt_estructura->execute();
$result_estructura = $stmt_estructura->get_result();
while ($row = $result_estructura->fetch_assoc()) {
    $estructuras[] = $row;
}
$stmt_estructura->close();

$asignaturas = [];
$sql_asignaturas = "SELECT * FROM formato_asignatura_cancelacion WHERE formato_cancelacion_id = ?";
$stmt_asignaturas = $conn->prepare($sql_asignaturas);
$stmt_asignaturas->bind_param("i", $id);
$stmt_asignaturas->execute();
$result_asignaturas = $stmt_asignaturas->get_result();
while ($row = $result_asignaturas->fetch_assoc()) {
    $asignaturas[] = $row;
}
$stmt_asignaturas->close();

// Obtener datos específicos según tipo de acción
$accion = [];
switch ($cancelacion['tipo_accion']) {
    case 'Suspensión':
        $sql_accion = "SELECT * FROM formato_suspension_cancelacion WHERE formato_cancelacion_id = ?";
        break;
    case 'Cancelación':
        $sql_accion = "SELECT * FROM formato_cancelacion_detalle WHERE formato_cancelacion_id = ?";
        break;
    case 'Destitución':
        $sql_accion = "SELECT * FROM formato_destitucion_cancelacion WHERE formato_cancelacion_id = ?";
        break;
}

if (isset($sql_accion)) {
    $stmt_accion = $conn->prepare($sql_accion);
    $stmt_accion->bind_param("i", $id);
    $stmt_accion->execute();
    $result_accion = $stmt_accion->get_result();
    if ($result_accion->num_rows > 0) {
        $accion = $result_accion->fetch_assoc();
    }
    $stmt_accion->close();
}

// Procesar actualización (similar al código anterior)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn->begin_transaction();
        
        // Preparar variables para la actualización principal
        $no_identidad = $_POST['no_identidad'];
        $tipo_accion = $_POST['tipo_accion'];
        $nombre_propuesto = $_POST['nombre_propuesto'];
        $clave_srhd = $_POST['clave_srhd'];
        $nombre_cargo = $_POST['nombre_cargo'];
        $nombre_centro_educativo = $_POST['nombre_centro_educativo'];
        $codigo_centro = $_POST['codigo_centro'];
        $nivel_educativo = $_POST['nivel_educativo'];
        $lugar = $_POST['lugar'];
        $municipio = $_POST['municipio'];
        $departamento = $_POST['departamento'];
        $total_horas_semanales = $_POST['total_horas_semanales'];
        $fecha = $_POST['fecha'];
        $revisado_por = $_POST['revisado_por'];
        $firma_director_departamental = $_POST['firma_director_departamental'];
        $acuerdo_nombramiento = isset($_POST['acuerdo_nombramiento']) ? 1 : 0;
        $datos_cancelacion = isset($_POST['datos_cancelacion']) ? 1 : 0;
        
        // Actualizar tabla principal formato_cancelacion
        $sql = "UPDATE formato_cancelacion SET 
                no_identidad = ?, tipo_accion = ?, nombre_propuesto = ?, clave_srhd = ?, 
                nombre_cargo = ?, nombre_centro_educativo = ?, codigo_centro = ?, 
                nivel_educativo = ?, lugar = ?, municipio = ?, departamento = ?, 
                total_horas_semanales = ?, fecha = ?, revisado_por = ?, 
                firma_director_departamental = ?, acuerdo_nombramiento = ?, datos_cancelacion = ? 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssisssiii", 
            $no_identidad,
            $tipo_accion,
            $nombre_propuesto,
            $clave_srhd,
            $nombre_cargo,
            $nombre_centro_educativo,
            $codigo_centro,
            $nivel_educativo,
            $lugar,
            $municipio,
            $departamento,
            $total_horas_semanales,
            $fecha,
            $revisado_por,
            $firma_director_departamental,
            $acuerdo_nombramiento,
            $datos_cancelacion,
            $id
        );
        
        $stmt->execute();
        $stmt->close();
        
        // Preparar variables para ciclos
        $ciclo_comun = isset($_POST['ciclo_comun']) ? 1 : 0;
        $ciclo_basico_tecnico = isset($_POST['ciclo_basico_tecnico']) ? 1 : 0;
        $bachillerato_tc = isset($_POST['bachillerato_tc']) ? 1 : 0;
        $otros_ciclo = isset($_POST['otros_ciclo']) ? 1 : 0;
        $comercio = isset($_POST['comercio']) ? 1 : 0;
        $sat = isset($_POST['sat']) ? 1 : 0;
        $semed = isset($_POST['semed']) ? 1 : 0;
        $iii_ciclo_cebot = isset($_POST['iii_ciclo_cebot']) ? 1 : 0;
        $basica = isset($_POST['basica']) ? 1 : 0;
        
        // Actualizar ciclos en formato_ciclo_cancelacion
        $sql_ciclos = "UPDATE formato_ciclo_cancelacion SET 
                      ciclo_comun = ?, ciclo_basico_tecnico = ?, bachillerato_tc = ?, 
                      otros_ciclo = ?, comercio = ?, sat = ?, semed = ?, iii_ciclo_cebot = ?, basica = ? 
                      WHERE formato_cancelacion_id = ?";
        
        $stmt_ciclos = $conn->prepare($sql_ciclos);
        $stmt_ciclos->bind_param("iiiiiiiiii",
            $ciclo_comun,
            $ciclo_basico_tecnico,
            $bachillerato_tc,
            $otros_ciclo,
            $comercio,
            $sat,
            $semed,
            $iii_ciclo_cebot,
            $basica,
            $id
        );
        $stmt_ciclos->execute();
        $stmt_ciclos->close();
        
        // Eliminar estructura presupuestaria existente y insertar nueva
        $sql_delete_estructura = "DELETE FROM formato_estructura_presupuestaria_cancelacion WHERE formato_cancelacion_id = ?";
        $stmt_delete_estructura = $conn->prepare($sql_delete_estructura);
        $stmt_delete_estructura->bind_param("i", $id);
        $stmt_delete_estructura->execute();
        $stmt_delete_estructura->close();
        
        // Insertar estructura presupuestaria si existe
        if (isset($_POST['estructura_inst']) && is_array($_POST['estructura_inst'])) {
            $sql_estructura = "INSERT INTO formato_estructura_presupuestaria_cancelacion 
                              (formato_cancelacion_id, estructura_inst, estructura_prog, estructura_subprog, 
                               estructura_fnd, siarhd_depto, siarhd_munic, siarhd_centro, siarhd_plaza, 
                               siarhd_cargo, siarhd_jornada, siarhd_hrs_sem) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt_estructura = $conn->prepare($sql_estructura);
            
            foreach ($_POST['estructura_inst'] as $index => $estructura_inst) {
                if (!empty($estructura_inst)) {
                    $estructura_prog = $_POST['estructura_prog'][$index] ?? null;
                    $estructura_subprog = $_POST['estructura_subprog'][$index] ?? null;
                    $estructura_fnd = $_POST['estructura_fnd'][$index] ?? null;
                    $siarhd_depto = $_POST['siarhd_depto'][$index] ?? null;
                    $siarhd_munic = $_POST['siarhd_munic'][$index] ?? null;
                    $siarhd_centro = $_POST['siarhd_centro'][$index] ?? null;
                    $siarhd_plaza = $_POST['siarhd_plaza'][$index] ?? null;
                    $siarhd_cargo = $_POST['siarhd_cargo'][$index] ?? null;
                    $siarhd_jornada = $_POST['siarhd_jornada'][$index] ?? null;
                    $siarhd_hrs_sem = $_POST['siarhd_hrs_sem'][$index] ?? null;
                    
                    $stmt_estructura->bind_param("isssssssissi",
                        $id,
                        $estructura_inst,
                        $estructura_prog,
                        $estructura_subprog,
                        $estructura_fnd,
                        $siarhd_depto,
                        $siarhd_munic,
                        $siarhd_centro,
                        $siarhd_plaza,
                        $siarhd_cargo,
                        $siarhd_jornada,
                        $siarhd_hrs_sem
                    );
                    $stmt_estructura->execute();
                }
            }
            $stmt_estructura->close();
        }
        
        // Eliminar datos de acción existentes
        $sql_delete_accion = "DELETE FROM formato_suspension_cancelacion WHERE formato_cancelacion_id = ?";
        $stmt_delete_accion = $conn->prepare($sql_delete_accion);
        $stmt_delete_accion->bind_param("i", $id);
        $stmt_delete_accion->execute();
        $stmt_delete_accion->close();
        
        $sql_delete_accion = "DELETE FROM formato_cancelacion_detalle WHERE formato_cancelacion_id = ?";
        $stmt_delete_accion = $conn->prepare($sql_delete_accion);
        $stmt_delete_accion->bind_param("i", $id);
        $stmt_delete_accion->execute();
        $stmt_delete_accion->close();
        
        $sql_delete_accion = "DELETE FROM formato_destitucion_cancelacion WHERE formato_cancelacion_id = ?";
        $stmt_delete_accion = $conn->prepare($sql_delete_accion);
        $stmt_delete_accion->bind_param("i", $id);
        $stmt_delete_accion->execute();
        $stmt_delete_accion->close();
        
        // Insertar según el tipo de acción
        switch ($_POST['tipo_accion']) {
            case 'Suspensión':
                if (!empty($_POST['suspension_por'])) {
                    $suspension_por = $_POST['suspension_por'];
                    $suspension_a_partir = $_POST['suspension_a_partir'];
                    $suspension_de = $_POST['suspension_de'];
                    $suspension_del = $_POST['suspension_del'];
                    $suspension_al = $_POST['suspension_al'];
                    $suspension_de2 = $_POST['suspension_de2'];
                    $suspension_del2 = $_POST['suspension_del2'];
                    
                    $sql_accion = "INSERT INTO formato_suspension_cancelacion 
                                  (formato_cancelacion_id, suspension_por, suspension_a_partir, 
                                   suspension_de, suspension_del, suspension_al, suspension_de2, suspension_del2) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $stmt_accion = $conn->prepare($sql_accion);
                    $stmt_accion->bind_param("isssssss",
                        $id,
                        $suspension_por,
                        $suspension_a_partir,
                        $suspension_de,
                        $suspension_del,
                        $suspension_al,
                        $suspension_de2,
                        $suspension_del2
                    );
                    $stmt_accion->execute();
                    $stmt_accion->close();
                }
                break;
                
            case 'Cancelación':
                if (!empty($_POST['cancelacion_por'])) {
                    $cancelacion_por = $_POST['cancelacion_por'];
                    $cancelacion_a_partir = $_POST['cancelacion_a_partir'];
                    $cancelacion_de = $_POST['cancelacion_de'];
                    $cancelacion_del = $_POST['cancelacion_del'];
                    
                    $sql_accion = "INSERT INTO formato_cancelacion_detalle 
                                  (formato_cancelacion_id, cancelacion_por, cancelacion_a_partir, 
                                   cancelacion_de, cancelacion_del) 
                                  VALUES (?, ?, ?, ?, ?)";
                    
                    $stmt_accion = $conn->prepare($sql_accion);
                    $stmt_accion->bind_param("issss",
                        $id,
                        $cancelacion_por,
                        $cancelacion_a_partir,
                        $cancelacion_de,
                        $cancelacion_del
                    );
                    $stmt_accion->execute();
                    $stmt_accion->close();
                }
                break;
                
            case 'Destitución':
                if (!empty($_POST['destitucion_por'])) {
                    $destitucion_por = $_POST['destitucion_por'];
                    $destitucion_a_partir = $_POST['destitucion_a_partir'];
                    $destitucion_de = $_POST['destitucion_de'];
                    $destitucion_del = $_POST['destitucion_del'];
                    
                    $sql_accion = "INSERT INTO formato_destitucion_cancelacion 
                                  (formato_cancelacion_id, destitucion_por, destitucion_a_partir, 
                                   destitucion_de, destitucion_del) 
                                  VALUES (?, ?, ?, ?, ?)";
                    
                    $stmt_accion = $conn->prepare($sql_accion);
                    $stmt_accion->bind_param("issss",
                        $id,
                        $destitucion_por,
                        $destitucion_a_partir,
                        $destitucion_de,
                        $destitucion_del
                    );
                    $stmt_accion->execute();
                    $stmt_accion->close();
                }
                break;
        }
        
        // Eliminar asignaturas existentes y insertar nuevas
        $sql_delete_asignaturas = "DELETE FROM formato_asignatura_cancelacion WHERE formato_cancelacion_id = ?";
        $stmt_delete_asignaturas = $conn->prepare($sql_delete_asignaturas);
        $stmt_delete_asignaturas->bind_param("i", $id);
        $stmt_delete_asignaturas->execute();
        $stmt_delete_asignaturas->close();
        
        // Insertar nuevas asignaturas en formato_asignatura_cancelacion
        if (isset($_POST['asignatura']) && is_array($_POST['asignatura'])) {
            $sql_asignaturas = "INSERT INTO formato_asignatura_cancelacion 
                               (formato_cancelacion_id, asignatura, horas, curso, grupo, jornada) 
                               VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt_asignaturas = $conn->prepare($sql_asignaturas);
            
            foreach ($_POST['asignatura'] as $index => $asignatura) {
                if (!empty($asignatura)) {
                    $horas = $_POST['horas'][$index] ?? null;
                    $curso = $_POST['curso'][$index] ?? null;
                    $grupo = $_POST['grupo'][$index] ?? null;
                    $jornada = $_POST['jornada'][$index] ?? null;
                    
                    $stmt_asignaturas->bind_param("isisss",
                        $id,
                        $asignatura,
                        $horas,
                        $curso,
                        $grupo,
                        $jornada
                    );
                    $stmt_asignaturas->execute();
                }
            }
            $stmt_asignaturas->close();
        }
        
        $conn->commit();
        echo "<script>alert('✅ Formato actualizado correctamente'); window.location.href='listar.php?success=1';</script>";
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<div style='color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin: 10px 0;'>❌ Error al actualizar el formulario: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="form-container">
    <form method="POST">
        <fieldset>
            <legend>Información Principal</legend>
            <div class="form-row">
                <div class="form-group">
                    <label class="required">No. Identidad</label>
                    <input type="text" name="no_identidad" required value="<?php echo htmlspecialchars($cancelacion['no_identidad']); ?>">
                </div>
                <div class="form-group">
                    <label>Nombre del Propuesto</label>
                    <input type="text" name="nombre_propuesto" value="<?php echo htmlspecialchars($cancelacion['nombre_propuesto']); ?>">
                </div>
            </div>
            
            <div class="form-section">
                <label class="required">Tipo de Acción</label>
                <div class="radio-group">
                    <label><input type="radio" name="tipo_accion" value="Suspensión" <?php echo $cancelacion['tipo_accion'] == 'Suspensión' ? 'checked' : ''; ?> required> Suspensión</label>
                    <label><input type="radio" name="tipo_accion" value="Cancelación" <?php echo $cancelacion['tipo_accion'] == 'Cancelación' ? 'checked' : ''; ?>> Cancelación</label>
                    <label><input type="radio" name="tipo_accion" value="Destitución" <?php echo $cancelacion['tipo_accion'] == 'Destitución' ? 'checked' : ''; ?>> Destitución</label>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Clave S R H D</label>
                    <input type="text" name="clave_srhd" value="<?php echo htmlspecialchars($cancelacion['clave_srhd']); ?>">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Información del Cargo</legend>
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre del Cargo</label>
                    <input type="text" name="nombre_cargo" value="<?php echo htmlspecialchars($cancelacion['nombre_cargo']); ?>">
                </div>
                <div class="form-group">
                    <label>Nombre del Centro Educativo</label>
                    <input type="text" name="nombre_centro_educativo" value="<?php echo htmlspecialchars($cancelacion['nombre_centro_educativo']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Código</label>
                    <input type="text" name="codigo_centro" value="<?php echo htmlspecialchars($cancelacion['codigo_centro']); ?>">
                </div>
                <div class="form-group">
                    <label>Nivel</label>
                    <input type="text" name="nivel_educativo" value="<?php echo htmlspecialchars($cancelacion['nivel_educativo']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Lugar</label>
                    <input type="text" name="lugar" value="<?php echo htmlspecialchars($cancelacion['lugar']); ?>">
                </div>
                <div class="form-group">
                    <label>Municipio</label>
                    <input type="text" name="municipio" value="<?php echo htmlspecialchars($cancelacion['municipio']); ?>">
                </div>
                <div class="form-group">
                    <label>Departamento</label>
                    <input type="text" name="departamento" value="<?php echo htmlspecialchars($cancelacion['departamento']); ?>">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Ciclos Educativos</legend>
            <div class="checkbox-group">
                <label><input type="checkbox" name="ciclo_comun" <?php echo !empty($ciclos['ciclo_comun']) ? 'checked' : ''; ?>> Ciclo Común</label>
                <label><input type="checkbox" name="ciclo_basico_tecnico" <?php echo !empty($ciclos['ciclo_basico_tecnico']) ? 'checked' : ''; ?>> Ciclo Básico Técnico</label>
                <label><input type="checkbox" name="bachillerato_tc" <?php echo !empty($ciclos['bachillerato_tc']) ? 'checked' : ''; ?>> Bachillerato T.C.</label>
                <label><input type="checkbox" name="otros_ciclo" <?php echo !empty($ciclos['otros_ciclo']) ? 'checked' : ''; ?>> Otros</label>
                <label><input type="checkbox" name="comercio" <?php echo !empty($ciclos['comercio']) ? 'checked' : ''; ?>> Comercio</label>
                <label><input type="checkbox" name="sat" <?php echo !empty($ciclos['sat']) ? 'checked' : ''; ?>> SAT</label>
                <label><input type="checkbox" name="semed" <?php echo !empty($ciclos['semed']) ? 'checked' : ''; ?>> Semed</label>
                <label><input type="checkbox" name="iii_ciclo_cebot" <?php echo !empty($ciclos['iii_ciclo_cebot']) ? 'checked' : ''; ?>> III Ciclo C.E.B OT</label>
                <label><input type="checkbox" name="basica" <?php echo !empty($ciclos['basica']) ? 'checked' : ''; ?>> BÁSICA</label>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Total de Horas Semanales</label>
                    <input type="number" name="total_horas_semanales" value="<?php echo $cancelacion['total_horas_semanales']; ?>">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Asignaturas</legend>
            <div class="table-container">
                <table class="data-table" id="asignaturas-table">
                    <thead>
                        <tr>
                            <th>Asignaturas</th>
                            <th>Horas</th>
                            <th>Curso</th>
                            <th>Grupo</th>
                            <th>Jornada</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asignaturas as $asignatura): ?>
                        <tr>
                            <td><input type="text" name="asignatura[]" value="<?php echo htmlspecialchars($asignatura['asignatura']); ?>"></td>
                            <td><input type="number" name="horas[]" value="<?php echo $asignatura['horas']; ?>"></td>
                            <td><input type="text" name="curso[]" value="<?php echo htmlspecialchars($asignatura['curso']); ?>"></td>
                            <td><input type="text" name="grupo[]" value="<?php echo htmlspecialchars($asignatura['grupo']); ?>"></td>
                            <td><input type="text" name="jornada[]" value="<?php echo htmlspecialchars($asignatura['jornada']); ?>"></td>
                            <td><button type="button" class="btn-small btn-remove" onclick="this.parentElement.parentElement.remove()">-</button></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><input type="text" name="asignatura[]"></td>
                            <td><input type="number" name="horas[]"></td>
                            <td><input type="text" name="curso[]"></td>
                            <td><input type="text" name="grupo[]"></td>
                            <td><input type="text" name="jornada[]"></td>
                            <td><button type="button" class="btn-small btn-add" onclick="agregarFilaAsignatura()">+</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>

        <fieldset>
            <legend>Estructura Presupuestaria</legend>
            <div class="table-container">
                <table class="data-table" id="estructura-table">
                    <thead>
                        <tr>
                            <th>Inst</th>
                            <th>Prog.</th>
                            <th>SubProg.</th>
                            <th>Fnd</th>
                            <th>Depto</th>
                            <th>Munic.</th>
                            <th>Centro</th>
                            <th>Plaza</th>
                            <th>Cargo</th>
                            <th>Jornada</th>
                            <th>Hrs/Sem</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estructuras as $estructura): ?>
                        <tr>
                            <td><input type="text" name="estructura_inst[]" value="<?php echo htmlspecialchars($estructura['estructura_inst']); ?>"></td>
                            <td><input type="text" name="estructura_prog[]" value="<?php echo htmlspecialchars($estructura['estructura_prog']); ?>"></td>
                            <td><input type="text" name="estructura_subprog[]" value="<?php echo htmlspecialchars($estructura['estructura_subprog']); ?>"></td>
                            <td><input type="text" name="estructura_fnd[]" value="<?php echo htmlspecialchars($estructura['estructura_fnd']); ?>"></td>
                            <td><input type="text" name="siarhd_depto[]" value="<?php echo htmlspecialchars($estructura['siarhd_depto']); ?>"></td>
                            <td><input type="text" name="siarhd_munic[]" value="<?php echo htmlspecialchars($estructura['siarhd_munic']); ?>"></td>
                            <td><input type="text" name="siarhd_centro[]" value="<?php echo htmlspecialchars($estructura['siarhd_centro']); ?>"></td>
                            <td><input type="number" name="siarhd_plaza[]" value="<?php echo $estructura['siarhd_plaza']; ?>"></td>
                            <td><input type="text" name="siarhd_cargo[]" value="<?php echo htmlspecialchars($estructura['siarhd_cargo']); ?>"></td>
                            <td><input type="text" name="siarhd_jornada[]" value="<?php echo htmlspecialchars($estructura['siarhd_jornada']); ?>"></td>
                            <td><input type="number" name="siarhd_hrs_sem[]" value="<?php echo $estructura['siarhd_hrs_sem']; ?>"></td>
                            <td><button type="button" class="btn-small btn-remove" onclick="this.parentElement.parentElement.remove()">-</button></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><input type="text" name="estructura_inst[]"></td>
                            <td><input type="text" name="estructura_prog[]"></td>
                            <td><input type="text" name="estructura_subprog[]"></td>
                            <td><input type="text" name="estructura_fnd[]"></td>
                            <td><input type="text" name="siarhd_depto[]"></td>
                            <td><input type="text" name="siarhd_munic[]"></td>
                            <td><input type="text" name="siarhd_centro[]"></td>
                            <td><input type="number" name="siarhd_plaza[]"></td>
                            <td><input type="text" name="siarhd_cargo[]"></td>
                            <td><input type="text" name="siarhd_jornada[]"></td>
                            <td><input type="number" name="siarhd_hrs_sem[]"></td>
                            <td><button type="button" class="btn-small btn-add" onclick="agregarFilaEstructura()">+</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>

        <fieldset>
            <legend>Detalles del Motivo</legend>
            <div id="detalles-accion">
                <?php
                $tipoAccion = $cancelacion['tipo_accion'];
                if ($tipoAccion == 'Suspensión' && !empty($accion)): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Suspensión Por:</label>
                            <input type="text" name="suspension_por" value="<?php echo htmlspecialchars($accion['suspension_por']); ?>">
                        </div>
                        <div class="form-group">
                            <label>A Partir de:</label>
                            <input type="date" name="suspension_a_partir" value="<?php echo $accion['suspension_a_partir']; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>de</label>
                            <input type="date" name="suspension_de" value="<?php echo $accion['suspension_de']; ?>">
                        </div>
                        <div class="form-group">
                            <label>del</label>
                            <input type="date" name="suspension_del" value="<?php echo $accion['suspension_del']; ?>">
                        </div>
                        <div class="form-group">
                            <label>al</label>
                            <input type="date" name="suspension_al" value="<?php echo $accion['suspension_al']; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>de</label>
                            <input type="date" name="suspension_de2" value="<?php echo $accion['suspension_de2']; ?>">
                        </div>
                        <div class="form-group">
                            <label>del</label>
                            <input type="date" name="suspension_del2" value="<?php echo $accion['suspension_del2']; ?>">
                        </div>
                    </div>
                <?php elseif ($tipoAccion == 'Cancelación' && !empty($accion)): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Cancelación Por:</label>
                            <input type="text" name="cancelacion_por" value="<?php echo htmlspecialchars($accion['cancelacion_por']); ?>">
                        </div>
                        <div class="form-group">
                            <label>A Partir de:</label>
                            <input type="date" name="cancelacion_a_partir" value="<?php echo $accion['cancelacion_a_partir']; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>de</label>
                            <input type="date" name="cancelacion_de" value="<?php echo $accion['cancelacion_de']; ?>">
                        </div>
                        <div class="form-group">
                            <label>del</label>
                            <input type="date" name="cancelacion_del" value="<?php echo $accion['cancelacion_del']; ?>">
                        </div>
                    </div>
                <?php elseif ($tipoAccion == 'Destitución' && !empty($accion)): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Destitución Por:</label>
                            <input type="text" name="destitucion_por" value="<?php echo htmlspecialchars($accion['destitucion_por']); ?>">
                        </div>
                        <div class="form-group">
                            <label>A Partir de:</label>
                            <input type="date" name="destitucion_a_partir" value="<?php echo $accion['destitucion_a_partir']; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>de</label>
                            <input type="date" name="destitucion_de" value="<?php echo $accion['destitucion_de']; ?>">
                        </div>
                        <div class="form-group">
                            <label>del</label>
                            <input type="date" name="destitucion_del" value="<?php echo $accion['destitucion_del']; ?>">
                        </div>
                    </div>
                <?php else: ?>
                    <p style="text-align: center; color: #666; font-style: italic;">Seleccione un tipo de acción para ver los campos específicos</p>
                <?php endif; ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>Documentos y Firma</legend>
            <div class="form-row">
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" name="fecha" value="<?php echo $cancelacion['fecha']; ?>">
                </div>
                <div class="form-group">
                    <label>Revisado por</label>
                    <input type="text" name="revisado_por" value="<?php echo htmlspecialchars($cancelacion['revisado_por']); ?>">
                </div>
                <div class="form-group">
                    <label>Firma Director Departamental</label>
                    <input type="text" name="firma_director_departamental" value="<?php echo htmlspecialchars($cancelacion['firma_director_departamental']); ?>">
                </div>
            </div>
            
            <div class="form-section">
                <label><strong>Documentos a Acompañar:</strong></label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="acuerdo_nombramiento" <?php echo $cancelacion['acuerdo_nombramiento'] ? 'checked' : ''; ?>> a) Acuerdo de Nombramiento</label>
                    <label><input type="checkbox" name="datos_cancelacion" <?php echo $cancelacion['datos_cancelacion'] ? 'checked' : ''; ?>> b) Datos que dan Origen a Cancelación (Acta de Defunción, Jubilación, Pensión, Renuncia, Resolución)</label>
                </div>
            </div>
        </fieldset>

        <input type="submit" value="💾 Actualizar Formulario">
    </form>
</div>

<script>
    // Función para agregar filas dinámicamente a la tabla de asignaturas
    function agregarFilaAsignatura() {
        const table = document.getElementById('asignaturas-table').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        
        newRow.innerHTML = `
            <td><input type="text" name="asignatura[]"></td>
            <td><input type="number" name="horas[]"></td>
            <td><input type="text" name="curso[]"></td>
            <td><input type="text" name="grupo[]"></td>
            <td><input type="text" name="jornada[]"></td>
            <td><button type="button" class="btn-small btn-remove" onclick="this.parentElement.parentElement.remove()">-</button></td>
        `;
    }

    // Función para agregar filas dinámicamente a la tabla de estructura presupuestaria
    function agregarFilaEstructura() {
        const table = document.getElementById('estructura-table').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        
        newRow.innerHTML = `
            <td><input type="text" name="estructura_inst[]"></td>
            <td><input type="text" name="estructura_prog[]"></td>
            <td><input type="text" name="estructura_subprog[]"></td>
            <td><input type="text" name="estructura_fnd[]"></td>
            <td><input type="text" name="siarhd_depto[]"></td>
            <td><input type="text" name="siarhd_munic[]"></td>
            <td><input type="text" name="siarhd_centro[]"></td>
            <td><input type="number" name="siarhd_plaza[]"></td>
            <td><input type="text" name="siarhd_cargo[]"></td>
            <td><input type="text" name="siarhd_jornada[]"></td>
            <td><input type="number" name="siarhd_hrs_sem[]"></td>
            <td><button type="button" class="btn-small btn-remove" onclick="this.parentElement.parentElement.remove()">-</button></td>
        `;
    }

    // Función para cargar campos específicos según el tipo de acción
    document.querySelectorAll('input[name="tipo_accion"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const detallesDiv = document.getElementById('detalles-accion');
            const tipoAccion = this.value;
            
            let campos = '';
            
            switch(tipoAccion) {
                case 'Suspensión':
                    campos = `
                        <div class="form-row">
                            <div class="form-group">
                                <label>Suspensión Por:</label>
                                <input type="text" name="suspension_por">
                            </div>
                            <div class="form-group">
                                <label>A Partir de:</label>
                                <input type="date" name="suspension_a_partir">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>de</label>
                                <input type="date" name="suspension_de">
                            </div>
                            <div class="form-group">
                                <label>del</label>
                                <input type="date" name="suspension_del">
                            </div>
                            <div class="form-group">
                                <label>al</label>
                                <input type="date" name="suspension_al">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>de</label>
                                <input type="date" name="suspension_de2">
                            </div>
                            <div class="form-group">
                                <label>del</label>
                                <input type="date" name="suspension_del2">
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'Cancelación':
                    campos = `
                        <div class="form-row">
                            <div class="form-group">
                                <label>Cancelación Por:</label>
                                <input type="text" name="cancelacion_por">
                            </div>
                            <div class="form-group">
                                <label>A Partir de:</label>
                                <input type="date" name="cancelacion_a_partir">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>de</label>
                                <input type="date" name="cancelacion_de">
                            </div>
                            <div class="form-group">
                                <label>del</label>
                                <input type="date" name="cancelacion_del">
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'Destitución':
                    campos = `
                        <div class="form-row">
                            <div class="form-group">
                                <label>Destitución Por:</label>
                                <input type="text" name="destitucion_por">
                            </div>
                            <div class="form-group">
                                <label>A Partir de:</label>
                                <input type="date" name="destitucion_a_partir">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>de</label>
                                <input type="date" name="destitucion_de">
                            </div>
                            <div class="form-group">
                                <label>del</label>
                                <input type="date" name="destitucion_del">
                            </div>
                        </div>
                    `;
                    break;
                    
                default:
                    campos = '<p style="text-align: center; color: #666; font-style: italic;">Seleccione un tipo de acción para ver los campos específicos</p>';
            }
            
            detallesDiv.innerHTML = campos;
        });
    });
</script>

<?php include "../../includes/footer.php"; ?>