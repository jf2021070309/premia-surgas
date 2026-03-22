<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil VIP — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        :root { 
            --primary: #821515; 
            --silver-metal: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
            --silver-txt: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: radial-gradient(circle at center, #5e0a0a 0%, #2b0303 100%);
            background-attachment: fixed;
            margin: 0; color: white; min-height: 100vh; overflow-x: hidden;
            display: flex; flex-direction: column;
        }
        
        .header-wrapper { padding: 2.5rem 1.5rem 1rem; text-align: left; max-width: 500px; margin: 0 auto; width: 100%; position: relative;}
        .user-greeting { display: flex; align-items: center; gap: 15px; margin-bottom: 2rem; }
        .profile-avatar { width: 55px; height: 55px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 700; }
        .greeting-text h1 { margin: 0; font-size: 1.6rem; font-weight: 700; color: #fff; }
        .greeting-text p { margin: 0; font-size: 0.8rem; opacity: 0.5; text-transform: uppercase; letter-spacing: 1.5px; }

        /* --- 360° TOTAL FREE ROTATION --- */
        .vip-card-stage {
            perspective: 2000px;
            margin: 2rem auto 4rem;
            max-width: 420px;
            width: 90%;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            touch-action: none; /* Crucial for mobile drag */
            user-select: none;
        }

        .vip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            will-change: transform;
            /* Initially positioned slightly tilted for 3D look */
            transform: rotateY(-15deg) rotateX(10deg);
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 28px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.6);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* FRONT: DARK AS BLACK */
        .card-front {
            background: #000 url('https://www.transparenttextures.com/patterns/carbon-fibre.png');
            display: flex; flex-direction: column; justify-content: space-between; padding: 2rem;
            background-color: #050505;
        }

        .card-shine {
            position: absolute; top: 0; left: -150%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
            transform: skewX(-25deg); animation: cardShineEffect 8s infinite ease-out;
        }
        @keyframes cardShineEffect { 0% { left: -150%; } 10% { left: 200%; } 100% { left: 200%; } }

        .card-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .card-logo { height: 26px; filter: brightness(0) invert(1) opacity(0.9); }
        .membership-tag { 
            font-size: 0.6rem; font-weight: 800; letter-spacing: 2.5px; 
            background: var(--silver-metal); color: #000; padding: 5px 12px; border-radius: 50px; 
        }

        .card-holder { margin-top: 1.5rem; text-align: left; }
        .holder-label { font-size: 0.55rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom: 2px; }
        .holder-name { font-size: 1.35rem; font-weight: 700; letter-spacing: 1px; color: #fff; }

        .card-footer { display: flex; justify-content: space-between; align-items: flex-end; }
        .client-code { font-family: monospace; font-size: 0.85rem; letter-spacing: 3px; opacity: 0.7; }
        .points-val { font-size: 2.5rem; font-weight: 800; line-height: 1; display: block; background: var(--silver-txt); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        /* BACK SIDE: RED WINE GLOSS */
        .card-back {
            transform: rotateY(180deg);
            background: #1a0000;
            background-image: radial-gradient(circle at 70% 30%, #4e0808 0%, #1a0000 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem;
        }
        .qr-wrapper { background: #fff; padding: 12px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.6); }
        .qr-info { margin-top: 1.5rem; font-size: 0.7rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1.5px; }

        /* Buttons & Footer */
        .btn-store {
            background: #fff; color: #000; text-decoration: none; padding: 1.2rem 2rem; border-radius: 100px;
            display: flex; align-items: center; justify-content: center; gap: 0.8rem; font-weight: 800; font-size: 1rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4); transition: 0.3s; width: 85%; max-width: 380px; margin: 0 auto 3rem;
            text-transform: uppercase; letter-spacing: 2px;
        }
        .btn-store:hover { background: var(--silver-metal); transform: translateY(-3px); }

        .container { padding: 0 1.5rem 4rem; max-width: 500px; margin: 0 auto; width: 100%; }
        .section-title { font-size: 0.9rem; font-weight: 700; margin-bottom: 1.2rem; opacity: 0.8; letter-spacing: 1.5px; }
        .history-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border-radius: 2rem; border: 1px solid rgba(255,255,255,0.1); overflow: hidden; }
        .history-item { padding: 1.2rem 1.6rem; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; }
        .item-label { font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .item-pts { font-weight: 800; color: #fff; font-size: 1rem; }

        .logout-btn-client { position: absolute; top: 2.2rem; right: 1.5rem; color: rgba(255,255,255,0.3); background: rgba(255,255,255,0.05); width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .hint-text { text-align: center; font-size: 0.65rem; opacity: 0.4; text-transform: uppercase; letter-spacing: 1.5px; margin-top: -3.5rem; margin-bottom: 2.5rem; }

        [v-cloak] { display: none; }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <a href="<?= BASE_URL ?>logout" class="logout-btn-client"><i class='bx bx-log-out'></i></a>
        <div class="user-greeting">
            <div class="profile-avatar"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
            <div class="greeting-text">
                <p>Membresía Digital</p>
                <h1>Hola, <?= explode(' ', $cliente['nombre'])[0] ?></h1>
            </div>
        </div>
    </div>

    <!-- TARJETA MOTOR 3D 360° -->
    <div class="vip-card-stage" id="cardStage">
        <div class="vip-card-inner" id="cardInner">
            <!-- CARA FRONTAL -->
            <div class="card-front">
                <div class="card-shine"></div>
                <div class="card-top">
                    <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" class="card-logo">
                    <span class="membership-tag">VIP ELITE</span>
                </div>
                <div class="card-holder">
                    <div class="holder-label">Digital Member Card</div>
                    <div class="holder-name"><?= htmlspecialchars($cliente['nombre']) ?></div>
                    <div style="width: 45px; height: 32px; background: var(--silver-metal); border-radius: 6px; margin-top: 15px; opacity: 0.8; box-shadow: inset 0 0 10px rgba(0,0,0,0.5);"></div>
                </div>
                <div class="card-footer">
                    <div class="client-code"><?= htmlspecialchars($cliente['codigo']) ?></div>
                    <div style="text-align: right;">
                        <span class="holder-label" style="opacity: 0.3;">Saldo Puntos</span>
                        <b class="points-val" id="pts-count">0</b>
                    </div>
                </div>
            </div>

            <!-- CARA TRASERA -->
            <div class="card-back">
                <div class="qr-wrapper">
                    <div id="qrcode"></div>
                </div>
                <div class="qr-info">Muestra código para escanear</div>
            </div>
        </div>
    </div>

    <div class="hint-text"><i class='bx bx-move'></i> Arrastra la tarjeta para girar 360°</div>

    <a href="<?= BASE_URL ?>tienda" class="btn-store">
        <i class='bx bxs-shopping-bag'></i> Ir a la tienda
    </a>

    <div class="container">
        <div class="section-title">Últimos Movimientos</div>
        <div class="history-card">
            <?php if (empty($ventas)): ?>
                <div style="padding: 3rem; text-align: center; opacity: 0.3;">Vacío.</div>
            <?php else: ?>
                <?php foreach (array_slice($ventas, 0, 4) as $v): ?>
                <div class="history-item">
                    <div class="item-label"><i class='bx bx-coin-stack' style="color:#f39c12"></i> <?= htmlspecialchars($v['detalle'] ?: 'Venta') ?></div>
                    <span class="item-pts">+<?= $v['puntos'] ?></span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const stage = document.getElementById('cardStage');
        const inner = document.getElementById('cardInner');
        
        let isDragging = false;
        let lastX = 0;
        let lastY = 0;
        let rotY = -15; // Initial values
        let rotX = 10;

        // --- MOTOR DE ROTACIÓN LIBRE (360°) ---
        const startDrag = (e) => {
            isDragging = true;
            lastX = e.clientX || e.touches[0].clientX;
            lastY = e.clientY || e.touches[0].clientY;
            inner.style.transition = 'none'; // Smooth manual control
        };

        const moveDrag = (e) => {
            if (!isDragging) return;
            const currentX = e.clientX || (e.touches ? e.touches[0].clientX : 0);
            const currentY = e.clientY || (e.touches ? e.touches[0].clientY : 0);
            
            const deltaX = currentX - lastX;
            const deltaY = currentY - lastY;

            rotY += deltaX * 0.5; // Sensitivity
            rotX -= deltaY * 0.5;

            inner.style.transform = `rotateY(${rotY}deg) rotateX(${rotX}deg)`;

            lastX = currentX;
            lastY = currentY;
        };

        const endDrag = () => {
            if (!isDragging) return;
            isDragging = false;
            inner.style.transition = 'transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        };

        stage.addEventListener('mousedown', startDrag);
        window.addEventListener('mousemove', moveDrag);
        window.addEventListener('mouseup', endDrag);

        stage.addEventListener('touchstart', startDrag);
        window.addEventListener('touchmove', moveDrag);
        window.addEventListener('touchend', endDrag);

        // --- Generar QR ---
        const qrUrl = '<?= BASE_URL ?>scan?c=<?= urlencode($cliente['codigo']) ?>&t=<?= urlencode($cliente['token']) ?>';
        new QRCode(document.getElementById("qrcode"), { 
            text: qrUrl, width: 140, height: 140, colorDark: "#000", colorLight: "#fff",
            correctLevel: QRCode.CorrectLevel.H 
        });

        // --- Animación de Puntos ---
        const ptsTarget = <?= (int)($cliente['puntos'] ?? 0) ?>;
        const ptsElem = document.getElementById('pts-count');
        let current = 0;
        const dur = 2000; const jumps = 60;
        const timer = setInterval(() => {
            current += ptsTarget / jumps;
            if (current >= ptsTarget) {
                ptsElem.textContent = Math.floor(ptsTarget).toLocaleString();
                clearInterval(timer);
            } else {
                ptsElem.textContent = Math.floor(current).toLocaleString();
            }
        }, dur / jumps);

    </script>
    <script> const BASE_URL = '<?= BASE_URL ?>'; </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
