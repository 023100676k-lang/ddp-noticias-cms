<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $sql = "SELECT imagen FROM sobre_dd WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $s = $resultado->fetch_assoc();
        if ($s['imagen'] && file_exists($s['imagen'])) unlink($s['imagen']);
    }

    $sql = "DELETE FROM sobre_dd WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: sobre.php?mensaje=eliminado");
exit;
?>
