<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $link_externo = trim($_POST['link_externo'] ?? '');
    $fecha_publicacion = $_POST['fecha_publicacion'] ?? '';
    $usuario_id = $_SESSION['usuario_id'];

    if (empty($titulo) || empty($fecha_publicacion)) {
        header("Location: actualidad_nuevo.php?error=vacio");
        exit;
    }

    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/noticias/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre = time() . '_' . basename($_FILES['foto']['name']);
        $ruta = $carpeta . $nombre;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta)) $foto = $ruta;
    }

    $sql = "INSERT INTO noticias (titulo, foto, link_externo, fecha_publicacion, usuario_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $titulo, $foto, $link_externo, $fecha_publicacion, $usuario_id);

    if ($stmt->execute()) {
        header("Location: actualidad.php?mensaje=guardado");
    } else {
        header("Location: actualidad_nuevo.php?error=bd");
    }
    exit;
} else {
    header("Location: actualidad_nuevo.php");
    exit;
}
?>
