<?php
require_once 'verificar_sesion.php';
require_once 'conexion.php';

// ========== ESTADÍSTICAS ==========
function contar($conexion, $tabla) {
    $r = $conexion->query("SELECT COUNT(*) AS total FROM $tabla");
    return $r ? $r->fetch_assoc()['total'] : 0;
}

$stats = [
    'noticias' => contar($conexion, 'noticias'),
    'reportajes' => contar($conexion, 'reportajes'),
    'podcasts' => contar($conexion, 'podcasts'),
    'boletines' => contar($conexion, 'boletines'),
    'alianzas' => contar($conexion, 'alianzas'),
    'usuarios' => contar($conexion, 'usuarios')
];
$stats['total'] = $stats['noticias'] + $stats['reportajes'] + $stats['podcasts'] + $stats['boletines'] + $stats['alianzas'];

// ========== ACTIVIDAD POR MES (últimos 6 meses) ==========
$datos_mes = [];
for ($i = 5; $i >= 0; $i--) {
    $mes = date('Y-m', strtotime("-$i months"));
    $nombre_mes = date('M', strtotime("-$i months"));
    
    $n = $conexion->query("SELECT COUNT(*) AS t FROM noticias WHERE DATE_FORMAT(fecha_publicacion, '%Y-%m') = '$mes'")->fetch_assoc()['t'] ?? 0;
    $r = $conexion->query("SELECT COUNT(*) AS t FROM reportajes WHERE DATE_FORMAT(fecha_publicacion, '%Y-%m') = '$mes'")->fetch_assoc()['t'] ?? 0;
    $p = $conexion->query("SELECT COUNT(*) AS t FROM podcasts WHERE DATE_FORMAT(fecha_publicacion, '%Y-%m') = '$mes'")->fetch_assoc()['t'] ?? 0;
    
    $datos_mes[] = ['mes' => $nombre_mes, 'noticias' => $n, 'reportajes' => $r, 'podcasts' => $p, 'total' => $n + $r + $p];
}

// ========== ÚLTIMAS PUBLICACIONES ==========
$ultimas = [];
$r = $conexion->query("SELECT id, titulo, fecha_publicacion FROM noticias ORDER BY id DESC LIMIT 2");
while ($f = $r->fetch_assoc()) { $f['tipo']='Actualidad'; $f['color']='#C8102E'; $f['icono']='newspaper'; $ultimas[]=$f; }
$r = $conexion->query("SELECT id, titulo, fecha_publicacion FROM reportajes ORDER BY id DESC LIMIT 2");
while ($f = $r->fetch_assoc()) { $f['tipo']='Reportaje'; $f['color']='#1B2A4A'; $f['icono']='camera'; $ultimas[]=$f; }
$r = $conexion->query("SELECT id, titulo, fecha_publicacion FROM podcasts ORDER BY id DESC LIMIT 2");
while ($f = $r->fetch_assoc()) { $f['tipo']='Podcast'; $f['color']='#FFC107'; $f['icono']='microphone'; $ultimas[]=$f; }
usort($ultimas, fn($a,$b) => strtotime($b['fecha_publicacion']) - strtotime($a['fecha_publicacion']));
$ultimas = array_slice($ultimas, 0, 6);

// ========== USUARIOS RECIENTES ==========
$usuarios_recientes = [];
$r = $conexion->query("SELECT nombres, ap_paterno, email, rol, created_at FROM usuarios ORDER BY created_at DESC LIMIT 3");
while ($f = $r->fetch_assoc()) $usuarios_recientes[] = $f;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard | DDP Noticias</title>
    <link rel="icon" type="image/png" sizes="16x16" href="./images/logo.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/ddp-estilos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        /* === KPI CARDS === */
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px; }
        .kpi-card {
            background: #fff;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 4px 25px rgba(27,42,74,0.06);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: block;
            border: 1px solid rgba(0,0,0,0.03);
        }
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
            background: var(--kpi-color);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        .kpi-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(200,16,46,0.15);
        }
        .kpi-card:hover::before { transform: scaleX(1); }
        .kpi-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: #fff;
            margin-bottom: 15px;
            background: var(--kpi-gradient);
            box-shadow: 0 8px 20px var(--kpi-shadow);
        }
        .kpi-label {
            font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px;
            color: #7A8BA8; font-weight: 700; margin-bottom: 8px;
        }
        .kpi-value {
            font-size: 34px; font-weight: 800; color: #1B2A4A;
            line-height: 1; margin-bottom: 10px;
        }
        .kpi-trend {
            font-size: 12px; font-weight: 600;
            display: flex; align-items: center; gap: 5px;
        }
        .trend-up { color: #00D68F; }
        .trend-neutral { color: #7A8BA8; }

        /* === BANNER === */
        .hero-banner {
            background: linear-gradient(135deg, #1B2A4A 0%, #0F1A30 50%, #2C3E60 100%);
            border-radius: 24px;
            padding: 35px 40px;
            color: #fff;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(27,42,74,0.3);
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(200,16,46,0.5) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 4s ease-in-out infinite;
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(200,16,46,0.3) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 6s ease-in-out infinite reverse;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }
        .hero-content { position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .hero-text h1 { color: #fff; font-weight: 800; font-size: 28px; margin: 0 0 8px 0; }
        .hero-text p { color: rgba(255,255,255,0.7); font-size: 14px; margin: 0; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 10px;
            background: rgba(200,16,46,0.95);
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 10px 30px rgba(200,16,46,0.5);
        }
        .hero-badge .num { font-size: 22px; font-weight: 800; }

        /* === CHART CARDS === */
        .chart-card {
            background: #fff;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 4px 25px rgba(27,42,74,0.06);
            margin-bottom: 25px;
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }
        .chart-card:hover { box-shadow: 0 15px 40px rgba(27,42,74,0.1); }
        .chart-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px; padding-bottom: 15px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .chart-title {
            font-size: 15px; font-weight: 700; color: #1B2A4A;
            text-transform: uppercase; letter-spacing: 1px;
            display: flex; align-items: center; gap: 10px;
        }
        .chart-title::before {
            content: '';
            width: 4px; height: 20px;
            background: linear-gradient(180deg, #C8102E, #8B0A1F);
            border-radius: 2px;
        }
        .chart-legend { display: flex; gap: 15px; font-size: 12px; color: #7A8BA8; }
        .legend-item { display: flex; align-items: center; gap: 6px; font-weight: 600; }
        .legend-dot { width: 10px; height: 10px; border-radius: 50%; }

        /* === TIMELINE === */
        .timeline-item {
            display: flex; gap: 15px;
            padding: 15px;
            border-radius: 12px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .timeline-item:hover {
            background: #F8FAFF;
            border-left-color: #C8102E;
            transform: translateX(5px);
        }
        .timeline-icon {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .timeline-content { flex: 1; min-width: 0; }
        .timeline-title {
            font-weight: 600; color: #1B2A4A; font-size: 13px;
            margin: 0 0 4px 0;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .timeline-meta {
            font-size: 11px; color: #7A8BA8;
            display: flex; gap: 10px; align-items: center;
        }
        .badge-tipo {
            font-size: 10px; padding: 3px 10px;
            border-radius: 20px; color: #fff;
            font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* === USER LIST === */
        .user-row {
            display: flex; align-items: center; gap: 12px;
            padding: 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .user-row:hover { background: #F8FAFF; }
        .user-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #C8102E, #8B0A1F);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
        }
        .user-info { flex: 1; }
        .user-name { font-weight: 600; color: #1B2A4A; font-size: 13px; margin: 0; }
        .user-email { font-size: 11px; color: #7A8BA8; margin: 0; }
        .user-rol {
            font-size: 10px; padding: 3px 10px;
            border-radius: 20px; background: rgba(200,16,46,0.1);
            color: #C8102E; font-weight: 700; text-transform: uppercase;
        }

        /* === SECTION TITLE === */
        .section-title {
            font-size: 18px; font-weight: 800; color: #1B2A4A;
            margin: 30px 0 20px 0;
            display: flex; align-items: center; gap: 12px;
        }
        .section-title::before {
            content: '';
            width: 5px; height: 25px;
            background: linear-gradient(180deg, #C8102E, #8B0A1F);
            border-radius: 3px;
        }
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
                                <div class="dropdown-menu p-0 m-0"><form><input class="form-control" type="search" placeholder="Buscar"></form></div>
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
                    <li class="active"><a href="./index.php"><i class="icon icon-single-04"></i><span class="nav-text">Dashboard</span></a></li>
                    <li class="nav-label">Secciones</li>
                    <li><a href="./actualidad.php"><i class="icon icon-newspaper"></i><span class="nav-text">Actualidad</span></a></li>
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

                <!-- BANNER HERO -->
                <div class="hero-banner">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h1>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>! 👋</h1>
                            <p>Panel de administración de DDP Noticias — <?php echo date('l, d \d\e F \d\e Y'); ?></p>
                        </div>
                        <div class="hero-badge">
                            📊 <span class="num"><?php echo $stats['total']; ?></span> publicaciones
                        </div>
                    </div>
                </div>

                <!-- KPIs -->
                <div class="kpi-grid">
                    <a href="./actualidad.php" class="kpi-card" style="--kpi-color: #C8102E; --kpi-gradient: linear-gradient(135deg, #C8102E, #8B0A1F); --kpi-shadow: rgba(200,16,46,0.3);">
                        <div class="kpi-icon"><i class="icon icon-newspaper"></i></div>
                        <div class="kpi-label">Noticias</div>
                        <div class="kpi-value"><?php echo $stats['noticias']; ?></div>
                        <div class="kpi-trend <?php echo $stats['noticias'] > 0 ? 'trend-up' : 'trend-neutral'; ?>">
                            <?php echo $stats['noticias'] > 0 ? '↑ Activo' : '○ Sin datos'; ?>
                        </div>
                    </a>
                    <a href="./reportajes.php" class="kpi-card" style="--kpi-color: #1B2A4A; --kpi-gradient: linear-gradient(135deg, #1B2A4A, #0F1A30); --kpi-shadow: rgba(27,42,74,0.3);">
                        <div class="kpi-icon"><i class="icon icon-camera"></i></div>
                        <div class="kpi-label">Reportajes</div>
                        <div class="kpi-value"><?php echo $stats['reportajes']; ?></div>
                        <div class="kpi-trend <?php echo $stats['reportajes'] > 0 ? 'trend-up' : 'trend-neutral'; ?>">
                            <?php echo $stats['reportajes'] > 0 ? '↑ Activo' : '○ Sin datos'; ?>
                        </div>
                    </a>
                    <a href="./podcast.php" class="kpi-card" style="--kpi-color: #FFC107; --kpi-gradient: linear-gradient(135deg, #FFC107, #FFA500); --kpi-shadow: rgba(255,193,7,0.3);">
                        <div class="kpi-icon"><i class="icon icon-microphone"></i></div>
                        <div class="kpi-label">Podcasts</div>
                        <div class="kpi-value"><?php echo $stats['podcasts']; ?></div>
                        <div class="kpi-trend <?php echo $stats['podcasts'] > 0 ? 'trend-up' : 'trend-neutral'; ?>">
                            <?php echo $stats['podcasts'] > 0 ? '↑ Activo' : '○ Sin datos'; ?>
                        </div>
                    </a>
                    <a href="./boletin.php" class="kpi-card" style="--kpi-color: #00D68F; --kpi-gradient: linear-gradient(135deg, #00D68F, #00B377); --kpi-shadow: rgba(0,214,143,0.3);">
                        <div class="kpi-icon"><i class="icon icon-envelope"></i></div>
                        <div class="kpi-label">Boletines NTEP</div>
                        <div class="kpi-value"><?php echo $stats['boletines']; ?></div>
                        <div class="kpi-trend <?php echo $stats['boletines'] > 0 ? 'trend-up' : 'trend-neutral'; ?>">
                            <?php echo $stats['boletines'] > 0 ? '↑ Activo' : '○ Sin datos'; ?>
                        </div>
                    </a>
                    <a href="./alianzas.php" class="kpi-card" style="--kpi-color: #3699FF; --kpi-gradient: linear-gradient(135deg, #3699FF, #1B7FDD); --kpi-shadow: rgba(54,153,255,0.3);">
                        <div class="kpi-icon"><i class="icon icon-handshake"></i></div>
                        <div class="kpi-label">Alianzas</div>
                        <div class="kpi-value"><?php echo $stats['alianzas']; ?></div>
                        <div class="kpi-trend <?php echo $stats['alianzas'] > 0 ? 'trend-up' : 'trend-neutral'; ?>">
                            <?php echo $stats['alianzas'] > 0 ? '↑ Activo' : '○ Sin datos'; ?>
                        </div>
                    </a>
                    <a href="./perfil.php" class="kpi-card" style="--kpi-color: #7A8BA8; --kpi-gradient: linear-gradient(135deg, #7A8BA8, #4A5568); --kpi-shadow: rgba(122,139,168,0.3);">
                        <div class="kpi-icon"><i class="icon icon-user"></i></div>
                        <div class="kpi-label">Usuarios</div>
                        <div class="kpi-value"><?php echo $stats['usuarios']; ?></div>
                        <div class="kpi-trend trend-up">● Sistema</div>
                    </a>
                </div>

                <!-- GRÁFICOS -->
                <div class="section-title">Análisis de Actividad</div>
                <div class="row">
                    <!-- GRÁFICO DE DONA -->
                    <div class="col-lg-4">
                        <div class="chart-card">
                            <div class="chart-header">
                                <div class="chart-title">Distribución</div>
                            </div>
                            <div style="position: relative; height: 280px;">
                                <canvas id="chartDona"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- GRÁFICO DE BARRAS -->
                    <div class="col-lg-8">
                        <div class="chart-card">
                            <div class="chart-header">
                                <div class="chart-title">Publicaciones por Mes</div>
                                <div class="chart-legend">
                                    <div class="legend-item"><span class="legend-dot" style="background: #C8102E;"></span> Noticias</div>
                                    <div class="legend-item"><span class="legend-dot" style="background: #1B2A4A;"></span> Reportajes</div>
                                    <div class="legend-item"><span class="legend-dot" style="background: #FFC107;"></span> Podcasts</div>
                                </div>
                            </div>
                            <div style="position: relative; height: 280px;">
                                <canvas id="chartBarras"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GRÁFICO DE LÍNEA -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="chart-card">
                            <div class="chart-header">
                                <div class="chart-title">Tendencia General (Últimos 6 Meses)</div>
                            </div>
                            <div style="position: relative; height: 200px;">
                                <canvas id="chartLinea"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÚLTIMAS PUBLICACIONES Y USUARIOS -->
                <div class="section-title">Actividad Reciente</div>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="chart-card">
                            <div class="chart-header">
                                <div class="chart-title">Últimas Publicaciones</div>
                            </div>
                            <?php if (empty($ultimas)): ?>
                                <p class="text-center text-muted py-4">No hay publicaciones aún. ¡Crea la primera!</p>
                            <?php else: ?>
                                <?php foreach ($ultimas as $item): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-icon" style="background: <?php echo $item['color']; ?>;">
                                            <i class="icon icon-<?php echo $item['icono']; ?>"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <p class="timeline-title"><?php echo htmlspecialchars($item['titulo']); ?></p>
                                            <div class="timeline-meta">
                                                <span class="badge-tipo" style="background: <?php echo $item['color']; ?>;"><?php echo $item['tipo']; ?></span>
                                                <span><?php echo date('d/m/Y', strtotime($item['fecha_publicacion'])); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="chart-card">
                            <div class="chart-header">
                                <div class="chart-title">Usuarios Recientes</div>
                            </div>
                            <?php if (empty($usuarios_recientes)): ?>
                                <p class="text-center text-muted py-4">No hay usuarios.</p>
                            <?php else: ?>
                                <?php foreach ($usuarios_recientes as $u): ?>
                                    <div class="user-row">
                                        <div class="user-avatar"><?php echo strtoupper(substr($u['nombres'], 0, 1)); ?></div>
                                        <div class="user-info">
                                            <p class="user-name"><?php echo htmlspecialchars($u['nombres'] . ' ' . $u['ap_paterno']); ?></p>
                                            <p class="user-email"><?php echo htmlspecialchars($u['email']); ?></p>
                                        </div>
                                        <span class="user-rol"><?php echo htmlspecialchars($u['rol']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
        // Configuración global de Chart.js
        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.font.size = 11;
        Chart.defaults.color = '#7A8BA8';

        // ========== GRÁFICO DE DONA ==========
        new Chart(document.getElementById('chartDona'), {
            type: 'doughnut',
            data: {
                labels: ['Noticias', 'Reportajes', 'Podcasts', 'Boletines', 'Alianzas'],
                datasets: [{
                    data: [
                        <?php echo $stats['noticias']; ?>,
                        <?php echo $stats['reportajes']; ?>,
                        <?php echo $stats['podcasts']; ?>,
                        <?php echo $stats['boletines']; ?>,
                        <?php echo $stats['alianzas']; ?>
                    ],
                    backgroundColor: ['#C8102E', '#1B2A4A', '#FFC107', '#00D68F', '#3699FF'],
                    borderWidth: 0,
                    hoverOffset: 15,
                    spacing: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 12, usePointStyle: true, pointStyle: 'circle', font: { size: 11, weight: '600' } }
                    },
                    tooltip: {
                        backgroundColor: '#1B2A4A',
                        padding: 12,
                        titleFont: { size: 13, weight: '700' },
                        bodyFont: { size: 12 },
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.parsed + ' items';
                            }
                        }
                    }
                }
            }
        });

        // ========== GRÁFICO DE BARRAS ==========
        new Chart(document.getElementById('chartBarras'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($datos_mes, 'mes')); ?>,
                datasets: [
                    {
                        label: 'Noticias',
                        data: <?php echo json_encode(array_column($datos_mes, 'noticias')); ?>,
                        backgroundColor: '#C8102E',
                        borderRadius: 8,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Reportajes',
                        data: <?php echo json_encode(array_column($datos_mes, 'reportajes')); ?>,
                        backgroundColor: '#1B2A4A',
                        borderRadius: 8,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Podcasts',
                        data: <?php echo json_encode(array_column($datos_mes, 'podcasts')); ?>,
                        backgroundColor: '#FFC107',
                        borderRadius: 8,
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1B2A4A',
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#E2E8F0', drawBorder: false },
                        ticks: { stepSize: 1, font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '600' } }
                    }
                }
            }
        });

        // ========== GRÁFICO DE LÍNEA ==========
        var ctxLinea = document.getElementById('chartLinea').getContext('2d');
        var gradient = ctxLinea.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(200,16,46,0.3)');
        gradient.addColorStop(1, 'rgba(200,16,46,0)');

        new Chart(ctxLinea, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($datos_mes, 'mes')); ?>,
                datasets: [{
                    label: 'Total Publicaciones',
                    data: <?php echo json_encode(array_column($datos_mes, 'total')); ?>,
                    borderColor: '#C8102E',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#C8102E',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1B2A4A',
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#E2E8F0', drawBorder: false },
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '600' } }
                    }
                }
            }
        });
    </script>
</body>
</html>
