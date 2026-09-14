<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <style>
        :root {
            --wine-primary: #800000;
            --wine-dark: #600000;
            --slate-text: #1e293b;
            --gray-sub: #64748b;
            --border-light: #e2e8f0;
            --bg-neutral: #f8fafc;
        }

        .pv-container {
            padding: 1.5rem 2rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .pv-hero {
            background: linear-gradient(135deg, #800000 0%, #4a0000 100%);
            border-radius: 24px;
            padding: 2.5rem;
            color: #fff;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(128,0,0,0.25);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .pv-hero-title {
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -1px;
            margin: 0 0 0.5rem 0;
            line-height: 1.1;
        }

        .pv-hero-sub {
            font-size: 0.95rem;
            opacity: 0.9;
            max-width: 600px;
            line-height: 1.6;
            margin: 0;
        }

        .grid-two-cols {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        @media (max-width: 960px) {
            .grid-two-cols {
                grid-template-columns: 1fr;
            }
        }

        .pv-card {
            background: #fff;
            border: 1px solid var(--border-light);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .pv-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.5rem;
            padding-bottom: 1.2rem;
            border-bottom: 1px solid var(--border-light);
        }

        .pv-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #fdf2f2;
            color: var(--wine-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .quick-chip {
            background: #f1f5f9;
            border: 1.5px solid #cbd5e1;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 800;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-chip:hover {
            background: #800000;
            border-color: #800000;
            color: #fff;
            transform: translateY(-2px);
        }

        /* QR Card Styles */
        .qr-card-container {
            background: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-wrapper {
            background: #fff;
            padding: 1rem;
            border-radius: 20px;
            border: 2.5px solid var(--wine-primary);
            box-shadow: 0 15px 35px rgba(128,0,0,0.12);
            display: inline-block;
            margin: 1rem 0;
        }

        .qr-img {
            width: 220px;
            height: 220px;
            display: block;
        }

        .pulse-badge {
            animation: pulseAnim 2s infinite;
        }

        @keyframes pulseAnim {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.03); opacity: 0.9; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>

<body>
    <div id="app">
        <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

        <div class="admin-layout">
            <?php 
                $pageTitle = 'Punto de Venta';
                $pageSubtitle = 'Solicitud de Puntos por Balones de Gas (10kg)';
                include __DIR__ . '/../partials/header_admin.php'; 
            ?>

            <div class="pv-container">

                <!-- Hero Section -->
                <div class="pv-hero">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.6rem;">
                            <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                                Punto de Venta Oficial
                            </span>
                            <span style="background: #10b981; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 800;">
                                Tasa: 1 Balón 10kg = <?= $puntosPorBalon ?> pts
                            </span>
                        </div>
                        <h1 class="pv-hero-title">
                            <?= htmlspecialchars($cliente['razon_social'] ?? $cliente['nombre'] ?? $_SESSION['nombre_usuario']) ?>
                        </h1>
                        <p class="pv-hero-sub">
                            Solicita tus puntos cada vez que recibas balones de 10kg. Al generar tu solicitud, muestra tu código QR al Conductor para que valide la entrega con foto y recuento automático.
                        </p>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 18px; padding: 1.5rem 2rem; text-align: center; min-width: 160px;">
                        <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 800; letter-spacing: 1px; opacity: 0.85;">Saldo Acumulado</span>
                        <div style="font-size: 2.8rem; font-weight: 950; line-height: 1; margin: 6px 0; color: #fff;">
                            <span id="saldo-puntos-actual"><?= number_format($cliente['puntos'] ?? 0) ?></span>
                        </div>
                        <span style="font-size: 0.8rem; font-weight: 700; color: #fef08a;">PUNTOS ACTIVOS</span>
                    </div>
                </div>

                <!-- Grid: 1. Formulario de Solicitud | 2. Código QR para Conductor -->
                <div class="grid-two-cols">
                    
                    <!-- Tarjeta 1: Solicitar Puntos -->
                    <div class="pv-card">
                        <div>
                            <div class="pv-card-header">
                                <div class="pv-card-icon">
                                    <i class='bx bx-cube-alt'></i>
                                </div>
                                <div>
                                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 900; color: #0f172a;">1. Indicar Cantidad de Balones</h3>
                                    <span style="font-size: 0.78rem; color: #64748b; font-weight: 600;">Balones de 10kg entregados por el Conductor</span>
                                </div>
                            </div>

                            <!-- Selector Numérico -->
                            <div style="text-align: center; margin: 1.5rem 0;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.8rem;">
                                    ¿Cuántos balones de 10kg recibiste?
                                </label>
                                <div style="display: inline-flex; align-items: center; background: #f8fafc; border-radius: 20px; padding: 8px; border: 2.5px solid #cbd5e1;">
                                    <button type="button" onclick="modificarBalones(-1)" style="background: #fff; border: 1.5px solid #cbd5e1; width: 48px; height: 48px; border-radius: 14px; font-size: 1.5rem; font-weight: 900; color: #0f172a; cursor: pointer; transition: all 0.2s;">
                                        -
                                    </button>
                                    <input type="number" id="num-balones" value="5" min="1" max="500" oninput="recalcularPuntos()" style="width: 100px; text-align: center; border: none; background: transparent; font-size: 2.2rem; font-weight: 950; color: #800000; outline: none;">
                                    <button type="button" onclick="modificarBalones(1)" style="background: #fff; border: 1.5px solid #cbd5e1; width: 48px; height: 48px; border-radius: 14px; font-size: 1.5rem; font-weight: 900; color: #0f172a; cursor: pointer; transition: all 0.2s;">
                                        +
                                    </button>
                                </div>
                            </div>

                            <!-- Chips rápidos -->
                            <div style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; margin-bottom: 1.8rem;">
                                <button type="button" class="quick-chip" onclick="fijarBalones(5)">5 balones</button>
                                <button type="button" class="quick-chip" onclick="fijarBalones(10)">10 balones</button>
                                <button type="button" class="quick-chip" onclick="fijarBalones(20)">20 balones</button>
                                <button type="button" class="quick-chip" onclick="fijarBalones(30)">30 balones</button>
                                <button type="button" class="quick-chip" onclick="fijarBalones(50)">50 balones</button>
                            </div>

                            <!-- Cálculo de Puntos Box -->
                            <div style="background: #ecfdf5; border: 2px dashed #10b981; border-radius: 18px; padding: 1.5rem; text-align: center; margin-bottom: 1.5rem;">
                                <span style="font-size: 0.75rem; font-weight: 800; color: #065f46; text-transform: uppercase; letter-spacing: 1.5px;">
                                    Puntos a Recibir
                                </span>
                                <div style="font-size: 2.8rem; font-weight: 950; color: #047857; line-height: 1.1; margin: 4px 0;">
                                    +<span id="txt-calc-puntos">50</span> <span style="font-size: 1.1rem; font-weight: 800;">PTS</span>
                                </div>
                                <span style="font-size: 0.78rem; color: #065f46; font-weight: 600;">
                                    Calculado a <b id="txt-calc-factor"><?= $puntosPorBalon ?></b> pts por balón de 10kg
                                </span>
                            </div>
                        </div>

                        <!-- Botón de Envío -->
                        <button type="button" id="btn-enviar-solicitud" onclick="enviarSolicitudPuntos()" style="background: #800000; color: #fff; border: none; padding: 1.1rem; border-radius: 16px; font-weight: 900; font-size: 1.05rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 10px 25px rgba(128,0,0,0.3); transition: all 0.2s;">
                            <i class='bx bx-send' style="font-size: 1.3rem;"></i>
                            SOLICITAR PUNTOS AHORA
                        </button>
                    </div>

                    <!-- Tarjeta 2: Código QR para el Conductor -->
                    <?php 
                        $codigoQR = !empty($cliente['token']) ? $cliente['token'] : ($cliente['ruc'] ?? $cliente['dni'] ?? 'CLI-'.$cliente['id']);
                        $qrUrlData = BASE_URL . 'scan?t=' . urlencode($codigoQR);
                        $qrImgSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qrUrlData);
                        $ultimaPendiente = !empty($pendientes) ? $pendientes[0] : null;
                    ?>
                    <div class="qr-card-container">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 0.5rem;">
                            <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px;">
                                2. Validación con Conductor
                            </span>
                        </div>
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 900; color: #0f172a;">
                            Muestra este Código QR
                        </h3>
                        <p style="margin: 6px 0 1rem 0; font-size: 0.85rem; color: #64748b; max-width: 340px;">
                            El conductor lo escaneará desde su teléfono para tomar la foto de evidencia y verificar los balones.
                        </p>

                        <!-- Estado en tiempo real -->
                        <div id="qr-status-box" style="margin-bottom: 0.8rem;">
                            <?php if ($ultimaPendiente): ?>
                                <div class="pulse-badge" style="background: #fffbeb; border: 1.5px solid #f59e0b; padding: 8px 16px; border-radius: 30px; display: inline-flex; align-items: center; gap: 8px; color: #b45309; font-weight: 800; font-size: 0.82rem;">
                                    <i class='bx bx-loader-alt bx-spin' style="font-size: 1.1rem;"></i>
                                    Esperando al conductor: <?= $ultimaPendiente['balones_cantidad'] ?> balones (+<?= $ultimaPendiente['puntos'] ?> pts)
                                </div>
                            <?php else: ?>
                                <div style="background: #f1f5f9; padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; color: #475569;">
                                    <i class='bx bx-check-circle'></i> Listo para nueva solicitud
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Imagen QR -->
                        <div class="qr-wrapper">
                            <img src="<?= $qrImgSrc ?>" alt="QR Punto de Venta" class="qr-img">
                        </div>

                        <div style="margin-top: 0.5rem; font-size: 0.78rem; color: #64748b; font-weight: 600;">
                            RUC / Identificador: <b style="color: #0f172a;"><?= htmlspecialchars($cliente['ruc'] ?? $cliente['dni'] ?? '—') ?></b>
                        </div>

                        <div style="margin-top: 1rem; display: flex; gap: 10px;">
                            <a href="<?= $qrImgSrc ?>" download="QR_PuntoDeVenta.png" target="_blank" style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 0.6rem 1.2rem; border-radius: 10px; font-size: 0.8rem; font-weight: 700; color: #0f172a; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                <i class='bx bx-download'></i> Descargar QR
                            </a>
                            <button type="button" onclick="window.print()" style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 0.6rem 1.2rem; border-radius: 10px; font-size: 0.8rem; font-weight: 700; color: #0f172a; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                <i class='bx bx-printer'></i> Imprimir
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Sección: Historial de Solicitudes de Balones -->
                <div style="background: #fff; border: 1px solid var(--border-light); border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); padding-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #fdf2f2; color: #800000; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                                <i class='bx bx-history'></i>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 900; color: #0f172a;">Historial de Entregas de Balones</h3>
                                <span style="font-size: 0.78rem; color: #64748b; font-weight: 500;">Registro de solicitudes, verificación fotográfica y puntos otorgados</span>
                            </div>
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #64748b;">
                            Total Solicitudes: <b><?= count($historial) ?></b>
                        </span>
                    </div>

                    <?php if (!empty($historial)): ?>
                        <div style="overflow-x: auto;">
                            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #e2e8f0; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                        <th style="padding: 1rem;">Fecha y Hora</th>
                                        <th style="padding: 1rem;">Balones Solicitados</th>
                                        <th style="padding: 1rem;">Balones Verificados</th>
                                        <th style="padding: 1rem;">Puntos</th>
                                        <th style="padding: 1rem;">Conductor</th>
                                        <th style="padding: 1rem; text-align: center;">Evidencia Foto</th>
                                        <th style="padding: 1rem; text-align: center;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historial as $v): ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 0.88rem;">
                                            <td style="padding: 1rem; font-weight: 600; color: #1e293b;">
                                                <?= date('d/m/Y H:i', strtotime($v['fecha'])) ?>
                                            </td>
                                            <td style="padding: 1rem;">
                                                <span style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-weight: 800; padding: 4px 10px; border-radius: 8px; font-size: 0.82rem;">
                                                    <i class='bx bx-cube-alt'></i> <?= $v['balones_cantidad'] ?> balones (10kg)
                                                </span>
                                            </td>
                                            <td style="padding: 1rem;">
                                                <?php if (!empty($v['balones_verificados'])): ?>
                                                    <span style="color: #065f46; font-weight: 800;">
                                                        <i class='bx bx-check-circle'></i> <?= $v['balones_verificados'] ?> balones
                                                    </span>
                                                <?php else: ?>
                                                    <span style="color: #94a3b8;">— Pendiente —</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding: 1rem; font-weight: 900; color: #800000; font-size: 1.05rem;">
                                                +<?= number_format($v['puntos']) ?> pts
                                            </td>
                                            <td style="padding: 1rem; color: #475569; font-weight: 600;">
                                                <?= htmlspecialchars($v['conductor'] ?? '—') ?>
                                            </td>
                                            <td style="padding: 1rem; text-align: center;">
                                                <?php if (!empty($v['evidencia_foto'])): ?>
                                                    <button type="button" onclick="verFotoEvidenciaModal('<?= BASE_URL . $v['evidencia_foto'] ?>', <?= $v['balones_cantidad'] ?>)" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; border-radius: 8px; font-size: 0.75rem; font-weight: 800; padding: 5px 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                                        <i class='bx bx-image'></i> Ver Foto
                                                    </button>
                                                <?php else: ?>
                                                    <span style="color: #cbd5e1;">Sin foto</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding: 1rem; text-align: center;">
                                                <?php if (($v['estado'] ?? '') === 'aprobado'): ?>
                                                    <span style="background: #dcfce7; color: #15803d; font-size: 0.72rem; font-weight: 800; padding: 4px 10px; border-radius: 6px; display: inline-block;">
                                                        APROBADO
                                                    </span>
                                                <?php elseif (($v['estado'] ?? '') === 'rechazado'): ?>
                                                    <span style="background: #fee2e2; color: #b91c1c; font-size: 0.72rem; font-weight: 800; padding: 4px 10px; border-radius: 6px; display: inline-block;">
                                                        RECHAZADO
                                                    </span>
                                                <?php else: ?>
                                                    <span style="background: #fef3c7; color: #b45309; font-size: 0.72rem; font-weight: 800; padding: 4px 10px; border-radius: 6px; display: inline-block;">
                                                        ⏳ PENDIENTE
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 3rem 1rem; color: #94a3b8;">
                            <i class='bx bx-cube' style="font-size: 3rem; opacity: 0.4; display: block; margin-bottom: 0.5rem;"></i>
                            <h4 style="margin: 0; color: #64748b; font-weight: 800;">Aún no tienes solicitudes de balones</h4>
                            <p style="margin: 4px 0 0 0; font-size: 0.85rem;">Ingresa la cantidad en el formulario superior para generar tu primera solicitud y código QR.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Previsualizar Foto Evidencia -->
    <div id="modal-evidencia-view" style="display: none; position: fixed; inset: 0; z-index: 999999; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 1.5rem;">
        <div style="background: #fff; border-radius: 20px; max-width: 550px; width: 100%; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.5);">
            <div style="padding: 1.2rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h4 id="modal-foto-title" style="margin: 0; font-size: 1rem; font-weight: 800; color: #0f172a;">Evidencia de Entrega</h4>
                <button onclick="document.getElementById('modal-evidencia-view').style.display = 'none'" style="background: #e2e8f0; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer;">✕</button>
            </div>
            <div style="padding: 1.5rem; text-align: center; background: #0f172a;">
                <img id="modal-foto-src" src="" alt="Evidencia" style="max-width: 100%; max-height: 60vh; border-radius: 12px; object-fit: contain;">
            </div>
        </div>
    </div>

    <script>
        const PUNTOS_POR_BALON = <?= (int)$puntosPorBalon ?>;
        const CLIENTE_ID = <?= (int)($cliente['id'] ?? 0) ?>;
        let pendingVentaId = <?= !empty($ultimaPendiente) ? (int)$ultimaPendiente['id'] : 'null' ?>;

        function modificarBalones(delta) {
            const inp = document.getElementById('num-balones');
            let val = parseInt(inp.value) || 1;
            val = Math.max(1, val + delta);
            inp.value = val;
            recalcularPuntos();
        }

        function fijarBalones(cant) {
            document.getElementById('num-balones').value = cant;
            recalcularPuntos();
        }

        function recalcularPuntos() {
            const inp = document.getElementById('num-balones');
            let val = parseInt(inp.value) || 1;
            if (val < 1) { val = 1; inp.value = 1; }
            const total = val * PUNTOS_POR_BALON;
            document.getElementById('txt-calc-puntos').textContent = total;
        }

        async function enviarSolicitudPuntos() {
            const balones = parseInt(document.getElementById('num-balones').value) || 1;
            const btn = document.getElementById('btn-enviar-solicitud');
            const origHtml = btn.innerHTML;

            if (!CLIENTE_ID) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Punto de Venta no Vinculado',
                    text: 'Tu usuario no tiene un cliente asignado en la base de datos. Contacta con administración.'
                });
                return;
            }

            btn.disabled = true;
            btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> Generando Solicitud y QR...";

            try {
                const formData = new FormData();
                formData.append('cliente_id', CLIENTE_ID);
                formData.append('balones', balones);

                const res = await fetch('<?= BASE_URL ?>scan/solicitar-puntos-pv', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Solicitud Generada con Éxito!',
                        html: `Has solicitado <b>${data.puntos} puntos</b> por <b>${balones} balones de 10kg</b>.<br><br><b>Muestra tu Código QR al conductor</b> para que capture la foto de evidencia y apruebe tus puntos.`,
                        confirmButtonColor: '#800000',
                        confirmButtonText: 'Ver Código QR'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo generar la solicitud.',
                        confirmButtonColor: '#800000'
                    });
                }
            } catch (e) {
                btn.disabled = false;
                btn.innerHTML = origHtml;
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Red',
                    text: 'Ocurrió un error al enviar la solicitud.',
                    confirmButtonColor: '#800000'
                });
            }
        }

        function verFotoEvidenciaModal(url, balones) {
            document.getElementById('modal-foto-src').src = url;
            document.getElementById('modal-foto-title').textContent = 'Evidencia de Entrega: ' + balones + ' Balones de 10kg';
            document.getElementById('modal-evidencia-view').style.display = 'flex';
        }

        // ── Polling en Tiempo Real: Detecta cuando el conductor aprueba ──
        if (pendingVentaId) {
            const checkInterval = setInterval(async () => {
                try {
                    const res = await fetch('<?= BASE_URL ?>afiliados/estado-solicitud?id=' + pendingVentaId);
                    const data = await res.json();
                    if (data.success && data.estado === 'aprobado') {
                        clearInterval(checkInterval);
                        Swal.fire({
                            icon: 'success',
                            title: '🎉 ¡PUNTOS APROBADOS Y ACREDITADOS!',
                            html: `El conductor <b>${data.conductor_nombre || 'asignado'}</b> ha verificado los <b>${data.balones_verificados || ''} balones</b> con evidencia fotográfica.<br><br>¡Se sumaron <b>+${data.puntos} puntos</b> a tu saldo!`,
                            confirmButtonColor: '#10b981',
                            confirmButtonText: '¡Excelente!'
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                } catch (e) {}
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            recalcularPuntos();
        });
    </script>
</body>

</html>
