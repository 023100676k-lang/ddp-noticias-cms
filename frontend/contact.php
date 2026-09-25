<?php
require_once 'conexion.php';
require_once 'header_frontend.php';

// Procesar el formulario de contacto
$mensaje_exito = '';
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $asunto = trim($_POST['asunto'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (empty($nombre) || empty($email) || empty($mensaje)) {
        $mensaje_error = 'Por favor completa todos los campos obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje_error = 'El correo electrónico no es válido.';
    } else {
        $sql = "INSERT INTO contactos (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $email, $asunto, $mensaje);
        
        if ($stmt->execute()) {
            $mensaje_exito = '¡Mensaje enviado correctamente! Te responderemos pronto.';
            $nombre = $email = $asunto = $mensaje = '';
        } else {
            $mensaje_error = 'Hubo un error al enviar el mensaje. Intenta de nuevo.';
        }
    }
}

// Obtener datos de Sobre D&D
$sobre = $conexion->query("SELECT titulo, historia, mision, vision, valores, imagen 
                            FROM sobre_dd 
                            ORDER BY id ASC 
                            LIMIT 1")->fetch_assoc();
?>
<section class="breadcrumb-area py-sm-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-contents">
                    <h2 class="title-big">Contacto</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="w3l-banner py-5">
    <div class="midd-w3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 about-right-faq align-self">
                    <h5 class="title-small mb-2">DDP Noticias</h5>
                    <h3 class="title-banner"><?php echo htmlspecialchars($sobre['titulo'] ?? 'Sobre D&D'); ?></h3>
                    <div class="mt-4"><?php echo $sobre['historia'] ?? 'Información en construcción.'; ?></div>
                </div>
                <div class="col-md-6 left-wthree-img mt-lg-0 mt-4">
                    <?php if (!empty($sobre['imagen'])): ?>
                        <img src="../<?php echo htmlspecialchars($sobre['imagen']); ?>" alt="" class="img-fluid">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="w3l-homeblock5 py-5">
    <div class="container py-lg-5 py-4">
        <div class="row">
            <div class="col-lg-4">
                <div class="area-box">
                    <h4 class="title-big" style="font-size: 20px;">Misión</h4>
                    <p><?php echo htmlspecialchars($sobre['mision'] ?? 'Sin información'); ?></p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="area-box">
                    <h4 class="title-big" style="font-size: 20px;">Visión</h4>
                    <p><?php echo htmlspecialchars($sobre['vision'] ?? 'Sin información'); ?></p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="area-box">
                    <h4 class="title-big" style="font-size: 20px;">Valores</h4>
                    <p><?php echo htmlspecialchars($sobre['valores'] ?? 'Sin información'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Formulario de Contacto -->
<section class="w3l-contact-2 py-5" id="contact">
    <div class="container py-lg-5 py-md-4">
        <h3 class="title-big mb-5 text-center">Envíanos un mensaje</h3>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if ($mensaje_exito): ?>
                    <div class="alert alert-success" style="padding: 15px; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 20px;">
                        ✅ <?php echo $mensaje_exito; ?>
                    </div>
                <?php endif; ?>
                <?php if ($mensaje_error): ?>
                    <div class="alert alert-danger" style="padding: 15px; background: #f8d7da; color: #721c24; border-radius: 8px; margin-bottom: 20px;">
                        ❌ <?php echo $mensaje_error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" style="background: #fff; padding: 40px; border-radius: 15px; box-shadow: 0 5px 30px rgba(0,0,0,0.08);">
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; color: #333;">Nombre Completo *</label>
                        <input type="text" name="nombre" class="form-control" style="padding: 12px; border: 2px solid #eee; border-radius: 8px;" placeholder="Tu nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; color: #333;">Correo Electrónico *</label>
                        <input type="email" name="email" class="form-control" style="padding: 12px; border: 2px solid #eee; border-radius: 8px;" placeholder="tu@email.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; color: #333;">Asunto</label>
                        <input type="text" name="asunto" class="form-control" style="padding: 12px; border: 2px solid #eee; border-radius: 8px;" placeholder="¿Sobre qué nos escribes?" value="<?php echo htmlspecialchars($asunto ?? ''); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; color: #333;">Mensaje *</label>
                        <textarea name="mensaje" class="form-control" style="padding: 12px; border: 2px solid #eee; border-radius: 8px; min-height: 150px;" placeholder="Escribe tu mensaje aquí..." required><?php echo htmlspecialchars($mensaje ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-style btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">
                        Enviar Mensaje <span class="fa fa-paper-plane"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer_frontend.php'; ?>
