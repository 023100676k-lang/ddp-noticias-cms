<?php
// ============================================================
// Página Principal - DDP Noticias (Frontend dinámico)
// ============================================================
require_once 'conexion.php';

// Noticias
$noticias = $conexion->query("SELECT id, titulo, foto, link_externo, fecha_publicacion 
                               FROM noticias 
                               ORDER BY fecha_publicacion DESC, id DESC 
                               LIMIT 3");

// Reportajes (destacados o últimos)
$reportajes = $conexion->query("SELECT id, titulo, foto_principal, fecha_publicacion 
                                 FROM reportajes 
                                 ORDER BY fecha_publicacion DESC, id DESC 
                                 LIMIT 3");

// Boletin más reciente
$boletin = $conexion->query("SELECT id, numero_boletin, resumen, foto_portada, archivo_pdf, fecha_publicacion 
                              FROM boletines 
                              ORDER BY fecha_publicacion DESC, id DESC 
                              LIMIT 1")->fetch_assoc();

// Podcasts
$podcasts = $conexion->query("SELECT id, titulo, url_embed, fecha_publicacion 
                               FROM podcasts 
                               ORDER BY fecha_publicacion DESC, id DESC 
                               LIMIT 4");

// Alianzas
$alianzas = $conexion->query("SELECT id, nombre, logo, url 
                               FROM alianzas 
                               ORDER BY fecha_alianza DESC, id DESC 
                               LIMIT 10");

// Sobre D&D (historia)
$sobre = $conexion->query("SELECT titulo, historia, mision, vision, valores, imagen 
                            FROM sobre_dd 
                            ORDER BY id ASC 
                            LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DDP Noticias - Diálogo y Desarrollo Perú</title>
    <link href="./recursos/css" rel="stylesheet">
    <link rel="stylesheet" href="./recursos/style-starter.css">
</head>
<body>

<!-- header -->
<header id="site-header" class="fixed-top">
  <div class="container">
      <nav class="navbar navbar-expand-lg stroke">
      <a class="navbar-brand" href="index.php">
          <img src="./recursos/logo.png" alt="DDP Noticias" title="DDP Noticias" style="height:75px;">
      </a> 
          <button class="navbar-toggler collapsed bg-gradient" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon fa icon-expand fa-bars"></span>
              <span class="navbar-toggler-icon fa icon-close fa-times"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
              <ul class="navbar-nav ml-auto">
                  <li class="nav-item active">
                      <a class="nav-link" href="index.php">Inicio <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="index.php#actualidad">Actualidad</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="reportajes-1.php">Reportajes</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="podcast.php">Podcast</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="boletines.php">Boletín NTEP</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="podcast.php">Alianzas</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="contact.php">Sobre D&amp;D</a>
                  </li>
                  <li class="ml-2">
                      <a href="index.php#btn" class="btn btn-style btn-outline-secondary">Contacto</a>
                  </li>
              </ul>
          </div>
      </nav>
  </div>
</header>

<!-- breadcrumb -->
<section class="breadcrumb-area py-sm-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-contents">
                    <h2 class="title-big">Reportajes</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- video / reportaje destacado -->
<section class="w3l-video w3l-homeblock3" id="video">
    <div class="container-fluid">
        <div class="video-grids-info row">
            <div class="video-gd-right col-lg-6 p-0">
                <div class="position-relative">
                    <?php if ($reportajes && $reportajes->num_rows > 0): 
                        $reportajes->data_seek(0);
                        $rep_destacado = $reportajes->fetch_assoc();
                    ?>
                    <a href="#"><img src="../<?php echo htmlspecialchars($rep_destacado['foto_principal'] ?: 'images/logo.png'); ?>" alt="" class="img-fluid"></a>
                    <a href="index.php#small-dialog" class="popup-with-zoom-anim play-view text-center position-absolute"></a>
                    <div id="small-dialog" class="zoom-anim-dialog mfp-hide">
                        <iframe src="./recursos/index.html" allow="autoplay; fullscreen" allowfullscreen=""></iframe>
                    </div>
                    <?php else: ?>
                    <img src="./recursos/video.jpg" alt="" class="img-fluid">
                    <?php endif; ?>
                </div>
            </div>
            <div class="video-gd-left col-lg-6 p-lg-5 p-4 align-self">
                <div class="p-xl-4 p-0 video-wrap">
                    <?php if (isset($rep_destacado)): ?>
                    <h5><?php echo date('M d, Y', strtotime($rep_destacado['fecha_publicacion'])); ?></h5>
                    <h3 class="title-big text-left mb-4">
                        <a href="#"><?php echo htmlspecialchars($rep_destacado['titulo']); ?></a>
                    </h3>
                    <p>Reportaje destacado de DDP Noticias...</p>
                    <a href="#" class="btn mt-4 p-0">Leer <span class="fa fa-arrow-right"></span> </a>
                    <?php else: ?>
                    <h5>Set 09, 2026</h5>
                    <h3 class="title-big text-left mb-4">No hay reportajes publicados aún</h3>
                    <p>Los reportajes aparecerán aquí cuando los crees desde el panel de administración.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Noticias Recientes (DINÁMICO) -->
<section class="breadcrumb-area py-sm-5 py-1">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-contents">
                    <h2 class="title-big">Noticias Recientes</h2><a class="anchor" id="actualidad"></a>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="grids-block-5 py-5">
    <section class="py-lg-4 py-md-3">
        <div class="container">
            <div class="row">
                <?php include 'noticias_dinamicas.php'; ?>
            </div>
            <div class="pagination">
                <ul>
                    <li><a target="_blank" href="https://www.facebook.com/DialogoyDesarrolloPeru">Ver todos</a></li>
                </ul>
            </div>
        </div>
    </section>
</div>

<!-- Boletín NTEP (DINÁMICO) -->
<section class="w3l-homeblock5 py-0">
    <div class="container py-lg-5 py-4">
        <div class="row">
            <div class="col-lg-8 align-self">
                <h3 class="title-big mb-4"> Boletín NTEP <?php echo $boletin ? date('Y', strtotime($boletin['fecha_publicacion'])) : 'Año 2026'; ?> </h3>
                <?php if ($boletin): ?>
                    <p><?php echo htmlspecialchars(substr($boletin['resumen'] ?? '', 0, 200)); ?></p>
                    <div class="row mt-sm-4 mt-2 px-3">
                        <div class="col-6 p-0">
                            <span>Nº <?php echo htmlspecialchars($boletin['numero_boletin']); ?></span>
                            <h4><?php echo date('d \d\e F', strtotime($boletin['fecha_publicacion'])); ?></h4>
                        </div>
                        <div class="col-6 p-0">
                            <span><a target="_blank" href="../<?php echo htmlspecialchars($boletin['archivo_pdf']); ?>" class="facebook"><span class="fa fa-download"></span></a></span>
                            <h4>Ver Boletín</h4>
                        </div>
                        <center><a href="boletines.php" class="btn btn-style btn-primary mt-md-5 mt-4">Ver todos</a></center>
                    </div>
                <?php else: ?>
                    <p>No hay boletines publicados aún.</p>
                <?php endif; ?>
            </div>
            <div class="col-lg-4 mt-lg-0 mt-4">
                <?php if ($boletin && $boletin['foto_portada']): ?>
                    <img src="../<?php echo htmlspecialchars($boletin['foto_portada']); ?>" class="img-fluid radius-image" alt="">
                <?php else: ?>
                    <img src="./recursos/boletin-ntep-45.png" class="img-fluid radius-image" alt="">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Podcast (DINÁMICO) -->
<section class="w3l-homeblock3 py-5">
    <div class="container py-lg-5 py-md-4">
        <h3 class="title-big mb-5 text-center">Podcast</h3>
        <div class="row">
            <?php if ($podcasts && $podcasts->num_rows > 0): 
                while ($pod = $podcasts->fetch_assoc()): 
            ?>
            <div class="col-lg-3 col-sm-6 mt-sm-0 mt-5">
                <div class="area-box">
                    <img src="./recursos/podcast.png" alt="Podcast">
                    <h4 style="font-size: 14px; margin-top: 10px;"><?php echo htmlspecialchars($pod['titulo']); ?></h4>
                    <?php if ($pod['url_embed']): ?>
                        <a href="<?php echo htmlspecialchars($pod['url_embed']); ?>" target="_blank" class="btn btn-sm btn-primary mt-2">Escuchar</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No hay podcasts publicados aún.</p>
            </div>
            <?php endif; ?>
        </div>
        <center><a href="#" class="btn btn-style btn-primary mt-md-5 mt-4">Ver todos</a></center>
    </div>
</section>

<!-- Alianzas (DINÁMICO) -->
<section class="w3l-logos w3l-homeblock3 py-5">
    <div class="container py-lg-3">
        <h5 class="title-small mb-1 text-center">DyD Perú</h5>
        <h3 class="title-big mb-md-5 mb-4 text-center">Alianzas</h3>
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="owl-logos owl-carousel owl-theme logo-view">
                    <?php if ($alianzas && $alianzas->num_rows > 0): 
                        while ($al = $alianzas->fetch_assoc()): 
                    ?>
                    <div class="item">
                        <a href="<?php echo htmlspecialchars($al['url'] ?: '#'); ?>" target="_blank">
                            <img src="../<?php echo htmlspecialchars($al['logo'] ?: 'images/logo.png'); ?>" alt="<?php echo htmlspecialchars($al['nombre']); ?>" class="img-fluid">
                        </a>
                    </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sobre D&D (DINÁMICO) -->
<section class="w3l-banner py-0" id="work">
    <div class="midd-w3 py-lg-4 py-md-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mt-lg-0 mt-lg-5 about-right-faq align-self">
                    <h5 class="title-small mb-2">DDP Noticias</h5>
                    <h3 class="title-banner"><?php echo htmlspecialchars($sobre['titulo'] ?? 'Diálogo y Desarrollo Perú'); ?></h3>
                    <p class="mt-4"><?php echo htmlspecialchars($sobre['historia'] ?? 'Somos un espacio de periodismo independiente.'); ?></p>
                    <a href="index.php#btn" class="btn btn-style btn-primary mt-md-5 mt-4">Nosotros</a>
                </div>
                <div class="col-md-6 left-wthree-img mt-lg-0 mt-4">
                    <div class="position-relative">
                        <?php if (!empty($sobre['imagen'])): ?>
                            <img src="../<?php echo htmlspecialchars($sobre['imagen']); ?>" alt="" class="img-fluid">
                        <?php else: ?>
                            <img src="./recursos/bannerimg.jpg" alt="" class="img-fluid">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Redes Sociales -->
<div class="middle py-5">
    <div class="container py-xl-5 py-lg-3">
        <div class="welcome-left text-center py-md-5 py-3">
            <h3 class="title-big">Síguenos en nuestras Redes Sociales</h3>
            <div class="main-social-footer-29">
                <a target="_blank" href="https://www.facebook.com/DialogoyDesarrolloPeru" class="facebook"><span class="fa fa-facebook-square fa-2x"></span></a>
                <a target="_blank" href="https://www.tiktok.com/@dialogo.y.desarrollo" class="twitter"><img src="./recursos/tiktokg.png"></a>
                <a target="_blank" href="https://www.instagram.com/dialogo.y.desarrollo/" class="instagram"><span class="fa fa-instagram fa-2x"></span></a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<section class="w3l-footer-29-main py-5" id="footer">
  <div class="footer-29 py-md-3">
    <div class="container">
      <div class="row footer-top-29">
        <div class="col-lg-6 col-md-6 footer-list-29 footer-1">
          <h6 class="footer-title-29">Quiénes Somos</h6>
          <p><?php echo htmlspecialchars($sobre['mision'] ?? 'Somos un espacio de periodismo independiente.'); ?></p>
          <div class="main-social-footer-29">
            <a target="_blank" href="https://www.facebook.com/DialogoyDesarrolloPeru" class="facebook"><span class="fa fa-facebook-square"></span></a>
            <a target="_blank" href="https://www.tiktok.com/@dialogo.y.desarrollo" class="twitter"><img src="./recursos/tiktokp.png"></a>
            <a target="_blank" href="https://www.instagram.com/dialogo.y.desarrollo/" class="instagram"><span class="fa fa-instagram"></span></a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 footer-list-29 footer-2 mt-md-0 mt-5">
          <ul>
            <h6 class="footer-title-29">Contenido</h6>
            <li><a href="index.php#actualidad">Noticias</a></li>
            <li><a href="reportajes-1.php">Reportajes</a></li>
            <li><a href="#">Podcast</a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-6 mt-lg-0 mt-5 footer-list-29 footer-3">
          <div class="properties">
            <h6 class="footer-title-29">Contacto</h6>
            <ul>
              <li><a href="mailto:info@dialogoydesarrollo.com.pe">info@dialogoydesarrollo.com.pe</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="bottom-copies text-center">
        <p class="copy-footer-29">© 2026 Diálogo y Desarrollo Perú. All rights reserved</p>
      </div>
    </div>
  </div>
  <button onclick="topFunction()" id="movetop" title="Go to top" style="display: none;">
    <span class="fa fa-angle-up"></span>
  </button>
</section>

<!-- Scripts del template original -->
<script src="./recursos/jquery-3.3.1.min.js.descarga"></script>
<script src="./recursos/theme-change.js.descarga"></script>
<script src="./recursos/easyResponsiveTabs.js.descarga"></script>
<script src="./recursos/owl.carousel.js.descarga"></script>
<script src="./recursos/jquery.magnific-popup.min.js.descarga"></script>
<script src="./recursos/bootstrap.min.js.descarga"></script>

<script>
  $(document).ready(function () {
    $('.owl-logos').owlCarousel({
      loop: true, margin: 0, nav: false, responsiveClass: true,
      autoplay: true, autoplayTimeout: 5000, autoplaySpeed: 1000, autoplayHoverPause: false,
      responsive: { 0: {items: 2, nav: false}, 480: {items: 2, nav: false}, 568: {items: 3, nav: false}, 1000: {items: 5, nav: false} }
    });
    $('.owl-carousel').owlCarousel({
      loop: true, margin: 0, responsiveClass: true,
      responsive: { 0: {items: 1, nav: true}, 400: {items: 2, nav: true, margin: 20}, 768: {items: 3, nav: true, margin: 20}, 1000: {items: 4, nav: true, loop: true, margin: 25} }
    });
    $('.popup-with-zoom-anim').magnificPopup({ type: 'inline', fixedContentPos: false, fixedBgPos: true, overflowY: 'auto', closeBtnInside: true, preloader: false, midClick: true, removalDelay: 300, mainClass: 'my-mfp-zoom-in' });
    $('.navbar-toggler').click(function () { $('body').toggleClass('noscroll'); });
  });
  $(window).on("scroll", function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 80) { $("#site-header").addClass("nav-fixed"); } else { $("#site-header").removeClass("nav-fixed"); }
  });
  function topFunction() { document.body.scrollTop = 0; document.documentElement.scrollTop = 0; }
  window.onscroll = function () {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      document.getElementById("movetop").style.display = "block";
    } else {
      document.getElementById("movetop").style.display = "none";
    }
  };
</script>
</body>
</html>

