<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Cliente — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>
        #qrcode canvas, #qrcode img { display:block; margin:0 auto; }
    </style>
</head>
<body>
<div class="topbar no-print">
    <a href="<?= BASE_URL ?>panel" style="color:#fff; font-size:1.3rem; margin-right:.8rem">←</a>
    <span class="topbar-logo">🎫 Tarjeta del Cliente</span>
</div>

<div class="container" style="max-width:500px">
    <div class="card qr-card" style="margin-top:2rem">
        <div style="font-size:.8rem; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:.5rem">GAS EXPRESS SURGAS</div>
        <h2 style="color:var(--dark); font-size:1.4rem; margin-bottom:.4rem"><?= htmlspecialchars($cliente['nombre']) ?></h2>
        <div class="badge-code"><?= htmlspecialchars($cliente['codigo']) ?></div>

        <?php
            $scanUrl = BASE_URL . 'scan?c=' . urlencode($cliente['codigo']) . '&t=' . urlencode($cliente['token']);
        ?>
        <div class="qr-code-box">
            <div id="qrcode"></div>
        </div>

        <p style="color:var(--muted); font-size:.88rem;">
            📍 <?= htmlspecialchars($cliente['distrito'] ?: '—') ?> &nbsp;|&nbsp;
            📞 <?= htmlspecialchars($cliente['celular']) ?>
        </p>
        <p style="color:var(--muted); font-size:.88rem; margin-top:.4rem">
            ⭐ Puntos acumulados: <strong style="color:var(--primary)"><?= $cliente['puntos'] ?></strong>
        </p>

        <p style="margin-top:1.2rem; font-size:.85rem; color:var(--muted)">
            Presenta este QR en cada compra para acumular puntos y canjear premios.
        </p>
    </div>

    <div class="action-row no-print" style="margin-top:1.2rem">
        <a href="<?= BASE_URL ?>clientes/imprimir?id=<?= $cliente['id'] ?>" target="_blank" class="btn btn-dark">🖨 Imprimir Tarjeta</a>
        <a href="<?= BASE_URL ?>clientes/nuevo" class="btn btn-outline">+ Nuevo Cliente</a>
        <a href="<?= BASE_URL ?>panel" class="btn btn-primary">Continuar</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById('qrcode'), {
        text: '<?= addslashes($scanUrl) ?>',
        width: 200,
        height: 200,
        colorDark: '#1a1a1a',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    });
</script>
</body>
</html>
