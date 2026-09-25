<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $sql = "SELECT logo FROM alianzas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $al = $resultado->fetch_assoc();
        if ($al['logo'] && file_exists($al['logo'])) unlink($al['logo']);
    }

    $sql = "DELETE FROM alianzas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: alianzas.php?mensaje=eliminado");
exit;
?>
