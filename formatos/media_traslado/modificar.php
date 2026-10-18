<?php
include '../../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: listar.php");
    exit;
}

// Obtener datos principales
$sql_principal = "SELECT * FROM formato_traslado_media WHERE id = ?";
$stmt = $conn->prepare($sql_principal);
$stmt->bind_param("i", $id);
$stmt->execute();
$result_principal = $stmt->get_result();
$datos_principal = $result_principal->fetch_assoc();

if (!$datos_principal) {
    header("Location: listar.php");
    exit;
}

// Obtener centros educativos
$centros = [];
$sql_centros = "SELECT * FROM centros_educativos_media WHERE traslado_id = ?";
$stmt = $conn->prepare($sql_centros);
$stmt->bind_param("i", $id);
$stmt->execute();
$result_centros = $stmt->get_result();
while ($row = $result_centros->fetch_assoc()) {
    $centros[$row['tipo']] = $row;
}

// Obtener estructura presupuestaria
$estructura_presupuestaria = [];
$sql_estructura = "SELECT * FROM estructura_presupuestaria_media WHERE traslado_id = ?";
$stmt = $conn->prepare($sql_estructura);
$stmt->bind_param("i", $id);
$stmt->execute();
$result_estructura = $stmt->get_result();
while ($row = $result_estructura->fetch_assoc()) {
    $estructura_presupuestaria[] = $row;
}

// Obtener estructura SIARHD
$estructura_siarhd = [];
$sql_siarhd = "SELECT * FROM estructura_siarhd_media WHERE traslado_id = ?";
$stmt = $conn->prepare($sql_siarhd);
$stmt->bind_param("i", $id);
$stmt->execute();
$result_siarhd = $stmt->get_result();
while ($row = $result_siarhd->fetch_assoc()) {
    $estructura_siarhd[] = $row;
}

// Obtener justificación
$sql_justificacion = "SELECT * FROM justificacion_media WHERE traslado_id = ?";
$stmt = $conn->prepare($sql_justificacion);
$stmt->bind_param("i", $id);
$stmt->execute();
$result_justificacion = $stmt->get_result();
$datos_justificacion = $result_justificacion->fetch_assoc();

// Obtener carga académica
$carga_academica = [];
$sql_carga = "SELECT * FROM carga_academica_media WHERE traslado_id = ?";
$stmt = $conn->prepare($sql_carga);
$stmt->bind_param("i", $id);
$stmt->execute();
$result_carga = $stmt->get_result();
while ($row = $result_carga->fetch_assoc()) {
    $carga_academica[] = $row;
}

// Obtener firmas
$sql_firmas = "SELECT * FROM firmas_validacion_media WHERE traslado_id = ?";
$stmt = $conn->prepare($sql_firmas);
$stmt->bind_param("i", $id);
$stmt->execute();
$result_firmas = $stmt->get_result();
$datos_firmas = $result_firmas->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modificar Traslado Docente MEDIA - F1</title>
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
    <a href="<?= htmlspecialchars(BASE_URL . '/menu.php', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-menu">🏠 Volver al Menú</a>
    <a href="listar.php" class="btn" style="background:#27ae60; color:white;">📋 Ver Lista</a>
</div>

<div class="form-container">
<form id="trasladoForm" method="post" action="procesar.php">

<input type="hidden" name="id" value="<?= $id ?>">

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
<input type="radio" name="tipo_nombramiento" value="INTERINO" id="interino" <?= ($datos_principal['tipo_nombramiento'] == 'INTERINO') ? 'checked' : '' ?> required>
<label for="interino">INTERINO</label>
</div>
<div class="checkbox-group">
<input type="radio" name="tipo_nombramiento" value="PERMANENTE" id="permanente" <?= ($datos_principal['tipo_nombramiento'] == 'PERMANENTE') ? 'checked' : '' ?>>
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
<input type="text" name="nombre_docente" value="<?= htmlspecialchars($datos_principal['nombre_docente']) ?>" required>
</div>
<div class="form-group">
<label>IDENTIDAD</label>
<input type="text" name="identidad" value="<?= htmlspecialchars($datos_principal['identidad']) ?>" required>
</div>
<div class="form-group">
<label>SDGTHD</label>
<input type="text" name="sdgthd" value="<?= htmlspecialchars($datos_principal['sdgthd']) ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>COLEGIO MAGISTERIAL</label>
<input type="text" name="colegio_magisterial" value="<?= htmlspecialchars($datos_principal['colegio_magisterial']) ?>">
</div>
<div class="form-group">
<label>COLEGIACIÓN N°</label>
<input type="text" name="colegiacion_numero" value="<?= htmlspecialchars($datos_principal['colegiacion_numero']) ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>JORNADA DE TRABAJO</label>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="PARCIAL" id="parcial" <?= ($datos_principal['jornada_trabajo'] == 'PARCIAL') ? 'checked' : '' ?>>
<label for="parcial">PARCIAL</label>
</div>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="COMPLETA" id="completa" <?= ($datos_principal['jornada_trabajo'] == 'COMPLETA') ? 'checked' : '' ?>>
<label for="completa">COMPLETA</label>
</div>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="EXCLUSIVA" id="exclusiva" <?= ($datos_principal['jornada_trabajo'] == 'EXCLUSIVA') ? 'checked' : '' ?>>
<label for="exclusiva">EXCLUSIVA</label>
</div>
</div>
<div class="form-group">
<label>REQUISITO</label>
<div class="checkbox-group">
<input type="radio" name="requisito" value="CONCURSO" id="concurso" <?= ($datos_principal['requisito'] == 'CONCURSO') ? 'checked' : '' ?>>
<label for="concurso">CONCURSO</label>
</div>
<div class="checkbox-group">
<input type="radio" name="requisito" value="EXONERADO(A)" id="exonerado" <?= ($datos_principal['requisito'] == 'EXONERADO(A)') ? 'checked' : '' ?>>
<label for="exonerado">EXONERADO(A)</label>
</div>
<div class="checkbox-group">
<input type="radio" name="requisito" value="COMPLETACIÓN" id="completacion" <?= ($datos_principal['requisito'] == 'COMPLETACIÓN') ? 'checked' : '' ?>>
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
<input type="date" name="fecha_inicio" value="<?= $datos_principal['fecha_inicio'] ?>" required>
</div>
<div class="form-group">
<label>FECHA DE VENCIMIENTO</label>
<input type="date" name="fecha_vencimiento" value="<?= $datos_principal['fecha_vencimiento'] ?>">
</div>
<div class="form-group">
<label>No. DE HORAS</label>
<input type="number" name="numero_horas" id="numero_horas" value="<?= $datos_principal['numero_horas'] ?>" readonly>
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO ORIGEN -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO ORIGEN (SEGÚN ESTADO DE CUENTA)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_origen" value="<?= htmlspecialchars($centros['ORIGEN']['centro_educativo'] ?? '') ?>">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_origen" value="<?= htmlspecialchars($centros['ORIGEN']['cargo'] ?? '') ?>">
</div>
<div class="form-group">
<label>DEPTO.</label>
<input type="text" name="depto_origen" value="<?= htmlspecialchars($centros['ORIGEN']['depto'] ?? '') ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_origen" value="<?= htmlspecialchars($centros['ORIGEN']['lugar'] ?? '') ?>">
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_origen" value="<?= htmlspecialchars($centros['ORIGEN']['municipio'] ?? '') ?>">
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO REUBICACIÓN -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO (DONDE LABORA SEGÚN REUBICACIÓN, SI FUESE EL CASO)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_reubicacion" value="<?= htmlspecialchars($centros['REUBICACION']['centro_educativo'] ?? '') ?>">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_reubicacion" value="<?= htmlspecialchars($centros['REUBICACION']['cargo'] ?? '') ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_reubicacion" value="<?= htmlspecialchars($centros['REUBICACION']['lugar'] ?? '') ?>">
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_reubicacion" value="<?= htmlspecialchars($centros['REUBICACION']['municipio'] ?? '') ?>">
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
<?php foreach($estructura_presupuestaria as $index => $estructura): ?>
<tr>
<td><input type="text" name="estructura_inst[]" value="<?= htmlspecialchars($estructura['estructura_inst']) ?>" size="3" required></td>
<td><input type="text" name="estructura_programa[]" value="<?= htmlspecialchars($estructura['estructura_programa']) ?>" size="3" required></td>
<td><input type="text" name="estructura_subprograma[]" value="<?= htmlspecialchars($estructura['estructura_subprograma']) ?>" size="3" required></td>
<td><input type="text" name="estructura_fondo[]" value="<?= htmlspecialchars($estructura['estructura_fondo']) ?>" size="3" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
<?php endforeach; ?>
<?php if(empty($estructura_presupuestaria)): ?>
<tr>
<td><input type="text" name="estructura_inst[]" value="06" size="3" required></td>
<td><input type="text" name="estructura_programa[]" value="13" size="3" required></td>
<td><input type="text" name="estructura_subprograma[]" value="01" size="3" required></td>
<td><input type="text" name="estructura_fondo[]" value="11" size="3" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
<?php endif; ?>
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
<?php foreach($estructura_siarhd as $index => $siarhd): ?>
<tr>
<td><input type="text" name="siarhd_depto[]" value="<?= htmlspecialchars($siarhd['depto']) ?>" size="3" required></td>
<td><input type="text" name="siarhd_muni[]" value="<?= htmlspecialchars($siarhd['muni']) ?>" size="3" required></td>
<td><input type="text" name="siarhd_centro[]" value="<?= htmlspecialchars($siarhd['centro_educativo']) ?>" size="8" required></td>
<td><input type="text" name="siarhd_num_plaza[]" value="<?= htmlspecialchars($siarhd['num_plaza']) ?>" size="5" required></td>
<td><input type="number" name="siarhd_num_horas[]" value="<?= $siarhd['num_horas'] ?>" size="3" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
<?php endforeach; ?>
<?php if(empty($estructura_siarhd)): ?>
<tr>
<td><input type="text" name="siarhd_depto[]" size="3" required></td>
<td><input type="text" name="siarhd_muni[]" size="3" required></td>
<td><input type="text" name="siarhd_centro[]" size="8" required></td>
<td><input type="text" name="siarhd_num_plaza[]" size="5" required></td>
<td><input type="number" name="siarhd_num_horas[]" value="0" size="3" required></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
<?php endif; ?>
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
<input type="text" name="sustituye_a" value="<?= htmlspecialchars($datos_justificacion['sustituye_a'] ?? '') ?>">
</div>
<div class="form-group">
<label>IDENTIDAD N°:</label>
<input type="text" name="identidad_sustituido" value="<?= htmlspecialchars($datos_justificacion['identidad_sustituido'] ?? '') ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>¿POR QUÉ QUEDO VACANTE EL CARGO?</label>
<input type="text" name="motivo_vacante" value="<?= htmlspecialchars($datos_justificacion['motivo_vacante'] ?? '') ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_justificacion" value="<?= htmlspecialchars($datos_justificacion['centro_educativo'] ?? '') ?>">
</div>
<div class="form-group">
<label>N° DE PLAZA</label>
<input type="text" name="num_plaza_justificacion" value="<?= htmlspecialchars($datos_justificacion['num_plaza'] ?? '') ?>">
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
<tbody id="cargaAcademicaBody">
<?php foreach($carga_academica as $index => $carga): ?>
<tr>
<td><?= $index + 1 ?></td>
<td><input type="text" name="asignatura[]" value="<?= htmlspecialchars($carga['asignatura']) ?>" required></td>
<td><input type="number" class="horas-row" name="horas[]" value="<?= $carga['num_horas'] ?>" min="0" required></td>
<td><input type="text" name="modalidad[]" value="<?= htmlspecialchars($carga['modalidad']) ?>"></td>
<td><input type="text" name="cursos[]" value="<?= htmlspecialchars($carga['cursos']) ?>"></td>
<td><input type="text" name="grupos[]" value="<?= htmlspecialchars($carga['grupos']) ?>"></td>
<td><input type="text" name="jornada[]" value="<?= htmlspecialchars($carga['jornada_academica']) ?>"></td>
<td><input type="text" name="programa[]" value="<?= htmlspecialchars($carga['programa']) ?>"></td>
<td><input type="text" name="subprograma[]" value="<?= htmlspecialchars($carga['subprograma']) ?>"></td>
<td><input type="text" name="fondo[]" value="<?= htmlspecialchars($carga['fondo']) ?>"></td>
<td><button type="button" class="remove-row-btn" onclick="removeRow(this)">❌</button></td>
</tr>
<?php endforeach; ?>
<?php if(empty($carga_academica)): ?>
<tr>
<td>1</td>
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
</tr>
<?php endif; ?>
</tbody>
</table>
<div class="total-hours">
TOTAL HORAS: <span id="totalHorasSpan"><?= array_sum(array_column($carga_academica, 'num_horas')) ?></span>
</div>
<button type="button" class="add-row-btn" onclick="addCargaRow()">+ Agregar fila</button>
</div>

<!-- CENTRO EDUCATIVO DESTINO -->
<div class="section">
<div class="section-title">DATOS DEL CENTRO EDUCATIVO DESTINO (SEGÚN ESTADO DE CUENTA)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_destino" value="<?= htmlspecialchars($centros['DESTINO']['centro_educativo'] ?? '') ?>">
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_destino" value="<?= htmlspecialchars($centros['DESTINO']['cargo'] ?? '') ?>">
</div>
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_destino" value="<?= htmlspecialchars($centros['DESTINO']['lugar'] ?? '') ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_destino" value="<?= htmlspecialchars($centros['DESTINO']['municipio'] ?? '') ?>">
</div>
<div class="form-group">
<label>DEPTO.</label>
<input type="text" name="depto_destino" value="<?= htmlspecialchars($centros['DESTINO']['depto'] ?? '') ?>">
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
<input type="text" name="firma_docente" value="<?= htmlspecialchars($datos_firmas['firma_docente'] ?? '') ?>">
</td>
<td width="50%">
<label>NÚMERO DE TELÉFONO</label>
<input type="text" name="telefono_contacto" value="<?= htmlspecialchars($datos_firmas['telefono_contacto'] ?? '') ?>">
</td>
</tr>
<tr>
<td>
<label>VºBº DIRECTOR(A) DEL CENTRO EDUCATIVO</label>
<input type="text" name="vb_director_centro" value="<?= htmlspecialchars($datos_firmas['vb_director_centro'] ?? '') ?>">
</td>
<td>
<label>VºBº DIRECTOR(A) DISTRITAL O MUNICIPAL</label>
<input type="text" name="vb_director_distrital" value="<?= htmlspecialchars($datos_firmas['vb_director_distrital'] ?? '') ?>">
</td>
</tr>
<tr>
<td>
<label>(ACCIONES) FIRMA DE QUIEN REVISA</label>
<input type="text" name="firma_revisa" value="<?= htmlspecialchars($datos_firmas['firma_revisa'] ?? '') ?>">
</td>
<td>
<label>FECHA DE ENTREGA</label>
<input type="date" name="fecha_entrega" value="<?= $datos_firmas['fecha_entrega'] ?? '' ?>">
</td>
</tr>
</table>
</div>

<button type="submit" class="btn-submit">ACTUALIZAR TRASLADO MEDIA</button>
</form>
</div>

<script>
// CARGA ACADÉMICA
const cargaBody = document.getElementById('cargaAcademicaBody');
const totalHorasSpan = document.getElementById('totalHorasSpan');
const numeroHorasInput = document.getElementById('numero_horas');
let cargaCount = <?= count($carga_academica) ?: 1 ?>;

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

// Inicializar cálculo de horas
recalcTotal();
</script>

</body>
</html>
