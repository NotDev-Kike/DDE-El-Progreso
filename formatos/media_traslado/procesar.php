<?php
include '../../includes/db.php';

if ($_POST && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Iniciar transacción
    $conn->begin_transaction();
    
    try {
        // Actualizar datos principales
        $sql_principal = "UPDATE formato_traslado_media SET 
            tipo_nombramiento = ?, nombre_docente = ?, identidad = ?, colegio_magisterial = ?, 
            sdgthd = ?, colegiacion_numero = ?, jornada_trabajo = ?, requisito = ?, 
            fecha_inicio = ?, fecha_vencimiento = ?, numero_horas = ?
            WHERE id = ?";
        
        $stmt = $conn->prepare($sql_principal);
        $stmt->bind_param(
            "sssssssssssi",
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
            $_POST['numero_horas'],
            $id
        );
        $stmt->execute();
        $stmt->close();
        
        // Eliminar datos existentes de tablas hijas
        $tablas_hijas = [
            'centros_educativos_media',
            'estructura_presupuestaria_media', 
            'estructura_siarhd_media',
            'justificacion_media',
            'carga_academica_media',
            'firmas_validacion_media'
        ];
        
        foreach ($tablas_hijas as $tabla) {
            $sql = "DELETE FROM $tabla WHERE traslado_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
        
        // Reinsertar datos en tablas hijas (usando las mismas funciones de agregar.php)
        include 'agregar.php'; // Para tener acceso a las funciones
        
        insertarCentrosMedia($conn, $id, $_POST);
        insertarEstructuraPresupuestariaMedia($conn, $id, $_POST);
        insertarEstructuraSiarhdMedia($conn, $id, $_POST);
        insertarJustificacionMedia($conn, $id, $_POST);
        insertarCargaAcademicaMedia($conn, $id, $_POST);
        insertarFirmasMedia($conn, $id, $_POST);
        
        $conn->commit();
        header("Location: listar.php?updated=1");
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error al actualizar: " . $e->getMessage();
    }
} else {
    header("Location: listar.php");
}
?>