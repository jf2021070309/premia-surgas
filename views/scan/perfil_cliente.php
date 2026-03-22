<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        :root { 
            --primary: #e62e2e; 
            --primary-glow: rgba(230, 46, 46, 0.4);
            --dark-bg: #0a0101;
            --wine-gradient: linear-gradient(180deg, #2b0808 0%, #0a0101 100%);
            --silver-text: linear-gradient(135deg, #d1d1d1 0%, #ffffff 50%, #d1d1d1 100%);
            --card-glass: rgba(255, 255, 255, 0.08); 
            --card-border: rgba(255, 255, 255, 0.15);
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
        
        .page-wrapper {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
            background: radial-gradient(circle at 50% 0%, rgba(230, 46, 46, 0.15) 0%, transparent 60%);
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
            background: linear-gradient(135deg, #333, #000);
            border: 2px solid var(--card-border);
            color: #fff;
            border-radius: 22px; 
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 800;
            box-shadow: 0 12px 30px rgba(0,0,0,0.5), 0 0 15px var(--primary-glow);
            position: relative;
        }
        .profile-avatar-premium::after {
            content: ''; position: absolute; bottom: -2px; right: -2px;
            width: 18px; height: 18px; background: #2ecc71;
            border: 3px solid #0a0101; border-radius: 50%;
        }

        .greeting-content h1 { 
            margin: 0; 
            font-size: 1.6rem; 
            font-weight: 900;
            letter-spacing: -1px;
            background: var(--silver-text);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .greeting-content p { 
            margin: 0; 
            font-size: 0.75rem; 
            opacity: 0.8; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            font-weight: 800;
            color: #ff9999;
        }

        .logout-pill {
            background: rgba(255,255,255,0.08);
            border: 1px solid var(--card-border);
            padding: 14px;
            border-radius: 16px;
            color: #fff;
            transition: 0.3s;
        }
        .logout-pill:hover {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--primary-glow);
        }

        /* VIP CARD */
        .vip-card-container {
            perspective: 2000px;
            margin: 2rem auto;
            max-width: 420px;
            width: 92%;
            height: 250px;
            cursor: pointer;
        }
        .vip-card-inner {
            position: relative; width: 100%; height: 100%;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }
        .vip-card-container.is-flipped .vip-card-inner { transform: rotateY(180deg); }
        .card-front, .card-back {
            position: absolute; width: 100%; height: 100%;
            backface-visibility: hidden; border-radius: 28px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }
        .card-front {
            background: #000;
            background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.08) 0%, transparent 50%), linear-gradient(45deg, #050505 0%, #151515 100%);
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
        }
        .holder-name-v { font-size: 1.4rem; font-weight: 800; color: #fff; letter-spacing: 1px; }
        .points-val-v { font-size: 2.8rem; font-weight: 900; line-height: 1; display: block; background: var(--silver-text); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .card-back {
            background: #0a0a0a; transform: rotateY(180deg);
            display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2.5rem;
            background-image: radial-gradient(circle at center, #1a1a1a 0%, #000 100%);
        }
        .qr-container-v { background: #fff; padding: 15px; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.8); }

        .flip-hint { 
            text-align: center; font-size: 0.8rem; opacity: 0.8; font-weight: 600;
            display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 2.5rem;
            color: #ff9999;
        }

        /* STATS & PROGRESS */
        .stats-row-premium { display: flex; gap: 12px; padding: 0 1.5rem; margin-bottom: 1.5rem; }
        .stat-mini-card {
            flex: 1; background: rgba(255,255,255,0.06); border: 1px solid var(--card-border);
            padding: 18px 12px; border-radius: 24px; text-align: center;
        }
        .stat-lbl-v { font-size: 0.65rem; opacity: 0.5; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 6px; font-weight: 800; }
        .stat-val-v { font-size: 1.3rem; font-weight: 900; }

        .progress-section { padding: 0 1.5rem; margin-bottom: 3.5rem; }
        .progress-info { display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 8px; font-weight: 700; color: #ffcccc; }
        .progress-bar-bg { height: 10px; background: rgba(0,0,0,0.3); border-radius: 10px; border: 1px solid var(--card-border); overflow: hidden; }
        .progress-bar-fill { height: 100%; background: linear-gradient(90deg, var(--primary), #ff6666); border-radius: 10px; box-shadow: 0 0 15px var(--primary-glow); transition: 1s ease; }

        /* ACTION CARD */
        .action-card-premium {
            background: rgba(255,255,255,0.06); backdrop-filter: blur(15px);
            border: 2px solid rgba(255,255,255,0.2); padding: 1.5rem;
            border-radius: 30px; display: flex; align-items: center; justify-content: space-between;
            text-decoration: none; margin: 0 1.5rem 3rem; transition: 0.3s;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }
        .action-card-premium:hover { transform: translateY(-4px); background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.4); }
        .action-icon-box {
            width: 54px; height: 54px; background: var(--primary); color: #fff;
            border-radius: 18px; display: flex; align-items: center; justify-content: center;
            font-size: 1.7rem; box-shadow: 0 8px 25px var(--primary-glow);
        }
        .action-text h3 { margin: 0; font-size: 1.1rem; font-weight: 800; }
        .action-text p { margin: 5px 0 0; font-size: 0.8rem; opacity: 0.6; }

        /* HISTORY */
        .history-section { padding: 0 1.5rem 5rem; }
        .section-title-v { font-size: 1.3rem; font-weight: 900; margin-bottom: 1.5rem; color: #fff; }
        .history-item-premium {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 22px; padding: 1.2rem; margin-bottom: 12px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .history-points { font-weight: 900; font-size: 1.1rem; }
        .points-pos { color: #40ff8f; }
        .points-neg { color: #ff4d4d; }

        .footer-minimal { text-align: center; padding: 2rem 0; opacity: 0.3; font-size: 0.7rem; letter-spacing: 3px; font-weight: 800; text-transform: uppercase; }

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
                        <div class="label-small-v" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5; margin-bottom: 6px;">Titular de Cuenta</div>
                        <div class="holder-name-v"><?= htmlspecialchars($cliente['nombre']) ?></div>
                    </div>
                    <div class="card-footer-v">
                        <div class="client-code-v"><?= htmlspecialchars($cliente['codigo']) ?></div>
                        <div class="points-box-v">
                            <span class="label-small-v">Saldo Actual</span>
                            <b class="points-val-v" id="points-counter">0</b>
                            <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 3px; opacity: 0.7;">pts surgas</span>
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

        <!-- Dashboard Summary -->
        <div class="stats-row-premium">
            <div class="stat-mini-card">
                <span class="stat-lbl-v">Operaciones</span>
                <b class="stat-val-v"><?= count($ventas) ?></b>
            </div>
            <div class="stat-mini-card" style="background: rgba(230, 46, 46, 0.1); border-color: rgba(230, 46, 46, 0.3);">
                <span class="stat-lbl-v" style="color: #ff9999;">Nivel</span>
                <b class="stat-val-v" style="color: #fff;">DIAMOND</b>
            </div>
        </div>

        <!-- Progress to Next Reward -->
        <div class="progress-section">
            <div class="progress-info">
                <span>Próximo Nivel</span>
                <span>80%</span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: 80%;"></div>
            </div>
            <p style="font-size: 0.65rem; opacity: 0.5; margin-top: 10px; text-align: center; text-transform: uppercase; letter-spacing: 1px;">Faltan 500 pts para alcanzar el nivel Legend</p>
        </div>

        <!-- Main Action -->
        <a href="<?= BASE_URL ?>tienda" class="action-card-premium">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="action-icon-box"><i class='bx bxs-shopping-bag'></i></div>
                <div class="action-text">
                    <h3>Tienda de Premios</h3>
                    <p>Canjea tus puntos acumulados</p>
                </div>
            </div>
            <i class='bx bx-chevron-right' style="font-size: 1.5rem; opacity: 0.4;"></i>
        </a>

        <!-- History -->
        <div class="history-section">
            <h2 class="section-title-v">Actividad Reciente</h2>
            <div class="history-list">
                <?php if (empty($ventas)): ?>
                    <div style="padding: 3rem 2rem; text-align: center; opacity: 0.3; background: rgba(255,255,255,0.02); border-radius: 25px; border: 1px dashed rgba(255,255,255,0.1);">
                        No hay movimientos registrados hoy.
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($ventas, 0, 5) as $v): 
                        $isPositive = $v['puntos'] >= 0;
                    ?>
                    <div class="history-item-premium">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 44px; height: 44px; background: rgba(255,255,255,0.05); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: var(--primary);">
                                <i class='bx <?= $isPositive ? 'bx-coin-stack' : 'bx-gift' ?>'></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700;"><?= htmlspecialchars($v['detalle'] ?: 'Operación') ?></h4>
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
        // Interacción Tarjeta
        const cardContainer = document.getElementById('profileCard');
        cardContainer.addEventListener('click', () => {
            cardContainer.classList.toggle('is-flipped');
        });

        // Generar QR
        const qrContent = '<?= BASE_URL ?>scan?c=<?= urlencode($cliente['codigo']) ?>&t=<?= urlencode($cliente['token']) ?>';
        new QRCode(document.getElementById("qrcode"), {
            text: qrContent,
            width: 150,
            height: 150,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        // Animación Puntos
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
