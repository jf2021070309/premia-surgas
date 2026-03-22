<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        :root { 
            --primary: #821515; 
            --dark-wine: #2b0303;
            --silver-text: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
            --silver-metal: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--dark-wine);
            background: radial-gradient(circle at center, #5e0a0a 0%, #2b0303 100%);
            background-attachment: fixed;
            margin: 0; 
            color: white; 
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .header-wrapper {
            padding: 2.5rem 1.5rem 1rem;
            text-align: left;
            position: relative;
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
        }

        .user-greeting {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 55px; height: 55px; 
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; font-weight: 700;
        }

        .greeting-text h1 { 
            margin: 0; 
            font-size: 1.6rem; 
            font-weight: 700;
            color: #fff;
        }
        .greeting-text p { margin: 0; font-size: 0.85rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px; }

        /* --- 3D FREE TILT & FLIP CARD --- */
        .vip-card-container {
            perspective: 1200px;
            margin: 0 auto 3rem;
            max-width: 420px;
            width: 92%;
            height: 250px;
            cursor: pointer;
            z-index: 10;
        }

        .vip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            transform-style: preserve-3d;
            will-change: transform;
        }

        .vip-card-container.is-flipped .vip-card-inner {
            transform: rotateY(180deg) !important; /* Force flip, override JS tilt temporarily */
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 28px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            border: 1px solid rgba(255, 255, 255, 0.15);
            overflow: hidden;
        }

        /* FRONT: SATIN BLACK METALLIC */
        .card-front {
            background: #000;
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(255,255,255,0.08) 0%, transparent 50%),
                linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2rem;
        }

        .card-shine {
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
            transform: skewX(-25deg);
            animation: cardShineEffect 7s infinite;
        }
        @keyframes cardShineEffect { 0% { left: -150%; } 12% { left: 150%; } 100% { left: 150%; } }

        .card-header { display: flex; justify-content: space-between; align-items: flex-start; }
        .card-logo { height: 26px; filter: brightness(0) invert(1); opacity: 0.9; }
        
        .membership-badge {
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 2px;
            background: var(--silver-metal);
            color: #000;
            padding: 6px 12px;
            border-radius: 50px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .card-middle { text-align: left; margin-top: 1rem; }
        .label-small { font-size: 0.55rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5; margin-bottom: 4px; }
        .holder-name { font-size: 1.3rem; font-weight: 700; color: #fff; letter-spacing: 0.5px; }

        .card-footer { display: flex; justify-content: space-between; align-items: flex-end; }
        .client-code { font-family: monospace; font-size: 0.85rem; letter-spacing: 2.5px; opacity: 0.7; }
        
        .points-val { 
            font-size: 2.4rem; font-weight: 800; line-height: 1; display: block; 
            background: var(--silver-text); -webkit-background-clip: text; -webkit-text-fill-color: transparent; 
        }
        .points-unit { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5; display: block; text-align: right;}

        /* BACK: DARK WINE GRADIENT */
        .card-back {
            background: #111;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-image: radial-gradient(circle at center, #2b0303 0%, #0a0a0a 100%);
        }

        .qr-container {
            background: #fff;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
        }
        
        .qr-help { margin-top: 1.5rem; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.5; }

        /* Botón Tienda Elite */
        .btn-store {
            background: #fff;
            color: #000; 
            text-decoration: none; 
            padding: 1.2rem 2.5rem; 
            border-radius: 100px;
            display: flex; align-items: center; justify-content: center; gap: 0.8rem;
            font-weight: 800; font-size: 1rem; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            transition: 0.3s;
            max-width: 380px; width: 85%;
            margin: 0 auto 3.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: none;
            z-index: 20;
        }
        .btn-store:hover { transform: translateY(-3px); box-shadow: 0 25px 60px rgba(0,0,0,0.7); background: #eee; }

        .container { padding: 0 1.5rem 4rem; max-width: 500px; margin: 0 auto; width:100%; }
        .section-title { font-size: 0.95rem; font-weight: 700; margin-bottom: 1.2rem; opacity: 0.9; letter-spacing: 1px; color: #fff; }
        .history-card { background: rgba(255,255,255,0.04); backdrop-filter: blur(10px); border-radius: 2rem; border: 1px solid rgba(255,255,255,0.08); overflow: hidden; }
        .history-item { padding: 1.2rem 1.6rem; border-bottom: 1px solid rgba(255,255,255,0.04); }
        .history-main-row { display: flex; justify-content: space-between; align-items: center; }
        .item-name { font-size: 0.95rem; font-weight: 600; display: flex; align-items: center; gap: 10px; color: #fff; }
        .item-name i { color: var(--accent); }
        .item-pts { color: #fff; font-weight: 800; font-size: 1.05rem; }
        .history-date { font-size: 0.75rem; opacity: 0.4; margin-top: 4px; padding-left: 28px; }

        .footer { text-align: center; padding: 3rem 0; color: rgba(255,255,255,0.2); font-size: 0.75rem; }
        
        .logout-btn-client {
            position: absolute; top: 2.2rem; right: 1.5rem; color: rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.05); width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        .hint { text-align: center; font-size: 0.65rem; opacity: 0.4; text-transform: uppercase; letter-spacing: 1px; margin-top: -2.5rem; margin-bottom: 2rem; }

        [v-cloak] { display: none; }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <a href="<?= BASE_URL ?>logout" class="logout-btn-client">
            <i class='bx bx-log-out'></i>
        </a>
        <div class="user-greeting">
            <div class="profile-avatar"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
            <div class="greeting-text">
                <p>Digital Membership</p>
                <h1>Hola, <?= explode(' ', $cliente['nombre'])[0] ?></h1>
            </div>
        </div>
    </div>

    <!-- TARJETA VIP (TILT + FLIP) -->
    <div class="vip-card-container" id="cardContainer">
        <div class="vip-card-inner" id="cardInner">
            <div class="card-front">
                <div class="card-shine"></div>
                <div class="card-header">
                    <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" class="card-logo">
                    <span class="membership-badge">ELITE MEMBER</span>
                </div>
                <div class="card-middle">
                    <div class="label-small">Titular Surgas</div>
                    <div class="holder-name"><?= htmlspecialchars($cliente['nombre']) ?></div>
                    <div style="width: 40px; height: 30px; background: var(--silver-metal); border-radius: 6px; margin-top: 12px; opacity: 0.8;"></div>
                </div>
                <div class="card-footer">
                    <div class="client-code"><?= htmlspecialchars($cliente['codigo']) ?></div>
                    <div class="points-box">
                        <span class="points-val" id="points-counter">0</span>
                        <span class="points-unit">pts surgas</span>
                    </div>
                </div>
            </div>
            <div class="card-back">
                <div class="qr-container">
                    <div id="qrcode"></div>
                </div>
                <div class="qr-help">Escanear para acumular</div>
            </div>
        </div>
    </div>

    <div class="hint">Toca para girar • Mueve para inclinar</div>

    <a href="<?= BASE_URL ?>tienda" class="btn-store">
        <i class='bx bxs-shopping-bag'></i> Ir a la tienda
    </a>

    <div class="container">
        <div class="section-title">Actividad Reciente</div>
        <div class="history-card">
            <?php if (empty($ventas)): ?>
                <div style="padding: 3rem 2rem; text-align: center; opacity: 0.3; font-size: 0.9rem;">Sin movimientos.</div>
            <?php else: ?>
                <?php foreach (array_slice($ventas, 0, 4) as $v): ?>
                <div class="history-item">
                    <div class="history-main-row">
                        <div class="item-name">
                            <i class='bx bx-check-circle'></i>
                            <?= htmlspecialchars($v['detalle'] ?: 'Operación') ?>
                        </div>
                        <span class="item-pts">+<?= $v['puntos'] ?></span>
                    </div>
                    <span class="history-date"><?= date('d/m/Y', strtotime($v['fecha'])) ?></span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            &copy; <?= date('Y') ?> Surgas Premium Digital Card
        </div>
    </div>

    <script>
        const container = document.getElementById('cardContainer');
        const inner = document.getElementById('cardInner');
        let isFlipped = false;

        // --- EFECTO TILT (INCLINACIÓN) ---
        container.addEventListener('mousemove', (e) => {
            if (isFlipped) return;
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = ((y - centerY) / centerY) * -15;
            const rotateY = ((x - centerX) / centerX) * 15;
            
            inner.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });

        container.addEventListener('mouseleave', () => {
            if (isFlipped) return;
            inner.style.transform = `rotateX(0deg) rotateY(0deg)`;
        });

        // --- EFECTO FLIP (GIRAR) ---
        container.addEventListener('click', () => {
            isFlipped = !isFlipped;
            container.classList.toggle('is-flipped');
            if(!isFlipped) {
                inner.style.transform = `rotateX(0deg) rotateY(0deg)`;
            }
        });

        // Generar QR
        const qrContent = '<?= BASE_URL ?>scan?c=<?= urlencode($cliente['codigo']) ?>&t=<?= urlencode($cliente['token']) ?>';
        new QRCode(document.getElementById("qrcode"), {
            text: qrContent,
            width: 150, height: 150,
            colorDark : "#000000", colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        // Points Animation
        const pointsTarget = <?= (int) ($cliente['puntos'] ?? 0) ?>;
        const pointsElement = document.getElementById('points-counter');
        let currentPoints = 0;
        const duration = 2000;
        const steps = 60;
        const counterTimer = setInterval(() => {
            currentPoints += pointsTarget / steps;
            if (currentPoints >= pointsTarget) {
                pointsElement.textContent = Math.floor(pointsTarget).toLocaleString();
                clearInterval(counterTimer);
            } else {
                pointsElement.textContent = Math.floor(currentPoints).toLocaleString();
            }
        }, duration / steps);
    </script>
    <script> const BASE_URL = '<?= BASE_URL ?>'; </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
