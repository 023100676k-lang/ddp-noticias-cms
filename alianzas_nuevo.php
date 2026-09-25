<?php require_once 'verificar_sesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Nueva Alianza | DDP Noticias</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logo.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/ddp-estilos.css" rel="stylesheet">
</head>
<body>
    <div id="preloader"><div class="sk-three-bounce"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div></div>
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
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Nueva Alianza</h4>
                            <p class="mb-0">Registra una nueva alianza con otra institución o empresa.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="./alianzas.php">Alianzas</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Nueva</a></li>
                        </ol>
                    </div>
                </div>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">❌ Error al guardar. Verifica los campos.</div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Datos de la Alianza</h4></div>
                            <div class="card-body">
                                <form action="guardar_alianza.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Nombre de la Alianza <span class="text-danger">*</span></label>
                                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Universidad Nacional..." required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Fecha de la Alianza <span class="text-danger">*</span></label>
                                            <input type="date" name="fecha_alianza" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Sitio Web (URL)</label>
                                        <input type="url" name="url" class="form-control" placeholder="https://ejemplo.com">
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción</label>
                                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe brevemente el objetivo de esta alianza..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Logo de la Alianza</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Subir</span></div>
                                            <div class="custom-file">
                                                <input type="file" name="logo" class="custom-file-input" accept="image/*">
                                                <label class="custom-file-label">Seleccionar logo...</label>
                                            </div>
                                        </div>
                                        <small class="text-muted">Recomendado: PNG con fondo transparente, 200x200 px.</small>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Guardar Alianza</button>
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
