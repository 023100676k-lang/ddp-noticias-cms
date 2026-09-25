<?php
require_once 'conexion.php';
require_once 'header_frontend.php';

$alianzas = $conexion->query("SELECT id, nombre, logo, url, descripcion, fecha_alianza 
                               FROM alianzas 
                               ORDER BY fecha_alianza DESC, id DESC");
?>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Inter', sans-serif; background: #FAFBFC; }

    /* Hero */
    .alianzas-hero {
        background: linear-gradient(135deg, #1B2A4A 0%, #0F1A30 100%);
        padding: 80px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .alianzas-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(200,16,46,0.15) 1px, transparent 1px);
        background-size: 30px 30px;
    }
    .alianzas-hero h1 {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 52px;
        font-weight: 900;
        margin: 0;
        position: relative;
        z-index: 2;
    }
    .alianzas-hero h1::after {
        content: '';
        display: block;
        width: 80px;
        height: 5px;
        background: linear-gradient(90deg, #C8102E, #8B0A1F);
        margin: 20px auto 0;
        border-radius: 3px;
    }
    .alianzas-hero p {
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
    .alianzas-grid {
        padding: 70px 0;
        background: #F8FAFF;
    }

    /* Tarjeta de alianza */
    .alianza-card {
        background: #fff;
        border-radius: 20px;
        padding: 40px 30px 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 420px;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    .alianza-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 70px rgba(200,16,46,0.18);
    }

    /* Franja roja cóncava */
    .alianza-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: #C8102E;
        -webkit-mask-image: radial-gradient(circle at 0 100%, transparent 70%, #000 70%);
        mask-image: radial-gradient(circle at 0 100%, transparent 70%, #000 70%);
        z-index: 2;
        pointer-events: none;
    }

    /* Contenedor del logo */
    .alianza-card .logo-wrapper {
        width: 100%;
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        position: relative;
    }
    .alianza-card .logo-wrapper img {
        max-width: 100%;
        max-height: 160px;
        width: auto;
        height: auto;
        object-fit: contain;
        transition: transform 0.4s ease;
        filter: grayscale(0.2);
    }
    .alianza-card:hover .logo-wrapper img {
        transform: scale(1.08);
        filter: grayscale(0);
    }

    /* Nombre */
    .alianza-card .nombre {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-weight: 800;
        color: #1B2A4A;
        margin: 0 0 15px 0;
        line-height: 1.3;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Descripción */
    .alianza-card .descripcion {
        font-size: 14px;
        color: #5A6B85;
        line-height: 1.6;
        margin: 0 0 25px 0;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Fecha */
    .alianza-card .fecha {
        display: inline-block;
        font-size: 11px;
        color: #C8102E;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 15px;
    }

    /* Botón */
    .alianza-card .btn-visitar {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #C8102E, #8B0A1F);
        color: #fff;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: all 0.3s ease;
        margin-top: auto;
        align-self: center;
        box-shadow: 0 8px 20px rgba(200,16,46,0.3);
    }
    .alianza-card:hover .btn-visitar {
        box-shadow: 0 12px 30px rgba(200,16,46,0.5);
        transform: translateY(-2px);
    }
    .alianza-card .btn-visitar span.fa {
        transition: transform 0.3s ease;
    }
    .alianza-card:hover .btn-visitar span.fa {
        transform: translateX(5px);
    }

    /* Sin alianzas */
    .no-alianzas {
        text-align: center;
        padding: 80px 20px;
        color: #7A8BA8;
    }
    .no-alianzas h3 {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        color: #1B2A4A;
        margin-bottom: 15px;
    }

    @media (max-width: 768px) {
        .alianzas-hero h1 { font-size: 32px; }
        .alianzas-hero { padding: 50px 0; }
        .alianza-card { min-height: 380px; }
    }
</style>

<!-- Hero -->
<!-- Título -->
<div class="container" style="padding-top: 120px; padding-bottom: 20px;">
    <div class="row">
        <div class="col-md-12">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 42px; font-weight: 900; color: #1B2A4A; margin: 0; padding-bottom: 20px; position: relative;">
                Alianzas
                <span style="position: absolute; bottom: 0; left: 0; width: 80px; height: 5px; background: linear-gradient(90deg, #C8102E, #8B0A1F); border-radius: 3px;"></span>
            </h2>
            <p style="color: #7A8BA8; font-size: 16px; margin-top: 15px;">Instituciones y organizaciones que confían en Diálogo y Desarrollo Perú para construir un país más informado</p>
        </div>
    </div>
</div>

<!-- Grid de alianzas -->
<section class="alianzas-grid">
    <div class="container">
        <div class="row">
            <?php if ($alianzas && $alianzas->num_rows > 0): 
                while ($al = $alianzas->fetch_assoc()): 
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="alianza-card">
                    <div class="logo-wrapper">
                        <img src="../<?php echo htmlspecialchars($al['logo'] ?: 'images/logo.png'); ?>" 
                             alt="<?php echo htmlspecialchars($al['nombre']); ?>">
                    </div>
                    <span class="fecha"><?php echo date('F Y', strtotime($al['fecha_alianza'])); ?></span>
                    <h3 class="nombre"><?php echo htmlspecialchars($al['nombre']); ?></h3>
                    <p class="descripcion"><?php echo htmlspecialchars($al['descripcion'] ?? 'Sin descripción.'); ?></p>
                    <?php if (!empty($al['url'])): ?>
                        <a href="<?php echo htmlspecialchars($al['url']); ?>" target="_blank" class="btn-visitar">
                            Visitar sitio <span class="fa fa-arrow-right"></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; else: ?>
            <div class="col-12">
                <div class="no-alianzas">
                    <h3>No hay alianzas registradas aún</h3>
                    <p>Vuelve pronto para ver nuestras nuevas alianzas estratégicas.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'footer_frontend.php'; ?>

