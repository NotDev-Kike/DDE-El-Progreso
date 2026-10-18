<?php
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Eliminar en cascada debido a las foreign keys
    $sql = "DELETE FROM formato_traslado_basica WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: listar.php?deleted=1");
    } else {
        header("Location: listar.php?error=1");
    }
} else {
    header("Location: listar.php");
}
exit();
?>