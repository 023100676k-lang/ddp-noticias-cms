<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    // Obtener la foto para eliminarla del disco
    $sql = "SELECT foto FROM noticias WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $noticia = $resultado->fetch_assoc();
        if ($noticia['foto'] && file_exists($noticia['foto'])) {
            unlink($noticia['foto']);
        }
    }
    
    // Eliminar el registro
    $sql = "DELETE FROM noticias WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: actualidad.php?mensaje=eliminado");
exit;
?>
