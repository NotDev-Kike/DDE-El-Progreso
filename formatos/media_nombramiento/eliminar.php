<?php
include "../../includes/db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM formato_nombramiento_media WHERE id=$id");
}

header("Location: listar.php");
exit;
?>