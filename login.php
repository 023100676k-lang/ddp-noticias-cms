<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | DDP Noticias</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logo.png">
    <link href="./css/style.css" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1B2A4A 0%, #0F1A30 100%);
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(200,16,46,0.3) 0%, transparent 70%);
            top: -200px;
            right: -200px;
            border-radius: 50%;
            animation: flotar 6s ease-in-out infinite;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(200,16,46,0.2) 0%, transparent 70%);
            bottom: -150px;
            left: -150px;
            border-radius: 50%;
            animation: flotar 8s ease-in-out infinite reverse;
        }
        @keyframes flotar {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-30px); }
        }
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            padding: 50px 40px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            animation: aparecer 0.6s ease-out;
        }
        @keyframes aparecer {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(200, 16, 46, 0.4);
            transition: transform 0.3s ease;
        }
        .login-logo img:hover { transform: scale(1.05); }
        .login-titulo {
            text-align: center;
            margin-bottom: 35px;
        }
        .login-titulo h2 {
            color: #1B2A4A;
            font-weight: 800;
            font-size: 26px;
            margin-bottom: 5px;
        }
        .login-titulo p {
            color: #7A8BA8;
            font-size: 14px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            color: #1B2A4A;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #F8FAFF;
            outline: none;
        }
        .form-control:focus {
            border-color: #C8102E;
            box-shadow: 0 0 0 4px rgba(200, 16, 46, 0.1);
            background: #fff;
        }
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #C8102E, #8B0A1F);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(200, 16, 46, 0.35);
            margin-top: 10px;
        }
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(200, 16, 46, 0.5);
        }
        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #E2E8F0;
        }
        .login-footer a {
            color: #C8102E;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .login-footer a:hover {
            color: #8B0A1F;
            text-decoration: underline;
        }
        .alerta {
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .alerta-error {
            background: #FEE2E2;
            color: #991B1B;
            border-left: 4px solid #C8102E;
        }
        .alerta-exito {
            background: #D1FAE5;
            color: #065F46;
            border-left: 4px solid #10B981;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <img src="./images/logo.png" alt="DDP Noticias">
            </div>
            <div class="login-titulo">
                <h2>Panel de Administración</h2>
                <p>DDP Noticias - Acceso Restringido</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alerta alerta-error">
                    <?php
                        if ($_GET['error'] == 'credenciales') echo '❌ Email o contraseña incorrectos.';
                        elseif ($_GET['error'] == 'vacio') echo '⚠️ Por favor, completa todos los campos.';
                        elseif ($_GET['error'] == 'sesion') echo '🔒 Debes iniciar sesión para acceder.';
                        elseif ($_GET['error'] == 'no_cambio') echo '❌ No se pudo cambiar la contraseña. Intenta de nuevo.';
                        elseif ($_GET['error'] == 'token_invalido') echo '❌ El enlace de recuperación no es válido.';
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'logout'): ?>
                <div class="alerta alerta-exito">
                    ✅ Has cerrado sesión correctamente.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'restablecido'): ?>
                <div class="alerta alerta-exito">
                    ✅ Contraseña cambiada exitosamente. Ahora inicia sesión con tu nueva contraseña.
                </div>
            <?php endif; ?>

            <form action="procesar_login.php" method="POST">
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@ddp.com" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>

            <div class="login-footer">
                <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
            </div>
        </div>
    </div>
</body>
</html>