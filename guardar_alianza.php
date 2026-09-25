<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_alianza = $_POST['fecha_alianza'] ?? '';
    $usuario_id = $_SESSION['usuario_id'];

    if (empty($nombre) || empty($fecha_alianza)) {
        header("Location: alianzas_nuevo.php?error=vacio");
        exit;
    }

    $logo = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/alianzas/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre_archivo = time() . '_' . basename($_FILES['logo']['name']);
        $ruta = $carpeta . $nombre_archivo;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $ruta)) $logo = $ruta;
    }

    $sql = "INSERT INTO alianzas (nombre, logo, url, descripcion, fecha_alianza, usuario_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $logo, $url, $descripcion, $fecha_alianza, $usuario_id);

    if ($stmt->execute()) {
        header("Location: alianzas.php?mensaje=guardado");
    } else {
        header("Location: alianzas_nuevo.php?error=bd");
    }
    exit;
} else {
    header("Location: alianzas_nuevo.php");
    exit;
}
?>
