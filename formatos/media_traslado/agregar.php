<?php
include '../../includes/db.php';

if ($_POST) {
    // Insertar datos principales en tabla MEDIA
    $sql_principal = "INSERT INTO formato_traslado_media (
        tipo_nombramiento, nombre_docente, identidad, colegio_magisterial, 
        sdgthd, colegiacion_numero, jornada_trabajo, requisito, 
        fecha_inicio, fecha_vencimiento, numero_horas
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql_principal);
    $stmt->bind_param(
        "ssssssssssi",
        $_POST['tipo_nombramiento'],
        $_POST['nombre_docente'],
        $_POST['identidad'],
        $_POST['colegio_magisterial'],
        $_POST['sdgthd'],
        $_POST['colegiacion_numero'],
        $_POST['jornada_trabajo'],
        $_POST['requisito'],
        $_POST['fecha_inicio'],
        $_POST['fecha_vencimiento'],
        $_POST['numero_horas']
    );
    
    if ($stmt->execute()) {
        $traslado_id = $conn->insert_id;
        
        // Insertar centros educativos MEDIA
        insertarCentrosMedia($conn, $traslado_id, $_POST);
        // Insertar estructura presupuestaria MEDIA
        insertarEstructuraPresupuestariaMedia($conn, $traslado_id, $_POST);
        // Insertar estructura SIARHD MEDIA
        insertarEstructuraSiarhdMedia($conn, $traslado_id, $_POST);
        // Insertar justificación MEDIA
        insertarJustificacionMedia($conn, $traslado_id, $_POST);
        // Insertar carga académica MEDIA
        insertarCargaAcademicaMedia($conn, $traslado_id, $_POST);
        // Insertar firmas MEDIA
        insertarFirmasMedia($conn, $traslado_id, $_POST);
        
        header("Location: listar.php?success=1");
    } else {
        echo "Error: " . $conn->error;
    }
}

function insertarCentrosMedia($conn, $traslado_id, $data) {
    // Centro origen MEDIA
    $sql = "INSERT INTO centros_educativos_media (traslado_id, tipo, centro_educativo, cargo, lugar, municipio, depto) 
            VALUES (?, 'ORIGEN', ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $traslado_id, $data['centro_origen'], $data['cargo_origen'], $data['lugar_origen'], $data['municipio_origen'], $data['depto_origen']);
    $stmt->execute();
    
    // Centro reubicación MEDIA
    $sql = "INSERT INTO centros_educativos_media (traslado_id, tipo, centro_educativo, cargo, lugar, municipio, depto) 
            VALUES (?, 'REUBICACION', ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $traslado_id, $data['centro_reubicacion'], $data['cargo_reubicacion'], $data['lugar_reubicacion'], $data['municipio_reubicacion'], $data['depto_reubicacion']);
    $stmt->execute();
    
    // Centro destino MEDIA
    $sql = "INSERT INTO centros_educativos_media (traslado_id, tipo, centro_educativo, cargo, lugar, municipio, depto) 
            VALUES (?, 'DESTINO', ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $traslado_id, $data['centro_destino'], $data['cargo_destino'], $data['lugar_destino'], $data['municipio_destino'], $data['depto_destino']);
    $stmt->execute();
}

function insertarEstructuraPresupuestariaMedia($conn, $traslado_id, $data) {
    if (isset($data['estructura_inst'])) {
        for ($i = 0; $i < count($data['estructura_inst']); $i++) {
            $sql = "INSERT INTO estructura_presupuestaria_media (traslado_id, estructura_inst, estructura_programa, estructura_subprograma, estructura_fondo) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $traslado_id, $data['estructura_inst'][$i], $data['estructura_programa'][$i], $data['estructura_subprograma'][$i], $data['estructura_fondo'][$i]);
            $stmt->execute();
        }
    }
}

function insertarEstructuraSiarhdMedia($conn, $traslado_id, $data) {
    if (isset($data['siarhd_depto'])) {
        for ($i = 0; $i < count($data['siarhd_depto']); $i++) {
            $sql = "INSERT INTO estructura_siarhd_media (traslado_id, depto, muni, centro_educativo, num_plaza, num_horas) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssii", $traslado_id, $data['siarhd_depto'][$i], $data['siarhd_muni'][$i], $data['siarhd_centro'][$i], $data['siarhd_num_plaza'][$i], $data['siarhd_num_horas'][$i]);
            $stmt->execute();
        }
    }
}

function insertarJustificacionMedia($conn, $traslado_id, $data) {
    $sql = "INSERT INTO justificacion_media (traslado_id, sustituye_a, identidad_sustituido, motivo_vacante, centro_educativo, num_plaza) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $traslado_id, $data['sustituye_a'], $data['identidad_sustituido'], $data['motivo_vacante'], $data['centro_justificacion'], $data['num_plaza_justificacion']);
    $stmt->execute();
}

function insertarCargaAcademicaMedia($conn, $traslado_id, $data) {
    if (isset($data['asignatura'])) {
        for ($i = 0; $i < count($data['asignatura']); $i++) {
            $sql = "INSERT INTO carga_academica_media (traslado_id, asignatura, num_horas, modalidad, cursos, grupos, jornada_academica, programa, subprograma, fondo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssssss", $traslado_id, $data['asignatura'][$i], $data['horas'][$i], $data['modalidad'][$i], $data['cursos'][$i], $data['grupos'][$i], $data['jornada'][$i], $data['programa'][$i], $data['subprograma'][$i], $data['fondo'][$i]);
            $stmt->execute();
        }
    }
}

function insertarFirmasMedia($conn, $traslado_id, $data) {
    $sql = "INSERT INTO firmas_validacion_media (traslado_id, firma_docente, telefono_contacto, vb_director_centro, vb_director_distrital, firma_revisa, fecha_entrega) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $traslado_id, $data['firma_docente'], $data['telefono_contacto'], $data['vb_director_centro'], $data['vb_director_distrital'], $data['firma_revisa'], $data['fecha_entrega']);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formato Unificado de Traslado Docente MEDIA - F1</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family: Arial, sans-serif; }
    body { background-color:#f5f5f5; padding:20px; color:#333; }
    .navigation { text-align:center; margin-bottom:20px; }
    .btn { display:inline-block; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:bold; margin:0 5px; border:none; cursor:pointer; }
    .btn-back { background-color:#555; color:white; }
    .btn-menu { background-color:#0066cc; color:white; }
    .form-container { max-width:900px; margin:0 auto; background:white; box-shadow:0 0 10px rgba(0,0,0,0.1); padding:20px; }
    .header { text-align:center; margin-bottom:20px; border-bottom:2px solid #000; padding-bottom:10px; }
    .header h1{ font-size:16px; font-weight:bold; margin-bottom:5px; }
    .header h2{ font-size:14px; margin-bottom:5px; }
    .header h3{ font-size:12px; font-weight:normal; }
    .section { margin-bottom:15px; border:1px solid #000; padding:10px; }
    .section-title { font-weight:bold; text-align:center; margin-bottom:10px; font-size:14px; background-color:#f0f0f0; padding:5px; }
    .form-row { display:flex; margin-bottom:8px; }
    .form-group { flex:1; padding:0 5px; }
    .form-group label { display:block; font-size:12px; margin-bottom:3px; font-weight:bold; }
    input[type="text"], input[type="number"], input[type="date"], select { width:100%; padding:5px; border:1px solid #999; font-size:12px; }
    .checkbox-group { display:flex; align-items:center; margin-bottom:5px; }
    .checkbox-group label { margin-left:5px; font-weight:normal; }
    table { width:100%; border-collapse:collapse; font-size:11px; margin-top:5px; }
    table th, table td { border:1px solid #000; padding:5px; text-align:center; }
    table th { background-color:#f0f0f0; font-weight:bold; }
    .total-hours { text-align:right; margin-top:10px; font-weight:bold; }
    .signature-table td { height:60px; vertical-align:top; }
    .btn-submit { background-color:#004080; color:white; border:none; padding:10px 20px; font-size:14px; cursor:pointer; margin-top:20px; display:block; margin-left:auto; margin-right:auto; }
    .btn-submit:hover { background-color:#002952; }
    .add-row-btn { background-color:#007bff; color:white; border:none; padding:5px 10px; font-size:12px; cursor:pointer; margin-top:5px; border-radius:4px; }
    .remove-row-btn { background:none; border:none; cursor:pointer; color:red; }
    .media-badge { background:#e74c3c; color:white; padding:2px 8px; border-radius:3px; font-size:10px; margin-left:5px; }
</style>
</head>
<body>

<div class="navigation">
    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-back">🔙 Volver Atrás</a>
    <a href="../../menu.php" class="btn btn-menu">🏠 Volver al Menú</a>
</div>

<div class="form-container">
<form id="trasladoForm" method="post" action="agregar.php">

<div class="header">
<h1>HONDURAS</h1>
<h2>SECRETARÍA DE EDUCACIÓN</h2>
<h3>DIRECCIÓN DEPARTAMENTAL DE EDUCACIÓN, YORO</h3>
<h2>FORMATO UNIFICADO DE TRASLADO DOCENTE <span class="media-badge">MEDIA</span> - F1</h2>
</div>

<!-- TIPO DE NOMBRAMIENTO -->
<div class="section">
<div class="section-title">TIPO DE NOMBRAMIENTO</div>
<div class="form-row">
<div class="form-group">
<div class="checkbox-group">
<input type="radio" name="tipo_nombramiento" value="INTERINO" id="interino" required>
<label for="interino">INTERINO</label>
</div>
<div class="checkbox-group">
<input type="radio" name="tipo_nombramiento" value="PERMANENTE" id="permanente">
<label for="permanente">PERMANENTE</label>
</div>
</div>
</div>
</div>

<!-- INFORMACIÓN PERSONAL DEL DOCENTE -->
<div class="section">
<div class="section-title">INFORMACIÓN PERSONAL DEL DOCENTE</div>
<div class="form-row">
<div class="form-group">
<label>NOMBRE DEL DOCENTE</label>
<input type="text" name="nombre_docente" required>
</div>
<div class="form-group">
<label>IDENTIDAD</label>
<input type="text" name="identidad" required>
</div>
<div class="form-group">
<label>SDGTHD</label>
<input type="text" name="sdgthd">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>COLEGIO MAGISTERIAL</label>
<input type="text" name="colegio_magisterial">
</div>
<div class="form-group">
<label>COLEGIACIÓN N°</label>
<input type="text" name="colegiacion_numero">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>JORNADA DE TRABAJO</label>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="PARCIAL" id="parcial">
<label for="parcial">PARCIAL</label>
</div>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="COMPLETA" id="completa">
<label for="completa">COMPLETA</label>
</div>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="EXCLUSIVA" id="exclusiva">
<label for="exclusiva">EXCLUSIVA</label>
</div>
</div>
<div class="form-group">
<label>REQUISITO</label>
<div class="checkbox-group">
<input type="radio" name="requisito" value="CONCURSO" id="concurso">
<label for="concurso">CONCURSO</label>
</div>
<div class="checkbox-group">
<input type="radio" name="requisito" value="EXONERADO(A)" id="exonerado">
<label for="exonerado">EXONERADO(A)</label>
</div>
<div class="checkbox-group">
<input type="radio" name="requisito" value="COMPLETACIÓN" id="completacion">
<label for="completacion">COMPLETACIÓN</label>
</div>
</div>
</div>
</div>

<!-- DATOS ESPECÍFICOS DEL NOMBRAMIENTO -->
<div class="section">
<div class="section-title">DATOS ESPECÍFICOS DEL NOMBRAMIENTO</div>
<div class="form-row">
<div class="form-group">
<label>FECHA DE INICIO</label>
<input type="date" name="fecha_inicio" required>
</div>
<div class="form-group">
<label>FECHA DE VENCIMIENTO</label>
<input type="date" name="fecha_vencimiento">
</div>
<div class="form-group">
<label>No. DE HORAS</label>
<input type="number" name="numero_horas" id="numero_horas" readonly>
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO ORIGEN -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO ORIGEN (SEGÚN ESTADO DE CUENTA)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_origen">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_origen">
</div>
<div class="form-group">
<label>DEPTO.</label>
<input type="text" name="depto_origen">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_origen">
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_origen">
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO REUBICACIÓN -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO (DONDE LABORA SEGÚN REUBICACIÓN, SI FUESE EL CASO)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_reubicacion">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_reubicacion">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_reubicacion">
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_reubicacion">
</div>
</div>
</div>

<!-- PLAZA PRESUPUESTARIA -->
<div class="section">
<div class="section-title">PLAZA PRESUPUESTARIA</div>
<div class="form-row">
<div class="form-group">
<label>ESTRUCTURA PRESUPUESTARIA</label>
<table id="estructuraTable">
<tr>
<th>INST.</th>
<th>PROGRAMA</th>
<th>SUB-PROGRAMA</th>
<th>FONDO</th>
<th>ACCIÓN</th>
</tr>
<tr>
<td><input type="text" name="estructura_inst[]" value="06" size="3" required></td>
<td><input type="text" name="estructura_programa[]" value="13" size="3" required></td>
<td><input type="text" name="estructura_subprograma[]" value="01" size="3" required></td>
<td><input type="text" name="estructura_fondo[]" value="11" size="3" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
</table>
<button type="button" class="add-row-btn" onclick="addEstructuraRow()">+ Agregar fila</button>
</div>
</div>

<div class="form-row">
<div class="form-group">
<label>ESTRUCTURA SIARHD</label>
<table id="siarhdTable">
<tr>
<th>DEPTO.</th>
<th>MUNI.</th>
<th>CENTRO EDUCATIVO</th>
<th>N° DE PLAZA</th>
<th>N° HRS</th>
<th>ACCIÓN</th>
</tr>
<tr>
<td><input type="text" name="siarhd_depto[]" size="3" required></td>
<td><input type="text" name="siarhd_muni[]" size="3" required></td>
<td><input type="text" name="siarhd_centro[]" size="8" required></td>
<td><input type="text" name="siarhd_num_plaza[]" size="5" required></td>
<td><input type="number" name="siarhd_num_horas[]" size="3" value="0" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
</table>
<button type="button" class="add-row-btn" onclick="addSiarhdRow()">+ Agregar fila</button>
</div>
</div>
</div>

<!-- JUSTIFICACIÓN -->
<div class="section">
<div class="section-title">JUSTIFICACIÓN DEL NOMBRAMIENTO</div>
<div class="form-row">
<div class="form-group">
<label>SUSTITUYE A:</label>
<input type="text" name="sustituye_a">
</div>
<div class="form-group">
<label>IDENTIDAD N°:</label>
<input type="text" name="identidad_sustituido">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>¿POR QUÉ QUEDO VACANTE EL CARGO?</label>
<input type="text" name="motivo_vacante">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_justificacion">
</div>
<div class="form-group">
<label>N° DE PLAZA</label>
<input type="text" name="num_plaza_justificacion">
</div>
</div>
</div>

<!-- CARGA ACADÉMICA -->
<div class="section">
<div class="section-title">CARGA ACADÉMICA DEL DOCENTE</div>
<table id="cargaAcademica">
<thead>
<tr>
<th>N°</th>
<th>ASIGNATURA</th>
<th>Nº HRS</th>
<th>MODALIDAD</th>
<th>CURSO(S)</th>
<th>GRUPO(S)</th>
<th>JORNADA</th>
<th>PROGRAMA</th>
<th>SUB-PROGRAMA</th>
<th>FONDO</th>
<th>ACCIÓN</th>
</tr>
</thead>
<tbody id="cargaAcademicaBody"></tbody>
</table>
<div class="total-hours">
TOTAL HORAS: <span id="totalHorasSpan">0</span>
</div>
<button type="button" class="add-row-btn" onclick="addCargaRow()">+ Agregar fila</button>
</div>

<!-- CENTRO EDUCATIVO DESTINO -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO DESTINO (SEGÚN ESTADO DE CUENTA)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_destino">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_destino">
</div>
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_destino">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_destino">
</div>
<div class="form-group">
<label>DEPTO.</label>
<input type="text" name="depto_destino">
</div>
</div>
</div>

<!-- FIRMAS -->
<div class="section">
<div class="section-title">FIRMAS QUE VALIDAN ESTE DOCUMENTO</div>
<table class="signature-table">
<tr>
<td width="50%">
<label>FIRMA DEL DOCENTE</label>
<input type="text" name="firma_docente">
</td>
<td width="50%">
<label>NÚMERO DE TELÉFONO</label>
<input type="text" name="telefono_contacto">
</td>
</tr>
<tr>
<td>
<label>VºBº DIRECTOR(A) DEL CENTRO EDUCATIVO</label>
<input type="text" name="vb_director_centro">
</td>
<td>
<label>VºBº DIRECTOR(A) DISTRITAL O MUNICIPAL</label>
<input type="text" name="vb_director_distrital">
</td>
</tr>
<tr>
<td>
<label>(ACCIONES) FIRMA DE QUIEN REVISA</label>
<input type="text" name="firma_revisa">
</td>
<td>
<label>FECHA DE ENTREGA</label>
<input type="date" name="fecha_entrega">
</td>
</tr>
</table>
</div>

<button type="submit" class="btn-submit">GUARDAR TRASLADO MEDIA</button>
</form>
</div>

<script>
// CARGA ACADÉMICA
const cargaBody = document.getElementById('cargaAcademicaBody');
const totalHorasSpan = document.getElementById('totalHorasSpan');
const numeroHorasInput = document.getElementById('numero_horas');
let cargaCount = 0;

function addCargaRow() {
    cargaCount++;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${cargaCount}</td>
        <td><input type="text" name="asignatura[]" required></td>
        <td><input type="number" class="horas-row" name="horas[]" min="0" value="0" required></td>
        <td><input type="text" name="modalidad[]"></td>
        <td><input type="text" name="cursos[]"></td>
        <td><input type="text" name="grupos[]"></td>
        <td><input type="text" name="jornada[]"></td>
        <td><input type="text" name="programa[]"></td>
        <td><input type="text" name="subprograma[]"></td>
        <td><input type="text" name="fondo[]"></td>
        <td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
    `;
    cargaBody.appendChild(row);
    recalcTotal();
}

function removeRow(button){
    const row = button.closest('tr');
    row.remove();
    recalcTotal();
    // Recalcular números de fila en carga académica
    const rows = cargaBody.querySelectorAll('tr');
    cargaCount = 0;
    rows.forEach((row, index) => {
        cargaCount++;
        row.cells[0].textContent = cargaCount;
    });
}

function recalcTotal() {
    const horasInputs = document.querySelectorAll('.horas-row');
    let total = 0;
    horasInputs.forEach(input => total += parseInt(input.value)||0);
    totalHorasSpan.textContent = total;
    numeroHorasInput.value = total;
}

document.addEventListener('input', function(e){
    if(e.target.classList.contains('horas-row')) recalcTotal();
});

// Inicializar 1 fila
addCargaRow();

function addEstructuraRow() {
    const table = document.getElementById('estructuraTable');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" name="estructura_inst[]" size="3" required></td>
        <td><input type="text" name="estructura_programa[]" size="3" required></td>
        <td><input type="text" name="estructura_subprograma[]" size="3" required></td>
        <td><input type="text" name="estructura_fondo[]" size="3" required></td>
        <td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
    `;
}

function addSiarhdRow() {
    const table = document.getElementById('siarhdTable');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" name="siarhd_depto[]" size="3" required></td>
        <td><input type="text" name="siarhd_muni[]" size="3" required></td>
        <td><input type="text" name="siarhd_centro[]" size="8" required></td>
        <td><input type="text" name="siarhd_num_plaza[]" size="5" required></td>
        <td><input type="number" name="siarhd_num_horas[]" size="3" value="0" required></td>
        <td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
    `;
}
</script>

</body>
</html>