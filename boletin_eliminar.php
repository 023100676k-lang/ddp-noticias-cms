<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $sql = "SELECT foto_portada, archivo_pdf FROM boletines WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $bol = $resultado->fetch_assoc();
        if ($bol['foto_portada'] && file_exists($bol['foto_portada'])) unlink($bol['foto_portada']);
        if ($bol['archivo_pdf'] && file_exists($bol['archivo_pdf'])) unlink($bol['archivo_pdf']);
    }

    $sql = "DELETE FROM boletines WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: boletin.php?mensaje=eliminado");
exit;
?>
