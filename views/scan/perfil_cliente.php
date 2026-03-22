<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root { 
            --primary: #821515; 
            --secondary: #2b0303; 
            --accent: #f39c12; 
            --gold: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
            --silver: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
            --bronze: linear-gradient(135deg, #804A00, #C08040, #804A00, #C08040, #804A00);
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: #2b0303;
            background: radial-gradient(circle at top right, #5e0a0a 0%, #2b0303 100%);
            background-attachment: fixed;
            margin: 0; 
            color: white; 
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .header-wrapper {
            padding: 2.5rem 1.5rem 1rem;
            text-align: left;
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }

        .user-greeting {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile-avatar {
            width: 55px; height: 55px; background: rgba(255,255,255,0.1); 
            border: 1px solid rgba(255,255,255,0.2); color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; font-weight: 700;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .greeting-text h1 { 
            margin: 0; 
            font-size: 1.6rem; 
            font-weight: 700;
            letter-spacing: -0.5px;
            color: white;
        }
        .greeting-text p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.6;
        }

        /* --- TARJETA VIP PREMIUM --- */
        .vip-card-container {
            perspective: 1000px;
            margin: 0 auto 2.5rem;
            max-width: 420px;
            width: 92%;
        }

        .vip-card {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 1.8rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
            aspect-ratio: 1.6 / 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.4s ease;
        }

        .vip-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at bottom left, rgba(130, 21, 21, 0.3) 0%, transparent 50%);
            z-index: 0;
        }

        /* Efecto de Brillo (Shine) */
        .card-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(
                90deg, 
                transparent, 
                rgba(255, 255, 255, 0.1), 
                transparent
            );
            transform: skewX(-25deg);
            animation: cardShineEffect 6s infinite;
            z-index: 1;
        }

        @keyframes cardShineEffect {
            0% { left: -150%; }
            20% { left: 150%; }
            100% { left: 150%; }
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            z-index: 2;
        }

        .card-logo {
            height: 28px;
            opacity: 0.9;
            filter: brightness(0) invert(1);
        }

        .membership-level {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: rgba(255,255,255,0.1);
            padding: 5px 12px;
            border-radius: 50px;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }
        /* Dinámicos */
        .level-platinum { background: var(--silver); color: #333; border: none; }
        .level-gold { background: var(--gold); color: #442e00; border: none; }
        .level-bronze { background: var(--bronze); color: #fff; border: none; }

        .card-middle {
            margin-top: 1rem;
            z-index: 2;
        }

        .card-holder-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: 0.5;
            margin-bottom: 2px;
        }

        .card-holder-name {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            z-index: 2;
        }

        .card-id {
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            letter-spacing: 2px;
            opacity: 0.7;
        }

        .card-points-display {
            text-align: right;
        }

        .card-points-val {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            display: block;
        }

        .card-points-lbl {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: 0.7;
        }

        /* Chip Holográfico */
        .card-chip {
            width: 40px;
            height: 30px;
            background: linear-gradient(135deg, #fce abb, #f8b500);
            border-radius: 6px;
            position: relative;
            margin-top: 10px;
            opacity: 0.8;
            border: 1px solid rgba(0,0,0,0.1);
        }
        .card-chip::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                linear-gradient(90deg, transparent 50%, rgba(0,0,0,0.1) 50%),
                linear-gradient(rgba(0,0,0,0.1) 50%, transparent 50%);
            background-size: 8px 8px;
        }

        /* --- BOTÓN TIENDA --- */
        .btn-store {
            background: #fff;
            color: #000; 
            text-decoration: none; 
            padding: 1.1rem 2rem; 
            border-radius: 100px;
            display: flex; align-items: center; justify-content: center; gap: 0.8rem;
            font-weight: 800; font-size: 1rem; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            width: 90%;
            max-width: 380px;
            margin: 0 auto 3rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border: none;
        }
        .btn-store:hover { 
            transform: translateY(-5px) scale(1.02); 
            box-shadow: 0 25px 50px rgba(0,0,0,0.6);
            background: var(--accent);
            color: white;
        }
        .btn-store i { font-size: 1.3rem; }

        .container { 
            padding: 0 1.5rem 4rem; 
            max-width: 500px; 
            margin: 0 auto; 
        }

        .section-title { 
            display: flex; align-items: center; justify-content: space-between;
            font-size: 1.1rem; font-weight: 700; color: white; 
            margin: 0 0 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        .history-card { 
            background: rgba(255, 255, 255, 0.03); 
            backdrop-filter: blur(10px);
            border-radius: 2rem; 
            border: 1px solid rgba(255,255,255,0.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            color: white;
            overflow: hidden;
        }

        .history-item { 
            padding: 1.4rem 1.6rem; 
            border-bottom: 1px solid rgba(255,255,255,0.03); 
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .history-item:last-child { border-bottom: none; }
        .history-item:active { background: rgba(255,255,255,0.05); }
        
        .history-main-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-name { 
            font-size: 0.95rem; 
            font-weight: 600; 
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .item-name i { color: var(--accent); font-size: 1.2rem; }
        
        .item-pts { 
            color: #4ade80; 
            font-weight: 800; 
            font-size: 1.1rem;
        }
        
        .history-date { font-size: 0.8rem; opacity: 0.4; margin-top: 4px; display: block; padding-left: 31px; }

        .btn-expand-min { font-size: 0.7rem; opacity: 0.5; }

        .detail-list { 
            margin-top: 1rem; 
            padding: 1rem 0 0.5rem 31px; 
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .detail-subitem { 
            font-size: 0.85rem; 
            padding: 0.4rem 0; 
            display: flex;
            justify-content: space-between;
            opacity: 0.8;
        }

        .footer { text-align: center; padding: 2rem 0; color: rgba(255,255,255,0.3); font-size: 0.8rem; }
        
        .logout-btn-client {
            position: absolute;
            top: 2.2rem;
            right: 1.5rem;
            color: rgba(255,255,255,0.4);
            font-size: 1.4rem;
            transition: 0.3s;
            background: rgba(255,255,255,0.05);
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        /* Mascot Float */
        .mascot-profile {
            position: fixed;
            bottom: -20px;
            right: -20px;
            width: 150px;
            opacity: 0.15;
            pointer-events: none;
            z-index: -1;
            filter: grayscale(1);
        }

        [v-cloak] { display: none; }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <a href="<?= BASE_URL ?>logout" class="logout-btn-client" title="Cerrar Sesión">
            <i class='bx bx-log-out'></i>
        </a>
        
        <div class="user-greeting">
            <div class="profile-avatar"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
            <div class="greeting-text">
                <p>Bienvenido de nuevo,</p>
                <h1><?= explode(' ', $cliente['nombre'])[0] ?></h1>
            </div>
        </div>
    </div>

    <!-- TARJETA VIP ELITE -->
    <div class="vip-card-container">
        <?php 
            $pts = (int)($cliente['puntos'] ?? 0);
            $levelClass = 'level-bronze';
            $levelName  = 'MIEMBRO BRONCE';
            if ($pts > 1500) { $levelClass = 'level-platinum'; $levelName = 'MIEMBRO PLATINUM'; }
            elseif ($pts > 500) { $levelClass = 'level-gold'; $levelName = 'MIEMBRO GOLD'; }
        ?>
        <div class="vip-card">
            <div class="card-shine"></div>
            
            <div class="card-top">
                <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" class="card-logo">
                <span class="membership-level <?= $levelClass ?>"><?= $levelName ?></span>
            </div>

            <div class="card-middle">
                <div class="card-holder-label">Titular de Cuenta</div>
                <div class="card-holder-name"><?= htmlspecialchars($cliente['nombre']) ?></div>
                <div class="card-chip"></div>
            </div>

            <div class="card-bottom">
                <div class="card-id"><?= htmlspecialchars($cliente['codigo']) ?></div>
                <div class="card-points-display">
                    <span class="card-points-lbl">Saldo Total</span>
                    <b class="card-points-val" id="points-counter">0</b>
                    <span class="card-points-lbl">Puntos Surgas</span>
                </div>
            </div>
        </div>
    </div>

    <a href="<?= BASE_URL ?>tienda" class="btn-store shadow-lg">
        <i class='bx bxs-shopping-bag'></i> Explorar Recompensas
    </a>

    <div class="container">
        <div class="section-title">
            <span>Actividad Reciente</span>
            <i class='bx bx-chevron-right' style="opacity:0.3"></i>
        </div>
        
        <div class="history-card">
            <?php if (empty($ventas)): ?>
                <div style="padding: 3rem 2rem; text-align: center; opacity: 0.4; font-size: 0.9rem;">
                    <i class='bx bx-info-circle' style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                    Aún no tienes movimientos registrados.
                </div>
            <?php else: ?>
                <?php foreach (array_slice($ventas, 0, 5) as $v): 
                    $detalleRaw = $v['detalle'] ?? '';
                    $conceptos = array_filter(explode(', ', $detalleRaw));
                    $esMultiple = count($conceptos) > 1;
                ?>
                <div class="history-item" <?= $esMultiple ? 'onclick="toggleDetail(this)"' : '' ?>>
                    <div class="history-main-row">
                        <div class="item-name">
                            <i class='bx <?= $v['monto'] > 0 ? 'bx-plus-circle' : 'bx-gift' ?>'></i>
                            <?= $esMultiple ? 'Carga de puntos múltiple' : htmlspecialchars($detalleRaw ?: 'Operación del sistema') ?>
                        </div>
                        <span class="item-pts">+<?= $v['puntos'] ?></span>
                    </div>
                    <span class="history-date"><?= date('d M, Y', strtotime($v['fecha'])) ?></span>

                    <?php if ($esMultiple): ?>
                        <div class="detail-list" style="display: none;">
                            <?php foreach ($conceptos as $c): 
                                $nombreItem = htmlspecialchars($c);
                                $ptsItem = '';
                                if (preg_match('/(.*)\(\+([0-9]+)\s*pts\)/', $c, $matches)) {
                                    $nombreItem = trim($matches[1]);
                                    $ptsItem = '+' . $matches[2] . ' pts';
                                }
                            ?>
                                <div class="detail-subitem">
                                    <span><?= $nombreItem ?></span>
                                    <span style="font-weight:700"><?= $ptsItem ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            PremiaSurgas Digital Member Card <br>
            &copy; <?= date('Y') ?> Surgas S.A.
        </div>
    </div>

    <img src="<?= BASE_URL ?>assets/premios/gas.png" class="mascot-profile" alt="Mascota">

    <script>
        // Points Counter Animation
        document.addEventListener('DOMContentLoaded', () => {
            const pointsTarget = <?= (int) ($cliente['puntos'] ?? 0) ?>;
            const pointsElement = document.getElementById('points-counter');
            let currentPoints = 0;
            const duration = 2000; 
            const steps = 60;
            const increment = pointsTarget / steps;
            const intervalTime = duration / steps;

            const timer = setInterval(() => {
                currentPoints += increment;
                if (currentPoints >= pointsTarget) {
                    pointsElement.textContent = Math.floor(pointsTarget).toLocaleString();
                    clearInterval(timer);
                } else {
                    pointsElement.textContent = Math.floor(currentPoints).toLocaleString();
                }
            }, intervalTime);
        });

        function toggleDetail(el) {
            const list = el.querySelector('.detail-list');
            if (list.style.display === 'none') {
                list.style.display = 'block';
            } else {
                list.style.display = 'none';
            }
        }
    </script>
    <script> const BASE_URL = '<?= BASE_URL ?>'; </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
