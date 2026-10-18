<?php
include "../includes/db.php";
include "../includes/header.php";

if(!isset($_GET['id'])){
    header("Location:listar.php");
    exit;
}

$id = $_GET['id'];
$res = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario=?");
$res->bind_param("i",$id);
$res->execute();
$usuario = $res->get_result()->fetch_assoc();
$res->close();

$mensaje = "";
$tipo = "";

if(isset($_POST['guardar'])){
    $username = trim($_POST['usuario']);
    $nombre_completo = trim($_POST['nombre_completo']);
    $rol = $_POST['rol'] === 'administrador' ? 'admin' : 'usuario';
    $contrasena = trim($_POST['contrasena']);

    $verificar = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario=? AND id_usuario<>?");
    $verificar->bind_param("si", $username, $id);
    $verificar->execute();
    $verificar->bind_result($existe);
    $verificar->fetch();
    $verificar->close();

    if($existe > 0){
        $mensaje = "El nombre de usuario '$username' ya está en uso. Por favor elija otro.";
        $tipo = "warning";
    } else {
        if(!empty($contrasena)){
            $hashed_pass = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios SET usuario=?, nombre_completo=?, rol=?, contrasena=? WHERE id_usuario=?");
            $stmt->bind_param("ssssi", $username, $nombre_completo, $rol, $hashed_pass, $id);
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET usuario=?, nombre_completo=?, rol=? WHERE id_usuario=?");
            $stmt->bind_param("sssi", $username, $nombre_completo, $rol, $id);
        }

        if($stmt->execute()){
            $mensaje="Usuario actualizado correctamente.";
            $tipo="success";
        } else {
            $mensaje="Error al actualizar el usuario.";
            $tipo="error";
        }
        $stmt->close();
    }
}
?>

<h2 style="text-align:center;">Modificar Usuario</h2>

<form method="post" style="max-width:400px; margin:auto; background:#f9f9f9; padding:20px; border-radius:10px;">
    <label>Usuario:</label>
    <input type="text" name="usuario" value="<?= htmlspecialchars($usuario['usuario']); ?>" required style="width:100%; padding:8px; margin-bottom:10px;">

    <label>Nombre Completo:</label>
    <input type="text" name="nombre_completo" value="<?= htmlspecialchars($usuario['nombre_completo']); ?>" required style="width:100%; padding:8px; margin-bottom:10px;">

    <label>Rol:</label>
    <select name="rol" required style="width:100%; padding:8px; margin-bottom:10px;">
        <option value="administrador" <?= $usuario['rol']=='admin'?'selected':'' ?>>Administrador</option>
        <option value="usuario" <?= $usuario['rol']=='usuario'?'selected':'' ?>>Usuario</option>
    </select>

    <label>Contraseña (dejar en blanco si no desea cambiarla):</label>
    <input type="password" name="contrasena" placeholder="Nueva contraseña" style="width:100%; padding:8px; margin-bottom:15px;">

    <button type="submit" name="guardar" style="background-color:#ffc107; color:#fff; padding:10px 20px; border:none; border-radius:5px;">Guardar Cambios</button>
</form>

<div style="text-align:center; margin-top:15px;">
    <a class="button" href="listar.php" style="background-color:#0066cc;">← Volver</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if($mensaje != ""): ?>
<script>
Swal.fire({
    title: '<?= $tipo==="success"?"Éxito":($tipo==="warning"?"Advertencia":"Error") ?>',
    text: '<?= $mensaje ?>',
    icon: '<?= $tipo==="success"?"success":($tipo==="warning"?"warning":"error") ?>',
    confirmButtonColor: '<?= $tipo==="success"?"#28a745":"#d33" ?>'
}).then((result)=>{
    <?php if($tipo==="success"): ?>
        window.location.href = 'listar.php';
    <?php endif; ?>
});
</script>
<?php endif; ?>

<?php include "../includes/footer.php"; ?>
