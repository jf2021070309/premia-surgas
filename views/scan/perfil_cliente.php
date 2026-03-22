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
            --bg-color: #f7f9fc;
            --text-main: #1a1c1e;
            --silver-text: linear-gradient(135deg, #1a1a1a 0%, #444444 50%, #1a1a1a 100%);
            --silver-metal: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
            --card-silver: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--bg-color);
            background: radial-gradient(circle at top right, #ffffff 0%, #eef2f7 100%);
            background-attachment: fixed;
            margin: 0; 
            color: var(--text-main); 
            min-height: 100vh;
            overflow-x: hidden;
        }

        ::selection {
            background: rgba(130, 21, 21, 0.2);
            color: var(--primary);
        }

        
        /* Layout */
        .header-wrapper {
            background: linear-gradient(135deg, var(--primary) 0%, #4a0b0b 100%);
            padding: 3.5rem 1.5rem 7rem;
            color: white;
            position: relative;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            box-shadow: 0 15px 40px rgba(130, 21, 21, 0.2);
            margin-bottom: 0;
        }

        .header-content {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }

        .user-greeting {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 0;
        }

        .profile-avatar {
            width: 55px; height: 55px; 
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.3);
            color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; font-weight: 700;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .greeting-text h1 { 
            margin: 0; 
            font-size: 1.6rem; 
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #fff;
        }
        .greeting-text p { margin: 0; font-size: 0.85rem; opacity: 0.7; text-transform: uppercase; letter-spacing: 1px; color: #fff; }

        /* --- 3D FLIP CARD BLACK EDITION --- */
        .vip-card-container {
            perspective: 1500px;
            margin: -4.5rem auto 3rem;
            max-width: 420px;
            width: 92%;
            height: 250px; /* Base height for mobile aspect ratio */
            cursor: pointer;
            z-index: 10;
        }

        .vip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }

        .vip-card-container.is-flipped .vip-card-inner {
            transform: rotateY(180deg);
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            color: white; /* Forzar texto blanco dentro de la tarjeta */
        }

        /* LADO FRONTAL: BLACK METALLIC */
        .card-front {
            background: #0a0a0a;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255,255,255,0.05) 0%, transparent 40%),
                linear-gradient(45deg, #0a0a0a 0%, #1a1a1a 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.8rem;
        }

        /* Efecto de Brillo Silver */
        .card-shine {
            position: absolute;
            top: 0; left: -100%;
            width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            transform: skewX(-25deg);
            animation: cardShineEffect 8s infinite;
        }
        @keyframes cardShineEffect { 0% { left: -150%; } 15% { left: 150%; } 100% { left: 150%; } }

        .card-header { display: flex; justify-content: space-between; align-items: flex-start; }
        .card-logo { height: 26px; filter: brightness(0) invert(1) opacity(0.8); }
        
        .membership-badge {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 2px;
            background: var(--silver-metal);
            color: #111;
            padding: 6px 14px;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .card-middle { text-align: left; }
        .label-small { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom: 4px; }
        .holder-name { font-size: 1.25rem; font-weight: 700; color: #fff; letter-spacing: 1px; }

        .card-footer { display: flex; justify-content: space-between; align-items: flex-end; }
        .client-code { font-family: 'Courier New', Courier, monospace; font-size: 0.9rem; letter-spacing: 2.5px; opacity: 0.6; }
        
        .points-box { text-align: right; }
        .points-val { font-size: 2.4rem; font-weight: 800; line-height: 1; display: block; background: var(--card-silver); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .points-unit { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.6; }

        /* LADO TRASERO: QR & INFO */
        .card-back {
            background: #111;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-image: 
                radial-gradient(circle at center, #1a1a1a 0%, #0a0a0a 100%);
        }

        .qr-container {
            background: white;
            padding: 12px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        #qrcode img { display: block; }
        
        .qr-help { margin-top: 1.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.6; }

        /* Botón Tienda */
        .btn-store {
            background: #111;
            color: #fff; 
            text-decoration: none; 
            padding: 1.2rem 2.5rem; 
            border-radius: 100px;
            display: flex; align-items: center; justify-content: center; gap: 0.8rem;
            font-weight: 800; font-size: 1rem; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            width: 85%;
            max-width: 380px;
            margin: 0 auto 3.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: none;
        }
        .btn-store:hover { background: #000; transform: translateY(-3px); box-shadow: 0 20px 50px rgba(0,0,0,0.2); }

        /* Historial */
        .container { padding: 0 1.5rem 4rem; max-width: 500px; margin: 0 auto; }
        .section-title { font-size: 1rem; font-weight: 700; margin-bottom: 1.2rem; opacity: 0.8; letter-spacing: 1px; }
        .history-card { background: white; border-radius: 2rem; border: 1px solid #eef2f7; box-shadow: 0 10px 30px rgba(0,0,0,0.03); overflow: hidden; }
        .history-item { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f0f4f8; transition: 0.3s; }
        .history-item:active { background: #f8fafd; }
        .history-main-row { display: flex; justify-content: space-between; align-items: center; }
        .item-name { font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 10px; color: #444; }
        .item-name i { color: var(--primary); font-size: 1.1rem; }
        .item-pts { color: var(--text-main); font-weight: 800; font-size: 1.1rem; }
        .history-date { font-size: 0.75rem; opacity: 0.4; margin-top: 4px; padding-left: 28px; }

        .footer { text-align: center; padding: 3rem 0; color: rgba(0,0,0,0.3); font-size: 0.75rem; }
        
        .logout-btn-client {
            position: absolute; top: -0.5rem; right: 0; color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.1); width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(10px);
        }

        .flip-hint { margin-top: -1.5rem; text-align: center; font-size: 0.7rem; opacity: 0.4; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2rem; }
        
        [v-cloak] { display: none; }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <div class="header-content">
            <a href="<?= BASE_URL ?>logout" class="logout-btn-client" title="Cerrar Sesión">
                <i class='bx bx-log-out'></i>
            </a>
            <div class="user-greeting">
                <div class="profile-avatar"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
                <div class="greeting-text">
                    <p>Digital Membership</p>
                    <h1>¡Hola, <?= explode(' ', $cliente['nombre'])[0] ?>!</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- VIP CARD BLACK EDITION (3D FLIP) -->
    <div class="vip-card-container" id="profileCard">
        <div class="vip-card-inner">
            <!-- FRONT SIDE -->
            <div class="card-front">
                <div class="card-shine"></div>
                <div class="card-header">
                    <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" class="card-logo">
                    <span class="membership-badge">ELITE MEMBER</span>
                </div>
                <div class="card-middle">
                    <div class="label-small">Titular de Cuenta</div>
                    <div class="holder-name"><?= htmlspecialchars($cliente['nombre']) ?></div>
                </div>
                <div class="card-footer">
                    <div class="client-code"><?= htmlspecialchars($cliente['codigo']) ?></div>
                    <div class="points-box">
                        <span class="label-small">Saldo Actual</span>
                        <b class="points-val" id="points-counter">0</b>
                        <span class="points-unit">pts surgas</span>
                    </div>
                </div>
            </div>

            <!-- BACK SIDE -->
            <div class="card-back">
                <div class="qr-container">
                    <div id="qrcode"></div>
                </div>
                <div class="qr-help">Muestra para acumular</div>
            </div>
        </div>
    </div>

    <div class="flip-hint"><i class='bx bx-refresh'></i> Toca la tarjeta para ver tu QR</div>

    <a href="<?= BASE_URL ?>tienda" class="btn-store">
        <i class='bx bxs-shopping-bag'></i> Ir a la tienda
    </a>

    <div class="container">
        <div class="section-title">Actividad Reciente</div>
        <div class="history-card">
            <?php if (empty($ventas)): ?>
                <div style="padding: 3rem 2rem; text-align: center; opacity: 0.3; font-size: 0.9rem;">No hay movimientos recientes.</div>
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
            &copy; <?= date('Y') ?> Surgas — Premium Digital Member Card
        </div>
    </div>

    <script>
        const cardContainer = document.getElementById('profileCard');
        cardContainer.addEventListener('click', () => {
            cardContainer.classList.toggle('is-flipped');
        });

        // Generar QR en el reverso
        const qrContent = '<?= BASE_URL ?>scan?c=<?= urlencode($cliente['codigo']) ?>&t=<?= urlencode($cliente['token']) ?>';
        new QRCode(document.getElementById("qrcode"), {
            text: qrContent,
            width: 150,
            height: 150,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        // Points Animation
        const pointsTarget = <?= (int) ($cliente['puntos'] ?? 0) ?>;
        const pointsElement = document.getElementById('points-counter');
        let currentPoints = 0;
        const duration = 1500;
        const steps = 60;
        const interval = duration / steps;
        
        const counterTimer = setInterval(() => {
            currentPoints += pointsTarget / steps;
            if (currentPoints >= pointsTarget) {
                pointsElement.textContent = Math.floor(pointsTarget).toLocaleString();
                clearInterval(counterTimer);
            } else {
                pointsElement.textContent = Math.floor(currentPoints).toLocaleString();
            }
        }, interval);
    </script>
    <script> const BASE_URL = '<?= BASE_URL ?>'; </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
