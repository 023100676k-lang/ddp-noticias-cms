<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $url_embed = trim($_POST['url_embed'] ?? '');
    $fecha_publicacion = $_POST['fecha_publicacion'] ?? '';
    $usuario_id = $_SESSION['usuario_id'];

    if (empty($titulo) || empty($url_embed) || empty($fecha_publicacion)) {
        header("Location: podcast_nuevo.php?error=vacio");
        exit;
    }

    $sql = "INSERT INTO podcasts (titulo, url_embed, fecha_publicacion, usuario_id) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $titulo, $url_embed, $fecha_publicacion, $usuario_id);

    if ($stmt->execute()) {
        header("Location: podcast.php?mensaje=guardado");
    } else {
        header("Location: podcast_nuevo.php?error=bd");
    }
    exit;
} else {
    header("Location: podcast_nuevo.php");
    exit;
}
?>
