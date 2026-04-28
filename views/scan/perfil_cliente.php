<!DOCTYPE html>
<html lang="es">
<?php
$isDefaultPassword = false;
$hpw = $cliente['password'] ?? '';
if (empty($hpw)) {
    // Si no tiene contraseña, es inseguro y se considera "default" para forzar cambio
    $isDefaultPassword = true;
} else {
    $dni = trim($cliente['dni'] ?? '');
    $ruc = trim($cliente['ruc'] ?? '');

    $checkDni = $dni ? hash('sha256', $dni) : '---no-dni---';
    $checkRuc = $ruc ? hash('sha256', $ruc) : '---no-ruc---';

    if ($hpw === $checkDni || $hpw === $checkRuc) {
        $isDefaultPassword = true;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #400000;
            --bg-color: #f3f4f6; /* Sophisticated Obsidian Grey */
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --text-main: #1e293b;
            --silver-text: linear-gradient(135deg, #1a1a1a 0%, #444444 50%, #1a1a1a 100%);
            --silver-metal: linear-gradient(135deg, #70706F, #E9E9E7, #70706F, #E9E9E7, #70706F);
            --card-silver: linear-gradient(135deg, #a8a8a8 0%, #ffffff 50%, #a8a8a8 100%);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-color);
            margin: 0;
            color: #1e293b;
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
            width: 55px;
            height: 55px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        .greeting-text h1 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #fff;
        }

        .greeting-text p {
            margin: 0;
            font-size: 0.85rem;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff;
        }

        /* --- 3D FLIP CARD BLACK EDITION --- */
        .vip-card-container {
            perspective: 1500px;
            margin: 0;
            max-width: 420px;
            width: 100%;
            aspect-ratio: 1.58 / 1;
            cursor: pointer;
            z-index: 10;
        }

        /* Layout superior del Perfil */
        .profile-header-layout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3rem;
            max-width: 1000px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }

        .profile-card-column {
            flex: 0 0 auto;
            width: 100%;
            max-width: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-info-column {
            flex: 1;
            background: #fff;
            border-radius: 32px;
            padding: 3rem;
            /* Multi-layered shadow for better depth and visibility */
            box-shadow: 
                0 10px 20px rgba(0, 0, 0, 0.02),
                0 30px 70px rgba(0, 0, 0, 0.07);
            border: 1px solid #e2e8f0;
            min-width: 320px;
            position: relative;
        }

        .info-grid-modern {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-top: 2.5rem;
        }

        .info-item-elegant {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            transition: all 0.3s ease;
            padding: 1rem;
            border-radius: 16px;
        }

        .info-item-elegant:hover {
            background: #f8fafc;
        }

        .info-item-elegant.full-width {
            grid-column: 1 / -1;
        }

        .info-header-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-icon-elegant {
            font-size: 1.2rem;
            color: #64748b; /* Slate neutral */
            opacity: 0.8;
        }

        .info-label-elegant {
            font-size: 0.65rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .info-value-elegant {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a; /* Deeper black */
            padding-left: 0; /* Removing fixed padding for new flex layout */
        }

        .info-value-elegant.premium-status {
            color: var(--primary); /* Use brand red instead of purple */
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-verified-minimal {
            background: #f8fafc;
            color: #64748b;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid #e2e8f0;
        }

        @media (max-width: 991px) {
            .profile-header-layout {
                flex-direction: column;
                gap: 2rem;
                margin: 2rem auto;
            }

            .profile-info-column {
                width: 100%;
                max-width: 420px;
                padding: 1.5rem !important;
            }
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

        .card-front,
        .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 32px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            color: white;
            /* Forzar texto blanco dentro de la tarjeta */
        }

        /* LADO FRONTAL: BLACK METALLIC */
        .card-front {
            background: #0a0a0a;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 40%),
                linear-gradient(45deg, #0a0a0a 0%, #1a1a1a 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.8rem;
        }

        /* Efecto de Brillo Silver */
        .card-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            transform: skewX(-25deg);
            animation: cardShineEffect 8s infinite;
        }

        @keyframes cardShineEffect {
            0% {
                left: -150%;
            }

            15% {
                left: 150%;
            }

            100% {
                left: 150%;
            }
        }

        .vip-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .card-logo {
            height: 26px;
            filter: brightness(0) invert(1) opacity(0.8);
        }

        .membership-badge {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 2px;
            background: var(--silver-metal);
            color: #111;
            padding: 6px 14px;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .card-middle {
            text-align: left;
        }

        .label-small {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.4;
            margin-bottom: 4px;
        }

        .holder-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .client-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            letter-spacing: 2.5px;
            opacity: 0.6;
        }

        .points-box {
            text-align: right;
        }

        .points-val {
            font-size: 2.4rem;
            font-weight: 800;
            line-height: 1;
            display: block;
            background: var(--card-silver);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .points-unit {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.6;
        }

        /* LADO TRASERO: QR & INFO */
        .card-back {
            background: #0a0a0a;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-image:
                radial-gradient(circle at center, #1a1a1a 0%, #050505 100%);
        }

        .qr-container {
            background: white;
            padding: 10px;
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #qrcode canvas, #qrcode img {
            display: block;
            max-width: 100%;
            height: auto !important;
        }

        .qr-help {
            margin-top: 1.2rem;
            font-size: 0.65rem;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #fff;
            opacity: 0.8;
        }

        /* Botón Tienda */
        .btn-store {
            background: #111;
            color: #fff;
            text-decoration: none;
            padding: 1.2rem 2.5rem;
            border-radius: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            width: 85%;
            max-width: 380px;
            margin: 0 auto 3.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: none;
        }

        .btn-store:hover {
            background: #000;
            transform: translateY(-3px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        /* Historial */
        .container {
            padding: 0 2rem 4rem;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            opacity: 0.8;
            letter-spacing: 1px;
        }

        .history-card {
            background: white;
            border-radius: 2rem;
            border: 1px solid #eef2f7;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .history-item {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f0f4f8;
            transition: 0.3s;
        }

        .history-item:active {
            background: #f8fafd;
        }

        .history-main-row {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .item-name {
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            gap: 6px;
            color: #444;
            width: 100%;
        }

        .item-name i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        .item-pts {
            color: var(--text-main);
            font-weight: 800;
            font-size: 1.1rem;
        }

        .history-date {
            font-size: 0.75rem;
            opacity: 0.6;
            margin-top: 4px;
            color: #64748b;
        }

        .footer {
            text-align: center;
            padding: 3rem 0;
            color: rgba(0, 0, 0, 0.3);
            font-size: 0.75rem;
        }

        .logout-btn-client {
            position: absolute;
            top: -0.5rem;
            right: 0;
            color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .tab-switcher {
            display: flex;
            background: #fff;
            padding: 6px;
            border-radius: 100px;
            margin-bottom: 2rem;
            border: 1px solid #eef2f7;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
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

        .tab-btn i {
            font-size: 1.1rem;
        }

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
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .history-item {
            padding: 1rem 0.5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: 0.3s;
        }

        .canje-wallet-card {
            background: #fff;
            border-radius: 20px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
        }

        .canje-icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #fff1f2;
            color: #e11d48;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
            border: 1px solid #ffe4e6;
        }

        .canje-info-main {
            flex-grow: 1;
            min-width: 0;
        }

        .canje-prize-title {
            font-size: 0.95rem;
            font-weight: 850;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .modality-tag-mini {
            font-size: 0.6rem;
            font-weight: 800;
            color: #821515;
            background: rgba(130, 21, 21, 0.05);
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
            margin-top: 2px;
            text-transform: uppercase;
        }

        .canje-info-meta {
            font-size: 0.7rem;
            color: #94a3b8;
            font-weight: 600;
            margin-top: 4px;
        }

        .canje-metrics-side {
            text-align: right;
        }

        .canje-pts-val {
            font-size: 1rem;
            font-weight: 900;
            color: #e11d48;
            line-height: 1;
        }

        .canje-status-pill {
            font-size: 0.55rem;
            font-weight: 900;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 50px;
            margin-top: 6px;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        .btn-float-ticket {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #eef2f7;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .btn-float-ticket:hover {
            background: #821515;
            color: #fff;
            border-color: #821515;
        }

        .modality-badge {
            font-size: 0.55rem;
            font-weight: 900;
            color: #821515;
            background: #ffebeb;
            padding: 2px 8px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-top: 4px;
        }

        .btn-view-ticket {
            background: #fff;
            color: #821515;
            border: 1.5px solid #ffebeb;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            text-decoration: none;
        }

        .btn-view-ticket:hover {
            background: #821515;
            color: #fff;
            transform: translateY(-2px);
        }

        .canje-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
            display: inline-block;
            border: 1px solid transparent;
        }

        .badge-pendiente {
            background: #fffbeb;
            color: #d97706;
            border-color: #fef3c7;
        }

        .badge-pago_aprobado {
            background: #f0fdf4;
            color: #16a34a;
            border-color: #dcfce7;
        }

        .badge-canjeado {
            background: #f8fafc;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .badge-rechazado {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fee2e2;
        }

        .badge-pago_pendiente {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #dbeafe;
        }

        /* ── Modern Activity Items ── */
        .activity-card-group {
            background: #fff;
            border-radius: 24px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }

        .activity-card-group:hover {
            transform: translateY(-3px);
        }

        .activity-date-header {
            font-size: 0.72rem;
            font-weight: 850;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .activity-date-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }

        .activity-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .activity-row:last-child {
            border-bottom: none;
        }

        .activity-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            background: #f8fafc;
            color: #475569;
        }

        .activity-details {
            display: flex;
            flex-direction: column;
        }

        .activity-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
        }

        .activity-subtitle {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .activity-pts {
            font-size: 1.05rem;
            font-weight: 900;
            color: #22c55e;
        }

        .activity-pts.red {
            color: #ef4444;
        }

        /* ── Elite Activity Table Overrides ── */
        .elite-table-wrapper {
            background: #fff;
            border-radius: 24px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            overflow-x: auto;
        }

        .elite-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .elite-table th {
            text-align: left;
            padding: 1rem 1.5rem;
            font-size: 0.65rem;
            font-weight: 850;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 1px;
            border-bottom: 1px solid #f1f5f9;
        }

        .elite-table td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            font-size: 0.88rem;
        }

        .elite-table tr:last-child td {
            border-bottom: none;
        }

        .elite-table tr:hover td {
            background: #fcfdfe;
        }

        .col-date {
            font-weight: 800;
            color: #1e293b;
            width: 160px;
        }

        .col-type {
            width: 140px;
            text-align: center;
        }

        .type-badge {
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 850;
            text-transform: uppercase;
            display: inline-block;
        }

        .badge-recarga {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #dcfce7;
        }

        .badge-compra {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #dbeafe;
        }

        .badge-vale {
            background: #f5f3ff;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }

        .col-detail {
            padding-left: 2rem !important;
        }

        .col-pts {
            text-align: right;
            font-weight: 900;
            font-size: 1.2rem;
            color: #22c55e;
            width: 150px;
            padding-right: 2rem !important;
        }

        @media (max-width: 768px) {
            .elite-table-wrapper {
                padding: 0.5rem;
                border-radius: 16px;
            }

            .elite-table th {
                display: none;
            }

            .elite-table td {
                display: block;
                padding: 1rem;
                border: none;
                text-align: left;
            }

            .elite-table tr {
                display: block;
                margin-bottom: 1rem;
                background: #fff;
                border: 1px solid #f1f5f9;
                border-radius: 16px;
                padding: 0.5rem;
            }

            .col-date {
                width: 100%;
                font-size: 0.75rem;
                color: #94a3b8;
            }

            .col-pts {
                text-align: left;
                width: 100%;
                margin-top: 0.5rem;
                font-size: 1.2rem;
            }

            .col-type {
                width: 100%;
                margin-top: 0.5rem;
            }
        }

        /* ── Elite Filter Bar ── */
        .filter-bar {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: flex-end;
            margin-bottom: 2rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            font-size: 0.65rem;
            font-weight: 800;
            color: #8a99af;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 8px;
            margin-left: 2px;
        }

        .filter-input {
            height: 52px;
            background: #fff;
            border: 1.5px solid #f1f5f9;
            border-radius: 14px;
            padding: 0 1.2rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: #1e293b;
            outline: none;
            transition: 0.3s;
        }

        .filter-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.05);
        }

        .btn-clear {
            height: 52px;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 0 2rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            font-size: 0.85rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            letter-spacing: 1px;
        }

        .btn-clear:hover {
            background: #000;
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        @media (max-width: 991px) {
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .filter-group {
                min-width: 0;
            }

            .btn-export {
                width: 100%;
                justify-content: center;
            }
        }

        .flip-hint {
            width: 100%;
            text-align: center;
            font-size: 0.7rem;
            opacity: 0.4;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: -1.5rem auto 2rem;
        }

        /* HORIZONTAL VOUCHER MODAL STYLE */
        /* HORIZONTAL VOUCHER MODAL STYLE */
        .ticket-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(2, 6, 23, 0.8);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            animation: fadeInOverlay 0.3s ease;
            z-index: 10000;
        }

        .ticket-container {
            background: #fff;
            width: 100%;
            max-width: 550px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 40px 120px rgba(0, 0, 0, 0.6);
            display: flex;
            /* Mandatory for horizontal */
            animation: ticketPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ticket-side-brand {
            background: #821515;
            width: 85px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 2rem 0;
            color: #fff;
            position: relative;
        }

        .side-title {
            writing-mode: vertical-lr;
            transform: rotate(180deg);
            font-weight: 900;
            letter-spacing: 4px;
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
        }

        .side-logo-mini {
            width: 45px;
        }

        .ticket-perforation {
            width: 2px;
            border-left: 2px dashed #e2e8f0;
            margin: 1.5rem 0;
            position: relative;
        }

        .ticket-perforation::before,
        .ticket-perforation::after {
            content: '';
            position: absolute;
            left: -10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #0f172a;
        }

        .ticket-perforation::before {
            top: -24px;
        }

        .ticket-perforation::after {
            bottom: -24px;
        }

        .ticket-main-content {
            flex-grow: 1;
            padding: 2.2rem;
            position: relative;
        }

        .ticket-prize-name {
            font-size: 1.9rem;
            font-weight: 900;
            color: #1e293b;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
        }

        .ticket-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
            margin-bottom: 1.8rem;
        }

        .t-detail-item {
            display: flex;
            flex-direction: column;
        }

        .t-detail-label {
            font-size: 0.6rem;
            font-weight: 850;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 2px;
        }

        .t-detail-val {
            font-size: 0.9rem;
            font-weight: 750;
            color: #334155;
        }

        .ticket-status-row {
            border-top: 1px solid #f1f5f9;
            padding-top: 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .valid-badge {
            color: #16a34a;
            font-weight: 900;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 2px solid #dcfce7;
            padding: 5px 14px;
            border-radius: 50px;
            background: #f0fdf4;
        }

        .ticket-footer-hint {
            font-size: 0.65rem;
            font-weight: 850;
            color: #821515;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @keyframes ticketPop {
            0% {
                transform: scale(0.9) translateY(40px);
                opacity: 0;
            }

            100% {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInOverlay {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Responsive Mobile Ticket Enhancements */
        @media (max-width: 520px) {
            .ticket-container {
                flex-direction: column;
                max-width: 340px;
                border-radius: 28px;
                box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6);
            }

            .ticket-side-brand {
                width: 100%;
                height: 80px;
                flex-direction: row;
                padding: 0 1.5rem;
                clip-path: polygon(0 0, 100% 0, 100% 85%, 0 85%);
            }

            .side-title {
                writing-mode: horizontal-tb;
                transform: none;
                letter-spacing: 2px;
                font-size: 0.8rem;
            }

            .side-logo-mini {
                width: 35px;
            }

            .ticket-perforation {
                width: 100%;
                height: 2px;
                border-left: none;
                border-top: 2px dashed #e2e8f0;
                margin: 0;
            }

            .ticket-perforation::before,
            .ticket-perforation::after {
                width: 24px;
                height: 24px;
                background: #0f172a;
                top: -11px;
            }

            .ticket-perforation::before {
                left: -14px;
            }

            .ticket-perforation::after {
                right: -14px;
            }

            .ticket-main-content {
                padding: 1.8rem 1.5rem;
                text-align: center;
            }

            .ticket-prize-name {
                font-size: 1.6rem;
                margin-bottom: 1.2rem;
            }

            .ticket-details-grid {
                grid-template-columns: 1fr;
                gap: 0.8rem;
            }

            .t-detail-item {
                align-items: center;
            }

            .t-detail-val {
                font-size: 1rem;
            }

            .ticket-status-row {
                flex-direction: column;
                gap: 1rem;
                border-top: 2px dashed #f1f5f9;
                padding-top: 1.5rem;
            }

            .valid-badge {
                width: 100%;
                justify-content: center;
                padding: 10px;
                font-size: 1rem;
            }
        }

        .btn-view-ticket {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #f1f5f9;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-view-ticket:hover {
            background: var(--primary);
            color: #fff;
            transform: scale(1.1);
        }

        /* Admin layout overrides for client profile */
        .main-content-client {
            flex: 1;
            min-height: 100vh;
            background: #ffffff;
            position: relative;
        }

        .sidebar {
            z-index: 5000;
        }

        @media (max-width: 991px) {
            .admin-layout {
                display: block;
            }

            .header-wrapper {
                padding-top: 5.5rem;
            }

            #mobile-toggle-zone {
                display: block !important;
                position: fixed !important;
                top: 1.5rem !important;
                left: 1rem !important;
                z-index: 2000;
            }

            #mobile-toggle-zone .logout-btn-client {
                background: #000 !important;
                color: #fff !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
        }

        .page-title {
            color: var(--primary) !important;
            font-weight: 900 !important;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <div class="main-content-client">
            <?php
            $pageTitle = 'Mi Perfil';
            $pageSubtitle = 'Membresía Digital';
            include __DIR__ . '/../partials/header_admin.php';
            ?>

            <?php if ($isDefaultPassword): ?>
                <!-- ALERT BANNER: DEFAULT PASSWORD -->
                <div style="max-width: 1000px; margin: 2rem auto 0; padding: 0 1.5rem; position: relative; z-index: 10;">
                    <div onclick="window.location.hash='seguridad'"
                        style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 20px; padding: 1.25rem; color: #fff; display: flex; align-items: center; gap: 1rem; box-shadow: 0 10px 25px rgba(245, 158, 11, 0.2); cursor: pointer; transition: 0.3s; border-left: 5px solid #fff;">
                        <div
                            style="background: rgba(255,255,255,0.2); width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                            <i class='bx bx-lock-open-alt'></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 0.9rem; font-weight: 850; line-height: 1.2;">Contraseña predeterminada
                                detectada</div>
                            <div style="font-size: 0.75rem; opacity: 0.9; font-weight: 500; margin-top: 2px;">Tu seguridad
                                es importante. Cambia tu contraseña (DNI/RUC) por una nueva aquí.</div>
                        </div>
                        <i class='bx bx-chevron-right' style="font-size: 1.5rem; opacity: 0.7;"></i>
                    </div>
                </div>
            <?php endif; ?>

                <div id="profile-main-view">

                <!-- BANNER INCENTIVOS (Top de Mi Perfil) - Titanium & Midnight Premium -->
                <div style="max-width: 1000px; margin: 3.5rem auto 0; padding: 0 1.5rem;">
                    <div class="promo-banner-metas" onclick="window.location.hash='incentivos'"
                        style="background: #020617; border-radius: 24px; padding: 2rem 3rem; color: #fff; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); border: 1px solid rgba(255,255,255,0.05); cursor: pointer; transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1); position: relative; overflow: hidden;">
                        
                        <!-- Sutil brillo metálico de fondo -->
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.02) 50%, transparent 60%); pointer-events: none;"></div>

                        <div style="display: flex; align-items: center; gap: 2.5rem; position: relative; z-index: 2;">
                            <!-- Icon: Titanium Minimalist -->
                            <div style="font-size: 2.2rem; color: #e2e8f0; filter: drop-shadow(0 0 10px rgba(226, 232, 240, 0.2));">
                                <i class='bx bxs-zap'></i>
                            </div>
                            
                            <!-- Content: High-Contrast & Organized -->
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <h2 style="font-size: 1.5rem; font-weight: 900; margin: 0; letter-spacing: -0.8px; color: #f8fafc; display: flex; align-items: center; gap: 12px;">
                                    Programa de Incentivos 
                                    <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" style="height: 24px; width: auto; filter: brightness(0) invert(1); opacity: 0.9;">
                                </h2>
                                <p style="margin: 0; font-size: 0.9rem; color: #94a3b8; font-weight: 500; opacity: 0.8;">Acceso exclusivo a metas y beneficios de alto rendimiento.</p>
                            </div>
                        </div>

                        <!-- Action: Titanium Formal Button -->
                        <div class="titanium-btn" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 0.8rem 1.8rem; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2.5px; color: #f8fafc; transition: all 0.3s ease; backdrop-filter: blur(5px); cursor: pointer;">
                            <span>Explorar</span>
                            <i class='bx bx-right-arrow-alt' style="font-size: 1.2rem;"></i>
                        </div>
                    </div>

                    <style>
                        .promo-banner-metas:hover {
                            background: #0a0a0a;
                            border-color: rgba(255,255,255,0.15);
                            transform: scale(1.01);
                        }
                        .promo-banner-metas:hover .titanium-btn {
                            background: #ffffff !important;
                            color: #000000 !important;
                            border-color: #ffffff !important;
                            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
                            transform: translateX(8px);
                        }

                        @media (max-width: 768px) {
                            .promo-banner-metas {
                                flex-direction: column;
                                align-items: flex-start !important;
                                padding: 1.2rem 1.5rem !important;
                                gap: 1rem !important;
                                text-align: left;
                            }
                            .promo-banner-metas > div:first-child {
                                flex-direction: row; /* Keep icon/text horizontal on mobile if possible */
                                align-items: center !important;
                                gap: 1rem !important;
                            }
                            .promo-banner-metas h2 {
                                font-size: 1.15rem !important;
                            }
                            .promo-banner-metas p {
                                font-size: 0.75rem !important;
                                line-height: 1.3;
                            }
                            .titanium-btn {
                                width: 100%;
                                justify-content: center;
                                padding: 0.6rem 1.2rem !important;
                            }
                        }
                    </style>
                </div>

                <!-- Header Section: Card + Info -->
                <div class="profile-header-layout">

                    <!-- Tarjeta VIP y Hint (Lado Izquierdo) -->
                    <div class="profile-card-column">
                        <!-- VIP CARD BLACK EDITION (3D FLIP) -->
                        <div class="vip-card-container" id="profileCard">
                            <div class="vip-card-inner">
                                <!-- FRONT SIDE -->
                                <div class="card-front">
                                    <div class="card-shine"></div>
                                    <div class="vip-card-header">
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

                        <div class="flip-hint" style="margin-top: 2.2rem; color: #1e293b; font-weight: 850; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 1.5px; display: flex; align-items: center; justify-content: center; gap: 8px; opacity: 0.9;">
                            <i class='bx bx-refresh' style="font-size: 1.1rem; color: var(--primary);"></i> 
                            Toca la tarjeta para ver tu QR
                        </div>
                    </div>

                    <!-- Información del Cliente (Lado Derecho) -->
                    <div class="profile-info-column" style="background: #fff; border-radius: 32px; border: 1px solid #e2e8f0; box-shadow: 0 20px 40px rgba(0,0,0,0.04); padding: 2.5rem !important;">
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.5rem;">
                            <div>
                                <h3 style="font-size: 1.25rem; font-weight: 850; color: var(--primary); margin: 0; letter-spacing: -0.5px;">
                                    Información Detallada
                                </h3>
                                <p style="margin: 5px 0 0; font-size: 0.8rem; color: #64748b; font-weight: 500;">Datos del perfil y membresía</p>
                            </div>
                            <button onclick="openEditModal()" 
                                style="background: transparent; color: #0f172a; border: 1.5px solid #e2e8f0; padding: 0.6rem 1.2rem; border-radius: 12px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s ease; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class='bx bx-edit-alt' style="font-size: 1.1rem;"></i>
                                Editar
                            </button>
                        </div>

                        <div class="info-grid-modern" style="gap: 2rem 1.5rem;">
                            <!-- DNI / RUC -->
                            <div class="info-item-elegant" style="padding: 0;">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <div style="display: flex; align-items: center; gap: 8px; color: #94a3b8;">
                                        <i class='bx bx-id-card' style="font-size: 1rem;"></i>
                                        <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Documento</span>
                                    </div>
                                    <span style="font-size: 1.05rem; font-weight: 700; color: #0f172a; padding-left: 2px;">
                                        <?= htmlspecialchars($cliente['dni'] ?? $cliente['ruc'] ?? 'No registrado') ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="info-item-elegant" style="padding: 0;">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <div style="display: flex; align-items: center; gap: 8px; color: #94a3b8;">
                                        <i class='bx bx-mobile-alt' style="font-size: 1rem;"></i>
                                        <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Teléfono</span>
                                    </div>
                                    <span style="font-size: 1.05rem; font-weight: 700; color: #0f172a; padding-left: 2px;">
                                        <?= htmlspecialchars($cliente['celular'] ?? 'No registrado') ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Tipo de Cliente -->
                            <div class="info-item-elegant" style="padding: 0;">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <div style="display: flex; align-items: center; gap: 8px; color: #94a3b8;">
                                        <i class='bx bx-crown' style="font-size: 1rem;"></i>
                                        <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Categoría</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span style="font-size: 1.05rem; font-weight: 700; color: #0f172a;">
                                            <?= htmlspecialchars($cliente['tipo_cliente'] ?? 'Normal') ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Miembro Desde -->
                            <div class="info-item-elegant" style="padding: 0;">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <div style="display: flex; align-items: center; gap: 8px; color: #94a3b8;">
                                        <i class='bx bx-calendar' style="font-size: 1rem;"></i>
                                        <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Miembro desde</span>
                                    </div>
                                    <span style="font-size: 1.05rem; font-weight: 700; color: #0f172a; padding-left: 2px;">
                                        <?= isset($cliente['fecha_creacion']) ? date('d/m/Y', strtotime($cliente['fecha_creacion'])) : 'No registrado' ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Ubicación (Full Width) -->
                            <div class="info-item-elegant full-width" style="border-top: 1px solid #f8fafc; padding: 1.5rem 0 0; margin-top: 0.5rem;">
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    <div style="display: flex; align-items: center; gap: 8px; color: #94a3b8;">
                                        <i class='bx bx-map' style="font-size: 1rem;"></i>
                                        <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Dirección de Residencia</span>
                                    </div>
                                    <span style="font-size: 0.95rem; font-weight: 600; color: #475569; line-height: 1.5; padding-left: 2px;">
                                        <?= htmlspecialchars(($cliente['direccion'] ?? 'Sin dirección registrada') . ($cliente['departamento'] ? ', ' . $cliente['departamento'] : '')) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END VIEW: MI PERFIL -->



            <!-- Contenedor de Actividad y Canjes (Oculto por defecto para evitar parpadeos) -->
            <div class="container" style="display: none;">


                <!-- Tab Switcher -->
                <div class="tab-switcher">
                    <div class="tab-btn active" onclick="switchTab('actividad', this)">
                        <i class='bx bx-time-five'></i> Actividad
                    </div>
                    <div class="tab-btn" onclick="switchTab('canjes', this)">
                        <i class='bx bx-gift'></i> Canjes
                    </div>
                    <div class="tab-btn" onclick="switchTab('incentivos', this)">
                        <i class='bx bx-target-lock'></i> Metas & Vales
                    </div>
                    <div class="tab-btn" onclick="switchTab('seguridad', this)">
                        <i class='bx bx-lock-alt'></i> Seguridad
                    </div>
                </div>

                <!-- PANE 1: ACTIVIDAD -->
                <div id="pane-actividad" class="tab-content-pane active">

                    <div class="filter-bar" style="justify-content: flex-start; gap: 2rem;">
                        <div class="filter-group" style="flex: 0.8; min-width: 150px;">
                            <label class="filter-label">TIPO OPERACIÓN</label>
                            <select id="f-op" class="filter-input" onchange="filterActivityTable()">
                                <option value="todos">TODOS</option>
                                <option value="COMPRA">COMPRAS</option>
                                <option value="RECARGA">RECARGAS</option>
                                <option value="VALE">VALES</option>
                            </select>
                        </div>
                        <div class="filter-group" style="flex: 0.8; min-width: 150px;">
                            <label class="filter-label">DESDE</label>
                            <input type="date" id="f-desde" class="filter-input" onchange="filterActivityTable()">
                        </div>
                        <div class="filter-group" style="flex: 0.8; min-width: 150px;">
                            <label class="filter-label">HASTA</label>
                            <input type="date" id="f-hasta" class="filter-input" onchange="filterActivityTable()">
                        </div>
                        <button class="btn-clear" onclick="clearFilters()">
                            <i class='bx bx-eraser'></i>
                            LIMPIAR
                        </button>
                    </div>

                    <?php if (empty($ventas)): ?>
                        <div style="padding: 5rem 2rem; text-align: center; opacity: 0.3;">
                            <i class='bx bx-file-blank' style="font-size: 3.5rem; display: block; margin-bottom: 1rem;"></i>
                            <span style="font-size: 0.9rem; font-weight: 600;">No hay movimientos registrados.</span>
                        </div>
                    <?php else: ?>
                        <div class="elite-table-wrapper">
                            <table class="elite-table">
                                <thead>
                                    <tr>
                                        <th class="col-date">FECHA / HORA</th>
                                        <th class="col-type" style="text-align: center;">OPERACIÓN</th>
                                        <th class="col-detail">DETALLE / COMPROBANTE</th>
                                        <th class="col-pts" style="text-align: right;">PUNTOS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventas as $v): ?>
                                        <?php
                                        $tipoExt = $v['tipo_ext'] ?? '';
                                        $esRecarga = strpos($v['detalle'], 'Recarga') !== false;
                                        $esVale = ($tipoExt === 'VALE');

                                        $tipoClase = 'badge-compra';
                                        $tipoTexto = 'COMPRA';

                                        if ($esRecarga) {
                                            $tipoClase = 'badge-recarga';
                                            $tipoTexto = 'RECARGA';
                                        } elseif ($esVale) {
                                            $tipoClase = 'badge-vale';
                                            $tipoTexto = 'VALE';
                                        }

                                        $sortDate = date('Y-m-d', strtotime($v['fecha']));
                                        ?>
                                        <tr class="activity-row-data" data-tipo="<?= $tipoTexto ?>"
                                            data-fecha="<?= $sortDate ?>">
                                            <td class="col-date">
                                                <?= date('d/m/Y', strtotime($v['fecha'])) ?>
                                                <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600;">
                                                    <?= date('H:i', strtotime($v['fecha'])) ?>
                                                </div>
                                            </td>
                                            <td class="col-type">
                                                <span class="type-badge <?= $tipoClase ?>"><?= $tipoTexto ?></span>
                                            </td>
                                            <td>
                                                <div style="font-weight: 700; color: #1e293b; line-height: 1.4;">
                                                    <?php if (!empty($v['items'])): ?>
                                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                                            <?php foreach ($v['items'] as $it): ?>
                                                                <li style="display: flex; align-items: center; gap: 6px;">
                                                                    <i class='bx bx-chevron-right' style="color: #94a3b8;"></i>
                                                                    <?= htmlspecialchars($it['nombre_item']) ?> x<?= $it['cantidad'] ?>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php else: ?>
                                                        <?= htmlspecialchars($v['detalle']) ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div
                                                    style="font-size: 0.72rem; color: #64748b; margin-top: 4px; font-weight: 500;">
                                                    <?php 
                                                        if ($esRecarga) echo 'Abono directo de puntos';
                                                        elseif ($esVale) echo 'Canje de beneficio acumulado';
                                                        else echo 'Transacción en establecimiento';
                                                    ?>
                                                </div>
                                            </td>
                                            <td class="col-pts">
                                                <span style="color: <?= $esVale ? '#94a3b8' : '#22c55e' ?>">
                                                    <?= $esVale ? '' : '+' ?><?= $v['puntos'] ?>
                                                </span>
                                                <span style="font-size: 0.65rem; opacity: 0.5; font-weight: 700;">PTS</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- PANE 2: CANJES -->
                <div id="pane-canjes" class="tab-content-pane">
                    <?php if (empty($canjes)): ?>
                        <div style="padding: 5rem 2rem; text-align: center; opacity: 0.3;">
                            <i class='bx bx-gift' style="font-size: 3.5rem; display: block; margin-bottom: 1rem;"></i>
                            <span style="font-size: 0.9rem; font-weight: 600;">Aún no has canjeado premios.</span>
                        </div>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <?php foreach ($canjes as $c): ?>
                                <?php
                                $modStr = $c['monto'] > 0 ? (!empty($c['comprobante_url']) ? 'Puntos + Depósito' : 'Puntos + Efectivo') : 'Canje Total';
                                ?>
                                <div class="canje-wallet-card"
                                    style="padding: 1.25rem 1rem; border-radius: 24px; border-color: #f1f5f9; transition: transform 0.3s; display: flex; align-items: center; justify-content: space-between; background: #fff; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); position: relative; overflow: hidden; gap: 0.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1; min-width: 0;">
                                        <div class="canje-icon-circle"
                                            style="width: 48px; height: 48px; border-radius: 16px; background: #fff1f2; color: #e11d48; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; border: 1px solid #ffe4e6;">
                                            <i class='bx bxs-gift'></i>
                                        </div>

                                        <div class="canje-info-main" style="min-width: 0;">
                                            <div class="canje-prize-title"
                                                style="font-size: 0.95rem; font-weight: 850; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <?= htmlspecialchars($c['premio_nombre']) ?>
                                            </div>
                                            <div class="modality-tag-mini"
                                                style="font-size: 0.6rem; font-weight: 800; color: #821515; background: rgba(130, 21, 21, 0.05); padding: 2px 8px; border-radius: 6px; display: inline-block; margin-top: 4px; text-transform: uppercase; white-space: nowrap;">
                                                <?= $modStr ?>
                                            </div>
                                            <div class="canje-info-meta"
                                                style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; margin-top: 6px; display: flex; align-items: center; gap: 4px;">
                                                <i class='bx bx-time' style="font-size: 0.85rem;"></i>
                                                <?= date('d/m/Y', strtotime($c['fecha'])) ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0;">
                                        <div class="canje-metrics-side" style="text-align: right;">
                                            <div class="canje-pts-val"
                                                style="font-size: 1.15rem; font-weight: 900; color: #e11d48; line-height: 1;">
                                                -<?= $c['puntos_usados'] ?> <span
                                                    style="font-size: 0.7rem; opacity: 0.5;">PTS</span></div>
                                            <div class="canje-status-pill badge-<?= $c['estado'] ?>"
                                                style="margin-top: 6px; font-size: 0.5rem; padding: 2px 6px;">
                                                <?= str_replace('_', ' ', $c['estado']) ?>
                                            </div>
                                        </div>

                                        <?php if ($c['estado'] === 'pago_aprobado' || $c['estado'] === 'pendiente'): ?>
                                            <button class="btn-float-ticket"
                                                style="width: 40px; height: 40px; border-radius: 12px; background: #000; color: #fff; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; flex-shrink: 0;"
                                                onclick='showClaimTicket(<?= json_encode([
                                                    "id" => $c["id"],
                                                    "premio" => $c["premio_nombre"],
                                                    "puntos" => $c["puntos_usados"],
                                                    "monto" => $c["monto"],
                                                    "modalidad" => $modStr,
                                                    "fecha" => date("d/m/Y H:i", strtotime($c["fecha"]))
                                                ]) ?>)'>
                                                <i class="bx bx-qr" style="font-size: 1.25rem;"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- PANE 3: INCENTIVOS -->
                <div id="pane-incentivos" class="tab-content-pane">
                    <div id="incentivos-loader" style="padding: 5rem 2rem; text-align: center; color: #94a3b8;">
                        <i class='bx bx-loader-alt bx-spin' style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <div>Cargando tus metas...</div>
                    </div>
                    <div id="incentivos-content" style="display: none;">
                        <!-- PROGRESO DE METAS -->
                        <h3 style="font-size: 1.1rem; font-weight: 850; color: #1e293b; margin-bottom: 1rem;"><i
                                class='bx bx-target-lock' style="color:#7c3aed"></i> Progreso de Metas (<span
                                id="inc-periodo-lbl"></span>)</h3>
                        <div id="progreso-container"
                            style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;"></div>

                        <!-- VALES DISPONIBLES -->
                        <h3 style="font-size: 1.1rem; font-weight: 850; color: #1e293b; margin-bottom: 1rem;"><i
                                class='bx bx-receipt' style="color:#16a34a"></i> Mis Vales Disponibles</h3>
                        <div id="vales-container" style="display: flex; flex-direction: column; gap: 1rem;"></div>
                    </div>
                </div>

                <!-- PANE 4: SEGURIDAD -->
                <div id="pane-seguridad" class="tab-content-pane">
                    <div
                        style="max-width: 500px; margin: 0 auto; background: #fff; padding: 2.5rem; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div
                                style="background: #f8fafc; width: 64px; height: 64px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: #7c3aed; font-size: 2rem;">
                                <i class='bx bx-lock-alt'></i>
                            </div>
                            <h3 style="font-size: 1.3rem; font-weight: 850; color: #1e293b;">Cambiar Contraseña</h3>
                            <p style="font-size: 0.85rem; color: #94a3b8; font-weight: 500; margin-top: 5px;">Asegura tu
                                cuenta con una nueva clave.</p>
                        </div>

                        <div class="filter-group" style="margin-bottom: 1.5rem;">
                            <label class="filter-label">NUEVA CONTRASEÑA</label>
                            <input type="password" id="new-password" class="filter-input"
                                placeholder="Mínimo 4 caracteres" style="width: 100%;">
                        </div>

                        <button onclick="updateClientPassword()"
                            style="width: 100%; height: 52px; background: #1e293b; color: #fff; border: none; border-radius: 14px; font-weight: 800; font-size: 0.9rem; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class='bx bx-check-shield' style="font-size: 1.2rem;"></i>
                            ACTUALIZAR CONTRASEÑA
                        </button>

                        <?php if ($isDefaultPassword): ?>
                            <div
                                style="margin-top: 1.5rem; background: #fffbeb; padding: 1rem; border-radius: 14px; border: 1px solid #fef3c7; display: flex; gap: 10px; align-items: flex-start;">
                                <i class='bx bx-info-circle'
                                    style="color: #d97706; font-size: 1.2rem; margin-top: 2px;"></i>
                                <div style="font-size: 0.75rem; color: #b45309; font-weight: 600; line-height: 1.4;">
                                    Actualmente estás usando tu DNI/RUC como clave. Te recomendamos cambiarla lo antes
                                    posible.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="footer">
                    &copy; <?= date('Y') ?> Surgas — Premium Digital Member Card
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Modal Editar Perfil (Estilo Aliado Premium) -->
    <div id="modalEditProfile" class="ticket-overlay" style="display: none;">
        <div class="ticket-container" style="max-width: 600px; flex-direction: column; padding: 0; border-radius: 32px; overflow: hidden; border: none; background: #fff; box-shadow: 0 50px 100px rgba(0,0,0,0.4);">
            <!-- Header Modal -->
            <div style="padding: 2.2rem 2.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f8fafc;">
                <div style="display: flex; align-items: center; gap: 1.2rem;">
                    <div style="background: #fff1f2; width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #e11d48; font-size: 1.6rem; border: 1px solid #ffe4e6;">
                        <i class='bx bx-user-circle'></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.35rem; font-weight: 900; color: #0f172a; letter-spacing: -0.5px;">Editar Perfil</h3>
                        <p style="margin: 3px 0 0; font-size: 0.78rem; color: #64748b; font-weight: 600;">Actualiza tu información de contacto</p>
                    </div>
                </div>
                <button onclick="closeEditModal()" style="background: #f1f5f9; border: none; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: 0.3s;">
                    <i class='bx bx-x' style="font-size: 1.2rem;"></i>
                </button>
            </div>
            
            <!-- Body Modal -->
            <div style="padding: 2.5rem; display: flex; flex-direction: column; gap: 1.8rem;">
                <!-- Campo Nombre (Solo Lectura) -->
                <div class="filter-group">
                    <label class="filter-label" style="margin-bottom: 0.8rem; display: block; font-size: 0.68rem; font-weight: 850; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Nombre del Titular</label>
                    <div style="position: relative;">
                        <i class='bx bx-user' style="position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #cbd5e1; font-size: 1.3rem;"></i>
                        <input type="text" value="<?= htmlspecialchars($cliente['nombre']) ?>" disabled 
                            style="width: 100%; height: 56px; border-radius: 14px; border: 1px solid #f1f5f9; background: #f8fafc; padding-left: 3.5rem; color: #94a3b8; font-weight: 600; font-size: 0.95rem; cursor: not-allowed; outline: none;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="filter-group">
                        <label class="filter-label" style="margin-bottom: 0.8rem; display: block; font-size: 0.68rem; font-weight: 850; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Celular de Contacto</label>
                        <div style="position: relative;">
                            <i class='bx bx-phone' style="position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #cbd5e1; font-size: 1.3rem;"></i>
                            <input type="text" id="edit-celular" value="<?= htmlspecialchars($cliente['celular'] ?? '') ?>" maxlength="9" placeholder="9 dígitos"
                                style="width: 100%; height: 56px; border-radius: 14px; border: 1px solid #e2e8f0; padding-left: 3.5rem; color: #0f172a; font-weight: 700; font-size: 0.95rem; transition: 0.3s; outline: none; background: #fff;">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label" style="margin-bottom: 0.8rem; display: block; font-size: 0.68rem; font-weight: 850; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Documento ID</label>
                        <div style="position: relative;">
                            <i class='bx bx-id-card' style="position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #cbd5e1; font-size: 1.3rem;"></i>
                            <input type="text" value="<?= htmlspecialchars($cliente['dni'] ?: $cliente['ruc']) ?>" disabled
                                style="width: 100%; height: 56px; border-radius: 14px; border: 1px solid #f1f5f9; background: #f8fafc; padding-left: 3.5rem; color: #94a3b8; font-weight: 600; font-size: 0.95rem; cursor: not-allowed; outline: none;">
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" style="margin-bottom: 0.8rem; display: block; font-size: 0.68rem; font-weight: 850; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Dirección de Residencia</label>
                    <div style="position: relative;">
                        <i class='bx bx-map' style="position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #cbd5e1; font-size: 1.3rem;"></i>
                        <input type="text" id="edit-direccion" value="<?= htmlspecialchars($cliente['direccion'] ?? '') ?>" placeholder="Ej: Calle Principal 123, Tacna"
                            style="width: 100%; height: 56px; border-radius: 14px; border: 1px solid #e2e8f0; padding-left: 3.5rem; color: #0f172a; font-weight: 700; font-size: 0.95rem; transition: 0.3s; outline: none; background: #fff;">
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div style="padding: 1.8rem 2.5rem; background: #fff; border-top: 1px solid #f8fafc; display: flex; justify-content: flex-end; gap: 1rem;">
                <button onclick="closeEditModal()" 
                    style="height: 52px; padding: 0 1.8rem; border-radius: 14px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; font-weight: 800; font-size: 0.9rem; cursor: pointer; transition: 0.3s;">
                    Cancelar
                </button>
                <button onclick="saveProfileChanges()" 
                    style="height: 52px; padding: 0 2.2rem; border-radius: 14px; border: none; background: #000; color: #fff; font-weight: 800; font-size: 0.9rem; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.15);">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </div>

    <script>
        function openEditModal() {
            document.getElementById('modalEditProfile').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('modalEditProfile').style.display = 'none';
        }

        function saveProfileChanges() {
            const celular = document.getElementById('edit-celular').value.trim();
            const direccion = document.getElementById('edit-direccion').value.trim();

            if (!celular || celular.length !== 9 || isNaN(celular)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Celular inválido',
                    text: 'El celular debe tener exactamente 9 dígitos numéricos.',
                    confirmButtonColor: '#1e293b'
                });
                return;
            }

            Swal.fire({
                title: '¿Guardar cambios?',
                text: "Se actualizará tu información de contacto en nuestro sistema.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e293b',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Actualizando...', didOpen: () => { Swal.showLoading(); } });

                    fetch(BASE_URL + 'clientes/updateProfile', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ celular, direccion })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Tus datos se han actualizado correctamente.',
                                confirmButtonColor: '#1e293b'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                                confirmButtonColor: '#1e293b'
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.' });
                    });
                }
            });
        }

        const BASE_URL = '<?= BASE_URL ?>';
        const cardContainer = document.getElementById('profileCard');
        if (cardContainer) {
            cardContainer.addEventListener('click', () => {
                cardContainer.classList.toggle('is-flipped');
            });
        }

        // Generar QR en el reverso
        const qrContainer = document.getElementById("qrcode");
        if (qrContainer) {
            const qrContent = '<?= BASE_URL ?>scan?c=<?= urlencode($cliente['codigo']) ?>&t=<?= urlencode($cliente['token']) ?>';
            new QRCode(qrContainer, {
                text: qrContent,
                width: 125,
                height: 125,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }

        // Tab Switching Logic
        function switchTab(paneId, btnElement, hideCard = false) {
            // Remove active from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            // Add active to current button
            if (btnElement) btnElement.classList.add('active');

            // Hide all panes
            document.querySelectorAll('.tab-content-pane').forEach(pane => pane.classList.remove('active'));
            // Show selected pane
            const targetPane = document.getElementById('pane-' + paneId);
            if (targetPane) targetPane.classList.add('active');

            if (paneId === 'incentivos' && !window.incentivosLoaded) {
                loadIncentivos();
            }

            // Toggle visibility of profile card elements
            const cardElements = [
                document.getElementById('profile-main-view'),
                document.querySelector('.btn-store')
            ];

            const tabsContainer = document.querySelector('.container');

            if (hideCard) {
                // VIEW: ACTIVITY or REDEMPTIONS (Detail)
                cardElements.forEach(el => { if (el) el.style.display = 'none'; });
                if (document.querySelector('.header-wrapper')) document.querySelector('.header-wrapper').style.display = 'none';

                if (tabsContainer) {
                    tabsContainer.style.display = 'block';
                    tabsContainer.style.marginTop = '0';
                    tabsContainer.style.paddingTop = '2rem';
                }
                document.querySelector('.tab-switcher').style.display = 'none';
                document.querySelector('.main-content-client').style.paddingTop = '2rem';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                // VIEW: MAIN PROFILE (VIP Card Only)
                cardElements.forEach(el => { if (el) el.style.display = ''; });
                if (document.querySelector('.header-wrapper')) document.querySelector('.header-wrapper').style.display = '';

                if (tabsContainer) {
                    tabsContainer.style.display = 'none'; // Hide Activity/Canjes in Profile view
                }
                document.querySelector('.main-content-client').style.paddingTop = '';
            }
        }

        // Handle URL Hash for direct navigation
        function handleNavigation() {
            const hash = window.location.hash.replace('#', '');
            const titleEl = document.querySelector('.page-title');
            const subTitleEl = document.querySelector('.page-subtitle');

            if (hash === 'canjes') {
                if (titleEl) titleEl.innerText = 'Mis Canjes';
                if (subTitleEl) subTitleEl.innerText = 'Premios y canjes realizados';
                switchTab('canjes', document.querySelectorAll('.tab-btn')[1], true);
            } else if (hash === 'actividad') {
                if (titleEl) titleEl.innerText = 'Historial de Actividad';
                if (subTitleEl) subTitleEl.innerText = 'Tus puntos acumulados';
                switchTab('actividad', document.querySelectorAll('.tab-btn')[0], true);
            } else if (hash === 'incentivos') {
                if (titleEl) titleEl.innerText = 'Metas y Vales';
                if (subTitleEl) subTitleEl.innerText = 'Gana vales por tus compras';
                switchTab('incentivos', document.querySelectorAll('.tab-btn')[2], true);
            } else if (hash === 'seguridad') {
                if (titleEl) titleEl.innerText = 'Seguridad de Cuenta';
                if (subTitleEl) subTitleEl.innerText = 'Protege tu acceso';
                switchTab('seguridad', document.querySelectorAll('.tab-btn')[3], true);
            } else {
                // Default: Profile view
                if (titleEl) titleEl.innerText = 'Mi Perfil';
                if (subTitleEl) subTitleEl.innerText = 'Membresía Digital';
                window.location.hash = '';
                switchTab('actividad', document.querySelectorAll('.tab-btn')[0], false);
            }
        }

        function updateClientPassword() {
            const pass = document.getElementById('new-password').value;
            if (!pass || pass.length < 4) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Contraseña corta',
                    text: 'La contraseña debe tener al menos 4 caracteres.',
                    confirmButtonColor: '#1e293b'
                });
                return;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Tu sesión se mantendrá activa, pero deberás usar la nueva clave la próxima vez.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e293b',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Procesando...',
                        didOpen: () => { Swal.showLoading(); }
                    });

                    fetch(BASE_URL + 'clientes/changePassword', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ password: pass })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Actualizado!',
                                    text: 'Tu contraseña ha sido cambiada con éxito.',
                                    confirmButtonColor: '#1e293b'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                    confirmButtonColor: '#1e293b'
                                });
                            }
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de conexión',
                                text: 'No se pudo contactar con el servidor.',
                                confirmButtonColor: '#1e293b'
                            });
                        });
                }
            });
        }

        // --- Incentivos Logic ---
        function loadIncentivos() {
            window.incentivosLoaded = true;
            fetch(BASE_URL + 'incentivos/progresoJson?cliente_id=<?= $cliente['id'] ?>')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('incentivos-loader').style.display = 'none';
                    document.getElementById('incentivos-content').style.display = 'block';

                    if (data.success) {
                        renderProgreso(data.progreso);
                        renderVales(data.vales);
                    } else {
                        document.getElementById('incentivos-content').innerHTML = '<div style="text-align:center; padding:3rem; color:#ef4444;">Error al cargar datos.</div>';
                    }
                })
                .catch(err => {
                    document.getElementById('incentivos-loader').innerHTML = '<div style="color:#ef4444;">Error de conexión.</div>';
                });
        }

        function renderProgreso(progreso) {
            const container = document.getElementById('progreso-container');
            if (!progreso || progreso.length === 0) {
                container.innerHTML = '<div style="background:#f8fafc; padding:2rem; border-radius:16px; text-align:center; color:#94a3b8; font-size:0.9rem; font-weight:600;">No hay metas activas en este momento.</div>';
                return;
            }

            let html = '';
            progreso.forEach(p => {
                document.getElementById('inc-periodo-lbl').innerText = p.periodo.toUpperCase();
                const color = p.cumplida ? '#16a34a' : '#7c3aed';
                const bg = p.cumplida ? '#dcfce7' : '#f3e8ff';
                const statusText = p.vale_generado ? '<span style="color:#16a34a; font-weight:800; font-size:0.7rem;"><i class="bx bx-check-circle"></i> VALE GENERADO</span>' : `<span style="font-weight:700; font-size:0.8rem; color:#64748b;">${p.actual} / ${p.meta} ops</span>`;

                html += `
                    <div style="background:#fff; border:1px solid #f1f5f9; border-radius:20px; padding:1.5rem; box-shadow:0 4px 15px rgba(0,0,0,0.02);">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
                            <div>
                                <div style="font-weight:850; color:#1e293b; font-size:1rem;">${p.regla_nombre}</div>
                                <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; margin-top:2px;">Premio: ${p.premio}</div>
                                ${p.descripcion ? `<div style="font-size:0.7rem; color:#64748b; font-weight:500; margin-top:8px; background:#f8fafc; padding:8px 12px; border-radius:8px; border-left:3px solid #7c3aed; line-height:1.4;">${p.descripcion}</div>` : ''}
                            </div>
                            <div style="background:${bg}; color:${color}; padding:6px 12px; border-radius:50px; font-weight:900; font-size:0.7rem;">
                                ${p.porcentaje}%
                            </div>
                        </div>
                        
                        <div style="height:12px; background:#f1f5f9; border-radius:50px; overflow:hidden; margin-bottom:0.8rem;">
                            <div style="height:100%; background:${color}; width:${p.porcentaje}%; border-radius:50px; transition:1s ease;"></div>
                        </div>
                        
                        <div style="display:flex; justify-content:flex-end;">
                            ${statusText}
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function renderVales(vales) {
            const container = document.getElementById('vales-container');
            if (!vales || vales.length === 0) {
                container.innerHTML = '<div style="background:#f8fafc; padding:2rem; border-radius:16px; text-align:center; color:#94a3b8; font-size:0.9rem; font-weight:600;">No tienes vales disponibles. ¡Completa tus metas!</div>';
                return;
            }

            let html = '';
            vales.forEach(v => {
                // Formatting date:
                const d = new Date(v.fecha_vencimiento);
                const dateStr = d.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });

                const valStr = v.tipo_premio === 'vale_descuento' ? `${parseInt(v.valor)}%` : `S/ ${parseFloat(v.valor).toFixed(2)}`;

                html += `
                    <div style="background:linear-gradient(135deg, #1e293b, #0f172a); border-radius:24px; padding:1.5rem; color:#fff; position:relative; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.15);">
                        <!-- Decoraciones -->
                        <i class='bx bxs-star' style="position:absolute; top:-20px; right:-20px; font-size:8rem; opacity:0.05;"></i>
                        
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; position:relative; z-index:2;">
                            <div>
                                <div style="font-size:0.7rem; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Vale Disponible</div>
                                <div style="font-size:1.1rem; font-weight:850; color:#fff;">${v.descripcion}</div>
                            </div>
                            <div style="background:#fef08a; color:#854d0e; font-weight:900; font-size:1.2rem; padding:8px 16px; border-radius:14px; transform:rotate(5deg);">
                                ${valStr}
                            </div>
                        </div>

                        <div style="background:rgba(255,255,255,0.1); border-radius:16px; padding:1rem; display:flex; justify-content:space-between; align-items:center; position:relative; z-index:2; border:1px solid rgba(255,255,255,0.05);">
                            <div>
                                <div style="font-size:0.65rem; color:#cbd5e1; font-weight:600; text-transform:uppercase;">CÓDIGO ÚNICO</div>
                                <div style="font-family:monospace; font-size:1.2rem; font-weight:800; color:#fff; letter-spacing:1px;">${v.codigo}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:0.65rem; color:#cbd5e1; font-weight:600; text-transform:uppercase;">VÁLIDO HASTA</div>
                                <div style="font-size:0.9rem; font-weight:700; color:#fff;">${dateStr}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        // --- Real-time Activity Filtering Logic ---
        function filterActivityTable() {
            const searchInput = document.getElementById('f-search');
            const opInput = document.getElementById('f-op');

            const search = searchInput ? searchInput.value.toLowerCase() : '';
            const op = opInput ? opInput.value : 'todos';
            const desde = document.getElementById('f-desde').value;
            const hasta = document.getElementById('f-hasta').value;

            const rows = document.querySelectorAll('.activity-row-data');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rowOp = row.getAttribute('data-tipo');
                const rowDate = row.getAttribute('data-fecha');

                let show = true;

                // Filter by Search Text (if exists)
                if (search && !text.includes(search)) show = false;

                // Filter by Operation Type (if exists)
                if (op !== 'todos' && rowOp !== op) show = false;

                // Filter by Date Range
                if (desde && rowDate < desde) show = false;
                if (hasta && rowDate > hasta) show = false;

                row.style.display = show ? '' : 'none';
            });
        }

        function clearFilters() {
            document.getElementById('f-desde').value = '';
            document.getElementById('f-hasta').value = '';
            const searchInput = document.getElementById('f-search');
            const opInput = document.getElementById('f-op');
            if (searchInput) searchInput.value = '';
            if (opInput) opInput.value = 'todos';
            filterActivityTable();
        }

        window.addEventListener('load', handleNavigation);
        window.addEventListener('hashchange', handleNavigation);

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
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>

</html>