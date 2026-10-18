<?php include "../../includes/header.php"; ?>
<?php include "../../includes/db.php"; ?>

<div style="margin-bottom: 15px; text-align: center;">
    <a href="javascript:void(0);" onclick="window.history.back();" 
       style="display: inline-block; background-color: #555; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; margin-right: 8px; font-weight: bold;">
        🔙 Volver Atrás
    </a>
    <a href="../../menu.php" 
       style="display: inline-block; background-color: #6c757d; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; margin-right: 8px; font-weight: bold;">
        🏠 Volver al Menú
    </a>
    <a href="listar.php" 
       style="display: inline-block; background-color: #17a2b8; color: white; padding: 10px 18px; 
              border-radius: 6px; text-decoration: none; font-weight: bold;">
        📋 Ver Lista
    </a>
</div>

<h1 style="text-align:center; color:#dc3545;">🗑️ Eliminar Nombramiento</h1>

<style>
.alert-container {
    background: white;
    border-radius: 10px;
    padding: 30px;
    max-width: 600px;
    margin: 20px auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    text-align: center;
    border-left: 5px solid #dc3545;
}
.btn-container {
    margin: 20px 0;
}
.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    display: inline-block;
    margin: 0 10px;
    transition: all 0.3s ease;
}
.btn-danger {
    background: #dc3545;
    color: white;
}
.btn-danger:hover {
    background: #c82333;
    transform: translateY(-2px);
}
.btn-secondary {
    background: #6c757d;
    color: white;
}
.btn-secondary:hover {
    background: #545b62;
    transform: translateY(-2px);
}
.info-box {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin: 15px 0;
    text-align: left;
}
</style>

<?php
// Obtener el ID del registro a eliminar
$id = $_GET['id'] ?? 0;

if ($id == 0) {
    echo "<script>alert('ID no válido'); window.location.href='listar.php';</script>";
    exit;
}

// Obtener información del nombramiento para mostrar
$sql = "SELECT fnb.*, bcc.centro_educativo as centro_cuenta 
        FROM formato_nombramiento_basica fnb
        LEFT JOIN basica_centro_cuenta bcc ON fnb.id = bcc.nombramiento_id
        WHERE fnb.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Nombramiento no encontrado'); window.location.href='listar.php';</script>";
    exit;
}

$nombramiento = $result->fetch_assoc();

// Procesar la eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar'])) {
    try {
        // Iniciar transacción
        $conn->begin_transaction();
        
        // Eliminar registros de tablas hijas (se eliminarán en cascada por la restricción FOREIGN KEY)
        $sql_delete = "DELETE FROM formato_nombramiento_basica WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);
        
        if ($stmt_delete->execute()) {
            $conn->commit();
            echo "<script>alert('✅ Nombramiento eliminado correctamente'); window.location.href='listar.php?deleted=1';</script>";
        } else {
            throw new Exception("Error al eliminar el nombramiento");
        }
        
        $stmt_delete->close();
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('❌ Error al eliminar el nombramiento: " . $e->getMessage() . "'); window.location.href='listar.php?error=1';</script>";
    }
}

$conn->close();
?>

<div class="alert-container">
    <div style="font-size: 48px; color: #dc3545; margin-bottom: 20px;">⚠️</div>
    <h2 style="color: #dc3545;">¿Está seguro de eliminar este nombramiento?</h2>
    
    <div class="info-box">
        <p><strong>ID:</strong> <?php echo $nombramiento['id']; ?></p>
        <p><strong>Nombre del Docente:</strong> <?php echo htmlspecialchars($nombramiento['nombre_docente']); ?></p>
        <p><strong>Identidad:</strong> <?php echo $nombramiento['identidad']; ?></p>
        <p><strong>Centro Educativo:</strong> <?php echo htmlspecialchars($nombramiento['centro_cuenta']); ?></p>
        <p><strong>Tipo de Nombramiento:</strong> <?php echo $nombramiento['tipo_nombramiento']; ?></p>
        <p><strong>Fecha de Inicio:</strong> <?php echo $nombramiento['fecha_inicio']; ?></p>
    </div>
    
    <p style="color: #666; margin: 20px 0;">
        <strong>Advertencia:</strong> Esta acción no se puede deshacer. Se eliminarán todos los datos relacionados con este nombramiento.
    </p>
    
    <div class="btn-container">
        <form method="POST" style="display: inline;">
            <button type="submit" name="confirmar" class="btn btn-danger" onclick="return confirm('¿Está completamente seguro de eliminar este nombramiento?')">
                🗑️ Sí, Eliminar
            </button>
        </form>
        <a href="listar.php" class="btn btn-secondary">❌ Cancelar</a>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>