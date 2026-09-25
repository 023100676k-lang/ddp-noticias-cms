<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validar campos vacíos
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=vacio");
        exit;
    }

    // Buscar al usuario por email
    $sql = "SELECT id, nombres, ap_paterno, email, password_hash, rol FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar SOLO contraseña encriptada
        if (password_verify($password, $usuario['password_hash'])) {
            // Regenerar ID de sesión para seguridad
            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombres'] . ' ' . $usuario['ap_paterno'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            header("Location: index.php");
            exit;
        }
    }

    // Si no coincide, redirigir con error
    header("Location: login.php?error=credenciales");
    exit;
} else {
    // Si no es POST, redirigir
    header("Location: login.php");
    exit;
}
?>