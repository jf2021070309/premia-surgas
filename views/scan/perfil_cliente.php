<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        :root { 
            --primary: #2d0404; 
            --secondary: #2e7d32; 
            --bg-page: #2d0404;
            --surface: #ffffff;
            --text-main: #191c1d;
            --text-label: #8e8e8e;
            --shadow-card: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--bg-page);
            margin: 0; 
            color: var(--text-main); 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* --- BURGUNDY GLASS ARCHITECTURE --- */
        .page-header {
            padding: 3rem 1.5rem 5rem;
            color: white;
            text-align: right;
            position: relative;
        }
        .header-bg-text {
            position: absolute; top: 1.5rem; right: 1.5rem;
            font-size: 0.75rem; font-weight: 800; opacity: 0.6; letter-spacing: 2px;
            text-transform: uppercase; text-decoration: none; color: white;
        }

        .main-container {
            flex-grow: 1;
            background: var(--surface);
            margin-top: -3rem;
            border-top-left-radius: 40px;
            border-top-right-radius: 40px;
            padding: 2.5rem 2rem;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
            position: relative;
            z-index: 5;
        }

        /* Profile Data Styles (Reference Image) */
        .profile-field {
            margin-bottom: 2rem;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }
        .field-icon { font-size: 1.5rem; color: #ddd; }
        .field-content { flex-grow: 1; }
        .field-label { 
            font-size: 0.75rem; font-weight: 800; color: var(--text-label); 
            text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px; display: block;
        }
        .field-value { font-size: 1.1rem; font-weight: 600; color: var(--text-main); }

        /* Actions */
        .reward-btn {
            background: var(--secondary);
            color: white;
            padding: 1.2rem;
            border-radius: 20px;
            text-decoration: none;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            font-weight: 800; font-size: 1rem;
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.2);
            transition: 0.3s;
            margin-top: 1rem;
        }
        .reward-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(46, 125, 50, 0.3); }

        .footer-tag { text-align: center; margin-top: 3rem; font-size: 0.7rem; color: #ddd; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; }
        /* --- 3D VIP CARD (BURGUNDY PRESTIGE) --- */
        .vip-card-container {
            perspective: 1500px;
            margin: -5rem auto 2.5rem;
            width: 340px;
            height: 200px;
            cursor: pointer;
            z-index: 20;
        }
        .vip-card-inner { position: relative; width: 100%; height: 100%; text-align: left; transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform-style: preserve-3d; }
        .is-flipped .vip-card-inner { transform: rotateY(180deg); }
        .card-front, .card-back { position: absolute; width: 100%; height: 100%; -webkit-backface-visibility: hidden; backface-visibility: hidden; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
        .card-front { 
            background: linear-gradient(135deg, #1a1a1a 0%, #000 100%); 
            color: #fff; padding: 1.8rem; display: flex; flex-direction: column; justify-content: space-between; 
            border: 1px solid rgba(255,255,255,0.1);
        }
        .card-pts-box { margin-top: 10px; }
        .card-pts-label { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2.5px; color: rgba(255,255,255,0.4); font-weight: 800; display: block; margin-bottom: 4px; }
        .card-pts-val { font-size: 2.2rem; font-weight: 800; color: #fff; line-height: 1; letter-spacing: -1px; }

        .card-back { background: #fff; transform: rotateY(180deg); display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .qr-wrapper { background: #fff; padding: 10px; border-radius: 12px; }
        .qr-subtext { margin-top: 10px; font-size: 0.6rem; font-weight: 800; color: var(--primary); letter-spacing: 1px; opacity: 0.6; }

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
    <a href="<?= BASE_URL ?>logout" class="header-bg-text">Cerrar Sesión</a>
    <header class="page-header">
        <div style="font-size: 1.8rem; font-weight: 800;">PERFIL</div>
    </header>

    <main class="main-container">
        <!-- 3D VIP CARD (CENTRAL PIECE) -->
        <div class="vip-card-container" id="profileCard">
            <div class="vip-card-inner">
                <div class="card-front">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <span style="font-size: 0.8rem; font-weight: 800; letter-spacing: 2.5px; opacity: 0.8;">PRESTIGE</span>
                        <i class='bx bxs-chip' style="font-size: 2rem; opacity: 0.3;"></i>
                    </div>
                    
                    <div class="card-pts-box">
                        <span class="card-pts-label">Puntos Disponibles</span>
                        <b class="card-pts-val" id="points-counter">0</b>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                        <div>
                            <div style="font-size: 0.5rem; text-transform: uppercase; opacity: 0.4; letter-spacing: 1px;">Surgas Member</div>
                            <div style="font-size: 0.95rem; font-weight: 700; text-transform: uppercase;"><?= htmlspecialchars($cliente['nombre']) ?></div>
                        </div>
                        <i class='bx bx-fingerprint' style="font-size: 1.5rem; opacity: 0.2;"></i>
                    </div>
                </div>
                <div class="card-back">
                    <div class="qr-wrapper">
                        <div id="qrcode"></div>
                    </div>
                    <div class="qr-subtext">CÓDIGO: <?= htmlspecialchars($cliente['codigo']) ?></div>
                </div>
            </div>
        </div>

        <!-- PROFILE DATA FIELDS (AS PER IMAGE) -->
        <div class="profile-field">
            <div class="field-icon"><i class='bx bx-id-card'></i></div>
            <div class="field-content">
                <span class="field-label">DNI / Código</span>
                <div class="field-value"><?= htmlspecialchars($cliente['codigo']) ?></div>
            </div>
        </div>

        <div class="profile-field">
            <div class="field-icon"><i class='bx bx-user'></i></div>
            <div class="field-content">
                <span class="field-label">Nombre Completo</span>
                <div class="field-value"><?= htmlspecialchars($cliente['nombre']) ?></div>
            </div>
        </div>

        <div class="profile-field">
            <div class="field-icon"><i class='bx bx-calendar'></i></div>
            <div class="field-content">
                <span class="field-label">Miembro Desde</span>
                <div class="field-value">Marzo 2026</div>
            </div>
        </div>

        <a href="<?= BASE_URL ?>tienda" class="reward-btn">
            <i class='bx bxs-store'></i>
            VER CATÁLOGO DE PREMIOS
        </a>

        <div class="footer-tag">
            Surgas &bull; Prestige Membership &bull; <?= date('Y') ?>
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
