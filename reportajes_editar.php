<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;
$reportaje = null;

if ($id > 0) {
    $sql = "SELECT * FROM reportajes WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 1) $reportaje = $resultado->fetch_assoc();
}

if (!$reportaje) { header("Location: reportajes.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $resumen_corto = trim($_POST['resumen_corto'] ?? '');
    $desarrollo = $_POST['desarrollo'] ?? '';
    $fecha_publicacion = $_POST['fecha_publicacion'] ?? '';
    $es_destacado = isset($_POST['es_destacado']) ? 1 : 0;

    $foto_principal = $reportaje['foto_principal'];
    if (isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/reportajes/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre = time() . '_' . basename($_FILES['foto_principal']['name']);
        $ruta = $carpeta . $nombre;
        if (move_uploaded_file($_FILES['foto_principal']['tmp_name'], $ruta)) {
            if ($reportaje['foto_principal'] && file_exists($reportaje['foto_principal'])) unlink($reportaje['foto_principal']);
            $foto_principal = $ruta;
        }
    }

    $pdf_adjunto = $reportaje['pdf_adjunto'];
    if (isset($_FILES['pdf_adjunto']) && $_FILES['pdf_adjunto']['error'] === UPLOAD_ERR_OK) {
        $carpeta = 'uploads/reportajes/pdf/';
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);
        $nombre = time() . '_' . basename($_FILES['pdf_adjunto']['name']);
        $ruta = $carpeta . $nombre;
        if (move_uploaded_file($_FILES['pdf_adjunto']['tmp_name'], $ruta)) {
            if ($reportaje['pdf_adjunto'] && file_exists($reportaje['pdf_adjunto'])) unlink($reportaje['pdf_adjunto']);
            $pdf_adjunto = $ruta;
        }
    }

    $sql = "UPDATE reportajes SET titulo=?, resumen_corto=?, desarrollo=?, foto_principal=?, pdf_adjunto=?, fecha_publicacion=?, es_destacado=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssii", $titulo, $resumen_corto, $desarrollo, $foto_principal, $pdf_adjunto, $fecha_publicacion, $es_destacado, $id);
    $stmt->execute();

    header("Location: reportajes.php?mensaje=actualizado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Editar Reportaje | DDP Noticias</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logo.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/ddp-estilos.css" rel="stylesheet">
    <link href="./vendor/summernote/summernote-lite.min.css" rel="stylesheet">
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
                    <li class="active"><a href="./reportajes.php"><i class="icon icon-camera"></i><span class="nav-text">Reportajes</span></a></li>
                    <li><a href="./podcast.php"><i class="icon icon-microphone"></i><span class="nav-text">Podcast</span></a></li>
                    <li><a href="./boletin.php"><i class="icon icon-envelope"></i><span class="nav-text">Boletín NTEP</span></a></li>
                    <li><a href="./alianzas.php"><i class="icon icon-handshake"></i><span class="nav-text">Alianzas</span></a></li>
                    <li><a href="./sobre.php"><i class="icon icon-info"></i><span class="nav-text">Sobre D&D</span></a></li>
                </ul>
            </div>
        </div>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-12">
                        <div class="welcome-text">
                            <h4>Editar Reportaje</h4>
                            <p class="mb-0">Modifica los datos del reportaje</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Título <span class="text-danger">*</span></label>
                                        <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($reportaje['titulo']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Resumen Corto</label>
                                        <textarea name="resumen_corto" class="form-control" rows="2"><?php echo htmlspecialchars($reportaje['resumen_corto'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>Fecha <span class="text-danger">*</span></label>
                                            <input type="date" name="fecha_publicacion" class="form-control" value="<?php echo htmlspecialchars($reportaje['fecha_publicacion']); ?>" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Destacado</label>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="es_destacado" value="1" id="esDestacado" <?php echo $reportaje['es_destacado'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="esDestacado">Marcar como destacado</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Foto Actual</label><br>
                                            <?php if ($reportaje['foto_principal']): ?>
                                                <img src="<?php echo htmlspecialchars($reportaje['foto_principal']); ?>" width="150" style="border-radius:10px; margin-bottom:10px;">
                                            <?php else: ?>
                                                <p class="text-muted">Sin imagen</p>
                                            <?php endif; ?>
                                            <input type="file" name="foto_principal" class="form-control" accept="image/*">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>PDF Actual</label><br>
                                            <?php if ($reportaje['pdf_adjunto']): ?>
                                                <a href="<?php echo htmlspecialchars($reportaje['pdf_adjunto']); ?>" target="_blank" class="btn btn-info btn-sm mb-2">Ver PDF Actual</a>
                                            <?php else: ?>
                                                <p class="text-muted">Sin PDF</p>
                                            <?php endif; ?>
                                            <input type="file" name="pdf_adjunto" class="form-control" accept=".pdf">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Desarrollo del Reportaje</label>
                                        <textarea name="desarrollo" id="summernote" class="form-control"><?php echo htmlspecialchars($reportaje['desarrollo']); ?></textarea>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Actualizar Reportaje</button>
                                        <a href="./reportajes.php" class="btn btn-secondary">Cancelar</a>
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
    <script src="./vendor/summernote/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 350,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'italic', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
</body>
</html>
