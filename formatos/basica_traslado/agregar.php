<?php
include '../../includes/db.php';

if ($_POST) {
    // Insertar datos principales en tabla BÁSICA
    $sql_principal = "INSERT INTO formato_traslado_basica (
        tipo_nombramiento, nombre_docente, identidad, colegio_magisterial, 
        colegiacion_numero, nivel_educativo, jornada_trabajo, requisito,
        fecha_inicio, fecha_vencimiento, numero_horas, sustituye_a,
        identidad_sustituido, motivo_vacante, firma_docente, telefono_contacto,
        vb_director_centro, vb_director_distrital, firma_revisa, fecha_entrega
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql_principal);
    $stmt->bind_param(
        "ssssssssssisssssssss",
        $_POST['tipo_nombramiento'],
        $_POST['nombre_docente'],
        $_POST['identidad'],
        $_POST['colegio_magisterial'],
        $_POST['colegiacion_numero'],
        $_POST['nivel_educativo'],
        $_POST['jornada_trabajo'],
        $_POST['requisito'],
        $_POST['fecha_inicio'],
        $_POST['fecha_vencimiento'],
        $_POST['numero_horas'],
        $_POST['sustituye_a'],
        $_POST['identidad_sustituido'],
        $_POST['motivo_vacante'],
        $_POST['firma_docente'],
        $_POST['telefono_contacto'],
        $_POST['vb_director_centro'],
        $_POST['vb_director_distrital'],
        $_POST['firma_revisa'],
        $_POST['fecha_entrega']
    );
    
    if ($stmt->execute()) {
        $traslado_id = $conn->insert_id;
        
        // Insertar centro origen BÁSICA
        insertarCentroOrigenBasica($conn, $traslado_id, $_POST);
        // Insertar estructura presupuestaria origen BÁSICA
        insertarEstructuraPresupuestariaOrigenBasica($conn, $traslado_id, $_POST);
        // Insertar estructura SIARHD origen BÁSICA
        insertarEstructuraSiarhdOrigenBasica($conn, $traslado_id, $_POST);
        // Insertar centro destino BÁSICA
        insertarCentroDestinoBasica($conn, $traslado_id, $_POST);
        // Insertar estructura presupuestaria destino BÁSICA
        insertarEstructuraPresupuestariaDestinoBasica($conn, $traslado_id, $_POST);
        // Insertar estructura SIARHD destino BÁSICA
        insertarEstructuraSiarhdDestinoBasica($conn, $traslado_id, $_POST);
        
        header("Location: listar.php?success=1");
    } else {
        echo "Error: " . $conn->error;
    }
}

function insertarCentroOrigenBasica($conn, $traslado_id, $data) {
    $sql = "INSERT INTO traslado_basica_centro_origen (formato_id, centro_educativo, cargo, lugar, municipio) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $traslado_id, $data['centro_origen'], $data['cargo_origen'], $data['lugar_origen'], $data['municipio_origen']);
    $stmt->execute();
}

function insertarEstructuraPresupuestariaOrigenBasica($conn, $traslado_id, $data) {
    if (isset($data['estructura_inst_origen'])) {
        for ($i = 0; $i < count($data['estructura_inst_origen']); $i++) {
            $sql = "INSERT INTO traslado_basica_presupuesto_origen (formato_id, institucion, programa, subprograma, fondo) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $traslado_id, $data['estructura_inst_origen'][$i], $data['estructura_programa_origen'][$i], $data['estructura_subprograma_origen'][$i], $data['estructura_fondo_origen'][$i]);
            $stmt->execute();
        }
    }
}

function insertarEstructuraSiarhdOrigenBasica($conn, $traslado_id, $data) {
    if (isset($data['siarhd_depto_origen'])) {
        for ($i = 0; $i < count($data['siarhd_depto_origen']); $i++) {
            $sql = "INSERT INTO traslado_basica_siarhd_origen (formato_id, departamento, municipio, centro_educativo, numero_plaza, numero_horas) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssi", $traslado_id, $data['siarhd_depto_origen'][$i], $data['siarhd_muni_origen'][$i], $data['siarhd_centro_origen'][$i], $data['siarhd_num_plaza_origen'][$i], $data['siarhd_num_horas_origen'][$i]);
            $stmt->execute();
        }
    }
}

function insertarCentroDestinoBasica($conn, $traslado_id, $data) {
    $sql = "INSERT INTO traslado_basica_centro_destino (formato_id, centro_educativo, cargo, lugar, municipio) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $traslado_id, $data['centro_destino'], $data['cargo_destino'], $data['lugar_destino'], $data['municipio_destino']);
    $stmt->execute();
}

function insertarEstructuraPresupuestariaDestinoBasica($conn, $traslado_id, $data) {
    if (isset($data['estructura_inst_destino'])) {
        for ($i = 0; $i < count($data['estructura_inst_destino']); $i++) {
            $sql = "INSERT INTO traslado_basica_presupuesto_destino (formato_id, institucion, programa, subprograma, fondo) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $traslado_id, $data['estructura_inst_destino'][$i], $data['estructura_programa_destino'][$i], $data['estructura_subprograma_destino'][$i], $data['estructura_fondo_destino'][$i]);
            $stmt->execute();
        }
    }
}

function insertarEstructuraSiarhdDestinoBasica($conn, $traslado_id, $data) {
    if (isset($data['siarhd_depto_destino'])) {
        for ($i = 0; $i < count($data['siarhd_depto_destino']); $i++) {
            $sql = "INSERT INTO traslado_basica_siarhd_destino (formato_id, departamento, municipio, centro_educativo, numero_plaza, numero_horas) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssi", $traslado_id, $data['siarhd_depto_destino'][$i], $data['siarhd_muni_destino'][$i], $data['siarhd_centro_destino'][$i], $data['siarhd_num_plaza_destino'][$i], $data['siarhd_num_horas_destino'][$i]);
            $stmt->execute();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formato Unificado de Traslado Docente BÁSICA - F1</title>
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
    input[type="text"], input[type="number"], input[type="date"], select, textarea { width:100%; padding:5px; border:1px solid #999; font-size:12px; }
    .checkbox-group { display:flex; align-items:center; margin-bottom:5px; }
    .checkbox-group label { margin-left:5px; font-weight:normal; }
    table { width:100%; border-collapse:collapse; font-size:11px; margin-top:5px; }
    table th, table td { border:1px solid #000; padding:5px; text-align:center; }
    table th { background-color:#f0f0f0; font-weight:bold; }
    .signature-table td { height:60px; vertical-align:top; }
    .btn-submit { background-color:#004080; color:white; border:none; padding:10px 20px; font-size:14px; cursor:pointer; margin-top:20px; display:block; margin-left:auto; margin-right:auto; }
    .btn-submit:hover { background-color:#002952; }
    .add-row-btn { background-color:#007bff; color:white; border:none; padding:5px 10px; font-size:12px; cursor:pointer; margin-top:5px; border-radius:4px; }
    .remove-row-btn { background:none; border:none; cursor:pointer; color:red; }
    .basica-badge { background:#007bff; color:white; padding:2px 8px; border-radius:3px; font-size:10px; margin-left:5px; }
</style>
</head>
<body>

<div class="navigation">
    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-back">🔙 Volver Atrás</a>
    <a href="<?= htmlspecialchars(BASE_URL . '/menu.php', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-menu">🏠 Volver al Menú</a>
</div>

<div class="form-container">
<form id="trasladoForm" method="post" action="agregar.php">

<div class="header">
<h1>HONDURAS</h1>
<h2>SECRETARÍA DE EDUCACIÓN</h2>
<h3>DIRECCIÓN DEPARTAMENTAL DE EDUCACIÓN, YORO</h3>
<h2>FORMATO UNIFICADO DE NOMBRAMIENTOS (F1) <span class="basica-badge">BÁSICA</span></h2>
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
<label>NIVEL EDUCATIVO</label>
<div class="checkbox-group">
<input type="radio" name="nivel_educativo" value="PRE-BASICA" id="prebasica" required>
<label for="prebasica">PRE-BASICA</label>
</div>
<div class="checkbox-group">
<input type="radio" name="nivel_educativo" value="BASICA" id="basica">
<label for="basica">BASICA</label>
</div>
</div>
<div class="form-group">
<label>JORNADA DE TRABAJO</label>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="PARCIAL" id="parcial" required>
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
<input type="radio" name="requisito" value="CONCURSO" id="concurso" required>
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
<input type="number" name="numero_horas" required>
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO ORIGEN -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO ORIGEN (SEGÚN ESTADO DE CUENTA)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_origen" required>
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_origen" required>
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_origen" required>
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_origen" required>
</div>
</div>

<!-- ESTRUCTURA PRESUPUESTARIA ORIGEN -->
<div class="form-row">
<div class="form-group">
<label>ESTRUCTURA PRESUPUESTARIA</label>
<table id="estructuraOrigenTable">
<tr>
<th>INST.</th>
<th>PROGRAMA</th>
<th>SUB-PROGRAMA</th>
<th>FONDO</th>
<th>ACCIÓN</th>
</tr>
<tr>
<td><input type="text" name="estructura_inst_origen[]" value="06" size="3" required></td>
<td><input type="text" name="estructura_programa_origen[]" value="13" size="3" required></td>
<td><input type="text" name="estructura_subprograma_origen[]" value="01" size="3" required></td>
<td><input type="text" name="estructura_fondo_origen[]" value="11" size="3" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
</table>
<button type="button" class="add-row-btn" onclick="addEstructuraOrigenRow()">+ Agregar fila</button>
</div>
</div>

<!-- ESTRUCTURA SIARHD ORIGEN -->
<div class="form-row">
<div class="form-group">
<label>ESTRUCTURA SIARHD</label>
<table id="siarhdOrigenTable">
<tr>
<th>DEPTO.</th>
<th>MUNI.</th>
<th>CENTRO EDUCATIVO</th>
<th>N° DE PLAZA</th>
<th>N° HRS</th>
<th>ACCIÓN</th>
</tr>
<tr>
<td><input type="text" name="siarhd_depto_origen[]" size="3" required></td>
<td><input type="text" name="siarhd_muni_origen[]" size="3" required></td>
<td><input type="text" name="siarhd_centro_origen[]" size="8" required></td>
<td><input type="text" name="siarhd_num_plaza_origen[]" size="5" required></td>
<td><input type="number" name="siarhd_num_horas_origen[]" size="3" value="0" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
</table>
<button type="button" class="add-row-btn" onclick="addSiarhdOrigenRow()">+ Agregar fila</button>
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO DESTINO -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO DESTINO (SEGÚN ESTADO DE CUENTA)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_destino" required>
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_destino" required>
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_destino" required>
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_destino" required>
</div>
</div>

<!-- ESTRUCTURA PRESUPUESTARIA DESTINO -->
<div class="form-row">
<div class="form-group">
<label>ESTRUCTURA PRESUPUESTARIA</label>
<table id="estructuraDestinoTable">
<tr>
<th>INST.</th>
<th>PROGRAMA</th>
<th>SUB-PROGRAMA</th>
<th>FONDO</th>
<th>ACCIÓN</th>
</tr>
<tr>
<td><input type="text" name="estructura_inst_destino[]" value="06" size="3" required></td>
<td><input type="text" name="estructura_programa_destino[]" value="13" size="3" required></td>
<td><input type="text" name="estructura_subprograma_destino[]" value="01" size="3" required></td>
<td><input type="text" name="estructura_fondo_destino[]" value="11" size="3" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
</table>
<button type="button" class="add-row-btn" onclick="addEstructuraDestinoRow()">+ Agregar fila</button>
</div>
</div>

<!-- ESTRUCTURA SIARHD DESTINO -->
<div class="form-row">
<div class="form-group">
<label>ESTRUCTURA SIARHD</label>
<table id="siarhdDestinoTable">
<tr>
<th>DEPTO.</th>
<th>MUNI.</th>
<th>CENTRO EDUCATIVO</th>
<th>N° DE PLAZA</th>
<th>N° HRS</th>
<th>ACCIÓN</th>
</tr>
<tr>
<td><input type="text" name="siarhd_depto_destino[]" size="3" required></td>
<td><input type="text" name="siarhd_muni_destino[]" size="3" required></td>
<td><input type="text" name="siarhd_centro_destino[]" size="8" required></td>
<td><input type="text" name="siarhd_num_plaza_destino[]" size="5" required></td>
<td><input type="number" name="siarhd_num_horas_destino[]" size="3" value="0" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
</table>
<button type="button" class="add-row-btn" onclick="addSiarhdDestinoRow()">+ Agregar fila</button>
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
<textarea name="motivo_vacante" rows="3"></textarea>
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

<button type="submit" class="btn-submit">GUARDAR TRASLADO BÁSICA</button>
</form>
</div>

<script>
function removeRow(button){
    const row = button.closest('tr');
    row.remove();
}

function addEstructuraOrigenRow() {
    const table = document.getElementById('estructuraOrigenTable');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" name="estructura_inst_origen[]" size="3" required></td>
        <td><input type="text" name="estructura_programa_origen[]" size="3" required></td>
        <td><input type="text" name="estructura_subprograma_origen[]" size="3" required></td>
        <td><input type="text" name="estructura_fondo_origen[]" size="3" required></td>
        <td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
    `;
}

function addSiarhdOrigenRow() {
    const table = document.getElementById('siarhdOrigenTable');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" name="siarhd_depto_origen[]" size="3" required></td>
        <td><input type="text" name="siarhd_muni_origen[]" size="3" required></td>
        <td><input type="text" name="siarhd_centro_origen[]" size="8" required></td>
        <td><input type="text" name="siarhd_num_plaza_origen[]" size="5" required></td>
        <td><input type="number" name="siarhd_num_horas_origen[]" size="3" value="0" required></td>
        <td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
    `;
}

function addEstructuraDestinoRow() {
    const table = document.getElementById('estructuraDestinoTable');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" name="estructura_inst_destino[]" size="3" required></td>
        <td><input type="text" name="estructura_programa_destino[]" size="3" required></td>
        <td><input type="text" name="estructura_subprograma_destino[]" size="3" required></td>
        <td><input type="text" name="estructura_fondo_destino[]" size="3" required></td>
        <td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
    `;
}

function addSiarhdDestinoRow() {
    const table = document.getElementById('siarhdDestinoTable');
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" name="siarhd_depto_destino[]" size="3" required></td>
        <td><input type="text" name="siarhd_muni_destino[]" size="3" required></td>
        <td><input type="text" name="siarhd_centro_destino[]" size="8" required></td>
        <td><input type="text" name="siarhd_num_plaza_destino[]" size="5" required></td>
        <td><input type="number" name="siarhd_num_horas_destino[]" size="3" value="0" required></td>
        <td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
    `;
}
</script>

</body>
</html>
