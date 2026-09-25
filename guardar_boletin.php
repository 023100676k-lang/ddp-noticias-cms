<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_boletin = trim($_POST['numero_boletin'] ?? '');
    $resumen = trim($_POST['resumen'] ?? '');
    $fecha_publicacion = $_POST['fecha_publicacion'] ?? '';
    $usuario_id = $_SESSION['usuario_id'];

    if (empty($numero_boletin) || empty($fecha_publicacion)) {
        header("Location: boletin_nuevo.php?error=vacio");
        exit;
    }

    $foto_portada = null;
    if (isset($_FILES['foto_portada']) && $_FILES['foto_portada']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/boletines/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre = time() . '_' . basename($_FILES['foto_portada']['name']);
        $ruta = $carpeta . $nombre;
        if (move_uploaded_file($_FILES['foto_portada']['tmp_name'], $ruta)) $foto_portada = $ruta;
    }

    $archivo_pdf = null;
    if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/boletines/pdf/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre = time() . '_' . basename($_FILES['archivo_pdf']['name']);
        $ruta = $carpeta . $nombre;
        if (move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $ruta)) $archivo_pdf = $ruta;
    }

    if (!$archivo_pdf) {
        header("Location: boletin_nuevo.php?error=pdf");
        exit;
    }

    $sql = "INSERT INTO boletines (numero_boletin, resumen, foto_portada, archivo_pdf, fecha_publicacion, usuario_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $numero_boletin, $resumen, $foto_portada, $archivo_pdf, $fecha_publicacion, $usuario_id);

    if ($stmt->execute()) {
        header("Location: boletin.php?mensaje=guardado");
    } else {
        header("Location: boletin_nuevo.php?error=bd");
    }
    exit;
} else {
    header("Location: boletin_nuevo.php");
    exit;
}
?>
