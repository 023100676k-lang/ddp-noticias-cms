<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;
$alianza = null;

if ($id > 0) {
    $sql = "SELECT * FROM alianzas WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 1) $alianza = $resultado->fetch_assoc();
}

if (!$alianza) { header("Location: alianzas.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_alianza = $_POST['fecha_alianza'] ?? '';

    $logo = $alianza['logo'];
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/alianzas/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre_archivo = time() . '_' . basename($_FILES['logo']['name']);
        $ruta = $carpeta . $nombre_archivo;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $ruta)) {
            if ($alianza['logo'] && file_exists($alianza['logo'])) unlink($alianza['logo']);
            $logo = $ruta;
        }
    }

    $sql = "UPDATE alianzas SET nombre=?, logo=?, url=?, descripcion=?, fecha_alianza=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $logo, $url, $descripcion, $fecha_alianza, $id);
    $stmt->execute();

    header("Location: alianzas.php?mensaje=actualizado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Editar Alianza | DDP Noticias</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logo.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/ddp-estilos.css" rel="stylesheet">
</head>
<body>
    <div id="main-wrapper">
        <div class="nav-header">
            <a href="index.php" class="brand-logo"><img class="logo-abbr" src="./images/logo.png" alt="DDP"></a>
            <div class="nav-control"><div class="hamburger"><span class="line"></span><span class="line"></span><span class="line"></span></div></div>
        </div>
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <ul class="navbar-nav header-right ml-auto">
                            <li class="nav-item"><a class="nav-link" href="./logout.php" title="Cerrar Sesión" style="color: #C8102E;"><i class="mdi mdi-logout"></i></a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Menú Principal</li>
                    <li><a href="./index.php"><i class="icon icon-single-04"></i><span class="nav-text">Dashboard</span></a></li>
                    <li class="nav-label">Secciones</li>
                    <li><a href="./actualidad.php"><i class="icon icon-newspaper"></i><span class="nav-text">Actualidad</span></a></li>
                    <li><a href="./reportajes.php"><i class="icon icon-camera"></i><span class="nav-text">Reportajes</span></a></li>
                    <li><a href="./podcast.php"><i class="icon icon-microphone"></i><span class="nav-text">Podcast</span></a></li>
                    <li><a href="./boletin.php"><i class="icon icon-envelope"></i><span class="nav-text">Boletín NTEP</span></a></li>
                    <li class="active"><a href="./alianzas.php"><i class="icon icon-handshake"></i><span class="nav-text">Alianzas</span></a></li>
                    <li><a href="./sobre.php"><i class="icon icon-info"></i><span class="nav-text">Sobre D&D</span></a></li>
                </ul>
            </div>
        </div>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-12">
                        <div class="welcome-text">
                            <h4>Editar Alianza</h4>
                            <p class="mb-0">Modifica los datos de la alianza</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Nombre <span class="text-danger">*</span></label>
                                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($alianza['nombre']); ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Fecha <span class="text-danger">*</span></label>
                                            <input type="date" name="fecha_alianza" class="form-control" value="<?php echo htmlspecialchars($alianza['fecha_alianza']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Sitio Web</label>
                                        <input type="url" name="url" class="form-control" value="<?php echo htmlspecialchars($alianza['url'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción</label>
                                        <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($alianza['descripcion'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Logo Actual</label><br>
                                        <?php if ($alianza['logo']): ?>
                                            <img src="<?php echo htmlspecialchars($alianza['logo']); ?>" width="120" style="border-radius:10px; margin-bottom:10px; background:#F8FAFF; padding:10px;">
                                        <?php else: ?>
                                            <p class="text-muted">Sin logo</p>
                                        <?php endif; ?>
                                        <input type="file" name="logo" class="form-control" accept="image/*">
                                        <small class="text-muted">Deja vacío para mantener el logo actual.</small>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Actualizar Alianza</button>
                                        <a href="./alianzas.php" class="btn btn-secondary">Cancelar</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer"><div class="copyright"><p>Copyright © DDP Noticias 2026</p></div></div>
    </div>
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
</body>
</html>
