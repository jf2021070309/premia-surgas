<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Puntos — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #821515; --secondary: #4a0c0c; --success: #27ae60; --danger: #e74c3c; }
        body { font-family: 'Outfit', sans-serif; background: #fdfdfd; margin: 0; padding: 0; color: #333; }
        
        /* Layout */
        .header {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: white; padding: 1.5rem; text-align: center; border-radius: 0 0 2rem 2rem;
            position: relative; box-shadow: 0 4px 15px rgba(75, 18, 130, 0.2);
        }
        .header h1 { margin: 0; font-size: 1.4rem; text-transform: uppercase; letter-spacing: 1px; }
        .back-nav { position: absolute; left: 15px; top: 18px; }
        .btn-back { color: white; text-decoration: none; font-size: 1.2rem; }

        .container { padding: 1.5rem; max-width: 500px; margin: 0 auto; }
        
        /* Views */
        .v-screen { display: none; animation: fadeIn 0.3s ease; }
        .v-screen.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Components */
        .card { background: white; padding: 1.5rem; border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 1.5rem; border: 1px solid #f0f0f0; }
        .section-title { font-size: 0.9rem; font-weight: 700; color: #888; text-transform: uppercase; margin-bottom: 1rem; display: block; }
        
        .client-info-box { background: #f8f0ff; padding: 1rem; border-radius: 1rem; border: 1px dashed var(--primary); margin-bottom: 1rem; }
        .client-name { font-size: 1.2rem; font-weight: 700; color: var(--primary); display: block; }
        .client-detail { font-size: 0.9rem; color: #666; }

        .btn { 
            width: 100%; padding: 1rem; border-radius: 12px; font-weight: 700; cursor: pointer; 
            border: none; transition: all 0.2s; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-secondary { background: #f0f0f0; color: #555; }
        .btn-success { background: var(--success); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }

        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; color: #555; }
        .form-control { 
            width: 100%; padding: 0.8rem; border: 1.5px solid #eee; border-radius: 10px; 
            font-family: inherit; font-size: 1rem; box-sizing: border-box;
        }
        .form-control:focus { outline: none; border-color: var(--primary); }

        /* Operations List */
        .ops-list { margin-top: 1rem; border-top: 1px solid #eee; padding-top: 1rem; }
        .op-item { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 0.8rem; background: #fff; border-radius: 10px; margin-bottom: 0.5rem; border: 1px solid #f5f5f5;
        }
        .op-info b { display: block; color: var(--primary); }
        .op-info span { font-size: 0.8rem; color: #888; }
        .op-pts { font-weight: 700; color: var(--success); }

        .total-box { 
            background: var(--secondary); color: white; padding: 1rem; border-radius: 12px; 
            text-align: center; margin: 1.5rem 0;
        }
        .total-box span { font-size: 0.9rem; opacity: 0.8; }
        .total-box b { font-size: 1.8rem; display: block; }

        /* Scanner */
        #reader { width: 100%; border-radius: 1rem; overflow: hidden; }
        .scan-msg { text-align: center; color: #666; margin-top: 1rem; }
    </style>
</head>
<body>

    <div class="header">
        <div class="back-nav">
            <a href="<?= BASE_URL ?>panel" class="btn-back">✕</a>
        </div>
        <h1 id="header-title">Registrar Puntos</h1>
    </div>

    <div class="container" id="app">
        
        <!-- PANTALLA 1: INICIO -->
        <div id="screen-start" class="v-screen active">
            <button class="btn btn-primary" onclick="initScanner()" style="margin-bottom: 0.8rem; height: 60px; font-size: 1.1rem;">
                <span>📷</span> Escanear con cámara
            </button>
            
            <div style="margin-bottom: 1.5rem;">
                <input type="file" id="qr-input-file" accept="image/*" style="display: none;" onchange="onFileChange(event)">
                <button class="btn btn-secondary" onclick="document.getElementById('qr-input-file').click()" style="height: 60px; font-size: 1.1rem; border: 2px dashed #ccc; background: #fff; color: #666;">
                    <span>📁</span> Subir imagen de QR
                </button>
            </div>

            <div class="card">
                <span class="section-title">Cliente</span>
                <div class="client-info-box" style="background: #f5f5f5; border-color: #ddd;">
                    <span class="client-name" style="color: #888;">No seleccionado</span>
                </div>

                <hr style="border: none; border-top: 1px solid #eee; margin: 1.5rem 0;">

                <div class="form-group">
                    <label>Tipo operación:</label>
                    <select id="op-type" class="form-control" disabled>
                        <?php foreach ($operaciones as $op): ?>
                            <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?> pts)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Cantidad:</label>
                        <input type="number" id="op-qty" class="form-control" value="1" min="1" disabled>
                    </div>
                    <div class="form-group">
                        <label>Subtotal:</label>
                        <div id="op-subtotal" style="padding: 0.8rem; font-weight: 700; color: var(--primary);">-</div>
                    </div>
                </div>

                <button class="btn btn-secondary" disabled>Agregar</button>
            </div>

            <div class="ops-list">
                <span class="section-title">Operaciones</span>
                <div style="text-align: center; color: #aaa; padding: 1rem;">( vacío )</div>
            </div>

            <div class="total-box">
                <span>TOTAL PUNTOS</span>
                <b id="total-pts">0</b>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <button class="btn btn-success" disabled>Guardar</button>
                <a href="<?= BASE_URL ?>panel" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>

        <!-- PANTALLA 2: ESCANEANDO -->
        <div id="screen-scan" class="v-screen">
            <div class="card">
                <div id="reader"></div>
                <div class="scan-msg">📷 Apunte al QR del cliente</div>
            </div>
            <button class="btn btn-danger" onclick="stopScanner()">Cancelar</button>
        </div>

        <!-- PANTALLA 3: CLIENTE ENCONTRADO -->
        <div id="screen-main" class="v-screen">
            <button class="btn btn-secondary" onclick="initScanner()" style="margin-bottom: 1.5rem;">
                <span>📷</span> Escanear otro QR
            </button>

            <div class="card">
                <span class="section-title">Cliente</span>
                <div class="client-info-box">
                    <span id="res-name" class="client-name">-</span>
                    <span id="res-phone" class="client-detail">-</span>
                    <input type="hidden" id="client-id">
                </div>

                <hr style="border: none; border-top: 1px solid #eee; margin: 1.5rem 0;">

                <div class="form-group">
                    <label>Tipo operación:</label>
                    <select id="main-op-type" class="form-control" onchange="updateSubtotal()">
                        <?php foreach ($operaciones as $op): ?>
                            <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?> pts)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Cantidad:</label>
                        <input type="number" id="main-op-qty" class="form-control" value="1" min="1" oninput="updateSubtotal()">
                    </div>
                    <div class="form-group">
                        <label>Puntos unidad:</label>
                        <div id="main-op-unit" style="padding: 0.8rem; font-weight: 700; color: #666;">-</div>
                    </div>
                </div>

                <button class="btn btn-primary" onclick="addOperation()">+ Agregar Operación</button>
            </div>

            <div class="ops-list">
                <span class="section-title">Operaciones</span>
                <div id="ops-container">
                    <!-- Dinámico -->
                </div>
            </div>

            <div class="total-box">
                <span>TOTAL PUNTOS</span>
                <b id="main-total-pts">0</b>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <button id="save-all-btn" class="btn btn-success" onclick="saveAll()">Guardar Todo</button>
                <button class="btn btn-secondary" onclick="location.reload()">Cancelar</button>
            </div>
        </div>

        <!-- PANTALLA 4: NO ENCONTRADO -->
        <div id="screen-error" class="v-screen">
            <div class="card" style="text-align: center; border-top: 5px solid var(--danger);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">😕</div>
                <h2 style="margin: 0; color: var(--danger);">Cliente no encontrado</h2>
                <p style="color: #666;">El QR escaneado no coincide con ningún cliente registrado en nuestro sistema.</p>
                
                <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.8rem;">
                    <a href="<?= BASE_URL ?>clientes/nuevo" class="btn btn-primary">➕ Registrar nuevo cliente</a>
                    <button class="btn btn-secondary" onclick="initScanner()">📷 Escanear otra vez</button>
                    <button class="btn btn-danger" onclick="location.reload()">Cancelar</button>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const baseUrl = '<?= BASE_URL ?>';
        let html5QrCode;
        let operations = [];
        let running = false;

        function showScreen(id) {
            document.querySelectorAll('.v-screen').forEach(s => s.classList.remove('active'));
            document.getElementById(id).classList.add('active');
            
            const titles = {
                'screen-start': 'Registrar Puntos',
                'screen-scan': 'Escanear QR',
                'screen-main': 'Registrar Puntos',
                'screen-error': 'Error'
            };
            document.getElementById('header-title').innerText = titles[id] || 'Surgas';
        }

        async function initScanner() {
            showScreen('screen-scan');
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            const config = { 
                fps: 15, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };
            
            try {
                await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
            } catch (err) {
                console.error("Scanner Error:", err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Cámara',
                    text: 'No se pudo acceder a la cámara. Verifica los permisos.',
                    confirmButtonColor: '#821515'
                });
                showScreen('screen-start');
            }
        }

        async function onFileChange(event) {
            if (event.target.files.length === 0) return;
            
            const imageFile = event.target.files[0];
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }

            Swal.fire({
                title: 'Procesando QR...',
                html: 'Leyendo imagen, por favor espera.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                // Intentar leer con configuración experimental para mayor precisión
                const decodedText = await html5QrCode.scanFile(imageFile, false);
                Swal.close();
                onScanSuccess(decodedText);
            } catch (err) {
                console.error("File Scan Error:", err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Lectura',
                    text: 'No se detectó un código QR. Asegúrate de que la imagen sea clara y el código esté centrado.',
                    footer: '<small>Error técnico: ' + err + '</small>',
                    confirmButtonColor: '#821515'
                });
            }
            event.target.value = '';
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => showScreen('screen-start'));
            } else {
                showScreen('screen-start');
            }
        }

        function onScanSuccess(decodedText) {
            let codigo = decodedText;
            if (decodedText.includes('c=')) {
                const urlParams = new URLSearchParams(decodedText.split('?')[1]);
                codigo = urlParams.get('c');
            } else if (decodedText.includes('/')) {
                codigo = decodedText.split('/').pop();
            }

            // Si el escáner está activo (cámara), lo detenemos. 
            // Si es escaneo de archivo, no hay nada que detener.
            if (html5QrCode && html5QrCode.getState() === 2) { // 2 = Scanning
                html5QrCode.stop().then(() => {
                    buscarCliente(codigo);
                });
            } else {
                buscarCliente(codigo);
            }
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo contactar con el servidor.',
                    confirmButtonColor: '#821515'
                });
                showScreen('screen-start');
            }
        }

        function updateSubtotal() {
            const unit = parseInt(document.getElementById('main-op-type').value);
            const qty = parseInt(document.getElementById('main-op-qty').value) || 1;
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
            
            // Reset qty
            document.getElementById('main-op-qty').value = 1;
        }

        function removeOperation(index) {
            operations.splice(index, 1);
            renderOperations();
        }

        function renderOperations() {
            const container = document.getElementById('ops-container');
            if (operations.length === 0) {
                container.innerHTML = '<div style="text-align: center; color: #aaa; padding: 1rem;">( vacío )</div>';
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
                            <button onclick="removeOperation(${i})" style="background:none; border:none; color:#ff4444; font-size:1.2rem;">✕</button>
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

            if (total <= 0) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Operación vacía',
                    text: 'Agrega al menos una operación antes de guardar.',
                    confirmButtonColor: '#821515'
                });
            }

            running = true;
            const btn = document.getElementById('save-all-btn');
            btn.innerText = "Guardando...";
            btn.disabled = true;

            try {
                const detalleString = operations.map(op => `${op.name} x${op.qty} (+${op.subtotal} pts)`).join(', ');
                console.log('Enviando detalle:', detalleString);
                const res = await fetch(baseUrl + 'scan/registrar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        cliente_id: clientId, 
                        puntos: total,
                        detalle: detalleString
                    })
                });
                const data = await res.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Puntos registrados exitosamente',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = baseUrl + 'panel';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#821515'
                    });
                    running = false;
                    btn.innerText = "Guardar Todo";
                    btn.disabled = false;
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Servidor',
                    text: 'Ocurrió un fallo al procesar la solicitud.',
                    confirmButtonColor: '#821515'
                });
                running = false;
                btn.innerText = "Guardar Todo";
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
