<?php include "../../includes/header.php"; ?>
<?php include "../../includes/db.php"; ?>

<?php
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $conn->begin_transaction();
        
        // Eliminar registros relacionados primero (debido a las claves foráneas)
        $tablas_relacionadas = [
            'formato_ciclo_cancelacion',
            'formato_asignatura_cancelacion',
            'formato_estructura_presupuestaria_cancelacion',
            'formato_suspension_cancelacion',
            'formato_cancelacion_detalle',
            'formato_destitucion_cancelacion'
        ];
        
        foreach ($tablas_relacionadas as $tabla) {
            $sql = "DELETE FROM $tabla WHERE formato_cancelacion_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
        
        // Eliminar registro principal
        $sql = "DELETE FROM formato_cancelacion WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        $conn->commit();
        
        echo "<script>alert('✅ Formato eliminado correctamente'); window.location.href='listar.php';</script>";
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('❌ Error al eliminar: " . $e->getMessage() . "'); window.location.href='listar.php';</script>";
    }
} else {
    echo "<script>alert('ID no válido'); window.location.href='listar.php';</script>";
}

$conn->close();
?>

<?php include "../../includes/footer.php"; ?>