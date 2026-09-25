<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $sql = "SELECT foto_principal, pdf_adjunto FROM reportajes WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $rep = $resultado->fetch_assoc();
        if ($rep['foto_principal'] && file_exists($rep['foto_principal'])) unlink($rep['foto_principal']);
        if ($rep['pdf_adjunto'] && file_exists($rep['pdf_adjunto'])) unlink($rep['pdf_adjunto']);
    }

    $sql = "DELETE FROM reportajes WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: reportajes.php?mensaje=eliminado");
exit;
?>
