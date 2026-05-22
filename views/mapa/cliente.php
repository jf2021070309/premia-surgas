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
        :root { --primary: #800000; --primary-dark: #5a0000; --bg: #EBEEF2; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit','Inter',sans-serif; background: var(--bg); min-height: 100vh; overflow: hidden; }

        .header-back { position: fixed; top: 1.2rem; left: 1.2rem; width: 44px; height: 44px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.3rem; text-decoration: none; box-shadow: 0 4px 15px rgba(128,0,0,0.3); border: 2px solid #fff; transition: all 0.2s; z-index: 1000; }
        .header-back:hover { background: var(--primary-dark); transform: scale(1.05); }

        #map-cliente { height: 100vh; width: 100vw; position: absolute; top: 0; left: 0; z-index: 1; }

        #btnVerCercanos { position: fixed; bottom: 1.8rem; left: 50%; transform: translateX(-50%); z-index: 999; background: var(--primary); color: #fff; border: none; border-radius: 60px; padding: 1rem 2rem; font-family: 'Outfit',sans-serif; font-size: 0.95rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 0.6rem; box-shadow: 0 8px 30px rgba(128,0,0,0.45); transition: all 0.3s cubic-bezier(0.4,0,0.2,1); white-space: nowrap; animation: floatPulse 2.5s ease-in-out infinite; }
        #btnVerCercanos:hover { background: var(--primary-dark); transform: translateX(-50%) translateY(-3px); box-shadow: 0 14px 36px rgba(128,0,0,0.5); animation: none; }
        #btnVerCercanos i { font-size: 1.25rem; }
        @keyframes floatPulse { 0%,100% { box-shadow: 0 8px 30px rgba(128,0,0,0.45); } 50% { box-shadow: 0 12px 40px rgba(128,0,0,0.65); } }

        .sheet-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 2000; opacity: 0; pointer-events: none; transition: opacity 0.3s; backdrop-filter: blur(2px); }
        .sheet-overlay.open { opacity: 1; pointer-events: all; }
        
        .bottom-sheet { position: fixed; bottom: 0; left: 0; right: 0; background: #fff; border-radius: 28px 28px 0 0; z-index: 2001; padding: 0 1.2rem 2rem; max-height: 75vh; overflow-y: auto; transform: translateY(100%); transition: transform 0.4s cubic-bezier(0.16,1,0.3,1); box-shadow: 0 -10px 40px rgba(0,0,0,0.2); }
        .bottom-sheet.open { transform: translateY(0); }
        
        @media (min-width: 600px) {
            .bottom-sheet { max-width: 500px; margin: 0 auto; left: 50%; transform: translate(-50%, 100%); border-radius: 28px; bottom: 1.5rem; }
            .bottom-sheet.open { transform: translate(-50%, 0); }
        }

        .sheet-handle { width: 44px; height: 5px; background: #e2e8f0; border-radius: 10px; margin: 1rem auto 1.25rem; }
        .sheet-title { font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.3rem; }
        .sheet-title i { color: var(--primary); }
        .sheet-subtitle { font-size: 0.8rem; color: #94a3b8; font-weight: 600; margin-bottom: 1.2rem; }

        .map-legend-sheet { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.2rem; padding-bottom: 0.8rem; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; }
        .legend-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.76rem; font-weight: 700; color: #64748b; }
        .dot-surgas { width: 12px; height: 12px; border-radius: 50%; background: var(--primary); border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.3); flex-shrink: 0; }
        .dot-venta  { width: 12px; height: 12px; border-radius: 50%; background: #ef4444; border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.3); flex-shrink: 0; }
        .dot-user   { width: 12px; height: 12px; border-radius: 50%; background: #3b82f6; border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.3); flex-shrink: 0; }

        .punto-item { display: flex; align-items: center; gap: 1rem; padding: 0.9rem; background: #f8fafc; border-radius: 14px; margin-bottom: 0.75rem; border: 1px solid #f1f5f9; transition: all 0.2s; cursor: pointer; }
        .punto-item:hover { background: #fff; border-color: #fecaca; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.07); }
        .punto-item-img { width: 54px; height: 54px; border-radius: 12px; object-fit: cover; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 1.5rem; flex-shrink: 0; overflow: hidden; }
        .punto-item-img img { width: 100%; height: 100%; object-fit: cover; }
        .punto-item-info { flex: 1; min-width: 0; }
        .punto-item-name { font-weight: 800; font-size: 0.92rem; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .punto-item-dist { font-size: 0.75rem; font-weight: 700; color: #64748b; margin-top: 2px; display: flex; align-items: center; gap: 0.3rem; }
        .punto-item-dist i { color: #ef4444; }
        .punto-item-pin { width: 36px; height: 36px; background: #fee2e2; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 1.1rem; flex-shrink: 0; }

        .location-status { display: flex; align-items: center; gap: 0.6rem; padding: 0.6rem 1.2rem; border-radius: 30px; font-size: 0.8rem; font-weight: 700; position: fixed; top: 1.2rem; left: 50%; transform: translateX(-50%); z-index: 1000; box-shadow: 0 4px 15px rgba(0,0,0,0.15); white-space: nowrap; }
        .loc-finding { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        .loc-found   { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .loc-error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

        .empty-sheet { text-align: center; padding: 2rem 1rem; color: #94a3b8; }
        .empty-sheet i { font-size: 2.5rem; display: block; margin-bottom: 0.5rem; opacity: 0.4; }
        .empty-sheet p { font-size: 0.85rem; font-weight: 600; }

        .bottom-sheet::-webkit-scrollbar { width: 4px; }
        .bottom-sheet::-webkit-scrollbar-track { background: transparent; }
        .bottom-sheet::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        /* Leaflet custom styles */
        .leaflet-marker-surgas {
            width: 20px !important;
            height: 20px !important;
            background: #800000;
            border: 3px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.4);
        }
        .leaflet-marker-venta {
            width: 16px !important;
            height: 16px !important;
            background: #ef4444;
            border: 2px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.4);
        }
        .leaflet-marker-user {
            width: 18px !important;
            height: 18px !important;
            background: #3b82f6;
            border: 3px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.4);
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
            margin-top: -20px !important;
            white-space: nowrap;
        }
        .leaflet-tooltip-custom::before {
            display: none !important;
        }
    </style>
</head>
<body>

<!-- Botón flotante para regresar -->
<a href="<?= BASE_URL ?>scan?c=<?= $_SESSION['codigo_cliente'] ?>&t=<?= $_SESSION['token_cliente'] ?>" class="header-back">
    <i class='bx bx-arrow-back'></i>
</a>

<!-- Indicador de estado de ubicación flotante -->
<div class="location-status loc-finding" id="locStatus">
    <i class='bx bx-loader-alt bx-spin'></i> Obteniendo tu ubicación…
</div>

<!-- Contenedor del mapa a pantalla completa -->
<div id="map-cliente"></div>

<!-- Botón flotante para abrir panel de locales -->
<button id="btnVerCercanos" onclick="abrirSheet()">
    <i class='bx bx-store-alt'></i> Ver puntos de venta cercanos
</button>

<!-- Overlay y Bottom Sheet de locales -->
<div class="sheet-overlay" id="sheetOverlay" onclick="cerrarSheet()"></div>
<div class="bottom-sheet" id="bottomSheet">
    <div class="sheet-handle"></div>
    <div class="sheet-title"><i class='bx bxs-map-pin'></i> Puntos Cercanos</div>
    <div class="sheet-subtitle" id="sheetSubtitle">Calculando distancias…</div>
    
    <!-- Leyenda integrada en la cabecera del panel de locales -->
    <div class="map-legend-sheet">
        <div class="legend-item"><div class="dot-surgas"></div>Planta Surgas</div>
        <div class="legend-item"><div class="dot-venta"></div>Punto de Venta</div>
        <div class="legend-item"><div class="dot-user"></div>Mi ubicación</div>
    </div>
    
    <div id="sheetContent">
        <div class="empty-sheet"><i class='bx bx-loader-alt bx-spin'></i><p>Obteniendo tu ubicación…</p></div>
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
    map = L.map('map-cliente').setView([SURGAS_LAT, SURGAS_LNG], 15);

    // Beautiful premium tiles (CartoDB Voyager)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        maxZoom: 20
    }).addTo(map);

    // Planta Surgas marker
    var surgasIcon = L.divIcon({
        className: 'leaflet-marker-surgas',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    var surgasMarker = L.marker([SURGAS_LAT, SURGAS_LNG], { icon: surgasIcon }).addTo(map);
    surgasMarker.bindPopup('<div style="font-family:Outfit,sans-serif;padding:4px;"><b style="color:#800000;">🏭 Planta Surgas</b><br><small>Ubicación principal</small></div>').openPopup();

    // Pintar puntos de venta
    puntosData.forEach(function(p) {
        var lat = parseFloat(p.latitud);
        var lng = parseFloat(p.longitud);
        
        var ventaIcon = L.divIcon({
            className: 'leaflet-marker-venta',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });

        var marker = L.marker([lat, lng], { icon: ventaIcon }).addTo(map);
        
        var fotoHtml = p.foto
            ? '<img src="' + (p.foto.startsWith('http') ? p.foto : BASE_URL + p.foto) + '" style="width:160px;height:70px;object-fit:cover;border-radius:8px;margin-bottom:6px;display:block;">'
            : '';
        marker.bindPopup('<div style="font-family:Outfit,sans-serif;min-width:150px;">' + fotoHtml +
                         '<b style="color:#1e293b;font-size:0.9rem;">' + p.nombre + '</b></div>');
        
        // Permanent label
        marker.bindTooltip(p.nombre, {
            permanent: true,
            direction: 'top',
            offset: [0, -10],
            className: 'leaflet-tooltip-custom'
        });

        marker._puntoData = p;
        puntosMarkers.push(marker);
    });

    // Obtener ubicación
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

    // Auto-hide status badge after 3 seconds
    setTimeout(function() {
        st.style.transition = 'opacity 0.6s ease';
        st.style.opacity = '0';
        setTimeout(function() { st.style.display = 'none'; }, 600);
    }, 3000);

    if (userMarker) {
        map.removeLayer(userMarker);
    }
    
    var userIcon = L.divIcon({
        className: 'leaflet-marker-user',
        iconSize: [18, 18],
        iconAnchor: [9, 9]
    });
    
    userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(map);
    userMarker.bindPopup('<div style="font-family:Outfit,sans-serif;padding:4px;"><b>Mi ubicación</b></div>');

    // Ajustar bounds
    var markers = [
        L.marker([SURGAS_LAT, SURGAS_LNG]),
        userMarker
    ].concat(puntosMarkers);
    
    var group = new L.featureGroup(markers);
    map.fitBounds(group.getBounds(), { padding: [40, 40] });
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

function abrirSheet() {
    document.getElementById('sheetOverlay').classList.add('open');
    document.getElementById('bottomSheet').classList.add('open');
    renderizarPuntos();
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
        ? lista.length + ' puntos • ordenados por distancia'
        : lista.length + ' puntos registrados';

    var html = '';
    lista.forEach(function(item) {
        var p = item.p;
        var distText = '';
        if (item.dist !== null) {
            distText = item.dist < 1 ? Math.round(item.dist*1000) + ' m de ti' : item.dist.toFixed(1) + ' km de ti';
        }
        var fotoHtml = p.foto
            ? '<div class="punto-item-img"><img src="' + (p.foto.startsWith('http') ? p.foto : BASE_URL + p.foto) + '"></div>'
            : '<div class="punto-item-img"><i class=\'bx bx-store-alt\'></i></div>';

        html += '<div class="punto-item" onclick="centrarEnPunto(' + p.latitud + ',' + p.longitud + ')">'
            + fotoHtml
            + '<div class="punto-item-info">'
            +   '<div class="punto-item-name">' + p.nombre + '</div>'
            +   (distText ? '<div class="punto-item-dist"><i class=\'bx bx-map-pin\'></i>' + distText + '</div>' : '')
            + '</div>'
            + '<div class="punto-item-pin"><i class=\'bx bx-chevron-right\'></i></div>'
            + '</div>';
    });
    content.innerHTML = html;
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
