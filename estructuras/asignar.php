<?php
include "../includes/header.php";
include "../includes/db.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!$id){
    header("Location: listar.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id_docente = isset($_POST['id_docente']) ? intval($_POST['id_docente']) : null;
    $stmt = $conn->prepare("UPDATE estructuras SET id_docente = ? WHERE id_estructura = ?");
    $stmt->bind_param("ii", $id_docente, $id);
    $stmt->execute();
    header("Location: listar.php");
    exit;
}

// obtener estructura
$res = $conn->prepare("SELECT id_estructura, codigo, id_docente FROM estructuras WHERE id_estructura = ?");
$res->bind_param("i",$id);
$res->execute();
$estructura = $res->get_result()->fetch_assoc();
$res->close();
?>

<h2>Asignar Estructura <?= htmlspecialchars($estructura['codigo']); ?></h2>

<form method="post">
    <label>Docente:</label>
    <select name="id_docente" required>
        <option value="">Seleccionar...</option>
        <?php
        $r = $conn->query("SELECT id_docente, nombre_docente FROM docentes ORDER BY nombre_docente");
        while($d = $r->fetch_assoc()){
            $sel = ($estructura['id_docente'] == $d['id_docente']) ? 'selected' : '';
            echo "<option value='{$d['id_docente']}' $sel>".htmlspecialchars($d['nombre_docente'])."</option>";
        }
        ?>
    </select>
    <button type="submit" style="background:#28a745; color:#fff; padding:8px 12px;">Asignar</button>
</form>

<?php include "../includes/footer.php"; ?>
