<?php
include "../includes/header.php";
include "../includes/db.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $codigo = trim($_POST['codigo']);
    $nivel = trim($_POST['nivel']);
    $tipo_plaza = $_POST['tipo_plaza'];
    $id_docente = !empty($_POST['id_docente']) ? intval($_POST['id_docente']) : null;

    $stmt = $conn->prepare("INSERT INTO estructuras (codigo, nivel, tipo_plaza, id_docente) VALUES (?,?,?,?)");
    $stmt->bind_param("sssi", $codigo, $nivel, $tipo_plaza, $id_docente);
    if($stmt->execute()){
        header("Location: listar.php");
        exit;
    } else {
        $error = $stmt->error;
    }
}
?>

<h2>Agregar Estructura</h2>
<form method="post" style="max-width:600px;">
    <label>Código:</label>
    <input type="text" name="codigo" required>

    <label>Nivel:</label>
    <input type="text" name="nivel">

    <label>Tipo de Plaza:</label>
    <select name="tipo_plaza">
        <option value="NOMBRAMIENTO">NOMBRAMIENTO</option>
        <option value="TRASLADO">TRASLADO</option>
        <option value="CANCELACION">CANCELACION</option>
    </select>

    <label>Asignar a (opcional):</label>
    <select name="id_docente">
        <option value="">-- Ninguno --</option>
        <?php
        $r = $conn->query("SELECT id_docente, nombre_docente FROM docentes ORDER BY nombre_docente");
        while($d = $r->fetch_assoc()){
            echo "<option value='{$d['id_docente']}'>".htmlspecialchars($d['nombre_docente'])."</option>";
        }
        ?>
    </select>

    <button type="submit" style="background:#28a745; color:#fff; padding:8px 12px; border:none; border-radius:6px; margin-top:10px;">Guardar</button>
</form>

<?php include "../includes/footer.php"; ?>
