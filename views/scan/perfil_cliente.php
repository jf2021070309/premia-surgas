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
            --primary: #1e57a3; 
            --primary-dark: #003f83;
            --secondary: #78c02c; 
            --bg-page: #f8f9fa;
            --surface: #ffffff;
            --text-main: #191c1d;
            --text-muted: #555c66;
            --shadow-card: 0 10px 30px rgba(0, 0, 0, 0.05);
            --shadow-header: 0 4px 15px rgba(30, 87, 163, 0.2);
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

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-dash { animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        
        /* Dashboard Architecture */
        .dashboard-header {
            background: var(--primary);
            padding: 3rem 1.5rem 5rem;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }
        .profile-pic {
            width: 80px; height: 80px;
            background: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem; font-weight: 800; color: var(--primary);
            margin-bottom: 1rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border: 4px solid rgba(255,255,255,0.2);
        }
        .user-title { font-size: 1.4rem; font-weight: 800; margin: 0; letter-spacing: -0.5px; }
        .user-role { font-size: 0.8rem; opacity: 0.7; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }
        
        .dash-exit {
            position: absolute; top: 1.5rem; right: 1.5rem;
            color: white; font-size: 1.4rem; text-decoration: none;
            opacity: 0.8; transition: 0.3s;
        }
        .dash-exit:hover { opacity: 1; transform: scale(1.1); }

        /* Overlapping Stats Card */
        .stats-card {
            background: var(--surface);
            margin: -2.5rem 1.5rem 0;
            padding: 2rem 1rem;
            border-radius: 20px;
            box-shadow: var(--shadow-card);
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
            z-index: 5;
        }
        .stat-item { text-align: center; position: relative; }
        .stat-item:first-child::after {
            content: ''; position: absolute; right: 0; top: 20%; height: 60%; width: 1px; background: #eee;
        }
        .stat-val { font-size: 2rem; font-weight: 800; color: var(--primary); display: block; line-height: 1; }
        .stat-label { font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 8px; display: block; }
        .tier-val { color: var(--secondary); text-transform: uppercase; }

        /* Action Grid */
        .dash-grid {
            padding: 2rem 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }
        .dash-btn {
            background: var(--surface);
            padding: 1.8rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            display: flex; flex-direction: column; align-items: center; gap: 15px;
            box-shadow: var(--shadow-card);
            transition: 0.3s;
            border-bottom: 3px solid transparent;
        }
        .dash-btn:hover { transform: translateY(-5px); border-bottom-color: var(--secondary); }
        .dash-btn i { font-size: 2.2rem; color: var(--primary); }
        .dash-btn span { font-size: 0.85rem; font-weight: 800; color: var(--text-main); text-transform: uppercase; }
        .btn-green { background: #f0fdf4; }
        .btn-green i { color: var(--secondary); }

        /* Card Section */
        .dash-card-sec {
            padding: 0 1.5rem 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .section-tag { align-self: flex-start; margin-bottom: 1.2rem; font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; }

        .dash-footer { text-align: center; padding: 2rem 0; font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }

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

        .vip-card-container { width: 340px; height: 200px; perspective: 1000px; cursor: pointer; margin: 0 auto; position: relative; z-index: 10; transform: scale(0.9); }
        .vip-card-inner { position: relative; width: 100%; height: 100%; text-align: left; transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform-style: preserve-3d; }
        .is-flipped .vip-card-inner { transform: rotateY(180deg); }
        .card-front, .card-back { position: absolute; width: 100%; height: 100%; -webkit-backface-visibility: hidden; backface-visibility: hidden; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.05); }
        .card-front { background: linear-gradient(135deg, #1e57a3 0%, #003f83 100%); color: #fff; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; }
        .membership-badge { font-size: 0.6rem; font-weight: 800; letter-spacing: 2px; color: #fff; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 6px; }
        .label-small { font-size: 0.55rem; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.5); font-weight: 700; margin-bottom: 4px; }
        .holder-name { font-size: 1.1rem; font-weight: 800; color: #fff; text-transform: uppercase; }
        .card-back { background: #fff; transform: rotateY(180deg); display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .qr-container { background: #fff; padding: 12px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .qr-help { margin-top: 1rem; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); font-weight: 800; }
        
        [v-cloak] { display: none; }
    </style>
</head>
<body>
    <div class="dash-wrapper">
        <!-- HEADER (BLUE) -->
        <header class="dashboard-header">
            <a href="<?= BASE_URL ?>logout" class="dash-exit" title="Salir">
                <i class='bx bx-log-out'></i>
            </a>
            <div class="profile-pic">
                <?= strtoupper(substr($cliente['nombre'], 0, 1)) ?>
            </div>
            <h1 class="user-title"><?= strtoupper($cliente['nombre']) ?></h1>
            <span class="user-role">Member since 2018</span>
        </header>

        <!-- OVERLAPPING STATS CARD -->
        <div class="stats-card animate-dash delay-1">
            <div class="stat-item">
                <b class="stat-val" id="points-counter">0</b>
                <span class="stat-label">Points Balance</span>
            </div>
            <div class="stat-item">
                <?php 
                    $pts = (int)($cliente['puntos'] ?? 0);
                    $tier = ($pts >= 2500) ? "Platinum" : (($pts >= 1000) ? "Gold" : "Silver");
                ?>
                <b class="stat-val tier-val"><?= $tier ?></b>
                <span class="stat-label">Current Tier</span>
            </div>
        </div>

        <!-- DASH ACTION GRID -->
        <div class="dash-grid animate-dash delay-2">
            <a href="<?= BASE_URL ?>tienda" class="dash-btn btn-green">
                <i class='bx bxs-gift'></i>
                <span>Rewards</span>
            </a>
            <a href="#" class="dash-btn">
                <i class='bx bx-history'></i>
                <span>History</span>
            </a>
        </div>

        <!-- 3D VIP ID SECTION -->
        <div class="dash-card-sec animate-dash delay-3">
            <span class="section-tag">Digital VIP Card</span>
            <div class="vip-card-container" id="profileCard">
                <div class="vip-card-inner">
                    <div class="card-front">
                        <div class="card-header">
                            <span style="font-size: 0.8rem; font-weight: 800; letter-spacing: 2px;">SURGAS PRESTIGE</span>
                            <span class="membership-badge">VIP ELITE</span>
                        </div>
                        <div class="card-middle">
                            <div class="label-small">Titular Membership</div>
                            <div class="holder-name"><?= htmlspecialchars($cliente['nombre']) ?></div>
                        </div>
                        <div class="card-footer" style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div style="font-family: monospace; font-size: 0.8rem; opacity: 0.5;"><?= htmlspecialchars($cliente['codigo']) ?></div>
                            <i class='bx bx-landscape' style="font-size: 1.2rem; opacity: 0.5;"></i>
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
        </div>

        <div class="dash-footer">
            Azure Corporate &bull; Digital Dashboard &bull; <?= date('Y') ?>
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
