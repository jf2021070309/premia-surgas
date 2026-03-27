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
        
        /* Layout */
        .scan-container { max-width: 650px; margin: 4rem auto 0; padding-bottom: 5rem; }
        .v-screen { display: none; }
        .v-screen.active { display: block; animation: smoothReveal 0.4s ease-out; }
        @keyframes smoothReveal { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Card Elite Style from Image */
        .elite-form-card { 
            background: white; border-radius: 20px; 
            border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.02);
            overflow: hidden;
        }
        .elite-card-header { 
            background: #f8fafc; padding: 1.5rem 2rem; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .elite-header-icon { 
            width: 36px; height: 36px; background: #fdf2f2; color: var(--p-wine); 
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; border: 1px solid #fee2e2;
        }
        .elite-card-header h3 { margin: 0; font-size: 0.95rem; font-weight: 800; color: #1e293b; letter-spacing: -0.01em; }

        .elite-card-body { padding: 3rem 3rem 2rem; }

        /* Small labels */
        .elite-label { display: block; font-size: 0.75rem; font-weight: 800; color: var(--p-wine); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.9rem; }

        /* Interactive Choice Blocks */
        .choice-row { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem; }
        .choice-btn { 
            background: white; border: 1.5px solid #f1f5f9; border-radius: 16px; 
            padding: 2rem 1.5rem; cursor: pointer; transition: 0.3s;
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.8rem;
        }
        .choice-btn:hover { border-color: var(--p-wine); transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.03); }
        .choice-btn i { font-size: 2.2rem; color: #1e293b; transition: 0.3s; }
        .choice-btn:hover i { color: var(--p-wine); }
        .choice-btn span { font-size: 0.8rem; font-weight: 800; color: #475569; letter-spacing: 0.2px; }

        /* Premium Input Area */
        .elite-input-wrapper { position: relative; margin-bottom: 2.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .elite-input-wrapper i.icon-dni { position: absolute; left: 1.25rem; color: #94a3b8; font-size: 1.5rem; pointer-events: none; }
        .elite-input { 
            flex: 1; height: 55px; padding: 0 4rem 0 3rem; border: 1.5px solid #f1f5f9; border-radius: 12px;
            font-family: inherit; font-size: 0.9rem; font-weight: 600; color: #1e293b; outline: none; transition: 0.3s;
            background: #fff;
        }
        .elite-input::placeholder { color: #cbd5e1; font-weight: 500; }
        .elite-input:focus { border-color: var(--p-wine); background: white; box-shadow: 0 0 0 4px rgba(128,0,0,0.05); }

        .btn-search-icon { 
            position: absolute; right: 4px; width: 42px; height: 42px; border-radius: 10px; 
            background: var(--p-wine); color: white; border: none; display: flex; 
            align-items: center; justify-content: center; font-size: 1.3rem; 
            cursor: pointer; transition: 0.3s;
        }
        .btn-search-icon:hover { transform: scale(1.05); background: #600000; }

        /* Final Button (Black) */
        .btn-elite-black { 
            display: flex; align-items: center; justify-content: center;
            background: #000; color: white; border: none; height: 50px; width: 100%;
            border-radius: 50px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase;
            letter-spacing: 1.5px; cursor: pointer; transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .btn-elite-black:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(0,0,0,0.25); background: #000; }

        /* Result formatting */
        .elite-customer-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.25rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem; }
        .customer-avatar { width: 42px; height: 42px; background: white; border: 1.5px solid #e2e8f0; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: var(--p-wine); }

        .form-select-elite { height: 50px; width: 100%; border-radius: 12px; border: 1.5px solid #f1f5f9; padding: 0 1rem; font-weight: 700; font-size: 0.85rem; color: #1e293b; outline: none; appearance: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 1rem center / 1.25rem; }

        .op-row { padding: 1rem; background: #fff; border-radius: 12px; border: 1px solid #f1f5f9; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; }
        .total-display { margin-top: 2rem; padding: 1.5rem; background: #fdfcfa; border-radius: 16px; border: 2px dashed #ffedd5; display: flex; justify-content: space-between; align-items: center; }

        .elite-subtotal-box { height: 50px; background: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #1e293b; font-size: 0.85rem; border: 1.5px solid #f1f5f9; }

        #reader { width: 100%; border-radius: 16px; overflow: hidden; }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión de Puntos';
            $pageSubtitle = '¡Puntaje asignado, cliente feliz!';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="scan-container">

                <!-- [PANTALLA 1] — INICIO -->
                <div id="screen-start" class="v-screen active">
                    <div class="elite-form-card">
                        <div class="elite-card-header">
                            <div class="elite-header-icon"><i class='bx bx-qr-scan'></i></div>
                            <h3>REGISTRAR OPERACI&Oacute;N</h3>
                        </div>
                        
                        <div class="elite-card-body">
                            <label class="elite-label">M&eacute;todo de Lectura QR</label>
                            <div class="choice-row">
                                <div class="choice-btn" onclick="initScanner()">
                                    <i class='bx bx-camera'></i>
                                    <span>USAR C&Aacute;MARA</span>
                                </div>
                                <div class="choice-btn" onclick="document.getElementById('qr-input-file').click()">
                                    <i class='bx bx-image-add'></i>
                                    <span>GALERIA / QR</span>
                                </div>
                            </div>

                            <label class="elite-label">B&uacute;squeda Manual (DNI)</label>
                            <div class="elite-input-wrapper">
                                <i class='bx bx-id-card icon-dni'></i>
                                <input type="tel" id="manual-dni" class="elite-input" placeholder="Ej. 12345678" maxlength="8">
                                <button class="btn-search-icon" onclick="buscarPorDni()" title="Buscar cliente">
                                    <i class='bx bx-search'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- [PANTALLA 2] — SCANNER -->
                <div id="screen-scan" class="v-screen">
                    <div class="elite-form-card">
                        <div class="elite-card-header">
                            <div class="elite-header-icon"><i class='bx bx-camera'></i></div>
                            <h3>LECTOR QR EN l&Iacute;NEA</h3>
                        </div>
                        <div class="elite-card-body" style="padding: 1.5rem;">
                            <div id="reader"></div>
                            <div style="text-align: center; margin-top: 1rem;">
                                <p style="font-size: 0.8rem; color: #64748b; font-weight: 500;">Apunte su c&aacute;mara hacia el QR del cliente</p>
                            </div>
                        </div>
                        <div style="padding-bottom: 2rem; text-align: center;">
                            <button class="btn-elite-black" onclick="stopScanner()" style="background: #f1f5f9; color: #475569;">Cancelar</button>
                        </div>
                    </div>
                </div>

                <!-- [PANTALLA 3] — REGISTRO ACTIVADO -->
                <div id="screen-main" class="v-screen">
                    <div class="elite-form-card">
                        <div class="elite-card-header">
                            <div class="elite-header-icon"><i class='bx bx-user-circle'></i></div>
                            <h3>ORDEN DE SUMA DE PUNTOS</h3>
                        </div>

                        <div class="elite-card-body">
                            <label class="elite-label">Cliente Seleccionado</label>
                            <div class="elite-customer-box">
                                <div class="customer-avatar"><i class='bx bx-user'></i></div>
                                <div>
                                    <b id="res-name" style="display: block; font-size: 0.95rem; color: #1e293b;">-</b>
                                    <span id="res-phone" style="font-size: 0.8rem; color: #64748b;">-</span>
                                </div>
                                <input type="hidden" id="client-id">
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                                <div class="form-group">
                                    <label class="elite-label">Tipo de Recarga</label>
                                    <select id="main-op-type" class="form-select-elite" onchange="updateSubtotal()">
                                        <?php foreach ($operaciones as $op): ?>
                                            <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?> pts)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="elite-label">Subtotal Puntos</label>
                                    <div id="main-op-unit" class="elite-subtotal-box">-</div>
                                </div>
                            </div>

                            <div class="form-group" style="margin-top: 1.25rem; margin-bottom: 2rem;">
                                <label class="elite-label">Cantidad</label>
                                <select id="main-op-qty" class="form-select-elite" onchange="updateSubtotal()">
                                    <?php for($i=1;$i<=10;$i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <button class="btn-elite-black" onclick="addOperation()">
                                A&Ntilde;ADIR A LISTA
                            </button>

                            <div style="margin-top: 3rem;">
                                <label class="elite-label">Resumen de Operaci&oacute;n</label>
                                <div id="ops-container" style="min-height: 50px;">
                                    <!-- Dinámico -->
                                </div>

                                <div class="total-display">
                                    <div>
                                        <span class="elite-label" style="margin: 0; opacity: 0.6;">Cr&eacute;dito Total</span>
                                        <div id="main-total-pts" style="font-size: 2.2rem; font-weight: 950; color: #1e293b; line-height: 1; margin-top: 0.2rem;">0</div>
                                    </div>
                                    <div style="text-align: right; font-weight: 800; color: var(--p-wine); font-size: 0.8rem; letter-spacing: 1px;">PUNTOS</div>
                                </div>
                            </div>
                        </div>

                        <div style="padding: 1.5rem 2.5rem 3rem; text-align: center;">
                            <button id="save-all-btn" class="btn-elite-black" onclick="saveAll()" style="width: 100%;">REGISTRAR</button>
                            <button onclick="location.reload()" style="background: none; border: none; font-size: 0.75rem; font-weight: 800; color: #94a3b8; margin-top: 1.25rem; cursor: pointer; text-transform: uppercase;">Cancelar todo</button>
                        </div>
                    </div>
                </div>

                <!-- [PANTALLA 4] — NO ENCONTRADO -->
                <div id="screen-error" class="v-screen">
                    <div class="elite-form-card">
                        <div class="elite-card-header">
                            <div class="elite-header-icon" style="background:#fef2f2; color:#ef4444; border-color:#fee2e2;"><i class='bx bx-user-x' ></i></div>
                            <h3>CLIENTE NO REGISTRADO</h3>
                        </div>
                        <div class="elite-card-body" style="text-align: center; padding: 4rem 3rem;">
                            <h2 style="font-weight: 900; color: #1e293b; font-size: 1.25rem; margin-bottom: 0.75rem;">No se encontr&oacute; al cliente</h2>
                            <p style="font-size: 0.85rem; color: #64748b; line-height: 1.6; margin-bottom: 2.5rem;">El c&oacute;digo o DNI no coincide con ningún registro. &iquest;Deseas crearlo ahora?</p>
                            
                            <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-elite-black" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">Nuevo Cliente</a>
                            <button onclick="location.reload()" style="background: none; border: none; font-size: 0.75rem; font-weight: 800; color: #94a3b8; margin-top: 1.5rem; cursor: pointer; text-transform: uppercase;">Volver al inicio</button>
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
                'screen-start': '&iexcl;Puntaje asignado, cliente feliz!',
                'screen-scan': 'Lectura de C&oacute;digo QR',
                'screen-main': 'Calculando puntos ganados',
                'screen-error': 'Lo sentimos, no encontramos el registro'
            };
            document.querySelector('.page-subtitle').innerHTML = subtitles[id] || 'Panel de Gesti&oacute;n';
        }

        async function initScanner() {
            showScreen('screen-scan');
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 20, qrbox: { width: 280, height: 280 }, aspectRatio: 1.0 };
            try {
                await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'C&aacute;mara no disponible' });
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
                container.innerHTML = '<div style="text-align: center; color: #cbd5e1; padding: 1rem; font-weight: 500; font-size: 0.8rem;">Lista vac&iacute;a</div>';
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
                            <div style="font-weight: 800; color: var(--p-orange);">+${op.subtotal}</div>
                            <i class='bx bx-x' onclick="removeOperation(${i})" style="cursor:pointer; color:#94a3b8; font-size:1.1rem;"></i>
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
