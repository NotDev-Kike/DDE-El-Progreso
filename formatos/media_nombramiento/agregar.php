<!DOCTYPE html>
<?php
require_once '../../includes/db.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}
?>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formato Unificado de Nombramiento Docente - F1</title>
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
</style>
</head>
<body>

<div class="navigation">
    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-back">🔙 Volver Atrás</a>
    <a href="<?= htmlspecialchars(BASE_URL . '/menu.php', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-menu">🏠 Volver al Menú</a>
</div>

<div class="form-container">
<form id="nombramientoForm" method="post" action="procesar.php">

<div class="header">
<h1>HONDURAS</h1>
<h2>SECRETARÍA DE EDUCACIÓN</h2>
<h3>DIRECCIÓN DEPARTAMENTAL DE EDUCACIÓN, YORO</h3>
</div>

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

<!-- DATOS DEL NOMBRAMIENTO -->
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

<!-- CENTRO EDUCATIVO CUENTA -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO (SEGÚN ESTADO DE CUENTA)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_cuenta">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_cuenta">
</div>
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_cuenta">
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_cuenta">
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO REUBICACIÓN -->
<div class="section">
<div class="section-title">CENTRO EDUCATIVO (REUBICACIÓN)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_reubicacion">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_reubicacion">
</div>
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
</tr>
<tr>
<td><input type="text" name="estructura_inst[]" value="06" size="3"></td>
<td><input type="text" name="estructura_programa[]" value="13" size="3"></td>
<td><input type="text" name="estructura_subprograma[]" value="01" size="3"></td>
<td><input type="text" name="estructura_fondo[]" value="11" size="3"></td>
</tr>
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
</tr>
<tr>
<td><input type="text" name="siarhd_depto[]" size="3"></td>
<td><input type="text" name="siarhd_muni[]" size="3"></td>
<td><input type="text" name="siarhd_centro[]" size="8"></td>
<td><input type="text" name="siarhd_num_plaza[]" size="5"></td>
<td><input type="number" name="siarhd_num_horas[]" size="3" value="0"></td>
</tr>
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

<button type="submit" class="btn-submit">GUARDAR NOMBRAMIENTO</button>
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
        <td><input type="number" class="horas-row" name="horas[]" min="0" value="0"></td>
        <td><input type="text" name="modalidad[]"></td>
        <td><input type="text" name="cursos[]"></td>
        <td><input type="text" name="grupos[]"></td>
        <td><input type="text" name="jornada[]"></td>
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

// Inicializar 12 filas
for(let i=1;i<=12;i++){cargaCount=i; addCargaRow();}

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
