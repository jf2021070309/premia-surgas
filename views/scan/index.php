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
        :root { --p-orange: #ff6600; --dark-blue: #0f172a; }
        
        .scan-container { max-width: 580px; margin: 0 auto; padding-bottom: 5rem; }
        .v-screen { display: none; }
        .v-screen.active { display: block; animation: smoothReveal 0.5s ease-out; }
        @keyframes smoothReveal { from { opacity: 0; filter: blur(10px); transform: scale(0.98); } to { opacity: 1; filter: blur(0); transform: scale(1); } }

        /* Modern Choice Cards */
        .option-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; }
        .choice-card { 
            background: white; border-radius: 24px; padding: 2rem 1.5rem; text-align: center;
            border: 1px solid #f1f5f9; cursor: pointer; transition: 0.3s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02); display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .choice-card:hover { transform: translateY(-5px); border-color: var(--p-orange); box-shadow: 0 15px 35px rgba(255,102,0,0.08); }
        .choice-card i { font-size: 2.8rem; color: var(--dark-blue); margin-bottom: 1rem; transition: 0.3s; }
        .choice-card:hover i { color: var(--p-orange); transform: scale(1.1); }
        .choice-card span { font-weight: 700; font-size: 0.95rem; color: #334155; }

        /* Unified Card Style */
        .premium-box { background: white; border-radius: 30px; padding: 2.2rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.03); margin-bottom: 1.5rem; }
        
        /* High-Impact Input Group */
        .search-group-elite { position: relative; margin: 1.5rem 0; width: 100%; }
        .search-group-elite input { 
            width: 100%; height: 65px; border-radius: 20px; border: 2.5px solid #f1f5f9; 
            padding: 0 3.8rem; font-size: 1.25rem; font-weight: 700; color: var(--dark-blue); 
            letter-spacing: 5px; background: #fafafa; transition: 0.3s; text-align: center;
        }
        .search-group-elite input:focus { border-color: var(--p-orange); background: white; box-shadow: 0 10px 25px rgba(255,102,0,0.08); }
        .search-group-elite .icon-prefix { position: absolute; left: 1.4rem; top: 50%; transform: translateY(-50%); font-size: 1.6rem; color: #94a3b8; }
        .search-group-elite .btn-search { 
            position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); 
            width: 50px; height: 50px; border-radius: 15px; background: var(--dark-blue); 
            color: white; border: none; display: flex; align-items: center; justify-content: center; 
            font-size: 1.4rem; cursor: pointer; transition: 0.3s;
        }
        .search-group-elite .btn-search:hover { background: var(--p-orange); transform: translateY(-50%) scale(1.05); }

        /* Gallery Button Elite */
        .btn-gallery-elite { 
            width: 100%; height: 60px; background: #f8fafc; border: 1.5px dashed #cbd5e1; 
            border-radius: 20px; color: #475569; font-weight: 700; display: flex; 
            align-items: center; justify-content: center; gap: 0.8rem; cursor: pointer; 
            transition: 0.3s; font-size: 0.95rem;
        }
        .btn-gallery-elite:hover { background: #fffcf0; border-color: var(--p-orange); color: var(--p-orange); }

        /* Result View Elite */
        .customer-card { background: var(--dark-blue); border-radius: 28px; padding: 1.8rem; color: white; display: flex; align-items: center; gap: 1.25rem; margin-bottom: 2rem; box-shadow: 0 15px 35px rgba(0,0,0,0.15); overflow: hidden; position: relative; }
        .customer-card::after { content: ""; position: absolute; right: -20px; bottom: -20px; font-family: 'boxicons'; content: "\eb80"; font-size: 8rem; opacity: 0.05; transform: rotate(-15deg); }
        .customer-card .avatar { width: 65px; height: 65px; background: rgba(255,255,255,0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--p-orange); }

        .form-select-elite { height: 60px; width: 100%; border-radius: 18px; border: 2px solid #f1f5f9; padding: 0 1.25rem; font-weight: 700; font-size: 1rem; color: var(--dark-blue); outline: none; appearance: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 1rem center / 1.5rem; transition: 0.3s; }
        .form-select-elite:focus { border-color: var(--p-orange); }

        .op-pill { background: white; border-radius: 22px; padding: 1.25rem; border: 1px solid #e2e8f0; margin-bottom: 0.8rem; display: flex; justify-content: space-between; align-items: center; transition: 0.3s; border-left: 5px solid var(--p-orange); }
        .op-pill:hover { transform: translateX(5px); box-shadow: 0 8px 25px rgba(0,0,0,0.04); }

        .total-plate { background: #fffcf0; border-radius: 25px; padding: 1.8rem 2.2rem; display: flex; justify-content: space-between; align-items: center; border: 2px solid #fff3e0; margin: 2rem 0; overflow: hidden; position: relative; }
        .total-plate::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 6px; background: var(--p-orange); }
        .total-plate .val { font-size: 3.2rem; font-weight: 900; color: var(--dark-blue); line-height: 1; }
        .total-plate .lbl { display: block; font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 1px; }

        .btn-confirm-elite { width: 100%; height: 75px; background: var(--dark-blue); color: white; border-radius: 24px; font-weight: 800; font-size: 1.3rem; border: none; cursor: pointer; transition: 0.3s; box-shadow: 0 12px 30px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; gap: 1rem; }
        .btn-confirm-elite:hover { background: var(--p-orange); transform: translateY(-4px); }
        
        #reader { width: 100%; border-radius: 25px; overflow: hidden; }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gesti&oacute;n de Puntos';
            $pageSubtitle = '&iexcl;Puntaje asignado, cliente feliz!';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="scan-container">

                <!-- [PANTALLA 1] — ELECCIÓN INICIAL -->
                <div id="screen-start" class="v-screen active">
                    <h5 style="font-weight: 800; text-transform: uppercase; color: #94a3b8; font-size: 0.85rem; letter-spacing: 1.5px; margin-bottom: 2rem; text-align: center;">M&eacute;todo de Lectura QR</h5>
                    
                    <div class="option-grid">
                        <div class="choice-card" onclick="initScanner()">
                            <i class='bx bx-camera'></i>
                            <span>USAR C&Aacute;MARA</span>
                        </div>
                        <div class="choice-card" onclick="document.getElementById('qr-input-file').click()">
                            <i class='bx bx-image-add'></i>
                            <span>GALERIA / QR</span>
                        </div>
                    </div>

                    <div class="premium-box" style="margin-top: 1rem; padding: 1.8rem;">
                        <span style="display: block; text-align: center; color: #94a3b8; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; margin-bottom: 1rem; letter-spacing: 1px;">B&uacute;squeda por Documento</span>
                        
                        <div class="search-group-elite" style="margin: 0.5rem 0;">
                            <i class='bx bx-id-card icon-prefix'></i>
                            <input type="tel" id="manual-dni" placeholder="Ingresa DNI" maxlength="8">
                            <button class="btn-search" onclick="buscarPorDni()">
                                <i class='bx bx-search-alt'></i>
                            </button>
                        </div>
                        <input type="file" id="qr-input-file" accept="image/*" style="display: none;" onchange="onFileChange(event)">
                    </div>
                </div>

                <!-- [PANTALLA 2] — SCANNER -->
                <div id="screen-scan" class="v-screen">
                    <div class="premium-box" style="padding: 1.5rem; text-align: center;">
                        <div id="reader"></div>
                        <div style="margin-top: 1.5rem; padding-bottom: 1rem;">
                            <h4 style="font-weight: 800; margin-bottom: 0.5rem;">Lectura de Código</h4>
                            <p style="color: #64748b; font-size: 0.9rem;">Apunte su cámara hacia el QR del cliente</p>
                        </div>
                    </div>
                    <button class="btn-secondary-premium" onclick="stopScanner()" style="width: 100%; height: 65px; border-radius: 20px;">
                        <i class='bx bx-x'></i> Cancelar Escaneo
                    </button>
                </div>

                <!-- [PANTALLA 3] — REGISTRO ACTIVADO -->
                <div id="screen-main" class="v-screen">
                    <div class="customer-card">
                        <div class="avatar"><i class='bx bx-user-circle'></i></div>
                        <div>
                            <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; opacity: 0.6; letter-spacing: 1px;">Cliente Seleccionado</span>
                            <h4 id="res-name" style="margin: 3px 0; font-size: 1.3rem; font-weight: 800;">-</h4>
                            <div style="font-size: 0.9rem; color: var(--p-orange); font-weight: 600;">
                                <i class='bx bxs-phone-call'></i> <span id="res-phone">-</span>
                            </div>
                        </div>
                        <input type="hidden" id="client-id">
                    </div>

                    <div class="premium-box">
                        <div class="form-group" style="margin-bottom: 1.8rem;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 0.8rem; letter-spacing: 1px;">Tipo de Recarga</label>
                            <select id="main-op-type" class="form-select-elite" onchange="updateSubtotal()">
                                <?php foreach ($operaciones as $op): ?>
                                    <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?> pts)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.2rem;">
                            <div class="form-group">
                                <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 0.8rem; letter-spacing: 1px;">Cantidad</label>
                                <select id="main-op-qty" class="form-select-elite" onchange="updateSubtotal()">
                                    <?php for($i=1;$i<=10;$i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 0.8rem; letter-spacing: 1px;">Suma Parcial</label>
                                <div id="main-op-unit" style="height: 60px; display: flex; align-items: center; justify-content: center; background: #fffcf0; border-radius: 18px; border: 2px solid #fff3e0; font-weight: 950; color: var(--p-orange); font-size: 1.3rem;">-</div>
                            </div>
                        </div>

                        <button onclick="addOperation()" style="margin-top: 2rem; width: 100%; height: 60px; background: #f1f5f9; border: none; border-radius: 18px; color: var(--dark-blue); font-weight: 800; cursor: pointer; transition:0.3s; display: flex; align-items:center; justify-content:center; gap:0.5rem;">
                            <i class='bx bx-list-plus' style="font-size: 1.5rem;"></i> Agregar a la Lista
                        </button>
                    </div>

                    <div style="margin-top: 3rem;">
                        <h5 style="font-weight: 800; text-transform: uppercase; color: #94a3b8; font-size: 0.85rem; letter-spacing: 1.5px; margin-bottom: 1.5rem;">Resumen de Operaci&oacute;n</h5>
                        
                        <div id="ops-container" style="min-height: 80px;">
                            <!-- Dinámico -->
                        </div>

                        <div class="total-plate">
                            <div>
                                <span class="lbl">Cr&eacute;dito Total</span>
                                <div class="val" id="main-total-pts">0</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 800; font-size: 1.1rem; color: var(--p-orange);">PUNTOS</div>
                                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Suma final</div>
                            </div>
                        </div>

                        <div style="margin-top: 2.5rem; display: flex; flex-direction: column; gap: 1rem;">
                            <button id="save-all-btn" class="btn-confirm-elite" onclick="saveAll()">
                                <i class='bx bx-cloud-upload' ></i> Confirmar y Agregar
                            </button>
                            <button class="btn-secondary-premium" onclick="location.reload()" style="height: 55px; justify-content: center; font-weight: 700; color: #94a3b8;">
                                <i class='bx bx-undo'></i> Cancelar Todo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- [PANTALLA 4] — ERROR -->
                <div id="screen-error" class="v-screen">
                    <div class="premium-box" style="text-align: center; padding: 4rem 2rem;">
                        <div style="width: 110px; height: 110px; background: #fffcf0; color: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2.5rem; font-size: 4rem;">
                            <i class='bx bx-ghost'></i>
                        </div>
                        <h2 style="font-weight: 900; margin: 0 0 1rem; color: var(--dark-blue);">¡Uy! No lo encontramos</h2>
                        <p style="color: #64748b; font-size: 1rem; line-height: 1.6; max-width: 320px; margin: 0 auto 3rem;">El c&oacute;digo o DNI no est&aacute; en nuestra base de datos. &iquest;Deseas registrar al cliente?</p>
                        
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-confirm-elite" style="text-decoration: none; background: var(--dark-blue); font-size: 1.1rem;">
                                <i class='bx bx-user-plus'></i> Nuevo Registro
                            </a>
                            <button onclick="location.reload()" style="background: none; border: none; color: #94a3b8; font-weight: 800; cursor: pointer; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Volver al inicio</button>
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
            
            const titles = {
                'screen-start': 'Gesti&oacute;n de Puntos',
                'screen-scan': 'Lector en l&iacute;nea',
                'screen-main': 'Procesando Suma',
                'screen-error': '&iexcl;Casi lo tenemos!'
            };
            document.querySelector('.page-title').innerHTML = titles[id] || 'Suma de Puntos';
        }

        async function initScanner() {
            showScreen('screen-scan');
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 20, qrbox: { width: 280, height: 280 }, aspectRatio: 1.0 };
            try {
                await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'C&aacute;mara no activa' });
                showScreen('screen-start');
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
                Swal.fire({ icon: 'warning', title: 'DNI Inv&aacute;lido' });
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
                    document.getElementById('res-phone').innerText = data.cliente.celular;
                    document.getElementById('client-id').value = data.cliente.id;
                    showScreen('screen-main');
                    updateSubtotal();
                } else {
                    showScreen('screen-error');
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error' });
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
                container.innerHTML = '<div style="text-align: center; color: #cbd5e1; padding: 2rem; font-weight: 600;">Utilice el panel anterior para añadir puntos</div>';
                document.getElementById('main-total-pts').innerText = '0';
                document.getElementById('save-all-btn').disabled = true;
                return;
            }
            let html = '';
            let total = 0;
            operations.forEach((op, i) => {
                total += op.subtotal;
                html += `
                    <div class="op-pill">
                        <div>
                            <b>${op.name} x${op.qty}</b>
                            <span style="font-size: 0.8rem; color: #94a3b8;">${op.unit} pts c/u</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="font-weight: 950; color: var(--p-orange); font-size: 1.25rem;">+${op.subtotal}</div>
                            <button onclick="removeOperation(${i})" style="background:none; border:none; color:#cbd5e1; cursor:pointer; font-size: 1.5rem;">
                                <i class='bx bx-trash'></i>
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
            btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> GUARDANDO...";
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
                        icon: 'success', title: '&iexcl;Puntos Sumados!', text: 'El saldo del cliente ha sido actualizado.',
                        timer: 2000, showConfirmButton: false, background: '#ffffff', color: '#0f172a'
                    }).then(() => window.location.href = baseUrl + 'panel');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    running = false;
                    btn.innerHTML = "<i class='bx bx-cloud-upload' ></i> Confirmar y Agregar";
                    btn.disabled = false;
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Servidor no responde' });
                running = false;
                btn.innerHTML = "<i class='bx bx-cloud-upload' ></i> Confirmar y Agregar";
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
