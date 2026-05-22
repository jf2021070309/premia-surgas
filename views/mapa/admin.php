<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de Venta — Admin PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Leaflet Map library -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        :root { --primary: #800000; --primary-dark: #5a0000; --primary-light: rgba(128,0,0,0.08); }
        .page-wrap { padding: 1.5rem 2rem; }
        @media (max-width: 768px) { .page-wrap { padding: 1rem; } }
        #map-admin { height: 480px; border-radius: 16px; border: 2px solid #e5e7eb; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .map-instructions { background: linear-gradient(135deg,#fff8f8,#fff); border: 1px solid #fce4e4; border-radius: 12px; padding: 0.85rem 1.2rem; font-size: 0.82rem; color: #7B1A1A; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
        .form-panel { background: #fff; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 12px rgba(0,0,0,0.04); padding: 1.75rem; }
        .form-panel h3 { margin: 0 0 1.2rem; font-size: 1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.5rem; }
        .form-panel h3 i { color: var(--primary); font-size: 1.2rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.4rem; }
        .form-control { width: 100%; padding: 0.65rem 0.9rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; font-family: 'Inter',sans-serif; color: #1e293b; background: #f8fafc; transition: all 0.2s; box-sizing: border-box; }
        .form-control:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(128,0,0,0.08); }
        .form-control[readonly] { background: #f1f5f9; color: #64748b; cursor: not-allowed; }
        .coords-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .btn-submit { width: 100%; padding: 0.85rem; background: var(--primary); color: #fff; border: none; border-radius: 12px; font-size: 0.95rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s; margin-top: 0.5rem; }
        .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(128,0,0,0.2); }
        .section-title { font-size: 1rem; font-weight: 800; color: #1e293b; margin: 2rem 0 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .section-title i { color: var(--primary); }
        .puntos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
        .punto-card { background: #fff; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: all 0.2s; }
        .punto-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .punto-card-img { width: 100%; height: 140px; object-fit: cover; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 2.5rem; }
        .punto-card-img img { width: 100%; height: 140px; object-fit: cover; }
        .punto-card-body { padding: 1rem 1.1rem; }
        .punto-card-name { font-weight: 800; font-size: 0.95rem; color: #1e293b; margin-bottom: 0.35rem; }
        .punto-card-coords { font-size: 0.72rem; color: #94a3b8; font-weight: 600; display: flex; align-items: center; gap: 0.3rem; margin-bottom: 0.75rem; }
        .btn-delete-punto { width: 100%; padding: 0.55rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 8px; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.4rem; transition: all 0.2s; text-decoration: none; }
        .btn-delete-punto:hover { background: #dc2626; color: #fff; }
        .empty-state { text-align: center; padding: 3rem; color: #94a3b8; }
        .empty-state i { font-size: 3rem; display: block; margin-bottom: 0.75rem; opacity: 0.4; }
        .file-input-label { display: flex; align-items: center; gap: 0.6rem; padding: 0.65rem 0.9rem; border: 1.5px dashed #cbd5e1; border-radius: 10px; cursor: pointer; background: #f8fafc; font-size: 0.85rem; color: #64748b; font-weight: 600; transition: all 0.2s; }
        .file-input-label:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-light); }
        .file-input-label i { font-size: 1.2rem; }
        input[type="file"] { display: none; }
        .map-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start; }
        @media (max-width: 900px) { .map-grid { grid-template-columns: 1fr; } }
        .flash-alert { padding: 0.9rem 1.2rem; border-radius: 12px; font-size: 0.87rem; font-weight: 700; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.6rem; }
        .flash-success { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
        .flash-error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .marker-preview { display: inline-flex; align-items: center; gap: 0.5rem; background: #fee2e2; color: #dc2626; padding: 0.3rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-top: 0.4rem; }

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
        .leaflet-marker-temp {
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
            font-family: 'Inter', sans-serif !important;
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
<div id="app">
    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
        $pageTitle    = 'Puntos de Venta';
        $pageSubtitle = 'Gestiona los puntos en el mapa';
        $pageIcon     = 'bx-map-pin';
        include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="page-wrap">

            <?php if (isset($_SESSION['flash'])): $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
                <div class="flash-alert flash-<?= $f['type'] ?>">
                    <i class='bx <?= $f['type'] === 'success' ? 'bx-check-circle' : 'bx-error-circle' ?>'></i>
                    <?= htmlspecialchars($f['message']) ?>
                </div>
            <?php endif; ?>

            <div class="map-instructions">
                <i class='bx bx-info-circle'></i>
                Haz <strong>clic en el mapa</strong> para seleccionar las coordenadas del nuevo punto de venta.
            </div>

            <div class="map-grid">
                <div>
                    <div id="map-admin"></div>
                </div>

                <div class="form-panel">
                    <h3><i class='bx bx-plus-circle'></i> Nuevo Punto de Venta</h3>
                    <form action="<?= BASE_URL ?>mapa/create" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nombre del Punto *</label>
                            <input type="text" name="nombre" id="nombrePunto" class="form-control"
                                   placeholder="Ej: Distribuidora Norte" required autocomplete="off">
                        </div>
                        <div class="coords-row">
                            <div class="form-group">
                                <label>Latitud *</label>
                                <input type="text" name="latitud" id="latInput" class="form-control" placeholder="—" readonly required>
                            </div>
                            <div class="form-group">
                                <label>Longitud *</label>
                                <input type="text" name="longitud" id="lngInput" class="form-control" placeholder="—" readonly required>
                            </div>
                        </div>
                        <div id="coordPreview" style="display:none; margin-top:-0.5rem; margin-bottom:1rem;">
                            <span class="marker-preview">
                                <i class='bx bxs-map'></i>
                                <span id="coordText">—</span>
                            </span>
                        </div>
                        <div class="form-group">
                            <label>Foto del Punto</label>
                            <label class="file-input-label" id="fileLabel" for="fotoInput">
                                <i class='bx bx-image-add'></i>
                                <span id="fileLabelText">Subir imagen (opcional)</span>
                            </label>
                            <input type="file" name="foto" id="fotoInput" accept="image/*">
                        </div>
                        <button type="submit" class="btn-submit">
                            <i class='bx bx-save'></i> Guardar Punto
                        </button>
                    </form>
                </div>
            </div>

            <div class="section-title">
                <i class='bx bx-list-ul'></i> Puntos Registrados (<?= count($puntos) ?>)
            </div>

            <?php if (!empty($puntos)): ?>
                <div class="puntos-grid">
                    <?php foreach ($puntos as $p): ?>
                        <div class="punto-card">
                            <div class="punto-card-img">
                                <?php if ($p['foto']): ?>
                                    <img src="<?= (strpos($p['foto'], 'http') === 0) ? htmlspecialchars($p['foto']) : BASE_URL . htmlspecialchars($p['foto']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
                                <?php else: ?>
                                    <i class='bx bx-store-alt'></i>
                                <?php endif; ?>
                            </div>
                            <div class="punto-card-body">
                                <div class="punto-card-name"><?= htmlspecialchars($p['nombre']) ?></div>
                                <div class="punto-card-coords">
                                    <i class='bx bx-map'></i>
                                    <?= number_format($p['latitud'], 6) ?>, <?= number_format($p['longitud'], 6) ?>
                                </div>
                                <a href="<?= BASE_URL ?>mapa/delete?id=<?= $p['id'] ?>"
                                   class="btn-delete-punto"
                                   onclick="return confirm('¿Eliminar este punto de venta?')">
                                    <i class='bx bx-trash'></i> Eliminar
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class='bx bx-map-alt'></i>
                    <p>No hay puntos de venta registrados aún.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Leaflet JS logic -->
<script>
var BASE_URL = '<?= BASE_URL ?>';
var SURGAS_LAT = -18.0256127;
var SURGAS_LNG = -70.2416288;
var map, tempMarker = null;

function initMap() {
    // Initialize map
    map = L.map('map-admin').setView([SURGAS_LAT, SURGAS_LNG], 15);

    // Beautiful premium tiles (CartoDB Voyager)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        maxZoom: 20
    }).addTo(map);

    // Marcador Planta Surgas
    var surgasIcon = L.divIcon({
        className: 'leaflet-marker-surgas',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    var surgasMarker = L.marker([SURGAS_LAT, SURGAS_LNG], { icon: surgasIcon }).addTo(map);
    surgasMarker.bindPopup('<div style="font-family:Inter,sans-serif;padding:4px;"><b style="color:#800000;">🏭 Planta Surgas</b><br><small>Ubicación principal</small></div>').openPopup();

    // Pintar puntos existentes
    var puntosData = <?= $puntosJson ?? '[]' ?>;
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
            ? '<img src="' + (p.foto.startsWith('http') ? p.foto : BASE_URL + p.foto) + '" style="width:160px;height:80px;object-fit:cover;border-radius:8px;margin-bottom:6px;display:block;">'
            : '';
        marker.bindPopup('<div style="font-family:Inter,sans-serif;min-width:160px;">' + fotoHtml +
                         '<b style="color:#1e293b;font-size:0.9rem;">' + p.nombre + '</b>' +
                         '<br><small style="color:#94a3b8;">' + lat.toFixed(6) + ', ' + lng.toFixed(6) + '</small></div>');
    });

    // Click en mapa para seleccionar coordenadas
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(8);
        var lng = e.latlng.lng.toFixed(8);

        document.getElementById('latInput').value = lat;
        document.getElementById('lngInput').value = lng;
        document.getElementById('coordText').textContent = lat + ', ' + lng;
        document.getElementById('coordPreview').style.display = 'block';

        if (tempMarker) {
            map.removeLayer(tempMarker);
        }
        
        var tempIcon = L.divIcon({
            className: 'leaflet-marker-temp',
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });
        
        tempMarker = L.marker(e.latlng, { icon: tempIcon }).addTo(map);
        tempMarker.bindPopup('📍 Nuevo punto seleccionado').openPopup();
    });
}

// File input and init
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    document.getElementById('fotoInput').addEventListener('change', function() {
        document.getElementById('fileLabelText').textContent = this.files[0] ? this.files[0].name : 'Subir imagen (opcional)';
    });
});
</script>
</body>
</html>
