<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificador = trim($_POST['identificador'] ?? '');

    // Buscar por email O por nombre de usuario (nombres)
    $sql = "SELECT id FROM usuarios WHERE email = ? OR nombres = ? LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $identificador, $identificador);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        // Redirigir directamente a restablecer con el ID del usuario
        header("Location: restablecer.php?user=" . $usuario['id']);
        exit;
    } else {
        header("Location: recuperar.php?error=no_existe");
        exit;
    }
} else {
    header("Location: recuperar.php");
    exit;
}
?>
