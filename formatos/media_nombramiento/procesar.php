<?php
include '../../includes/db.php';

// ===================== DATOS DEL FORMULARIO PRINCIPAL =====================
$tipo_nombramiento       = $_POST['tipo_nombramiento'] ?? '';
$nombre_docente          = $_POST['nombre_docente'] ?? '';
$identidad               = $_POST['identidad'] ?? '';
$sdgthd                  = $_POST['sdgthd'] ?? '';
$colegio_magisterial     = $_POST['colegio_magisterial'] ?? '';
$colegiacion_numero      = $_POST['colegiacion_numero'] ?? '';
$jornada_trabajo         = $_POST['jornada_trabajo'] ?? '';
$requisito               = $_POST['requisito'] ?? '';
$fecha_inicio            = $_POST['fecha_inicio'] ?? null;
$fecha_vencimiento       = $_POST['fecha_vencimiento'] ?? null;
$numero_horas            = $_POST['numero_horas'] ?? 0;
$centro_cuenta           = $_POST['centro_cuenta'] ?? '';
$cargo_cuenta            = $_POST['cargo_cuenta'] ?? '';
$lugar_cuenta            = $_POST['lugar_cuenta'] ?? '';
$municipio_cuenta        = $_POST['municipio_cuenta'] ?? '';
$centro_reubicacion      = $_POST['centro_reubicacion'] ?? '';
$cargo_reubicacion       = $_POST['cargo_reubicacion'] ?? '';
$lugar_reubicacion       = $_POST['lugar_reubicacion'] ?? '';
$municipio_reubicacion   = $_POST['municipio_reubicacion'] ?? '';
$sustituye_a             = $_POST['sustituye_a'] ?? '';
$identidad_sustituido    = $_POST['identidad_sustituido'] ?? '';
$motivo_vacante          = $_POST['motivo_vacante'] ?? '';
$firma_docente           = $_POST['firma_docente'] ?? '';
$telefono_contacto       = $_POST['telefono_contacto'] ?? '';
$vb_director_centro      = $_POST['vb_director_centro'] ?? '';
$vb_director_distrital   = $_POST['vb_director_distrital'] ?? '';
$firma_revisa            = $_POST['firma_revisa'] ?? '';
$fecha_entrega           = $_POST['fecha_entrega'] ?? null;

// ===================== INSERTAR FORMATO PRINCIPAL =====================
$stmt = $conn->prepare("
    INSERT INTO formato_nombramiento_media 
    (tipo_nombramiento, nombre_docente, identidad, sdgthd, colegio_magisterial, colegiacion_numero, jornada_trabajo, requisito, fecha_inicio, fecha_vencimiento, numero_horas, centro_cuenta, cargo_cuenta, lugar_cuenta, municipio_cuenta, centro_reubicacion, cargo_reubicacion, lugar_reubicacion, municipio_reubicacion, sustituye_a, identidad_sustituido, motivo_vacante, firma_docente, telefono_contacto, vb_director_centro, vb_director_distrital, firma_revisa, fecha_entrega)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "ssssssssssisssssssssssssssss",
    $tipo_nombramiento, $nombre_docente, $identidad, $sdgthd, $colegio_magisterial, $colegiacion_numero, $jornada_trabajo, $requisito, $fecha_inicio, $fecha_vencimiento, $numero_horas, $centro_cuenta, $cargo_cuenta, $lugar_cuenta, $municipio_cuenta, $centro_reubicacion, $cargo_reubicacion, $lugar_reubicacion, $municipio_reubicacion, $sustituye_a, $identidad_sustituido, $motivo_vacante, $firma_docente, $telefono_contacto, $vb_director_centro, $vb_director_distrital, $firma_revisa, $fecha_entrega
);

if ($stmt->execute()) {
    $id_formato = $stmt->insert_id; // ID del registro principal

    // ===================== CARGA ACADÉMICA =====================
    $asignaturas = $_POST['asignatura'] ?? [];
    $horas       = $_POST['horas'] ?? [];
    $modalidad   = $_POST['modalidad'] ?? [];
    $cursos      = $_POST['cursos'] ?? [];
    $grupos      = $_POST['grupos'] ?? [];
    $jornadas    = $_POST['jornada'] ?? [];

    if (count($asignaturas) > 0) {
        $stmt2 = $conn->prepare("
            INSERT INTO carga_academica_nombramiento_media 
            (id_formato, asignatura, horas, modalidad, cursos, grupos, jornada_academica)
            VALUES (?,?,?,?,?,?,?)
        ");

        for ($i = 0; $i < count($asignaturas); $i++) {
            $asig = trim($asignaturas[$i]);
            $hora = $horas[$i] ?? 0;
            $mod  = trim($modalidad[$i] ?? '');
            $cur  = trim($cursos[$i] ?? '');
            $gru  = trim($grupos[$i] ?? '');
            $jor  = trim($jornadas[$i] ?? '');

            if ($asig === '' && $hora == 0 && $mod === '' && $cur === '' && $gru === '' && $jor === '') continue;

            $stmt2->bind_param("issssss", $id_formato, $asig, $hora, $mod, $cur, $gru, $jor);
            $stmt2->execute();
        }
        $stmt2->close();
    }

    // ===================== SIARHD =====================
    $siarhd_depto     = $_POST['siarhd_depto'] ?? [];
    $siarhd_muni      = $_POST['siarhd_muni'] ?? [];
    $siarhd_centro    = $_POST['siarhd_centro'] ?? [];
    $siarhd_num_plaza = $_POST['siarhd_num_plaza'] ?? [];
    $siarhd_num_horas = $_POST['siarhd_num_horas'] ?? [];

    if (count($siarhd_depto) > 0) {
        $stmt3 = $conn->prepare("
            INSERT INTO siarhd_estructura_nombramiento_media
            (id_formato, siarhd_depto, siarhd_muni, siarhd_centro, siarhd_num_plaza, siarhd_num_horas)
            VALUES (?,?,?,?,?,?)
        ");

        for ($i = 0; $i < count($siarhd_depto); $i++) {
            $dep  = trim($siarhd_depto[$i] ?? '');
            $mun  = trim($siarhd_muni[$i] ?? '');
            $cen  = trim($siarhd_centro[$i] ?? '');
            $npla = trim($siarhd_num_plaza[$i] ?? '');
            $nhor = $siarhd_num_horas[$i] ?? 0;

            if ($dep === '' && $mun === '' && $cen === '' && $npla === '' && $nhor == 0) continue;

            $stmt3->bind_param("issssi", $id_formato, $dep, $mun, $cen, $npla, $nhor);
            $stmt3->execute();
        }
        $stmt3->close();
    }

    // ===================== ESTRUCTURA PRESUPUESTARIA =====================
    $estructura_inst        = $_POST['estructura_inst'] ?? [];
    $estructura_programa    = $_POST['estructura_programa'] ?? [];
    $estructura_subprograma = $_POST['estructura_subprograma'] ?? [];
    $estructura_fondo       = $_POST['estructura_fondo'] ?? [];

    if (count($estructura_inst) > 0) {
        $stmt4 = $conn->prepare("
            INSERT INTO estructura_presupuestaria_nombramiento_media
            (id_formato, estructura_inst, estructura_programa, estructura_subprograma, estructura_fondo)
            VALUES (?,?,?,?,?)
        ");

        for ($i = 0; $i < count($estructura_inst); $i++) {
            $inst = trim($estructura_inst[$i] ?? '');
            $prog = trim($estructura_programa[$i] ?? '');
            $sub  = trim($estructura_subprograma[$i] ?? '');
            $fond = trim($estructura_fondo[$i] ?? '');

            if ($inst === '' && $prog === '' && $sub === '' && $fond === '') continue;

            $stmt4->bind_param("issss", $id_formato, $inst, $prog, $sub, $fond);
            $stmt4->execute();
        }
        $stmt4->close();
    }

    // ===================== MENSAJE Y REDIRECCIÓN =====================
    echo "<script>
        alert('✅ Nombramiento guardado correctamente.');
        window.location.href = 'agregar.php';
    </script>";

} else {
    echo "<script>
        alert('❌ Error al guardar el nombramiento: " . addslashes($stmt->error) . "');
        window.history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>
