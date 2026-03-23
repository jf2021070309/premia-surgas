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
            --primary: #520404; 
            --primary-glow: #8b0c0c;
            --bg-obsidian: #050505;
            --surface-card: #0f0f0f;
            --text-silver: #e2e2e2;
            --text-muted: #888888;
            --accent-gold: #c5a059;
            --silver-metal: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
            --card-silver: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
            --shadow-glow: 0 0 30px rgba(139, 12, 12, 0.15);
        }
        body { 
            font-family: 'Manrope', sans-serif; 
            background: var(--bg-obsidian);
            margin: 0; 
            color: var(--text-silver); 
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-section { animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.3s; }
        .delay-3 { animation-delay: 0.45s; }

        
        /* Luxury Dashboard Components */
        .dashboard-container {
            padding: 1.5rem 1.25rem 4rem;
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .greeting-label { font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 2px; display: block; }
        .user-name { font-size: 1.25rem; font-weight: 800; color: #fff; margin: 0; }
        
        .logout-pill {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            color: var(--text-silver);
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 800;
            text-decoration: none;
            display: flex; align-items: center; gap: 6px;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .logout-pill:hover { background: var(--primary); border-color: var(--primary-glow); }

        /* Points Hero (Futuristic) */
        .points-hero {
            text-align: center;
            padding: 2.5rem 1rem;
            background: radial-gradient(circle at 50% 50%, rgba(139, 12, 12, 0.1) 0%, transparent 80%);
            position: relative;
        }
        .points-hero .label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 4px; display: block; margin-bottom: 0.8rem; }
        .points-display {
            font-size: 5.5rem;
            font-weight: 900;
            color: #fff;
            line-height: 0.9;
            letter-spacing: -3px;
            background: linear-gradient(180deg, #ffffff 0%, #777777 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.5));
        }
        .points-suffix { font-size: 0.8rem; font-weight: 900; color: var(--accent-gold); text-transform: uppercase; letter-spacing: 6px; display: block; margin-top: 10px; opacity: 0.8; }

        /* Tier Journey (Modern) */
        .tier-journey {
            background: linear-gradient(145deg, #0a0a0a 0%, #050505 100%);
            padding: 2rem;
            border-radius: 32px;
            border: 1px solid rgba(255,255,255,0.02);
            position: relative;
            overflow: hidden;
        }
        .tier-journey::before {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle at 30% 30%, rgba(139, 12, 12, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        .tier-info { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.5rem; }
        .tier-title { font-size: 0.9rem; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 1px; }
        .next-tier { font-size: 0.75rem; color: var(--accent-gold); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        
        .progress-track {
            height: 4px; background: #111; border-radius: 10px; overflow: visible;
            position: relative;
        }
        .progress-fill {
            height: 100%; height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-glow) 100%);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(139, 12, 12, 0.4);
            position: relative;
        }
        .progress-fill::after {
            content: ''; position: absolute; right: -4px; top: 50%; transform: translateY(-50%);
            width: 8px; height: 8px; background: #fff; border-radius: 50%;
            box-shadow: 0 0 10px #fff;
        }
        .journey-footer { margin-top: 1.5rem; font-size: 0.7rem; color: var(--text-muted); text-align: center; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

        /* Actions Grid */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        .action-card {
            background: #0d0d0d;
            padding: 1.8rem 1rem;
            border-radius: 28px;
            text-decoration: none;
            display: flex; flex-direction: column; align-items: center; gap: 15px;
            border: 1px solid rgba(255,255,255,0.02);
            transition: all 0.4s ease;
        }
        .action-card:hover { background: #121212; border-color: rgba(139, 12, 12, 0.3); transform: scale(1.02); }
        .action-card i { font-size: 2rem; color: var(--text-silver); }
        .action-card span { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; }
        .action-card:hover span { color: #fff; }

        /* VIP ID SECTION */
        .vip-id-section {
            background: linear-gradient(180deg, transparent 0%, #000 100%);
            padding: 2rem 0 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-top: 1px solid rgba(255,255,255,0.03);
        }
        .vip-id-label { font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 6px; margin-bottom: 2.5rem; }

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

        .footer { text-align: center; padding: 2rem 0 5rem; color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.3; }
        
        .points-box { display: none !important; } /* Hidden in card since it's hero now */
        
        [v-cloak] { display: none; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <!-- TOP BAR -->
        <div class="top-bar animate-section">
            <div>
                <span class="greeting-label">Membership status</span>
                <h1 class="user-name">Hola, <?= explode(' ', $cliente['nombre'])[0] ?></h1>
            </div>
            <a href="<?= BASE_URL ?>logout" class="logout-pill">
                <i class='bx bx-power-off'></i> Salir
            </a>
        </div>

        <!-- POINTS HERO -->
        <div class="points-hero animate-section delay-1">
            <span class="label">Balance Disponible</span>
            <b class="points-display" id="points-counter">0</b>
            <span class="points-suffix">Puntos Surgas</span>
        </div>

        <!-- TIER JOURNEY -->
        <?php 
            $puntos_actuales = (int)($cliente['puntos'] ?? 0);
            $tier_max = 5000;
            if ($puntos_actuales < 1000) { $tier_max = 1000; $tier_now = "Silver"; $tier_next = "Gold"; }
            elseif ($puntos_actuales < 2500) { $tier_max = 2500; $tier_now = "Gold"; $tier_next = "Platinum"; }
            else { $tier_max = 5000; $tier_now = "Platinum"; $tier_next = "Onyx"; }
            $prog_journey = min(100, ($puntos_actuales / $tier_max) * 100);
        ?>
        <div class="tier-journey animate-section delay-2">
            <div class="tier-info">
                <span class="tier-title"><i class='bx bxs-crown'></i> Nivel <?= $tier_now ?></span>
                <span class="next-tier">Siguiente: <?= $tier_next ?></span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" style="width: <?= $prog_journey ?>%"></div>
            </div>
            <div class="journey-footer">
                Faltan <?= number_format($tier_max - $puntos_actuales) ?> puntos para <?= $tier_next ?>
            </div>
        </div>

        <!-- ACTIONS GRID -->
        <div class="actions-grid animate-section delay-3">
            <a href="<?= BASE_URL ?>tienda" class="action-card">
                <i class='bx bxs-shopping-bags'></i>
                <span>Premios</span>
            </a>
            <a href="#" class="action-card">
                <i class='bx bx-history'></i>
                <span>Historial</span>
            </a>
        </div>
    </div>

    <!-- VIP ID SECTION (BOTTOM) -->
    <div class="vip-id-section animate-section delay-3">
        <span class="vip-id-label">Digital VIP ID</span>

    <div class="vip-card-container" id="profileCard" style="margin-top: 0; transform: scale(0.95);">
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

        <div class="footer">
            Surgas &bull; Prestige Digital ID &bull; <?= date('Y') ?>
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
