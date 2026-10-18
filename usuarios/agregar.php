<?php
include "../includes/db.php";
include "../includes/header.php";

$mensaje = "";
$tipo = "";

if(isset($_POST['guardar'])){
    $usuario = trim($_POST['usuario']);
    $nombre_completo = trim($_POST['nombre_completo']);
    $password = trim($_POST['contrasena']);
    $rol = $_POST['rol'] === "administrador" ? "admin" : "usuario";

    $check = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario=?");
    $check->bind_param("s", $usuario);
    $check->execute();
    $check->bind_result($existe);
    $check->fetch();
    $check->close();

    if($existe > 0){
        $mensaje = "El nombre de usuario ya existe.";
        $tipo = "warning";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios(usuario, contrasena, nombre_completo, rol) VALUES(?,?,?,?)");
        $stmt->bind_param("ssss", $usuario, $hash, $nombre_completo, $rol);
        if($stmt->execute()){
            $mensaje = "Usuario agregado correctamente.";
            $tipo = "success";
        } else {
            $mensaje = "Error al agregar el usuario.";
            $tipo = "error";
        }
        $stmt->close();
    }
}
?>

<h2 style="text-align:center;">Agregar Usuario</h2>

<form method="post" style="max-width:400px; margin:auto; background:#f9f9f9; padding:20px; border-radius:10px;">
    <label>Nombre de Usuario:</label>
    <input type="text" name="usuario" required style="width:100%; padding:8px; margin-bottom:10px;">

    <label>Nombre Completo:</label>
    <input type="text" name="nombre_completo" required style="width:100%; padding:8px; margin-bottom:10px;">

    <label>Contraseña:</label>
    <input type="password" name="contrasena" required style="width:100%; padding:8px; margin-bottom:10px;">

    <label>Rol:</label>
    <select name="rol" required style="width:100%; padding:8px; margin-bottom:15px;">
        <option value="administrador">Administrador</option>
        <option value="usuario">Usuario</option>
    </select>

    <button type="submit" name="guardar" style="background-color:#28a745; color:#fff; padding:10px 20px; border:none; border-radius:5px;">Guardar</button>
</form>

<div style="text-align:center; margin-top:15px;">
    <a class="button" href="listar.php" style="background-color:#0066cc;">← Volver</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if($mensaje != ""): ?>
<script>
Swal.fire({
    title: '<?= $tipo==="success"?"Éxito":"Advertencia" ?>',
    text: '<?= $mensaje ?>',
    icon: '<?= $tipo ?>',
    confirmButtonColor: '<?= $tipo==="success"?"#28a745":"#d33" ?>'
}).then((result)=>{
    <?php if($tipo==="success"): ?>
        window.location.href = 'listar.php';
    <?php endif; ?>
});
</script>
<?php endif; ?>

<?php include "../includes/footer.php"; ?>