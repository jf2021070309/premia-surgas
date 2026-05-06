<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/clientes_nuevo.css">
    <style>
        /* Los estilos de carga se han movido al final para mejor control dinámico */
        .switch-container {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface-low);
            padding: 1.5rem 2rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--outline);
            margin-top: 1rem;
        }
        .switch-text h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--on-surface);
            margin: 0;
        }
        .switch-text p {
            font-size: 0.75rem;
            color: var(--on-muted);
            margin: 4px 0 0;
        }
        /* Custom Switch Styling */
        .premium-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }
        .premium-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider-premium {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #cbd5e1;
            transition: .4s;
            border-radius: 34px;
        }
        .slider-premium:before {
            position: absolute;
            content: "";
            height: 18px; width: 18px;
            left: 4px; bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider-premium {
            background-color: var(--primary);
        }
        input:checked + .slider-premium:before {
            transform: translateX(24px);
        }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Mi Anuncio';
            $pageSubtitle = 'Gestiona la publicidad de tu negocio en la App';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container animate-fade-in">
            <div class="elite-form-card">
                <div class="card-header-premium">
                    <i class='bx bxs-megaphone'></i>
                    Configuración del Anuncio Publicitario
                </div>

                <form action="<?= BASE_URL ?>afiliados/guardarAnuncio" method="POST" enctype="multipart/form-data" class="premium-form">
                    
                    <!-- Nombre Comercial -->
                    <div class="form-group-modern full-width">
                        <label><i class='bx bx-store'></i> Nombre Comercial del Negocio</label>
                        <div class="input-wrapper">
                            <i class='bx bx-edit-alt'></i>
                            <input type="text" name="nombre_negocio" placeholder="Ej: Restaurante El Gourmet" 
                                   value="<?= ($anuncio['nombre_negocio'] ?? null) ?: ($usuario['nombre'] ?? '') ?>" required>
                        </div>
                    </div>

                    <!-- Ubicación -->
                    <div class="form-group-modern full-width">
                        <label><i class='bx bx-map-pin'></i> Ubicación / Dirección</label>
                        <div class="input-wrapper">
                            <i class='bx bx-map'></i>
                            <input type="text" name="ubicacion" placeholder="Ej: Av. Bolognesi 123, Tacna" 
                                   value="<?= ($anuncio['ubicacion'] ?? null) ?: ($usuario['direccion'] ?? '') ?>" required>
                        </div>
                    </div>

                    <!-- Celular de Contacto -->
                    <div class="form-group-modern full-width">
                        <label><i class='bx bx-phone'></i> Número de Contacto</label>
                        <div class="input-wrapper">
                            <i class='bx bx-mobile-alt'></i>
                            <input type="text" name="celular" placeholder="Ej: 987654321" 
                                   value="<?= ($anuncio['celular'] ?? null) ?: ($usuario['celular'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Imagen Publicitaria -->
                    <div class="form-group-modern">
                        <label><i class='bx bx-image'></i> Imagen Publicitaria</label>
                        <div class="upload-zone-premium">
                            <div class="file-display-area">
                                <?php if (!empty($anuncio['imagen_negocio'] ?? null)): 
                                    $imgPath = $anuncio['imagen_negocio'];
                                    $isExternal = (strpos($imgPath, 'http') === 0);
                                    $fullImgPath = $isExternal ? $imgPath : BASE_URL . $imgPath;
                                ?>
                                    <img src="<?= $fullImgPath ?>" class="preview-img-premium">
                                <?php else: ?>
                                    <i class='bx bx-image-add'></i>
                                <?php endif; ?>
                            </div>
                            
                            <div class="file-actions-premium">
                                <button type="button" class="btn-action-upload" onclick="this.nextElementSibling.click()">
                                    <i class='bx bx-cloud-upload'></i> Subir Imagen
                                </button>
                                <input type="file" name="imagen_negocio" accept="image/*" style="display: none;">
                                
                                <?php if (!empty($anuncio['imagen_negocio'] ?? null)): ?>
                                    <a href="<?= $fullImgPath ?>" target="_blank" class="btn-action-view">
                                        <i class='bx bx-show'></i> Ver Actual
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Carta PDF -->
                    <div class="form-group-modern">
                        <label><i class='bx bxs-file-pdf'></i> Carta o Menú (PDF)</label>
                        <div class="upload-zone-premium">
                            <div class="file-display-area">
                                <?php if (!empty($anuncio['carta_pdf'] ?? null)): ?>
                                    <i class='bx bxs-file-pdf text-danger' style="font-size: 4rem;"></i>
                                <?php else: ?>
                                    <i class='bx bx-file-blank'></i>
                                <?php endif; ?>
                            </div>

                            <div class="file-actions-premium">
                                <button type="button" class="btn-action-upload btn-pdf" onclick="this.nextElementSibling.click()">
                                    <i class='bx bx-upload'></i> Subir PDF
                                </button>
                                <input type="file" name="carta_pdf" accept="application/pdf" style="display: none;">

                                <?php if (!empty($anuncio['carta_pdf'] ?? null)): ?>
                                    <a href="<?= BASE_URL . $anuncio['carta_pdf'] ?>" target="_blank" class="btn-action-view view-pdf">
                                        <i class='bx bx-link-external'></i> Ver Menú
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Estado Switch -->
                    <div class="switch-container">
                        <div class="switch-text">
                            <h4>Visibilidad en la Aplicación</h4>
                            <p>Activa para que tu negocio aparezca en el carrusel de los clientes.</p>
                        </div>
                        <label class="premium-switch">
                            <input type="checkbox" name="estado" value="1" <?= ($anuncio['estado'] ?? 1) == 1 ? 'checked' : '' ?>>
                            <span class="slider-premium"></span>
                        </label>
                    </div>

                    <!-- Hidden Input for Background Color -->
                    <input type="hidden" name="color_fondo" id="color_fondo_input" value="<?= $anuncio['color_fondo'] ?? '#A7D8F5' ?>">

                    <!-- Botón Guardar -->
                    <div class="form-footer-actions" style="flex-direction: column; gap: 1rem;">
                        <button type="submit" class="btn-premium-submit">
                            <i class='bx bx-check-circle me-2'></i>
                            Guardar y Publicar
                        </button>

                        <button type="button" class="btn-preview-banner" onclick="abrirPrevisualizacion()">
                            <i class='bx bx-show-alt'></i>
                            Previsualizar Diseño Final
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Previsualización -->
    <div id="modalPreview" class="preview-overlay" style="display: none;">
        <div class="preview-modal-content">
            <div class="preview-modal-header">
                <h3><i class='bx bx-laptop'></i> Vista Previa del Cliente</h3>
                <button onclick="cerrarPrevisualizacion()" class="btn-close-preview">&times;</button>
            </div>
            <div class="preview-modal-body">
                <p class="preview-hint">Así es como los clientes verán tu anuncio en el carrusel principal.</p>
                
                <div class="carousel-mockup">
                    <div class="ad-card-solid" id="mockupCard" style="background-color: <?= $anuncio['color_fondo'] ?? '#A7D8F5' ?>;">
                        <div class="ad-card-solid-left">
                            <h3 class="ad-title-solid" id="mockupTitle"><?= htmlspecialchars(($anuncio['nombre_negocio'] ?? null) ?: ($usuario['nombre'] ?? 'Nombre del Negocio')) ?></h3>
                            <p class="ad-location-solid"><i class='bx bx-map'></i> <span id="mockupLocation"><?= htmlspecialchars(($anuncio['ubicacion'] ?? null) ?: ($usuario['direccion'] ?? 'Ubicación del Negocio')) ?></span></p>
                            
                            <div class="ad-actions-solid">
                                <button type="button" class="btn-ad-solid" id="mockupPdfBtn" style="<?= empty($anuncio['carta_pdf'] ?? null) ? 'display: none;' : '' ?>">
                                    <i class='bx bxs-file-pdf'></i> Ver Carta de Productos
                                </button>
                            </div>
                        </div>
                        <div class="ad-card-solid-right">
                            <div class="ad-badge-solid">
                                <i class='bx bxs-star'></i> Socio Afiliado
                            </div>
                            <div class="ad-logo-wrapper">
                                <img src="<?= !empty($anuncio['imagen_negocio'] ?? null) ? (strpos($anuncio['imagen_negocio'], 'http') === 0 ? $anuncio['imagen_negocio'] : BASE_URL . $anuncio['imagen_negocio']) : BASE_URL . 'assets/img/default-negocio.jpg' ?>" id="mockupImage" alt="Logo">
                            </div>
                        </div>
                    </div>
                    
                    <div class="color-picker-section">
                        <p>Personalizar fondo:</p>
                        <div class="color-options">
                            <div class="color-dot <?= ($anuncio['color_fondo'] ?? '#A7D8F5') === '#A7D8F5' ? 'active' : '' ?>" style="background-color: #A7D8F5;" onclick="cambiarFondo('#A7D8F5', this)"></div>
                            <div class="color-dot <?= ($anuncio['color_fondo'] ?? '') === '#B8F2E6' ? 'active' : '' ?>" style="background-color: #B8F2E6;" onclick="cambiarFondo('#B8F2E6', this)"></div>
                            <div class="color-dot <?= ($anuncio['color_fondo'] ?? '') === '#FEF08A' ? 'active' : '' ?>" style="background-color: #FEF08A;" onclick="cambiarFondo('#FEF08A', this)"></div>
                            <div class="color-dot <?= ($anuncio['color_fondo'] ?? '') === '#FFFBEB' ? 'active' : '' ?>" style="background-color: #FFFBEB;" onclick="cambiarFondo('#FFFBEB', this)"></div>
                        </div>
                    </div>

                    <div class="preview-actions">
                        <button type="button" class="btn-save-preview" onclick="guardarColorYSalir()">
                            <i class='bx bx-check'></i> Guardar Diseño
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos del Botón Previsualizar */
        .btn-preview-banner {
            background: #fff;
            color: #1e293b;
            border: 1.5px solid #e2e8f0;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        .btn-preview-banner:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Modal de Previsualización */
        .preview-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(8px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.3s ease;
        }
        .preview-modal-content {
            background: #fff;
            width: 100%;
            max-width: 900px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.5);
            animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .preview-modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .preview-modal-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 800;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-close-preview {
            background: #f1f5f9;
            border: none;
            width: 36px; height: 36px;
            border-radius: 50%;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        .btn-close-preview:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .preview-modal-body {
            padding: 2.5rem;
            background: #f8fafc;
        }
        .preview-hint {
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            font-weight: 500;
        }

        /* Estilos del Banner (Copiados de perfil_cliente.php y adaptados a color sólido) */
        .carousel-mockup {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }
        .ad-card-solid {
            width: 100%;
            aspect-ratio: 2.3 / 1;
            border-radius: 28px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2.5rem;
            transition: background-color 0.3s ease;
            border: 2px solid #E5E7EB;
        }
        .ad-card-solid-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            z-index: 2;
            text-align: left;
        }
        .ad-card-solid-right {
            width: 40%;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
            height: 100%;
            z-index: 2;
        }
        .ad-title-solid {
            font-size: 2rem;
            font-weight: 900;
            margin: 0 0 0.5rem;
            letter-spacing: -1px;
            color: #1e293b;
            line-height: 1.1;
        }
        .ad-location-solid {
            font-size: 0.9rem;
            color: #4b5563;
            margin: 0 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }
        .btn-ad-solid {
            background: #1e293b;
            color: #fff;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            cursor: pointer;
        }
        .ad-badge-solid {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            gap: 5px;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        .ad-badge-solid i { color: #fbbf24; }
        .ad-logo-wrapper {
            width: 130px;
            height: 130px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border: 4px solid #fff;
            background: #fff;
        }
        .ad-logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Color Picker Options */
        .color-picker-section {
            margin-top: 2rem;
            text-align: center;
        }
        .color-picker-section p {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        .color-options {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .color-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid transparent;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }
        .color-dot.active {
            border-color: #1e293b;
            transform: scale(1.15);
        }
        .color-dot:hover {
            transform: scale(1.1);
        }
        .preview-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }
        .btn-save-preview {
            background: #1e293b;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 10px 20px rgba(30,41,59,0.2);
        }
        .btn-save-preview:hover {
            background: #0f172a;
            transform: translateY(-2px);
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        @media (max-width: 768px) {
            .ad-card-solid { aspect-ratio: auto; flex-direction: column; text-align: center; padding: 2rem 1.5rem; gap: 1.5rem; }
            .ad-card-solid-left { align-items: center; }
            .ad-card-solid-right { width: 100%; align-items: center; flex-direction: column-reverse; gap: 1rem; }
            .ad-logo-wrapper { width: 100px; height: 100px; margin: 0 auto; }
            .ad-badge-solid { margin-bottom: 0; }
            .preview-modal-body { padding: 1.5rem; }
        }

        /* Estilos de los inputs ya existentes en el archivo */
        .upload-zone-premium {
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            background: #ffffff;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px -10px rgba(0,0,0,0.05);
            position: relative;
        }
        .upload-zone-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px -12px rgba(0,0,0,0.08);
            border-color: var(--primary-glow);
        }
        .file-display-area {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 15px;
            background: #f8fafc;
            border-radius: 14px;
            padding: 10px;
            min-height: 80px;
        }
        .file-actions-premium {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
            max-width: 180px;
        }
        .btn-action-upload {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-action-upload:hover {
            filter: brightness(1.2);
            transform: scale(1.02);
        }
        .btn-action-upload.btn-pdf {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .btn-action-view {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 8px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-action-view:hover {
            background: #fff;
            color: var(--primary);
            border-color: var(--primary);
        }
        .preview-img-premium {
            max-height: 90px;
            border-radius: 8px;
        }
        .upload-zone-premium i {
            font-size: 2.2rem !important;
            color: #94a3b8;
        }

        @media (max-width: 992px) {
            .premium-form {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 2rem;
            }
            .upload-zone-premium {
                min-height: 250px;
            }
        }

        @media (max-width: 576px) {
            .premium-form {
                padding: 1.25rem;
            }
            .switch-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1.25rem;
            }
            .btn-premium-submit {
                width: 100%;
                padding: 1rem;
            }
        }
    </style>

    <script>
        function cambiarFondo(color, element) {
            document.getElementById('mockupCard').style.backgroundColor = color;
            document.getElementById('color_fondo_input').value = color;
            
            document.querySelectorAll('.color-dot').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function guardarColorYSalir() {
            cerrarPrevisualizacion();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Diseño previsualizado y aplicado. Recuerda Guardar el formulario.',
                showConfirmButton: false,
                timer: 3000
            });
        }

        function abrirPrevisualizacion() {
            // Actualizar mockup con valores actuales del formulario
            const nombre = document.querySelector('input[name="nombre_negocio"]').value;
            const ubicacion = document.querySelector('input[name="ubicacion"]').value;
            const hasPdf = document.querySelector('input[name="carta_pdf"]').files.length > 0 || <?= !empty($anuncio['carta_pdf'] ?? null) ? 'true' : 'false' ?>;

            document.getElementById('mockupTitle').innerText = nombre || 'Nombre del Negocio';
            document.getElementById('mockupLocation').innerText = ubicacion || 'Ubicación del Negocio';
            document.getElementById('mockupPdfBtn').style.display = hasPdf ? 'inline-flex' : 'none';

            // Imagen (si hay una nueva seleccionada)
            const imgInput = document.querySelector('input[name="imagen_negocio"]');
            if (imgInput.files && imgInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('mockupImage').src = e.target.result;
                };
                reader.readAsDataURL(imgInput.files[0]);
            }

            document.getElementById('modalPreview').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function cerrarPrevisualizacion() {
            document.getElementById('modalPreview').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Desactivar Drag & Drop
        ['dragover', 'drop'].forEach(eventName => {
            window.addEventListener(eventName, function(e) {
                if (e.target.closest('.upload-zone-premium')) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }, false);
        });

        document.querySelectorAll('.upload-zone-premium input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const zone = this.closest('.upload-zone-premium');
                const displayArea = zone.querySelector('.file-display-area');
                
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    zone.classList.add('has-file');

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            displayArea.innerHTML = `<img src="${e.target.result}" class="preview-img-premium animate-fade-in">`;
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        displayArea.innerHTML = `<i class='bx bxs-file-pdf text-danger animate-fade-in' style="font-size: 4rem !important;"></i>`;
                    }
                }
            });
        });

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalPreview');
            if (event.target == modal) {
                cerrarPrevisualizacion();
            }
        }
    </script>

    <?php if (isset($_SESSION['flash'])): ?>
        <script>
            Swal.fire({
                icon: '<?= $_SESSION['flash']['type'] ?>',
                title: '<?= $_SESSION['flash']['title'] ?>',
                text: '<?= $_SESSION['flash']['message'] ?>',
                confirmButtonColor: '#000'
            });
        </script>
    <?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
