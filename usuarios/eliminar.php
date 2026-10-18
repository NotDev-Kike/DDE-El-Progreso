<?php
include "../includes/db.php";
include "../includes/header.php";

$mensaje = "";
$tipo = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $res = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario=?");
    $res->bind_param("i", $id);
    $res->execute();
    $usuario = $res->get_result()->fetch_assoc();
    $res->close();

    if($usuario){
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario=?");
        $stmt->bind_param("i", $id);

        if($stmt->execute()){
            $mensaje = "Usuario eliminado correctamente.";
            $tipo = "success";
        } else {
            $mensaje = "Error al eliminar el usuario.";
            $tipo = "error";
        }
        $stmt->close();
    } else {
        $mensaje = "El usuario no existe.";
        $tipo = "warning";
    }
} else {
    $mensaje = "ID inválido.";
    $tipo = "warning";
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    title: '<?= $tipo==="success"?"Éxito":($tipo==="warning"?"Advertencia":"Error") ?>',
    text: '<?= $mensaje ?>',
    icon: '<?= $tipo==="success"?"success":($tipo==="warning"?"warning":"error") ?>',
    confirmButtonColor: '<?= $tipo==="success"?"#28a745":"#d33" ?>'
}).then((result)=>{
    window.location.href = 'listar.php';
});
</script>

<?php include "../includes/footer.php"; ?>