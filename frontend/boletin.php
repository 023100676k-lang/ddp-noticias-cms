<?php
require_once 'conexion.php';
require_once 'header_frontend.php';

$id = $_GET['id'] ?? 0;
$boletin = null;

if ($id > 0) {
    $sql = "SELECT b.*, 
                   COALESCE(CONCAT(u.nombres, ' ', u.ap_paterno), 'Redacción DDP') AS autor
            FROM boletines b
            LEFT JOIN usuarios u ON b.usuario_id = u.id
            WHERE b.id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 1) {
        $boletin = $resultado->fetch_assoc();
    }
}

if (!$boletin) {
    echo '<section class="breadcrumb-area py-sm-5 py-4"><div class="container text-center"><h2 class="title-big">Boletín no encontrado</h2><a href="boletines.php" class="btn btn-style btn-primary mt-3">← Volver</a></div></section>';
    require_once 'footer_frontend.php';
    exit;
}

// Otros boletines
$otros = $conexion->query("SELECT id, numero_boletin, foto_portada, fecha_publicacion 
                            FROM boletines 
                            WHERE id != $id 
                            ORDER BY fecha_publicacion DESC 
                            LIMIT 3");
?>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Inter', sans-serif; background: #FAFBFC; }

    /* Barra de progreso */
    .progress-bar-read {
        position: fixed; top: 0; left: 0; height: 4px;
        background: linear-gradient(90deg, #C8102E, #E63946, #C8102E);
        z-index: 9999; width: 0%; transition: width 0.1s ease;
        box-shadow: 0 0 15px rgba(200,16,46,0.6);
    }

    /* Hero */
    .boletin-hero {
        position: relative;
        min-height: 500px;
        background: linear-gradient(135deg, #1B2A4A 0%, #0F1A30 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .boletin-hero::before {
        content: '';
        position: absolute; inset: 0;
        background-image: radial-gradient(circle, rgba(200,16,46,0.15) 1px, transparent 1px);
        background-size: 30px 30px;
        opacity: 0.6;
    }
    .boletin-hero-content {
        position: relative; z-index: 2;
        text-align: center;
        color: #fff;
        padding: 60px 20px;
        max-width: 800px;
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(200,16,46,0.95);
        color: #fff;
        padding: 10px 22px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 25px;
        box-shadow: 0 10px 40px rgba(200,16,46,0.5);
        animation: pulse 2.5s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { box-shadow: 0 10px 40px rgba(200,16,46,0.5); }
        50% { box-shadow: 0 10px 60px rgba(200,16,46,0.8); }
    }
    .hero-titulo {
        font-family: 'Playfair Display', serif !important;
        font-size: 52px; font-weight: 900;
        line-height: 1.15; color: #fff;
        margin-bottom: 25px;
        text-shadow: 0 4px 25px rgba(0,0,0,0.8);
        animation: fadeInUp 0.8s ease-out;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .hero-meta {
        display: flex; justify-content: center; flex-wrap: wrap;
        gap: 25px; font-size: 14px; color: rgba(255,255,255,0.95);
        animation: fadeInUp 1s ease-out;
    }
    .hero-meta-item {
        display: flex; align-items: center; gap: 8px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.6);
    }
    .hero-meta-item .icon {
        width: 32px; height: 32px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    /* Contenido */
    .contenido-premium {
        background: #fff;
        margin: -60px auto 0;
        max-width: 850px;
        padding: 60px 70px;
        border-radius: 20px;
        box-shadow: 0 30px 80px rgba(0,0,0,0.08);
        position: relative;
        z-index: 3;
    }

    /* Portada grande */
    .portada-grande {
        margin-bottom: 40px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    .portada-grande img {
        width: 100%; height: auto; display: block;
    }

    /* Resumen */
    .resumen-premium {
        position: relative;
        background: linear-gradient(135deg, #FFF8F8 0%, #FFF 100%);
        padding: 35px 40px 35px 60px;
        border-radius: 15px;
        margin-bottom: 40px;
        font-family: 'Playfair Display', serif;
        font-size: 19px;
        font-style: italic;
        color: #1B2A4A;
        line-height: 1.7;
        border-left: 6px solid #C8102E;
        box-shadow: 0 10px 30px rgba(200,16,46,0.08);
    }
    .resumen-premium::before {
        content: '"';
        position: absolute;
        top: -20px; left: 15px;
        font-family: 'Playfair Display', serif;
        font-size: 120px;
        color: #C8102E;
        opacity: 0.15;
        line-height: 1;
    }

    /* Caja de PDF */
    .pdf-premium {
        position: relative;
        background: linear-gradient(135deg, #1B2A4A 0%, #0F1A30 100%);
        padding: 45px;
        border-radius: 20px;
        text-align: center;
        margin: 50px 0;
        color: #fff;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(27,42,74,0.4);
    }
    .pdf-premium::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(200,16,46,0.4) 0%, transparent 70%);
        border-radius: 50%;
    }
    .pdf-premium-content { position: relative; z-index: 2; }
    .pdf-premium h4 {
        font-family: 'Playfair Display', serif;
        font-size: 24px; margin-bottom: 12px;
    }
    .pdf-premium p {
        color: rgba(255,255,255,0.75);
        margin-bottom: 25px;
        font-size: 15px;
    }
    .btn-pdf-premium {
        display: inline-flex; align-items: center; gap: 12px;
        background: #C8102E;
        color: #fff;
        padding: 16px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 2px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(200,16,46,0.5);
    }
    .btn-pdf-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 45px rgba(200,16,46,0.7);
        background: #E63946;
        color: #fff;
    }

    /* Info grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    .info-card {
        background: #F8FAFF;
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid #C8102E;
    }
    .info-card .label {
        font-size: 11px;
        color: #7A8BA8;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .info-card .valor {
        font-size: 18px;
        color: #1B2A4A;
        font-weight: 800;
    }

    /* Botón volver */
    .btn-volver-premium {
        display: inline-flex; align-items: center; gap: 12px;
        background: transparent;
        color: #C8102E;
        border: 2px solid #C8102E;
        padding: 15px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 2px;
        transition: all 0.3s ease;
    }
    .btn-volver-premium:hover {
        background: #C8102E;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(200,16,46,0.4);
    }

    /* Otros boletines */
    .otros-section {
        background: #F4F6FA;
        padding: 90px 0;
        margin-top: 80px;
    }
    .otros-section h3 {
        font-family: 'Playfair Display', serif;
        font-size: 34px;
        font-weight: 800;
        color: #1B2A4A;
        text-align: center;
        margin-bottom: 15px;
    }
    .otros-section h3::after {
        content: '';
        display: block;
        width: 80px; height: 5px;
        background: linear-gradient(90deg, #C8102E, #8B0A1F);
        margin: 20px auto 0;
        border-radius: 3px;
    }
    .card-otro {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        transition: all 0.4s ease;
        text-decoration: none;
        display: block;
        height: 100%;
    }
    .card-otro:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 70px rgba(200,16,46,0.15);
    }
    .card-otro .img-wrapper {
        position: relative;
        width: 100%;
        height: 180px;
        overflow: hidden;
        background: #F4F6FA;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card-otro .img-wrapper img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .card-otro:hover .img-wrapper img { transform: scale(1.08); }
    .card-otro .img-wrapper::after {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 90px; height: 90px;
        background: #C8102E;
        -webkit-mask-image: radial-gradient(circle at 0 100%, transparent 70%, #000 70%);
        mask-image: radial-gradient(circle at 0 100%, transparent 70%, #000 70%);
    }
    .card-otro .info { padding: 20px; }
    .card-otro .fecha {
        color: #C8102E;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 8px;
    }
    .card-otro .titulo {
        font-family: 'Playfair Display', serif;
        color: #1B2A4A;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.3;
        margin: 0;
    }

    @media (max-width: 768px) {
        .hero-titulo { font-size: 30px; }
        .boletin-hero { min-height: 400px; }
        .contenido-premium { padding: 40px 25px; margin: -40px 15px 0; }
        .resumen-premium { font-size: 16px; padding: 25px 25px 25px 40px; }
    }
</style>

<div class="progress-bar-read" id="progressBar"></div>

<!-- Hero -->
<div class="boletin-hero">
    <div class="boletin-hero-content">
        <span class="hero-badge">📄 Boletín NTEP</span>
        <h1 class="hero-titulo">Boletín N° <?php echo htmlspecialchars($boletin['numero_boletin']); ?></h1>
        <div class="hero-meta">
            <div class="hero-meta-item">
                <span class="icon">✍️</span>
                <span><?php echo htmlspecialchars($boletin['autor']); ?></span>
            </div>
            <div class="hero-meta-item">
                <span class="icon">📅</span>
                <span><?php echo date('d \d\e F \d\e Y', strtotime($boletin['fecha_publicacion'])); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Contenido -->
<div class="container">
    <div class="contenido-premium">

        <!-- Info cards -->
        <div class="info-grid">
            <div class="info-card">
                <div class="label">Número</div>
                <div class="valor"><?php echo htmlspecialchars($boletin['numero_boletin']); ?></div>
            </div>
            <div class="info-card">
                <div class="label">Fecha de publicación</div>
                <div class="valor"><?php echo date('d/m/Y', strtotime($boletin['fecha_publicacion'])); ?></div>
            </div>
            <div class="info-card">
                <div class="label">Autor</div>
                <div class="valor"><?php echo htmlspecialchars($boletin['autor']); ?></div>
            </div>
        </div>

        <!-- Portada grande -->
        <?php if (!empty($boletin['foto_portada'])): ?>
            <div class="portada-grande">
                <img src="../<?php echo htmlspecialchars($boletin['foto_portada']); ?>" 
                     alt="Portada Boletín <?php echo htmlspecialchars($boletin['numero_boletin']); ?>">
            </div>
        <?php endif; ?>

        <!-- Resumen -->
        <?php if (!empty($boletin['resumen'])): ?>
            <h3 style="font-family: 'Playfair Display', serif; color: #1B2A4A; font-size: 24px; margin-bottom: 20px;">Resumen del Boletín</h3>
            <div class="resumen-premium">
                <?php echo nl2br(htmlspecialchars($boletin['resumen'])); ?>
            </div>
        <?php endif; ?>

        <!-- PDF -->
        <?php if (!empty($boletin['archivo_pdf'])): ?>
            <div class="pdf-premium">
                <div class="pdf-premium-content">
                    <h4>📥 Descargar Boletín Completo</h4>
                    <p>Accede al documento completo en formato PDF</p>
                    <a href="../<?php echo htmlspecialchars($boletin['archivo_pdf']); ?>" target="_blank" class="btn-pdf-premium">
                        <span class="fa fa-download"></span> Descargar PDF
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Volver -->
        <div style="text-align: center; margin-top: 60px;">
            <a href="boletines.php" class="btn-volver-premium">← Volver a Boletines</a>
        </div>
    </div>
</div>

<!-- Otros boletines -->
<?php if ($otros && $otros->num_rows > 0): ?>
<section class="otros-section">
    <div class="container">
        <h3>Otros Boletines</h3>
        <div class="row" style="margin-top: 50px;">
            <?php while ($otro = $otros->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="boletin.php?id=<?php echo $otro['id']; ?>" class="card-otro">
                    <div class="img-wrapper">
                        <img src="../<?php echo htmlspecialchars($otro['foto_portada'] ?: 'images/logo.png'); ?>" alt="">
                    </div>
                    <div class="info">
                        <div class="fecha"><?php echo date('d M, Y', strtotime($otro['fecha_publicacion'])); ?></div>
                        <h4 class="titulo">Boletín N° <?php echo htmlspecialchars($otro['numero_boletin']); ?></h4>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
    window.addEventListener('scroll', function() {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;
        document.getElementById('progressBar').style.width = scrolled + '%';
    });
</script>

<?php require_once 'footer_frontend.php'; ?>
