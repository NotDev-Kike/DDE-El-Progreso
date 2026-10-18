<?php
include '../../includes/db.php';

// ===================== VALIDAR ID FORMATO =====================
$id_formato = $_POST['id_formato'] ?? 0;
$id_formato = intval($id_formato);

if ($id_formato <= 0) {
    die("Error: ID de formato inválido o no recibido.");
}

// ===================== VERIFICAR SI EXISTE EL REGISTRO =====================
$stmt = $conn->prepare("SELECT id FROM formato_nombramiento_media WHERE id = ?");
$stmt->bind_param("i", $id_formato);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Error al modificar el nombramiento: El registro principal no existe.");
}
$stmt->close();

// ===================== OBTENER DATOS DEL FORMULARIO =====================
$tipo_nombramiento      = $_POST['tipo_nombramiento'] ?? '';
$nombre_docente         = $_POST['nombre_docente'] ?? '';
$identidad              = $_POST['identidad'] ?? '';
$sdgthd                 = $_POST['sdgthd'] ?? '';
$colegio_magisterial    = $_POST['colegio_magisterial'] ?? '';
$colegiacion_numero     = $_POST['colegiacion_numero'] ?? '';
$jornada_trabajo        = $_POST['jornada_trabajo'] ?? '';
$requisito              = $_POST['requisito'] ?? '';
$fecha_inicio           = $_POST['fecha_inicio'] ?? null;
$fecha_vencimiento      = $_POST['fecha_vencimiento'] ?? null;
$numero_horas           = intval($_POST['numero_horas'] ?? 0);
$centro_cuenta          = $_POST['centro_cuenta'] ?? '';
$cargo_cuenta           = $_POST['cargo_cuenta'] ?? '';
$lugar_cuenta           = $_POST['lugar_cuenta'] ?? '';
$municipio_cuenta       = $_POST['municipio_cuenta'] ?? '';
$centro_reubicacion     = $_POST['centro_reubicacion'] ?? '';
$cargo_reubicacion      = $_POST['cargo_reubicacion'] ?? '';
$lugar_reubicacion      = $_POST['lugar_reubicacion'] ?? '';
$municipio_reubicacion  = $_POST['municipio_reubicacion'] ?? '';
$sustituye_a            = $_POST['sustituye_a'] ?? '';
$identidad_sustituido   = $_POST['identidad_sustituido'] ?? '';
$motivo_vacante         = $_POST['motivo_vacante'] ?? '';
$firma_docente          = $_POST['firma_docente'] ?? '';
$vb_director_centro     = $_POST['vb_director_centro'] ?? '';
$firma_revisa           = $_POST['firma_revisa'] ?? '';
$telefono_contacto      = $_POST['telefono_contacto'] ?? '';
$vb_director_distrital  = $_POST['vb_director_distrital'] ?? '';
$fecha_entrega          = $_POST['fecha_entrega'] ?? null;

// ===================== ACTUALIZAR REGISTRO PRINCIPAL =====================
$query = "UPDATE formato_nombramiento_media SET
    tipo_nombramiento=?, nombre_docente=?, identidad=?, sdgthd=?, colegio_magisterial=?, 
    colegiacion_numero=?, jornada_trabajo=?, requisito=?, fecha_inicio=?, fecha_vencimiento=?, 
    numero_horas=?, centro_cuenta=?, cargo_cuenta=?, lugar_cuenta=?, municipio_cuenta=?, 
    centro_reubicacion=?, cargo_reubicacion=?, lugar_reubicacion=?, municipio_reubicacion=?, 
    sustituye_a=?, identidad_sustituido=?, motivo_vacante=?, firma_docente=?, 
    vb_director_centro=?, firma_revisa=?, telefono_contacto=?, vb_director_distrital=?, 
    fecha_entrega=? 
    WHERE id=?";

$stmt = $conn->prepare($query);
$stmt->bind_param(
    "ssssssssssisssssssssssssssssi",
    $tipo_nombramiento,
    $nombre_docente,
    $identidad,
    $sdgthd,
    $colegio_magisterial,
    $colegiacion_numero,
    $jornada_trabajo,
    $requisito,
    $fecha_inicio,
    $fecha_vencimiento,
    $numero_horas,
    $centro_cuenta,
    $cargo_cuenta,
    $lugar_cuenta,
    $municipio_cuenta,
    $centro_reubicacion,
    $cargo_reubicacion,
    $lugar_reubicacion,
    $municipio_reubicacion,
    $sustituye_a,
    $identidad_sustituido,
    $motivo_vacante,
    $firma_docente,
    $vb_director_centro,
    $firma_revisa,
    $telefono_contacto,
    $vb_director_distrital,
    $fecha_entrega,
    $id_formato
);

if (!$stmt->execute()) {
    die("Error al actualizar formato principal: " . $stmt->error);
}
$stmt->close();

// ===================== LIMPIAR Y VOLVER A INSERTAR TABLAS HIJAS =====================

// 1️⃣ ESTRUCTURA PRESUPUESTARIA
$conn->query("DELETE FROM estructura_presupuestaria_nombramiento_media WHERE id_formato = $id_formato");

if (isset($_POST['estructura_inst'])) {
    $stmt_estructura = $conn->prepare("INSERT INTO estructura_presupuestaria_nombramiento_media (id_formato, estructura_inst, estructura_programa, estructura_subprograma, estructura_fondo) VALUES (?, ?, ?, ?, ?)");
    foreach ($_POST['estructura_inst'] as $i => $inst) {
        $inst = $_POST['estructura_inst'][$i] ?? '';
        $prog = $_POST['estructura_programa'][$i] ?? '';
        $subp = $_POST['estructura_subprograma'][$i] ?? '';
        $fondo = $_POST['estructura_fondo'][$i] ?? '';
        $stmt_estructura->bind_param("issss", $id_formato, $inst, $prog, $subp, $fondo);
        $stmt_estructura->execute();
    }
    $stmt_estructura->close();
}

// 2️⃣ SIARHD
$conn->query("DELETE FROM siarhd_estructura_nombramiento_media WHERE id_formato = $id_formato");

if (isset($_POST['siarhd_depto'])) {
    $stmt_siarhd = $conn->prepare("INSERT INTO siarhd_estructura_nombramiento_media (id_formato, siarhd_depto, siarhd_muni, siarhd_centro, siarhd_num_plaza, siarhd_num_horas) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($_POST['siarhd_depto'] as $i => $dep) {
        $dep   = $_POST['siarhd_depto'][$i] ?? '';
        $muni  = $_POST['siarhd_muni'][$i] ?? '';
        $centro = $_POST['siarhd_centro'][$i] ?? '';
        $plaza  = $_POST['siarhd_num_plaza'][$i] ?? '';
        $horas  = intval($_POST['siarhd_num_horas'][$i] ?? 0);
        $stmt_siarhd->bind_param("issssi", $id_formato, $dep, $muni, $centro, $plaza, $horas);
        $stmt_siarhd->execute();
    }
    $stmt_siarhd->close();
}

// 3️⃣ CARGA ACADÉMICA
$conn->query("DELETE FROM carga_academica_nombramiento_media WHERE id_formato = $id_formato");

if (isset($_POST['asignatura'])) {
    $stmt_carga = $conn->prepare("INSERT INTO carga_academica_nombramiento_media (id_formato, asignatura, horas, modalidad, cursos, grupos, jornada_academica) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($_POST['asignatura'] as $i => $asig) {
        $asig = $_POST['asignatura'][$i] ?? '';
        $horas = intval($_POST['horas'][$i] ?? 0);
        $modal = $_POST['modalidad'][$i] ?? '';
        $curso = $_POST['cursos'][$i] ?? '';
        $grupo = $_POST['grupos'][$i] ?? '';
        $jorna = $_POST['jornada'][$i] ?? '';
        $stmt_carga->bind_param("isissss", $id_formato, $asig, $horas, $modal, $curso, $grupo, $jorna);
        $stmt_carga->execute();
    }
    $stmt_carga->close();
}

// ===================== MENSAJE FINAL =====================
echo "<script>alert('✅ Nombramiento modificado correctamente.'); window.location.href='listar.php';</script>";

$conn->close();
?>
