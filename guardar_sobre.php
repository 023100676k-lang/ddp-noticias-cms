<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $titulo = trim($_POST['titulo'] ?? 'Sobre D&D');
    $historia = $_POST['historia'] ?? '';
    $mision = trim($_POST['mision'] ?? '');
    $vision = trim($_POST['vision'] ?? '');
    $valores = trim($_POST['valores'] ?? '');
    $usuario_id = $_SESSION['usuario_id'];

    $imagen = null;

    // Si estamos editando, obtener la imagen actual
    if ($id > 0) {
        $sql_actual = "SELECT imagen FROM sobre_dd WHERE id = ?";
        $stmt = $conexion->prepare($sql_actual);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $registro = $resultado->fetch_assoc();
        $imagen = $registro['imagen'] ?? null;
    }

    // Procesar nueva imagen si se subió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/sobre/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre_archivo = time() . '_' . basename($_FILES['imagen']['name']);
        $ruta = $carpeta . $nombre_archivo;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
            if ($imagen && file_exists($imagen)) unlink($imagen);
            $imagen = $ruta;
        }
    }

    if ($id > 0) {
        // ACTUALIZAR
        $sql = "UPDATE sobre_dd SET titulo=?, historia=?, mision=?, vision=?, valores=?, imagen=?, usuario_id=? WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssii", $titulo, $historia, $mision, $vision, $valores, $imagen, $usuario_id, $id);
        $stmt->execute();
        header("Location: sobre.php?mensaje=actualizado");
    } else {
        // CREAR NUEVO
        $sql = "INSERT INTO sobre_dd (titulo, historia, mision, vision, valores, imagen, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssi", $titulo, $historia, $mision, $vision, $valores, $imagen, $usuario_id);
        $stmt->execute();
        header("Location: sobre.php?mensaje=guardado");
    }
    exit;
} else {
    header("Location: sobre.php");
    exit;
}
?>
