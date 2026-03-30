<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suma de Puntos — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <style>
        :root { --p-wine: #800000; --wine-light: #fdf2f2; --wine-hover: #a00000; }
        
        .scan-container { max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; display: flex; flex-direction: column; gap: 2rem; }
        
        /* Navbar Filter Bar */
        .search-navbar { 
            background: white; border-radius: 100px; border: 1px solid #eef2f6; padding: 0.75rem 0.75rem 0.75rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }
        .search-navbar-left { display: flex; align-items: center; gap: 1rem; flex-shrink: 0; }
        .search-navbar-center { display: flex; gap: 0.75rem; flex: 1; justify-content: center; }
        .search-navbar-right { width: 320px; flex-shrink: 0; }

        /* Icon Badge */
        .elite-badge-icon { 
            width: 38px; height: 38px; background: var(--wine-light); color: var(--p-wine); 
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; border: 1px solid #fee2e2;
        }

        /* Buttons Navbar */
        .nav-choice-btn { 
            background: #fff; border: 1px solid #eef2f6; border-radius: 50px; 
            padding: 0.6rem 1.4rem; cursor: pointer; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; align-items: center; gap: 0.6rem; 
            font-size: 0.72rem; font-weight: 850; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .nav-choice-btn:hover { border-color: var(--p-wine); color: var(--p-wine); background: var(--wine-light); transform: translateY(-2px); }
        .nav-choice-btn i { font-size: 1.2rem; }

        /* Search Input Navbar */
        .nav-input-wrapper { position: relative; display: flex; align-items: center; }
        .nav-input { 
            width: 100%; height: 50px; padding: 0 3.5rem 0 1.5rem; border: 1px solid #eef2f6; border-radius: 50px;
            font-family: inherit; font-size: 0.85rem; font-weight: 600; color: #1e293b; outline: none; transition: 0.3s; background: #fafbfc;
        }
        .nav-input:focus { border-color: var(--p-wine); background: #fff; box-shadow: 0 0 0 4px rgba(128,0,0,0.06); }
        .nav-search-icon { 
            position: absolute; right: 5px; width: 40px; height: 40px; border-radius: 50%; 
            background: var(--p-wine); color: white; border: none; display: flex; 
            align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; transition: 0.3s;
        }
        .nav-search-icon:hover { background: var(--wine-hover); transform: scale(1.05); }

        /* Main Card */
        .elite-form-card { 
            background: white; border-radius: 24px; border: 1px solid #eef2f6; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.02); overflow: hidden;
        }
        .elite-card-header { padding: 1.75rem 2.5rem; border-bottom: 1px solid #f8fafc; display: flex; align-items: center; justify-content: space-between; }
        .elite-card-header h3 { margin: 0; font-size: 0.85rem; font-weight: 850; color: #1e293b; letter-spacing: -0.2px; text-transform: uppercase; }

        .elite-card-content { display: flex; flex-direction: row; min-height: 420px; }
        .elite-card-main { flex: 1.3; padding: 2.5rem; border-right: 1px solid #f8fafc; display: flex; flex-direction: column; gap: 2.2rem; }
        .elite-card-side { flex: 1; padding: 2.5rem; background: #fafbfc; display: flex; flex-direction: column; }

        /* Buttons & Labels */
        .elite-label { display: block; font-size: 0.65rem; font-weight: 950; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.8rem; }
        .btn-elite-wine { 
            display: flex; align-items: center; justify-content: center;
            background: var(--p-wine); color: white; border: none; height: 56px; width: 100%;
            border-radius: 50px; font-weight: 900; font-size: 0.82rem; text-transform: uppercase;
            letter-spacing: 1.5px; cursor: pointer; transition: 0.3s; box-shadow: 0 8px 20px rgba(128,0,0,0.2);
        }
        .btn-elite-wine:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(128,0,0,0.35); background: var(--wine-hover); }
        .btn-elite-wine:disabled { opacity: 0.4; filter: grayscale(1); transform: none; box-shadow: none; cursor: not-allowed; }

        .form-select-elite { height: 54px; width: 100%; border-radius: 16px; border: 1px solid #eef2f6; padding: 0 1.25rem; font-weight: 700; font-size: 0.85rem; color: #1e293b; outline: none; appearance: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 1.25rem center / 1.1rem; transition: 0.3s; }
        .form-select-elite:focus { border-color: var(--p-wine); box-shadow: 0 0 0 4px rgba(128,0,0,0.05); }

        .elite-customer-box { background: #fff; border: 1px solid #eef2f6; border-radius: 20px; padding: 1.25rem; display: flex; align-items: center; gap: 1.25rem; box-shadow: 0 4px 15px rgba(0,0,0,0.01); }
        .customer-avatar { width: 50px; height: 50px; background: var(--wine-light); border: 1px solid #fee2e2; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: var(--p-wine); }
        
        /* Summary Design */
        .op-row { padding: 1.1rem 1.4rem; background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; margin-bottom: 0.8rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; transition: 0.2s; }
        .op-row:hover { transform: translateX(5px); border-color: var(--p-wine); box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        
        .total-display { margin-top: auto; padding: 2rem; background: #fff; border-radius: 22px; border: 1px solid #eef2f6; display: flex; justify-content: space-between; align-items: center; }

        #ops-container { height: 260px; overflow-y: auto; padding-right: 8px; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 2px; }
        #ops-container::-webkit-scrollbar { width: 4px; }
        #ops-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        #main-total-pts { font-size: 2.6rem; font-weight: 950; color: #1e293b; line-height: 1; letter-spacing: -1px; }

        .active-panel { animation: eliteFadeIn 0.7s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes eliteFadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .placeholder-panel { opacity: 0.45; pointer-events: none; filter: blur(0.5px); }

        @media (max-width: 900px) {
            .search-navbar { border-radius: 24px; flex-direction: column; padding: 1.5rem; gap: 1.5rem; }
            .search-navbar-right { width: 100%; }
            .elite-card-content { flex-direction: column; }
            .elite-card-main { border-right: none; border-bottom: 1px solid #f8fafc; }
        }
    </style>
</head>
<body onload="initLayout()">

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión de Puntos';
            $pageSubtitle = 'Busca al cliente y suma su puntaje de forma rápida';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="scan-container">
            <!-- NAVBAR DE BÚSQUEDA REDONDEADA (ELITE) -->
            <div class="search-navbar">
                <div class="search-navbar-left">
                    <div class="elite-badge-icon"><i class='bx bx-qr-scan'></i></div>
                    <span class="elite-label" style="margin:0; color: var(--p-wine);">MODO B&Uacute;SQUEDA</span>
                </div>
                
                <div class="search-navbar-center">
                    <div class="nav-choice-btn" onclick="initScanner()">
                        <i class='bx bx-camera'></i>
                        <span>Activar C&aacute;mara</span>
                    </div>
                    <input type="file" id="qr-input-file" accept="image/*" style="display: none;" onchange="onFileChange(event)">
                    <button class="nav-choice-btn" onclick="document.getElementById('qr-input-file').click()" style="background: none; border: 1px solid #eef2f6;">
                        <i class='bx bx-image-add'></i>
                        <span>Leer desde Archivo</span>
                    </button>
                </div>

                <div class="search-navbar-right">
                    <div class="nav-input-wrapper">
                        <input type="tel" id="manual-dni" class="nav-input" placeholder="Buscar por DNI o C&oacute;digo..." maxlength="8">
                        <button class="nav-search-icon" onclick="buscarPorDni()"><i class='bx bx-search'></i></button>
                    </div>
                </div>
            </div>

            <!-- LECTOR QR OVERLAY -->
            <div id="qr-reader-container" style="display: none; width: 100%;">
                <div class="elite-form-card">
                    <div class="elite-card-header">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="elite-badge-icon" style="background: #000; color: #fff; border: none;"><i class='bx bx-camera'></i></div>
                            <h3>ESCANEANDO C&Oacute;DIGO QR</h3>
                        </div>
                        <button onclick="stopScanner()" style="background: #f1f5f9; border: none; width: 35px; height: 35px; border-radius: 50%; color: #475569; cursor: pointer; transition: 0.3s;"><i class='bx bx-x'></i></button>
                    </div>
                    <div class="elite-card-body" style="padding: 1.5rem; background: #000;">
                        <div id="reader" style="width: 100%; border-radius: 16px; overflow: hidden; border: 2px solid #333;"></div>
                    </div>
                </div>
            </div>

            <!-- PANEL CENTRAL DE OPERACIÓN -->
            <div id="scan-right-panel" class="scan-right-panel placeholder-panel">
                <div id="right-panel-content">
                    <!-- Loaded via tpl-main-form -->
                </div>
            </div>

            <template id="tpl-main-form">
                <div class="elite-form-card">
                    <div class="elite-card-header">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="elite-badge-icon"><i class='bx bx-user-circle'></i></div>
                            <h3>FORMULARIO DE CARGA DE PUNTOS</h3>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 0.62rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;"><?= date('d F Y') ?></span>
                        </div>
                    </div>

                    <div class="elite-card-content">
                        <!-- COLUMNA IZQUIERDA: FORMULARIO -->
                        <div class="elite-card-main">
                            <div>
                                <label class="elite-label">Detalles del Cliente</label>
                                <div class="elite-customer-box">
                                    <div class="customer-avatar"><i class='bx bx-user'></i></div>
                                    <div>
                                        <b id="res-name" style="display: block; font-size: 1rem; color: #1e293b; letter-spacing: -0.3px;">---</b>
                                        <span id="res-phone" style="font-size: 0.8rem; color: #64748b; font-weight: 500;">Pendiente de identificaci&oacute;n</span>
                                    </div>
                                    <input type="hidden" id="client-id">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                                <div class="form-group">
                                    <label class="elite-label">Seleccionar Servicio</label>
                                    <select id="main-op-type" class="form-select-elite" onchange="updateSubtotal()">
                                        <?php foreach ($operaciones as $op): ?>
                                            <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?> pts)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="elite-label">Cant.</label>
                                    <select id="main-op-qty" class="form-select-elite" onchange="updateSubtotal()">
                                        <?php for($i=1;$i<=15;$i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: end;">
                                <div class="form-group">
                                    <label class="elite-label">Subtotal</label>
                                    <div id="main-op-unit" style="height: 56px; background: #fff; border: 1.5px solid #eef2f6; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: #1e293b; font-size: 0.95rem;">0 PTS</div>
                                </div>
                                <button class="btn-elite-wine" onclick="addOperation()" style="background: #000; box-shadow: 0 8px 20px rgba(0,0,0,0.1);">A&Ntilde;ADIR A LISTA</button>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA: RESUMEN -->
                        <div class="elite-card-side">
                            <label class="elite-label">Resumen de Operaci&oacute;n</label>
                            <div id="ops-container">
                                <div style="text-align: center; color: #cbd5e1; padding: 4rem 1rem;">
                                    <i class='bx bx-shopping-bag' style="font-size: 2.5rem; opacity: 0.2; display: block; margin-bottom: 0.8rem;"></i>
                                    <span style="font-size: 0.8rem; font-weight: 600;">Sin movimientos</span>
                                </div>
                            </div>

                            <div class="total-display">
                                <div>
                                    <span class="elite-label" style="margin: 0; opacity: 0.6; font-size: 0.58rem;">Suma Total</span>
                                    <div id="main-total-pts">0</div>
                                </div>
                                <b style="color: var(--p-wine); font-size: 0.85rem; letter-spacing: 2px;">PUNTOS</b>
                            </div>

                            <button id="save-all-btn" class="btn-elite-wine" onclick="saveAll()" disabled style="margin-top: 1.5rem;">REGISTRAR PUNTOS</button>
                            <div style="text-align: center; margin-top: 1.5rem;">
                                <button onclick="location.reload()" style="background: none; border: none; font-size: 0.72rem; font-weight: 850; color: #94a3b8; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px;">Limpiar Formulario</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template id="tpl-error">
                <div class="elite-form-card" style="min-height: 400px; display: flex; align-items: center; justify-content: center; border-color: #fee2e2;">
                    <div style="text-align: center; padding: 4rem;">
                        <div class="elite-badge-icon" style="width: 70px; height: 70px; font-size: 2.5rem; margin: 0 auto 1.5rem;"><i class='bx bx-user-x' ></i></div>
                        <h2 style="font-weight: 950; color: #1e293b; font-size: 1.5rem; margin-bottom: 0.5rem;">¡Sin coincidencias!</h2>
                        <p style="font-size: 0.95rem; color: #64748b; margin-bottom: 2.5rem; max-width: 320px;">El cliente no existe o los datos ingresados son incorrectos. Por favor, verifica el DNI.</p>
                        <div style="display: flex; gap: 1rem; justify-content: center;">
                            <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-elite-wine" style="text-decoration: none; width: 220px; display: flex; align-items: center; justify-content: center;">Registrar Cliente</a>
                            <button onclick="initLayout()" style="background: #f1f5f9; color: #475569; padding: 0 1.5rem; border-radius: 50px; font-weight: 850; font-size: 0.8rem; border: none; cursor: pointer;">Volver</button>
                        </div>
                    </div>
                </div>
            </template>
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
            document.getElementById('scan-right-panel').classList.add('placeholder-panel');
        }

        async function initScanner() {
            document.getElementById('qr-reader-container').style.display = 'block';
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 20, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
            try {
                await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'C&aacute;mara no disponible' });
                document.getElementById('qr-reader-container').style.display = 'none';
            }
        }

        async function onFileChange(event) {
            if (event.target.files.length === 0) return;
            const imageFile = event.target.files[0];
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            Swal.fire({ title: 'Escaneando...', didOpen: () => { Swal.showLoading(); } });
            try {
                const decodedText = await html5QrCode.scanFile(imageFile, false);
                Swal.close();
                onScanSuccess(decodedText);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se detect&oacute; QR.' });
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
                Swal.fire({ icon: 'warning', title: 'DNI Inv&aacute;lido' });
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
                    const tpl = document.getElementById('tpl-main-form').content.cloneNode(true);
                    content.appendChild(tpl);
                    document.getElementById('res-name').innerText = data.cliente.nombre;
                    document.getElementById('res-phone').innerText = data.cliente.celular;
                    document.getElementById('client-id').value = data.cliente.id;
                    rightPanel.classList.remove('placeholder-panel');
                    rightPanel.classList.add('active-panel');
                    updateSubtotal();
                } else {
                    content.innerHTML = '';
                    content.appendChild(document.getElementById('tpl-error').content.cloneNode(true));
                    rightPanel.classList.remove('placeholder-panel');
                    rightPanel.classList.add('active-panel');
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error' });
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
                container.innerHTML = `<div style="text-align: center; color: #cbd5e1; padding: 4rem 1rem;"><i class='bx bx-shopping-bag' style="font-size: 2.5rem; opacity: 0.2; display: block; margin-bottom: 0.8rem;"></i><span style="font-size: 0.8rem; font-weight: 600;">Sin movimientos</span></div>`;
                document.getElementById('main-total-pts').innerText = '0';
                document.getElementById('save-all-btn').disabled = true;
                return;
            }
            let html = '';
            let total = 0;
            operations.forEach((op, i) => {
                total += op.subtotal;
                html += `<div class="op-row"><div style="font-weight: 700;">${op.name} <span style="color:#94a3b8; font-weight:500;">x${op.qty}</span></div><div style="display: flex; align-items: center; gap: 0.8rem;"><div style="font-weight: 800; color: #ff6600;">+${op.subtotal}</div><i class='bx bx-x' onclick="removeOperation(${i})" style="cursor:pointer; color:#94a3b8; font-size:1.1rem;"></i></div></div>`;
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
            btn.innerHTML = "REGISTRANDO..."; btn.disabled = true;
            try {
                const detalleString = operations.map(op => `${op.name} x${op.qty} (+${op.subtotal} pts)`).join(', ');
                const res = await fetch(baseUrl + 'scan/registrar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cliente_id: clientId, puntos: total, detalle: detalleString })
                });
                const data = await res.json();
                if (data.success) {
                    Swal.fire({ icon: 'success', title: '&iexcl;Exito!', timer: 1500, showConfirmButton: false }).then(() => window.location.href = baseUrl + 'panel');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    running = false; btn.innerHTML = "REGISTRAR PUNTOS"; btn.disabled = false;
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error' });
                running = false; btn.innerHTML = "REGISTRAR PUNTOS"; btn.disabled = false;
            }
        }
    </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
