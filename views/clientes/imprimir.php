<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Tarjeta — <?= htmlspecialchars($cliente['nombre']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>
        @page { size: 85mm 55mm; margin: 0; }
        body { background: #fff; margin: 0; padding: 0; -webkit-print-color-adjust: exact; }
        
        .card-print {
            width: 85mm;
            height: 55mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            text-align: center;
            position: relative;
            box-sizing: border-box;
            background: #fff;
            padding: 2mm;
        }

        .brand-header {
            font-size: 7.5pt;
            font-weight: 500;
            color: #94a3b8;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 2mm;
        }

        .client-name {
            font-size: 13pt;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1mm;
            letter-spacing: -0.01em;
        }

        .client-doc {
            font-size: 9.5pt;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 5mm;
        }

        .qr-outer-box {
            background: #f8fafc;
            border: 1px dashed #e2e8f0;
            padding: 3mm;
            border-radius: 6mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #qrcode-print img {
            width: 26mm !important;
            height: 26mm !important;
        }

        @media screen {
            body { background: #f0f2f5; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; }
            .card-print { background: #fff; box-shadow: 0 10px 40px rgba(0,0,0,0.1); border-radius: 20px; border: none; }
            .no-print { margin-top: 2.5rem; display: flex; flex-direction: column; gap: 1rem; align-items: center; }
            .btn-download { background: #0f172a; color: white; border: none; padding: 1rem 2rem; border-radius: 12px; cursor: pointer; font-weight: 700; display: flex; align-items: center; gap: 8px; font-size: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.25); transition: all 0.2s; }
            .btn-download:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.3); }
            .btn-back { color: #64748b; font-weight: 600; text-decoration: none; font-size: 0.9rem; }
            .btn-back:hover { color: #0f172a; text-decoration: underline; }
        }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="card-print" id="card-to-capture">
        <div class="brand-header">GAS EXPRESS SURGAS</div>
        <div class="client-name"><?= htmlspecialchars($cliente['nombre']) ?></div>
        <div class="client-doc">
            <?= $cliente['tipo_cliente'] === 'Normal' ? 'DNI' : 'RUC' ?>: <?= htmlspecialchars($cliente['tipo_cliente'] === 'Normal' ? ($cliente['dni'] ?? '—') : ($cliente['ruc'] ?? '—')) ?>
        </div>
        
        <div class="qr-outer-box">
            <div id="qrcode-print"></div>
        </div>
    </div>

    <div class="no-print">
        <button id="btn-download" class="btn-download">
            <i class='bx bx-download'></i> Descargar Imagen
        </button>
        <a href="<?= BASE_URL ?>clientes/lista" class="btn-back">← Volver al Directorio</a>
    </div>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    const scanUrl = '<?= BASE_URL . "scan?c=" . urlencode($cliente['codigo']) . "&t=" . urlencode($cliente['token']) ?>';
    
    new QRCode(document.getElementById('qrcode-print'), {
        text: scanUrl,
        width: 120,
        height: 120,
        colorDark: '#0f172a',
        colorLight: '#f8fafc',
        correctLevel: QRCode.CorrectLevel.H
    });

    document.getElementById('btn-download').addEventListener('click', async function () {
        const btn = this;
        const originalContent = btn.innerHTML;
        btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> Generando...";
        btn.disabled = true;

        try {
            const card = document.getElementById('card-to-capture');
            const canvas = await html2canvas(card, {
                scale: 3, // Alta calidad
                backgroundColor: '#ffffff',
                logging: false,
                useCORS: true
            });

            const link = document.createElement('a');
            link.download = 'Tarjeta_Surgas_<?= str_replace(' ', '_', htmlspecialchars($cliente['nombre'])) ?>.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        } catch (e) {
            console.error(e);
            alert("Error al generar la imagen.");
        } finally {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    });
</script>
</body>
</html>
</body>
</html>
