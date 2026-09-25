<?php require_once 'verificar_sesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Nuevo Reportaje | DDP Noticias</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logo.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/ddp-estilos.css" rel="stylesheet">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editor.note-frame { border: 2px solid #E2E8F0; border-radius: 12px; }
        .note-editor.note-frame:focus-within { border-color: #C8102E; box-shadow: 0 0 0 4px rgba(200,16,46,0.1); }
        .note-toolbar { background: #F8FAFF; border-bottom: 1px solid #E2E8F0 !important; }
        .note-btn { border: 1px solid #E2E8F0 !important; background: #fff !important; }
        .note-btn:hover { background: #C8102E !important; color: #fff !important; }
    </style>
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
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Nuevo Reportaje</h4>
                            <p class="mb-0">Completa el formulario para publicar un reportaje.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="./reportajes.php">Reportajes</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Nuevo</a></li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Datos del Reportaje</h4></div>
                            <div class="card-body">
                                <form action="guardar_reporte.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Título <span class="text-danger">*</span></label>
                                        <input type="text" name="titulo" class="form-control" placeholder="Título del reportaje..." required>
                                    </div>
                                    <div class="form-group">
                                        <label>Resumen Corto</label>
                                        <textarea name="resumen_corto" id="summernote_resumen" class="form-control"></textarea>
                                        <small class="text-muted">Breve descripción para la vista previa (con formato enriquecido).</small>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>Fecha <span class="text-danger">*</span></label>
                                            <input type="date" name="fecha_publicacion" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Autor</label>
                                            <select name="autor_id" class="form-control">
                                                <option value="1">Redacción DDP</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Destacado</label>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="es_destacado" value="1" id="esDestacado">
                                                <label class="form-check-label" for="esDestacado">Marcar como destacado</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Foto Principal</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">Subir</span></div>
                                                <div class="custom-file">
                                                    <input type="file" name="foto_principal" class="custom-file-input" accept="image/*">
                                                    <label class="custom-file-label">Seleccionar imagen...</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>PDF Adjunto</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">Subir</span></div>
                                                <div class="custom-file">
                                                    <input type="file" name="pdf_adjunto" class="custom-file-input" accept=".pdf">
                                                    <label class="custom-file-label">Seleccionar PDF...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Desarrollo del Reportaje <span class="text-danger">*</span></label>
                                        <textarea name="desarrollo" id="summernote_desarrollo" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Guardar Reportaje</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            // Editor para Resumen Corto
            $('#summernote_resumen').summernote({
                height: 120,
                placeholder: 'Breve resumen para vista previa...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
            
            // Editor para Desarrollo
            $('#summernote_desarrollo').summernote({
                height: 400,
                placeholder: 'Escribe aquí el desarrollo completo del reportaje...',
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
