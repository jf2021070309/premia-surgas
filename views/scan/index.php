<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sumar Puntos — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <style>
        :root { --primary-brand: #ff6600; }
        .scan-container { max-width: 600px; margin: 0 auto; }
        .v-screen { display: none; animation: fadeIn 0.3s ease; }
        .v-screen.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .scan-card { background: white; border-radius: 24px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; margin-bottom: 2rem; }
        .btn-scan-main { background: #111827; color: white; width: 100%; padding: 1.25rem; border-radius: 16px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.75rem; font-size: 1.1rem; transition: 0.3s; }
        .btn-scan-main:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        
        .client-preview { background: #f8fafc; border-radius: 16px; padding: 1.25rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem; }
        .client-preview .avatar-circle { width: 48px; height: 48px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: var(--primary-brand); border: 2px solid #f1f5f9; }
        .client-preview .info b { display: block; font-size: 1rem; color: #1e293b; }
        .client-preview .info span { font-size: 0.85rem; color: #64748b; }

        .op-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #fff; border-radius: 12px; margin-bottom: 0.75rem; border: 1.5px solid #f1f5f9; transition: 0.2s; }
        .op-item:hover { border-color: #e2e8f0; background: #fafafa; }
        .op-info b { display: block; color: #1e293b; font-size: 0.95rem; }
        .op-info span { font-size: 0.8rem; color: #94a3b8; }
        .op-pts { font-weight: 800; color: #16a34a; font-size: 1rem; }

        .total-banner { background: #111827; color: white; padding: 1.5rem; border-radius: 20px; text-align: center; margin: 2rem 0; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .total-banner span { font-size: 0.8rem; opacity: 0.7; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .total-banner b { font-size: 2.5rem; display: block; line-height: 1; margin-top: 0.5rem; color: var(--primary-brand); }

        #reader { width: 100%; border-radius: 20px; overflow: hidden; border: 4px solid #f1f5f9; }
        .form-select-premium { width: 100%; padding: 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 12px; outline: none; font-family: inherit; font-size: 1rem; background: #fff; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; }
        .form-input-premium { width: 100%; padding: 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 12px; outline: none; font-family: inherit; font-size: 1rem; }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Suma de Puntos';
            $pageSubtitle = 'Busca al cliente o escanea su QR para asignar puntos';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="scan-container">

                <!-- [PANTALLA 1] — INICIO / BUSQUEDA -->
                <div id="screen-start" class="v-screen active">
                    <div class="scan-card">
                        <button class="btn-scan-main" onclick="initScanner()">
                            <i class='bx bx-qr-scan'></i> Escanear con Cámara
                        </button>
                        
                        <div style="margin: 2rem 0; display: flex; align-items: center; gap: 1rem;">
                            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 600;">O BUSCA POR DNI</span>
                            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                        </div>

                        <div style="display: flex; gap: 0.75rem;">
                            <input type="tel" id="manual-dni" class="form-input-premium" placeholder="Ingresa DNI (8 dígitos)" maxlength="8" style="font-weight: 700; text-align: center; letter-spacing: 2px;">
                            <button class="btn-primary-premium" onclick="buscarPorDni()" style="width: auto; height: 50px;">
                                <i class='bx bx-search-alt'></i> Buscar
                            </button>
                        </div>
                        
                        <div style="margin-top: 1.5rem; text-align: center;">
                            <input type="file" id="qr-input-file" accept="image/*" style="display: none;" onchange="onFileChange(event)">
                            <button onclick="document.getElementById('qr-input-file').click()" style="background: none; border: none; color: #64748b; font-size: 0.85rem; cursor: pointer; text-decoration: underline; font-weight: 500;">
                                <i class='bx bx-image-add'></i> Subir foto de QR
                            </button>
                        </div>
                    </div>
                </div>

                <!-- [PANTALLA 2] — ESCANEANDO -->
                <div id="screen-scan" class="v-screen">
                    <div class="scan-card">
                        <div id="reader"></div>
                        <div style="text-align: center; color: #64748b; margin-top: 1.5rem; font-size: 0.9rem;">
                            <i class='bx bx-loader-circle bx-spin'></i> Apunte al código QR del cliente
                        </div>
                    </div>
                    <button class="btn-secondary-premium" onclick="stopScanner()" style="width: 100%;">Cancelar Escaneo</button>
                </div>

                <!-- [PANTALLA 3] — PROCESO DE SUMA -->
                <div id="screen-main" class="v-screen">
                    <div class="scan-card">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                            <span style="font-weight: 800; font-size: 0.8rem; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Cliente Seleccionado</span>
                            <button onclick="initScanner()" style="background: #f1f5f9; border: none; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer; color: #475569;">
                                <i class='bx bx-refresh'></i> Cambiar
                            </button>
                        </div>

                        <div class="client-preview">
                            <div class="avatar-circle"><i class='bx bx-user'></i></div>
                            <div class="info">
                                <b id="res-name">-</b>
                                <span id="res-phone">-</span>
                                <input type="hidden" id="client-id">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase;">Tipo de Operación</label>
                            <select id="main-op-type" class="form-select-premium" onchange="updateSubtotal()">
                                <?php foreach ($operaciones as $op): ?>
                                    <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?> pts)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase;">Cantidad</label>
                                <select id="main-op-qty" class="form-select-premium" onchange="updateSubtotal()">
                                    <?php for($i=1;$i<=10;$i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase;">Puntos Unidad</label>
                                <div id="main-op-unit" style="padding: 0.85rem; font-weight: 800; font-size: 1.1rem; color: #1e293b; background: #f8fafc; border-radius: 12px; text-align: center;">-</div>
                            </div>
                        </div>

                        <button class="btn-primary-premium" onclick="addOperation()" style="margin-top: 1.5rem; width: 100%; justify-content: center; height: 55px; background: #16a34a; border-color: #16a34a;">
                            <i class='bx bx-plus-circle'></i> Agregar a la Lista
                        </button>
                    </div>

                    <div style="margin-bottom: 3rem;">
                        <h4 style="font-weight: 800; font-size: 0.85rem; color: #64748b; text-transform: uppercase; margin-bottom: 1rem;">Resumen de Puntos</h4>
                        <div id="ops-container">
                            <!-- Dinámico -->
                        </div>

                        <div class="total-banner">
                            <span>Total a Sumar</span>
                            <b id="main-total-pts">0</b>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <button id="save-all-btn" class="btn-primary-premium" onclick="saveAll()" style="justify-content: center; font-size: 1.1rem;">
                                <i class='bx bx-cloud-upload'></i> Guardar Todo
                            </button>
                            <button class="btn-secondary-premium" onclick="location.reload()" style="justify-content: center;">Cancelar</button>
                        </div>
                    </div>
                </div>

                <!-- [PANTALLA 4] — NO ENCONTRADO -->
                <div id="screen-error" class="v-screen">
                    <div class="scan-card" style="text-align: center;">
                        <div style="width: 80px; height: 80px; background: #fef2f2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 3rem;">
                            <i class='bx bx-user-x'></i>
                        </div>
                        <h2 style="font-weight: 800; color: #1e293b; margin: 0 0 0.5rem;">Cliente no encontrado</h2>
                        <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5;">El código o DNI ingresado no coincide con ningún cliente registrado.</p>
                        
                        <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem;">
                            <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-primary-premium" style="justify-content: center;">
                                <i class='bx bx-user-plus'></i> Registrar Nuevo Cliente
                            </a>
                            <button class="btn-secondary-premium" onclick="initScanner()" style="justify-content: center;">
                                <i class='bx bx-qr-scan'></i> Intentar de nuevo
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
                'screen-start': 'Busca al cliente o escanea su QR para asignar puntos',
                'screen-scan': 'Apunte la cámara al código QR',
                'screen-main': 'Calculando puntos para el cliente',
                'screen-error': 'Lo sentimos, no encontramos el registro'
            };
            document.querySelector('.page-subtitle').innerText = subtitles[id] || 'Panel de Gestión';
        }

        async function initScanner() {
            showScreen('screen-scan');
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            const config = { fps: 15, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
            try {
                await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Cámara no disponible', text: 'Permitir acceso a la cámara para escanear.' });
                showScreen('screen-start');
            }
        }

        async function onFileChange(event) {
            if (event.target.files.length === 0) return;
            const imageFile = event.target.files[0];
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");

            Swal.fire({ title: 'Procesando QR...', didOpen: () => { Swal.showLoading(); } });

            try {
                const decodedText = await html5QrCode.scanFile(imageFile, false);
                Swal.close();
                onScanSuccess(decodedText);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error de Lectura', text: 'No se detectó un código QR válido en la imagen.' });
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
                Swal.fire({ icon: 'warning', title: 'DNI Inválido', text: 'El DNI debe tener 8 dígitos.' });
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
                Swal.fire({ icon: 'error', title: 'Error de Conexión' });
                showScreen('screen-start');
            }
        }

        function updateSubtotal() {
            const unit = parseInt(document.getElementById('main-op-type').value);
            document.getElementById('main-op-unit').innerText = unit + ' pts';
        }

        function addOperation() {
            const select = document.getElementById('main-op-type');
            const typeName = select.options[select.selectedIndex].text.split(' (')[0];
            const unit = parseInt(select.value);
            const qty = parseInt(document.getElementById('main-op-qty').value) || 1;
            const subtotal = unit * qty;
            operations.push({ name: typeName, unit, qty, subtotal });
            renderOperations();
            document.getElementById('main-op-qty').value = 1;
        }

        function removeOperation(index) {
            operations.splice(index, 1);
            renderOperations();
        }

        function renderOperations() {
            const container = document.getElementById('ops-container');
            if (operations.length === 0) {
                container.innerHTML = '<div style="text-align: center; color: #aaa; padding: 2rem; background: #fff; border-radius: 12px; border: 1px dashed #e2e8f0;">( Lista vacía )</div>';
                document.getElementById('main-total-pts').innerText = '0';
                document.getElementById('save-all-btn').disabled = true;
                return;
            }
            let html = '';
            let total = 0;
            operations.forEach((op, i) => {
                total += op.subtotal;
                html += `
                    <div class="op-item">
                        <div class="op-info">
                            <b>${op.name} x${op.qty}</b>
                            <span>${op.unit} pts c/u</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="op-pts">+${op.subtotal}</div>
                            <button onclick="removeOperation(${i})" style="background:#fef2f2; border:none; color:#ef4444; width:32px; height:32px; border-radius:50%; cursor:pointer;">
                                <i class='bx bx-x'></i>
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
            if (total <= 0) return Swal.fire({ icon: 'warning', title: 'Lista vacía' });

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
                    Swal.fire({ icon: 'success', title: '¡Puntos Sumados!', text: 'El saldo del cliente ha sido actualizado.', timer: 2000, showConfirmButton: false })
                    .then(() => window.location.href = baseUrl + 'panel');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    running = false;
                    btn.innerHTML = "<i class='bx bx-cloud-upload'></i> Guardar Todo";
                    btn.disabled = false;
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Fallo de Servidor' });
                running = false;
                btn.innerHTML = "<i class='bx bx-cloud-upload'></i> Guardar Todo";
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
