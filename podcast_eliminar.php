<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $sql = "DELETE FROM podcasts WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: podcast.php?mensaje=eliminado");
exit;
?>
