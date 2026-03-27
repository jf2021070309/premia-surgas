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
        :root { --p-orange: #ff6600; }
        
        /* Layout Fixes */
        .scan-container { max-width: 650px; margin: 0 auto; padding-bottom: 5rem; }
        .v-screen { display: none; }
        .v-screen.active { display: block; animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Premium Floating Card */
        .scan-card-premium { 
            background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);
            border-radius: 32px; padding: 2.5rem; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.03), 0 1px 2px rgba(0,0,0,0.02); 
            border: 1px solid #ffffff; margin-bottom: 2rem;
            position: relative; overflow: hidden;
        }
        .scan-card-premium::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, var(--p-orange), #ff9e5e); }

        /* Actions styling */
        .btn-scan-main { 
            background: #111827; color: white; width: 100%; height: 75px; 
            border-radius: 20px; font-weight: 800; border: none; cursor: pointer; 
            display: flex; align-items: center; justify-content: center; gap: 1rem; 
            font-size: 1.25rem; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 25px rgba(17, 24, 39, 0.15);
        }
        .btn-scan-main:hover { transform: translateY(-3px) scale(1.01); background: #000; box-shadow: 0 15px 35px rgba(17, 24, 39, 0.2); }
        .btn-scan-main i { font-size: 1.8rem; color: var(--p-orange); }

        /* DNI Input Area */
        .search-area { background: #f8fafc; border-radius: 24px; padding: 1.5rem; border: 1.5px solid #edf2f7; margin: 2rem 0; }
        .dni-input-group { position: relative; display: flex; align-items: stretch; gap: 0.5rem; }
        .dni-input-group i.input-icon { position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); font-size: 1.4rem; color: #94a3b8; pointer-events: none; }
        .dni-input-field { 
            width: 100%; padding: 1.2rem 1.2rem 1.2rem 3.5rem; border: 2px solid #e2e8f0; 
            border-radius: 18px; outline: none; font-family: inherit; font-size: 1.2rem; 
            font-weight: 750; letter-spacing: 4px; color: #1e293b; background: white;
            transition: 0.3s;
        }
        .dni-input-field::placeholder { color: #cbd5e1; letter-spacing: 0; font-weight: 500; font-size: 1rem; }
        .dni-input-field:focus { border-color: var(--p-orange); box-shadow: 0 0 0 4px rgba(255, 102, 0, 0.1); }
        
        .btn-search-dni { 
            background: var(--p-orange); color: white; border: none; width: 65px; 
            border-radius: 18px; cursor: pointer; display: flex; align-items: center; 
            justify-content: center; font-size: 1.5rem; transition: 0.3s;
            box-shadow: 0 4px 12px rgba(255, 102, 0, 0.2);
        }
        .btn-search-dni:hover { transform: scale(1.05); background: #e05a00; }

        /* Result View */
        .client-card-elite { background: linear-gradient(135deg, #111827, #1e293b); color: white; border-radius: 24px; padding: 1.5rem; position: relative; margin-bottom: 2rem; box-shadow: 0 15px 40px rgba(0,0,0,0.15); }
        .client-card-elite .avatar-box { width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--p-orange); }
        .client-card-elite .client-tag { position: absolute; top: 1.5rem; right: 1.5rem; background: var(--p-orange); color: white; font-size: 0.65rem; font-weight: 800; padding: 4px 10px; border-radius: 50px; text-transform: uppercase; letter-spacing: 1px; }

        /* Ops List styling */
        .points-row { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem; background: white; border-radius: 20px; border: 1px solid #f1f5f9; margin-bottom: 0.8rem; transition: 0.3s; }
        .points-row:hover { transform: translateX(5px); border-color: #e2e8f0; background: #fafafa; }
        .points-row .qty-badge { background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; margin-right: 12px; }

        .total-display-premium { 
            margin-top: 3rem; background: white; border-radius: 28px; padding: 2rem; 
            display: flex; justify-content: space-between; align-items: center;
            border: 2px solid #111827; box-shadow: 0 12px 30px rgba(0,0,0,0.05);
        }
        .total-display-premium .label { display: block; font-size: 0.85rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 2px; }
        .total-display-premium .value { font-size: 3rem; font-weight: 900; line-height: 1; color: #111827; }
        .total-display-premium .pts-unit { color: var(--p-orange); margin-left: 8px; font-size: 1.2rem; }

        #reader { width: 100%; border-radius: 24px; overflow: hidden; border: none; box-shadow: inset 0 0 40px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Suma de Puntos';
            $pageSubtitle = '&iexcl;Hagamos sonre&iacute;r al cliente hoy!';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="scan-container">

                <!-- [PANTALLA 1] — SELECCIÓN INICIAL -->
                <div id="screen-start" class="v-screen active">
                    <div class="scan-card-premium">
                        <h3 style="font-weight: 800; text-align: center; margin-bottom: 2rem; color: #1e293b; font-size: 1.4rem;">Registrar Operaci&oacute;n</h3>
                        
                        <button class="btn-scan-main" onclick="initScanner()">
                            <i class='bx bx-qr-scan'></i> Escanear C&oacute;digo QR
                        </button>
                        
                        <div class="search-area">
                            <label style="display: block; text-align: center; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1.5rem;">O b&uacute;squeda manual</label>
                            
                            <div class="dni-input-group">
                                <i class='bx bx-id-card input-icon'></i>
                                <input type="tel" id="manual-dni" class="dni-input-field" placeholder="DNI del cliente" maxlength="8">
                                <button class="btn-search-dni" onclick="buscarPorDni()" title="Buscar cliente">
                                    <i class='bx bx-chevron-right'></i>
                                </button>
                            </div>
                        </div>

                        <div style="text-align: center;">
                            <input type="file" id="qr-input-file" accept="image/*" style="display: none;" onchange="onFileChange(event)">
                            <button onclick="document.getElementById('qr-input-file').click()" style="background: #f1f5f9; border: none; padding: 12px 24px; border-radius: 14px; color: #475569; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px;">
                                <i class='bx bx-image-add' style="font-size: 1.2rem;"></i> Importar desde galer&iacute;a
                            </button>
                        </div>
                    </div>
                </div>

                <!-- [PANTALLA 2] — SCANNER -->
                <div id="screen-scan" class="v-screen">
                    <div class="scan-card-premium" style="padding: 1.5rem;">
                        <div id="reader"></div>
                        <div style="text-align: center; margin-top: 1.5rem;">
                            <p style="color: #64748b; font-weight: 600; margin: 0;">Posicione el QR dentro del marco</p>
                            <small style="color: #94a3b8;">La lectura es autom&aacute;tica</small>
                        </div>
                    </div>
                    <button class="btn-secondary-premium" onclick="stopScanner()" style="width: 100%; border-radius: 20px; height: 60px; font-weight: 800;">
                        <i class='bx bx-arrow-back'></i> Cancelar Escaneo
                    </button>
                </div>

                <!-- [PANTALLA 3] — CONFIGURACIÓN DE PUNTOS -->
                <div id="screen-main" class="v-screen">
                    <div class="client-card-elite">
                        <span class="client-tag">Activo</span>
                        <div style="display: flex; align-items: center; gap: 1.25rem;">
                            <div class="avatar-box"><i class='bx bxs-user-badge'></i></div>
                            <div>
                                <h4 id="res-name" style="margin: 0; font-size: 1.25rem; font-weight: 800;">-</h4>
                                <span id="res-phone" style="font-size: 0.9rem; opacity: 0.7;">-</span>
                                <input type="hidden" id="client-id">
                            </div>
                        </div>
                    </div>

                    <div class="scan-card-premium">
                        <div class="form-group" style="margin-bottom: 2rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.8rem;">Producto / Operaci&oacute;n</label>
                            <select id="main-op-type" class="form-select-premium" onchange="updateSubtotal()" style="height: 60px; font-weight: 700;">
                                <?php foreach ($operaciones as $op): ?>
                                    <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?> pts)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                            <div class="form-group">
                                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.8rem;">Cantidad</label>
                                <select id="main-op-qty" class="form-select-premium" onchange="updateSubtotal()" style="height: 60px; font-weight: 700;">
                                    <?php for($i=1;$i<=10;$i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.8rem;">Subtotal Puntos</label>
                                <div id="main-op-unit" style="height: 60px; display: flex; align-items: center; justify-content: center; background: #fff7ed; border-radius: 18px; border: 1.5px solid #ffedd5; font-weight: 900; color: var(--p-orange); font-size: 1.2rem;">-</div>
                            </div>
                        </div>

                        <button class="btn-primary-premium" onclick="addOperation()" style="width: 100%; height: 60px; justify-content: center; background: #111827; border-radius: 18px; font-size: 1.1rem;">
                            <i class='bx bx-list-plus'></i> A&ntilde;adir al Listado
                        </button>
                    </div>

                    <div style="margin-top: 3rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.2rem;">
                            <h5 style="margin: 0; font-weight: 800; text-transform: uppercase; color: #94a3b8; letter-spacing: 1.5px;">Resumen de Suma</h5>
                            <span style="font-size: 0.8rem; font-weight: 600; color: #111827;">Lista de productos</span>
                        </div>
                        
                        <div id="ops-container" style="min-height: 100px;">
                            <!-- Dinámico -->
                        </div>

                        <div class="total-display-premium">
                            <div>
                                <span class="label">Total a Creditar</span>
                                <div style="display: flex; align-items: flex-end;">
                                    <div class="value" id="main-total-pts">0</div>
                                    <span class="pts-unit">PUNTOS</span>
                                </div>
                            </div>
                            <i class='bx bxs-coin-stack' style="font-size: 3.5rem; color: var(--p-orange); opacity: 0.2;"></i>
                        </div>

                        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1rem; margin-top: 2rem;">
                            <button id="save-all-btn" class="btn-primary-premium" onclick="saveAll()" style="height: 65px; justify-content: center; font-size: 1.2rem; background: #16a34a; border-color: #16a34a; box-shadow: 0 10px 25px rgba(22, 163, 74, 0.2);">
                                <i class='bx bx-check-double'></i> Confirmar y Guardar
                            </button>
                            <button class="btn-secondary-premium" onclick="location.reload()" style="height: 65px; justify-content: center; border-radius: 20px;">
                                <i class='bx bx-x'></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- [PANTALLA 4] — CLIENTE NO REGISTRADO -->
                <div id="screen-error" class="v-screen">
                    <div class="scan-card-premium" style="text-align: center; border-top: 10px solid #ef4444;">
                        <div style="width: 100px; height: 100px; background: #fef2f2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; font-size: 3.5rem;">
                            <i class='bx bx-shield-x'></i>
                        </div>
                        <h2 style="font-weight: 900; color: #1e293b; margin: 0 0 0.8rem;">C&oacute;digo no reconocido</h2>
                        <p style="color: #64748b; font-size: 1rem; line-height: 1.6; margin-bottom: 2.5rem;">El DNI o QR ingresado no existe. Verifique si el cliente ya fue registrado en el sistema.</p>
                        
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-primary-premium" style="justify-content: center; height: 65px; font-size: 1.1rem; background: #111827;">
                                <i class='bx bx-user-plus'></i> Registrar Nuevo Cliente
                            </a>
                            <button class="btn-secondary-premium" onclick="initScanner()" style="justify-content: center; height: 60px; border-radius: 18px;">
                                <i class='bx bx-repeat'></i> Intentar de nuevo
                            </button>
                        </div>
                    </div>
                </div>

            </div> <!-- .scan-container -->
        </div> <!-- .container -->
    </div> <!-- .admin-layout -->

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const baseUrl = BASE_URL;
        let html5QrCode;
        let operations = [];
        let running = false;

        function showScreen(id) {
            document.querySelectorAll('.v-screen').forEach(s => s.classList.remove('active'));
            document.getElementById(id).classList.add('active');
            
            const subtitles = {
                'screen-start': '&iexcl;Hagamos sonre&iacute;r al cliente hoy!',
                'screen-scan': 'Lectura de C&oacute;digo QR',
                'screen-main': 'Calculando puntos ganados',
                'screen-error': 'Lo sentimos, no encontramos el registro'
            };
            document.querySelector('.page-subtitle').innerHTML = subtitles[id] || 'Panel de Gesti&oacute;n';
        }

        async function initScanner() {
            showScreen('screen-scan');
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            const config = { fps: 20, qrbox: { width: 280, height: 280 }, aspectRatio: 1.0 };
            try {
                await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'C&aacute;mara no disponible', text: 'Permitir acceso a la c&aacute;mara para escanear.' });
                showScreen('screen-start');
            }
        }

        async function onFileChange(event) {
            if (event.target.files.length === 0) return;
            const imageFile = event.target.files[0];
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            Swal.fire({ title: 'Analizando QR...', didOpen: () => { Swal.showLoading(); } });
            try {
                const decodedText = await html5QrCode.scanFile(imageFile, false);
                Swal.close();
                onScanSuccess(decodedText);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error de Lectura', text: 'No se detect&oacute; un QR v&aacute;lido.' });
            }
            event.target.value = '';
        }

        function stopScanner() {
            if (html5QrCode) html5QrCode.stop().then(() => showScreen('screen-start'));
            else showScreen('screen-start');
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
                html5QrCode.stop().then(() => buscarCliente(codigo));
            } else {
                buscarCliente(codigo);
            }
        }

        function buscarPorDni() {
            const dni = document.getElementById('manual-dni').value.trim();
            if (!/^\d{8}$/.test(dni)) {
                Swal.fire({ icon: 'warning', title: 'DNI Inv&aacute;lido', text: 'El DNI debe tener 8 d&iacute;gitos.' });
                return;
            }
            buscarCliente(dni);
        }

        async function buscarCliente(codigo) {
            try {
                const res = await fetch(baseUrl + 'scan/buscar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ codigo })
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('res-name').innerText = data.cliente.nombre;
                    document.getElementById('res-phone').innerText ='Cel: ' + data.cliente.celular;
                    document.getElementById('client-id').value = data.cliente.id;
                    showScreen('screen-main');
                    updateSubtotal();
                } else {
                    showScreen('screen-error');
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error de Conexi&oacute;n' });
                showScreen('screen-start');
            }
        }

        function updateSubtotal() {
            const unit = parseInt(document.getElementById('main-op-type').value);
            const qty = parseInt(document.getElementById('main-op-qty').value) || 1;
            document.getElementById('main-op-unit').innerText = (unit * qty) + ' PTS';
        }

        function addOperation() {
            const select = document.getElementById('main-op-type');
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
                container.innerHTML = '<div style="text-align: center; color: #94a3b8; padding: 3rem; background: #f8fafc; border-radius: 20px; border: 2px dashed #e2e8f0; font-weight: 600;">A&ntilde;ada una operaci&oacute;n para comenzar</div>';
                document.getElementById('main-total-pts').innerText = '0';
                document.getElementById('save-all-btn').disabled = true;
                return;
            }
            let html = '';
            let total = 0;
            operations.forEach((op, i) => {
                total += op.subtotal;
                html += `
                    <div class="points-row">
                        <div style="display: flex; align-items: center;">
                            <span class="qty-badge">${op.qty}</span>
                            <div class="op-info">
                                <b>${op.name}</b>
                                <span>Valor unitario: ${op.unit} pts</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <div class="op-pts">+${op.subtotal}</div>
                            <button onclick="removeOperation(${i})" style="background:#fef2f2; border:none; color:#ef4444; width:36px; height:36px; border-radius:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.2s;">
                                <i class='bx bx-trash-alt' style="font-size:1.2rem;"></i>
                            </button>
                        </div>
                    </div>
                `;
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
            btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> PROCESANDO...";
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
                        icon: 'success', title: '&iexcl;Suma Exitosa!', text: 'El saldo del cliente ha sido actualizado correctamente.',
                        timer: 2500, showConfirmButton: false, background: '#ffffff', color: '#111827'
                    }).then(() => window.location.href = baseUrl + 'panel');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    running = false;
                    btn.innerHTML = "<i class='bx bx-check-double'></i> Confirmar y Guardar";
                    btn.disabled = false;
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Fallo de Servidor' });
                running = false;
                btn.innerHTML = "<i class='bx bx-check-double'></i> Confirmar y Guardar";
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
