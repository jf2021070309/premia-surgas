<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        :root { 
            --primary: #c41212; /* Brighter primary red */
            --primary-glow: rgba(196, 18, 18, 0.3);
            --dark-bg: #0d0101;
            --wine-gradient: linear-gradient(180deg, #2a0808 0%, #0d0101 100%);
            --silver-text: linear-gradient(135deg, #d1d1d1 0%, #ffffff 50%, #d1d1d1 100%);
            --card-glass: rgba(255, 255, 255, 0.08); 
            --card-border: rgba(255, 255, 255, 0.18);
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--dark-bg);
            background: var(--wine-gradient);
            background-attachment: fixed;
            margin: 0; 
            color: white; 
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Layout Improvements */
        .page-wrapper {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
            background: radial-gradient(circle at 50% 0%, rgba(196, 18, 18, 0.2) 0%, transparent 60%);
            min-height: 100vh;
        }

        .header-wrapper {
            padding: 3.5rem 1.5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-profile-info {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .profile-avatar-premium {
            width: 64px; height: 64px; 
            background: linear-gradient(135deg, #444, #111);
            border: 2px solid var(--card-border);
            color: #fff;
            border-radius: 22px; 
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 800;
            box-shadow: 0 12px 30px rgba(0,0,0,0.7), 0 0 15px var(--primary-glow);
            position: relative;
        }
        .profile-avatar-premium::after {
            content: ''; position: absolute; bottom: -2px; right: -2px;
            width: 18px; height: 18px; background: #2ecc71;
            border: 3px solid #0d0101; border-radius: 50%;
        }

        .greeting-content h1 { 
            margin: 0; 
            font-size: 1.7rem; 
            font-weight: 800;
            letter-spacing: -1px;
            background: var(--silver-text);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .greeting-content p { 
            margin: 0; 
            font-size: 0.75rem; 
            opacity: 0.7; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            font-weight: 800;
            color: #ffbaba;
        }

        .logout-pill {
            background: rgba(255,255,255,0.1);
            border: 1px solid var(--card-border);
            padding: 14px;
            border-radius: 16px;
            color: #fff;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        .logout-pill:hover {
            background: var(--primary);
            border-color: #ff3b30;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(196, 18, 18, 0.4);
        }

        /* VIP CARD - MAINTAINED */
        .vip-card-container {
            perspective: 2000px;
            margin: 2rem auto;
            max-width: 420px;
            width: 92%;
            height: 250px;
            cursor: pointer;
        }

        .vip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }

        .vip-card-container.is-flipped .vip-card-inner {
            transform: rotateY(180deg);
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%; height: 100%;
            backface-visibility: hidden;
            border-radius: 28px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .card-front {
            background: #000;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255,255,255,0.08) 0%, transparent 50%),
                linear-gradient(45deg, #050505 0%, #1a1a1a 100%);
            display: flex; flex-direction: column; justify-content: space-between; padding: 2rem;
        }

        .card-shine {
            position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: skewX(-30deg); animation: cardShineEffect 6s infinite;
        }
        @keyframes cardShineEffect { 0% { left: -150%; } 20% { left: 150%; } 100% { left: 150%; } }

        .card-header-v { display: flex; justify-content: space-between; align-items: flex-start; }
        .card-logo-v { height: 30px; filter: brightness(0) invert(1); }
        
        .membership-badge-v {
            font-size: 0.7rem; font-weight: 800; letter-spacing: 2px;
            background: linear-gradient(135deg, #70706F, #E9E9E7, #70706F);
            color: #000; padding: 7px 16px; border-radius: 50px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
        }

        .card-middle-v { text-align: left; margin-bottom: 0.5rem; }
        .label-small-v { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5; margin-bottom: 6px; }
        .holder-name-v { font-size: 1.4rem; font-weight: 800; color: #fff; letter-spacing: 1px; }

        .card-footer-v { display: flex; justify-content: space-between; align-items: flex-end; }
        .client-code-v { font-family: 'Courier New', Courier, monospace; font-size: 1rem; letter-spacing: 3px; opacity: 0.7; }
        
        .points-box-v { text-align: right; }
        .points-val-v { font-size: 2.8rem; font-weight: 900; line-height: 1; display: block; background: var(--silver-text); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .points-unit-v { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 3px; opacity: 0.7; }

        .card-back {
            background: #0a0a0a; transform: rotateY(180deg);
            display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2.5rem;
            background-image: radial-gradient(circle at center, #1a1a1a 0%, #000 100%);
        }

        .qr-container-v { background: #fff; padding: 15px; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.8); }
        
        .flip-hint { 
            text-align: center; font-size: 0.8rem; opacity: 0.7; font-weight: 600;
            display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 2.5rem;
            color: #ffdada; text-shadow: 0 2px 5px rgba(0,0,0,0.5);
        }

        /* --- DASHBOARD ELEMENTS --- */
        .stats-row-premium {
            display: flex; gap: 15px; padding: 0 1.5rem; margin-bottom: 2.5rem;
        }

        .stat-mini-card {
            flex: 1; background: rgba(255,255,255,0.06); border: 1px solid var(--card-border);
            padding: 20px 15px; border-radius: 24px; text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3); transition: 0.3s;
        }
        .stat-mini-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.1); }
        
        .stat-lbl-v { font-size: 0.7rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 8px; font-weight: 700; }
        .stat-val-v { font-size: 1.4rem; font-weight: 900; color: #fff; }

        .action-card-premium {
            background: rgba(255,255,255,0.05); backdrop-filter: blur(15px);
            border: 2px solid rgba(255,255,255,0.15); padding: 1.5rem;
            border-radius: 24px; display: flex; align-items: center; justify-content: space-between;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none; box-shadow: 0 15px 30px rgba(0,0,0,0.4);
            margin: 0 1.5rem 3rem;
        }
        .action-card-premium:hover {
            transform: translateY(-5px); background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.3); box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }

        .action-icon-box {
            width: 54px; height: 54px; background: var(--primary); color: #fff;
            border-radius: 18px; display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; box-shadow: 0 8px 25px rgba(196, 18, 18, 0.6);
        }

        .history-section { padding: 0 1.5rem 6rem; }
        .section-header-row h2 { font-size: 1.2rem; font-weight: 800; letter-spacing: 0.5px; color: #fff; margin-bottom: 2rem; }
        
        .history-item-premium {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 22px; padding: 1.4rem; margin-bottom: 12px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        .history-item-premium:hover { border-color: var(--primary); background: rgba(255,255,255,0.08); }

        .points-pos { color: #2ecc71; font-weight: 900; }
        .points-neg { color: #ff4747; font-weight: 900; }

        .footer-minimal { opacity: 0.3; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase; text-align: center; }
        [v-cloak] { display: none; }
    </style>
</head>
<body>

    <div class="page-wrapper">
        <!-- Header -->
        <header class="header-wrapper">
            <div class="user-profile-info">
                <div class="profile-avatar-premium"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
                <div class="greeting-content">
                    <p>Digital Membership</p>
                    <h1>¡Hola, <?php echo htmlspecialchars($cliente['nombre']); ?>!</h1>
                </div>
            </div>
            <a href="<?= BASE_URL ?>logout" class="logout-pill" title="Cerrar Sesión">
                <i class='bx bx-log-out'></i>
            </a>
        </header>

        <!-- VIP Card -->
        <div class="vip-card-container" id="profileCard">
            <div class="vip-card-inner">
                <div class="card-front">
                    <div class="card-shine"></div>
                    <div class="card-header-v">
                        <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" class="card-logo-v">
                        <span class="membership-badge-v">ELITE MEMBER</span>
                    </div>
                    <div class="card-middle-v">
                        <div class="label-small-v">Titular de Cuenta</div>
                        <div class="holder-name-v"><?= htmlspecialchars($cliente['nombre']) ?></div>
                    </div>
                    <div class="card-footer-v">
                        <div class="client-code-v"><?= htmlspecialchars($cliente['codigo']) ?></div>
                        <div class="points-box-v">
                            <span class="label-small-v">Saldo Actual</span>
                            <b class="points-val-v" id="points-counter">0</b>
                            <span class="points-unit-v">pts surgas</span>
                        </div>
                    </div>
                </div>
                <div class="card-back">
                    <div class="qr-container-v">
                        <div id="qrcode"></div>
                    </div>
                    <p style="margin-top: 1.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.6;">Muestra para acumular</p>
                </div>
            </div>
        </div>

        <div class="flip-hint">
            <i class='bx bx-refresh bx-spin-slow'></i>
            Toca la tarjeta para ver tu QR
        </div>

        <!-- Dashboard Stats -->
        <div class="stats-row-premium">
            <div class="stat-mini-card">
                <span class="stat-lbl-v">Movimientos</span>
                <b class="stat-val-v"><?= count($ventas) ?></b>
            </div>
            <div class="stat-mini-card" style="background: rgba(130, 21, 21, 0.15); border-color: rgba(130, 21, 21, 0.3);">
                <span class="stat-lbl-v" style="color: #ff9999;">Estatus</span>
                <b class="stat-val-v" style="color: #fff;">DIAMOND</b>
            </div>
        </div>

        <!-- Actions -->
        <a href="<?= BASE_URL ?>tienda" class="action-card-premium">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div class="action-icon-box"><i class='bx bxs-shopping-bag'></i></div>
                <div>
                    <h3 style="margin: 0; color: #fff;">Tienda de Premios</h3>
                    <p style="margin: 2px 0 0; font-size: 0.8rem; opacity: 0.6;">Canjea tus puntos acumulados</p>
                </div>
            </div>
            <i class='bx bx-chevron-right' style="font-size: 1.5rem; opacity: 0.4;"></i>
        </a>

        <!-- History -->
        <div class="history-section">
            <h2 class="section-header-row">Actividad Reciente</h2>
            <div class="history-list-premium">
                <?php if (empty($ventas)): ?>
                    <div style="padding: 3rem 2rem; text-align: center; opacity: 0.3; background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px dashed rgba(255,255,255,0.1);">
                        No hay movimientos registrados hoy.
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($ventas, 0, 5) as $v): 
                        $isPositive = $v['puntos'] >= 0;
                    ?>
                    <div class="history-item-premium">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: var(--primary);">
                                <i class='bx <?= $isPositive ? 'bx-coin-stack' : 'bx-gift' ?>'></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: #fff;"><?= htmlspecialchars($v['detalle'] ?: 'Operación') ?></h4>
                                <p style="margin: 2px 0 0; font-size: 0.75rem; opacity: 0.5;"><?= date('d/m/Y', strtotime($v['fecha'])) ?></p>
                            </div>
                        </div>
                        <span class="history-points <?= $isPositive ? 'points-pos' : 'points-neg' ?>">
                            <?= $isPositive ? '+' : '' ?><?= $v['puntos'] ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="footer-minimal">
                &copy; <?= date('Y') ?> Surgas — Premium Experience
            </div>
        </div>
    </div>

    <script>
        const cardContainer = document.getElementById('profileCard');
        cardContainer.addEventListener('click', () => {
            cardContainer.classList.toggle('is-flipped');
        });

        const qrContent = '<?= BASE_URL ?>scan?c=<?= urlencode($cliente['codigo']) ?>&t=<?= urlencode($cliente['token']) ?>';
        new QRCode(document.getElementById("qrcode"), {
            text: qrContent,
            width: 150,
            height: 150,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

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
