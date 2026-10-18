<?php
include "../includes/header.php";
include "../includes/db.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id<=0){ header("Location:listar.php"); exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $codigo = trim($_POST['codigo']);
    $nivel = trim($_POST['nivel']);
    $tipo_plaza = $_POST['tipo_plaza'];
    $stmt = $conn->prepare("UPDATE estructuras SET codigo=?, nivel=?, tipo_plaza=? WHERE id_estructura=?");
    $stmt->bind_param("sssi", $codigo, $nivel, $tipo_plaza, $id);
    $stmt->execute();
    header("Location: listar.php");
    exit;
}

$res = $conn->prepare("SELECT * FROM estructuras WHERE id_estructura = ?");
$res->bind_param("i",$id);
$res->execute();
$e = $res->get_result()->fetch_assoc();
$res->close();
?>

<h2>Modificar Estructura <?= htmlspecialchars($e['codigo']); ?></h2>
<form method="post">
    <label>Código:</label><input type="text" name="codigo" value="<?= htmlspecialchars($e['codigo']); ?>" required>
    <label>Nivel:</label><input type="text" name="nivel" value="<?= htmlspecialchars($e['nivel']); ?>">
    <label>Tipo Plaza:</label>
    <select name="tipo_plaza">
        <option value="NOMBRAMIENTO" <?= $e['tipo_plaza']=='NOMBRAMIENTO'?'selected':'' ?>>NOMBRAMIENTO</option>
        <option value="TRASLADO" <?= $e['tipo_plaza']=='TRASLADO'?'selected':'' ?>>TRASLADO</option>
        <option value="CANCELACION" <?= $e['tipo_plaza']=='CANCELACION'?'selected':'' ?>>CANCELACION</option>
    </select>
    <button type="submit" style="background:#ffc107; color:#000; padding:8px 12px; border:none; border-radius:6px; margin-top:8px;">Guardar</button>
</form>

<?php include "../includes/footer.php"; ?>
