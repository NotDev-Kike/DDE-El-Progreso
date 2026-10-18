<?php
include "../includes/db.php";

if(!isset($_GET['id'])) {
    echo "<script>alert('ID no válido.'); window.location='listar.php';</script>";
    exit;
}

$id = $_GET['id'];

$stmt_check = $conn->prepare("SELECT * FROM docentes WHERE id_docente=?");
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if($result->num_rows === 0){
    echo "<script>alert('Docente no encontrado.'); window.location='listar.php';</script>";
    exit;
}

$stmt = $conn->prepare("DELETE FROM docentes WHERE id_docente=?");
$stmt->bind_param("i", $id);

if($stmt->execute()){
    echo "<script>alert('Docente eliminado correctamente.'); window.location='listar.php';</script>";
    exit;
} else {
    echo "<script>alert('Error al eliminar el docente.'); window.location='listar.php';</script>";
    exit;
}
?>