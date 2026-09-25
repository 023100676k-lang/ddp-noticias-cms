<?php
require_once 'conexion.php';
require_once 'header_frontend.php';

$podcasts = $conexion->query("SELECT id, titulo, url_embed, fecha_publicacion 
                               FROM podcasts 
                               ORDER BY fecha_publicacion DESC, id DESC");

// Función para convertir URLs normales a formato embed
function convertir_url_embed($url) {
    if (empty($url)) return '';
    // YouTube
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }
    // Spotify
    if (strpos($url, 'spotify.com') !== false) {
        if (strpos($url, '/embed/') === false) {
            $url = str_replace('open.spotify.com/', 'open.spotify.com/embed/', $url);
        }
        return $url;
    }
    // SoundCloud
    if (strpos($url, 'soundcloud.com') !== false && strpos($url, 'w.soundcloud.com') === false) {
        return 'https://w.soundcloud.com/player/?url=' . urlencode($url) . '&color=%23C8102E&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false';
    }
    // Vimeo
    if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
        return 'https://player.vimeo.com/video/' . $matches[1];
    }
    return $url;
}
?>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Inter', sans-serif; background: #FAFBFC; }

    /* Hero */
    .podcast-hero {
        background: linear-gradient(135deg, #1B2A4A 0%, #0F1A30 100%);
        padding: 80px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .podcast-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(200,16,46,0.15) 1px, transparent 1px);
        background-size: 30px 30px;
    }
    .podcast-hero h1 {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 52px;
        font-weight: 900;
        margin: 0;
        position: relative;
        z-index: 2;
    }
    .podcast-hero h1::after {
        content: '';
        display: block;
        width: 80px;
        height: 5px;
        background: linear-gradient(90deg, #C8102E, #8B0A1F);
        margin: 20px auto 0;
        border-radius: 3px;
    }
    .podcast-hero p {
        color: rgba(255,255,255,0.75);
        font-size: 16px;
        margin-top: 20px;
        position: relative;
        z-index: 2;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Grid */
    .podcast-grid {
        padding: 70px 0;
        background: #F8FAFF;
    }

    /* Tarjeta de podcast */
    .podcast-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 380px;
        position: relative;
    }
    .podcast-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 70px rgba(200,16,46,0.18);
    }

    /* Reproductor embebido */
    .podcast-card .embed-wrapper {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 */
        background: #0F1A30;
        overflow: hidden;
    }
    .podcast-card .embed-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* Franja roja cóncava sobre el reproductor */
    .podcast-card .embed-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 90px;
        height: 90px;
        background: #C8102E;
        -webkit-mask-image: radial-gradient(circle at 0 100%, transparent 70%, #000 70%);
        mask-image: radial-gradient(circle at 0 100%, transparent 70%, #000 70%);
        z-index: 3;
        pointer-events: none;
    }

    /* Info */
    .podcast-card .info {
        padding: 25px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .podcast-card .fecha {
        display: inline-block;
        color: #C8102E;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 12px;
    }
    .podcast-card .titulo {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        color: #1B2A4A;
        line-height: 1.4;
        margin: 0 0 20px 0;
        min-height: 75px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .podcast-card .btn-ver {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #C8102E;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-decoration: none;
        margin-top: auto;
        align-self: flex-start;
        transition: gap 0.3s ease;
    }
    .podcast-card .btn-ver:hover {
        gap: 15px;
        color: #8B0A1F;
    }

    /* No podcasts */
    .no-podcasts {
        text-align: center;
        padding: 80px 20px;
        color: #7A8BA8;
    }
    .no-podcasts h3 {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        color: #1B2A4A;
        margin-bottom: 15px;
    }

    @media (max-width: 768px) {
        .podcast-hero h1 { font-size: 32px; }
        .podcast-hero { padding: 50px 0; }
    }
</style>

<!-- Título -->
<div class="container" style="padding-top: 120px; padding-bottom: 20px;">
    <div class="row">
        <div class="col-md-12">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 42px; font-weight: 900; color: #1B2A4A; margin: 0; padding-bottom: 20px; position: relative;">
                Podcast
                <span style="position: absolute; bottom: 0; left: 0; width: 80px; height: 5px; background: linear-gradient(90deg, #C8102E, #8B0A1F); border-radius: 3px;"></span>
            </h2>
            <p style="color: #7A8BA8; font-size: 16px; margin-top: 15px;">Escucha nuestros episodios de análisis, entrevistas y reportajes en audio</p>
        </div>
    </div>
</div>

<!-- Grid de podcasts -->
<section class="podcast-grid">
    <div class="container">
        <div class="row">
            <?php if ($podcasts && $podcasts->num_rows > 0): 
                while ($pod = $podcasts->fetch_assoc()): 
                    $embed_url = convertir_url_embed($pod['url_embed']);
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="podcast-card">
                    <div class="embed-wrapper">
                        <?php if (!empty($embed_url)): ?>
                            <iframe 
                                src="<?php echo htmlspecialchars($embed_url); ?>" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen
                                loading="lazy">
                            </iframe>
                        <?php else: ?>
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #C8102E, #8B0A1F); display: flex; align-items: center; justify-content: center;">
                                <span class="fa fa-microphone" style="font-size: 60px; color: #fff;"></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="info">
                        <span class="fecha"><?php echo date('d M, Y', strtotime($pod['fecha_publicacion'])); ?></span>
                        <h3 class="titulo"><?php echo htmlspecialchars($pod['titulo']); ?></h3>
                        <?php if (!empty($pod['url_embed'])): ?>
                            <a href="<?php echo htmlspecialchars($pod['url_embed']); ?>" target="_blank" class="btn-ver">
                                Ver original <span class="fa fa-external-link"></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="col-12">
                <div class="no-podcasts">
                    <h3>No hay podcasts publicados aún</h3>
                    <p>Vuelve pronto para escuchar nuestros nuevos episodios.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'footer_frontend.php'; ?>

