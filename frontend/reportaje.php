<?php
require_once 'conexion.php';
require_once 'header_frontend.php';

$id = $_GET['id'] ?? 0;
$reportaje = null;

if ($id > 0) {
    $sql = "SELECT r.*, 
                   COALESCE(CONCAT(a.nombres, ' ', a.ap_paterno), 'Redacción DDP') AS autor
            FROM reportajes r
            LEFT JOIN autores a ON r.autor_id = a.id
            WHERE r.id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 1) {
        $reportaje = $resultado->fetch_assoc();
    }
}

if (!$reportaje) {
    echo '<section class="breadcrumb-area py-sm-5 py-4"><div class="container text-center"><h2 class="title-big">Reportaje no encontrado</h2><a href="reportajes-1.php" class="btn btn-style btn-primary mt-3">← Volver</a></div></section>';
    require_once 'footer_frontend.php';
    exit;
}

// Calcular tiempo de lectura
$texto_plano = strip_tags($reportaje['desarrollo'] ?? '');
$palabras = str_word_count($texto_plano);
$minutos_lectura = max(1, ceil($palabras / 200));

// Reportajes relacionados
$relacionados = $conexion->query("SELECT id, titulo, foto_principal, fecha_publicacion 
                                   FROM reportajes 
                                   WHERE id != $id 
                                   ORDER BY RAND() 
                                   LIMIT 3");

// URL actual para compartir
$url_actual = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ============================================================
       DISEÑO PREMIUM - DDP NOTICIAS
       ============================================================ */
    body {
        font-family: 'Inter', sans-serif !important;
        background: #FAFBFC !important;
    }

    /* Barra de progreso de lectura */
    .progress-bar-read {
        position: fixed;
        top: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #C8102E 0%, #E63946 50%, #C8102E 100%);
        z-index: 9999;
        width: 0%;
        transition: width 0.1s ease;
        box-shadow: 0 0 15px rgba(200,16,46,0.6);
    }

    /* Hero con parallax */
    .hero-premium {
        position: relative;
        min-height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #0F1A30;
    }
    .hero-premium-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: blur(2px);
        transform: scale(1.1);
        transition: transform 0.5s ease;
    }
    .hero-premium::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(10,10,15,0.5) 0%, rgba(15,26,48,0.85) 60%, rgba(15,26,48,0.98) 100%);
        z-index: 1;
    }
    /* Patrón de puntos decorativo */
    .hero-premium::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(200,16,46,0.15) 1px, transparent 1px);
        background-size: 30px 30px;
        z-index: 1;
        opacity: 0.5;
    }
    .hero-premium-content {
        position: relative;
        z-index: 2;
        max-width: 950px;
        padding: 80px 30px;
        text-align: center;
        color: #fff;
    }

    /* Badge animado */
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
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

    /* Título con tipografía premium */
    .hero-titulo {
        font-family: 'Playfair Display', serif !important;
        font-size: 58px;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 30px;
        letter-spacing: -1px;
        text-shadow: 0 5px 30px rgba(0,0,0,0.5);
        animation: fadeInUp 0.8s ease-out;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Meta info */
    .hero-meta {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 25px;
        font-size: 14px;
        color: rgba(255,255,255,0.9);
        animation: fadeInUp 1s ease-out;
    }
    .hero-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .hero-meta-item .icon {
        width: 32px;
        height: 32px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    /* Flecha scroll */
    .scroll-indicator {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        color: rgba(255,255,255,0.6);
        font-size: 24px;
        animation: bounce 2s infinite;
    }
    @keyframes bounce {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50% { transform: translateX(-50%) translateY(-10px); }
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

    /* Caja de compartir */
    .compartir-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        margin-bottom: 40px;
    }
    .compartir-label {
        font-size: 12px;
        font-weight: 700;
        color: #7A8BA8;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .compartir-botones {
        display: flex;
        gap: 10px;
    }
    .compartir-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 16px;
    }
    .compartir-btn:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .btn-facebook { background: #1877F2; }
    .btn-twitter { background: #1DA1F2; }
    .btn-whatsapp { background: #25D366; }
    .btn-linkedin { background: #0A66C2; }

    /* Resumen destacado */
    .resumen-premium {
        position: relative;
        background: linear-gradient(135deg, #FFF8F8 0%, #FFFFFF 100%);
        padding: 35px 40px 35px 60px;
        border-radius: 15px;
        margin-bottom: 50px;
        font-family: 'Playfair Display', serif;
        font-size: 21px;
        font-style: italic;
        color: #1B2A4A;
        line-height: 1.6;
        border-left: 6px solid #C8102E;
        box-shadow: 0 10px 30px rgba(200,16,46,0.08);
    }
    .resumen-premium::before {
        content: '"';
        position: absolute;
        top: -20px;
        left: 15px;
        font-family: 'Playfair Display', serif;
        font-size: 120px;
        color: #C8102E;
        opacity: 0.15;
        line-height: 1;
    }

    /* Contenido del reportaje */
    .contenido-texto {
        font-size: 18px;
        line-height: 2;
        color: #2D2D2D;
    }
    .contenido-texto > *:first-child { margin-top: 0; }
    .contenido-texto p {
        margin-bottom: 28px;
        text-align: justify;
    }
    .contenido-texto h1, .contenido-texto h2, .contenido-texto h3 {
        font-family: 'Playfair Display', serif;
        color: #1B2A4A;
        font-weight: 800;
        margin: 50px 0 25px;
        line-height: 1.2;
    }
    .contenido-texto h2 { 
        font-size: 32px;
        position: relative;
        padding-left: 25px;
    }
    .contenido-texto h2::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        bottom: 8px;
        width: 5px;
        background: linear-gradient(180deg, #C8102E, #8B0A1F);
        border-radius: 3px;
    }
    .contenido-texto h3 { font-size: 24px; }
    .contenido-texto a {
        color: #C8102E;
        text-decoration: none;
        font-weight: 600;
        border-bottom: 2px solid rgba(200,16,46,0.3);
        transition: all 0.3s ease;
    }
    .contenido-texto a:hover {
        border-bottom-color: #C8102E;
        background: rgba(200,16,46,0.05);
    }
    .contenido-texto img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
        margin: 35px 0;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    .contenido-texto ul, .contenido-texto ol {
        margin: 30px 0;
        padding-left: 35px;
    }
    .contenido-texto li {
        margin-bottom: 15px;
        padding-left: 10px;
    }
    .contenido-texto li::marker {
        color: #C8102E;
        font-weight: 700;
        font-size: 1.1em;
    }
    .contenido-texto blockquote {
        position: relative;
        border-left: 5px solid #C8102E;
        padding: 30px 40px;
        margin: 40px 0;
        background: linear-gradient(135deg, #F8FAFF, #FFF);
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-style: italic;
        color: #1B2A4A;
        border-radius: 0 15px 15px 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .contenido-texto strong { color: #1B2A4A; font-weight: 700; }

    /* Caja PDF */
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
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(200,16,46,0.4) 0%, transparent 70%);
        border-radius: 50%;
    }
    .pdf-premium-content { position: relative; z-index: 2; }
    .pdf-premium h4 {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        margin-bottom: 12px;
    }
    .pdf-premium p {
        color: rgba(255,255,255,0.75);
        margin-bottom: 25px;
        font-size: 15px;
    }
    .btn-pdf-premium {
        display: inline-flex;
        align-items: center;
        gap: 12px;
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

    /* Botón volver */
    .btn-volver-premium {
        display: inline-flex;
        align-items: center;
        gap: 12px;
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

    /* Sección relacionados */
    .relacionados-premium {
        background: linear-gradient(180deg, #F4F6FA 0%, #FFFFFF 100%);
        padding: 90px 0;
        margin-top: 80px;
    }
    .relacionados-premium h3 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 800;
        color: #1B2A4A;
        text-align: center;
        margin-bottom: 15px;
    }
    .relacionados-premium .subtitulo {
        text-align: center;
        color: #7A8BA8;
        font-size: 15px;
        margin-bottom: 60px;
    }
    .relacionados-premium h3::after {
        content: '';
        display: block;
        width: 80px;
        height: 5px;
        background: linear-gradient(90deg, #C8102E, #8B0A1F);
        margin: 20px auto 0;
        border-radius: 3px;
    }

    /* Tarjeta relacionada premium */
    .card-rel-premium {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: block;
        height: 100%;
        position: relative;
    }
    .card-rel-premium:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 70px rgba(200,16,46,0.2);
    }
    .card-rel-premium .img-wrapper {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
    }
    .card-rel-premium .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .card-rel-premium:hover .img-wrapper img {
        transform: scale(1.1);
    }
    .card-rel-premium .img-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: #C8102E;
        -webkit-mask-image: radial-gradient(circle at 0 100%, transparent 70%, #000 70%);
        mask-image: radial-gradient(circle at 100% 0%, transparent 70%, #000 70%);
        z-index: 2;
    }
    .card-rel-premium .info {
        padding: 25px;
    }
    .card-rel-premium .fecha {
        display: inline-block;
        color: #C8102E;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 12px;
    }
    .card-rel-premium .titulo {
        color: #1B2A4A;
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.4;
        margin: 0;
        min-height: 75px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s ease;
    }
    .card-rel-premium:hover .titulo {
        color: #C8102E;
    }
    .card-rel-premium .leer-mas {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #C8102E;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-top: 18px;
        transition: gap 0.3s ease;
    }
    .card-rel-premium:hover .leer-mas { gap: 15px; }

    /* Animaciones de aparición al scroll */
    .fade-in-scroll {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }
    .fade-in-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-titulo { font-size: 32px; }
        .hero-premium { min-height: 450px; }
        .contenido-premium { padding: 40px 25px; margin: -40px 15px 0; }
        .contenido-texto { font-size: 16px; }
        .resumen-premium { font-size: 17px; padding: 25px 25px 25px 40px; }
        .relacionados-premium h3 { font-size: 26px; }
    }
</style>

<!-- Barra de progreso de lectura -->
<div class="progress-bar-read" id="progressBar"></div>

<!-- HERO PREMIUM -->
<div class="hero-premium">
    <div class="hero-premium-bg" style="background-image: url('../<?php echo htmlspecialchars($reportaje['foto_principal'] ?: 'images/logo.png'); ?>');"></div>
    <div class="hero-premium-content">
        <span class="hero-badge">
            <span>📰</span> Reportaje Especial
        </span>
        <h1 class="hero-titulo"><?php echo htmlspecialchars($reportaje['titulo']); ?></h1>
        <div class="hero-meta">
            <div class="hero-meta-item">
                <span class="icon">✍️</span>
                <span><?php echo htmlspecialchars($reportaje['autor']); ?></span>
            </div>
            <div class="hero-meta-item">
                <span class="icon">📅</span>
                <span><?php echo date('d \d\e F \d\e Y', strtotime($reportaje['fecha_publicacion'])); ?></span>
            </div>
            <div class="hero-meta-item">
                <span class="icon">⏱️</span>
                <span><?php echo $minutos_lectura; ?> min de lectura</span>
            </div>
        </div>
    </div>
    <div class="scroll-indicator">⌄</div>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div class="container">
    <div class="contenido-premium">
        
        <!-- Barra de compartir -->
        <div class="compartir-box">
            <span class="compartir-label">Compartir</span>
            <div class="compartir-botones">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url_actual); ?>" target="_blank" class="compartir-btn btn-facebook" title="Facebook">
                    <span class="fa fa-facebook"></span>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url_actual); ?>&text=<?php echo urlencode($reportaje['titulo']); ?>" target="_blank" class="compartir-btn btn-twitter" title="Twitter">
                    <span class="fa fa-twitter"></span>
                </a>
                <a href="https://wa.me/?text=<?php echo urlencode($reportaje['titulo'] . ' ' . $url_actual); ?>" target="_blank" class="compartir-btn btn-whatsapp" title="WhatsApp">
                    <span class="fa fa-whatsapp"></span>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($url_actual); ?>&title=<?php echo urlencode($reportaje['titulo']); ?>" target="_blank" class="compartir-btn btn-linkedin" title="LinkedIn">
                    <span class="fa fa-linkedin"></span>
                </a>
            </div>
        </div>

        <!-- Imagen principal -->
        <?php if (!empty($reportaje['foto_principal'])): ?>
            <div style="margin-bottom: 50px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.15);">
                <img src="../<?php echo htmlspecialchars($reportaje['foto_principal']); ?>" 
                     alt="<?php echo htmlspecialchars($reportaje['titulo']); ?>" 
                     style="width: 100%; height: auto; display: block;">
            </div>
        <?php endif; ?>

        <!-- Resumen destacado -->
        <?php if (!empty($reportaje['resumen_corto'])): ?>
            <div class="resumen-premium">
                <?php echo strip_tags($reportaje['resumen_corto']); ?>
            </div>
        <?php endif; ?>

        <!-- Contenido -->
        <div class="contenido-texto">
            <?php echo $reportaje['desarrollo']; ?>
        </div>

        <!-- PDF -->
        <?php if (!empty($reportaje['pdf_adjunto'])): ?>
            <div class="pdf-premium">
                <div class="pdf-premium-content">
                    <h4>📄 Documento Complementario</h4>
                    <p>Descarga el PDF con información adicional y datos ampliados</p>
                    <a href="../<?php echo htmlspecialchars($reportaje['pdf_adjunto']); ?>" target="_blank" class="btn-pdf-premium">
                        <span class="fa fa-download"></span> Descargar PDF
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Botón volver -->
        <div style="text-align: center; margin-top: 60px;">
            <a href="reportajes-1.php" class="btn-volver-premium">
                ← Volver a Reportajes
            </a>
        </div>
    </div>
</div>

<!-- RELACIONADOS PREMIUM -->
<?php if ($relacionados && $relacionados->num_rows > 0): ?>
<section class="relacionados-premium">
    <div class="container">
        <h3>Sigue Explorando</h3>
        <p class="subtitulo">Otros reportajes que podrían interesarte</p>
        <div class="row">
            <?php while ($rel = $relacionados->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="reportaje.php?id=<?php echo $rel['id']; ?>" class="card-rel-premium fade-in-scroll">
                    <div class="img-wrapper">
                        <img src="../<?php echo htmlspecialchars($rel['foto_principal'] ?: 'images/logo.png'); ?>" alt="">
                    </div>
                    <div class="info">
                        <span class="fecha"><?php echo date('d M, Y', strtotime($rel['fecha_publicacion'])); ?></span>
                        <h4 class="titulo"><?php echo htmlspecialchars($rel['titulo']); ?></h4>
                        <span class="leer-mas">Leer más <span class="fa fa-arrow-right"></span></span>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
    // Barra de progreso de lectura
    window.addEventListener('scroll', function() {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;
        document.getElementById('progressBar').style.width = scrolled + '%';
    });

    // Animación de aparición al scroll
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.fade-in-scroll').forEach(function(el) {
        observer.observe(el);
    });
</script>

<?php require_once 'footer_frontend.php'; ?>
