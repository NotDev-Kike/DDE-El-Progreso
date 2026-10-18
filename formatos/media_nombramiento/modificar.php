<?php
include '../../includes/db.php';

$id_formato = $_GET['id'] ?? 0;
$id_formato = intval($id_formato);

if ($id_formato <= 0) {
    die("ID de formato inválido.");
}

// ===================== OBTENER DATOS DEL FORMULARIO PRINCIPAL =====================
$stmt = $conn->prepare("SELECT * FROM formato_nombramiento_media WHERE id = ?");
$stmt->bind_param("i", $id_formato);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();
$stmt->close();

if (!$datos) {
    die("No se encontró el nombramiento.");
}

// ===================== OBTENER CARGA ACADÉMICA =====================
$carga = [];
$stmt2 = $conn->prepare("SELECT * FROM carga_academica_nombramiento_media WHERE id_formato = ?");
$stmt2->bind_param("i", $id_formato);
$stmt2->execute();
$res2 = $stmt2->get_result();
while($row = $res2->fetch_assoc()) $carga[] = $row;
$stmt2->close();

// ===================== OBTENER SIARHD =====================
$siarhd = [];
$stmt3 = $conn->prepare("SELECT * FROM siarhd_estructura_nombramiento_media WHERE id_formato = ?");
$stmt3->bind_param("i", $id_formato);
$stmt3->execute();
$res3 = $stmt3->get_result();
while($row = $res3->fetch_assoc()) $siarhd[] = $row;
$stmt3->close();

// ===================== OBTENER ESTRUCTURA PRESUPUESTARIA =====================
$estructura = [];
$stmt4 = $conn->prepare("SELECT * FROM estructura_presupuestaria_nombramiento_media WHERE id_formato = ?");
$stmt4->bind_param("i", $id_formato);
$stmt4->execute();
$res4 = $stmt4->get_result();
while($row = $res4->fetch_assoc()) $estructura[] = $row;
$stmt4->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modificar Nombramiento - F1</title>
<style>
/* Copiar exactamente los estilos de agregar.php */
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
</style>
</head>
<body>

<div class="navigation">
    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-back">🔙 Volver Atrás</a>
    <a href="<?= htmlspecialchars(BASE_URL . '/menu.php', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-menu">🏠 Volver al Menú</a>
</div>

<div class="form-container">
<form id="nombramientoForm" method="post" action="procesar_modificar.php">
<input type="hidden" name="id_formato" value="<?= $id_formato ?>">

<div class="header">
<h1>HONDURAS</h1>
<h2>SECRETARÍA DE EDUCACIÓN</h2>
<h3>DIRECCIÓN DEPARTAMENTAL DE EDUCACIÓN, YORO</h3>
</div>

<!-- TIPO DE NOMBRAMIENTO -->
<div class="section">
<div class="section-title">TIPO DE NOMBRAMIENTO</div>
<div class="form-row">
<div class="form-group">
<div class="checkbox-group">
<input type="radio" name="tipo_nombramiento" value="INTERINO" id="interino" <?= $datos['tipo_nombramiento']=='INTERINO'?'checked':'' ?> required>
<label for="interino">INTERINO</label>
</div>
<div class="checkbox-group">
<input type="radio" name="tipo_nombramiento" value="PERMANENTE" id="permanente" <?= $datos['tipo_nombramiento']=='PERMANENTE'?'checked':'' ?>>
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
<input type="text" name="nombre_docente" value="<?= htmlspecialchars($datos['nombre_docente']) ?>" required>
</div>
<div class="form-group">
<label>IDENTIDAD</label>
<input type="text" name="identidad" value="<?= htmlspecialchars($datos['identidad']) ?>" required>
</div>
<div class="form-group">
<label>SDGTHD</label>
<input type="text" name="sdgthd" value="<?= htmlspecialchars($datos['sdgthd']) ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>COLEGIO MAGISTERIAL</label>
<input type="text" name="colegio_magisterial" value="<?= htmlspecialchars($datos['colegio_magisterial']) ?>">
</div>
<div class="form-group">
<label>COLEGIACIÓN N°</label>
<input type="text" name="colegiacion_numero" value="<?= htmlspecialchars($datos['colegiacion_numero']) ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>JORNADA DE TRABAJO</label>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="PARCIAL" id="parcial" <?= $datos['jornada_trabajo']=='PARCIAL'?'checked':'' ?>>
<label for="parcial">PARCIAL</label>
</div>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="COMPLETA" id="completa" <?= $datos['jornada_trabajo']=='COMPLETA'?'checked':'' ?>>
<label for="completa">COMPLETA</label>
</div>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="EXCLUSIVA" id="exclusiva" <?= $datos['jornada_trabajo']=='EXCLUSIVA'?'checked':'' ?>>
<label for="exclusiva">EXCLUSIVA</label>
</div>
</div>
<div class="form-group">
<label>REQUISITO</label>
<div class="checkbox-group">
<input type="radio" name="requisito" value="CONCURSO" id="concurso" <?= $datos['requisito']=='CONCURSO'?'checked':'' ?>>
<label for="concurso">CONCURSO</label>
</div>
<div class="checkbox-group">
<input type="radio" name="requisito" value="EXONERADO(A)" id="exonerado" <?= $datos['requisito']=='EXONERADO(A)'?'checked':'' ?>>
<label for="exonerado">EXONERADO(A)</label>
</div>
<div class="checkbox-group">
<input type="radio" name="requisito" value="COMPLETACIÓN" id="completacion" <?= $datos['requisito']=='COMPLETACIÓN'?'checked':'' ?>>
<label for="completacion">COMPLETACIÓN</label>
</div>
</div>
</div>
</div>

<!-- DATOS DEL NOMBRAMIENTO -->
<div class="section">
<div class="section-title">DATOS ESPECÍFICOS DEL NOMBRAMIENTO</div>
<div class="form-row">
<div class="form-group">
<label>FECHA DE INICIO</label>
<input type="date" name="fecha_inicio" value="<?= $datos['fecha_inicio'] ?>" required>
</div>
<div class="form-group">
<label>FECHA DE VENCIMIENTO</label>
<input type="date" name="fecha_vencimiento" value="<?= $datos['fecha_vencimiento'] ?>">
</div>
<div class="form-group">
<label>No. DE HORAS</label>
<input type="number" name="numero_horas" id="numero_horas" readonly value="<?= $datos['numero_horas'] ?>">
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO CUENTA -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO (SEGÚN ESTADO DE CUENTA)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_cuenta" value="<?= htmlspecialchars($datos['centro_cuenta']) ?>">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_cuenta" value="<?= htmlspecialchars($datos['cargo_cuenta']) ?>">
</div>
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_cuenta" value="<?= htmlspecialchars($datos['lugar_cuenta']) ?>">
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_cuenta" value="<?= htmlspecialchars($datos['municipio_cuenta']) ?>">
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO REUBICACIÓN -->
<div class="section">
<div class="section-title">CENTRO EDUCATIVO (REUBICACIÓN)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_reubicacion" value="<?= htmlspecialchars($datos['centro_reubicacion']) ?>">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_reubicacion" value="<?= htmlspecialchars($datos['cargo_reubicacion']) ?>">
</div>
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_reubicacion" value="<?= htmlspecialchars($datos['lugar_reubicacion']) ?>">
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_reubicacion" value="<?= htmlspecialchars($datos['municipio_reubicacion']) ?>">
</div>
</div>
</div>

<!-- JUSTIFICACIÓN -->
<div class="section">
<div class="section-title">JUSTIFICACIÓN DEL NOMBRAMIENTO</div>
<div class="form-row">
<div class="form-group">
<label>SUSTITUYE A:</label>
<input type="text" name="sustituye_a" value="<?= htmlspecialchars($datos['sustituye_a']) ?>">
</div>
<div class="form-group">
<label>IDENTIDAD N°:</label>
<input type="text" name="identidad_sustituido" value="<?= htmlspecialchars($datos['identidad_sustituido']) ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>¿POR QUÉ QUEDO VACANTE EL CARGO?</label>
<input type="text" name="motivo_vacante" value="<?= htmlspecialchars($datos['motivo_vacante']) ?>">
</div>
</div>
</div>

<!-- ESTRUCTURA PRESUPUESTARIA -->
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
<th>ACCIONES</th>
</tr>
<?php
if(count($estructura) > 0){
    foreach($estructura as $row){
        echo "<tr>
        <td><input type='text' name='estructura_inst[]' size='3' value='".htmlspecialchars($row['estructura_inst'])."'></td>
        <td><input type='text' name='estructura_programa[]' size='3' value='".htmlspecialchars($row['estructura_programa'])."'></td>
        <td><input type='text' name='estructura_subprograma[]' size='3' value='".htmlspecialchars($row['estructura_subprograma'])."'></td>
        <td><input type='text' name='estructura_fondo[]' size='3' value='".htmlspecialchars($row['estructura_fondo'])."'></td>
        <td><button type='button' class='remove-row-btn' onclick='removeRow(this)'>❌</button></td>
        </tr>";
    }
}else{
    echo "<tr>
    <td><input type='text' name='estructura_inst[]' size='3'></td>
    <td><input type='text' name='estructura_programa[]' size='3'></td>
    <td><input type='text' name='estructura_subprograma[]' size='3'></td>
    <td><input type='text' name='estructura_fondo[]' size='3'></td>
    <td><button type='button' class='remove-row-btn' onclick='removeRow(this)'>❌</button></td>
    </tr>";
}
?>
</table>
<button type="button" class="add-row-btn" onclick="addEstructuraRow()">+ Agregar fila</button>
</div>

<div class="form-group">
<label>ESTRUCTURA SIARHD</label>
<table id="siarhdTable">
<tr>
<th>DEPTO.</th>
<th>MUNI.</th>
<th>CENTRO EDUCATIVO</th>
<th>N° DE PLAZA</th>
<th>N° HRS</th>
<th>ACCIONES</th>
</tr>
<?php
if(count($siarhd) > 0){
    foreach($siarhd as $row){
        echo "<tr>
        <td><input type='text' name='siarhd_depto[]' size='3' value='".htmlspecialchars($row['siarhd_depto'])."'></td>
        <td><input type='text' name='siarhd_muni[]' size='3' value='".htmlspecialchars($row['siarhd_muni'])."'></td>
        <td><input type='text' name='siarhd_centro[]' size='8' value='".htmlspecialchars($row['siarhd_centro'])."'></td>
        <td><input type='text' name='siarhd_num_plaza[]' size='5' value='".htmlspecialchars($row['siarhd_num_plaza'])."'></td>
        <td><input type='number' name='siarhd_num_horas[]' size='3' value='".htmlspecialchars($row['siarhd_num_horas'])."'></td>
        <td><button type='button' class='remove-row-btn' onclick='removeRow(this)'>❌</button></td>
        </tr>";
    }
}else{
    echo "<tr>
    <td><input type='text' name='siarhd_depto[]' size='3'></td>
    <td><input type='text' name='siarhd_muni[]' size='3'></td>
    <td><input type='text' name='siarhd_centro[]' size='8'></td>
    <td><input type='text' name='siarhd_num_plaza[]' size='5'></td>
    <td><input type='number' name='siarhd_num_horas[]' size='3' value='0'></td>
    <td><button type='button' class='remove-row-btn' onclick='removeRow(this)'>❌</button></td>
    </tr>";
}
?>
</table>
<button type="button" class="add-row-btn" onclick="addSiarhdRow()">+ Agregar fila</button>
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
<th>ACCIONES</th>
</tr>
</thead>
<tbody id="cargaAcademicaBody"></tbody>
</table>
<div class="total-hours">
TOTAL HORAS: <span id="totalHorasSpan">0</span>
</div>
<button type="button" class="add-row-btn" onclick="addCargaRow()">+ Agregar fila</button>
</div>

<!-- FIRMAS -->
<div class="section">
<div class="section-title">FIRMAS QUE VALIDAN ESTE DOCUMENTO</div>
<table class="signature-table">
<tr>
<td width="50%">
<label>FIRMA DEL DOCENTE</label>
<input type="text" name="firma_docente" value="<?= htmlspecialchars($datos['firma_docente']) ?>">
</td>
<td width="50%">
<label>NÚMERO DE TELÉFONO</label>
<input type="text" name="telefono_contacto" value="<?= htmlspecialchars($datos['telefono_contacto']) ?>">
</td>
</tr>
<tr>
<td>
<label>VºBº DIRECTOR(A) DEL CENTRO EDUCATIVO</label>
<input type="text" name="vb_director_centro" value="<?= htmlspecialchars($datos['vb_director_centro']) ?>">
</td>
<td>
<label>VºBº DIRECTOR(A) DISTRITAL O MUNICIPAL</label>
<input type="text" name="vb_director_distrital" value="<?= htmlspecialchars($datos['vb_director_distrital']) ?>">
</td>
</tr>
<tr>
<td>
<label>(ACCIONES) FIRMA DE QUIEN REVISA</label>
<input type="text" name="firma_revisa" value="<?= htmlspecialchars($datos['firma_revisa']) ?>">
</td>
<td>
<label>FECHA DE ENTREGA</label>
<input type="date" name="fecha_entrega" value="<?= $datos['fecha_entrega'] ?>">
</td>
</tr>
</table>
</div>

<button type="submit" class="btn-submit">GUARDAR CAMBIOS</button>
</form>
</div>

<script>
// Copiar exactamente los scripts de agregar.php para Carga Académica, Estructura y SIARHD

const cargaBody = document.getElementById('cargaAcademicaBody');
const totalHorasSpan = document.getElementById('totalHorasSpan');
const numeroHorasInput = document.getElementById('numero_horas');
let cargaCount = 0;

function addCargaRow(asignatura='', horas=0, modalidad='', cursos='', grupos='', jornada=''){
    cargaCount++;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${cargaCount}</td>
        <td><input type="text" name="asignatura[]" value="${asignatura}" required></td>
        <td><input type="number" class="horas-row" name="horas[]" min="0" value="${horas}"></td>
        <td><input type="text" name="modalidad[]" value="${modalidad}"></td>
        <td><input type="text" name="cursos[]" value="${cursos}"></td>
        <td><input type="text" name="grupos[]" value="${grupos}"></td>
        <td><input type="text" name="jornada[]" value="${jornada}"></td>
        <td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
    `;
    cargaBody.appendChild(row);
    recalcTotal();
}

function removeRow(button){
    const row = button.closest('tr');
    row.remove();
    recalcTotal();
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

// Cargar filas de la base de datos
<?php
foreach($carga as $row){
    $asig = addslashes($row['asignatura']);
    $hrs = intval($row['horas']);
    $mod = addslashes($row['modalidad']);
    $cur = addslashes($row['cursos']);
    $gru = addslashes($row['grupos']);
    $jor = addslashes($row['jornada_academica']);
    echo "addCargaRow('$asig', $hrs, '$mod', '$cur', '$gru', '$jor');\n";
}
?>

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
