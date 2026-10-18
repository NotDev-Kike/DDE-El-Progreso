<?php
include "../includes/db.php";
include "../includes/header.php";

if(!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM nombramientos WHERE id_nombramiento = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: listar.php");
exit;
?>