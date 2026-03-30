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
        
        .scan-container { max-width: 1200px; margin: 1.5rem auto; padding: 0 1.5rem; display: flex; flex-direction: column; gap: 1.5rem; }
        
        /* Search Bar (Top Card) */
        .search-navbar { 
            background: white; border-radius: 18px; border: 1px solid #e2e8f0; padding: 1.25rem 2rem;
            display: flex; align-items: center; justify-content: space-between; gap: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        }
        .search-navbar-left { display: flex; align-items: center; gap: 1rem; flex-shrink: 0; }
        .search-navbar-center { display: flex; gap: 1rem; flex: 1; justify-content: center; }
        .search-navbar-right { width: 300px; }

        /* Operation Card (Main) */
        .elite-form-card { 
            background: white; border-radius: 20px; 
            border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.02);
            overflow: hidden;
        }
        .elite-card-header { 
            background: #fff; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .elite-header-icon { 
            width: 32px; height: 32px; background: #fffcfc; color: var(--p-wine); 
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; border: 1px solid #fee2e2;
        }
        .elite-card-header h3 { margin: 0; font-size: 0.85rem; font-weight: 850; color: #1e293b; letter-spacing: -0.2px; text-transform: uppercase; }

        .elite-card-content { display: flex; flex-direction: row; min-height: 400px; }
        .elite-card-main { flex: 1.4; padding: 2.5rem; border-right: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 2rem; }
        .elite-card-side { flex: 1; padding: 2.5rem; background: #fafbfc; display: flex; flex-direction: column; }

        .elite-label { display: block; font-size: 0.65rem; font-weight: 950; color: var(--p-wine); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.8rem; }

        /* Navbar Specific Btns */
        .nav-choice-btn { 
            background: #f8fafc; border: 1.5px solid #f1f5f9; border-radius: 12px; 
            padding: 0.6rem 1.25rem; cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; gap: 0.75rem; 
            font-size: 0.75rem; font-weight: 800; color: #1e293b;
        }
        .nav-choice-btn:hover { border-color: var(--p-wine); background: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(128,0,0,0.05); }
        .nav-choice-btn i { font-size: 1.2rem; color: #b91c1c; }

        .nav-input-wrapper { position: relative; display: flex; align-items: center; }
        .nav-input { 
            width: 100%; height: 44px; padding: 0 3rem 0 1.2rem; border: 1.5px solid #f1f5f9; border-radius: 12px;
            font-family: inherit; font-size: 0.85rem; font-weight: 600; color: #1e293b; outline: none; transition: 0.3s; background: #fdfdfd;
        }
        .nav-input:focus { border-color: var(--p-wine); background: #fff; }
        .nav-search-icon { 
            position: absolute; right: 5px; width: 34px; height: 34px; border-radius: 8px; 
            background: var(--p-wine); color: white; border: none; display: flex; 
            align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer; transition: 0.3s;
        }

        /* Forms in Card */
        .btn-elite-black { 
            display: flex; align-items: center; justify-content: center;
            background: #000; color: white; border: none; height: 55px; width: 100%;
            border-radius: 50px; font-weight: 900; font-size: 0.85rem; text-transform: uppercase;
            letter-spacing: 1.2px; cursor: pointer; transition: 0.3s; box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        .btn-elite-black:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(0,0,0,0.25); }
        .btn-elite-black:disabled { opacity: 0.5; filter: grayscale(1); transform: none; }

        .elite-customer-box { background: #fff; border: 1.5px solid #f1f5f9; border-radius: 16px; padding: 1.25rem; display: flex; align-items: center; gap: 1.25rem; }
        .customer-avatar { width: 48px; height: 48px; background: #fdf2f2; border: 1px solid #fee2e2; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: var(--p-wine); }

        .form-select-elite { height: 52px; width: 100%; border-radius: 14px; border: 1.5px solid #f1f5f9; padding: 0 1.25rem; font-weight: 700; font-size: 0.85rem; color: #1e293b; outline: none; appearance: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 1.25rem center / 1.1rem; }

        .op-row { padding: 1.1rem; background: #fff; border-radius: 14px; border: 1px solid #f1f5f9; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; }
        .total-display { margin-top: auto; padding: 2rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }

        #ops-container { height: 260px; overflow-y: auto; padding-right: 5px; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 2px; }
        #ops-container::-webkit-scrollbar { width: 4px; }
        #ops-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        .active-panel { animation: slideUp 0.6s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .placeholder-panel { opacity: 0.5; pointer-events: none; }

        @media (max-width: 900px) {
            .search-navbar { flex-direction: column; gap: 1.5rem; }
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
            $pageSubtitle = 'Busca al cliente y suma su puntaje';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="scan-container">
            <!-- NAVBAR DE BÚSQUEDA -->
            <div class="search-navbar">
                <div class="search-navbar-left">
                    <div class="elite-header-icon"><i class='bx bx-qr-scan'></i></div>
                    <span class="elite-label" style="margin:0; font-size: 0.7rem;">M&Eacute;TODO DE B&Uacute;SQUEDA</span>
                </div>
                
                <div class="search-navbar-center">
                    <div class="nav-choice-btn" onclick="initScanner()">
                        <i class='bx bx-camera'></i>
                        <span>C&Aacute;MARA</span>
                    </div>
                    <input type="file" id="qr-input-file" accept="image/*" style="display: none;" onchange="onFileChange(event)">
                    <div class="nav-choice-btn" onclick="document.getElementById('qr-input-file').click()">
                        <i class='bx bx-image-add'></i>
                        <span>GALER&Iacute;A</span>
                    </div>
                </div>

                <div class="search-navbar-right">
                    <div class="nav-input-wrapper">
                        <input type="tel" id="manual-dni" class="nav-input" placeholder="Buscar por DNI..." maxlength="8">
                        <button class="nav-search-icon" onclick="buscarPorDni()"><i class='bx bx-search'></i></button>
                    </div>
                </div>
            </div>

            <!-- LECTOR QR OVERLAY (Solo se muestra activo) -->
            <div id="qr-reader-container" style="display: none; width: 100%;">
                <div class="elite-form-card">
                    <div class="elite-card-header">
                        <h3>ESCANEANDO C&Oacute;DIGO QR</h3>
                        <button onclick="stopScanner()" style="background: none; border: none; color: #94a3b8; font-size: 1.5rem; cursor: pointer;"><i class='bx bx-x'></i></button>
                    </div>
                    <div class="elite-card-body" style="padding: 1rem; display: flex; justify-items: center; flex-direction: column;">
                        <div id="reader" style="width: 100%; border-radius: 15px; overflow: hidden;"></div>
                    </div>
                </div>
            </div>

            <!-- ÁREA PRINCIPAL -->
            <div id="scan-right-panel" class="scan-right-panel placeholder-panel">
                <div id="right-panel-content">
                    <!-- Loaded via tpl-main-form -->
                </div>
            </div>

            <template id="tpl-main-form">
                <div class="elite-form-card">
                    <div class="elite-card-header">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="elite-header-icon"><i class='bx bx-user-circle'></i></div>
                            <h3>ORDEN DE SUMA DE PUNTOS</h3>
                        </div>
                        <div style="text-align: right;">
                            <span id="date-now" style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;"><?= date('d M, Y') ?></span>
                        </div>
                    </div>

                    <div class="elite-card-content">
                        <!-- COLUMNA IZQUIERDA -->
                        <div class="elite-card-main">
                            <div>
                                <label class="elite-label">Cliente Seleccionado</label>
                                <div class="elite-customer-box">
                                    <div class="customer-avatar"><i class='bx bx-user'></i></div>
                                    <div>
                                        <b id="res-name" style="display: block; font-size: 0.95rem; color: #1e293b;">- - -</b>
                                        <span id="res-phone" style="font-size: 0.8rem; color: #64748b; font-weight: 500;">Esperando identificaci&oacute;n...</span>
                                    </div>
                                    <input type="hidden" id="client-id">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                                <div class="form-group">
                                    <label class="elite-label">Tipo de Servicio / Recarga</label>
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

                            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: end;">
                                <div class="form-group">
                                    <label class="elite-label">Subtotal de Operaci&oacute;n</label>
                                    <div id="main-op-unit" class="elite-subtotal-box">0 PTS</div>
                                </div>
                                <button class="btn-elite-black" onclick="addOperation()">A&Ntilde;ADIR A LA LISTA</button>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA -->
                        <div class="elite-card-side">
                            <label class="elite-label">Detalle del Registro</label>
                            <div id="ops-container">
                                <div style="text-align: center; color: #cbd5e1; padding: 4rem 1rem;">
                                    <i class='bx bx-list-ol' style="font-size: 2.5rem; opacity: 0.2; display: block; margin-bottom: 0.5rem;"></i>
                                    <span style="font-size: 0.8rem; font-weight: 600;">Lista vac&iacute;a</span>
                                </div>
                            </div>

                            <div class="total-display">
                                <div>
                                    <span class="elite-label" style="margin: 0; opacity: 0.6; font-size: 0.6rem;">Puntaje Total a Sumar</span>
                                    <div id="main-total-pts" style="font-size: 2.2rem; font-weight: 950; color: #1e293b; line-height: 1; margin-top: 0.3rem;">0</div>
                                </div>
                                <b style="color: #b91c1c; font-size: 0.8rem; letter-spacing: 1px;">PTS</b>
                            </div>

                            <button id="save-all-btn" class="btn-elite-black" onclick="saveAll()" disabled style="margin-top: 1.5rem;">REGISTRAR TODO</button>
                            <div style="text-align: center; margin-top: 1.5rem;">
                                <button onclick="location.reload()" style="background: none; border: none; font-size: 0.7rem; font-weight: 900; color: #94a3b8; cursor: pointer; text-transform: uppercase;">Cancelar operaci&oacute;n</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template id="tpl-error">
                <div class="elite-form-card" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <div style="text-align: center; padding: 3rem;">
                        <i class='bx bx-user-x' style="font-size: 4rem; color: #fee2e2; display: block; margin-bottom: 1rem;"></i>
                        <h2 style="font-weight: 950; color: #1e293b; font-size: 1.4rem; margin-bottom: 0.5rem;">Cliente no encontrado</h2>
                        <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 2rem;">No existe un registro vinculado a este c&oacute;digo o DNI.</p>
                        <div style="display: flex; gap: 1rem; justify-content: center;">
                            <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-elite-black" style="text-decoration: none; width: 180px; height: 45px; display: flex; align-items: center; justify-content: center;">Crear Nuevo</a>
                            <button onclick="initLayout()" style="background: #f1f5f9; color: #475569; width: 120px; border-radius: 12px; font-weight: 800; font-size: 0.75rem; border: none; cursor: pointer;">REINTENTAR</button>
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
                container.innerHTML = `<div style="text-align: center; color: #cbd5e1; padding: 4rem 1rem;"><i class='bx bx-list-ol' style="font-size: 2.5rem; opacity: 0.2; display: block; margin-bottom: 0.5rem;"></i><span style="font-size: 0.8rem; font-weight: 600;">Lista vac&iacute;a</span></div>`;
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
                    running = false; btn.innerHTML = "REGISTRAR"; btn.disabled = false;
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error' });
                running = false; btn.innerHTML = "REGISTRAR"; btn.disabled = false;
            }
        }
    </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
