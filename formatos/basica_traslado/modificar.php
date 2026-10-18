<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: listar.php");
    exit;
}

// Obtener datos de la tabla principal
$sql = "SELECT * FROM formato_traslado_basica WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Enfoque más seguro: construir el SQL dinámicamente
        $set_parts = [];
        $params = [];
        $types = '';
        
        // Lista de campos y sus tipos
        $fields = [
            'tipo_nombramiento' => 's',
            'nombre_docente' => 's',
            'identidad' => 's',
            'colegio_magisterial' => 's',
            'sdgthd' => 's',
            'colegiacion_numero' => 's',
            'nivel_educativo' => 's',
            'jornada_trabajo' => 's',
            'requisito' => 's',
            'fecha_inicio' => 's',
            'fecha_vencimiento' => 's',
            'centro_educativo_cuenta' => 's',
            'cargo_cuenta' => 's',
            'lugar_cuenta' => 's',
            'municipio_cuenta' => 's',
            'estructura_inst' => 's',
            'estructura_programa' => 's',
            'estructura_subprograma' => 's',
            'estructura_fondo' => 's',
            'siarhd_depto' => 's',
            'siarhd_muni' => 's',
            'siarhd_centro' => 's',
            'siarhd_num_plaza' => 's',
            'sustituye_a' => 's',
            'identidad_sustituido' => 's',
            'motivo_vacante' => 's',
            'firma_docente' => 's',
            'vb_director_centro' => 's',
            'firma_revisa' => 's',
            'telefono_contacto' => 's',
            'vb_director_distrital' => 's',
            'fecha_entrega' => 's'
        ];
        
        // Campos numéricos
        $numeric_fields = [
            'numero_horas' => 'i',
            'siarhd_num_horas' => 'i'
        ];
        
        // Procesar todos los campos juntos
        $all_fields = array_merge($fields, $numeric_fields);
        
        foreach ($all_fields as $field => $type) {
            if (isset($_POST[$field])) {
                $set_parts[] = "$field = ?";
                if ($type === 'i') {
                    $params[] = intval($_POST[$field]);
                } else {
                    $params[] = $_POST[$field];
                }
                $types .= $type;
            }
        }
        
        // Agregar ID
        $params[] = $id;
        $types .= 'i';
        
        // Construir SQL final
        $sql_update = "UPDATE formato_traslado_basica SET " . implode(", ", $set_parts) . " WHERE id = ?";
        
        $stmt = $conn->prepare($sql_update);
        
        // Vincular parámetros usando referencia
        $bind_params = array_merge([$types], $params);
        
        // Crear referencias para bind_param
        $refs = [];
        foreach ($bind_params as $key => $value) {
            $refs[$key] = &$bind_params[$key];
        }
        
        call_user_func_array([$stmt, 'bind_param'], $refs);
        
        if ($stmt->execute()) {
            header("Location: listar.php?updated=1");
            exit;
        } else {
            $error_message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        
    } catch (Exception $e) {
        $error_message = "Error al actualizar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modificar Traslado BÁSICA</title>
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
    .basica-badge { background:#007bff; color:white; padding:2px 8px; border-radius:3px; font-size:10px; margin-left:5px; }
    .error-message { background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px; border-radius:4px; border:1px solid #f5c6cb; }
</style>
</head>
<body>

<div class="navigation">
    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-back">🔙 Volver Atrás</a>
    <a href="../../menu.php" class="btn btn-menu">🏠 Volver al Menú</a>
    <a href="listar.php" class="btn btn-back">📋 Ver Lista</a>
</div>

<div class="form-container">
<?php if (isset($error_message)): ?>
    <div class="error-message">❌ <?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>

<form method="post" action="modificar.php?id=<?php echo $id; ?>">

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
<input type="radio" name="tipo_nombramiento" value="INTERINO" id="interino" <?php echo (isset($row['tipo_nombramiento']) && $row['tipo_nombramiento'] == 'INTERINO') ? 'checked' : ''; ?> required>
<label for="interino">INTERINO</label>
</div>
<div class="checkbox-group">
<input type="radio" name="tipo_nombramiento" value="PERMANENTE" id="permanente" <?php echo (isset($row['tipo_nombramiento']) && $row['tipo_nombramiento'] == 'PERMANENTE') ? 'checked' : ''; ?>>
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
<input type="text" name="nombre_docente" value="<?php echo isset($row['nombre_docente']) ? htmlspecialchars($row['nombre_docente']) : ''; ?>" required>
</div>
<div class="form-group">
<label>IDENTIDAD</label>
<input type="text" name="identidad" value="<?php echo isset($row['identidad']) ? htmlspecialchars($row['identidad']) : ''; ?>" required>
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>COLEGIO MAGISTERIAL</label>
<input type="text" name="colegio_magisterial" value="<?php echo isset($row['colegio_magisterial']) ? htmlspecialchars($row['colegio_magisterial']) : ''; ?>">
</div>
<div class="form-group">
<label>SDGTHD</label>
<input type="text" name="sdgthd" value="<?php echo isset($row['sdgthd']) ? htmlspecialchars($row['sdgthd']) : ''; ?>">
</div>
<div class="form-group">
<label>COLEGIACIÓN N°</label>
<input type="text" name="colegiacion_numero" value="<?php echo isset($row['colegiacion_numero']) ? htmlspecialchars($row['colegiacion_numero']) : ''; ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>NIVEL EDUCATIVO</label>
<div class="checkbox-group">
<input type="radio" name="nivel_educativo" value="PRE-BASICA" id="prebasica" <?php echo (isset($row['nivel_educativo']) && $row['nivel_educativo'] == 'PRE-BASICA') ? 'checked' : ''; ?> required>
<label for="prebasica">PRE-BASICA</label>
</div>
<div class="checkbox-group">
<input type="radio" name="nivel_educativo" value="BASICA" id="basica" <?php echo (isset($row['nivel_educativo']) && $row['nivel_educativo'] == 'BASICA') ? 'checked' : ''; ?>>
<label for="basica">BASICA</label>
</div>
</div>
<div class="form-group">
<label>JORNADA DE TRABAJO</label>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="PARCIAL" id="parcial" <?php echo (isset($row['jornada_trabajo']) && $row['jornada_trabajo'] == 'PARCIAL') ? 'checked' : ''; ?>>
<label for="parcial">PARCIAL</label>
</div>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="COMPLETA" id="completa" <?php echo (isset($row['jornada_trabajo']) && $row['jornada_trabajo'] == 'COMPLETA') ? 'checked' : ''; ?>>
<label for="completa">COMPLETA</label>
</div>
<div class="checkbox-group">
<input type="radio" name="jornada_trabajo" value="EXCLUSIVA" id="exclusiva" <?php echo (isset($row['jornada_trabajo']) && $row['jornada_trabajo'] == 'EXCLUSIVA') ? 'checked' : ''; ?>>
<label for="exclusiva">EXCLUSIVA</label>
</div>
</div>
<div class="form-group">
<label>REQUISITO</label>
<div class="checkbox-group">
<input type="radio" name="requisito" value="CONCURSO" id="concurso" <?php echo (isset($row['requisito']) && $row['requisito'] == 'CONCURSO') ? 'checked' : ''; ?>>
<label for="concurso">CONCURSO</label>
</div>
<div class="checkbox-group">
<input type="radio" name="requisito" value="EXONERADO(A)" id="exonerado" <?php echo (isset($row['requisito']) && $row['requisito'] == 'EXONERADO(A)') ? 'checked' : ''; ?>>
<label for="exonerado">EXONERADO(A)</label>
</div>
<div class="checkbox-group">
<input type="radio" name="requisito" value="COMPLETACIÓN" id="completacion" <?php echo (isset($row['requisito']) && $row['requisito'] == 'COMPLETACIÓN') ? 'checked' : ''; ?>>
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
<input type="date" name="fecha_inicio" value="<?php echo isset($row['fecha_inicio']) ? $row['fecha_inicio'] : ''; ?>" required>
</div>
<div class="form-group">
<label>FECHA DE VENCIMIENTO</label>
<input type="date" name="fecha_vencimiento" value="<?php echo isset($row['fecha_vencimiento']) ? $row['fecha_vencimiento'] : ''; ?>">
</div>
<div class="form-group">
<label>No. DE HORAS</label>
<input type="number" name="numero_horas" value="<?php echo isset($row['numero_horas']) ? $row['numero_horas'] : '0'; ?>" required>
</div>
</div>
</div>

<!-- CENTRO EDUCATIVO (SEGÚN ESTADO DE CUENTA) -->
<div class="section">
<div class="section-title">CENTRO EDUCATIVO (SEGÚN ESTADO DE CUENTA)</div>
<div class="form-row">
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="centro_educativo_cuenta" value="<?php echo isset($row['centro_educativo_cuenta']) ? htmlspecialchars($row['centro_educativo_cuenta']) : ''; ?>" required>
</div>
<div class="form-group">
<label>CARGO</label>
<input type="text" name="cargo_cuenta" value="<?php echo isset($row['cargo_cuenta']) ? htmlspecialchars($row['cargo_cuenta']) : ''; ?>" required>
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>LUGAR</label>
<input type="text" name="lugar_cuenta" value="<?php echo isset($row['lugar_cuenta']) ? htmlspecialchars($row['lugar_cuenta']) : ''; ?>" required>
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="municipio_cuenta" value="<?php echo isset($row['municipio_cuenta']) ? htmlspecialchars($row['municipio_cuenta']) : ''; ?>" required>
</div>
</div>
</div>

<!-- ESTRUCTURA PRESUPUESTARIA -->
<div class="section">
<div class="section-title">ESTRUCTURA PRESUPUESTARIA</div>
<div class="form-row">
<div class="form-group">
<label>INSTITUCIÓN</label>
<input type="text" name="estructura_inst" value="<?php echo isset($row['estructura_inst']) ? htmlspecialchars($row['estructura_inst']) : '06'; ?>" required>
</div>
<div class="form-group">
<label>PROGRAMA</label>
<input type="text" name="estructura_programa" value="<?php echo isset($row['estructura_programa']) ? htmlspecialchars($row['estructura_programa']) : '13'; ?>" required>
</div>
<div class="form-group">
<label>SUB-PROGRAMA</label>
<input type="text" name="estructura_subprograma" value="<?php echo isset($row['estructura_subprograma']) ? htmlspecialchars($row['estructura_subprograma']) : '01'; ?>" required>
</div>
<div class="form-group">
<label>FONDO</label>
<input type="text" name="estructura_fondo" value="<?php echo isset($row['estructura_fondo']) ? htmlspecialchars($row['estructura_fondo']) : '11'; ?>" required>
</div>
</div>
</div>

<!-- ESTRUCTURA SIARHD -->
<div class="section">
<div class="section-title">ESTRUCTURA SIARHD</div>
<div class="form-row">
<div class="form-group">
<label>DEPARTAMENTO</label>
<input type="text" name="siarhd_depto" value="<?php echo isset($row['siarhd_depto']) ? htmlspecialchars($row['siarhd_depto']) : ''; ?>" required>
</div>
<div class="form-group">
<label>MUNICIPIO</label>
<input type="text" name="siarhd_muni" value="<?php echo isset($row['siarhd_muni']) ? htmlspecialchars($row['siarhd_muni']) : ''; ?>" required>
</div>
<div class="form-group">
<label>CENTRO EDUCATIVO</label>
<input type="text" name="siarhd_centro" value="<?php echo isset($row['siarhd_centro']) ? htmlspecialchars($row['siarhd_centro']) : ''; ?>" required>
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>N° DE PLAZA</label>
<input type="text" name="siarhd_num_plaza" value="<?php echo isset($row['siarhd_num_plaza']) ? htmlspecialchars($row['siarhd_num_plaza']) : ''; ?>" required>
</div>
<div class="form-group">
<label>N° HORAS</label>
<input type="number" name="siarhd_num_horas" value="<?php echo isset($row['siarhd_num_horas']) ? $row['siarhd_num_horas'] : '0'; ?>" required>
</div>
</div>
</div>

<!-- JUSTIFICACIÓN -->
<div class="section">
<div class="section-title">JUSTIFICACIÓN DEL NOMBRAMIENTO</div>
<div class="form-row">
<div class="form-group">
<label>SUSTITUYE A:</label>
<input type="text" name="sustituye_a" value="<?php echo isset($row['sustituye_a']) ? htmlspecialchars($row['sustituye_a']) : ''; ?>">
</div>
<div class="form-group">
<label>IDENTIDAD N°:</label>
<input type="text" name="identidad_sustituido" value="<?php echo isset($row['identidad_sustituido']) ? htmlspecialchars($row['identidad_sustituido']) : ''; ?>">
</div>
</div>
<div class="form-row">
<div class="form-group">
<label>¿POR QUÉ QUEDO VACANTE EL CARGO?</label>
<input type="text" name="motivo_vacante" value="<?php echo isset($row['motivo_vacante']) ? htmlspecialchars($row['motivo_vacante']) : ''; ?>">
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
<input type="text" name="firma_docente" value="<?php echo isset($row['firma_docente']) ? htmlspecialchars($row['firma_docente']) : ''; ?>">
</td>
<td width="50%">
<label>NÚMERO DE TELÉFONO</label>
<input type="text" name="telefono_contacto" value="<?php echo isset($row['telefono_contacto']) ? htmlspecialchars($row['telefono_contacto']) : ''; ?>">
</td>
</tr>
<tr>
<td>
<label>VºBº DIRECTOR(A) DEL CENTRO EDUCATIVO</label>
<input type="text" name="vb_director_centro" value="<?php echo isset($row['vb_director_centro']) ? htmlspecialchars($row['vb_director_centro']) : ''; ?>">
</td>
<td>
<label>VºBº DIRECTOR(A) DISTRITAL O MUNICIPAL</label>
<input type="text" name="vb_director_distrital" value="<?php echo isset($row['vb_director_distrital']) ? htmlspecialchars($row['vb_director_distrital']) : ''; ?>">
</td>
</tr>
<tr>
<td>
<label>(ACCIONES) FIRMA DE QUIEN REVISA</label>
<input type="text" name="firma_revisa" value="<?php echo isset($row['firma_revisa']) ? htmlspecialchars($row['firma_revisa']) : ''; ?>">
</td>
<td>
<label>FECHA DE ENTREGA</label>
<input type="date" name="fecha_entrega" value="<?php echo isset($row['fecha_entrega']) ? $row['fecha_entrega'] : ''; ?>">
</td>
</tr>
</table>
</div>

<button type="submit" class="btn-submit">ACTUALIZAR TRASLADO BÁSICA</button>
</form>
</div>

<script>
// Formatear fecha si es necesario
document.addEventListener('DOMContentLoaded', function() {
    const fechaInputs = document.querySelectorAll('input[type="date"]');
    fechaInputs.forEach(input => {
        if (input.value) {
            // Asegurar formato YYYY-MM-DD
            const date = new Date(input.value);
            if (!isNaN(date.getTime())) {
                input.value = date.toISOString().split('T')[0];
            }
        }
    });
});
</script>

</body>
</html>