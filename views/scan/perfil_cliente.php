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
    <style>
        :root { 
            --primary: #821515; 
            --bg-color: #f7f9fc;
            --text-main: #1a1c1e;
            --silver-text: linear-gradient(135deg, #1a1a1a 0%, #444444 50%, #1a1a1a 100%);
            --silver-metal: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
            --card-silver: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
        }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--bg-color);
            background: radial-gradient(circle at top right, #ffffff 0%, #eef2f7 100%);
            background-attachment: fixed;
            margin: 0; 
            color: var(--text-main); 
            min-height: 100vh;
            overflow-x: hidden;
        }

        ::selection {
            background: rgba(130, 21, 21, 0.2);
            color: var(--primary);
        }

        
        /* Layout */
        .header-wrapper {
            background: linear-gradient(135deg, var(--primary) 0%, #4a0b0b 100%);
            padding: 3.5rem 1.5rem 7rem;
            color: white;
            position: relative;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            box-shadow: 0 15px 40px rgba(130, 21, 21, 0.2);
            margin-bottom: 0;
        }

        .header-content {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }

        .user-greeting {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 0;
        }

        .profile-avatar {
            width: 55px; height: 55px; 
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.3);
            color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; font-weight: 700;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .greeting-text h1 { 
            margin: 0; 
            font-size: 1.6rem; 
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #fff;
        }
        .greeting-text p { margin: 0; font-size: 0.85rem; opacity: 0.7; text-transform: uppercase; letter-spacing: 1px; color: #fff; }

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
        .history-main-row { display: flex; flex-direction: column; gap: 10px; }
        .item-name { font-size: 0.9rem; font-weight: 600; display: flex; flex-direction: column; gap: 6px; color: #444; width: 100%; }
        .item-name i { color: var(--primary); font-size: 1.1rem; }
        .item-pts { color: var(--text-main); font-weight: 800; font-size: 1.1rem; }
        .history-date { font-size: 0.75rem; opacity: 0.6; margin-top: 4px; color: #64748b; }

        .footer { text-align: center; padding: 3rem 0; color: rgba(0,0,0,0.3); font-size: 0.75rem; }
        
        .logout-btn-client {
            position: absolute; top: -0.5rem; right: 0; color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.1); width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(10px);
        }

        .tab-switcher {
            display: flex;
            background: #fff;
            padding: 6px;
            border-radius: 100px;
            margin-bottom: 2rem;
            border: 1px solid #eef2f7;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
        }
        .tab-btn {
            flex: 1;
            padding: 12px 10px;
            border-radius: 100px;
            text-align: center;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .tab-btn i { font-size: 1.1rem; }
        .tab-btn.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 8px 20px rgba(130, 21, 21, 0.2);
        }
        .tab-content-pane {
            display: none;
            animation: paneFadeIn 0.5s ease;
}

        .tab-content-pane.active {
            display: block;
        }
        @keyframes paneFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .history-item {
            padding: 1rem 0.5rem; border-bottom: 1px solid #f1f5f9;
            transition: 0.3s;
        }

        .canje-wallet-card {
            background: #fff; border-radius: 20px; padding: 1rem;
            display: flex; align-items: center; gap: 1rem;
            border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            position: relative; overflow: hidden;
        }

        .canje-icon-circle {
            width: 48px; height: 48px; border-radius: 14px;
            background: #fff1f2; color: #e11d48;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; flex-shrink: 0;
            border: 1px solid #ffe4e6;
        }

        .canje-info-main { flex-grow: 1; min-width: 0; }
        .canje-prize-title { font-size: 0.95rem; font-weight: 850; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2; }
        
        .modality-tag-mini {
            font-size: 0.6rem; font-weight: 800; color: #821515;
            background: rgba(130, 21, 21, 0.05); padding: 2px 8px;
            border-radius: 6px; display: inline-block; margin-top: 2px;
            text-transform: uppercase;
        }

        .canje-info-meta { font-size: 0.7rem; color: #94a3b8; font-weight: 600; margin-top: 4px; }

        .canje-metrics-side { text-align: right; margin-right: 2.5rem; }
        .canje-pts-val { font-size: 1rem; font-weight: 900; color: #e11d48; line-height: 1; }
        .canje-status-pill {
            font-size: 0.55rem; font-weight: 900; text-transform: uppercase;
            padding: 3px 8px; border-radius: 50px; margin-top: 6px;
            display: inline-block; letter-spacing: 0.5px;
        }

        .btn-float-ticket {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            width: 32px; height: 32px; border-radius: 10px;
            background: #f8fafc; border: 1px solid #eef2f7; color: #64748b;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s;
        }
        .btn-float-ticket:hover { background: #821515; color: #fff; border-color: #821515; }
        .modality-badge {
            font-size: 0.55rem; font-weight: 900; color: #821515; 
            background: #ffebeb; padding: 2px 8px; border-radius: 50px;
            text-transform: uppercase; letter-spacing: 0.5px;
            display: inline-block; margin-top: 4px;
        }

        .btn-view-ticket {
            background: #fff; color: #821515; border: 1.5px solid #ffebeb;
            width: 32px; height: 32px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            text-decoration: none;
        }
        .btn-view-ticket:hover { background: #821515; color: #fff; transform: translateY(-2px); }

        .canje-badge {
            padding: 4px 10px; border-radius: 50px; font-size: 0.6rem;
            font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;
            margin-top: 8px; display: inline-block;
        }
        .badge-pendiente { background: #fff7ed; color: #d97706; border: 1px solid #ffedd5; }
        .badge-pago_aprobado { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
        .badge-canjeado { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .badge-rechazado { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }
        .badge-pago_pendiente { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }

        .flip-hint { 
            width: 100%;
            text-align: center; 
            font-size: 0.7rem; 
            opacity: 0.4; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            margin: -1.5rem auto 2rem; 
        }        /* HORIZONTAL VOUCHER MODAL STYLE */
        /* HORIZONTAL VOUCHER MODAL STYLE */
        .ticket-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem; animation: fadeInOverlay 0.3s ease;
            z-index: 10000;
        }

        .ticket-container {
            background: #fff; width: 100%; max-width: 550px;
            border-radius: 24px; overflow: hidden;
            box-shadow: 0 40px 120px rgba(0,0,0,0.6);
            display: flex; /* Mandatory for horizontal */
            animation: ticketPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative; border: 1px solid rgba(255,255,255,0.1);
        }

        .ticket-side-brand {
            background: #821515; width: 85px; flex-shrink: 0;
            display: flex; flex-direction: column; align-items: center; justify-content: space-between;
            padding: 2rem 0; color: #fff; position: relative;
        }
        .side-title {
            writing-mode: vertical-lr; transform: rotate(180deg);
            font-weight: 900; letter-spacing: 4px; font-size: 0.9rem;
            opacity: 0.9; text-transform: uppercase;
        }
        .side-logo-mini { width: 45px; }

        .ticket-perforation {
            width: 2px; border-left: 2px dashed #e2e8f0;
            margin: 1.5rem 0; position: relative;
        }
        .ticket-perforation::before, .ticket-perforation::after {
            content: ''; position: absolute; left: -10px;
            width: 18px; height: 18px; border-radius: 50%; background: #0f172a;
        }
        .ticket-perforation::before { top: -24px; }
        .ticket-perforation::after { bottom: -24px; }

        .ticket-main-content { flex-grow: 1; padding: 2.2rem; position: relative; }
        .ticket-prize-name {
            font-size: 1.9rem; font-weight: 900; color: #1e293b;
            line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.5px;
        }

        .ticket-details-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;
            margin-bottom: 1.8rem;
        }
        .t-detail-item { display: flex; flex-direction: column; }
        .t-detail-label { font-size: 0.6rem; font-weight: 850; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px; }
        .t-detail-val { font-size: 0.9rem; font-weight: 750; color: #334155; }

        .ticket-status-row {
            border-top: 1px solid #f1f5f9; padding-top: 1.2rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        .valid-badge {
            color: #16a34a; font-weight: 900; font-size: 0.85rem;
            display: flex; align-items: center; gap: 6px; border: 2px solid #dcfce7;
            padding: 5px 14px; border-radius: 50px; background: #f0fdf4;
        }
        .ticket-footer-hint { font-size: 0.65rem; font-weight: 850; color: #821515; text-transform: uppercase; letter-spacing: 0.5px; }

        @keyframes ticketPop {
            0% { transform: scale(0.9) translateY(40px); opacity: 0; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }
        @keyframes fadeInOverlay { from { opacity: 0; } to { opacity: 1; } }

        /* Responsive Mobile Ticket Enhancements */
        @media (max-width: 520px) {
            .ticket-container { 
                flex-direction: column; 
                max-width: 340px; 
                border-radius: 28px;
                box-shadow: 0 40px 80px rgba(0,0,0,0.6);
            }
            .ticket-side-brand { 
                width: 100%; height: 80px; 
                flex-direction: row; padding: 0 1.5rem; 
                clip-path: polygon(0 0, 100% 0, 100% 85%, 0 85%); 
            }
            .side-title { writing-mode: horizontal-tb; transform: none; letter-spacing: 2px; font-size: 0.8rem; }
            .side-logo-mini { width: 35px; }

            .ticket-perforation { 
                width: 100%; height: 2px; border-left: none; 
                border-top: 2px dashed #e2e8f0; margin: 0;
            }
            .ticket-perforation::before, .ticket-perforation::after {
                width: 24px; height: 24px; background: #0f172a; top: -11px;
            }
            .ticket-perforation::before { left: -14px; }
            .ticket-perforation::after { right: -14px; }

            .ticket-main-content { padding: 1.8rem 1.5rem; text-align: center; }
            .ticket-prize-name { font-size: 1.6rem; margin-bottom: 1.2rem; }
            .ticket-details-grid { grid-template-columns: 1fr; gap: 0.8rem; }
            .t-detail-item { align-items: center; }
            .t-detail-val { font-size: 1rem; }

            .ticket-status-row { flex-direction: column; gap: 1rem; border-top: 2px dashed #f1f5f9; padding-top: 1.5rem; }
            .valid-badge { width: 100%; justify-content: center; padding: 10px; font-size: 1rem; }
        }

        .btn-view-ticket {
            width: 32px; height: 32px; border-radius: 10px;
            background: #f1f5f9; color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; border: none; cursor: pointer; transition: 0.3s;
        }
        .btn-view-ticket:hover { background: var(--primary); color: #fff; transform: scale(1.1); }

        [v-cloak] { display: none; }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <div class="header-content">
            <a href="<?= BASE_URL ?>logout" class="logout-btn-client" title="Cerrar Sesión">
                <i class='bx bx-log-out'></i>
            </a>
            <div class="user-greeting">
                <div class="profile-avatar"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
                <div class="greeting-text">
                    <p>Digital Membership</p>
                    <h1>¡Hola, <?= explode(' ', $cliente['nombre'])[0] ?>!</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- VIP CARD BLACK EDITION (3D FLIP) -->
    <div class="vip-card-container" id="profileCard">
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

    <div class="flip-hint"><i class='bx bx-refresh'></i> Toca la tarjeta para ver tu QR</div>

    <a href="<?= BASE_URL ?>tienda" class="btn-store">
        <i class='bx bxs-shopping-bag'></i> Ir a la tienda
    </a>

    <div class="container">
        
        <!-- Tab Switcher -->
        <div class="tab-switcher">
            <div class="tab-btn active" onclick="switchTab('actividad', this)">
                <i class='bx bx-time-five'></i> Actividad
            </div>
            <div class="tab-btn" onclick="switchTab('canjes', this)">
                <i class='bx bx-gift'></i> Canjes
            </div>
        </div>

        <!-- PANE 1: ACTIVIDAD -->
        <div id="pane-actividad" class="tab-content-pane active">
            <div class="section-title">Actividad Reciente</div>
            <div class="history-card">
                <?php if (empty($ventas)): ?>
                    <div style="padding: 3rem 2rem; text-align: center; opacity: 0.3; font-size: 0.9rem;">No hay movimientos recientes.</div>
                <?php else: ?>
                    <?php foreach (array_slice($ventas, 0, 10) as $v): ?>
                    <div class="history-item">
                        <div class="history-main-row">
                            <div class="item-name" style="line-height: 1.5; background: #f8fafc; padding: 0.8rem 1rem; border-radius: 16px; border: 1px solid #eef2f7; font-size: 0.85rem; color: #475569; width: 100%;">
                                <?php if (!empty($v['items'])): ?>
                                    <div style="display: flex; flex-direction: column; gap: 4px; width: 100%;">
                                        <?php foreach ($v['items'] as $it): ?>
                                            <div style="display: flex; justify-content: space-between; align-items: baseline; width: 100%;">
                                                <span>• <?= htmlspecialchars($it['nombre_item']) ?> x<?= $it['cantidad'] ?></span>
                                                <b style="color: #1e293b; margin-left: 8px;">+<?= $it['puntos_subtotal'] ?> pts</b>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <?php 
                                        $detalleLimpio = str_replace(['), ', ', Recarga'], [")\n• Recarga", "\n• Recarga"], $v['detalle'] ?? '');
                                        if (!empty($detalleLimpio) && !str_starts_with($detalleLimpio, '•') && !str_starts_with($detalleLimpio, 'Compra')) {
                                            $detalleLimpio = '• ' . $detalleLimpio;
                                        }
                                        echo nl2br(htmlspecialchars($detalleLimpio));
                                    ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.8rem; padding: 0 0.5rem;">
                            <span class="history-date" style="padding-left: 0;"><?= date('d/m/Y', strtotime($v['fecha'])) ?></span>
                            <span class="item-pts" style="font-size: 0.95rem; color: var(--primary);">+<?= $v['puntos'] ?> pts</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- PANE 2: CANJES -->
        <div id="pane-canjes" class="tab-content-pane">
            <div class="section-title">Historial de Canjes</div>
            <div class="history-card">
                <?php if (empty($canjes)): ?>
                    <div style="padding: 3rem 2rem; text-align: center; opacity: 0.3; font-size: 0.9rem;">Aún no has canjeado premios.</div>
                <?php else: ?>
                    <?php foreach ($canjes as $c): ?>
                    <?php 
                        // Calcular modalidad
                        $modStr = 'Canje Total';
                        if ($c['monto'] > 0) {
                            $modStr = !empty($c['comprobante_url']) ? 'Puntos + Depósito' : 'Puntos + Efectivo';
                        }
                    ?>
                    <div class="history-item">
                        <div class="canje-wallet-card">
                            <div class="canje-icon-circle">
                                <i class='bx bx-gift'></i>
                            </div>

                            <div class="canje-info-main">
                                <div class="canje-prize-title"><?= htmlspecialchars($c['premio_nombre']) ?></div>
                                <div class="modality-tag-mini"><?= $modStr ?></div>
                                <div class="canje-info-meta">
                                    <i class='bx bx-calendar-alt'></i> <?= date('d/m/Y', strtotime($c['fecha'])) ?>
                                </div>
                            </div>

                            <div class="canje-metrics-side">
                                <div class="canje-pts-val">-<?= $c['puntos_usados'] ?> pts</div>
                                <?php if ($c['monto'] > 0): ?>
                                    <div style="font-size: 0.7rem; font-weight: 950; color: #1e293b; margin-top: 1px;">+ S/ <?= number_format($c['monto'], 2) ?></div>
                                <?php endif; ?>
                                <div class="canje-status-pill badge-<?= $c['estado'] ?>">
                                    <?= str_replace('_', ' ', $c['estado']) ?>
                                </div>
                            </div>

                            <?php if ($c['estado'] === 'pago_aprobado' || $c['estado'] === 'pendiente'): ?>
                                <button class="btn-float-ticket" title="Ver Ticket para reclamar" 
                                        onclick='showClaimTicket(<?= json_encode([
                                            "id"     => $c["id"],
                                            "premio" => $c["premio_nombre"],
                                            "puntos" => $c["puntos_usados"],
                                            "monto"  => $c["monto"],
                                            "modalidad" => $modStr,
                                            "fecha"  => date("d/m/Y H:i", strtotime($c["fecha"]))
                                        ]) ?>)'>
                                    <i class="bx bx-show"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer">
            &copy; <?= date('Y') ?> Surgas — Premium Digital Member Card
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

        // Tab Switching Logic
        function switchTab(paneId, btnElement) {
            // Remove active from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            // Add active to current button
            btnElement.classList.add('active');

            // Hide all panes
            document.querySelectorAll('.tab-content-pane').forEach(pane => pane.classList.remove('active'));
            // Show selected pane
            document.getElementById('pane-' + paneId).classList.add('active');
        }

        // Ticket Viewer Logic (Horizontal Style)
        function showClaimTicket(data) {
            const overlay = document.createElement('div');
            overlay.className = 'ticket-overlay';
            overlay.onclick = (e) => { if (e.target === overlay) overlay.remove(); };

            const montoRow = data.monto > 0 ? `
                <div class="t-detail-item">
                    <span class="t-detail-label">Pago Adicional</span>
                    <span class="t-detail-val" style="color: #e11d48;">S/ ${parseFloat(data.monto).toFixed(2)}</span>
                </div>
            ` : '';

            overlay.innerHTML = `
                <div class="ticket-container">
                    <!-- Sidebar -->
                    <div class="ticket-side-brand">
                        <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" class="side-logo-mini" style="filter: brightness(0) invert(1);">
                        <span class="side-title">PRÉMIASURGAS</span>
                        <i class='bx bxs-star' style='font-size: 1.2rem; opacity: 0.5;'></i>
                    </div>

                    <!-- Perforation -->
                    <div class="ticket-perforation"></div>

                    <!-- Main Content -->
                    <div class="ticket-main-content">
                        <div class="ticket-prize-name">${data.premio}</div>
                        
                        <div class="ticket-details-grid">
                            <div class="t-detail-item">
                                <span class="t-detail-label">Modalidad</span>
                                <span class="t-detail-val">${data.modalidad}</span>
                            </div>
                             <div class="t-detail-item">
                                <span class="t-detail-label">Deducción</span>
                                <span class="t-detail-val">${data.puntos} pts</span>
                            </div>
                            <div class="t-detail-item">
                                <span class="t-detail-label">Nro. Transacción</span>
                                <span class="t-detail-val">#${String(data.id).padStart(5, '0')}</span>
                            </div>
                            <div class="t-detail-item">
                                <span class="t-detail-label">Fecha Canje</span>
                                <span class="t-detail-val">${data.fecha.split(' ')[0]}</span>
                            </div>
                            ${montoRow}
                        </div>

                        <div class="ticket-status-row">
                            <span class="valid-badge">
                                <i class='bx bxs-check-shield'></i> VÁLIDO
                            </span>
                            <span class="ticket-footer-hint">
                                <i class='bx bxs-map-pin'></i> RECLAMAR EN DEPÓSITO
                            </span>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
        }

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
