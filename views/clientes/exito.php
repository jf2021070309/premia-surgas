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
        <div style="font-size:0.95rem; font-weight: 500; color: #555; margin-bottom: 0.5rem;">
            <?= $cliente['tipo_cliente'] === 'Normal' ? 'DNI' : 'RUC' ?>: <?= htmlspecialchars($cliente['tipo_cliente'] === 'Normal' ? ($cliente['dni'] ?? '—') : ($cliente['ruc'] ?? '—')) ?>
        </div>
        <div class="badge-code"><?= htmlspecialchars($cliente['codigo']) ?></div>

        <div class="qr-code-box">
            <div id="qrcode"></div>
        </div>

        <p style="color:var(--muted); font-size:.88rem;">
            📍 <?= htmlspecialchars($cliente['departamento'] ?? '—') ?> &nbsp;|&nbsp;
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
        <button onclick="downloadCard()" class="btn btn-dark" style="background:#444">💾 Descargar Imagen</button>
        <button onclick="shareWhatsApp()" class="btn" style="background:#25D366; color:white">🟢 Compartir WhatsApp</button>
        
        <div style="width:100%; height:1px; background:#eee; margin: 0.5rem 0;"></div>
        
        <a href="<?= BASE_URL ?>clientes/imprimir?id=<?= $cliente['id'] ?>" target="_blank" class="btn btn-outline" style="border-color:#444; color:#444">🖨 Imprimir</a>
        <a href="<?= BASE_URL ?>clientes/nuevo" class="btn btn-outline">+ Nuevo</a>
        <a href="<?= BASE_URL ?>panel" class="btn btn-primary">Ir al Panel</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
    const clienteNombre = "<?= addslashes($cliente['nombre']) ?>";
    const clienteCelular = "<?= addslashes($cliente['celular']) ?>";
    const clienteCodigo = "<?= addslashes($cliente['codigo']) ?>";

    // Generar QR (Usa el código directo para evitar enlaces genéricos)
    const qr = new QRCode(document.getElementById('qrcode'), {
        text: clienteCodigo,
        width: 220,
        height: 220,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });

    async function captureCard() {
        const card = document.querySelector('.qr-card');
        return await html2canvas(card, {
            scale: 2,
            backgroundColor: '#f0f2f5',
            logging: false,
            useCORS: true
        });
    }

    async function downloadCard() {
        const btn = event.currentTarget;
        const originalText = btn.innerText;
        btn.innerText = "Generando...";
        btn.disabled = true;

        try {
            const canvas = await captureCard();
            const link = document.createElement('a');
            link.download = `Tarjeta_Surgas_${clienteNombre.replace(/\s+/g, '_')}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        } catch (e) {
            alert("Error al generar la imagen.");
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    async function shareWhatsApp() {
        const btn = event.currentTarget;
        const originalText = btn.innerText;
        btn.innerText = "Preparando...";
        
        const message = `¡Hola! 🔥 Aquí tienes tu tarjeta de cliente de *Gas Express Surgas*.\n\n👤 *Cliente:* ${clienteNombre}\n🆔 *Código:* ${clienteCodigo}\n\nPresenta este QR en tus compras para acumular puntos y canjear premios. ¡Gracias por tu preferencia! 🏠✨`;

        try {
            const canvas = await captureCard();
            canvas.toBlob(async (blob) => {
                try {
                    const data = [new ClipboardItem({ [blob.type]: blob })];
                    await navigator.clipboard.write(data);
                } catch (err) {
                    console.warn("No se pudo copiar al portapapeles automáticamente.");
                }
            });

            const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
            const waUrl = isMobile 
                ? `https://wa.me/51${clienteCelular}?text=${encodeURIComponent(message)}`
                : `https://web.whatsapp.com/send?phone=51${clienteCelular}&text=${encodeURIComponent(message)}`;
            
            window.open(waUrl, '_blank');
            alert("✅ ¡Imagen de tarjeta copiada!\n\nSe ha abierto el chat. Solo tienes que 'Pegar' (Ctrl+V) para enviar la tarjeta junto con el mensaje.");
        } catch (e) {
            console.error(e);
            alert("Error al procesar la tarjeta.");
        } finally {
            btn.innerText = originalText;
        }
    }
</script>
<script> const BASE_URL = '<?= BASE_URL ?>'; </script>
<script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
