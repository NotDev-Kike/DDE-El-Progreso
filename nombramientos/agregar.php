<?php
include "../includes/db.php"; 
include "../includes/header.php";

$mensaje = "";
$tipo = "";

if(isset($_POST['guardar'])){
    $nombre = trim($_POST['nombre']);

    $verificar = $conn->prepare("SELECT COUNT(*) FROM nombramientos WHERE nombre_nombramiento = ?");
    $verificar->bind_param("s", $nombre);
    $verificar->execute();
    $verificar->bind_result($existe);
    $verificar->fetch();
    $verificar->close();

    if($existe > 0){
        $mensaje = "El nombramiento ya existe. No se puede agregar duplicado.";
        $tipo = "warning";
    } else {
        $stmt = $conn->prepare("INSERT INTO nombramientos(nombre_nombramiento) VALUES(?)");
        $stmt->bind_param("s", $nombre);
        if($stmt->execute()){
            $mensaje = "Nombramiento agregado correctamente.";
            $tipo = "success";
        } else {
            $mensaje = "Error al agregar el nombramiento.";
            $tipo = "error";
        }
        $stmt->close();
    }
}
?>

<h2 style="text-align:center;">Agregar Nombramiento</h2>

<form method="post" style="max-width:400px; margin:auto; background:#f9f9f9; padding:20px; border-radius:10px; box-shadow:0 0 8px rgba(0,0,0,0.1);">
    <label>Nombre del Nombramiento:</label>
    <input type="text" name="nombre" required style="width:100%; padding:8px; margin-top:5px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
    
    <button type="submit" name="guardar" 
        style="background-color:#28a745; color:#fff; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
        Guardar
    </button>
</form>

<div style="text-align:center; margin-top:15px;">
    <a class="button" href="listar.php" 
       style="background-color:#0066cc; color:white; padding:8px 15px; border-radius:5px; text-decoration:none;">
       ← Volver
    </a>
</div>

<footer style="text-align:center; margin-top:30px; color:#555;">
    <p>© 2025 Jose Enrique Pineda Rivera</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if($mensaje !== ""): ?>
<script>
Swal.fire({
    title: '<?= $tipo === "success" ? "Éxito" : "Advertencia" ?>',
    text: '<?= $mensaje ?>',
    icon: '<?= $tipo ?>',
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '<?= $tipo === "success" ? "#28a745" : "#d33" ?>'
}).then((result) => {
    <?php if($tipo === "success"): ?>
        window.location.href = 'listar.php';
    <?php endif; ?>
});
</script>
<?php endif; ?>

<?php include "../includes/footer.php"; ?>