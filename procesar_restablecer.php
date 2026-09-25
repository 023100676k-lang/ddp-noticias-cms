<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? 0;
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    // Validar que las contraseñas coincidan
    if ($password !== $password2 || empty($password)) {
        header("Location: restablecer.php?user=$user_id&error=no_coinciden");
        exit;
    }

    // Validar longitud mínima (6 caracteres)
    if (strlen($password) < 6) {
        header("Location: restablecer.php?user=$user_id&error=corta");
        exit;
    }

    // Encriptar la contraseña con bcrypt
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Actualizar en la base de datos
    $sql = "UPDATE usuarios SET password_hash = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $password_hash, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: login.php?mensaje=restablecido");
    } else {
        header("Location: login.php?error=no_cambio");
    }
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>