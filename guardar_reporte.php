<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $resumen_corto = trim($_POST['resumen_corto'] ?? '');
    $desarrollo = $_POST['desarrollo'] ?? '';
    $fecha_publicacion = $_POST['fecha_publicacion'] ?? '';
    $es_destacado = isset($_POST['es_destacado']) ? 1 : 0;
    $autor_id = $_POST['autor_id'] ?? 1;
    $usuario_id = $_SESSION['usuario_id'];

    if (empty($titulo) || empty($fecha_publicacion)) {
        header("Location: reportajes_nuevo.php?error=vacio");
        exit;
    }

    $foto_principal = null;
    if (isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/reportajes/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre = time() . '_' . basename($_FILES['foto_principal']['name']);
        $ruta = $carpeta . $nombre;
        if (move_uploaded_file($_FILES['foto_principal']['tmp_name'], $ruta)) $foto_principal = $ruta;
    }

    $pdf_adjunto = null;
    if (isset($_FILES['pdf_adjunto']) && $_FILES['pdf_adjunto']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/reportajes/pdf/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre = time() . '_' . basename($_FILES['pdf_adjunto']['name']);
        $ruta = $carpeta . $nombre;
        if (move_uploaded_file($_FILES['pdf_adjunto']['tmp_name'], $ruta)) $pdf_adjunto = $ruta;
    }

    $sql = "INSERT INTO reportajes (titulo, resumen_corto, desarrollo, foto_principal, pdf_adjunto, fecha_publicacion, es_destacado, autor_id, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssii", $titulo, $resumen_corto, $desarrollo, $foto_principal, $pdf_adjunto, $fecha_publicacion, $es_destacado, $autor_id, $usuario_id);

    if ($stmt->execute()) {
        header("Location: reportajes.php?mensaje=guardado");
    } else {
        header("Location: reportajes_nuevo.php?error=bd");
    }
    exit;
} else {
    header("Location: reportajes_nuevo.php");
    exit;
}
?>
