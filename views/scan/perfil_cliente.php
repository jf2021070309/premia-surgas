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
            --secondary: #2e7d32; 
            --bg-page: #f8f9fa;
            --surface: #ffffff;
            --text-main: #191c1d;
            --text-muted: #424751;
            --sidebar-w: 260px;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.02);
            --shadow-md: 0 10px 30px rgba(0,0,0,0.04);
        }
        body { 
            font-family: 'Manrope', sans-serif; 
            background: var(--bg-page);
            margin: 0; 
            color: var(--text-main); 
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* --- DASHBOARD LAYOUT --- */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--primary-dark);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex; flex-direction: column;
            color: white;
            z-index: 100;
        }
        .sidebar-header { padding: 2.5rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); text-align: center; }
        .sidebar-logo { width: 50px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.1); margin-bottom: 1rem; }
        .sidebar-nav { padding: 2rem 0; flex-grow: 1; }
        .nav-link { 
            display: flex; align-items: center; gap: 15px;
            padding: 1rem 1.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-weight: 700; font-size: 0.9rem;
            transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.05); }
        .nav-link i { font-size: 1.2rem; }

        .sidebar-footer { padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); }
        .btn-logout { 
            display: flex; align-items: center; gap: 10px;
            color: #ff9999; text-decoration: none; font-weight: 700; font-size: 0.85rem;
        }

        .main-content {
            flex-grow: 1;
            margin-left: var(--sidebar-w);
            padding: 2.5rem;
            max-width: 1400px;
        }

        /* --- TOP BAR --- */
        .top-bar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 3rem;
        }
        .page-title { margin: 0; font-size: 1.8rem; font-weight: 800; color: var(--primary-dark); }
        .user-pill { 
            display: flex; align-items: center; gap: 12px; 
            background: white; padding: 8px 16px; border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }
        .pill-name { font-size: 0.9rem; font-weight: 800; color: var(--text-main); }

        /* --- PROFILE HEADER CARD (DASHBOARD STYLE) --- */
        .profile-board {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
        }
        .board-info { display: flex; align-items: center; gap: 2.5rem; }
        .board-avatar {
            width: 120px; height: 120px;
            background: #f0f4f8; border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 3rem; font-weight: 800; color: var(--primary);
        }
        .board-text h2 { margin: 0; font-size: 2.2rem; font-weight: 800; color: var(--primary-dark); }
        .board-text p { margin: 5px 0 0; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; }
        .badge-platinum { 
            margin-top: 15px; display: inline-block;
            background: #eef2f7; color: var(--primary);
            padding: 6px 14px; border-radius: 100px;
            font-size: 0.75rem; font-weight: 800; letter-spacing: 1px;
        }

        /* 3D CARD ON THE RIGHT */
        .board-right { position: relative; z-index: 10; }

        /* --- STATS ROW --- */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 2.5rem;
        }
        .stat-card {
            background: white; padding: 2rem; border-radius: 24px;
            box-shadow: var(--shadow-sm);
            display: flex; flex-direction: column; gap: 10px;
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
        .stat-card .label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; }
        .stat-card .value { font-size: 2.5rem; font-weight: 800; color: var(--primary-dark); }
        .stat-card .trend { font-size: 0.8rem; font-weight: 700; color: var(--secondary); }

        /* Mobile Adjustments */
        @media (max-width: 1100px) {
            .profile-board { flex-direction: column; text-align: center; gap: 2rem; }
            .board-info { flex-direction: column; gap: 1.5rem; }
            .stats-row { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 1.5rem; }
            .board-text h2 { font-size: 1.6rem; }
            .vip-card-container { transform: scale(0.8); }
        }

                .vip-card-container { width: 340px; height: 200px; perspective: 1000px; cursor: pointer; }
        .vip-card-inner { position: relative; width: 100%; height: 100%; text-align: left; transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform-style: preserve-3d; }
        .is-flipped .vip-card-inner { transform: rotateY(180deg); }
        .card-front, .card-back { position: absolute; width: 100%; height: 100%; -webkit-backface-visibility: hidden; backface-visibility: hidden; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,63,131,0.1); border: 1px solid rgba(0,0,0,0.02); }
        .card-front { background: linear-gradient(135deg, #1e57a3 0%, #003f83 100%); color: #fff; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; }
        .label-v { font-size: 0.55rem; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.5); font-weight: 700; }
        .holder-v { font-size: 1.1rem; font-weight: 800; color: #fff; text-transform: uppercase; margin-top: 4px; }
        .card-back { background: #fff; transform: rotateY(180deg); display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .qr-container { background: #fff; padding: 10px; border-radius: 12px; }
        .qr-help { margin-top: 10px; font-size: 0.65rem; font-weight: 800; color: var(--primary); opacity: 0.6; }
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
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class='bx bxs-user-circle' style="font-size: 2.5rem;"></i>
            </div>
            <div style="font-weight: 800; font-size: 0.8rem; letter-spacing: 1px;">SURGAS PRESTIGE</div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-link active"><i class='bx bxs-dashboard'></i> Dashboard</a>
            <a href="<?= BASE_URL ?>tienda" class="nav-link"><i class='bx bxs-gift'></i> Rewards</a>
            <a href="#" class="nav-link"><i class='bx bx-history'></i> History</a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>logout" class="btn-logout">
                <i class='bx bx-log-out'></i> Salir
            </a>
        </div>
    </aside>

    <!-- CONTENT -->
    <?php $pts = (int)($cliente['puntos'] ?? 0); ?>
    <main class="main-content">
        <header class="top-bar">
            <h1 class="page-title">Client Dashboard</h1>
            <div class="user-pill">
                <div style="width: 24px; height: 24px; background: #eef2f7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; color: var(--primary);">
                    <?= substr($cliente['nombre'], 0, 1) ?>
                </div>
                <span class="pill-name"><?= explode(' ', $cliente['nombre'])[0] ?></span>
            </div>
        </header>

        <!-- PROFILE BOARD (WIDE CARD) -->
        <section class="profile-board">
            <div class="board-info">
                <div class="board-avatar">
                   <?= strtoupper(substr($cliente['nombre'], 0, 1)) ?>
                </div>
                <div class="board-text">
                    <h2><?= strtoupper($cliente['nombre']) ?></h2>
                    <p>Executive Membership</p>
                    <span class="badge-platinum"><?= ($pts >= 2500) ? "PLATINUM STATUS" : (($pts >= 1000) ? "GOLD STATUS" : "SILVER STATUS") ?></span>
                </div>
            </div>

            <!-- RIGHT SIDE 3D CARD -->
            <div class="board-right">
                <div class="vip-card-container" id="profileCard">
                    <div class="vip-card-inner">
                        <div class="card-front">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="font-size: 0.7rem; font-weight: 800; letter-spacing: 1.5px;">PRESTIGE ID</span>
                                <i class='bx bxs-chip' style="font-size: 1.5rem; opacity: 0.5;"></i>
                            </div>
                            <div>
                                <div class="label-v">Titular Name</div>
                                <div class="holder-v"><?= htmlspecialchars($cliente['nombre']) ?></div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                <span style="font-family: monospace; font-size: 0.7rem; opacity: 0.5;"><?= htmlspecialchars($cliente['codigo']) ?></span>
                                <i class='bx bx-fingerprint' style="font-size: 1.4rem; opacity: 0.3;"></i>
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
        </section>

        <!-- STATS ROW -->
        <div class="stats-row">
            <div class="stat-card">
                <span class="label">Points Balance</span>
                <b class="value" id="points-counter">0</b>
                <span class="trend"><i class='bx bx-trending-up'></i> +120 hoy</span>
            </div>
            <div class="stat-card">
                <span class="label">Tier Status</span>
                <?php 
                    $p_now = $pts;
                    $p_max = ($pts >= 2500) ? 5000 : (($pts >= 1000) ? 2500 : 1000);
                    $perc = ($p_now / $p_max) * 100;
                ?>
                <b class="value"><?= number_format($perc, 1) ?>%</b>
                <span class="label" style="font-size: 0.65rem;">Hacia el siguiente nivel</span>
            </div>
            <div class="stat-card">
                <span class="label">Recent Activity</span>
                <b class="value" style="font-size: 1.2rem; margin-top: 10px;">Carga de Gasolina</b>
                <span class="label" style="font-size: 0.65rem;">Hace 2 horas</span>
            </div>
        </div>

        <div style="text-align: center; margin-top: 4rem; font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">
            Surgas Dashboard &bull; Digital Ecosystem &bull; <?= date('Y') ?>
        </div>
    </main>


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
