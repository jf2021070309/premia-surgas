<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Puntos — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4b1282;
            --secondary: #2d0b4e;
        }
        body { font-family: 'Outfit', sans-serif; background: #f4f7f6; }
        .register-header {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: white; padding: 2rem 1rem; text-align: center;
            border-radius: 0 0 2rem 2rem; margin-bottom: 2rem;
        }
        .scanner-container {
            max-width: 500px; margin: 0 auto; background: white;
            padding: 1.5rem; border-radius: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        #reader { width: 100%; border-radius: 1rem; overflow: hidden; border: none !important; }
        .btn-scan {
            width: 100%; padding: 1rem; background: var(--primary); color: white;
            border: none; border-radius: 12px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 1rem;
        }
        .client-data-card {
            margin-top: 1.5rem; padding: 1.5rem; background: #fff; border-radius: 1rem;
            border-left: 5px solid var(--primary); display: none;
        }
        .points-selector {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin: 1.5rem 0;
        }
        .point-btn {
            padding: 1rem; border: 2px solid #eee; border-radius: 12px;
            background: white; font-weight: 700; font-size: 1.2rem; cursor: pointer;
            transition: all 0.2s; color: var(--primary);
        }
        .point-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
        .form-control {
            width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 1rem;
            font-family: inherit;
        }
        .btn-save {
            width: 100%; padding: 1rem; background: #27ae60; color: white;
            border: none; border-radius: 12px; font-weight: 700; cursor: pointer;
            font-size: 1.1rem; display: none;
        }
        .status-msg { margin-top: 1rem; text-align: center; font-weight: 600; }
        .back-nav { position: absolute; top: 20px; left: 20px; }
        .btn-back {
            background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);
            color: white; padding: 0.5rem 1rem; border-radius: 2rem; text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="register-header">
        <div class="back-nav">
            <a href="<?= BASE_URL ?>panel" class="btn-back">← Volver</a>
        </div>
        <h1>Registrar Puntos</h1>
        <p>Escanea el QR del cliente para asignar puntos</p>
    </div>

    <div class="container" id="app">
        <div class="scanner-container">
            <div id="reader"></div>
            <button id="start-btn" class="btn-scan">
                <span>📷</span> Abrir Cámara para Escanear
            </button>

            <div id="client-info" class="client-data-card">
                <h3 style="margin-bottom: 0.5rem;">Datos del Cliente</h3>
                <p><strong>Nombre:</strong> <span id="client-name">-</span></p>
                <p><strong>Celular:</strong> <span id="client-phone">-</span></p>
                <input type="hidden" id="client-id">

                <div class="points-selector">
                    <button class="point-btn" data-pts="5">+5</button>
                    <button class="point-btn" data-pts="10">+10</button>
                    <button class="point-btn" data-pts="15">+15</button>
                </div>

                <label style="font-size: 0.85rem; color: #666; margin-bottom: 0.3rem; display: block;">Motivo (Opcional)</label>
                <input type="text" id="motivo" class="form-control" placeholder="Ej: Compra de balón 10kg">

                <button id="save-btn" class="btn-save">Guardar Puntos</button>
            </div>
            
            <div id="status" class="status-msg"></div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const baseUrl = '<?= BASE_URL ?>';
        const startBtn = document.getElementById('start-btn');
        const readerDiv = document.getElementById('reader');
        const clientInfo = document.getElementById('client-info');
        const saveBtn = document.getElementById('save-btn');
        const statusDiv = document.getElementById('status');
        
        let html5QrCode;
        let selectedPoints = 0;

        // Iniciar Escáner
        startBtn.addEventListener('click', () => {
            startBtn.style.display = 'none';
            html5QrCode = new Html5Qrcode("reader");
            
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            html5QrCode.start(
                { facingMode: "environment" }, 
                config,
                onScanSuccess
            ).catch(err => {
                statusDiv.innerText = "Error al abrir cámara: " + err;
                startBtn.style.display = 'block';
            });
        });

        function onScanSuccess(decodedText) {
            // El QR puede ser una URL o solo el código
            // Intentamos extraer el código CLI-XXXXXX
            let codigo = decodedText;
            if (decodedText.includes('c=')) {
                const urlParams = new URLSearchParams(decodedText.split('?')[1]);
                codigo = urlParams.get('c');
            } else if (decodedText.includes('/')) {
                codigo = decodedText.split('/').pop();
            }

            html5QrCode.stop().then(() => {
                readerDiv.style.display = 'none';
                buscarCliente(codigo);
            });
        }

        async function buscarCliente(codigo) {
            statusDiv.innerHTML = "🔍 Buscando cliente...";
            try {
                const res = await fetch(baseUrl + 'scan/buscar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ codigo })
                });
                const data = await res.json();

                if (data.success) {
                    document.getElementById('client-name').innerText = data.cliente.nombre;
                    document.getElementById('client-phone').innerText = data.cliente.celular;
                    document.getElementById('client-id').value = data.cliente.id;
                    clientInfo.style.display = 'block';
                    statusDiv.innerHTML = "✅ Cliente identificado";
                } else {
                    statusDiv.innerHTML = "❌ " + data.message;
                    startBtn.style.display = 'block';
                    readerDiv.style.display = 'block';
                }
            } catch (e) {
                statusDiv.innerHTML = "❌ Error de conexión";
            }
        }

        // Selección de puntos
        document.querySelectorAll('.point-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.point-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedPoints = parseInt(btn.dataset.pts);
                saveBtn.style.display = 'block';
            });
        });

        // Guardar Puntos
        saveBtn.addEventListener('click', async () => {
            const clienteId = document.getElementById('client-id').value;
            const motivo = document.getElementById('motivo').value;

            if (!selectedPoints) return alert("Selecciona los puntos");

            saveBtn.disabled = true;
            saveBtn.innerText = "Guardando...";

            try {
                const res = await fetch(baseUrl + 'scan/registrar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        cliente_id: clienteId, 
                        puntos: selectedPoints,
                        motivo: motivo 
                    })
                });
                const data = await res.json();

                if (data.success) {
                    statusDiv.innerHTML = "🎉 ¡Puntos registrados exitosamente!";
                    clientInfo.style.display = 'none';
                    setTimeout(() => window.location.href = baseUrl + 'panel', 2000);
                } else {
                    alert(data.message);
                    saveBtn.disabled = false;
                    saveBtn.innerText = "Guardar Puntos";
                }
            } catch (e) {
                alert("Error al guardar");
                saveBtn.disabled = false;
            }
        });
    </script>
</body>
</html>
