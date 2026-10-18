<?php include "../../includes/header.php"; ?>
<?php include "../../includes/db.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formato Unificado de Nombramiento (F1) - Educación Básica</title>
<style>
    * { 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
        font-family: Arial, sans-serif; 
    }
    body { 
        background-color: #f5f5f5; 
        padding: 20px; 
        color: #000; 
    }
    .navigation { 
        text-align: center; 
        margin-bottom: 20px; 
    }
    .btn { 
        display: inline-block; 
        padding: 10px 18px; 
        border-radius: 6px; 
        text-decoration: none; 
        font-weight: bold; 
        margin: 0 5px; 
        border: none; 
        cursor: pointer; 
        font-size: 14px;
        color: white !important;
    }
    .btn-back { 
        background-color: #555; 
    }
    .btn-menu { 
        background-color: #0066cc; 
    }
    .btn-list { 
        background-color: #17a2b8; 
    }
    .form-container { 
        max-width: 900px; 
        margin: 0 auto; 
        background: white; 
        box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        padding: 20px; 
        border: 1px solid #000;
    }
    .header { 
        text-align: center; 
        margin-bottom: 20px; 
        border-bottom: 2px solid #000; 
        padding-bottom: 10px; 
        color: #000 !important;
    }
    .header h1 { 
        font-size: 18px; 
        font-weight: bold; 
        margin-bottom: 5px; 
        text-transform: uppercase;
        color: #000;
    }
    .header h2 { 
        font-size: 16px; 
        margin-bottom: 5px; 
        text-transform: uppercase;
        color: #000;
    }
    .header h3 { 
        font-size: 14px; 
        font-weight: normal; 
        text-transform: uppercase;
        color: #000;
    }
    
    /* Estilos de tablas como el PDF */
    .form-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
        border: 1px solid #000;
    }
    .form-table th {
        background-color: #f0f0f0;
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
        font-weight: bold;
        font-size: 12px;
        vertical-align: top;
        color: #000 !important;
    }
    .form-table td {
        border: 1px solid #000;
        padding: 8px;
        vertical-align: top;
        color: #000;
    }
    
    /* Grupos de checkboxes/radios */
    .option-group {
        display: flex;
        gap: 15px;
        margin: 5px 0;
    }
    .option-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .option-item input[type="radio"],
    .option-item input[type="checkbox"] {
        margin: 0;
    }
    .option-item label {
        font-size: 12px;
        font-weight: normal;
        margin: 0;
        color: #000;
    }
    
    /* Campos de formulario */
    input[type="text"],
    input[type="number"],
    input[type="date"],
    textarea {
        width: 100%;
        padding: 4px;
        border: 1px solid #999;
        font-size: 12px;
        background: white;
        color: #000;
    }
    input[type="text"]::placeholder,
    input[type="number"]::placeholder,
    textarea::placeholder {
        color: #666;
    }
    
    /* Secciones específicas */
    .section-title {
        font-weight: bold;
        text-align: center;
        background-color: #f0f0f0;
        padding: 8px;
        border: 1px solid #000;
        border-bottom: none;
        font-size: 14px;
        text-transform: uppercase;
        color: #000 !important;
        margin-bottom: 0;
    }
    
    .budget-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }
    .budget-table th,
    .budget-table td {
        border: 1px solid #000;
        padding: 4px;
        text-align: center;
        color: #000;
    }
    .budget-table th {
        background-color: #f0f0f0;
        font-weight: bold;
        color: #000 !important;
    }
    .budget-table input {
        width: 100%;
        border: none;
        text-align: center;
        background: transparent;
        font-size: 11px;
        color: #000;
    }
    
    .btn-submit {
        background-color: #004080;
        color: white !important;
        border: none;
        padding: 12px 30px;
        font-size: 16px;
        cursor: pointer;
        margin: 20px auto;
        display: block;
        font-weight: bold;
        text-transform: uppercase;
    }
    .btn-submit:hover {
        background-color: #002952;
    }
    
    /* Asegurar que todo el texto sea visible */
    label {
        color: #000 !important;
        font-weight: bold;
    }
    
    /* Mensajes de alerta */
    .alert {
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
    }
    .alert-success {
        background: #d4edda;
        color: #155724 !important;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24 !important;
        border: 1px solid #f5c6cb;
    }
</style>
</head>
<body>

<div class="navigation">
    <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-back">🔙 Volver Atrás</a>
    <a href="../../menu.php" class="btn btn-menu">🏠 Volver al Menú</a>
    <a href="listar.php" class="btn btn-list">📋 Ver Lista</a>
</div>

<div class="form-container">
<form id="nombramientoForm" method="post" action="">

<?php
// Obtener el ID del registro a modificar
$id = $_GET['id'] ?? 0;

if ($id == 0) {
    echo '<div class="alert alert-danger">❌ ID no válido</div>';
    echo '<script>setTimeout(() => window.location.href = "listar.php", 2000);</script>';
    exit;
}

// Consulta para obtener los datos del nombramiento
$sql = "SELECT fnb.*, 
               bip.colegio_magisterial, bip.sdgthd, bip.colegiacion_numero, bip.jornada_trabajo, bip.requisito,
               bcc.centro_educativo as centro_cuenta, bcc.cargo as cargo_cuenta, bcc.lugar as lugar_cuenta, bcc.municipio as municipio_cuenta,
               bcr.centro_educativo as centro_reubicacion, bcr.cargo as cargo_reubicacion, bcr.lugar as lugar_reubicacion, bcr.municipio as municipio_reubicacion,
               bep.estructura_inst, bep.estructura_programa, bep.estructura_subprograma, bep.estructura_fondo,
               bes.siarhd_depto, bes.siarhd_muni, bes.siarhd_centro, bes.siarhd_num_plaza, bes.siarhd_num_horas,
               bj.sustituye_a, bj.identidad_sustituido, bj.motivo_vacante,
               bfv.firma_docente, bfv.telefono_contacto, bfv.vb_director_centro, bfv.vb_director_distrital, bfv.firma_revisa, bfv.fecha_entrega
        FROM formato_nombramiento_basica fnb
        LEFT JOIN basica_info_profesional bip ON fnb.id = bip.nombramiento_id
        LEFT JOIN basica_centro_cuenta bcc ON fnb.id = bcc.nombramiento_id
        LEFT JOIN basica_centro_reubicacion bcr ON fnb.id = bcr.nombramiento_id
        LEFT JOIN basica_estructura_presupuestaria bep ON fnb.id = bep.nombramiento_id
        LEFT JOIN basica_estructura_siarhd bes ON fnb.id = bes.nombramiento_id
        LEFT JOIN basica_justificacion bj ON fnb.id = bj.nombramiento_id
        LEFT JOIN basica_firmas_validacion bfv ON fnb.id = bfv.nombramiento_id
        WHERE fnb.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<div class="alert alert-danger">❌ Nombramiento no encontrado</div>';
    echo '<script>setTimeout(() => window.location.href = "listar.php", 2000);</script>';
    exit;
}

$row = $result->fetch_assoc();

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Iniciar transacción
        $conn->begin_transaction();
        
        // Actualizar tabla principal
        $sql_principal = "UPDATE formato_nombramiento_basica SET 
                         tipo_nombramiento = ?, nombre_docente = ?, identidad = ?, nivel_educativo = ?, 
                         fecha_inicio = ?, fecha_vencimiento = ?, numero_horas = ? 
                         WHERE id = ?";
        
        $stmt_principal = $conn->prepare($sql_principal);
        $stmt_principal->bind_param("ssssssii",
            $_POST['tipo_nombramiento'],
            $_POST['nombre_docente'],
            $_POST['identidad'],
            $_POST['nivel_educativo'],
            $_POST['fecha_inicio'],
            $_POST['fecha_vencimiento'],
            $_POST['numero_horas'],
            $id
        );
        $stmt_principal->execute();
        $stmt_principal->close();
        
        // Actualizar información profesional
        $sql_profesional = "UPDATE basica_info_profesional SET 
                           colegio_magisterial = ?, sdgthd = ?, colegiacion_numero = ?, 
                           jornada_trabajo = ?, requisito = ? 
                           WHERE nombramiento_id = ?";
        
        $stmt_profesional = $conn->prepare($sql_profesional);
        $stmt_profesional->bind_param("sssssi",
            $_POST['colegio_magisterial'],
            $_POST['sdgthd'],
            $_POST['colegiacion_numero'],
            $_POST['jornada_trabajo'],
            $_POST['requisito'],
            $id
        );
        $stmt_profesional->execute();
        $stmt_profesional->close();
        
        // Actualizar centro educativo según cuenta
        $sql_centro_cuenta = "UPDATE basica_centro_cuenta SET 
                             centro_educativo = ?, cargo = ?, lugar = ?, municipio = ? 
                             WHERE nombramiento_id = ?";
        
        $stmt_centro_cuenta = $conn->prepare($sql_centro_cuenta);
        $stmt_centro_cuenta->bind_param("ssssi",
            $_POST['centro_educativo_cuenta'],
            $_POST['cargo_cuenta'],
            $_POST['lugar_cuenta'],
            $_POST['municipio_cuenta'],
            $id
        );
        $stmt_centro_cuenta->execute();
        $stmt_centro_cuenta->close();
        
        // Actualizar centro educativo reubicación
        $sql_check_reubicacion = "SELECT id FROM basica_centro_reubicacion WHERE nombramiento_id = ?";
        $stmt_check = $conn->prepare($sql_check_reubicacion);
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $sql_centro_reubicacion = "UPDATE basica_centro_reubicacion SET 
                                      centro_educativo = ?, cargo = ?, lugar = ?, municipio = ? 
                                      WHERE nombramiento_id = ?";
        } else {
            $sql_centro_reubicacion = "INSERT INTO basica_centro_reubicacion 
                                      (nombramiento_id, centro_educativo, cargo, lugar, municipio) 
                                      VALUES (?, ?, ?, ?, ?)";
        }
        
        $stmt_centro_reubicacion = $conn->prepare($sql_centro_reubicacion);
        if ($result_check->num_rows > 0) {
            $stmt_centro_reubicacion->bind_param("ssssi",
                $_POST['centro_educativo_reubicacion'],
                $_POST['cargo_reubicacion'],
                $_POST['lugar_reubicacion'],
                $_POST['municipio_reubicacion'],
                $id
            );
        } else {
            $stmt_centro_reubicacion->bind_param("issss",
                $id,
                $_POST['centro_educativo_reubicacion'],
                $_POST['cargo_reubicacion'],
                $_POST['lugar_reubicacion'],
                $_POST['municipio_reubicacion']
            );
        }
        $stmt_centro_reubicacion->execute();
        $stmt_centro_reubicacion->close();
        $stmt_check->close();
        
        // Actualizar estructura presupuestaria
        $sql_estructura = "UPDATE basica_estructura_presupuestaria SET 
                          estructura_inst = ?, estructura_programa = ?, 
                          estructura_subprograma = ?, estructura_fondo = ? 
                          WHERE nombramiento_id = ?";
        
        $stmt_estructura = $conn->prepare($sql_estructura);
        $stmt_estructura->bind_param("ssssi",
            $_POST['estructura_inst'],
            $_POST['estructura_programa'],
            $_POST['estructura_subprograma'],
            $_POST['estructura_fondo'],
            $id
        );
        $stmt_estructura->execute();
        $stmt_estructura->close();
        
        // Actualizar estructura SIARHD
        $sql_siarhd = "UPDATE basica_estructura_siarhd SET 
                      siarhd_depto = ?, siarhd_muni = ?, siarhd_centro = ?, 
                      siarhd_num_plaza = ?, siarhd_num_horas = ? 
                      WHERE nombramiento_id = ?";
        
        $stmt_siarhd = $conn->prepare($sql_siarhd);
        $stmt_siarhd->bind_param("sssssi",
            $_POST['siarhd_depto'],
            $_POST['siarhd_muni'],
            $_POST['siarhd_centro'],
            $_POST['siarhd_num_plaza'],
            $_POST['siarhd_num_horas'],
            $id
        );
        $stmt_siarhd->execute();
        $stmt_siarhd->close();
        
        // Actualizar justificación
        $sql_justificacion = "UPDATE basica_justificacion SET 
                             sustituye_a = ?, identidad_sustituido = ?, motivo_vacante = ? 
                             WHERE nombramiento_id = ?";
        
        $stmt_justificacion = $conn->prepare($sql_justificacion);
        $stmt_justificacion->bind_param("sssi",
            $_POST['sustituye_a'],
            $_POST['identidad_sustituido'],
            $_POST['motivo_vacante'],
            $id
        );
        $stmt_justificacion->execute();
        $stmt_justificacion->close();
        
        // Actualizar firmas y validación
        $sql_firmas = "UPDATE basica_firmas_validacion SET 
                      firma_docente = ?, telefono_contacto = ?, vb_director_centro = ?, 
                      vb_director_distrital = ?, firma_revisa = ?, fecha_entrega = ? 
                      WHERE nombramiento_id = ?";
        
        $stmt_firmas = $conn->prepare($sql_firmas);
        $stmt_firmas->bind_param("ssssssi",
            $_POST['firma_docente'],
            $_POST['telefono_contacto'],
            $_POST['vb_director_centro'],
            $_POST['vb_director_distrital'],
            $_POST['firma_revisa'],
            $_POST['fecha_entrega'],
            $id
        );
        $stmt_firmas->execute();
        $stmt_firmas->close();
        
        $conn->commit();
        echo '<div class="alert alert-success">✅ Nombramiento actualizado correctamente</div>';
        echo '<script>setTimeout(() => window.location.href = "listar.php?updated=1", 2000);</script>';
        
    } catch (Exception $e) {
        $conn->rollback();
        echo '<div class="alert alert-danger">❌ Error al actualizar el nombramiento: ' . $e->getMessage() . '</div>';
    }
    
    // Recargar los datos actualizados
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}

$conn->close();
?>

<!-- ENCABEZADO -->
<div class="header">
    <h1>HONDURAS</h1>
    <h2>GOBIERNO DE LA REPÚBLICA</h2>
    <h2>SECRETARÍA DE EDUCACIÓN</h2>
    <h3>DIRECCIÓN DEPARTAMENTAL DE EDUCACIÓN, YORO</h3>
    <h3>FORMATO UNIFICADO DE NOMBRAMIENTO (F1)</h3>
</div>

<!-- TIPO DE NOMBRAMIENTO -->
<table class="form-table">
    <tr>
        <th width="30%">TIPO DE NOMBRAMIENTO</th>
        <td>
            <div class="option-group">
                <div class="option-item">
                    <input type="radio" name="tipo_nombramiento" value="INTERINO" id="interino" 
                           <?php echo ($row['tipo_nombramiento'] == 'INTERINO') ? 'checked' : ''; ?> required>
                    <label for="interino">INTERINO</label>
                </div>
                <div class="option-item">
                    <input type="radio" name="tipo_nombramiento" value="PERMANENTE" id="permanente"
                           <?php echo ($row['tipo_nombramiento'] == 'PERMANENTE') ? 'checked' : ''; ?>>
                    <label for="permanente">PERMANENTE</label>
                </div>
            </div>
        </td>
    </tr>
</table>

<!-- INFORMACIÓN PERSONAL DEL DOCENTE -->
<div class="section-title">INFORMACIÓN PERSONAL DEL DOCENTE</div>
<table class="form-table">
    <tr>
        <th width="25%">NOMBRE DEL DOCENTE</th>
        <td><input type="text" name="nombre_docente" value="<?php echo htmlspecialchars($row['nombre_docente']); ?>" required></td>
    </tr>
    <tr>
        <th>IDENTIDAD</th>
        <td><input type="text" name="identidad" value="<?php echo $row['identidad']; ?>" required></td>
    </tr>
    <tr>
        <th>SDGTHD</th>
        <td><input type="text" name="sdgthd" value="<?php echo $row['sdgthd']; ?>"></td>
    </tr>
    <tr>
        <th>COLEGIO MAGISTERIAL</th>
        <td><input type="text" name="colegio_magisterial" value="<?php echo $row['colegio_magisterial']; ?>"></td>
    </tr>
    <tr>
        <th>COLEGIACIÓN N°</th>
        <td><input type="text" name="colegiacion_numero" value="<?php echo $row['colegiacion_numero']; ?>"></td>
    </tr>
    <tr>
        <th>NIVEL EDUCATIVO</th>
        <td>
            <div class="option-group">
                <div class="option-item">
                    <input type="radio" name="nivel_educativo" value="PRE-BASICA" id="pre-basica" 
                           <?php echo ($row['nivel_educativo'] == 'PRE-BASICA') ? 'checked' : ''; ?> required>
                    <label for="pre-basica">PRE-BASICA</label>
                </div>
                <div class="option-item">
                    <input type="radio" name="nivel_educativo" value="BASICA" id="basica"
                           <?php echo ($row['nivel_educativo'] == 'BASICA') ? 'checked' : ''; ?>>
                    <label for="basica">BASICA</label>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>JORNADA DE TRABAJO</th>
        <td>
            <div class="option-group">
                <div class="option-item">
                    <input type="radio" name="jornada_trabajo" value="PARCIAL" id="parcial"
                           <?php echo ($row['jornada_trabajo'] == 'PARCIAL') ? 'checked' : ''; ?>>
                    <label for="parcial">PARCIAL</label>
                </div>
                <div class="option-item">
                    <input type="radio" name="jornada_trabajo" value="COMPLETA" id="completa"
                           <?php echo ($row['jornada_trabajo'] == 'COMPLETA') ? 'checked' : ''; ?>>
                    <label for="completa">COMPLETA</label>
                </div>
                <div class="option-item">
                    <input type="radio" name="jornada_trabajo" value="EXCLUSIVA" id="exclusiva"
                           <?php echo ($row['jornada_trabajo'] == 'EXCLUSIVA') ? 'checked' : ''; ?>>
                    <label for="exclusiva">EXCLUSIVA</label>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>REQUISITO</th>
        <td>
            <div class="option-group">
                <div class="option-item">
                    <input type="radio" name="requisito" value="CONCURSO" id="concurso"
                           <?php echo ($row['requisito'] == 'CONCURSO') ? 'checked' : ''; ?>>
                    <label for="concurso">CONCURSO</label>
                </div>
                <div class="option-item">
                    <input type="radio" name="requisito" value="EXONERADO(A)" id="exonerado"
                           <?php echo ($row['requisito'] == 'EXONERADO(A)') ? 'checked' : ''; ?>>
                    <label for="exonerado">EXONERADO(A)</label>
                </div>
                <div class="option-item">
                    <input type="radio" name="requisito" value="COMPLETACIÓN" id="completacion"
                           <?php echo ($row['requisito'] == 'COMPLETACIÓN') ? 'checked' : ''; ?>>
                    <label for="completacion">COMPLETACIÓN</label>
                </div>
            </div>
        </td>
    </tr>
</table>

<!-- DATOS ESPECÍFICOS DEL NOMBRAMIENTO -->
<div class="section-title">DATOS ESPECÍFICOS DEL NOMBRAMIENTO</div>
<table class="form-table">
    <tr>
        <th width="33%">FECHA DE INICIO</th>
        <th width="33%">FECHA DE VENCIMIENTO</th>
        <th width="34%">No. DE HORAS</th>
    </tr>
    <tr>
        <td><input type="date" name="fecha_inicio" value="<?php echo $row['fecha_inicio']; ?>" required></td>
        <td><input type="date" name="fecha_vencimiento" value="<?php echo $row['fecha_vencimiento']; ?>"></td>
        <td><input type="number" name="numero_horas" value="<?php echo $row['numero_horas']; ?>"></td>
    </tr>
</table>

<!-- DATOS DEL CENTRO EDUCATIVO (SEGÚN ESTADO DE CUENTA) -->
<div class="section-title">DATOS DEL CENTRO EDUCATIVO (SEGÚN ESTADO DE CUENTA)</div>
<table class="form-table">
    <tr>
        <th width="25%">CENTRO EDUCATIVO</th>
        <th width="25%">CARGO</th>
        <th width="25%">LUGAR</th>
        <th width="25%">MUNICIPIO</th>
    </tr>
    <tr>
        <td><input type="text" name="centro_educativo_cuenta" value="<?php echo htmlspecialchars($row['centro_cuenta']); ?>"></td>
        <td><input type="text" name="cargo_cuenta" value="<?php echo $row['cargo_cuenta']; ?>"></td>
        <td><input type="text" name="lugar_cuenta" value="<?php echo $row['lugar_cuenta']; ?>"></td>
        <td><input type="text" name="municipio_cuenta" value="<?php echo $row['municipio_cuenta']; ?>"></td>
    </tr>
</table>

<!-- DATOS DEL CENTRO EDUCATIVO (REUBICACIÓN) -->
<div class="section-title">DATOS DEL CENTRO EDUCATIVO (DONDE LABORA SEGÚN REUBICACIÓN, SI FUESE EL CASO)</div>
<table class="form-table">
    <tr>
        <th width="25%">CENTRO EDUCATIVO</th>
        <th width="25%">CARGO</th>
        <th width="25%">LUGAR</th>
        <th width="25%">MUNICIPIO</th>
    </tr>
    <tr>
        <td><input type="text" name="centro_educativo_reubicacion" value="<?php echo htmlspecialchars($row['centro_reubicacion']); ?>"></td>
        <td><input type="text" name="cargo_reubicacion" value="<?php echo $row['cargo_reubicacion']; ?>"></td>
        <td><input type="text" name="lugar_reubicacion" value="<?php echo $row['lugar_reubicacion']; ?>"></td>
        <td><input type="text" name="municipio_reubicacion" value="<?php echo $row['municipio_reubicacion']; ?>"></td>
    </tr>
</table>

<!-- PLAZA PRESUPUESTARIA -->
<div class="section-title">PLAZA PRESUPUESTARIA</div>
<table class="form-table">
    <tr>
        <th width="50%">ESTRUCTURA PRESUPUESTARIA</th>
        <th width="50%">ESTRUCTURA SIARHD</th>
    </tr>
    <tr>
        <td>
            <table class="budget-table">
                <tr>
                    <th width="25%">INST.</th>
                    <th width="25%">PROGRAMA</th>
                    <th width="25%">SUB-PROGRAMA</th>
                    <th width="25%">FONDO</th>
                </tr>
                <tr>
                    <td><input type="text" name="estructura_inst" value="<?php echo $row['estructura_inst']; ?>"></td>
                    <td><input type="text" name="estructura_programa" value="<?php echo $row['estructura_programa']; ?>"></td>
                    <td><input type="text" name="estructura_subprograma" value="<?php echo $row['estructura_subprograma']; ?>"></td>
                    <td><input type="text" name="estructura_fondo" value="<?php echo $row['estructura_fondo']; ?>"></td>
                </tr>
            </table>
        </td>
        <td>
            <table class="budget-table">
                <tr>
                    <th width="20%">DEPTO.</th>
                    <th width="20%">MUNI.</th>
                    <th width="25%">CENTRO EDUCATIVO</th>
                    <th width="17%">N° DE PLAZA</th>
                    <th width="18%">N° HRS</th>
                </tr>
                <tr>
                    <td><input type="text" name="siarhd_depto" value="<?php echo $row['siarhd_depto']; ?>"></td>
                    <td><input type="text" name="siarhd_muni" value="<?php echo $row['siarhd_muni']; ?>"></td>
                    <td><input type="text" name="siarhd_centro" value="<?php echo $row['siarhd_centro']; ?>"></td>
                    <td><input type="text" name="siarhd_num_plaza" value="<?php echo $row['siarhd_num_plaza']; ?>"></td>
                    <td><input type="text" name="siarhd_num_horas" value="<?php echo $row['siarhd_num_horas']; ?>"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- JUSTIFICACIÓN DEL NOMBRAMIENTO -->
<div class="section-title">JUSTIFICACIÓN DEL NOMBRAMIENTO</div>
<table class="form-table">
    <tr>
        <th width="50%">SUSTITUYE A:</th>
        <td width="50%"><input type="text" name="sustituye_a" value="<?php echo htmlspecialchars($row['sustituye_a']); ?>"></td>
    </tr>
    <tr>
        <th>IDENTIDAD N°:</th>
        <td><input type="text" name="identidad_sustituido" value="<?php echo $row['identidad_sustituido']; ?>"></td>
    </tr>
    <tr>
        <th>¿POR QUÉ QUEDO VACANTE EL CARGO?</th>
        <td><textarea name="motivo_vacante"><?php echo htmlspecialchars($row['motivo_vacante']); ?></textarea></td>
    </tr>
</table>

<!-- FIRMAS QUE VALIDAN ESTE DOCUMENTO -->
<div class="section-title">FIRMAS QUE VALIDAN ESTE DOCUMENTO</div>
<table class="form-table">
    <tr>
        <th width="50%">FIRMA DEL DOCENTE</th>
        <td width="50%"><input type="text" name="firma_docente" value="<?php echo htmlspecialchars($row['firma_docente']); ?>"></td>
    </tr>
    <tr>
        <th>NÚMERO DE TELÉFONO</th>
        <td><input type="text" name="telefono_contacto" value="<?php echo $row['telefono_contacto']; ?>"></td>
    </tr>
    <tr>
        <th>VºBº DIRECTOR(A) DEL CENTRO EDUCATIVO</th>
        <td><input type="text" name="vb_director_centro" value="<?php echo htmlspecialchars($row['vb_director_centro']); ?>"></td>
    </tr>
    <tr>
        <th>VºBº DIRECTOR(A) DISTRITAL O MUNICIPAL</th>
        <td><input type="text" name="vb_director_distrital" value="<?php echo htmlspecialchars($row['vb_director_distrital']); ?>"></td>
    </tr>
    <tr>
        <th>(ACCIONES) FIRMA DE QUIEN REVISA</th>
        <td><input type="text" name="firma_revisa" value="<?php echo htmlspecialchars($row['firma_revisa']); ?>"></td>
    </tr>
    <tr>
        <th>FECHA DE ENTREGA</th>
        <td><input type="date" name="fecha_entrega" value="<?php echo $row['fecha_entrega']; ?>"></td>
    </tr>
</table>

<button type="submit" class="btn-submit">ACTUALIZAR NOMBRAMIENTO</button>
</form>
</div>

</body>
</html>
<?php include "../../includes/footer.php"; ?>