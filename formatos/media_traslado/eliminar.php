<?php
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Iniciar transacción para eliminar en todas las tablas
    $conn->begin_transaction();
    
    try {
        // Eliminar de tablas hijas primero (por las claves foráneas)
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
        
        // Finalmente eliminar de la tabla principal
        $sql_principal = "DELETE FROM formato_traslado_media WHERE id = ?";
        $stmt = $conn->prepare($sql_principal);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $conn->commit();
            header("Location: listar.php?deleted=1");
        } else {
            throw new Exception("Error al eliminar el registro principal");
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error al eliminar: " . $e->getMessage();
    }
} else {
    header("Location: listar.php");
}
?>