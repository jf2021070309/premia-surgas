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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        :root { 
            --primary: #821515; 
            --primary-dark: #5f0005;
            --bg-page: #f8f9fa;
            --surface: #ffffff;
            --text-main: #191c1d;
            --text-muted: #58413f;
            --sidebar-w: 260px;
            --shadow-sm: 0 4px 15px rgba(130, 21, 21, 0.05);
            --shadow-lg: 0 20px 50px rgba(0,0,0,0.1);
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--bg-page);
            margin: 0; 
            color: var(--text-main); 
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* --- DASHBOARD ARCHITECTURE --- */
        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex; flex-direction: column;
            color: white;
            z-index: 100;
            box-shadow: 10px 0 30px rgba(0,0,0,0.05);
        }
        .sidebar-header { padding: 3rem 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-logo { width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.8rem; font-weight: 800; border: 2px solid rgba(255,255,255,0.2); }
        
        .nav-menu { padding: 2rem 0; flex-grow: 1; }
        .nav-item { 
            display: flex; align-items: center; gap: 15px; 
            padding: 1rem 2rem; color: rgba(255,255,255,0.7); 
            text-decoration: none; font-weight: 600; font-size: 0.95rem;
            transition: 0.3s;
        }
        .nav-item:hover, .nav-item.active { color: white; background: rgba(255,255,255,0.05); border-left: 4px solid white; }
        .nav-item i { font-size: 1.4rem; }

        .sidebar-foot { padding: 2rem; border-top: 1px solid rgba(255,255,255,0.05); }
        .btn-exit { display: flex; align-items: center; gap: 10px; color: #ffb4ac; text-decoration: none; font-weight: 700; font-size: 0.9rem; }

        .main-workspace {
            flex-grow: 1;
            margin-left: var(--sidebar-w);
            padding: 3rem;
            max-width: 1300px;
        }

        /* TOP BAR */
        .top-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; }
        .page-heading { margin: 0; font-size: 2rem; font-weight: 800; letter-spacing: -1px; color: var(--primary-dark); }
        .user-chip { display: flex; align-items: center; gap: 12px; background: white; padding: 8px 20px; border-radius: 100px; box-shadow: var(--shadow-sm); font-weight: 700; font-size: 0.9rem; }

        /* DASHBOARD CARDS */
        .hero-board {
            background: white; border-radius: 30px; padding: 3rem;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: var(--shadow-lg); margin-bottom: 3rem;
            position: relative; overflow: hidden;
        }
        .hero-board::before { content: ''; position: absolute; top: 0; left: 0; width: 6px; height: 100%; background: var(--primary); }

        .hero-info h2 { margin: 0; font-size: 2.5rem; font-weight: 800; color: var(--primary-dark); line-height: 1; }
        .hero-info p { margin: 10px 0 0; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; }
        .data-grid { display: flex; gap: 3rem; margin-top: 2rem; }
        .data-point .lbl { font-size: 0.7rem; font-weight: 800; color: #aaa; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 5px; }
        .data-point .val { font-size: 1.1rem; font-weight: 700; color: var(--text-main); }

        /* VIP CARD POSITIONED RIGHT */
        .hero-right { position: relative; z-index: 10; transform: scale(1.05); }

        /* RECENT ACTIVITY SECTION */
        .activity-sec { margin-top: 3rem; }
        .sec-header { font-size: 1.1rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--primary-dark); }
        .activity-list { background: white; border-radius: 25px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid #f0f0f0; }
        .activity-row { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 1.5rem 2rem; border-bottom: 1px solid #f8f9fa;
            transition: 0.3s;
        }
        .activity-row:hover { background: #fffcfc; }
        .act-icon { width: 45px; height: 45px; background: #fff0f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: var(--primary); }
        .act-info { flex-grow: 1; padding: 0 1.5rem; }
        .act-title { font-weight: 700; font-size: 0.95rem; display: block; }
        .act-date { font-size: 0.75rem; color: #aaa; font-weight: 600; }
        .act-pts { font-weight: 800; font-size: 1.1rem; color: var(--primary); }

        /* 3D CARD OVERRIDE (S PRESTIGE STYLE) */
        .vip-card-container { width: 340px; height: 200px; perspective: 1000px; cursor: pointer; }
        .vip-card-inner { position: relative; width: 100%; height: 100%; transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform-style: preserve-3d; }
        .is-flipped .vip-card-inner { transform: rotateY(180deg); }
        .card-front, .card-back { position: absolute; width: 100%; height: 100%; -webkit-backface-visibility: hidden; backface-visibility: hidden; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); }
        .card-front { background: #000; color: #fff; padding: 1.8rem; display: flex; flex-direction: column; justify-content: space-between; }
        .pts-card-box { margin-top: 10px; }
        .pts-card-lbl { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.4); font-weight: 800; display: block; margin-bottom: 4px; }
        .pts-card-val { font-size: 2.2rem; font-weight: 800; color: #fff; line-height: 1; letter-spacing: -1px; }
        .chip-icon { position: absolute; top: 1.5rem; right: 1.5rem; font-size: 1.8rem; opacity: 0.3; }
        .fingerprint-icon { position: absolute; bottom: 1.5rem; right: 1.5rem; font-size: 1.5rem; opacity: 0.2; }
        
        @media (max-width: 1000px) {
            .sidebar { display: none; }
            .main-workspace { margin-left: 0; padding: 1.5rem; }
            .hero-board { flex-direction: column; text-align: center; gap: 3rem; padding: 2.5rem 1.5rem; }
            .data-grid { justify-content: center; gap: 1.5rem; }
            .hero-info h2 { font-size: 1.8rem; }
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

    <!-- DASHBOARD SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
            <div style="font-size: 0.75rem; font-weight: 800; letter-spacing: 2px;">SURGAS PRESTIGE</div>
        </div>
        <nav class="nav-menu">
            <a href="#" class="nav-item active"><i class='bx bxs-dashboard'></i> Dashboard</a>
            <a href="<?= BASE_URL ?>tienda" class="nav-item"><i class='bx bxs-shopping-bags'></i> Catálogo</a>
            <a href="#" class="nav-item"><i class='bx bx-history'></i> Mi Actividad</a>
        </nav>
        <div class="sidebar-foot">
            <a href="<?= BASE_URL ?>logout" class="btn-exit">
                <i class='bx bx-log-out'></i> Salir
            </a>
        </div>
    </aside>

    <!-- CONTENT WORKSPACE -->
    <main class="main-workspace">
        <header class="top-nav">
            <h1 class="page-heading">Executive Overview</h1>
            <div class="user-chip">
                <i class='bx bxs-user-circle' style="font-size: 1.2rem; color: var(--primary);"></i>
                <span><?= explode(' ', $cliente['nombre'])[0] ?></span>
            </div>
        </header>

        <!-- DASHBOARD HERO PANEL -->
        <section class="hero-board">
            <div class="hero-info">
                <p>Welcome back,</p>
                <h2><?= strtoupper($cliente['nombre']) ?></h2>
                
                <div class="data-grid">
                    <div class="data-point">
                        <span class="lbl">DNI / Código</span>
                        <span class="val"><?= htmlspecialchars($cliente['codigo']) ?></span>
                    </div>
                    <div class="data-point">
                        <span class="lbl">Member Since</span>
                        <span class="val">Marzo 2026</span>
                    </div>
                </div>
                
                <a href="<?= BASE_URL ?>tienda" style="display: inline-flex; align-items: center; gap: 10px; margin-top: 2rem; background: var(--primary); color: white; padding: 12px 25px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 0.85rem; box-shadow: 0 10px 25px rgba(130, 21, 21, 0.15);">
                    <i class='bx bxs-store'></i> IR AL CATÁLOGO
                </a>
            </div>

            <!-- 3D CARD ON THE RIGHT -->
            <div class="hero-right">
                <div class="vip-card-container" id="profileCard">
                    <div class="vip-card-inner">
                        <div class="card-front">
                            <i class='bx bxs-chip chip-icon'></i>
                            <i class='bx bx-fingerprint fingerprint-icon'></i>

                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.8rem; border: 1px solid rgba(255,255,255,0.2);">S</div>
                                <span style="font-weight: 800; font-size: 0.8rem; letter-spacing: 2px; color: #fff;">PRESTIGE</span>
                            </div>

                            <div class="pts-card-box">
                                <span class="pts-card-lbl">Puntos Disponibles</span>
                                <b class="pts-card-val" id="points-counter">0</b>
                            </div>

                            <div style="font-size: 0.75rem; font-weight: 700; opacity: 0.6; letter-spacing: 1px;">
                                <?= htmlspecialchars($cliente['codigo']) ?>
                            </div>
                        </div>
                        <div class="card-back" style="background: white; transform: rotateY(180deg); display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 20px;">
                            <div style="background: #fff; padding: 10px; border-radius: 12px; border: 1px solid #eee;">
                                <div id="qrcode"></div>
                            </div>
                            <div style="margin-top: 10px; font-size: 0.65rem; font-weight: 800; color: var(--primary); letter-spacing: 1px;">ID DIGITAL</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- RECENT ACTIVITY GRID -->
        <section class="activity-sec">
            <h3 class="sec-header">Actividad Reciente</h3>
            <div class="activity-list">
                <?php if (empty($ventas)): ?>
                    <div style="padding: 4rem; text-align: center; color: #ccc; font-weight: 700;">No hay movimientos recientes</div>
                <?php else: ?>
                    <?php foreach (array_slice($ventas, 0, 5) as $v): ?>
                    <div class="activity-row">
                        <div class="act-icon">
                            <i class='bx bxs-gas-pump'></i>
                        </div>
                        <div class="act-info">
                            <span class="act-title"><?= htmlspecialchars($v['detalle'] ?: 'Consumo de Combustible') ?></span>
                            <span class="act-date"><?= date('d M, Y', strtotime($v['fecha'])) ?></span>
                        </div>
                        <div class="act-pts">+<?= number_format($v['puntos']) ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <footer style="margin-top: 5rem; text-align: center; font-size: 0.75rem; color: #aaa; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">
            Surgas &bull; Executive Dashboard &bull; Premium Member Card
        </footer>
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
