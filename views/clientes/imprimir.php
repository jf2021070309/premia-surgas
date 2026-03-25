<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Tarjeta — <?= htmlspecialchars($cliente['nombre']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>
        @page { size: 85mm 55mm; margin: 0; }   /* Tamaño tarjeta de crédito */
        body  { background: #fff; }
        .card-print {
            width: 85mm;
            height: 55mm;
            border: 1.5px solid #ccc;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4mm;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
            margin: auto;
            text-align: center;
        }
        .card-print h1  { font-size: 11pt; color: #e67e22; margin: 0 0 2mm; }
        .card-print h2  { font-size: 9pt;  color: #333;    margin: 0 0 2mm; font-weight:600; }
        .card-print .cd { font-size: 8pt;  color: #555;    letter-spacing: 1px; margin-bottom: 2mm; font-weight:700; }
        .card-print #qrcode-print canvas,
        .card-print #qrcode-print img { width: 28mm !important; height: 28mm !important; }
        .card-print p   { font-size: 6.5pt; color: #777;   margin-top: 2mm; }
        @media screen {
            body { background: #eee; display: flex; flex-direction:column; align-items:center; justify-content:center; min-height:100vh; }
            .no-print { margin-top: 1rem; display:flex; gap:.8rem; }
        }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    <?php
        $scanUrl = BASE_URL . 'scan?c=' . urlencode($cliente['codigo']) . '&t=' . urlencode($cliente['token']);
    ?>
    <div class="card-print">
        <h1>&#x1F525; GAS EXPRESS SURGAS</h1>
        <h2><?= htmlspecialchars($cliente['nombre']) ?></h2>
        <div class="cd">
            <?= $cliente['tipo_cliente'] === 'Normal' ? 'DNI: ' : 'RUC: ' ?> <?= htmlspecialchars($cliente['tipo_cliente'] === 'Normal' ? ($cliente['dni'] ?? '—') : ($cliente['ruc'] ?? '—')) ?><br>
            CÓDIGO: <?= htmlspecialchars($cliente['codigo']) ?>
        </div>
        <div id="qrcode-print"></div>
        <p>Acumula puntos en cada compra &#x1F381;</p>
    </div>

    <div class="no-print">
        <button id="btn-print" class="btn btn-primary">&#x1F5A8; Imprimir ahora</button>
        <a href="<?= BASE_URL ?>clientes/exito?id=<?= $cliente['id'] ?>" class="btn btn-dark">&larr; Volver</a>
    </div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById('qrcode-print'), {
        text: '<?= $cliente['codigo'] ?>',
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
