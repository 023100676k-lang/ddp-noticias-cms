<?php
require_once 'conexion.php';
$user_id = $_GET['user'] ?? 0;
$usuario = null;

if ($user_id > 0) {
    $sql = "SELECT id, nombres, email FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 1) $usuario = $resultado->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Contraseña | DDP Noticias</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logo.png">
    <style>
        * { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1B2A4A, #0F1A30); }
        .login-container { width: 100%; max-width: 420px; padding: 20px; }
        .login-card { background: #fff; border-radius: 25px; padding: 50px 40px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); }
        .login-logo { text-align: center; margin-bottom: 20px; }
        .login-logo img { width: 90px; height: 90px; border-radius: 50%; box-shadow: 0 10px 30px rgba(200,16,46,0.4); }
        h2 { color: #1B2A4A; font-weight: 800; text-align: center; margin-bottom: 10px; font-size: 22px; }
        p.subtitulo { color: #7A8BA8; text-align: center; font-size: 13px; margin-bottom: 20px; }
        .usuario-info { background: #F0F4FA; padding: 12px; border-radius: 10px; text-align: center; margin-bottom: 25px; }
        .usuario-info strong { color: #1B2A4A; }
        .usuario-info span { color: #C8102E; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #1B2A4A; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 14px 18px; border: 2px solid #E2E8F0; border-radius: 12px; font-size: 14px; background: #F8FAFF; outline: none; transition: all 0.3s ease; }
        .form-control:focus { border-color: #C8102E; box-shadow: 0 0 0 4px rgba(200,16,46,0.1); background: #fff; }
        .btn-login { width: 100%; padding: 15px; background: linear-gradient(135deg, #C8102E, #8B0A1F); color: #fff; border: none; border-radius: 12px; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer; box-shadow: 0 8px 25px rgba(200,16,46,0.35); transition: all 0.3s ease; }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(200,16,46,0.5); }
        .alerta-error { padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; background: #FEE2E2; color: #991B1B; border-left: 4px solid #C8102E; }
        .login-footer { text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #E2E8F0; }
        .login-footer a { color: #C8102E; text-decoration: none; font-size: 13px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo"><img src="./images/logo.png" alt="DDP"></div>

            <?php if ($usuario): ?>
                <h2>Nueva Contraseña</h2>
                <p class="subtitulo">Ingresa tu nueva contraseña</p>

                <div class="usuario-info">
                    Cambiando contraseña para: <strong><?php echo htmlspecialchars($usuario['nombres']); ?></strong><br>
                    <span><?php echo htmlspecialchars($usuario['email']); ?></span>
                </div>

                <?php if (isset($_GET['error']) && $_GET['error'] == 'no_coinciden'): ?>
                    <div class="alerta-error">❌ Las contraseñas no coinciden.</div>
                <?php endif; ?>

                <form action="procesar_restablecer.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">
                    <div class="form-group">
                        <label>Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Confirmar Contraseña</label>
                        <input type="password" name="password2" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                    <button type="submit" class="btn-login">Cambiar Contraseña</button>
                </form>
            <?php else: ?>
                <h2>Usuario no encontrado</h2>
                <p class="subtitulo">El enlace no es válido o ha expirado.</p>
                <a href="recuperar.php" class="btn-login" style="display:block; text-align:center; text-decoration:none;">Intentar de nuevo</a>
            <?php endif; ?>

            <div class="login-footer"><a href="login.php">← Volver al inicio de sesión</a></div>
        </div>
    </div>
</body>
</html>
