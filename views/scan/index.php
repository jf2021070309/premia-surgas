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
        :root { --p-wine: #800000; --dark-blue: #000; }
        
        /* Layout Grid */
        .scan-wrapper { 
            display: flex; gap: 2rem; align-items: stretch;
            max-width: 1400px; margin: 1.5rem auto; padding: 0 1.5rem;
            min-height: 520px;
        }
        
        .scan-left-panel { flex: 0 0 350px; display: flex; flex-direction: column; }
        .scan-right-panel { flex: 1; display: flex; flex-direction: column; }
        
        /* Placeholder state for right panel */
        .scan-right-panel:not(.active) { opacity: 0.5; pointer-events: none; filter: grayscale(0.5); transition: 0.3s; }
        .scan-right-panel.active { opacity: 1; pointer-events: auto; filter: none; animation: slideIn 0.5s ease-out; }

        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        
        /* Elite Card Style */
        .elite-form-card { 
            background: white; border-radius: 20px; 
            border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.02);
            overflow: hidden; height: 100%;
            display: flex; flex-direction: column;
        }
        .elite-card-header { 
            background: #fff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: 1rem; flex-shrink: 0;
        }
        .elite-header-icon { 
            width: 42px; height: 42px; background: #fffcfc; color: var(--p-wine); 
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; border: 1px solid #fee2e2; box-shadow: 0 2px 5px rgba(128,0,0,0.05);
        }
        .elite-card-header h3 { margin: 0; font-size: 0.95rem; font-weight: 800; color: #1e293b; letter-spacing: -0.5px; text-transform: uppercase; }

        .elite-card-body { padding: 2.5rem 2rem; flex: 1; display: flex; flex-direction: column; }

        /* Two Column Layout (Inside Card) */
        .elite-card-content { display: flex; flex-direction: row; flex: 1; }
        .elite-card-main { flex: 1.2; padding: 2.5rem; border-right: 1px solid #f1f5f9; display: flex; flex-direction: column; justify-content: space-between; }
        .elite-card-side { flex: 1; padding: 2.5rem; background: #fafbfc; display: flex; flex-direction: column; }

        .elite-label { display: block; font-size: 0.72rem; font-weight: 950; color: var(--p-wine); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.25rem; }

        .choice-row { display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 2rem; }
        .choice-btn { 
            background: white; border: 1.5px solid #f1f5f9; border-radius: 18px; 
            padding: 1.5rem; cursor: pointer; transition: 0.3s;
            display: flex; flex-direction: row; align-items: center; gap: 1.5rem;
        }
        .choice-btn:hover { border-color: var(--p-wine); transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.04); }
        .choice-btn i { font-size: 2rem; color: #b91c1c; transition: 0.3s; }
        .choice-btn span { font-size: 0.85rem; font-weight: 800; color: #1e293b; letter-spacing: 0.3px; }

        .elite-input-wrapper { position: relative; margin-top: auto; display: flex; align-items: center; }
        .elite-input { 
            flex: 1; height: 55px; padding: 0 4rem 0 1.5rem; border: 1.5px solid #f1f5f9; border-radius: 14px;
            font-family: inherit; font-size: 0.95rem; font-weight: 600; color: #1e293b; outline: none; transition: 0.3s; background: #fff;
        }
        .elite-input::placeholder { color: #cbd5e1; font-weight: 500; font-size: 0.85rem; }
        .elite-input:focus { border-color: var(--p-wine); box-shadow: 0 0 0 4px rgba(128,0,0,0.05); }

        .btn-search-icon { 
            position: absolute; right: 6px; width: 43px; height: 43px; border-radius: 10px; 
            background: var(--p-wine); color: white; border: none; display: flex; 
            align-items: center; justify-content: center; font-size: 1.4rem; cursor: pointer; transition: 0.3s;
        }

        .btn-elite-black { 
            display: flex; align-items: center; justify-content: center;
            background: #000; color: white; border: none; height: 58px; width: 100%;
            border-radius: 50px; font-weight: 900; font-size: 0.9rem; text-transform: uppercase;
            letter-spacing: 1.5px; cursor: pointer; transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .btn-elite-black:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0,0,0,0.25); background: #000; }
        .btn-elite-black:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .elite-customer-box { background: #fff; border: 1.5px solid #f1f5f9; border-radius: 18px; padding: 1.5rem; margin-bottom: 0; display: flex; align-items: center; gap: 1.5rem; transition: 0.3s; }
        .elite-customer-box:hover { border-color: #e2e8f0; }
        .customer-avatar { width: 52px; height: 52px; background: #fdf2f2; border: 1px solid #fee2e2; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--p-wine); }

        .form-select-elite { height: 55px; width: 100%; border-radius: 14px; border: 1.5px solid #f1f5f9; padding: 0 1.5rem; font-weight: 700; font-size: 0.95rem; color: #1e293b; outline: none; appearance: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 1.25rem center / 1.25rem; }

        .op-row { padding: 1.25rem; background: #fff; border-radius: 14px; border: 1px solid #f1f5f9; margin-bottom: 0.8rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; transition: 0.2s; }
        .op-row:hover { border-color: #e2e8f0; background: #fafafa; }
        
        .total-display { margin-top: auto; padding: 2rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }

        .elite-subtotal-box { height: 55px; background: #fff; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: #1e293b; font-size: 1rem; border: 1.5px solid #f1f5f9; }

        #reader { width: 100%; border-radius: 16px; overflow: hidden; background: #000; }

        /* Custom Scrollbar for Operations List */
        #ops-container { 
            height: 230px; 
            overflow-y: auto; 
            padding-right: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        #ops-container::-webkit-scrollbar { width: 4px; }
        #ops-container::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        #ops-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        #main-op-add-btn-row { margin-top: auto; padding-top: 2rem; }

        @media (max-width: 1100px) {
            .scan-wrapper { flex-direction: column; align-items: stretch; }
            .scan-left-panel { flex: 1; width: 100%; }
            .elite-card-content { flex-direction: column; }
            .elite-card-main { border-right: none; border-bottom: 1px solid #f1f5f9; }
        }
    </style>
</head>
<body onload="initLayout()">

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión de Puntos';
            $pageSubtitle = '¡Puntaje asignado, cliente feliz!';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="scan-wrapper">
                <!-- Input de archivo oculto para escaneo desde galería -->
                <input type="file" id="qr-input-file" accept="image/*" style="display: none;" onchange="onFileChange(event)">

                <!-- PANEL IZQUIERDO: BÚSQUEDA Y SCAN -->
                <div class="scan-left-panel">
                    <div class="elite-form-card">
                        <div class="elite-card-header">
                            <div class="elite-header-icon"><i class='bx bx-qr-scan'></i></div>
                            <h3>REGISTRAR OPERACI&Oacute;N</h3>
                        </div>
                        
                        <div class="elite-card-body">
                            <div>
                                <label class="elite-label">M&eacute;todo de Lectura QR</label>
                                <div class="choice-row">
                                    <div class="choice-btn" onclick="initScanner()">
                                        <div class="elite-header-icon" style="flex-shrink:0;"><i class='bx bx-camera'></i></div>
                                        <span>USAR C&Aacute;MARA</span>
                                    </div>
                                    <div class="choice-btn" onclick="document.getElementById('qr-input-file').click()">
                                        <div class="elite-header-icon" style="flex-shrink:0;"><i class='bx bx-image-add'></i></div>
                                        <span>GALERIA / QR</span>
                                    </div>
                                </div>
                            </div>

                            <div class="elite-input-wrapper">
                                <div style="width: 100%;">
                                    <label class="elite-label">B&uacute;squeda Manual (DNI)</label>
                                    <div style="position: relative;">
                                        <input type="tel" id="manual-dni" class="elite-input" placeholder="Ej. 12345678" maxlength="8">
                                        <button class="btn-search-icon" onclick="buscarPorDni()" title="Buscar cliente">
                                            <i class='bx bx-search'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lector QR (Sustituye contenido si se activa cámara) -->
                    <div id="qr-reader-container" style="display: none; height: 100%;">
                        <div class="elite-form-card">
                            <div class="elite-card-header">
                                <div class="elite-header-icon"><i class='bx bx-camera'></i></div>
                                <h3>ESCANEANDO QR</h3>
                            </div>
                            <div class="elite-card-body" style="padding: 1rem;">
                                <div id="reader"></div>
                                <button class="btn-elite-black" onclick="stopScanner()" style="background: #f1f5f9; color: #475569; margin-top: 1rem; height: 40px;">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PANEL DERECHO: DETALLES DE OPERACIÓN -->
                <div id="scan-right-panel" class="scan-right-panel">
                    <div id="right-panel-content" style="height: 100%;">
                        <!-- Se precarga el template vía JS (initLayout) -->
                    </div>
                </div>

                <!-- TEMPLATE: FORMULARIO DE REGISTRO -->
                <template id="tpl-main-form">
                    <div class="elite-form-card">
                        <div class="elite-card-header">
                            <div class="elite-header-icon"><i class='bx bx-user-circle'></i></div>
                            <h3>ORDEN DE SUMA DE PUNTOS</h3>
                        </div>

                        <div class="elite-card-content">
                            <!-- SECCIÓN DATOS -->
                            <div class="elite-card-main">
                                <div>
                                    <label class="elite-label">Cliente Seleccionado</label>
                                    <div class="elite-customer-box">
                                        <div class="customer-avatar"><i class='bx bx-user'></i></div>
                                        <div>
                                            <b id="res-name" style="display: block; font-size: 1.1rem; color: #1e293b; letter-spacing: -0.5px;">- - -</b>
                                            <span id="res-phone" style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Esperando cliente...</span>
                                        </div>
                                        <input type="hidden" id="client-id">
                                    </div>
                                </div>

                                <div style="margin: 2rem 0;">
                                    <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                                        <div class="form-group">
                                            <label class="elite-label">Tipo de Recarga</label>
                                            <select id="main-op-type" class="form-select-elite" onchange="updateSubtotal()">
                                                <?php foreach ($operaciones as $op): ?>
                                                    <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?> pts)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="elite-label">Cantidad</label>
                                            <select id="main-op-qty" class="form-select-elite" onchange="updateSubtotal()">
                                                <?php for($i=1;$i<=10;$i++): ?>
                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="main-op-add-btn-row">
                                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: end;">
                                        <div class="form-group">
                                            <label class="elite-label">Subtotal</label>
                                            <div id="main-op-unit" class="elite-subtotal-box">0 PTS</div>
                                        </div>
                                        <button class="btn-elite-black" onclick="addOperation()" style="margin: 0;">
                                            A&Ntilde;ADIR A LISTA
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- SECCIÓN RESUMEN -->
                            <div class="elite-card-side">
                                <label class="elite-label">Resumen de Operaci&oacute;n</label>
                                <div id="ops-container">
                                    <div style="text-align: center; color: #cbd5e1; padding: 4rem 1rem; font-weight: 500; font-size: 0.8rem;">
                                        <i class='bx bx-list-check' style="font-size: 2.5rem; opacity: 0.25; display: block; margin-bottom: 1rem;"></i>
                                        Lista vac&iacute;a
                                    </div>
                                </div>

                                <div class="total-display">
                                    <div>
                                        <span class="elite-label" style="margin: 0; opacity: 0.65; font-size: 0.65rem;">Total Acumulado</span>
                                        <div id="main-total-pts" style="font-size: 2.5rem; font-weight: 950; color: #1e293b; line-height: 1; margin-top: 0.4rem;">0</div>
                                    </div>
                                    <div style="text-align: right; font-weight: 900; color: #b91c1c; font-size: 0.75rem; letter-spacing: 1.5px;">PUNTOS</div>
                                </div>

                                <button id="save-all-btn" class="btn-elite-black" onclick="saveAll()" disabled style="margin-top: 1.5rem;">REGISTRAR</button>
                                <div style="text-align: center; margin-top: 1.5rem;">
                                    <button onclick="location.reload()" style="background: none; border: none; font-size: 0.7rem; font-weight: 900; color: #94a3b8; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s;" onmouseover="this.style.color='#b91c1c'" onmouseout="this.style.color='#94a3b8'">Cancelar todo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- TEMPLATE: ERROR NO ENCONTRADO -->
                <template id="tpl-error">
                    <div class="elite-form-card">
                        <div class="elite-card-header" style="border-bottom-color: #fee2e2;">
                            <div class="elite-header-icon" style="background:#fef2f2; color:#ef4444; border-color:#fee2e2;"><i class='bx bx-user-x' ></i></div>
                            <h3 style="color: #991b1b;">CLIENTE NO ENCONTRADO</h3>
                        </div>
                        <div class="elite-card-body" style="text-align: center; justify-content: center;">
                            <h2 style="font-weight: 900; color: #1e293b; font-size: 1.5rem; margin-bottom: 1rem; letter-spacing: -0.5px;">No se encontr&oacute; al cliente</h2>
                            <p style="font-size: 0.95rem; color: #64748b; line-height: 1.6; margin-bottom: 3rem; max-width: 400px; margin-left: auto; margin-right: auto;">El c&oacute;digo o DNI no coincide con ningún registro en nuestra base de datos. &iquest;Deseas crearlo ahora?</p>
                            
                            <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-elite-black" style="text-decoration: none; display: flex; align-items: center; justify-content: center; width: 280px; margin: 0 auto;">Nuevo Cliente</a>
                            <button onclick="initLayout()" style="background: none; border: none; font-size: 0.8rem; font-weight: 800; color: #94a3b8; margin-top: 2rem; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;">Volver a buscar</button>
                        </div>
                    </div>
                </template>

            </div> <!-- .scan-wrapper -->
        </div> <!-- .container -->
    </div> <!-- .admin-layout -->

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const baseUrl = BASE_URL;
        let html5QrCode;
        let operations = [];
        let running = false;

        function initLayout() {
            const rightPanel = document.getElementById('scan-right-panel');
            const content = document.getElementById('right-panel-content');
            
            content.innerHTML = '';
            const tpl = document.getElementById('tpl-main-form').content.cloneNode(true);
            content.appendChild(tpl);
            
            const unitBox = document.getElementById('main-op-unit');
            if(unitBox) updateSubtotal();

            rightPanel.classList.remove('active');
        }

        async function initScanner() {
            document.getElementById('qr-reader-container').style.display = 'block';
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 20, qrbox: { width: 280, height: 280 }, aspectRatio: 1.0 };
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
            if (html5QrCode) html5QrCode.stop().then(() => {
                document.getElementById('qr-reader-container').style.display = 'none';
            });
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
            } else {
                buscarCliente(codigo);
            }
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
                    
                    rightPanel.classList.add('active');
                    updateSubtotal();
                } else {
                    content.innerHTML = '';
                    const tpl = document.getElementById('tpl-error').content.cloneNode(true);
                    content.appendChild(tpl);
                    rightPanel.classList.add('active');
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
                container.innerHTML = `
                    <div style="text-align: center; color: #cbd5e1; padding: 4rem 1rem; font-weight: 500; font-size: 0.8rem;">
                        <i class='bx bx-list-check' style="font-size: 2.5rem; opacity: 0.25; display: block; margin-bottom: 1rem;"></i>
                        Lista vac&iacute;a
                    </div>
                `;
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
                        <div style="font-weight: 700;">${op.name} <span style="color:#94a3b8; font-weight:500;">x${op.qty}</span></div>
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <div style="font-weight: 800; color: #ff6600;">+${op.subtotal}</div>
                            <i class='bx bx-x' onclick="removeOperation(${i})" style="cursor:pointer; color:#94a3b8; font-size:1.1rem; padding: 4px; border-radius: 50%; transition: 0.2s;" onmouseover="this.style.background='#fee2e2'; this.style.color='#ef4444'" onmouseout="this.style.background='transparent'; this.style.color='#94a3b8'"></i>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
            document.getElementById('main-total-pts').innerText = total;
            document.getElementById('save-all-btn').disabled = false;

            // Scroll to bottom on add
            container.scrollTop = container.scrollHeight;
        }

        async function saveAll() {
            if (running) return;
            const total = parseInt(document.getElementById('main-total-pts').innerText);
            const clientId = document.getElementById('client-id').value;
            if (total <= 0) return;

            running = true;
            const btn = document.getElementById('save-all-btn');
            btn.innerHTML = "REGISTRANDO...";
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
                    Swal.fire({
                        icon: 'success', title: '&iexcl;Suma Exitosa!', text: 'El saldo del cliente ha sido actualizado.',
                        timer: 2000, showConfirmButton: false
                    }).then(() => window.location.href = baseUrl + 'panel');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    running = false;
                    btn.innerHTML = "REGISTRAR";
                    btn.disabled = false;
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error' });
                running = false;
                btn.innerHTML = "REGISTRAR";
                btn.disabled = false;
            }
        }
    </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
