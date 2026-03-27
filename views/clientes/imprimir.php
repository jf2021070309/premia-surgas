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
            border: 1px solid #f1f5f9;
        }

        .header-title {
            font-size: 11pt;
            font-weight: 800;
            color: #800000;
            letter-spacing: 0.05em;
            margin-bottom: 3mm;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .client-name {
            font-size: 10pt;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1mm;
        }

        .client-doc {
            font-size: 8pt;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 3mm;
        }

        #qrcode-print {
            background: #fff;
            padding: 1.5mm;
        }

        .footer-msg {
            font-size: 6.5pt;
            font-weight: 500;
            color: #94a3b8;
            margin-top: 3mm;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        @media screen {
            body { background: #f0f2f5; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; }
            .card-print { background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; border: none; }
            .no-print { margin-top: 2rem; display: flex; gap: 1rem; }
            .btn-print { background: #800000; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 8px; cursor: pointer; font-weight: 700; }
            .btn-back { background: #fff; border: 1px solid #e2e8f0; color: #64748b; padding: 0.8rem 1.5rem; border-radius: 8px; cursor: pointer; font-weight: 600; text-decoration: none; }
        }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="card-print">
        <div class="header-title">🔥 GAS EXPRESS SURGAS</div>
        <div class="client-name"><?= htmlspecialchars(mb_strtoupper($cliente['nombre'])) ?></div>
        <div class="client-doc">
            <?= $cliente['tipo_cliente'] === 'Normal' ? 'DNI' : 'RUC' ?>: <?= htmlspecialchars($cliente['tipo_cliente'] === 'Normal' ? ($cliente['dni'] ?? '—') : ($cliente['ruc'] ?? '—')) ?>
        </div>
        
        <div id="qrcode-print"></div>
        
        <div class="footer-msg">Acumula puntos en cada compra 🎁</div>
    </div>

    <div class="no-print">
        <button id="btn-print" class="btn-print">🖨️ Imprimir ahora</button>
        <a href="<?= BASE_URL ?>clientes/lista" class="btn-back">← Volver a la Lista</a>
    </div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    <?php
        $scanUrl = BASE_URL . 'scan?c=' . urlencode($cliente['codigo']) . '&t=' . urlencode($cliente['token']);
    ?>
    new QRCode(document.getElementById('qrcode-print'), {
        text: '<?= addslashes($scanUrl) ?>',
        width: 106,
        height: 106,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    });

    document.getElementById('btn-print').addEventListener('click', function () {
        setTimeout(function () { window.print(); }, 300);
    });
</script>
</body>
</html>
