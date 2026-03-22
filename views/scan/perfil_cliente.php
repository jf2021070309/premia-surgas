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
            --primary: #821515; 
            --dark-bg: #050505;
            --silver-text: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
            --silver-metal: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
            --gold-text: linear-gradient(135deg, #bf953f 0%, #fcf6ba 50%, #b38728 100%);
            --card-glass: rgba(255, 255, 255, 0.03);
            --card-border: rgba(255, 255, 255, 0.08);
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--dark-bg);
            background: radial-gradient(circle at top right, #1a0505 0%, #050505 100%);
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
        }

        .header-wrapper {
            padding: 3rem 1.5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-profile-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile-avatar-premium {
            width: 55px; height: 55px; 
            background: linear-gradient(135deg, #222, #000);
            border: 1px solid var(--card-border);
            color: #fff;
            border-radius: 18px; 
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; font-weight: 700;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
            position: relative;
        }
        .profile-avatar-premium::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            z-index: -1;
        }

        .greeting-content h1 { 
            margin: 0; 
            font-size: 1.5rem; 
            font-weight: 700;
            letter-spacing: -0.5px;
            background: var(--silver-text);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .greeting-content p { 
            margin: 0; 
            font-size: 0.75rem; 
            opacity: 0.5; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            font-weight: 600;
        }

        .logout-pill {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--card-border);
            padding: 10px;
            border-radius: 12px;
            color: rgba(255,255,255,0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logout-pill:hover {
            color: #fff;
            background: rgba(130, 21, 21, 0.2);
            border-color: rgba(130, 21, 21, 0.4);
        }

        /* VIP CARD - KEPT AS REQUESTED */
        .vip-card-container {
            perspective: 1500px;
            margin: 1.5rem auto 1.5rem;
            max-width: 420px;
            width: 92%;
            height: 250px;
            cursor: pointer;
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
        }

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

        .card-shine {
            position: absolute;
            top: 0; left: -100%;
            width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            transform: skewX(-25deg);
            animation: cardShineEffect 8s infinite;
        }
        @keyframes cardShineEffect { 0% { left: -150%; } 15% { left: 150%; } 100% { left: 150%; } }

        .card-header-v { display: flex; justify-content: space-between; align-items: flex-start; }
        .card-logo-v { height: 26px; filter: brightness(0) invert(1) opacity(0.8); }
        
        .membership-badge-v {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 2px;
            background: var(--silver-metal);
            color: #111;
            padding: 6px 14px;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .card-middle-v { text-align: left; }
        .label-small-v { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom: 4px; }
        .holder-name-v { font-size: 1.25rem; font-weight: 700; color: #fff; letter-spacing: 1px; }

        .card-footer-v { display: flex; justify-content: space-between; align-items: flex-end; }
        .client-code-v { font-family: 'Courier New', Courier, monospace; font-size: 0.9rem; letter-spacing: 2.5px; opacity: 0.6; }
        
        .points-box-v { text-align: right; }
        .points-val-v { font-size: 2.4rem; font-weight: 800; line-height: 1; display: block; background: var(--silver-text); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .points-unit-v { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.6; }

        .card-back {
            background: #111;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-image: radial-gradient(circle at center, #1a1a1a 0%, #0a0a0a 100%);
        }

        .qr-container-v {
            background: white;
            padding: 12px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        
        /* New Layout Sections */
        .flip-hint { 
            text-align: center; 
            font-size: 0.75rem; 
            opacity: 0.5; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 2.5rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 0 1.5rem;
            margin-bottom: 3rem;
        }

        .action-card-premium {
            background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
            border: 1px solid var(--card-border);
            padding: 1.25rem;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
        }
        .action-card-premium:hover {
            transform: translateY(-5px);
            background: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.05));
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .action-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .action-icon-box {
            width: 50px; height: 50px;
            background: var(--primary);
            color: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(130, 21, 21, 0.3);
        }
        .action-text h3 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
            color: white;
        }
        .action-text p {
            margin: 0;
            font-size: 0.75rem;
            opacity: 0.5;
        }
        .action-arrow {
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.3);
        }

        /* History Section */
        .history-section {
            padding: 0 1.5rem 5rem;
        }
        .section-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .section-header-row h2 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .view-all-link {
            font-size: 0.8rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            opacity: 0.8;
        }

        .history-list-premium {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .history-item-premium {
            background: var(--card-glass);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s ease;
        }
        .history-item-premium:hover {
            border-color: rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.05);
        }

        .history-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .history-type-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            background: rgba(255,255,255,0.03);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--primary);
        }
        .history-data h4 {
            margin: 0; font-size: 0.9rem; font-weight: 600;
        }
        .history-data p {
            margin: 0; font-size: 0.72rem; opacity: 0.4; margin-top: 2px;
        }

        .history-right {
            text-align: right;
        }
        .history-points {
            display: block;
            font-weight: 800;
            font-size: 1rem;
            color: white;
        }
        .points-pos { color: #2ecc71; }
        .points-neg { color: #e74c3c; }

        .footer-minimal {
            text-align: center;
            padding: 2rem 0;
            opacity: 0.2;
            font-size: 0.7rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

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

        <!-- VIP Card (Untouched Content) -->
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
                    <div class="qr-help" style="margin-top: 1.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.6;">Muestra para acumular</div>
                </div>
            </div>
        </div>

        <div class="flip-hint">
            <i class='bx bx-refresh bx-spin-slow'></i>
            Toca la tarjeta para ver tu QR
        </div>

        <!-- Main Actions -->
        <div class="actions-grid">
            <a href="<?= BASE_URL ?>tienda" class="action-card-premium">
                <div class="action-info">
                    <div class="action-icon-box">
                        <i class='bx bxs-shopping-bag'></i>
                    </div>
                    <div class="action-text">
                        <h3>Tienda de Premios</h3>
                        <p>Canjea tus puntos acumulados</p>
                    </div>
                </div>
                <div class="action-arrow">
                    <i class='bx bx-chevron-right'></i>
                </div>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="history-section">
            <div class="section-header-row">
                <h2>Actividad Reciente</h2>
                <span class="view-all-link">Últimos movimientos</span>
            </div>

            <div class="history-list-premium">
                <?php if (empty($ventas)): ?>
                    <div style="padding: 3rem 2rem; text-align: center; opacity: 0.3; font-size: 0.9rem; background: var(--card-glass); border-radius: 20px;">
                        No hay movimientos registrados.
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($ventas, 0, 4) as $v): 
                        $isPositive = $v['puntos'] >= 0;
                    ?>
                    <div class="history-item-premium">
                        <div class="history-left">
                            <div class="history-type-icon">
                                <i class='bx <?= $isPositive ? 'bx-plus-circle' : 'bx-gift' ?>'></i>
                            </div>
                            <div class="history-data">
                                <h4><?= htmlspecialchars($v['detalle'] ?: 'Operación') ?></h4>
                                <p><i class='bx bx-calendar-alt'></i> <?= date('d M, Y', strtotime($v['fecha'])) ?></p>
                            </div>
                        </div>
                        <div class="history-right">
                            <span class="history-points <?= $isPositive ? 'points-pos' : 'points-neg' ?>">
                                <?= $isPositive ? '+' : '' ?><?= $v['puntos'] ?> pts
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="footer-minimal">
                &copy; <?= date('Y') ?> Surgas Premium Digital System
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
