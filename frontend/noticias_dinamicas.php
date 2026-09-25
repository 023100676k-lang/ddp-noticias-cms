<?php
// ============================================================
// Módulo dinámico de Noticias (Actualidad)
// ============================================================
require_once 'conexion.php';

// Obtener las últimas 6 noticias publicadas
$sql = "SELECT id, titulo, foto, link_externo, fecha_publicacion 
        FROM noticias 
        ORDER BY fecha_publicacion DESC, id DESC 
        LIMIT 6";
$noticias = $conexion->query($sql);

if ($noticias && $noticias->num_rows > 0):
    while ($noticia = $noticias->fetch_assoc()):
        // Ruta de la imagen (con fallback al logo)
        $ruta_foto = !empty($noticia['foto']) 
            ? '../' . htmlspecialchars($noticia['foto']) 
            : './recursos/logo-ddp.png';
        
        // Enlace (externo o interno)
        $enlace = !empty($noticia['link_externo']) 
            ? htmlspecialchars($noticia['link_externo']) 
            : '#';
        
        // Fecha formateada
        $fecha = date('F d, Y', strtotime($noticia['fecha_publicacion']));
?>
    <div class="col-lg-4 col-md-6 grids5-info mt-lg-0 mt-5">
        <a target="_blank" href="<?php echo $enlace; ?>" class="d-block">
            <img src="<?php echo $ruta_foto; ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>" class="img-fluid">
        </a>
        <div class="blog-info">
            <h5><?php echo $fecha; ?></h5>
            <h4>
                <a target="_blank" href="<?php echo $enlace; ?>" class="d-block">
                    <?php echo htmlspecialchars($noticia['titulo']); ?>
                </a>
            </h4>
            <a target="_blank" href="<?php echo $enlace; ?>" class="btn mt-4 p-0">
                Leer <span class="fa fa-arrow-right"></span>
            </a>
        </div>
    </div>
<?php
    endwhile;
else:
?>
    <div class="col-lg-12 text-center">
        <p class="text-muted">No hay noticias publicadas aún.</p>
    </div>
<?php endif; ?>
