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
            --primary: #5a0606; 
            --primary-light: #821515;
            --primary-accent: #b41e1e;
            --bg-body: #fdfdfe;
            --text-main: #0a0a0a;
            --text-muted: #6b7280;
            --silver-metal: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
            --card-silver: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
            --surface: #ffffff;
            --surface-variant: #f8fafc;
            --shadow-soft: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            --shadow-premium: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        body { 
            font-family: 'Manrope', sans-serif; 
            background: var(--bg-body);
            background-image: 
                radial-gradient(at 100% 0%, rgba(130, 21, 21, 0.04) 0px, transparent 40%),
                radial-gradient(at 0% 100%, rgba(0, 0, 0, 0.02) 0px, transparent 40%);
            background-attachment: fixed;
            margin: 0; 
            color: var(--text-main); 
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        @keyframes fadeInOut {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in { animation: fadeInOut 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        
        /* Executive Header */
        .header-wrapper {
            background: linear-gradient(165deg, #2d0404 0%, #5a0606 100%);
            padding: 2.5rem 1.5rem 7.5rem;
            color: white;
            position: relative;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .header-content {
            max-width: 480px;
            margin: 0 auto;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-greeting {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .profile-avatar {
            width: 52px; height: 52px; 
            background: rgba(255,255,255,0.1);
            border: 1.5px solid rgba(255,255,255,0.3);
            color: #fff;
            border-radius: 12px; 
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; font-weight: 800;
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .greeting-text h1 { 
            margin: 0; 
            font-size: 1.5rem; 
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #fff;
        }
        .greeting-text p { margin: 0; font-size: 0.75rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 2px; color: #fff; font-weight: 600; }

        .logout-btn {
            background: rgba(255,255,255,0.1);
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .logout-btn:hover { background: rgba(255,255,255,0.2); color: #fff; transform: scale(1.05); }

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

        /* Premium Store Button */
        .page-content {
            margin-top: -1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
            align-items: center;
        }

        .btn-store {
            background: linear-gradient(135deg, #111 0%, #333 100%);
            color: #fff; 
            text-decoration: none; 
            padding: 1.25rem 2rem; 
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center; gap: 1rem;
            font-weight: 800; font-size: 1.1rem; 
            box-shadow: var(--shadow-premium);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            width: 88%;
            max-width: 420px;
            border: 1px solid rgba(255,255,255,0.1);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            overflow: hidden;
        }
        .btn-store span { position: relative; z-index: 2; }
        .btn-store i { font-size: 1.4rem; color: var(--primary-accent); }
        .btn-store:hover { transform: translateY(-5px); box-shadow: 0 25px 50px rgba(0,0,0,0.3); }
        .btn-store::before {
            content: '';
            position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: skewX(-25deg);
            transition: 0.6s;
        }
        .btn-store:hover::before { left: 150%; }

        /* Tier Progress Card */
        .tier-card {
            background: white;
            width: 88%;
            max-width: 420px;
            padding: 1.5rem;
            border-radius: 28px;
            box-shadow: var(--shadow-soft);
            border: 1px solid #f1f5f9;
        }
        .tier-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem; }
        .tier-name { font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .tier-pts { font-size: 0.9rem; font-weight: 800; color: var(--primary-light); }
        .tier-bar-bg { height: 12px; background: #f1f5f9; border-radius: 12px; overflow: hidden; position: relative; }
        .tier-bar-fill { 
            height: 100%; 
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-accent) 100%); 
            border-radius: 12px; 
            transition: width 2s cubic-bezier(0.65, 0, 0.35, 1);
            position: relative;
        }
        .tier-bar-fill::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(0deg, rgba(255,255,255,0.1) 0%, transparent 50%, rgba(255,255,255,0.1) 100%);
        }
        .tier-footer { margin-top: 1rem; font-size: 0.75rem; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 8px; }
        .tier-footer i { color: #f59e0b; font-size: 1rem; }

        /* Tonal Activity List */
        .activity-container { width: 88%; max-width: 420px; padding-bottom: 4rem; }
        .activity-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        .activity-title { font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin: 0; }
        .activity-link { font-size: 0.8rem; font-weight: 700; color: var(--primary-light); text-decoration: none; }
        
        .activity-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .activity-item { 
            background: var(--surface); 
            padding: 1rem 1.25rem; 
            border-radius: 20px; 
            display: flex; align-items: center; gap: 1rem;
            box-shadow: var(--shadow-soft);
            transition: transform 0.3s ease;
            border: 1px solid #f1f5f9;
        }
        .activity-item:hover { transform: scale(1.02); }
        .activity-icon {
            width: 44px; height: 44px;
            background: #fff1f1;
            color: var(--primary-light);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .activity-info { flex-grow: 1; }
        .activity-desc { font-size: 0.9rem; font-weight: 700; color: var(--text-main); margin-bottom: 2px; }
        .activity-date { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
        .activity-points { 
            font-size: 1rem; font-weight: 800; color: #10b981; 
            background: #f0fdf4; padding: 4px 10px; border-radius: 8px;
        }

        .footer { text-align: center; padding: 3rem 0; color: var(--text-muted); font-size: 0.75rem; font-weight: 600; opacity: 0.5; }
        
        .hint-text { margin-top: -1.5rem; text-align: center; font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2rem; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .hint-text i { font-size: 1.1rem; color: var(--primary-light); }
        
        [v-cloak] { display: none; }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <div class="header-content animate-in">
            <div class="user-greeting">
                <div class="profile-avatar"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
                <div class="greeting-text">
                    <p>Digital Membership</p>
                    <h1>¡Hola, <?= explode(' ', $cliente['nombre'])[0] ?>!</h1>
                </div>
            </div>
            <a href="<?= BASE_URL ?>logout" class="logout-btn" title="Cerrar Sesión">
                <i class='bx bx-power-off'></i>
            </a>
        </div>
    </div>

    <!-- VIP CARD BLACK EDITION (3D FLIP) -->
    <div class="vip-card-container animate-in delay-1" id="profileCard">
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

    <div class="hint-text animate-in delay-1">
        <i class='bx bxs-hand'></i> Toca la tarjeta para ver tu QR
    </div>

    <div class="page-content">
        <a href="<?= BASE_URL ?>tienda" class="btn-store animate-in delay-2">
            <span><i class='bx bxs-shopping-bag'></i> Ver Catálogo de Premios</span>
        </a>

        <!-- Gamification: Progres Tier -->
        <?php 
            $puntos_actuales = (int)($cliente['puntos'] ?? 0);
            $meta = 1000;
            if ($puntos_actuales >= 1000) $meta = 2500;
            if ($puntos_actuales >= 2500) $meta = 5000;
            $progress = min(100, ($puntos_actuales / $meta) * 100);
        ?>
        <div class="tier-card animate-in delay-2">
            <div class="tier-header">
                <span class="tier-name">Próxima Recompensa</span>
                <span class="tier-pts"><?= number_format($meta) ?> PTS</span>
            </div>
            <div class="tier-bar-bg">
                <div class="tier-bar-fill" style="width: <?= $progress ?>%"></div>
            </div>
            <div class="tier-footer">
                <i class='bx bxs-info-circle'></i> Estás a <?= number_format(max(0, $meta - $puntos_actuales)) ?> puntos de tu siguiente beneficio.
            </div>
        </div>

        <div class="activity-container animate-in delay-3">
            <div class="activity-header">
                <h3 class="activity-title">Actividad Reciente</h3>
                <a href="#" class="activity-link">Ver todo</a>
            </div>
            
            <div class="activity-list">
                <?php if (empty($ventas)): ?>
                    <div style="padding: 2.5rem; text-align: center; opacity: 0.3; background: #fff; border-radius: 20px;">
                        No hay movimientos registrados.
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($ventas, 0, 3) as $v): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class='bx bx-plus-circle'></i>
                        </div>
                        <div class="activity-info">
                            <div class="activity-desc"><?= htmlspecialchars($v['detalle'] ?: 'Recarga de Puntos') ?></div>
                            <div class="activity-date"><?= date('d M, Y', strtotime($v['fecha'])) ?></div>
                        </div>
                        <div class="activity-points">+<?= $v['puntos'] ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="footer">
                Surgas Prestige &bull; Member Experience &bull; <?= date('Y') ?>
            </div>
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
