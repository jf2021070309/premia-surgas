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
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: #2b0303;
            background: radial-gradient(circle at center, #5e0a0a 0%, #2b0303 100%);
            background-attachment: fixed;
            margin: 0; 
            color: white; 
            min-height: 100vh;
        }
        
        .header-wrapper {
            padding: 2.5rem 1.5rem 1rem;
            text-align: center;
            position: relative;
        }

        .profile-avatar {
            width: 70px; height: 70px; background: #f39c12; color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem; font-weight: 700; margin: 0 auto 1.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }

        .header-wrapper h1 { 
            margin: 0; 
            font-size: 2.3rem; 
            font-weight: 700;
            letter-spacing: -1px;
            color: white;
        }

        .tu-saldo {
            margin-top: 2.5rem;
            font-size: 1.0rem;
            font-weight: 700;
            letter-spacing: 6px;
            text-transform: uppercase;
            opacity: 0.8;
            color: #eee;
        }

        .points-hero {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin: 1.5rem 0 2rem;
        }

        .mascot-container {
            width: 210px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .mascot-container img {
            width: 100%;
            height: auto;
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.6));
            animation: mascotFloat 4s ease-in-out infinite;
        }

        @keyframes mascotFloat {
            0%, 100% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }

        .points-box {
            text-align: center;
            min-width: 140px; /* Fixed width prevents shifting during counting */
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .points-large {
            font-size: 7.5rem;
            font-weight: 800;
            line-height: 0.8;
            display: block;
            color: white;
            text-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .points-label-text {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 6px;
            text-transform: uppercase;
            display: block;
            margin-top: 10px;
            color: rgba(255,255,255,0.9);
        }

        .btn-store {
            background: #000000;
            color: white; 
            text-decoration: none; 
            padding: 1rem 2.5rem; 
            border-radius: 1.2rem;
            display: flex; align-items: center; justify-content: center; gap: 0.8rem;
            font-weight: 700; font-size: 1rem; 
            box-shadow: 0 12px 35px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
            width: 90%;
            max-width: 380px;
            margin: 0 auto 3.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-store::after {
            content: '';
            position: absolute;
            top: 0;
            left: -150%;
            width: 50%;
            height: 100%;
            background: linear-gradient(
                90deg, 
                transparent, 
                rgba(255, 255, 255, 0.15), 
                transparent
            );
            transform: skewX(-20deg);
            animation: shine 4s infinite;
        }

        @keyframes shine {
            0% { left: -150%; }
            25% { left: 150%; }
            100% { left: 150%; }
        }

        .btn-store i { font-size: 1.4rem; }
        .btn-store:hover { background: #111; transform: translateY(-3px); box-shadow: 0 20px 50px rgba(0,0,0,0.8); }
        .btn-store:active { transform: scale(0.96); }

        .container { 
            padding: 0 1.5rem 3rem; 
            max-width: 500px; 
            margin: 0 auto; 
            background: transparent;
            color: white;
        }

        .section-title { 
            display: flex; align-items: center; gap: 8px;
            font-size: 1.25rem; font-weight: 700; color: white; 
            margin: 2.5rem 0 1.5rem; padding-left: 0; 
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .section-title::before {
            content: '';
            display: block;
            width: 5px;
            height: 24px;
            background: #ff3333;
            box-shadow: 8px 0 0 #ff3333;
            margin-right: 15px;
            border-radius: 2px;
        }
        .section-title i { display: none; }

        .history-card { 
            background: #ffffff; 
            border-radius: 1.8rem; 
            padding: 0;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            color: #333;
            margin-top: 1rem;
            overflow: hidden; /* Fixes overlapping corners */
        }

        
        .history-item { 
            display: block; 
            padding: 1.25rem 1.5rem; 
            border-bottom: 1px solid #f6f6f6; 
            transition: all 0.3s ease;
            position: relative;
        }
        .history-item:last-child { border-bottom: none; }
        .history-item.has-details:hover { background: #fafafa; }
        
        .history-main-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-info-titles {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .history-info-titles i { color: #cbd5e0; font-size: 1.2rem; }

        .item-name { 
            font-size: 1rem; 
            font-weight: 700; 
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .item-pts { 
            color: var(--primary); 
            font-weight: 800; 
            font-size: 1.1rem;
            background: rgba(130, 21, 21, 0.05);
            padding: 4px 10px;
            border-radius: 8px;
        }
        
        .history-date { display: block; font-size: 0.85rem; color: #aab; margin-top: 6px; font-weight: 400; }

        .btn-expand {
            font-size: 0.65rem; 
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f4f6f8;
            color: #555;
            padding: 8px 16px;
            border-radius: 50px;
            border: 1px solid #eee;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .btn-expand:hover {
            background: #e9ecef;
            border-color: #ddd;
        }
        
        .history-item.active { background: #fafafa; }
        .history-item.active .btn-expand { background: #eee; }

        .detail-list { 
            margin-top: 1rem; 
            padding: 1rem 0 0.5rem 0; 
            border-top: 1px dashed #eee;
            width: 100%;
        }
        .detail-subitem { 
            font-size: 0.95rem; 
            padding: 0.6rem 0; 
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sub-name { color: #555; font-weight: 500; }
        .sub-pts { 
            color: #555; 
            font-weight: 700; 
            font-size: 0.95rem;
            background: #f0f2f5;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .footer { text-align: center; padding: 2.5rem 0; color: rgba(255,255,255,0.4); font-size: 0.85rem; line-height: 1.6; }
        
        .logout-btn-client {
            position: absolute;
            top: 2rem;
            right: 1.5rem;
            color: rgba(255,255,255,0.3);
            font-size: 1.6rem;
            transition: all 0.3s;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .logout-btn-client:hover {
            color: #ff4d4d;
            background: rgba(255,77,77,0.1);
            transform: scale(1.1) rotate(90deg);
        }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <a href="<?= BASE_URL ?>logout" class="logout-btn-client" title="Cerrar Sesión">
            <i class='bx bx-log-out'></i>
        </a>
        <div class="profile-avatar"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
        <h1>¡Hola, <?= explode(' ', $cliente['nombre'])[0] ?>!</h1>
        
        <div class="tu-saldo">Tu saldo actual</div>
        
        <div class="points-hero">
            <div class="mascot-container">
                <img src="<?= BASE_URL ?>assets/premios/gas.png" alt="Mascota Surgas">
            </div>
            <div class="points-box">
                <b class="points-large" id="points-counter">0</b>
                <span class="points-label-text">Puntos</span>
            </div>
        </div>

        <a href="<?= BASE_URL ?>tienda" class="btn-store">
            <i class='bx bxs-shopping-bag'></i> Ir a la tienda
        </a>
    </div>

    <div class="container">

        <div class="section-title"><i class='bx bx-history'></i> Mi Historial Reciente</div>
        
        <div class="history-card">
            <?php if (empty($ventas)): ?>
                <div style="padding: 2rem; text-align: center; color: #ccc;">Aún no tienes movimientos registrados.</div>
            <?php else: ?>
                <?php foreach (array_slice($ventas, 0, 5) as $v): 
                    $detalleRaw = $v['detalle'] ?? '';
                    $conceptos = array_filter(explode(', ', $detalleRaw));
                    $esMultiple = count($conceptos) > 1;
                ?>
                <div class="history-item <?= $esMultiple ? 'has-details' : '' ?>" <?= $esMultiple ? 'onclick="toggleDetail(this)"' : '' ?>>
                    <div class="history-main-row">
                        <div class="history-info-titles">
                            <span class="item-name">
                                <i class='bx <?= $esMultiple ? 'bx-list-plus' : 'bx-badge-check' ?>'></i>
                                <?= $esMultiple ? 'Carga de puntos' : htmlspecialchars($detalleRaw ?: ($v['monto'] > 0 ? 'Compra realizada' : 'Carga de puntos')) ?>
                            </span>
                            <span class="item-pts">+<?= $v['puntos'] ?></span>
                        </div>
                        <?php if ($esMultiple): ?>
                            <span class="btn-expand">Ver más ▼</span>
                        <?php endif; ?>
                    </div>
                    
                    <span class="history-date"><?= date('d/m/Y', strtotime($v['fecha'])) ?></span>

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
                                    <span class="sub-name"><?= $nombreItem ?></span>
                                    <?php if ($ptsItem): ?>
                                        <span class="sub-pts"><?= $ptsItem ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            PremiaSurgas &copy; <?= date('Y') ?> <br>
            ¡Sigue acumulando para canjear increíbles premios!
        </div>
    </div>

    <script>
        // Points Counter Animation
        document.addEventListener('DOMContentLoaded', () => {
            const pointsTarget = <?= (int) ($cliente['puntos'] ?? 0) ?>;
            const pointsElement = document.getElementById('points-counter');
            let currentPoints = 0;
            const duration = 1500; // 1.5 seconds
            const steps = 60;
            const increment = pointsTarget / steps;
            const intervalTime = duration / steps;

            const timer = setInterval(() => {
                currentPoints += increment;
                if (currentPoints >= pointsTarget) {
                    pointsElement.textContent = Math.floor(pointsTarget).toLocaleString();
                    clearInterval(timer);
                } else {
                    // Update only text content to avoid layout re-calculation if possible
                    pointsElement.textContent = Math.floor(currentPoints);
                }
            }, intervalTime);
        });

        function toggleDetail(el) {
            const list = el.querySelector('.detail-list');
            const btn = el.querySelector('.btn-expand');
            if (list.style.display === 'none') {
                list.style.display = 'block';
                el.classList.add('active');
                if(btn) btn.innerHTML = 'VER MENOS ▲';
            } else {
                list.style.display = 'none';
                el.classList.remove('active');
                if(btn) btn.innerHTML = 'VER MÁS ▼';
            }
        }
    </script>
</body>
</html>
