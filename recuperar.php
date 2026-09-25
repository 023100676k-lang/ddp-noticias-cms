<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña | DDP Noticias</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logo.png">
    <style>
        * { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1B2A4A, #0F1A30); }
        .login-container { width: 100%; max-width: 420px; padding: 20px; }
        .login-card { background: #fff; border-radius: 25px; padding: 50px 40px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); }
        .login-logo { text-align: center; margin-bottom: 20px; }
        .login-logo img { width: 90px; height: 90px; border-radius: 50%; box-shadow: 0 10px 30px rgba(200,16,46,0.4); }
        h2 { color: #1B2A4A; font-weight: 800; text-align: center; margin-bottom: 10px; font-size: 22px; }
        p.subtitulo { color: #7A8BA8; text-align: center; font-size: 13px; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #1B2A4A; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 14px 18px; border: 2px solid #E2E8F0; border-radius: 12px; font-size: 14px; background: #F8FAFF; outline: none; transition: all 0.3s ease; }
        .form-control:focus { border-color: #C8102E; box-shadow: 0 0 0 4px rgba(200,16,46,0.1); background: #fff; }
        .btn-login { width: 100%; padding: 15px; background: linear-gradient(135deg, #C8102E, #8B0A1F); color: #fff; border: none; border-radius: 12px; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer; box-shadow: 0 8px 25px rgba(200,16,46,0.35); transition: all 0.3s ease; }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(200,16,46,0.5); }
        .alerta { padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; }
        .alerta-error { background: #FEE2E2; color: #991B1B; border-left: 4px solid #C8102E; }
        .login-footer { text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #E2E8F0; }
        .login-footer a { color: #C8102E; text-decoration: none; font-size: 13px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo"><img src="./images/logo.png" alt="DDP"></div>
            <h2>Recuperar Contraseña</h2>
            <p class="subtitulo">Ingresa tu correo o usuario para cambiar tu contraseña</p>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'no_existe'): ?>
                <div class="alerta alerta-error">❌ El correo o usuario no está registrado.</div>
            <?php endif; ?>

            <form action="procesar_recuperar.php" method="POST">
                <div class="form-group">
                    <label>Correo o Usuario</label>
                    <input type="text" name="identificador" class="form-control" placeholder="admin@ddp.com o admin" required>
                </div>
                <button type="submit" class="btn-login">Cambiar Contraseña</button>
            </form>

            <div class="login-footer"><a href="login.php">← Volver al inicio de sesión</a></div>
        </div>
    </div>
</body>
</html>
