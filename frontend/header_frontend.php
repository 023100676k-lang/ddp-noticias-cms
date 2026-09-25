<?php
// Header común para todas las páginas del frontend
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DDP Noticias - Diálogo y Desarrollo Perú</title>
    <link href="./recursos/css" rel="stylesheet">
    <link rel="stylesheet" href="./recursos/style-starter.css">
    <!-- Font Awesome para iconos (Facebook, WhatsApp, Instagram, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
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
                  <li class="nav-item <?php echo $pagina_actual == 'index.php' ? 'active' : ''; ?>">
                      <a class="nav-link" href="index.php">Inicio</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="index.php#actualidad">Actualidad</a>
                  </li>
                  <li class="nav-item <?php echo $pagina_actual == 'reportajes-1.php' ? 'active' : ''; ?>">
                      <a class="nav-link" href="reportajes-1.php">Reportajes</a>
                  </li>
                  <li class="nav-item <?php echo $pagina_actual == 'podcast.php' ? 'active' : ''; ?>">
                      <a class="nav-link" href="podcast.php">Podcast</a>
                  </li>
                  <li class="nav-item <?php echo $pagina_actual == 'boletines.php' ? 'active' : ''; ?>">
                      <a class="nav-link" href="boletines.php">Boletín NTEP</a>
                  </li>
                  <li class="nav-item <?php echo $pagina_actual == 'alianzas.php' ? 'active' : ''; ?>">
                      <a class="nav-link" href="alianzas.php">Alianzas</a>
                  </li>
                  <li class="nav-item <?php echo $pagina_actual == 'contact.php' ? 'active' : ''; ?>">
                      <a class="nav-link" href="contact.php">Sobre D&amp;D</a>
                  </li>
                  <li class="ml-2">
                      <a href="contact.php" class="btn btn-style btn-outline-secondary">Contacto</a>
                  </li>
              </ul>
          </div>
      </nav>
  </div>
</header>
