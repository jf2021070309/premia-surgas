<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        :root { 
            --primary: #4a0404; 
            --primary-light: #7e2b23;
            --secondary: #735c00; 
            --accent-gold: #d4af37;
            --bg-page: #faf9f8;
            --surface: #ffffff;
            --surface-low: #f4f3f2;
            --text-main: #1a1c1c;
            --text-muted: #554240;
            --silver-metal: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
            --card-silver: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
            --shadow-premium: 0 12px 40px rgba(0, 0, 0, 0.08);
            --shadow-ambient: 0 4px 20px rgba(74, 4, 4, 0.04);
        }
        body { 
            font-family: 'Manrope', sans-serif; 
            background: var(--bg-page);
            margin: 0; 
            color: var(--text-main); 
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        @keyframes reveal {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .reveal { animation: reveal 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        
        /* Sovereign Layout */
        .page-wrapper {
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            padding-bottom: 5rem;
        }

        .main-header {
            padding: 1.5rem 1.5rem 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand-logo { height: 28px; filter: brightness(0.2); }
        .btn-exit { 
            width: 42px; height: 42px; 
            background: var(--surface);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            box-shadow: var(--shadow-ambient);
            border: 1px solid var(--surface-low);
            text-decoration: none;
            transition: 0.3s;
        }
        .btn-exit:hover { background: var(--primary); color: #fff; }

        /* Card Hero Section */
        .card-hero-wrapper {
            padding: 0 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Greeting Section */
        .welcome-sec {
            padding: 0 1.5rem;
            text-align: center;
        }
        .welcome-sec .label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem; }
        .welcome-sec h1 { font-size: 1.8rem; font-weight: 800; margin: 0; color: var(--primary); line-height: 1.2; }

        /* Points Sovereign Card */
        .points-hub {
            margin: 0 1.5rem;
            background: var(--surface);
            padding: 2.2rem 1.5rem;
            border-radius: 32px;
            box-shadow: var(--shadow-premium);
            text-align: center;
            border: 1px solid rgba(212, 175, 55, 0.1);
            position: relative;
            overflow: hidden;
        }
        .points-hub::after {
            content: ''; position: absolute; top: 0; right: 0; width: 100px; height: 100px;
            background: radial-gradient(circle at 100% 0%, rgba(212, 175, 55, 0.05) 0%, transparent 70%);
        }
        .points-hub .label { font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 3px; display: block; margin-bottom: 1rem; }
        .points-val-big {
            font-size: 4rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
            letter-spacing: -2px;
            display: block;
        }
        .points-label-surgas { font-size: 0.85rem; font-weight: 800; color: var(--accent-gold); text-transform: uppercase; letter-spacing: 5px; margin-top: 5px; display: block; }

        /* Journey Progress Section */
        .journey-sec {
            padding: 0 1.5rem;
        }
        .journey-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.2rem; }
        .tier-tag { font-size: 0.8rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; }
        .tier-next { font-size: 0.75rem; color: var(--accent-gold); font-weight: 800; }
        
        .prog-bar {
            height: 10px; background: var(--surface-low); border-radius: 100px; overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
            position: relative;
        }
        .prog-fill {
            height: 100%; height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 100px;
            transition: width 2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .journey-msg { margin-top: 1rem; font-size: 0.75rem; color: var(--text-muted); text-align: center; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

        /* Quick Action Grid */
        .action-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            padding: 0 1.5rem;
        }
        .action-btn {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: 24px;
            text-decoration: none;
            display: flex; flex-direction: column; align-items: center; gap: 12px;
            box-shadow: var(--shadow-ambient);
            border: 1px solid var(--surface-low);
            transition: 0.3s;
        }
        .action-btn:hover { transform: translateY(-3px); box-shadow: var(--shadow-premium); border-color: var(--accent-gold); }
        .action-btn i { font-size: 1.8rem; color: var(--primary); }
        .action-btn span { font-size: 0.8rem; font-weight: 800; color: var(--text-main); text-transform: uppercase; letter-spacing: 1px; }

        /* Footer */
        .page-footer { text-align: center; padding: 2rem 0; color: var(--text-muted); font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5; }

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
        .points-box { display: none !important; }
        .qr-help { margin-top: 1.5rem; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); font-weight: 800; }

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

        .vip-card-container { width: 340px; height: 200px; perspective: 1000px; cursor: pointer; margin: 0 auto; position: relative; z-index: 10; transform: scale(0.95); transition: 0.4s; }
        .vip-card-container:hover { transform: scale(1) translateY(-5px); }
        .vip-card-inner { position: relative; width: 100%; height: 100%; text-align: left; transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform-style: preserve-3d; }
        .is-flipped .vip-card-inner { transform: rotateY(180deg); }
        .card-front, .card-back { position: absolute; width: 100%; height: 100%; -webkit-backface-visibility: hidden; backface-visibility: hidden; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.12); border: 1px solid rgba(0,0,0,0.05); }
        .card-front { background: linear-gradient(135deg, #1a1c1c 0%, #000 100%); color: #fff; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; }
        .membership-badge { font-size: 0.6rem; font-weight: 800; letter-spacing: 2px; color: var(--accent-gold); border: 1px solid rgba(212, 175, 55, 0.3); padding: 4px 10px; border-radius: 6px; }
        .label-small { font-size: 0.55rem; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.4); font-weight: 700; margin-bottom: 4px; }
        .holder-name { font-size: 1.1rem; font-weight: 800; color: #fff; text-transform: uppercase; }
        .card-back { background: #fff; transform: rotateY(180deg); display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .qr-container { background: #fff; padding: 12px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        
        [v-cloak] { display: none; }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <!-- HEADER -->
        <header class="main-header reveal">
            <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" class="brand-logo">
            <a href="<?= BASE_URL ?>logout" class="btn-exit" title="Salir">
                <i class='bx bx-power-off'></i>
            </a>
        </header>

        <!-- 3D CARD (TOP) -->
        <div class="card-hero-wrapper reveal delay-1">
            <div class="vip-card-container" id="profileCard">
                <div class="vip-card-inner">
                    <div class="card-front">
                        <div class="card-header">
                            <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" style="width: 40px; border-radius: 4px; filter: invert(1);">
                            <span class="membership-badge">VIP ELITE</span>
                        </div>
                        <div class="card-middle">
                            <div class="label-small">Titular Membership</div>
                            <div class="holder-name"><?= htmlspecialchars($cliente['nombre']) ?></div>
                        </div>
                        <div class="card-footer" style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div style="font-family: monospace; font-size: 0.8rem; opacity: 0.4;"><?= htmlspecialchars($cliente['codigo']) ?></div>
                            <i class='bx bx-rotate-right' style="font-size: 1.2rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <div class="card-back">
                        <div class="qr-container">
                            <div id="qrcode"></div>
                        </div>
                        <div class="qr-help">MUESTRA PARA ACUMULAR</div>
                    </div>
                </div>
            </div>
            <p style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); margin-top: 1rem; text-transform: uppercase; letter-spacing: 2px;">Toca la tarjeta para ver tu QR</p>
        </div>

        <!-- GREETING -->
        <div class="welcome-sec reveal delay-1">
            <span class="label">Bienvenido de nuevo</span>
            <h1>Hola, <?= explode(' ', $cliente['nombre'])[0] ?></h1>
        </div>

        <!-- POINTS HUB -->
        <div class="points-hub reveal delay-2">
            <span class="label">Puntos Disponibles</span>
            <b class="points-val-big" id="points-counter">0</b>
            <span class="points-label-surgas">Puntos Surgas</span>
        </div>

        <!-- JOURNEY PROGRESS -->
        <?php 
            $pts = (int)($cliente['puntos'] ?? 0);
            $next_tier_pts = 1000;
            $tier_name = "Silver";
            $next_name = "Gold";
            if ($pts >= 1000) { $next_tier_pts = 2500; $tier_name = "Gold"; $next_name = "Platinum"; }
            if ($pts >= 2500) { $next_tier_pts = 5000; $tier_name = "Platinum"; $next_name = "Onyx"; }
            $prog = min(100, ($pts / $next_tier_pts) * 100);
            $missing = max(0, $next_tier_pts - $pts);
        ?>
        <div class="journey-sec reveal delay-2">
            <div class="journey-header">
                <span class="tier-tag">Nivel <?= $tier_name ?></span>
                <span class="tier-next">Siguiente: <?= $next_name ?></span>
            </div>
            <div class="prog-bar">
                <div class="prog-fill" style="width: <?= $prog ?>%"></div>
            </div>
            <div class="journey-msg">Te faltan <?= number_format($missing) ?> puntos para <?= $next_name ?></div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="action-row reveal delay-3">
            <a href="<?= BASE_URL ?>tienda" class="action-btn">
                <i class='bx bxs-gift'></i>
                <span>Premios</span>
            </a>
            <a href="#" class="action-btn">
                <i class='bx bx-history'></i>
                <span>Historial</span>
            </a>
        </div>

        <div class="page-footer">
            Surgas Heritage &bull; <?= date('Y') ?>
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
