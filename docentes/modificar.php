<?php
include "../includes/db.php";
include "../includes/header.php";

if(!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id = $_GET['id'];

$res = $conn->prepare("SELECT * FROM docentes WHERE id_docente=?");
$res->bind_param("i", $id);
$res->execute();
$result = $res->get_result();
$docente = $result->fetch_assoc();

if(!$docente) {
    echo "<p>Docente no encontrado.</p>";
    exit;
}

if(isset($_POST['guardar'])){
    $nombre = $_POST['nombre'];
    $identidad = $_POST['identidad'];

    try {
        $stmt = $conn->prepare("UPDATE docentes SET nombre_docente=?, identidad=? WHERE id_docente=?");
        $stmt->bind_param("sssi", $nombre, $identidad, $id);

        if($stmt->execute()){
            header("Location: listar.php");
            exit;
        } else {
            throw new Exception($stmt->error);
        }

    } catch (Exception $e) {
        echo "<script>alert('Error: Ya existe un docente con esa identidad o hubo un problema al guardar.');</script>";
    }
}
?>

<h2>Modificar Docente</h2>

<form method="post" style="max-width:500px; margin:auto;">
    <label>Nombre del Docente:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($docente['nombre_docente']); ?>" required>

    <label>Identidad:</label>
    <input type="text" name="identidad" value="<?= htmlspecialchars($docente['identidad']); ?>" required>

    <button type="submit" name="guardar" style="background-color:#ffc107; color:#fff; padding:10px 20px; border:none; border-radius:5px; margin-top:10px;">Guardar Cambios</button>
</form>

<div style="text-align:center; margin-top:15px;">
    <a class="button" href="listar.php" style="background-color:#0066cc;">Volver</a>
</div>

<?php include "../includes/footer.php"; ?>