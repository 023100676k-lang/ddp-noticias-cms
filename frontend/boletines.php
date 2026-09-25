<?php
require_once 'conexion.php';
require_once 'header_frontend.php';

$boletines = $conexion->query("SELECT id, numero_boletin, resumen, foto_portada, archivo_pdf, fecha_publicacion 
                                FROM boletines 
                                ORDER BY fecha_publicacion DESC, id DESC");
?>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .boletines-hero {
        background: linear-gradient(135deg, #1B2A4A 0%, #0F1A30 100%);
        padding: 80px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .boletines-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(200,16,46,0.15) 1px, transparent 1px);
        background-size: 30px 30px;
    }
    .boletines-hero h1 {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 52px;
        font-weight: 900;
        margin: 0;
        position: relative;
        z-index: 2;
    }
    .boletines-hero p {
        color: rgba(255,255,255,0.7);
        font-size: 16px;
        margin-top: 15px;
        position: relative;
        z-index: 2;
    }

    .boletines-grid {
        padding: 70px 0;
        background: #F8FAFF;
    }

    .boletin-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 480px;
        position: relative;
    }
    .boletin-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 70px rgba(200,16,46,0.2);
    }
    .boletin-card .img-wrapper {
        position: relative;
        width: 100%;
        height: 300px;
        overflow: hidden;
        background: #F4F6FA;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .boletin-card .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .boletin-card:hover .img-wrapper img {
        transform: scale(1.08);
    }
    /* Franja roja cóncava */
    .boletin-card .img-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 110px;
        height: 110px;
        background: #C8102E;
        -webkit-mask-image: radial-gradient(circle at 0 100%, transparent 70%, #000 70%);
        mask-image: radial-gradient(circle at 0 100%, transparent 70%, #000 70%);
        z-index: 2;
        pointer-events: none;
    }
    .boletin-card .info {
        padding: 25px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .boletin-card .numero {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(200,16,46,0.1);
        color: #C8102E;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
        align-self: flex-start;
    }
    .boletin-card .fecha {
        color: #7A8BA8;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }
    .boletin-card .titulo {
        font-family: 'Playfair Display', serif;
        color: #1B2A4A;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.3;
        margin: 0 0 12px 0;
        min-height: 60px;
    }
    .boletin-card .resumen {
        color: #5A6B85;
        font-size: 14px;
        line-height: 1.6;
        margin: 0 0 20px 0;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .boletin-card .btn-leer {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #C8102E;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-top: auto;
        transition: gap 0.3s ease;
    }
    .boletin-card:hover .btn-leer {
        gap: 15px;
    }

    .no-boletines {
        text-align: center;
        padding: 80px 20px;
        color: #7A8BA8;
    }

    @media (max-width: 768px) {
        .boletines-hero h1 { font-size: 32px; }
        .boletines-hero { padding: 50px 0; }
    }
</style>

<!-- Hero -->
<!-- Título -->
<div class="container" style="padding-top: 120px; padding-bottom: 20px;">
    <div class="row">
        <div class="col-md-12">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 42px; font-weight: 900; color: #1B2A4A; margin: 0; padding-bottom: 20px; position: relative;">
                Boletín NTEP
                <span style="position: absolute; bottom: 0; left: 0; width: 80px; height: 5px; background: linear-gradient(90deg, #C8102E, #8B0A1F); border-radius: 3px;"></span>
            </h2>
            <p style="color: #7A8BA8; font-size: 16px; margin-top: 15px;">Información destacada, análisis y noticias en formato PDF</p>
        </div>
    </div>
</div>

<!-- Grid de boletines -->
<section class="boletines-grid">
    <div class="container">
        <div class="row">
            <?php if ($boletines && $boletines->num_rows > 0): 
                while ($bol = $boletines->fetch_assoc()): 
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="boletin.php?id=<?php echo $bol['id']; ?>" class="boletin-card">
                    <div class="img-wrapper">
                        <img src="../<?php echo htmlspecialchars($bol['foto_portada'] ?: 'images/logo.png'); ?>" 
                             alt="Boletín <?php echo htmlspecialchars($bol['numero_boletin']); ?>">
                    </div>
                    <div class="info">
                        <span class="numero">📄 N° <?php echo htmlspecialchars($bol['numero_boletin']); ?></span>
                        <span class="fecha"><?php echo date('d \d\e F \d\e Y', strtotime($bol['fecha_publicacion'])); ?></span>
                        <h3 class="titulo">Boletín NTEP <?php echo htmlspecialchars($bol['numero_boletin']); ?></h3>
                        <p class="resumen"><?php echo htmlspecialchars($bol['resumen'] ?? 'Sin descripción.'); ?></p>
                        <span class="btn-leer">Ver detalle <span class="fa fa-arrow-right"></span></span>
                    </div>
                </a>
            </div>
            <?php endwhile; else: ?>
            <div class="col-12">
                <div class="no-boletines">
                    <h3>No hay boletines publicados aún</h3>
                    <p>Vuelve pronto para ver las nuevas ediciones.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'footer_frontend.php'; ?>

