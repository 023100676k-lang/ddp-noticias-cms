<?php require_once 'verificar_sesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Actualidad | DDP Noticias - Panel</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logo.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/ddp-estilos.css" rel="stylesheet">
    <style>
        .alerta-flotante {
            position: fixed; top: 90px; right: 30px; z-index: 9999;
            padding: 15px 25px; border-radius: 12px; font-weight: 600;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            animation: slideIn 0.5s ease-out;
        }
        .alerta-flotante.exito { background: linear-gradient(135deg, #00D68F, #00B377); color: #fff; }
        .alerta-flotante.error { background: linear-gradient(135deg, #E63946, #C8102E); color: #fff; }
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .acciones-tabla { display: flex; gap: 5px; }
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
                        <div class="header-left">
                            <div class="search_bar dropdown">
                                <span class="search_icon p-3 c-pointer" data-toggle="dropdown"><i class="mdi mdi-magnify"></i></span>
                                <div class="dropdown-menu p-0 m-0"><form><input class="form-control" type="search" placeholder="Buscar" aria-label="Search"></form></div>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown"><i class="mdi mdi-account"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="./perfil.php" class="dropdown-item"><i class="icon-user"></i><span class="ml-2">Mi Perfil</span></a>
                                    <a href="./logout.php" class="dropdown-item" style="color: #C8102E; font-weight: 600;"><i class="icon-key"></i><span class="ml-2">Cerrar Sesión</span></a>
                                </div>
                            </li>
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
                    <li class="active"><a href="./actualidad.php"><i class="icon icon-newspaper"></i><span class="nav-text">Actualidad</span></a></li>
                    <li><a href="./reportajes.php"><i class="icon icon-camera"></i><span class="nav-text">Reportajes</span></a></li>
                    <li><a href="./podcast.php"><i class="icon icon-microphone"></i><span class="nav-text">Podcast</span></a></li>
                    <li><a href="./boletin.php"><i class="icon icon-envelope"></i><span class="nav-text">Boletín NTEP</span></a></li>
                    <li><a href="./alianzas.php"><i class="icon icon-handshake"></i><span class="nav-text">Alianzas</span></a></li>
                    <li><a href="./sobre.php"><i class="icon icon-info"></i><span class="nav-text">Sobre D&D</span></a></li>
                </ul>
            </div>
        </div>
        <div class="content-body">
            <div class="container-fluid">
                <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'guardado'): ?>
                    <div class="alerta-flotante exito">✅ ¡Noticia guardada correctamente!</div>
                <?php endif; ?>
                <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'eliminado'): ?>
                    <div class="alerta-flotante exito">🗑️ Noticia eliminada correctamente</div>
                <?php endif; ?>
                <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'actualizado'): ?>
                    <div class="alerta-flotante exito">✏️ Noticia actualizada correctamente</div>
                <?php endif; ?>

                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Gestión de Actualidad</h4>
                            <p class="mb-0">Aquí puedes ver, editar y eliminar las noticias.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Actualidad</a></li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Lista de Noticias</h4>
                                <a href="./actualidad_nuevo.php" class="btn btn-primary btn-sm">+ Nueva Noticia</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Imagen</th>
                                                <th>Título</th>
                                                <th>Fecha</th>
                                                <th>Enlace</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require_once 'conexion.php';
                                            $sql = "SELECT id, titulo, foto, link_externo, fecha_publicacion FROM noticias ORDER BY fecha_publicacion DESC, id DESC";
                                            $resultado = $conexion->query($sql);

                                            if ($resultado && $resultado->num_rows > 0) {
                                                $i = 1;
                                                while ($noticia = $resultado->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td>' . $i++ . '</td>';
                                                    echo '<td>';
                                                    if ($noticia['foto']) {
                                                        echo '<img src="' . htmlspecialchars($noticia['foto']) . '" width="60" style="border-radius:8px;">';
                                                    } else {
                                                        echo '<span class="text-muted">Sin imagen</span>';
                                                    }
                                                    echo '</td>';
                                                    echo '<td>' . htmlspecialchars($noticia['titulo']) . '</td>';
                                                    echo '<td>' . htmlspecialchars($noticia['fecha_publicacion']) . '</td>';
                                                    echo '<td>';
                                                    if ($noticia['link_externo']) {
                                                        echo '<a href="' . htmlspecialchars($noticia['link_externo']) . '" target="_blank">Ver enlace</a>';
                                                    } else {
                                                        echo '<span class="text-muted">—</span>';
                                                    }
                                                    echo '</td>';
                                                    echo '<td class="acciones-tabla">';
                                                    echo '<a href="./actualidad_editar.php?id=' . $noticia['id'] . '" class="btn btn-warning btn-sm">Editar</a>';
                                                    echo '<a href="./actualidad_eliminar.php?id=' . $noticia['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'¿Estás seguro de eliminar esta noticia?\');">Eliminar</a>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="6" class="text-center text-muted py-4">No hay noticias registradas. ¡Crea la primera!</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
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
    <script>
        setTimeout(function() {
            var alerta = document.querySelector('.alerta-flotante');
            if (alerta) { alerta.style.transition = 'opacity 0.5s'; alerta.style.opacity = '0'; setTimeout(function() { alerta.remove(); }, 500); }
        }, 4000);
    </script>
</body>
</html>
