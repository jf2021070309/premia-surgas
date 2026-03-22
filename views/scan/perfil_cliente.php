<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        :root { 
            --primary: #c41c1c; 
            --primary-light: #ff3e3e;
            --bg-color: #0d0101;
            --glass: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.15);
            --text-silver: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
        }

        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(circle at 50% -20%, #4a0808 0%, transparent 70%),
                radial-gradient(circle at 0% 100%, #1a0505 0%, transparent 50%);
            background-attachment: fixed;
            margin: 0; color: white; min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 0 1.2rem;
            position: relative;
        }

        /* --- NEW HEADER DESIGN --- */
        .profile-header {
            padding: 3.5rem 0 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .avatar-container {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .avatar-ring {
            width: 86px; height: 86px;
            border-radius: 30px;
            padding: 4px;
            background: linear-gradient(135deg, var(--primary), #5a0808);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 15px 35px rgba(196, 28, 28, 0.3);
            transform: rotate(-5deg);
        }

        .avatar-main {
            width: 100%; height: 100%;
            background: #111;
            border-radius: 26px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem; font-weight: 800; color: white;
            transform: rotate(5deg);
        }

        .online-dot {
            position: absolute; bottom: 5px; right: 5px;
            width: 18px; height: 18px; background: #2ecc71;
            border: 4px solid var(--bg-color); border-radius: 50%;
            box-shadow: 0 0 10px #2ecc71;
        }

        .profile-header p {
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px;
            margin: 0; opacity: 0.6; font-weight: 700; color: #ff9999;
        }
        .profile-header h1 {
            font-size: 1.7rem; font-weight: 900; margin: 8px 0 0;
            background: var(--text-silver); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .logout-float {
            position: absolute; top: 3.5rem; right: 1.2rem;
            background: var(--glass); border: 1px solid var(--glass-border);
            width: 46px; height: 46px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.3rem; transition: 0.3s;
        }
        .logout-float:hover { background: var(--primary); transform: translateY(-3px); }

        /* --- CARD SECTION --- */
        .card-perspective {
            perspective: 2000px;
            margin: 1rem auto 3rem;
        }

        .card-inner-3d {
            position: relative; width: 100%; height: 260px;
            transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d; cursor: pointer;
        }

        .card-perspective.is-flipped .card-inner-3d { transform: rotateY(180deg); }

        .f-side, .b-side {
            position: absolute; width: 100%; height: 100%;
            backface-visibility: hidden; border-radius: 32px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.8);
            border: 1px solid rgba(255,255,255,0.15);
            overflow: hidden;
        }

        .f-side {
            background: #000 linear-gradient(135deg, #111, #000);
            display: flex; flex-direction: column; justify-content: space-between; padding: 2rem;
        }

        .card-logo-top { height: 32px; filter: brightness(0) invert(1); }
        .card-chip { width: 45px; height: 35px; background: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728); border-radius: 8px; margin-bottom: 2rem; }
        
        .card-name-v { font-size: 1.4rem; font-weight: 700; letter-spacing: 1px; color: #fff; }
        .card-code-v { font-family: 'Courier New', monospace; font-size: 1rem; letter-spacing: 3px; opacity: 0.6; margin-top: 5px; }
        
        .card-pts-v { text-align: right; }
        .card-pts-v span { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5; }
        .card-pts-val { font-size: 2.8rem; font-weight: 900; line-height: 1; display: block; background: var(--text-silver); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .b-side {
            background: #0a0a0a; transform: rotateY(180deg);
            display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2.5rem;
        }
        .qr-frame { background: #fff; padding: 12px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.5); }

        /* --- NOTABLE CHANGES --- */
        .action-highlight {
            background: linear-gradient(135deg, var(--primary), #8a0b0b);
            padding: 1.8rem; border-radius: 35px;
            margin-bottom: 3rem; position: relative;
            overflow: hidden; box-shadow: 0 20px 40px rgba(196, 28, 28, 0.4);
            display: flex; align-items: center; justify-content: space-between;
            text-decoration: none; border: 1px solid rgba(255,255,255,0.2);
        }
        .action-highlight::before {
            content: ''; position: absolute; top: -50%; left: -50%;
            width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }
        .action-text-h h3 { margin: 0; font-size: 1.4rem; font-weight: 900; color: #fff; }
        .action-text-h p { margin: 5px 0 0; font-size: 0.9rem; opacity: 0.9; font-weight: 500; }
        .action-btn-h { width: 56px; height: 56px; background: #fff; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--primary); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

        .section-title {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem; padding: 0 0.5rem;
        }
        .section-title h2 { font-size: 1.3rem; font-weight: 800; margin: 0; }
        .section-title a { font-size: 0.85rem; color: var(--primary-light); text-decoration: none; font-weight: 700; }

        .history-card {
            background: var(--glass); backdrop-filter: blur(20px);
            border: 1.5px solid var(--glass-border); border-radius: 30px;
            padding: 0.8rem; margin-bottom: 5rem;
        }

        .history-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.2rem; border-radius: 22px; transition: 0.3s;
        }
        .history-row:hover { background: rgba(255,255,255,0.04); }
        
        .history-icon-v {
            width: 48px; height: 48px; border-radius: 16px;
            background: rgba(196, 28, 28, 0.15); color: var(--primary-light);
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }

        .history-info-v h4 { margin: 0; font-size: 1rem; font-weight: 700; }
        .history-info-v p { margin: 4px 0 0; font-size: 0.8rem; opacity: 0.5; }

        .pts-v { font-weight: 900; font-size: 1.2rem; }
        .pts-v.pos { color: #2ecc71; }
        .pts-v.neg { color: #ff5e5e; }

        .footer-tag { text-align: center; padding: 2rem 0; opacity: 0.4; font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; }

        [v-cloak] { display: none; }
    </style>
</head>
<body>

    <div class="container">
        <!-- Float Logout -->
        <a href="<?= BASE_URL ?>logout" class="logout-float">
            <i class='bx bx-log-out'></i>
        </a>

        <!-- Header -->
        <header class="profile-header">
            <div class="avatar-container">
                <div class="avatar-ring">
                    <div class="avatar-main"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
                </div>
                <div class="online-dot"></div>
            </div>
            <p>Membership Pass</p>
            <h1>Hola, <?= htmlspecialchars($cliente['nombre']) ?></h1>
        </header>

        <!-- Virtual Card -->
        <div class="card-perspective" id="passCard">
            <div class="card-inner-3d">
                <div class="f-side">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" class="card-logo-top">
                        <div style="font-size: 0.65rem; font-weight: 800; background: rgba(255,255,255,0.1); padding: 6px 14px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2);">ELITE MEMBER</div>
                    </div>
                    
                    <div style="flex-grow: 1; display: flex; align-items: flex-end;">
                        <div style="flex: 1;">
                            <div class="card-name-v"><?= htmlspecialchars($cliente['nombre']) ?></div>
                            <div class="card-code-v"><?= htmlspecialchars($cliente['codigo']) ?></div>
                        </div>
                        <div class="card-pts-v">
                            <span>Balance</span>
                            <b class="card-pts-val" id="count-pts">0</b>
                        </div>
                    </div>
                </div>
                <div class="b-side">
                    <div class="qr-frame">
                        <div id="v-qrcode"></div>
                    </div>
                    <p style="margin-top: 1.5rem; font-size: 0.8rem; opacity: 0.7; font-weight: 600;">ESCANEAME PARA ACUMULAR</p>
                </div>
            </div>
        </div>

        <div style="text-align: center; font-size: 0.85rem; opacity: 0.6; margin-bottom: 2.5rem; font-weight: 500;">
            <i class='bx bx-rotate-right bx-spin-slow' style="vertical-align: middle; font-size: 1.2rem;"></i> Toca para girar la tarjeta
        </div>

        <!-- Main CTA -->
        <a href="<?= BASE_URL ?>tienda" class="action-highlight">
            <div class="action-text-h">
                <h3>Ir a Canjear</h3>
                <p>Descubre tus premios disponibles</p>
            </div>
            <div class="action-btn-h">
                <i class='bx bxs-gift'></i>
            </div>
        </a>

        <!-- History -->
        <div class="section-title">
            <h2>Actividad</h2>
            <a href="#">Ver historial</a>
        </div>

        <div class="history-card">
            <?php if (empty($ventas)): ?>
                <div style="padding: 4rem 2rem; text-align: center; opacity: 0.3;">
                    No tiene movimientos recientes
                </div>
            <?php else: ?>
                <?php foreach (array_slice($ventas, 0, 5) as $v): 
                    $isPos = $v['puntos'] >= 0;
                ?>
                <div class="history-row">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div class="history-icon-v">
                            <i class='bx <?= $isPos ? 'bx-bolt-circle' : 'bx-shopping-bag' ?>'></i>
                        </div>
                        <div class="history-info-v">
                            <h4><?= htmlspecialchars($v['detalle'] ?: 'Transacción') ?></h4>
                            <p><?= date('d/m/Y', strtotime($v['fecha'])) ?></p>
                        </div>
                    </div>
                    <div class="pts-v <?= $isPos ? 'pos' : 'neg' ?>">
                        <?= $isPos ? '+' : '' ?><?= $v['puntos'] ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="footer-tag">
            &copy; <?= date('Y') ?> SURGAS PREMIUM
        </div>
    </div>

    <script>
        const passCard = document.getElementById('passCard');
        passCard.addEventListener('click', () => {
            passCard.classList.toggle('is-flipped');
        });

        const qrText = '<?= BASE_URL ?>scan?c=<?= urlencode($cliente['codigo']) ?>&t=<?= urlencode($cliente['token']) ?>';
        new QRCode(document.getElementById("v-qrcode"), {
            text: qrText,
            width: 160,
            height: 160,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        const targetP = <?= (int) ($cliente['puntos'] ?? 0) ?>;
        const elP = document.getElementById('count-pts');
        let currentP = 0;
        const animDuration = 1500;
        const stepsCount = 60;
        const animInterval = animDuration / stepsCount;
        
        const timerP = setInterval(() => {
            currentP += targetP / stepsCount;
            if (currentP >= targetP) {
                elP.textContent = Math.floor(targetP).toLocaleString();
                clearInterval(timerP);
            } else {
                elP.textContent = Math.floor(currentP).toLocaleString();
            }
        }, animInterval);
    </script>
    <script> const BASE_URL = '<?= BASE_URL ?>'; </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
