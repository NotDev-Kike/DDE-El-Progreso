<?php
include "../includes/db.php";
include "../includes/header.php";

if(!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM nombramientos WHERE id_nombramiento = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    echo "<p>Nombramiento no encontrado.</p>";
    echo '<a class="button" href="listar.php" style="background-color:#0066cc;">Volver</a>';
    include "../includes/footer.php";
    exit;
}

$nombramiento = $result->fetch_assoc();

if(isset($_POST['guardar'])){
    $nombre = $_POST['nombre'];
    $stmt = $conn->prepare("UPDATE nombramientos SET nombre_nombramiento = ? WHERE id_nombramiento = ?");
    $stmt->bind_param("si", $nombre, $id);
    $stmt->execute();
    header("Location: listar.php");
    exit;
}
?>

<h2>Modificar Nombramiento</h2>

<form method="post" style="max-width:400px; margin:auto;">
    <label>Nombre del Nombramiento:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($nombramiento['nombre_nombramiento']); ?>" required>
    
    <button type="submit" name="guardar" style="background-color:#ffc107; color:#fff; padding:10px 20px; border:none; border-radius:5px; margin-top:10px;">Guardar Cambios</button>
</form>

<div style="text-align:center; margin-top:15px;">
    <a class="button" href="listar.php" style="background-color:#0066cc;">Volver</a>
</div>

<?php include "../includes/footer.php"; ?>