<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de Venta — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <!-- Leaflet Map library -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        :root { 
            --primary: #800000; 
            --primary-dark: #5a0000; 
            --primary-light: rgba(128, 0, 0, 0.08);
            --bg: #EBEEF2; 
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit','Inter',sans-serif; background: var(--bg); min-height: 100vh; overflow: hidden; }

        /* Premium Floating Glassmorphic Header */
        .map-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 72px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
            display: flex;
            align-items: center;
            padding: 0 1.2rem;
            z-index: 1000;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.05);
        }
        .map-header-back {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.3rem;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.04);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .map-header-back:hover {
            background: var(--primary);
            color: #fff;
            transform: scale(1.06) rotate(-8deg);
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.25);
        }
        .map-header-title-container {
            flex: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-right: 42px; /* Balance perfect alignment with the left back button */
        }
        .map-header-title {
            font-size: 1.05rem;
            font-weight: 950;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            letter-spacing: -0.3px;
        }
        .map-header-title i {
            color: var(--primary);
            font-size: 1.2rem;
        }
        .map-header-subtitle {
            font-size: 0.7rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 1px;
        }

        #map-cliente { height: 100vh; width: 100vw; position: absolute; top: 0; left: 0; z-index: 1; }

        /* Align Zoom Control to fit nicely under Header */
        .leaflet-top.leaflet-right {
            top: 88px !important;
            right: 14px !important;
        }

        /* Pulsing floating main button */
        #btnVerCercanos { 
            position: fixed; 
            bottom: 1.8rem; 
            left: 50%; 
            transform: translateX(-50%); 
            z-index: 999; 
            background: var(--primary); 
            color: #fff; 
            border: none; 
            border-radius: 60px; 
            padding: 1.1rem 2.4rem; 
            font-family: 'Outfit',sans-serif; 
            font-size: 0.95rem; 
            font-weight: 800; 
            cursor: pointer; 
            display: flex; 
            align-items: center; 
            gap: 0.7rem; 
            box-shadow: 0 10px 35px rgba(128,0,0,0.45); 
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); 
            white-space: nowrap; 
            animation: floatPulse 2.5s ease-in-out infinite; 
        }
        #btnVerCercanos:hover { 
            background: var(--primary-dark); 
            transform: translateX(-50%) translateY(-4px); 
            box-shadow: 0 16px 40px rgba(128,0,0,0.55); 
            animation: none; 
        }
        #btnVerCercanos i { font-size: 1.35rem; }
        @keyframes floatPulse { 
            0%,100% { box-shadow: 0 10px 30px rgba(128,0,0,0.45); transform: translateX(-50%) translateY(0); } 
            50% { box-shadow: 0 16px 40px rgba(128,0,0,0.65); transform: translateX(-50%) translateY(-6px); } 
        }

        /* Floating Recenter button */
        .btn-recenter {
            position: fixed;
            bottom: 7.5rem;
            right: 1.2rem;
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            font-size: 1.45rem;
            cursor: pointer;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 998;
        }
        .btn-recenter:hover {
            color: var(--primary);
            background: #fff;
            transform: scale(1.08) translateY(-2px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.18);
        }

        .sheet-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); z-index: 2000; opacity: 0; pointer-events: none; transition: opacity 0.4s ease; backdrop-filter: blur(4px); }
        .sheet-overlay.open { opacity: 1; pointer-events: all; }
        
        .bottom-sheet { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            background: #fff; 
            border-radius: 32px 32px 0 0; 
            z-index: 2001; 
            padding: 0 1.5rem 2rem; 
            max-height: 80vh; 
            overflow-y: auto; 
            transform: translateY(100%); 
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1); 
            box-shadow: 0 -12px 50px rgba(15, 23, 42, 0.18); 
        }
        .bottom-sheet.open { transform: translateY(0); }
        
        @media (min-width: 600px) {
            .bottom-sheet { max-width: 500px; margin: 0 auto; left: 50%; transform: translate(-50%, 100%); border-radius: 32px; bottom: 1.5rem; }
            .bottom-sheet.open { transform: translate(-50%, 0); }
        }

        .sheet-handle { width: 50px; height: 5px; background: #e2e8f0; border-radius: 10px; margin: 1.1rem auto 1.3rem; }
        .sheet-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.3rem; }
        .sheet-title i { color: var(--primary); }
        .sheet-subtitle { font-size: 0.82rem; color: #64748b; font-weight: 600; margin-bottom: 1.2rem; }

        /* Sleek Search bar */
        .search-container {
            position: relative;
            margin-bottom: 1.2rem;
            width: 100%;
        }
        .search-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.6rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            color: #0f172a;
            background: #f8fafc;
            transition: all 0.25s ease;
            box-sizing: border-box;
        }
        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.08);
        }
        .search-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            color: #94a3b8;
            pointer-events: none;
        }

        .map-legend-sheet { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.2rem; padding-bottom: 0.9rem; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; }
        .legend-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.76rem; font-weight: 700; color: #64748b; }
        .dot-surgas { width: 12px; height: 12px; border-radius: 50%; background: var(--primary); border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.3); flex-shrink: 0; }
        .dot-venta  { width: 12px; height: 12px; border-radius: 50%; background: #ef4444; border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.3); flex-shrink: 0; }
        .dot-user   { width: 12px; height: 12px; border-radius: 50%; background: #3b82f6; border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.3); flex-shrink: 0; }

        .punto-item { display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 18px; margin-bottom: 0.8rem; border: 1px solid #f1f5f9; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
        .punto-item:hover { background: #fff; border-color: #fca5a5; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(128, 0, 0, 0.05); }
        .punto-item-img { width: 58px; height: 58px; border-radius: 14px; object-fit: cover; background: #e2e8f0; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 1.6rem; flex-shrink: 0; overflow: hidden; }
        .punto-item-img img { width: 100%; height: 100%; object-fit: cover; }
        .punto-item-info { flex: 1; min-width: 0; }
        .punto-item-name { font-weight: 800; font-size: 0.94rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .punto-item-dist { font-size: 0.75rem; font-weight: 700; color: #64748b; margin-top: 3px; display: flex; align-items: center; gap: 0.3rem; }
        .punto-item-dist i { color: #ef4444; }
        .punto-item-pin { width: 36px; height: 36px; background: #fee2e2; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 1.15rem; flex-shrink: 0; }

        /* Floating location status bar - fitted beautifully under header */
        .location-status { display: flex; align-items: center; gap: 0.6rem; padding: 0.65rem 1.3rem; border-radius: 30px; font-size: 0.8rem; font-weight: 800; position: fixed; top: 84px; left: 50%; transform: translateX(-50%); z-index: 1000; box-shadow: 0 8px 30px rgba(0,0,0,0.15); white-space: nowrap; }
        .loc-finding { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        .loc-found   { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .loc-error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

        .empty-sheet { text-align: center; padding: 2.5rem 1.5rem; color: #94a3b8; }
        .empty-sheet i { font-size: 3rem; display: block; margin-bottom: 0.6rem; opacity: 0.4; }
        .empty-sheet p { font-size: 0.88rem; font-weight: 600; }

        .bottom-sheet::-webkit-scrollbar { width: 4px; }
        .bottom-sheet::-webkit-scrollbar-track { background: transparent; }
        .bottom-sheet::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* Premium Radar Scanning Overlay */
        .radar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.45s ease;
        }
        .radar-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
        .radar-container {
            position: relative;
            width: 250px;
            height: 250px;
            margin-bottom: 2rem;
        }
        .radar-circle {
            position: absolute;
            inset: 0;
            border: 2px solid rgba(128, 0, 0, 0.35);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(128, 0, 0, 0.08) 0%, rgba(15, 23, 42, 0) 75%);
            box-shadow: 0 0 35px rgba(128, 0, 0, 0.2);
            overflow: hidden;
        }
        .radar-circle::before {
            content: '';
            position: absolute;
            inset: 25px;
            border: 1px dashed rgba(128, 0, 0, 0.25);
            border-radius: 50%;
        }
        .radar-circle::after {
            content: '';
            position: absolute;
            inset: 65px;
            border: 1px solid rgba(128, 0, 0, 0.15);
            border-radius: 50%;
        }
        .radar-crosshair-h {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(128, 0, 0, 0.25);
            z-index: 2;
        }
        .radar-crosshair-v {
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 1px;
            background: rgba(128, 0, 0, 0.25);
            z-index: 2;
        }
        .radar-sweep {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 50%;
            height: 50%;
            background: linear-gradient(45deg, rgba(128, 0, 0, 0.45) 0%, rgba(128, 0, 0, 0) 70%);
            transform-origin: top left;
            border-top-left-radius: 100%;
            animation: radar-sweep-anim 2s linear infinite;
            z-index: 1;
        }
        @keyframes radar-sweep-anim {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .radar-blip {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #ff4444;
            border-radius: 50%;
            box-shadow: 0 0 12px #ff4444;
            animation: blip-pulse 2s infinite ease-in-out;
            z-index: 3;
        }
        @keyframes blip-pulse {
            0%, 100% { opacity: 0.15; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.4); }
        }
        .radar-text {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 0.6rem;
            text-align: center;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .radar-subtext {
            color: #94a3b8;
            font-size: 0.88rem;
            font-weight: 600;
            text-align: center;
        }

        /* Leaflet custom map markers styling */
        .leaflet-marker-surgas, .leaflet-marker-venta {
            background: transparent !important;
            border: none !important;
        }
        .pin-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));
            animation: bounceInMarker 0.65s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }
        @keyframes bounceInMarker {
            from { opacity: 0; transform: scale(0.3) translateY(-20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes floatMarker {
            0%, 100% { transform: translateY(0); filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3)); }
            50% { transform: translateY(-8px); filter: drop-shadow(0 12px 14px rgba(0,0,0,0.2)); }
        }
        @keyframes pulseGlow {
            0% { transform: scale(0.85); opacity: 0.45; }
            100% { transform: scale(1.2); opacity: 0.9; }
        }
        .floating-marker {
            animation: bounceInMarker 0.65s cubic-bezier(0.175, 0.885, 0.32, 1.275) both, floatMarker 3s ease-in-out infinite 0.65s;
        }
        .plant-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(128, 0, 0, 0.15);
            box-shadow: 0 0 14px 4px rgba(128, 0, 0, 0.4);
            animation: pulseGlow 1.5s ease-in-out infinite alternate;
            z-index: -1;
        }
        .plant-badge {
            position: absolute;
            bottom: 0px;
            right: 0px;
            background: #800000;
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            z-index: 10;
        }
        .floating-marker img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .pin-svg {
            transition: all 0.25s ease;
        }
        .pin-wrapper:hover .pin-svg {
            transform: scale(1.08);
            fill: #dc2626 !important;
        }
        .pin-inner-circle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #fff;
            border-radius: 50%;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.3);
        }
        .pin-inner-icon {
            position: absolute;
            top: 9px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Live user blue dot glowing effect */
        .leaflet-marker-user {
            width: 14px !important;
            height: 14px !important;
            background: #3b82f6;
            border: 2px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.4);
            animation: pulse-user 1.8s infinite;
        }
        @keyframes pulse-user {
            0% { box-shadow: 0 0 0 0px rgba(59, 130, 246, 0.7); }
            70% { box-shadow: 0 0 0 12px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0px rgba(59, 130, 246, 0); }
        }

        .leaflet-tooltip-custom {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            color: #dc2626 !important;
            padding: 0 !important;
            margin-top: -24px !important;
            white-space: nowrap;
            text-shadow: 0 1px 4px #fff, 0 -1px 4px #fff, 1px 0px 4px #fff, -1px 0px 4px #fff;
        }
        .leaflet-tooltip-custom::before {
            display: none !important;
        }
    </style>
</head>
<body>

<!-- Cabecera Glassmorphic Premium -->
<header class="map-header">
    <a href="<?= BASE_URL ?>scan?c=<?= $_SESSION['codigo_cliente'] ?>&t=<?= $_SESSION['token_cliente'] ?>" class="map-header-back" title="Volver al inicio">
        <i class='bx bx-arrow-back'></i>
    </a>
    <div class="map-header-title-container">
        <div class="map-header-title">
            <i class='bx bxs-map-pin'></i> PremiaSurgas
        </div>
        <div class="map-header-subtitle">Puntos de Venta</div>
    </div>
</header>

<!-- Indicador de estado de ubicación flotante -->
<div class="location-status loc-finding" id="locStatus">
    <i class='bx bx-loader-alt bx-spin'></i> Obteniendo tu ubicación…
</div>

<!-- Contenedor del mapa a pantalla completa -->
<div id="map-cliente"></div>

<!-- Botón flotante para recentrar en mi ubicación -->
<button id="btnRecenter" class="btn-recenter" onclick="recentrarMapa()" style="display: none;" title="Centrar en mi ubicación">
    <i class='bx bx-target-lock'></i>
</button>

<!-- Botón flotante para abrir panel de locales -->
<button id="btnVerCercanos" onclick="abrirSheet()">
    <i class='bx bx-store-alt'></i> Ver puntos de venta cercanos
</button>

<!-- Pantalla de Carga de Radar Premium -->
<div class="radar-overlay" id="radarOverlay">
    <div class="radar-container">
        <div class="radar-circle">
            <div class="radar-crosshair-h"></div>
            <div class="radar-crosshair-v"></div>
            <div class="radar-sweep"></div>
        </div>
    </div>
    <div class="radar-text" id="radarStatusText">Escaneando área…</div>
    <div class="radar-subtext" id="radarSubText">Conectando con el receptor de satélites GPS</div>
</div>

<!-- Overlay y Bottom Sheet de locales -->
<div class="sheet-overlay" id="sheetOverlay" onclick="cerrarSheet()"></div>
<div class="bottom-sheet" id="bottomSheet">
    <div class="sheet-handle"></div>
    <div class="sheet-title"><i class='bx bxs-map-pin'></i> Puntos de Venta</div>
    <div class="sheet-subtitle" id="sheetSubtitle">Calculando distancias…</div>

    <!-- Barra de Búsqueda Dinámica -->
    <div class="search-container">
        <i class='bx bx-search search-icon'></i>
        <input type="text" id="searchBox" class="search-input" placeholder="Buscar distribuidor o local..." oninput="filterPuntosList()">
    </div>
    
    <!-- Leyenda integrada en la cabecera del panel de locales -->
    <div class="map-legend-sheet">
        <div class="legend-item"><div class="dot-venta"></div>Punto de Venta</div>
        <div class="legend-item"><div class="dot-user"></div>Mi ubicación</div>
    </div>
    
    <div id="sheetContent">
        <div class="empty-sheet"><i class='bx bx-loader-alt bx-spin'></i><p>Cargando información…</p></div>
    </div>
</div>

<script>
var BASE_URL = '<?= BASE_URL ?>';
var SURGAS_LAT = -18.0256127;
var SURGAS_LNG = -70.2416288;
var userLat = null, userLng = null;
var map, userMarker = null;
var puntosData = <?= $puntosJson ?? '[]' ?>;
var puntosMarkers = [];

function initMap() {
    // Initialize map
    map = L.map('map-cliente', {
        zoomControl: false // Disable default zoom controls for cleaner look
    }).setView([SURGAS_LAT, SURGAS_LNG], 15);

    // Reposition zoom controls to top-right (positioned beautifully under the floating header)
    L.control.zoom({
        position: 'topright'
    }).addTo(map);

    // Beautiful premium tiles (CartoDB Voyager)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        maxZoom: 20
    }).addTo(map);


    // Pintar puntos de venta (Custom premium red pins)
    puntosData.forEach(function(p) {
        var lat = parseFloat(p.latitud);
        var lng = parseFloat(p.longitud);
        
        var ventaIcon = L.divIcon({
            className: 'leaflet-marker-venta',
            html: '<div class="pin-wrapper floating-marker" style="width:48px;height:48px;"><img src="' + BASE_URL + 'assets/puntos%20de%20venta/icon.png" alt="Punto"></div>',
            iconSize: [48, 48],
            iconAnchor: [24, 48]
        });

        var marker = L.marker([lat, lng], { icon: ventaIcon }).addTo(map);
        
        var fotoHtml = p.foto
            ? '<img src="' + (p.foto.startsWith('http') ? p.foto : BASE_URL + p.foto) + '" style="width:180px;height:90px;object-fit:cover;border-radius:10px;margin-bottom:8px;display:block;">'
            : '';
            
        var googleMapsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng;
        var wazeUrl = 'https://waze.com/ul?ll=' + lat + ',' + lng + '&navigate=yes';
        var horarioHtml = p.horario_atencion ? '<div style="font-size:0.8rem;color:#475569;margin-bottom:6px;font-weight:600;"><i class="bx bx-time"></i> ' + p.horario_atencion + '</div>' : '<div style="font-size:0.8rem;color:#94a3b8;margin-bottom:6px;font-weight:600;"><i class="bx bx-time"></i> Sin horario registrado</div>';

        var popupHtml = '<div style="font-family:Outfit,sans-serif;min-width:180px;padding:4px;">' + fotoHtml +
                         '<b style="color:#0f172a;font-size:0.95rem;display:block;margin-bottom:2px;">' + p.nombre + '</b>' +
                         '<div style="font-size:0.8rem;color:#475569;margin-bottom:6px;font-weight:600;"><i class="bx bx-user"></i> ' + p.propietario + '</div>' +
                         horarioHtml +
                         '<div style="display:flex;gap:6px;margin-top:10px;">' +
                         '  <a href="' + googleMapsUrl + '" target="_blank" style="flex:1;display:flex;align-items:center;justify-content:center;gap:4px;background:#ea4335;color:#fff;text-decoration:none;padding:6px 5px;border-radius:8px;font-size:0.75rem;font-weight:700;"><i class=\'bx bxl-google\'></i> Maps</a>' +
                         '  <a href="' + wazeUrl + '" target="_blank" style="flex:1;display:flex;align-items:center;justify-content:center;gap:4px;background:#33ccff;color:#fff;text-decoration:none;padding:6px 5px;border-radius:8px;font-size:0.75rem;font-weight:700;"><i class=\'bx bx-car\'></i> Waze</a>' +
                         '</div>' +
                         '</div>';
                         
        marker.bindPopup(popupHtml);
        
        // Permanent label
        marker.bindTooltip(p.nombre, {
            permanent: true,
            direction: 'top',
            offset: [0, -12],
            className: 'leaflet-tooltip-custom'
        });

        marker._puntoData = p;
        puntosMarkers.push(marker);
    });

    // Obtener ubicación GPS
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(pos) { actualizarUbicacion(pos.coords.latitude, pos.coords.longitude); },
            errorUbicacion,
            { enableHighAccuracy: true, timeout: 10000 }
        );
    } else {
        errorUbicacion();
    }
}

function haversineKm(lat1, lng1, lat2, lng2) {
    var R = 6371;
    var dLat = (lat2-lat1)*Math.PI/180;
    var dLng = (lng2-lng1)*Math.PI/180;
    var a = Math.sin(dLat/2)*Math.sin(dLat/2) +
            Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLng/2)*Math.sin(dLng/2);
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

function actualizarUbicacion(lat, lng) {
    userLat = lat; userLng = lng;
    var st = document.getElementById('locStatus');
    st.className = 'location-status loc-found';
    st.innerHTML = "<i class='bx bx-check-circle'></i> Ubicación obtenida";

    // Show recenter button
    document.getElementById('btnRecenter').style.display = 'flex';

    // Auto-hide status badge after 3 seconds
    setTimeout(function() {
        st.style.transition = 'opacity 0.6s ease';
        st.style.opacity = '0';
        setTimeout(function() { st.style.display = 'none'; }, 600);
    }, 3000);

    if (userMarker) {
        map.removeLayer(userMarker);
    }
    
    // Sleek user marker (glowing pulse dot)
    var userIcon = L.divIcon({
        className: 'leaflet-marker-user',
        iconSize: [14, 14],
        iconAnchor: [7, 7]
    });
    
    userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(map);
    userMarker.bindPopup('<div style="font-family:Outfit,sans-serif;padding:4px;font-weight:700;color:#0f172a;"><i class=\'bx bx-navigation\' style="color:#3b82f6;"></i> Mi posición actual</div>');

    // Ajustar vista inicial para englobar Posición y puntos de venta
    var markers = [
        userMarker
    ].concat(puntosMarkers);
    
    var group = new L.featureGroup(markers);
    map.fitBounds(group.getBounds(), { padding: [55, 55] });
}

function errorUbicacion() {
    var st = document.getElementById('locStatus');
    st.className = 'location-status loc-error';
    st.innerHTML = "<i class='bx bx-error-circle'></i> No se pudo obtener tu ubicación";
    
    // Auto-hide status badge after 4 seconds
    setTimeout(function() {
        st.style.transition = 'opacity 0.6s ease';
        st.style.opacity = '0';
        setTimeout(function() { st.style.display = 'none'; }, 600);
    }, 4000);
}

function recentrarMapa() {
    if (userLat && userLng) {
        map.setView([userLat, userLng], 17);
        if (userMarker) {
            userMarker.openPopup();
        }
    }
}

function abrirSheet() {
    // 1. Show premium Radar scanning screen
    var overlay = document.getElementById('radarOverlay');
    overlay.classList.add('active');
    
    var statusText = document.getElementById('radarStatusText');
    var subText = document.getElementById('radarSubText');
    
    statusText.textContent = "Iniciando escaneo…";
    subText.textContent = "Obteniendo datos GPS de satélite";
    
    // Generate beautiful random blips in the radar circle
    var radarContainer = document.querySelector('.radar-container');
    document.querySelectorAll('.radar-blip').forEach(b => b.remove());

    setTimeout(function() {
        statusText.textContent = "Buscando puntos cercanos…";
        subText.textContent = "Calculando distancias métricas";
        
        // Spawn 3 blinking blips in radar circle for high fidelity graphics
        for (var i = 0; i < 4; i++) {
            var blip = document.createElement('div');
            blip.className = 'radar-blip';
            blip.style.top = Math.floor(Math.random() * 150 + 50) + 'px';
            blip.style.left = Math.floor(Math.random() * 150 + 50) + 'px';
            blip.style.animationDelay = (i * 0.45) + 's';
            radarContainer.appendChild(blip);
        }
    }, 900);

    setTimeout(function() {
        var numPuntos = puntosData.length;
        statusText.textContent = "¡Escaneo completado!";
        subText.textContent = numPuntos + " distribuidores identificados";
    }, 2000);

    // 2. Transition smoothly to the bottom sheet view
    setTimeout(function() {
        overlay.classList.remove('active');
        
        document.getElementById('sheetOverlay').classList.add('open');
        document.getElementById('bottomSheet').classList.add('open');
        renderizarPuntos();
        
        // Clear search box on open
        document.getElementById('searchBox').value = '';
    }, 2700);
}

function cerrarSheet() {
    document.getElementById('sheetOverlay').classList.remove('open');
    document.getElementById('bottomSheet').classList.remove('open');
}

function renderizarPuntos() {
    var content = document.getElementById('sheetContent');
    var subtitle = document.getElementById('sheetSubtitle');

    if (puntosData.length === 0) {
        subtitle.textContent = 'Sin puntos registrados aún.';
        content.innerHTML = '<div class="empty-sheet"><i class=\'bx bx-map-alt\'></i><p>No hay puntos de venta registrados.</p></div>';
        return;
    }

    var lista = puntosData.map(function(p) {
        var dist = (userLat && userLng)
            ? haversineKm(userLat, userLng, parseFloat(p.latitud), parseFloat(p.longitud))
            : null;
        return { p: p, dist: dist };
    });
    lista.sort(function(a,b) { if (a.dist===null) return 1; if (b.dist===null) return -1; return a.dist-b.dist; });

    subtitle.textContent = (userLat && userLng)
        ? lista.length + ' locales • ordenados por distancia'
        : lista.length + ' locales registrados';

    var html = '';
    lista.forEach(function(item) {
        var p = item.p;
        var distText = '';
        if (item.dist !== null) {
            distText = item.dist < 1 ? Math.round(item.dist*1000) + ' m de ti' : item.dist.toFixed(2) + ' km de ti';
        }
        var fotoHtml = p.foto
            ? '<div class="punto-item-img"><img src="' + (p.foto.startsWith('http') ? p.foto : BASE_URL + p.foto) + '"></div>'
            : '<div class="punto-item-img"><i class=\'bx bx-store-alt\'></i></div>';

        var horarioHtmlList = p.horario_atencion ? '<div style="font-size:0.75rem; color:#64748b; font-weight: 600;"><i class="bx bx-time"></i> ' + p.horario_atencion + '</div>' : '<div style="font-size:0.75rem; color:#94a3b8; font-weight: 600;"><i class="bx bx-time"></i> Sin horario registrado</div>';

        html += '<div class="punto-item" onclick="centrarEnPunto(' + p.latitud + ',' + p.longitud + ')">'
            + fotoHtml
            + '<div class="punto-item-info">'
            +   '<div class="punto-item-name">' + p.nombre + '</div>'
            +   '<div style="font-size:0.75rem; color:#64748b; font-weight: 600;"><i class="bx bx-user"></i> ' + p.propietario + '</div>'
            +   horarioHtmlList
            +   (distText ? '<div class="punto-item-dist"><i class=\'bx bx-map-pin\'></i>' + distText + '</div>' : '')
            + '</div>'
            + '<div class="punto-item-pin"><i class=\'bx bx-chevron-right\'></i></div>'
            + '</div>';
    });
    content.innerHTML = html;
}

function filterPuntosList() {
    var query = document.getElementById('searchBox').value.toLowerCase().trim();
    var items = document.querySelectorAll('.punto-item');
    var visibleCount = 0;
    
    items.forEach(function(item) {
        var name = item.querySelector('.punto-item-name').textContent.toLowerCase();
        if (name.includes(query)) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    var emptySearch = document.getElementById('emptySearch');
    if (visibleCount === 0) {
        if (!emptySearch) {
            var empty = document.createElement('div');
            empty.id = 'emptySearch';
            empty.className = 'empty-sheet';
            empty.innerHTML = '<i class=\'bx bx-search-alt\'></i><p style="font-size:0.85rem;margin-top:6px;font-weight:700;color:#64748b;">No se encontraron resultados</p>';
            document.getElementById('sheetContent').appendChild(empty);
        }
    } else {
        if (emptySearch) {
            emptySearch.remove();
        }
    }
}

function centrarEnPunto(lat, lng) {
    cerrarSheet();
    map.setView([lat, lng], 17);
    puntosMarkers.forEach(function(m) {
        var d = m._puntoData;
        if (d && Math.abs(parseFloat(d.latitud)-lat) < 0.0001 && Math.abs(parseFloat(d.longitud)-lng) < 0.0001) {
            m.openPopup();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
</body>
</html>
