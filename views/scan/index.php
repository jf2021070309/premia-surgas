<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Puntos — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        /* ── Layout Grid ── */
        .scan-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* ── Right panel animation ── */
        .scan-right-panel:not(.active) { opacity: 0.45; pointer-events: none; transition: opacity 0.3s; }
        .scan-right-panel.active { opacity: 1; pointer-events: auto; animation: slideIn 0.4s cubic-bezier(0.16,1,0.3,1); }
        @keyframes slideIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Search top grid ── */
        .scan-top-search {
            padding: 2.2rem;
            display: flex;
            gap: 2.5rem;
            align-items: center;
            background: #fff;
            border-radius: 0 0 20px 20px;
        }

        /* ── Scanner Visual Placeholder ── */
        .scanner-visual-box {
            width: 320px;
            max-width: 100%;
            aspect-ratio: 1 / 1;
            background: #050505;
            border-radius: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.2, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.6);
            flex-shrink: 0;
        }
        
        .scanner-visual-box:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.7);
            border-color: rgba(255, 94, 20, 0.3);
        }

        /* Enfoque de esquinas */
        .scanner-corner {
            position: absolute;
            width: 25px;
            height: 25px;
            border: 2.5px solid rgba(255, 94, 20, 0.4);
            pointer-events: none;
            transition: all 0.4s;
        }
        .scanner-corner.tl { top: 25px; left: 25px; border-right: 0; border-bottom: 0; border-radius: 6px 0 0 0; }
        .scanner-corner.tr { top: 25px; right: 25px; border-left: 0; border-bottom: 0; border-radius: 0 6px 0 0; }
        .scanner-corner.bl { bottom: 25px; left: 25px; border-right: 0; border-top: 0; border-radius: 0 0 0 6px; }
        .scanner-corner.br { bottom: 25px; right: 25px; border-left: 0; border-top: 0; border-radius: 0 0 6px 0; }

        .scanner-visual-box:hover .scanner-corner { border-color: #ff5e14; width: 45px; height: 45px; }

        .scanner-visual-box i {
            font-size: 6rem;
            margin-bottom: 1.2rem;
            z-index: 2;
            color: #fff;
            transition: all 0.4s;
            opacity: 0.9;
        }
        .scanner-visual-box:hover i { transform: scale(1.1); opacity: 1; }

        .scanner-visual-box span {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            z-index: 2;
            color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 8px 18px;
            border-radius: 50px;
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(5px);
            transition: all 0.3s;
        }
        .scanner-visual-box:hover span { 
            color: #fff; 
            background: rgba(255, 94, 20, 0.1); 
            border-color: rgba(255, 94, 20, 0.3);
        }

        /* Stylized scanning line */
        .scanner-visual-box::after {
            content: '';
            position: absolute;
            top: -5px; left: 0; width: 100%; height: 2px;
            background: linear-gradient(to right, transparent, #ff5e14, transparent);
            box-shadow: 0 0 20px 2px rgba(255, 94, 20, 0.6);
            animation: scanning-line 3.5s infinite ease-in-out;
            z-index: 1;
        }
        @keyframes scanning-line {
            0% { top: 10%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 90%; opacity: 0; }
        }

        .search-options-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2.2rem;
            padding-left: 2.5rem;
        }

        /* ── Separator ── */
        .search-separator {
            display: flex;
            align-items: center;
            text-align: center;
            color: #94a3b8;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0.5rem 0;
        }
        .search-separator::before, .search-separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #f1f5f9;
        }
        .search-separator:not(:empty)::before { margin-right: 2rem; }
        .search-separator:not(:empty)::after { margin-left: 2rem; }

        /* ── DNI Input Premium ── */
        .elite-input-wrapper { 
            display: flex; 
            align-items: center; 
            gap: 1rem;
            width: 100%;
        }

        .elite-input {
            flex: 1;
            width: 100%;
            height: 52px;
            padding: 0 1.5rem;
            background: #f8fafc !important;
            border-radius: 12px !important;
            border: 2px solid #e2e8f0 !important;
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            font-weight: 600;
            color: #1e293b;
            outline: none;
            transition: all 0.3s;
        }
        .elite-input:focus {
            background: #fff !important;
            border-color: #800000 !important;
            box-shadow: 0 12px 24px -10px rgba(128,0,0,0.12);
        }
        .elite-input::placeholder { color: #94a3b8; font-weight: 400; }

        .btn-search-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            background: #800000;
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 16px rgba(128,0,0,0.2);
            flex-shrink: 0;
        }
        .btn-search-icon:hover { 
            background: #000; 
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.25);
        }

        /* ── Method choice buttons ── */
        .choice-btn {
            background: #eef6ff;
            border: 1.5px dashed transparent;
            border-radius: 16px;
            height: 64px;
            padding: 0 2rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
            width: 100%;
            color: #475569;
            position: relative;
            overflow: hidden;
        }
        .choice-btn::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(128,0,0,0.03) 0%, rgba(128,0,0,0) 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .choice-btn:hover {
            border: 1.5px solid #800000;
            background: #fff;
            color: #800000;
            transform: translateY(-4px);
            box-shadow: 0 15px 30px -10px rgba(128,0,0,0.15);
        }
        .choice-btn:hover::before { opacity: 1; }
        .choice-btn i { 
            font-size: 1.7rem; 
            color: #800000; 
            transition: all 0.3s;
            filter: drop-shadow(0 2px 4px rgba(128,0,0,0.1));
        }
        .choice-btn:hover i { transform: scale(1.1) rotate(-5deg); }
        .choice-btn span { 
            font-size: 0.95rem; 
            font-weight: 700; 
            letter-spacing: -0.01em; 
            transition: all 0.3s;
        }
        .choice-btn:hover span { letter-spacing: 0.01em; }

        /* ── Labels ── */
        .scan-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 900;
            color: #000;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* ── Two-column inside Order card ── */
        /* ── Animations ── */
        @keyframes profilePulse {
            0% { transform: scale(1); filter: drop-shadow(0 5px 15px rgba(128, 0, 0, 0.1)); }
            50% { transform: scale(1.05); filter: drop-shadow(0 15px 30px rgba(128, 0, 0, 0.2)); }
            100% { transform: scale(1); filter: drop-shadow(0 5px 15px rgba(128, 0, 0, 0.1)); }
        }
        @keyframes statusPulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }
        .elite-avatar-anim {
            animation: profilePulse 4s infinite ease-in-out;
        }
        .elite-status-anim {
            animation: statusPulse 2s infinite;
        }

        .elite-card-content {
            display: flex;
            flex-direction: row;
        }
        .elite-card-main {
            flex: 1;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            background: #fff;
        }
        .elite-card-side {
            width: 360px;
            padding: 2.5rem 2rem;
            background: #1e293b; /* Deep Obsidian */
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            flex-shrink: 0;
            position: relative;
        }

        /* ── Customer box ── */
        .elite-customer-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.85rem 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }
        .customer-avatar {
            width: 38px; height: 38px;
            background: #fdf2f2;
            border: 1px solid #fee2e2;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            color: #800000;
            flex-shrink: 0;
        }

        /* ── Selects ── */
        .form-select-scan {
            height: 52px;
            width: 100%;
            border-radius: 12px;
            border: 1.5px solid transparent;
            background: #eef6ff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 1rem center / 1.1rem;
            padding: 0 2.5rem 0 1.25rem;
            font-weight: 600;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            outline: none;
            appearance: none;
            transition: all 0.3s;
            cursor: pointer;
        }
        .form-select-scan:focus { 
            background: #fff;
            border-color: #800000; 
            box-shadow: 0 10px 20px -10px rgba(128,0,0,0.1); 
        }

        .elite-subtotal-box {
            height: 52px;
            background: #eef6ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #475569;
            font-size: 0.9rem;
            border: 1.5px solid transparent;
        }

        /* ── Operation rows ── */
        .op-row {
            padding: 0.75rem 1rem;
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.78rem;
            transition: border-color 0.2s;
        }
        .op-row:hover { border-color: #e2e8f0; }

        #ops-container {
            flex: 1;
            min-height: 90px;
            max-height: 160px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        #ops-container::-webkit-scrollbar { width: 3px; }
        #ops-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        /* ── Action Buttons ── */
        .btn-scan-add {
            width: 100%;
            height: 52px;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            color: #1e293b;
            font-weight: 800;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-scan-add:hover {
            border-color: #800000;
            color: #800000;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(128,0,0,0.15);
        }

        .btn-final-sum {
            width: 100%;
            height: 58px;
            background: #cc0000; 
            border: none;
            border-radius: 16px;
            color: #fff;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 15px 35px -12px rgba(204, 0, 0, 0.4);
        }
        .btn-final-sum:hover {
            background: #ff0000;
            transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgba(255, 0, 0, 0.5);
        }
        .btn-final-sum i { font-size: 1.5rem; }

        .summary-title {
            font-size: 0.72rem;
            font-weight: 800;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.4rem;
        }
        .summary-title i { color: #fff; font-size: 0.95rem; opacity: 0.7; }

        /* ── Close scanner ── */
        .btn-close-scanner {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            background: #f1f5f9; color: #475569; border: none;
            height: 36px; width: 100%;
            border-radius: 8px; margin-top: 0.75rem;
            font-size: 0.78rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-close-scanner:hover { background: #e2e8f0; color: #1e293b; }

        @media (max-width: 1100px) {
            .elite-card-content { flex-direction: column; }
            .elite-card-main { border-right: none; border-bottom: 1px solid #f1f5f9; padding: 1.5rem !important; }
            .elite-card-side { width: 100% !important; padding: 2rem !important; border-radius: 0 0 20px 20px; }
        }
        @media (max-width: 768px) {
            .scan-top-search { flex-direction: column; gap: 2rem; align-items: center; padding: 1.5rem; text-align: center; }
            .scanner-visual-box { width: 100%; max-width: 280px; height: auto; }
            .search-options-group { gap: 1.5rem; padding-left: 0; width: 100%; }
            .choice-btn { width: 100%; padding: 0 1.2rem; height: 56px; }
            #res-name { font-size: 1.5rem !important; }
            .elite-input-wrapper { flex-direction: column; width: 100%; gap: 0.75rem; }
            .elite-input { width: 100% !important; min-height: 52px; text-align: center; }
            .btn-search-icon { width: 100%; height: 52px; border-radius: 12px; }
            .search-separator { margin: 1rem 0; }
            
            /* Formulario Móvil */
            .elite-form-container { padding: 1.25rem !important; }
            .elite-service-grid { grid-template-columns: 1fr !important; gap: 1.25rem !important; }
            .elite-abono-card { flex-direction: column; gap: 1.5rem; text-align: center; padding: 1.5rem !important; }
            .elite-add-btn { width: 100%; justify-content: center; height: 60px !important; }
        }

        /* ── Scanner Overlay ── */
        .scanner-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(8px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            animation: fadeIn 0.3s ease;
        }
        .scanner-modal {
            background: #fff;
            width: 100%;
            max-width: 480px;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 40px 120px rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            animation: modalPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes modalPop { from { transform: scale(0.8) translateY(40px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        #reader {
            width: 100% !important;
            border: none !important;
            background: #000;
        }
        #reader video {
            object-fit: cover !important;
        }
        #reader__scan_region {
            background: #000 !important;
        }
        #reader__dashboard {
            display: none !important;
        }
    </style>
</head>
<body onload="initLayout()">

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión de Puntos';
            $pageSubtitle = 'Suma el puntaje asignado al cliente';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">

            <div class="scan-wrapper">
                <input type="file" id="qr-input-file" accept="image/*" style="display: none;" onchange="onFileChange(event)">

                <!-- ── Card 1: Búsqueda QR ── -->
                <div class="card" style="margin-bottom: 0;">
                    <div class="card-header" style="justify-content: flex-start; background: #f1f5f9;">
                        <div class="header-title-flex">
                            <i class='bx bx-qr-scan'></i>
                            <div class="title-text-group">
                                <h3>Búsqueda QR</h3>
                                <span>Escanea o ingresa el documento manualmente</span>
                            </div>
                        </div>
                    </div>

                    <div class="scan-top-search">
                        <!-- Cuadro de Escaneo Premium -->
                        <div class="scanner-visual-box" onclick="initScanner()">
                            <div class="scanner-corner tl"></div>
                            <div class="scanner-corner tr"></div>
                            <div class="scanner-corner bl"></div>
                            <div class="scanner-corner br"></div>
                            
                            <i class='bx bx-qr-scan'></i>
                            <span>Abrir Escáner</span>
                        </div>

                        <!-- Opciones de Búsqueda -->
                        <div class="search-options-group">
                            <div>
                                <label class="scan-label">Búsqueda rápida por DNI o RUC</label>
                                <div class="elite-input-wrapper">
                                    <input type="tel" id="manual-dni" class="elite-input" placeholder="DNI (8) o RUC (11 dígitos)..." maxlength="11" onkeydown="if(event.key==='Enter') buscarManual()">
                                    <button class="btn-search-icon" onclick="buscarManual()" title="Buscar">
                                        <i class='bx bx-search'></i>
                                    </button>
                                </div>
                            </div>

                            <div class="search-separator">o también</div>

                            <div>
                                <label class="scan-label">Importar desde el dispositivo</label>
                                <div class="choice-btn" onclick="document.getElementById('qr-input-file').click()">
                                    <i class='bx bx-image-add' style="color: #800000;"></i>
                                    <span>Seleccionar imagen QR</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Scanner Overlay -->
                <div id="qr-reader-overlay" class="scanner-overlay">
                    <div class="scanner-modal">
                        <div style="padding: 1.5rem 1.8rem; background: #fff; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="background: #fdf2f2; color: #800000; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    <i class='bx bx-camera'></i>
                                </div>
                                <div style="display: flex; flex-direction: column;">
                                    <h3 style="font-size: 0.95rem; font-weight: 850; color: #1e293b; margin: 0;">Escaneando QR</h3>
                                    <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 600;">Apunta al código del cliente</span>
                                </div>
                            </div>
                            <button onclick="stopScanner()" style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                <i class='bx bx-x'></i>
                            </button>
                        </div>
                        
                        <div style="position: relative; background: #000;">
                            <div id="reader"></div>
                            <!-- Visual Frame Overlay -->
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; display: flex; align-items: center; justify-content: center;">
                                <div style="width: 250px; height: 250px; border: 2px solid rgba(255,255,255,0.3); border-radius: 24px; position: relative;">
                                    <div style="position: absolute; top: -2px; left: -2px; width: 40px; height: 40px; border-top: 4px solid #fff; border-left: 4px solid #fff; border-radius: 20px 0 0 0;"></div>
                                    <div style="position: absolute; top: -2px; right: -2px; width: 40px; height: 40px; border-top: 4px solid #fff; border-right: 4px solid #fff; border-radius: 0 20px 0 0;"></div>
                                    <div style="position: absolute; bottom: -2px; left: -2px; width: 40px; height: 40px; border-bottom: 4px solid #fff; border-left: 4px solid #fff; border-radius: 0 0 0 20px;"></div>
                                    <div style="position: absolute; bottom: -2px; right: -2px; width: 40px; height: 40px; border-bottom: 4px solid #fff; border-right: 4px solid #fff; border-radius: 0 0 20px 0;"></div>
                                    
                                    <!-- Animated scanning line -->
                                    <div style="position: absolute; width: 100%; height: 2px; background: linear-gradient(to right, transparent, #fff, transparent); top: 0; box-shadow: 0 0 15px #fff; animation: scanMove 2s infinite ease-in-out;"></div>
                                </div>
                                <style>
                                    @keyframes scanMove { 0% { top: 5%; opacity: 0; } 50% { opacity: 1; } 100% { top: 95%; opacity: 0; } }
                                </style>
                            </div>
                        </div>

                        <div style="padding: 1.5rem; background: #f8fafc; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; color: #64748b; font-size: 0.75rem; font-weight: 600;">
                                <i class='bx bx-loader-alt bx-spin' style="color: #800000;"></i>
                                BUSCANDO CÓDIGO...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Card 2: Orden de Suma (template) ── -->
                <div id="scan-right-panel" class="scan-right-panel">
                    <div id="right-panel-content"></div>
                </div>

                <template id="tpl-main-form">
                    <div class="card" style="margin-bottom: 0; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); border-radius: 24px;">
                        <div class="elite-card-content">
                            <!-- Izquierda: Workspace -->
                            <div class="elite-card-main" style="flex: 1; padding: 2.5rem; background: #fff; display: flex; flex-direction: column; gap: 2rem;">
                                <!-- Perfil del Cliente Rediseñado - Edición Pure -->
                                <!-- Perfil del Cliente - Micro-Identidad de Élite -->
                                <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 3rem; padding: 0.5rem 0; gap: 1.25rem;">
                                    
                                    <div style="position: relative;">
                                        <div style="width: 80px; height: 80px; border-radius: 20px; background: #fff; display: flex; align-items: center; justify-content: center; border: 1.5px solid #000; box-shadow: 8px 8px 0px rgba(0,0,0,0.03);">
                                            <i class='bx bxs-user-detail' style="font-size: 4rem; color: #000;"></i>
                                        </div>
                                        <!-- La Lucesita Verde Animada (Escala reducida) -->
                                        <div class="elite-status-anim" style="position: absolute; bottom: -5px; right: -5px; width: 16px; height: 16px; background: #22c55e; border-radius: 4px; border: 2.5px solid #fff;"></div>
                                    </div>
                                    
                                    <div>
                                        <div style="margin-bottom: 0.4rem;">
                                            <span style="font-size: 0.65rem; font-weight: 950; color: #000; text-transform: uppercase; letter-spacing: 5px; opacity: 0.3;">Beneficiario Registrado</span>
                                        </div>
                                        <b id="res-name" style="display: block; font-size: 1.8rem; font-weight: 950; color: #000; letter-spacing: -0.05em; margin-bottom: 10px; line-height: 1;">— — —</b>
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 1.5rem;">
                                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                                <i class='bx bx-id-card' style="font-size: 1.1rem; color: #000; opacity: 0.7;"></i>
                                                <span id="res-phone" style="font-size: 0.9rem; color: #000; font-weight: 800; font-family: 'Roboto Mono', monospace; letter-spacing: 0.5px;">00000000</span>
                                            </div>
                                            <div style="width: 1px; height: 14px; background: #000; opacity: 0.1;"></div>
                                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                                <span style="font-size: 0.75rem; font-weight: 900; color: #22c55e; text-transform: uppercase; letter-spacing: 1.5px;">Estatus Activo</span>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" id="client-id">
                                </div>

                                <div class="elite-form-container" style="background: #f8fafc; padding: 2rem; border-radius: 28px; border: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 1.5rem;">
                                    <div class="elite-service-grid" style="display: grid; grid-template-columns: 3fr 1fr; gap: 1rem;">
                                        <div>
                                            <label class="scan-label" style="margin-bottom: 0.75rem; color: #000; font-size: 0.82rem; letter-spacing: 1.5px;">SERVICIO PRESTADO</label>
                                            <select id="main-op-type" class="form-select-scan" style="width: 100%; background: #fff; height: 56px; border: 1px solid #e2e8f0; border-radius: 14px; font-weight: 700; color: #000; padding: 0 1.25rem; font-size: 0.82rem;" onchange="updateSubtotal()">
                                                <?php foreach ($operaciones as $op): ?>
                                                    <option value="<?= $op['puntos'] ?>"><?= htmlspecialchars($op['nombre']) ?> (+<?= $op['puntos'] ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="scan-label" style="margin-bottom: 0.75rem; color: #000; font-size: 0.82rem; letter-spacing: 1.5px;">CANTIDAD</label>
                                            <select id="main-op-qty" class="form-select-scan" style="width: 100%; background: #fff; height: 56px; border: 1px solid #e2e8f0; border-radius: 14px; font-weight: 900; color: #000; text-align: center; font-size: 0.82rem;" onchange="updateSubtotal()">
                                                <?php for($i=1;$i<=10;$i++): ?>
                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="elite-abono-card" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                                        <div>
                                            <label class="scan-label" style="font-size: 0.68rem; margin-bottom: 0.3rem; opacity: 0.5;">ABONO PROYECTADO</label>
                                            <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                                                <span id="main-op-unit" style="font-size: 2.5rem; font-weight: 950; color: #000; line-height: 1; letter-spacing: -2px;">0</span>
                                                <span style="font-size: 1.2rem; font-weight: 950; color: #000; line-height: 1; letter-spacing: -1px; opacity: 0.2;">PTS</span>
                                                <span style="font-size: 0.82rem; font-weight: 900; color: #000; text-transform: uppercase; opacity: 0.3;">puntos</span>
                                            </div>
                                        </div>
                                        <button class="elite-add-btn" onclick="addOperation()" style="background: #000; color: #fff; border: none; padding: 0 1.75rem; height: 56px; border-radius: 16px; font-size: 0.82rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 10px 20px -5px rgba(0,0,0,0.3);">
                                            <i class='bx bx-plus-circle' style="font-size: 1.2rem;"></i>
                                            <span class="btn-text">Añadir al Ticket</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Derecha: Barra Lateral Obsidian -->
                            <div class="elite-card-side" style="width: 360px; padding: 2.5rem 2rem; background: #000; color: #fff; display: flex; flex-direction: column; gap: 2rem; flex-shrink: 0; position: relative;">
                                <div>
                                    <div class="summary-title" style="font-size: 0.72rem; font-weight: 800; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.15em; display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                        <i class='bx bx-receipt' style="color: #fff; font-size: 1rem; opacity: 0.7;"></i> Detalles del Ticket
                                    </div>
                                    <div id="ops-container" style="margin-top: 1.5rem; flex: 1; min-height: 250px;">
                                        <div style="text-align: center; color: rgba(255,255,255,0.12); padding: 4rem 1rem;">
                                            <i class='bx bx-basket' style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                                            <span style="font-size: 0.8rem; font-weight: 500;">Esperando servicios...</span>
                                        </div>
                                    </div>
                                </div>

                                <div style="margin-top: auto; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.08);">
                                    <div class="summary-title" style="font-size: 0.82rem; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.15em; display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">Total Acumulado</div>
                                    <div id="main-total-pts" style="font-size: 4rem; font-weight: 950; color: #fff; text-align: center; margin: 0.5rem 0; letter-spacing: -3px; line-height: 1; text-shadow: 0 10px 20px rgba(0,0,0,0.3);">
                                        0
                                    </div>
                                    <div style="text-align: center; font-size: 0.82rem; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.4em; margin-bottom: 1.8rem; opacity: 0.4;">Puntos</div>

                                    <button id="save-all-btn" class="btn-final-sum" onclick="saveAll()">
                                        <i class='bx bx-check-shield'></i> Confirmar Todo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Template: Error -->
                <template id="tpl-error">
                    <div class="card" onclick="initLayout()" style="margin-bottom: 0; border: none; box-shadow: 0 80px 150px -40px rgba(0,0,0,0.1); border-radius: 56px; min-height: 480px; display: flex; align-items: center; justify-content: flex-start; background: linear-gradient(135deg, #fff 60%, #fff1f2 100%); cursor: pointer; transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); overflow: hidden; position: relative; padding: 4rem 6rem; gap: 5rem;">
                        
                        <!-- Icono a la Izquierda (Estático) -->
                        <div style="flex-shrink: 0; position: relative; z-index: 1;">
                            <div style="width: 200px; height: 200px; background: #fff; border-radius: 56px; display: flex; align-items: center; justify-content: center; border: 2px solid #fee2e2; box-shadow: 0 30px 60px -15px rgba(190, 18, 60, 0.12);">
                                <i class='bx bx-x' style="font-size: 10rem; color: #be123c; font-weight: 200;"></i>
                            </div>
                        </div>

                        <!-- Texto a la Derecha -->
                        <div style="flex: 1; text-align: left; position: relative; z-index: 1;">
                            <label style="font-size: 0.8rem; font-weight: 900; color: #be123c; text-transform: uppercase; letter-spacing: 5px; margin-bottom: 1.5rem; display: block; opacity: 0.5;">Error de Búsqueda</label>
                            <h3 style="font-size: 3.2rem; font-weight: 950; color: #0f172a; letter-spacing: -0.06em; margin-bottom: 1.5rem; line-height: 1;">Sin resultados <br> encontrados</h3>
                            <p style="font-size: 1.15rem; color: #64748b; line-height: 1.8; font-weight: 500; margin-bottom: 3.5rem; max-width: 500px;">No hemos podido localizar al beneficiario en nuestra base de datos. Verifica el DNI/RUC o el código QR e intenta de nuevo.</p>
                            
                            <div style="display: flex; align-items: center; gap: 1.2rem;">
                                <div style="width: 12px; height: 12px; background: #be123c; border-radius: 50%; opacity: 0.3;"></div>
                                <span style="font-size: 0.85rem; color: #0f172a; font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">Toca para reintentar</span>
                            </div>
                        </div>

                        <!-- Decorative background element -->
                        <div style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(254, 226, 226, 0.4) 0%, rgba(255,255,255,0) 70%); pointer-events: none;"></div>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const baseUrl = BASE_URL;
        let html5QrCode;
        let operations = [];
        let running = false;

        function initLayout() {
            const content = document.getElementById('right-panel-content');
            content.innerHTML = '';
            content.appendChild(document.getElementById('tpl-main-form').content.cloneNode(true));
            if(document.getElementById('main-op-unit')) updateSubtotal();
            document.getElementById('scan-right-panel').classList.remove('active');
        }

        async function initScanner() {
            const overlay = document.getElementById('qr-reader-overlay');
            overlay.style.display = 'flex';
            
            if (html5QrCode) {
                try { await html5QrCode.stop(); } catch(e) {}
            }
            
            html5QrCode = new Html5Qrcode("reader");
            const config = { 
                fps: 15, // Bajamos FPS para que el procesador del móvil se enfoque en el detalle
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0,
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                }
            };

            try {
                // Forzamos video a pantalla completa en el contenedor
                await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
            } catch (err) {
                console.warn("Error con facingMode: environment:", err);
                try {
                    const devices = await Html5Qrcode.getCameras();
                    if (devices && devices.length > 0) {
                        const cameraId = devices[devices.length - 1].id;
                        await html5QrCode.start(cameraId, config, onScanSuccess);
                    } else {
                        throw new Error("No cameras found");
                    }
                } catch (err2) {
                    console.error("Error final al iniciar cámara:", err2);
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Error de cámara', 
                        text: 'No se pudo acceder a la cámara.'
                    });
                    overlay.style.display = 'none';
                }
            }
        }

        async function onFileChange(event) {
            if (event.target.files.length === 0) return;
            const imageFile = event.target.files[0];
            
            // Si ya hay un escáner de cámara corriendo, lo detenemos primero
            if (html5QrCode && html5QrCode.getState() === 2) {
                try { await html5QrCode.stop(); } catch(e) {}
            }

            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            
            Swal.fire({ 
                title: 'Escaneando imagen...', 
                html: 'Analizando el código QR de la imagen seleccionada',
                didOpen: () => { Swal.showLoading(); } 
            });

            try {
                const decodedText = await html5QrCode.scanFile(imageFile, true);
                Swal.close();
                onScanSuccess(decodedText);
            } catch (err) {
                console.error("Error al escanear archivo:", err);
                Swal.fire({ 
                    icon: 'error', 
                    title: 'No se detectó QR', 
                    text: 'Asegúrate de que la imagen sea clara y contenga un código QR de PremiaSurgas.',
                    confirmButtonColor: '#0f172a'
                });
            }
            event.target.value = '';
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.getState() === 2) {
                html5QrCode.stop().then(() => {
                    document.getElementById('qr-reader-overlay').style.display = 'none';
                }).catch(() => {
                    document.getElementById('qr-reader-overlay').style.display = 'none';
                });
            } else {
                document.getElementById('qr-reader-overlay').style.display = 'none';
            }
        }

        function onScanSuccess(decodedText) {
            console.log("QR Detectado:", decodedText);
            
            if (navigator.vibrate) navigator.vibrate(100);

            let codigo = decodedText;
            if (decodedText.includes('c=')) {
                const urlParams = new URLSearchParams(decodedText.split('?')[1]);
                codigo = urlParams.get('c');
            } else if (decodedText.includes('/')) {
                codigo = decodedText.split('/').pop();
            }
            
            const processAndSearch = () => {
                document.getElementById('qr-reader-overlay').style.display = 'none';
                buscarCliente(codigo);
            };

            // Solo intentamos detener si el escáner está realmente escaneando (cámara)
            if (html5QrCode && html5QrCode.getState() === 2) {
                html5QrCode.stop().then(processAndSearch).catch(processAndSearch);
            } else {
                processAndSearch();
            }
        }

        function buscarManual() {
            const valor = document.getElementById('manual-dni').value.trim();
            if (!/^\d{8}$/.test(valor) && !/^\d{11}$/.test(valor)) {
                Swal.fire({ icon: 'warning', title: 'Documento inválido', text: 'Ingresa un DNI de 8 dígitos o un RUC de 11 dígitos.' });
                return;
            }
            buscarCliente(valor);
        }

        async function buscarCliente(codigo) {
            const rightPanel = document.getElementById('scan-right-panel');
            const content = document.getElementById('right-panel-content');
            try {
                const res = await fetch(baseUrl + 'scan/buscar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ codigo })
                });
                const data = await res.json();
                operations = [];
                if (data.success) {
                    content.innerHTML = '';
                    content.appendChild(document.getElementById('tpl-main-form').content.cloneNode(true));
                    document.getElementById('res-name').innerText = data.cliente.nombre;
                    document.getElementById('res-phone').innerText = data.cliente.celular;
                    document.getElementById('client-id').value = data.cliente.id;
                    rightPanel.classList.add('active');
                    updateSubtotal();
                } else {
                    rightPanel.classList.remove('active');
                    content.innerHTML = '';
                    Swal.fire({
                        icon: 'warning',
                        title: '<span style="font-weight: 900; color: #0f172a; font-size: 1.8rem; letter-spacing: -1px;">Beneficiario no encontrado</span>',
                        html: '<p style="font-size: 1rem; color: #64748b; font-weight: 500;">El DNI o código QR no está registrado en el sistema. Por favor, verifica los datos e intenta nuevamente.</p>',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#0f172a',
                        padding: '2.5rem',
                        borderRadius: '24px'
                    });
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error de conexión' });
            }
        }

        function updateSubtotal() {
            const select = document.getElementById('main-op-type');
            if(!select) return;
            const unit = parseInt(select.value);
            const qty = parseInt(document.getElementById('main-op-qty').value) || 1;
            const subtotalBox = document.getElementById('main-op-unit');
            if(subtotalBox) subtotalBox.innerText = (unit * qty) + ' PTS';
        }

        function addOperation() {
            const select = document.getElementById('main-op-type');
            if(!select) return;
            const typeName = select.options[select.selectedIndex].text.split(' (')[0];
            const unit = parseInt(select.value);
            const qty = parseInt(document.getElementById('main-op-qty').value) || 1;
            const subtotal = unit * qty;
            operations.push({ name: typeName, unit, qty, subtotal });
            renderOperations();
        }

        function removeOperation(index) {
            operations.splice(index, 1);
            renderOperations();
        }

        function renderOperations() {
            const container = document.getElementById('ops-container');
            if (operations.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; color: rgba(255,255,255,0.15); padding: 4rem 1rem;">
                        <i class='bx bx-basket' style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <span style="font-size: 0.8rem; font-weight: 500; letter-spacing: 1px;">Esperando servicios...</span>
                    </div>`;
                document.getElementById('main-total-pts').innerText = '0';
                document.getElementById('save-all-btn').disabled = true;
                return;
            }
            let html = '';
            let total = 0;
            operations.forEach((op, i) => {
                total += op.subtotal;
                html += `
                    <div style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.8rem; margin-bottom: 1rem; position: relative; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <div style="font-size: 0.82rem; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 4px; opacity: 0.4;">Servicio</div>
                                <div style="font-size: 0.82rem; font-weight: 700; color: #fff; line-height: 1.3;">${op.name}</div>
                            </div>
                            <button onclick="removeOperation(${i})" style="background: rgba(239, 68, 68, 0.1); border: none; width: 32px; height: 32px; border-radius: 10px; cursor: pointer; color: #ef4444; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                                <i class='bx bx-trash-alt' style="font-size: 1.1rem;"></i>
                            </button>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-top: 0.8rem; border-top: 1px solid rgba(255,255,255,0.08);">
                            <div>
                                <div style="font-size: 0.82rem; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 1px; opacity: 0.3;">Cant.</div>
                                <div style="font-size: 0.82rem; font-weight: 700; color: #fff; opacity: 0.9;">x${op.qty}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.82rem; font-weight: 900; color: #22c55e; text-transform: uppercase; letter-spacing: 1.5px;">Abono</div>
                                <div style="font-size: 1.2rem; font-weight: 950; color: #fff; letter-spacing: -0.02em;">+${op.subtotal} <span style="font-size: 0.82rem; opacity: 0.5;">PTS</span></div>
                            </div>
                        </div>
                    </div>`;
            });
            container.innerHTML = html;
            document.getElementById('main-total-pts').innerText = total;
            document.getElementById('save-all-btn').disabled = false;
        }

        async function saveAll() {
            if (running) return;
            const total = parseInt(document.getElementById('main-total-pts').innerText);
            const clientId = document.getElementById('client-id').value;
            if (total <= 0) return;
            running = true;
            const btn = document.getElementById('save-all-btn');
            btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> Guardando...";
            btn.disabled = true;
            try {
                let detalleString = operations.map(op => `• ${op.name} x${op.qty} (+${op.subtotal} pts)`).join('\n');
                if (operations.length > 1) {
                    detalleString += `\n──────────\nTOTAL: ${total} pts`;
                }
                const res = await fetch(baseUrl + 'scan/registrar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        cliente_id: clientId, 
                        puntos: total, 
                        detalle: detalleString,
                        items: operations.map(op => ({
                            nombre: op.name,
                            cantidad: op.qty,
                            puntos_unitarios: op.unit,
                            subtotal: op.subtotal
                        }))
                    })
                });
                const data = await res.json();
                if (data.success) {
                    Swal.fire({ icon: 'success', title: '¡Registrado!', timer: 1500, showConfirmButton: false })
                        .then(() => window.location.href = baseUrl + 'panel');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    running = false;
                    btn.innerHTML = "<i class='bx bx-check-circle'></i> Registrar Puntos";
                    btn.disabled = false;
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error de conexión' });
                running = false;
                btn.innerHTML = "<i class='bx bx-check-circle'></i> Registrar Puntos";
                btn.disabled = false;
            }
        }
    </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
