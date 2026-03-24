<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Premios — PremiaSurgas</title>
    <!-- Bootstrap 5 for the modal and layout -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        :root {
            --low: #2ecc71;
            --medium: #f1c40f;
            --high: #3498db;
            --vip: #e74c3c;
            --glass: rgba(255, 255, 255, 0.9);
        }
        
        body { font-family: 'Outfit', sans-serif; background: #f8f9fa; }
        [v-cloak] { display: none; }
        .container { max-width: 1400px !important; margin: 0 auto; padding: 1.5rem; }

        /* ── Store Header Refinement ── */
        .panel-header {
            background: radial-gradient(circle at center, #5e0a0a 0%, #2b0303 100%);
            color: white;
            padding: 1.5rem 2rem 4rem;
            border-radius: 0 0 3.5rem 3.5rem;
            position: relative;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        /* ── Premium Balance Widget ── */
        .balance-premium {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem 0;
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .balance-label-premium {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            width: 100%;
            text-align: center;
        }

        .balance-main-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            width: 100%;
        }

        /* Mascot Container & Animations */
        .mascot-container {
            position: relative;
            width: 240px;
            height: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }

        .mascot-img {
            width: 100%;
            height: auto;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.5));
            animation: mascotFloat 4s ease-in-out infinite;
            z-index: 2;
        }

        @keyframes mascotFloat {
            0%, 100% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }

        /* Points Typography */
        .points-display-premium {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            text-align: center;
        }

        .points-number-premium {
            font-size: 8.5rem;
            font-weight: 800;
            line-height: 0.8;
            letter-spacing: -4px;
            color: #fff;
            margin: 0;
            text-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: numberReveal 1s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }

        @keyframes numberReveal {
            from { opacity: 0; transform: translateX(20px) scale(0.9); }
            to { opacity: 1; transform: translateX(0) scale(1); }
        }

        .points-unit-premium {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            margin-top: -5px;
            letter-spacing: 8px;
            text-transform: uppercase;
            opacity: 0.9;
        }

        /* Responsive Balance */
        @media (max-width: 768px) {
            .balance-main-content {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }
            .points-display-premium {
                align-items: center;
                text-align: center;
            }
            .points-number-premium {
                font-size: 6.5rem;
            }
            .mascot-container {
                width: 180px;
            }
        }

        /* History Link Premium */
        .history-link-premium {
            margin-top: 2rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .history-link-premium i {
            font-size: 1.1rem;
            transition: transform 0.3s;
        }

        .history-link-premium:hover {
            color: #fff;
            letter-spacing: 3px;
        }

        .history-link-premium:hover i {
            transform: rotate(-360deg);
        }

        /* ── Pill Nav Overrides ── */
        .back-nav-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 8px 18px;
            border-radius: 100px;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .back-nav-pill:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            color: #fff;
            transform: translateX(-3px);
        }

        /* ── Level Section & Header ── */
        .level-section { 
            margin-bottom: 6rem; 
            animation: slideUpFade 0.8s ease backwards;
        }
        
        .level-header { 
            display: flex; 
            align-items: center; 
            gap: 1.5rem; 
            margin-bottom: 3.5rem; 
            padding: 1.5rem;
            background: #f1f3f5;
            border-radius: 2rem;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .level-badge { 
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #1a1a1a;
            position: relative;
        }
        
        .level-badge::after { display: none; }

        .level-info-header {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .level-title { 
            font-size: 1.65rem; 
            font-weight: 850; 
            color: #1a1a1a; 
            letter-spacing: -1px;
            margin: 0;
            line-height: 1;
        }

        .level-subtitle {
            font-size: 0.7rem;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .level-points-range { display: none; }

        /* ── Modern Prize Card Grid ── */
        .prizes-grid { 
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            gap: 1.2rem; 
        }

        @media (min-width: 992px) {
            .prizes-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (min-width: 1400px) {
            .prizes-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }
        
        .prize-card {
            background: white; 
            border-radius: 1.5rem; 
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid #f0f0f0;
            display: flex; 
            flex-direction: column;
            position: relative;
        }

        .prize-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 15px 35px rgba(0,0,0,0.08); 
            border-color: #e0e0e0;
        }
        
        .prize-image-container { 
            aspect-ratio: 1 / 1;
            height: auto;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 0.8rem; 
            background: radial-gradient(circle at center, #ffffff 0%, #fbfbfb 100%);
            position: relative;
        }

        .prize-image { 
            width: 100%; 
            height: 100%; 
            object-fit: contain; 
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
            z-index: 1;
        }

        .prize-card:hover .prize-image {
            transform: scale(1.08) rotate(1deg);
        }
        
        .prize-info { 
            padding: 1.2rem; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
            background: white;
            border-top: 1px solid #f8f8f8;
        }

        .prize-name { 
            font-size: 1.15rem; 
            font-weight: 700; 
            color: #1a1a1a; 
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .prize-desc {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 1.2rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .prize-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 1.2rem;
            margin-top: auto;
        }

        .prize-points-wrap {
            display: flex;
            flex-direction: column;
            gap: 0px;
        }

        .prize-points-val { 
            font-size: 1.5rem; 
            font-weight: 800; 
            color: #ff9800; 
            line-height: 1;
        }

        .prize-points-lbl {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #748da6;
            margin-top: 2px;
        }

        .stock-badge {
            font-size: 0.8rem;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stock-badge.available { background: #f8f9fa; color: #1a1a1a; border: 1px solid #eee; }
        .stock-badge.empty { background: #feebeb; color: #e74c3c; }

        .btn-redeem-modern {
            width: 100%; 
            padding: 0.8rem; 
            border: none;
            border-radius: 0.8rem; 
            background: #1a1a1a; 
            color: white;
            font-weight: 700; 
            font-size: 0.85rem;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-redeem-modern i { font-size: 1rem; }

        .btn-redeem-modern:hover:not(:disabled) { 
            background: #000; 
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .btn-redeem-modern:disabled {
            background: #f1f3f5;
            color: #adb5bd;
            cursor: not-allowed;
        }

        /* Level Colors via CSS Variables */
        .level-low { --lvl-color: #1a1a1a; }
        .level-medium { --lvl-color: #1a1a1a; }
        .level-high { --lvl-color: #1a1a1a; }
        .level-vip { --lvl-color: #1a1a1a; }

        .level-badge { color: #1a1a1a !important; }

        .back-nav { position: absolute; top: 20px; left: 20px; }
        .btn-back { background: rgba(255,255,255,0.1); color: white; padding: 0.5rem 1.2rem; border-radius: 50px; text-decoration: none; border: 1px solid rgba(255,255,255,0.2); }

        /* Comprar Puntos Button Style */
        .btn-buy-pts {
            background: white; color: var(--primary); border: 2px solid var(--primary);
            padding: 8px 18px; border-radius: 100px; font-weight: 800; font-size: 0.85rem;
            text-transform: uppercase; letter-spacing: 1px; transition: 0.3s;
            display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
            box-shadow: 0 4px 15px rgba(130, 21, 21, 0.1);
        }
        .btn-buy-pts:hover { 
            background: var(--primary); color: white; transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(130, 21, 21, 0.2);
        }

        .buy-card {
            border: 2px solid #eee; border-radius: 1.5rem; padding: 1.5rem; cursor: pointer;
            transition: 0.3s; text-align: center; background: #fff;
        }
        .buy-card:hover { border-color: var(--primary); transform: translateY(-5px); }
        .buy-card.active { border-color: var(--primary); background: rgba(130, 21, 21, 0.05); }
        .buy-pts-val { font-size: 2rem; font-weight: 800; color: var(--primary); display: block; }
        .buy-price-val { font-size: 1.1rem; font-weight: 700; color: #444; }

        .payment-qr-wrap {
            background: #fff; border: 2px dashed #ddd; border-radius: 20px;
            padding: 2rem; text-align: center; margin-bottom: 1.5rem;
        }
        .yape-logo { width: 80px; margin-bottom: 1rem; }
        .evidence-upload {
            border: 2px dashed #821515; background: #fff9f9; border-radius: 15px;
            padding: 2rem; text-align: center; position: relative; cursor: pointer;
        }
    </style>
</head>
<body>
<div id="app" v-cloak>
    <div class="panel-header">
        <div class="header-top-row" style="position: relative; align-items: center; min-height: 60px;">
            <div class="header-logo-side">
                <?php 
                $urlVolver = BASE_URL . 'panel';
                if (isset($_SESSION['id_cliente']) && isset($_SESSION['codigo_cliente'])) {
                    $urlVolver = BASE_URL . 'scan?c=' . $_SESSION['codigo_cliente'] . '&t=' . $_SESSION['token_cliente'];
                }
                ?>
                <a href="<?= $urlVolver ?>" class="back-nav-pill" title="Volver">
                    <i class='bx bx-arrow-back'></i>
                    <span>VOLVER</span>
                </a>
            </div>

            <div class="header-user-side">
                <?php if (isset($_SESSION['rol']) && in_array(strtolower($_SESSION['rol']), ['admin', 'conductor'])): ?>
                <!-- Conductores y Admins ven su tarjeta -->
                <div class="user-card-integrated">
                    <div class="u-avatar"><?= substr($_SESSION['nombre_usuario'] ?? $_SESSION['usuario'] ?? 'U', 0, 1) ?></div>
                    <div class="u-details">
                        <span class="u-role-tag"><?= htmlspecialchars(strtoupper($_SESSION['rol'])) ?></span>
                        <span class="u-name-val"><?= htmlspecialchars($_SESSION['usuario'] ?? $_SESSION['nombre_usuario'] ?? 'Usuario') ?></span>
                    </div>
                    <div class="u-divider"></div>
                    <button onclick="confirmarLogoutStore()" class="u-logout-btn" title="Cerrar Sesión">
                        <i class='bx bx-log-out'></i>
                    </button>
                </div>
                <?php elseif (isset($_SESSION['id_cliente'])): ?>
                <!-- Clientes ven su tarjeta -->
                <div class="user-card-integrated">
                    <div class="u-avatar"><?= strtoupper(substr($cliente['nombre'] ?? $cliente['codigo'] ?? 'C', 0, 1)) ?></div>
                    <div class="u-details">
                        <span class="u-role-tag">CLIENTE</span>
                        <span class="u-name-val"><?= htmlspecialchars($cliente['nombre'] ?? $cliente['codigo'] ?? 'Cliente') ?></span>
                    </div>
                    <div class="u-divider"></div>
                    <a href="<?= BASE_URL ?>logout" class="u-logout-btn" title="Cerrar Sesión">
                        <i class='bx bx-log-out'></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!isset($_SESSION['id_cliente'])): ?>
        <div class="header-hero-content" style="padding-bottom: 2.5rem;">
            <h1 class="hero-main-title">Tienda de Premios</h1>
            <p class="hero-welcome-msg">Canjea tus puntos por increíbles recompensas.</p>
        </div>
        <?php else: ?>
        <div class="balance-premium">
            <span class="balance-label-premium">Tu saldo actual</span>
            
            <div class="balance-main-content">
                <div class="mascot-container">
                    <!-- Mascot Image (Stars are included in the image) -->
                    <img src="<?= BASE_URL ?>assets/premios/gas.png" alt="Mascota Surgas" class="mascot-img">
                </div>

                <div class="points-display-premium">
                    <h1 class="points-number-premium">{{ animatedSaldo.toLocaleString() }}</h1>
                    <span class="points-unit-premium">puntos</span>
                </div>
            </div>

            <div v-show="tienePendiente" class="alert alert-warning border-0 shadow-sm mt-3 text-center" style="border-radius: 1.5rem; background: #fffbeb; color: #d97706; border: 1px solid #fef3c7 !important;">
                <i class='bx bx-time-five'></i> 
                <b>Recarga en proceso:</b> El administrador está verificando tu comprobante. Los puntos se cargarán tras la validación.
            </div>

            <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
                <button @click="abrirCompra()" class="btn-buy-pts">
                    <i class='bx bxs-cart-add'></i> COMPRAR PUNTOS
                </button>
                <a href="<?= BASE_URL ?>tienda/historial" class="history-link-premium" style="margin-top: 0;">
                    <i class='bx bx-history'></i> VER HISTORIAL
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="container mt-4">
        <?php foreach ($premios as $key => $nivel): ?>
            <?php if (!empty($nivel['items'])): ?>
            <section class="level-section <?= $nivel['clase'] ?>">
                <div class="level-header">
                    <div class="level-badge">
                        <i class='bx bx-award'></i>
                    </div>
                    <div class="level-info-header">
                        <span class="level-subtitle">Categoría</span>
                        <h2 class="level-title"><?= $nivel['titulo'] ?></h2>
                    </div>
                    <div class="level-points-range">
                        <?= $nivel['puntos'] ?>
                    </div>
                </div>
                
                <div class="prizes-grid">
                    <?php foreach ($nivel['items'] as $item): ?>
                        <div class="prize-card shadow-sm">
                            <div class="prize-image-container">
                                <img src="<?= BASE_URL ?>assets/premios/<?= $item['imagen'] ?>" alt="<?= htmlspecialchars($item['nombre']) ?>" class="prize-image">
                            </div>
                            <div class="prize-info">
                                <h3 class="prize-name"><?= $item['nombre'] ?></h3>
                                <p class="prize-desc"><?= htmlspecialchars($item['descripcion']) ?></p>
                                
                                <div class="prize-meta-row">
                                    <div class="prize-points-wrap">
                                        <span class="prize-points-val"><?= number_format($item['puntos']) ?></span>
                                        <span class="prize-points-lbl">puntos</span>
                                    </div>
                                    
                                    <div class="stock-badge <?= $item['stock'] > 0 ? 'available' : 'empty' ?>">
                                        <i class='bx <?= $item['stock'] > 0 ? 'bx-check-circle' : 'bx-x-circle' ?>'></i>
                                        <?= $item['stock'] > 0 ? "Stock: {$item['stock']}" : "Agotado" ?>
                                    </div>
                                </div>

                                <button class="btn-redeem-modern" 
                                        @click="abrirCanje(<?= htmlspecialchars(json_encode($item)) ?>)"
                                        <?= $item['stock'] <= 0 ? 'disabled' : '' ?>>
                                    <i class='bx bxs-gift'></i>
                                    Canjear Premio
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Modal Buy Points Selection -->
    <div class="modal fade" id="modalComprarPuntos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h4 class="modal-title fw-bold" style="color: var(--primary);">Recargar Puntos</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-center text-muted mb-4">Selecciona el paquete de puntos que deseas adquirir:</p>
                    <div class="row g-3">
                        <div v-for="pkg in paquetes" :key="pkg.id" class="col-md-4">
                            <div @click="seleccionarPaquete(pkg)" :class="['buy-card', selectedPkg.id === pkg.id ? 'active' : '']">
                                <span class="buy-pts-val">{{ pkg.pts }}</span>
                                <span class="text-uppercase small fw-bold text-muted mb-2 d-block">Puntos Surgas</span>
                                <div class="buy-price-val">S/ {{ pkg.price }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button :disabled="!selectedPkg.pts" @click="irAPago()" class="btn btn-primary w-100 py-3 rounded-4 shadow-sm fw-bold">
                        CONTINUAR AL PAGO
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Digital Payment (Yape Ficticio) -->
    <div class="modal fade" id="modalPagoPuntos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Finalizar Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="payment-qr-wrap">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Logo_Yape.svg" class="yape-logo" alt="Yape">
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1">Monto a pagar:</h6>
                            <span class="h3 fw-bold text-primary">S/ {{ selectedPkg.price }}</span>
                        </div>
                        <?php if (!empty($yapeQrImagen)): ?>
                            <img src="<?= BASE_URL ?>assets/uploads/qr/<?= htmlspecialchars($yapeQrImagen) ?>"
                                 alt="QR Yape"
                                 class="img-fluid rounded shadow-sm"
                                 style="max-width:200px;">
                        <?php else: ?>
                            <div style="width:200px;height:200px;background:#f3e8ff;border-radius:1rem;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#a855f7;margin:0 auto;">
                                <i class='bx bx-qr' style="font-size:3rem;"></i>
                                <span style="font-size:0.8rem;margin-top:0.5rem;">QR no configurado</span>
                            </div>
                        <?php endif; ?>
                        <p class="small text-muted mt-3">Escanea el QR o yapea al número: <br><b>931 187 102</b></p>
                    </div>

                    <div class="evidence-section">
                        <label class="fw-bold mb-2">Adjuntar Comprobante (Captura):</label>
                        <div class="evidence-upload" @click="$refs.fileInput.click()">
                            <input type="file" ref="fileInput" class="d-none" @change="onFileSelected" accept="image/*">
                            <div v-if="!filePreview">
                                <i class='bx bx-cloud-upload' style="font-size: 2.5rem; color: var(--primary);"></i>
                                <p class="mb-0 mt-2 fw-bold">Haz clic para subir imagen</p>
                                <span class="small text-muted">JPG, PNG ó Captura de pantalla</span>
                            </div>
                            <div v-else>
                                <img :src="filePreview" class="img-fluid rounded" style="max-height: 150px;">
                                <p class="mb-0 mt-2 small text-success fw-bold">¡Imagen seleccionada!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button :disabled="!evidenceFile" @click="confirmarPago()" class="btn btn-success w-100 py-3 rounded-4 shadow-sm fw-bold">
                        ENVIAR PARA VERIFICACIÓN
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal canje -->
    <div class="modal fade" id="modalCanje" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Opciones de Canje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <img :src="'<?= BASE_URL ?>assets/premios/' + selected.imagen" class="mb-3" style="height: 100px; object-fit: contain;">
                        <h4 class="fw-bold mb-1">{{ selected.nombre }}</h4>
                        <p class="text-muted small">{{ selected.puntos }} Puntos requeridos</p>
                    </div>

                    <div :class="['option-card', tipo === 'total' ? 'active' : '', saldoInsuficiente ? 'disabled' : '']"
                         @click="!saldoInsuficiente && (tipo = 'total')">
                        <span class="option-check">✓</span>
                        <div class="fw-bold h6 mb-1">Canje Total</div>
                        <p class="small text-muted mb-0">Usa todos tus puntos para obtener el premio gratis.</p>
                        <div class="mt-2 fw-bold text-success" v-if="!saldoInsuficiente">
                            Costo: {{ selected.puntos }} Puntos
                        </div>
                        <div class="mt-2 fw-bold text-danger" v-else>
                            Puntos insuficientes
                        </div>
                    </div>

                    <div :class="['option-card', tipo === 'descuento' ? 'active' : '']"
                         @click="tipo = 'descuento'">
                        <span class="option-check">✓</span>
                        <div class="fw-bold h6 mb-1">Puntos + Efectivo</div>
                        <p class="small text-muted mb-2">Paga una parte con puntos y el resto en efectivo.</p>
                        
                        <div class="bg-light p-2 rounded small">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Puntos ({{ pct }}%)</span>
                                <b>{{ puntosDcto }} pts</b>
                            </div>
                            <input type="range" class="form-range" v-model="pct" min="0" :max="maxSliderPct" step="1">
                            <div class="d-flex justify-content-between mt-2">
                                <span>Pagarás:</span>
                                <b class="text-danger">S/ {{ montoEfectivo }}</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <form action="<?= BASE_URL ?>tienda/canjear" method="POST" style="width: 100%;">
                        <input type="hidden" name="premio_id" :value="selected.id">
                        <input type="hidden" name="tipo" :value="tipo">
                        <input type="hidden" name="puntos" :value="tipo === 'total' ? selected.puntos : puntosDcto">
                        <input type="hidden" name="monto" :value="tipo === 'total' ? 0 : montoEfectivo">
                        
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 shadow-sm" style="font-weight: 700;">
                            Confirmar Canje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    var CLIENTE_PUNTOS = <?= (int) ($cliente['puntos'] ?? 0) ?>;
    var MONTO_POR_PUNTO = <?= (float) ($montoPorPunto ?? 0.05) ?>;
    var BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
<script src="<?= BASE_URL ?>views/tienda.js"></script>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    <?php if ($_SESSION['flash']['type'] === 'success'): ?>
        const duration = 3 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 10000 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);
            // since particles fall down, start a bit higher than random
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
        }, 250);

        Swal.fire({
            title: '<h3 style="color: #821515; font-weight: 800; margin-bottom: 0; font-size: 1.4rem;">¡EXCELENTE CANJE!</h3>',
            html: `
                <div style="padding: 0.5rem;">
                    <p style="font-size: 1.1rem; color: #333; margin-bottom: 1rem;">
                        <b>¡Felicidades!</b> Acabas de canjear un premio increíble.
                    </p>
                    <div style="background: #f8f9fa; border-radius: 12px; padding: 1rem; border: 1px dashed #821515; margin-bottom: 1rem;">
                        <p style="margin-bottom: 0.3rem; color: #666; font-size: 0.9rem;">Para reclamar tu producto:</p>
                        <h5 style="color: #821515; font-weight: 700; margin-bottom: 0.8rem;">📍 Planta principal</h5>
                        <p style="margin-bottom: 0.3rem; color: #666; font-size: 0.85rem;">O escríbenos:</p>
                        <a href="https://wa.me/51931187102" target="_blank" style="text-decoration: none; background: #25D366; color: white; padding: 0.6rem 1.2rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.06 3.973L0 16l4.204-1.102a7.934 7.934 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                            </svg>
                            931187102
                        </a>
                    </div>
                    <p style="font-size: 0.8rem; color: #888;">¡Gracias por ser parte de PremiaSurgas!</p>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: '¡ENTENDIDO!',
            confirmButtonColor: '#821515',
            width: '90%',
            maxWidth: '380px',
            padding: '1rem',
            background: '#fff',
            backdrop: `rgba(130, 21, 21, 0.6)`,
            showClass: {
                popup: 'animate__animated animate__zoomIn'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown'
            },
            customClass: {
                title: 'text-dark',
                popup: 'rounded-5 border-0 shadow-lg'
            }
        });
    <?php else: ?>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        timer: 3000,
        timerProgressBar: true
    });
    <?php endif; ?>
</script>
<?php unset($_SESSION['flash']); endif; ?>

<script>
    function confirmarLogoutStore() {
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: "Saldrás del sistema.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#821515',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= BASE_URL ?>logout';
            }
        });
    }
</script>
</body>
</html>
