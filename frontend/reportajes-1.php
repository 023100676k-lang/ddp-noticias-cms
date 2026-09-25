<?php
require_once 'conexion.php';
require_once 'header_frontend.php';

$reportajes = $conexion->query("SELECT id, titulo, resumen_corto, foto_principal, fecha_publicacion, es_destacado 
                                 FROM reportajes 
                                 ORDER BY fecha_publicacion DESC, id DESC");
?>
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

<div class="grids-block-5 py-1">
    <section class="py-lg-4 py-md-3">
        <div class="container">
            <div class="row">
                <?php if ($reportajes && $reportajes->num_rows > 0): 
                    while ($rep = $reportajes->fetch_assoc()): 
                ?>
                <div class="col-lg-4 col-md-6 grids5-info mt-5">
                    <a href="reportaje.php?id=<?php echo $rep['id']; ?>" class="d-block">
                        <img src="../<?php echo htmlspecialchars($rep['foto_principal'] ?: 'images/logo.png'); ?>" alt="" class="img-fluid">
                    </a>
                    <div class="blog-info">
                        <h5><?php echo date('M d, Y', strtotime($rep['fecha_publicacion'])); ?></h5>
                        <h4><a href="reportaje.php?id=<?php echo $rep['id']; ?>" class="d-block"><?php echo htmlspecialchars($rep['titulo']); ?></a></h4>
                        <?php if (!empty($rep['resumen_corto'])): ?>
                            <p style="font-size: 14px; color: #666;"><?php echo htmlspecialchars(substr($rep['resumen_corto'], 0, 120)); ?>...</p>
                        <?php endif; ?>
                        <a href="reportaje.php?id=<?php echo $rep['id']; ?>" class="btn mt-4 p-0">Leer <span class="fa fa-arrow-right"></span> </a>
                    </div>
                </div>
                <?php endwhile; else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No hay reportajes publicados aún.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php require_once 'footer_frontend.php'; ?>

