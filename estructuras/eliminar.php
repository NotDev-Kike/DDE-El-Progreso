<?php
include "../includes/db.php";
include "../includes/header.php";

if(!isset($_GET['id'])) { header("Location:listar.php"); exit; }
$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM estructuras WHERE id_estructura = ?");
$stmt->bind_param("i",$id);
$stmt->execute();

header("Location: listar.php");
exit;
?>
