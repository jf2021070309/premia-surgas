<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Puntos — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        /* ── Layout Grid ── */
        .scan-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* ── Right panel animation ── */
        .scan-right-panel:not(.active) { opacity: 0.45; pointer-events: none; transition: opacity 0.3s; }
        .scan-right-panel.active { opacity: 1; pointer-events: auto; animation: slideIn 0.4s cubic-bezier(0.16,1,0.3,1); }
        @keyframes slideIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Search top grid ── */
        .scan-top-search {
            padding: 1.5rem 1.8rem;
            display: grid;
            grid-template-columns: 60% 1fr;
            gap: 2.5rem;
            align-items: end;
            background: #fff;
        }

        /* ── Method choice buttons ── */
        .choice-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .choice-btn {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.9rem 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
        }
        .choice-btn:hover {
            border-color: #800000;
            background: #fdf5f5;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(128,0,0,0.08);
        }
        .choice-btn i { font-size: 1.25rem; color: #800000; }
        .choice-btn span { font-size: 0.72rem; font-weight: 800; color: #1e293b; letter-spacing: 0.04em; text-transform: uppercase; }

        /* ── Scan label ── */
        .scan-label {
            display: block;
            font-size: 0.65rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.65rem;
        }

        /* ── DNI Input ── */
        .elite-input-wrapper { position: relative; display: flex; align-items: center; }
        .elite-input {
            flex: 1;
            height: 42px;
            padding: 0 3.2rem 0 1.15rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
            outline: none;
            transition: all 0.25s;
            background: #fff;
        }
        .elite-input::placeholder { color: #cbd5e1; font-weight: 400; }
        .elite-input:focus { border-color: #800000; box-shadow: 0 0 0 3px rgba(128,0,0,0.06); }

        .btn-search-icon {
            position: absolute;
            right: 4px;
            width: 34px; height: 34px;
            border-radius: 8px;
            background: #800000;
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-search-icon:hover { background: #660000; }

        /* ── Two-column inside Order card ── */
        .elite-card-content {
            display: flex;
            flex-direction: row;
        }
        .elite-card-main {
            flex: 1.4;
            padding: 1.5rem 1.8rem;
            border-right: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 1.35rem;
        }
        .elite-card-side {
            flex: 1;
            padding: 1.5rem 1.8rem;
            background: #fafbfc;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* ── Customer box ── */
        .elite-customer-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.85rem 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }
        .customer-avatar {
            width: 38px; height: 38px;
            background: #fdf2f2;
            border: 1px solid #fee2e2;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            color: #800000;
            flex-shrink: 0;
        }

        /* ── Selects ── */
        .form-select-scan {
            height: 42px;
            width: 100%;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 0 2.5rem 0 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            outline: none;
            appearance: none;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 0.85rem center / 1rem;
            transition: border-color 0.2s;
            cursor: pointer;
        }
        .form-select-scan:focus { border-color: #800000; box-shadow: 0 0 0 3px rgba(128,0,0,0.06); }

        /* ── Subtotal display box ── */
        .elite-subtotal-box {
            height: 42px;
            background: #f8fafc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #1e293b;
            font-size: 0.82rem;
            border: 1.5px solid #e2e8f0;
        }

        /* ── Operation rows ── */
        .op-row {
            padding: 0.75rem 1rem;
            background: #fff;
            border-radius: 8px;
            border: 1.5px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.78rem;
            transition: border-color 0.2s;
        }
        .op-row:hover { border-color: #e2e8f0; }

        #ops-container {
            flex: 1;
            min-height: 90px;
            max-height: 160px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        #ops-container::-webkit-scrollbar { width: 3px; }
        #ops-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        /* ── Total display ── */
        .total-display {
            padding: 1rem 1.15rem;
            background: #fff;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        #main-total-pts { font-size: 1.65rem; font-weight: 800; color: #1e293b; line-height: 1; margin-top: 2px; }

        /* ── Action buttons ── */
        .btn-scan-add {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            background: #000;
            color: #fff;
            border: none;
            height: 42px;
            width: 100%;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.82rem;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: all 0.25s;
            font-family: 'Inter', sans-serif;
            position: relative; overflow: hidden;
        }
        .btn-scan-add:hover { background: #111; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,0.2); }
        .btn-scan-add:disabled { opacity: 0.45; filter: grayscale(1); transform: none; box-shadow: none; cursor: not-allowed; }

        .btn-scan-register {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            background: #800000;
            color: #fff;
            border: none;
            height: 42px;
            width: 100%;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.82rem;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: all 0.25s;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 15px rgba(128,0,0,0.2);
        }
        .btn-scan-register:hover { background: #660000; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(128,0,0,0.3); }
        .btn-scan-register:disabled { opacity: 0.4; filter: grayscale(1); transform: none; box-shadow: none; cursor: not-allowed; }

        /* ── Close scanner ── */
        .btn-close-scanner {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            background: #f1f5f9; color: #475569; border: none;
            height: 36px; width: 100%;
            border-radius: 8px; margin-top: 0.75rem;
            font-size: 0.78rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-close-scanner:hover { background: #e2e8f0; color: #1e293b; }

        @media (max-width: 1100px) {
            .elite-card-content { flex-direction: column; }
            .elite-card-main { border-right: none; border-bottom: 1px solid #f1f5f9; }
        }
        @media (max-width: 768px) {
            .scan-top-search { grid-template-columns: 1fr; gap: 1.25rem; }
            .choice-row { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body onload="initLayout()">

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión de Puntos';
            $pageSubtitle = 'Suma el puntaje asignado al cliente';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">

            <!-- Section Header -->
            <div class="modern-section-header">
                <div class="section-title-flex">
                    <div class="section-title-text">
                        <h3>Gestión de Puntos</h3>
                        <span>Escanea el QR del cliente o búscalo por DNI para registrar su servicio</span>
                    </div>
                </div>
            </div>

            <div class="scan-wrapper">
                <input type="file" id="qr-input-file" accept="image/*" style="display: none;" onchange="onFileChange(event)">

                <!-- ── Card 1: Búsqueda QR ── -->
                <div class="card" style="margin-bottom: 0;">
                    <div class="card-header">
                        <div class="header-title-flex">
                            <i class='bx bx-qr-scan'></i>
                            <div class="title-text-group">
                                <h3>Búsqueda QR</h3>
                                <span>Escanea o ingresa el DNI manualmente</span>
                            </div>
                        </div>
                    </div>

                    <div class="scan-top-search">
                        <!-- Método de lectura -->
                        <div>
                            <label class="scan-label">Método de Lectura</label>
                            <div class="choice-row">
                                <div class="choice-btn" onclick="initScanner()">
                                    <i class='bx bx-camera'></i>
                                    <span>Usar Cámara</span>
                                </div>
                                <div class="choice-btn" onclick="document.getElementById('qr-input-file').click()">
                                    <i class='bx bx-image-add'></i>
                                    <span>QR Galería</span>
                                </div>
                            </div>
                        </div>

                        <!-- Búsqueda manual -->
                        <div>
                            <label class="scan-label">Búsqueda Manual</label>
                            <div class="elite-input-wrapper">
                                <input type="tel" id="manual-dni" class="elite-input" placeholder="DNI..." maxlength="8" onkeydown="if(event.key==='Enter') buscarPorDni()">
                                <button class="btn-search-icon" onclick="buscarPorDni()" title="Buscar">
                                    <i class='bx bx-search'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Scanner floating card -->
                <div id="qr-reader-container" style="display: none;">
                    <div class="card" style="margin-bottom: 0;">
                        <div class="card-header">
                            <div class="header-title-flex">
                                <i class='bx bx-camera'></i>
                                <div class="title-text-group">
                                    <h3>Escaneando QR</h3>
                                    <span>Apunta la cámara al código QR del cliente</span>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 1.25rem 1.8rem;">
                            <div id="reader"></div>
                            <button class="btn-close-scanner" onclick="stopScanner()">
                                <i class='bx bx-x'></i> Cerrar escáner
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ── Card 2: Orden de Suma (template) ── -->
                <div id="scan-right-panel" class="scan-right-panel">
                    <div id="right-panel-content"></div>
                </div>

                <template id="tpl-main-form">
                    <div class="card" style="margin-bottom: 0;">
                        <div class="card-header">
                            <div class="header-title-flex">
                                <i class='bx bx-receipt'></i>
                                <div class="title-text-group">
                                    <h3>Orden de Suma</h3>
                                    <span>Agrega los servicios y registra los puntos</span>
                                </div>
                            </div>
                        </div>

                        <div class="elite-card-content">
                            <!-- Izquierda: Cliente + Servicio -->
                            <div class="elite-card-main">
                                <!-- Cliente -->
                                <div>
                                    <label class="scan-label">Cliente</label>
                                    <div class="elite-customer-box">
                                        <div class="customer-avatar"><i class='bx bx-user'></i></div>
                                        <div>
                                            <b id="res-name" style="display: block; font-size: 0.88rem; color: #1e293b; margin-bottom: 2px;">— — —</b>
                                            <span id="res-phone" style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">No detectado</span>
                                        </div>
                                        <input type="hidden" id="client-id">
                                    </div>
                                </div>

                                <!-- Servicio + Cantidad -->
                                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                                    <div>
                                        <label class="scan-label">Servicio</label>
                                        <select id="main-op-type" class="form-select-scan" onchange="updateSubtotal()">
                                            <?php foreach ($operaciones as $op): ?>
                                                <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="scan-label">Cant.</label>
                                        <select id="main-op-qty" class="form-select-scan" onchange="updateSubtotal()">
                                            <?php for($i=1;$i<=10;$i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Suma + Añadir -->
                                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; align-items: end;">
                                    <div>
                                        <label class="scan-label">Suma</label>
                                        <div id="main-op-unit" class="elite-subtotal-box">0 PTS</div>
                                    </div>
                                    <button class="btn-scan-add" onclick="addOperation()">
                                        <i class='bx bx-plus-circle'></i> Añadir Servicio
                                    </button>
                                </div>
                            </div>

                            <!-- Derecha: Resumen -->
                            <div class="elite-card-side">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <label class="scan-label" style="margin: 0;">Resumen</label>
                                    <button onclick="location.reload()" style="background: none; border: none; font-size: 0.65rem; font-weight: 700; color: #94a3b8; cursor: pointer; text-transform: uppercase; display: flex; align-items: center; gap: 4px; font-family: 'Inter', sans-serif;">
                                        Limpiar <i class='bx bx-refresh' style="font-size: 0.9rem;"></i>
                                    </button>
                                </div>

                                <div id="ops-container">
                                    <div style="text-align: center; color: #cbd5e1; padding: 1.5rem 1rem; font-size: 0.75rem; font-weight: 500;">Vacío</div>
                                </div>

                                <div class="total-display" style="margin-top: auto;">
                                    <div>
                                        <span class="scan-label" style="margin: 0; font-size: 0.6rem;">Total</span>
                                        <div id="main-total-pts">0</div>
                                    </div>
                                    <b style="color: #800000; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em;">PUNTOS</b>
                                </div>

                                <button id="save-all-btn" class="btn-scan-register" onclick="saveAll()" disabled>
                                    <i class='bx bx-check-circle'></i> Registrar Puntos
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Template: Error -->
                <template id="tpl-error">
                    <div class="card" style="margin-bottom: 0;">
                        <div class="card-header" style="border-bottom-color: #fee2e2;">
                            <div class="header-title-flex">
                                <i class='bx bx-user-x' style="background: #fef2f2; color: #dc2626; border-color: #fecaca;"></i>
                                <div class="title-text-group">
                                    <h3 style="color: #dc2626;">Cliente no encontrado</h3>
                                    <span>Verifica los datos e intenta de nuevo</span>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 3rem 2rem; text-align: center;">
                            <i class='bx bx-user-x' style="font-size: 2.5rem; color: #fca5a5; display: block; margin-bottom: 1rem;"></i>
                            <h4 style="font-weight: 800; color: #1e293b; font-size: 1rem; margin-bottom: 0.5rem;">No encontrado</h4>
                            <p style="font-size: 0.82rem; color: #64748b; margin-bottom: 1.75rem;">El DNI o código QR no corresponde a ningún beneficiario registrado.</p>
                            <div style="display: flex; gap: 0.75rem; justify-content: center;">
                                <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-primary-premium" style="text-decoration: none;">
                                    <i class='bx bx-user-plus'></i> Nuevo Cliente
                                </a>
                                <button onclick="initLayout()" style="background: #f1f5f9; border: 1.5px solid #e2e8f0; color: #475569; padding: 0 1.5rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer; font-family: 'Inter', sans-serif;">
                                    Reintentar
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const baseUrl = BASE_URL;
        let html5QrCode;
        let operations = [];
        let running = false;

        function initLayout() {
            const content = document.getElementById('right-panel-content');
            content.innerHTML = '';
            content.appendChild(document.getElementById('tpl-main-form').content.cloneNode(true));
            if(document.getElementById('main-op-unit')) updateSubtotal();
            document.getElementById('scan-right-panel').classList.remove('active');
        }

        async function initScanner() {
            document.getElementById('qr-reader-container').style.display = 'block';
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 20, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
            try {
                await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error de cámara', text: 'No se pudo acceder a la cámara.' });
                document.getElementById('qr-reader-container').style.display = 'none';
            }
        }

        async function onFileChange(event) {
            if (event.target.files.length === 0) return;
            const imageFile = event.target.files[0];
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            Swal.fire({ title: 'Escaneando imagen...', didOpen: () => { Swal.showLoading(); } });
            try {
                const decodedText = await html5QrCode.scanFile(imageFile, false);
                Swal.close();
                onScanSuccess(decodedText);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo leer el QR de la imagen.' });
            }
            event.target.value = '';
        }

        function stopScanner() {
            if (html5QrCode) html5QrCode.stop().then(() => document.getElementById('qr-reader-container').style.display = 'none');
            else document.getElementById('qr-reader-container').style.display = 'none';
        }

        function onScanSuccess(decodedText) {
            let codigo = decodedText;
            if (decodedText.includes('c=')) {
                const urlParams = new URLSearchParams(decodedText.split('?')[1]);
                codigo = urlParams.get('c');
            } else if (decodedText.includes('/')) {
                codigo = decodedText.split('/').pop();
            }
            if (html5QrCode && html5QrCode.getState() === 2) {
                html5QrCode.stop().then(() => {
                    document.getElementById('qr-reader-container').style.display = 'none';
                    buscarCliente(codigo);
                });
            } else buscarCliente(codigo);
        }

        function buscarPorDni() {
            const dni = document.getElementById('manual-dni').value.trim();
            if (!/^\d{8}$/.test(dni)) {
                Swal.fire({ icon: 'warning', title: 'DNI inválido', text: 'Ingresa un DNI de 8 dígitos.' });
                return;
            }
            buscarCliente(dni);
        }

        async function buscarCliente(codigo) {
            const rightPanel = document.getElementById('scan-right-panel');
            const content = document.getElementById('right-panel-content');
            try {
                const res = await fetch(baseUrl + 'scan/buscar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ codigo })
                });
                const data = await res.json();
                operations = [];
                if (data.success) {
                    content.innerHTML = '';
                    content.appendChild(document.getElementById('tpl-main-form').content.cloneNode(true));
                    document.getElementById('res-name').innerText = data.cliente.nombre;
                    document.getElementById('res-phone').innerText = data.cliente.celular;
                    document.getElementById('client-id').value = data.cliente.id;
                    rightPanel.classList.add('active');
                    updateSubtotal();
                } else {
                    content.innerHTML = '';
                    content.appendChild(document.getElementById('tpl-error').content.cloneNode(true));
                    rightPanel.classList.add('active');
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error de conexión' });
            }
        }

        function updateSubtotal() {
            const select = document.getElementById('main-op-type');
            if(!select) return;
            const unit = parseInt(select.value);
            const qty = parseInt(document.getElementById('main-op-qty').value) || 1;
            const subtotalBox = document.getElementById('main-op-unit');
            if(subtotalBox) subtotalBox.innerText = (unit * qty) + ' PTS';
        }

        function addOperation() {
            const select = document.getElementById('main-op-type');
            if(!select) return;
            const typeName = select.options[select.selectedIndex].text.split(' (')[0];
            const unit = parseInt(select.value);
            const qty = parseInt(document.getElementById('main-op-qty').value) || 1;
            const subtotal = unit * qty;
            operations.push({ name: typeName, unit, qty, subtotal });
            renderOperations();
        }

        function removeOperation(index) {
            operations.splice(index, 1);
            renderOperations();
        }

        function renderOperations() {
            const container = document.getElementById('ops-container');
            if (operations.length === 0) {
                container.innerHTML = `<div style="text-align: center; color: #cbd5e1; padding: 1.5rem 1rem; font-size: 0.75rem; font-weight: 500;">Vacío</div>`;
                document.getElementById('main-total-pts').innerText = '0';
                document.getElementById('save-all-btn').disabled = true;
                return;
            }
            let html = '';
            let total = 0;
            operations.forEach((op, i) => {
                total += op.subtotal;
                html += `
                    <div class="op-row">
                        <div style="font-size: 0.78rem; font-weight: 700; color: #1e293b;">
                            ${op.name} <span style="color:#94a3b8; font-weight: 500;">×${op.qty}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-weight: 800; color: #800000; font-size: 0.82rem;">+${op.subtotal} pts</span>
                            <button onclick="removeOperation(${i})" style="background: none; border: none; cursor: pointer; color: #cbd5e1; font-size: 1.1rem; display: flex; padding: 0;" title="Eliminar">
                                <i class='bx bx-x'></i>
                            </button>
                        </div>
                    </div>`;
            });
            container.innerHTML = html;
            document.getElementById('main-total-pts').innerText = total;
            document.getElementById('save-all-btn').disabled = false;
        }

        async function saveAll() {
            if (running) return;
            const total = parseInt(document.getElementById('main-total-pts').innerText);
            const clientId = document.getElementById('client-id').value;
            if (total <= 0) return;
            running = true;
            const btn = document.getElementById('save-all-btn');
            btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> Guardando...";
            btn.disabled = true;
            try {
                const detalleString = operations.map(op => `${op.name} x${op.qty} (+${op.subtotal} pts)`).join(', ');
                const res = await fetch(baseUrl + 'scan/registrar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cliente_id: clientId, puntos: total, detalle: detalleString })
                });
                const data = await res.json();
                if (data.success) {
                    Swal.fire({ icon: 'success', title: '¡Registrado!', timer: 1500, showConfirmButton: false })
                        .then(() => window.location.href = baseUrl + 'panel');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    running = false;
                    btn.innerHTML = "<i class='bx bx-check-circle'></i> Registrar Puntos";
                    btn.disabled = false;
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error de conexión' });
                running = false;
                btn.innerHTML = "<i class='bx bx-check-circle'></i> Registrar Puntos";
                btn.disabled = false;
            }
        }
    </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
