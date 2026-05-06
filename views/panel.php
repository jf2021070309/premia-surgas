<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png?v=<?= time() ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <style>
        [v-cloak] {
            display: none !important;
        }

        /* ══════════════════════════════════════ 
           Premium Dashboard Components 
           ══════════════════════════════════════ */
        .notif-card {
            background: white;
            border-radius: 1rem;
            padding: 0.85rem 1.25rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notif-card:hover {
            transform: translateY(-2px);
            border-color: var(--primary, #800000);
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.05);
        }

        .notif-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #f8fafc;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .notif-icon.full { background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; }
        .notif-icon.mix  { background: #fff7ed; color: #9a3412; border: 1px solid #ffedd5; }
        .notif-icon.wait { background: #fdf2f2; color: #800000; border: 1px solid #fee2e2; }

        .notif-content {
            flex: 1;
            min-width: 0;
        }

        .notif-title {
            font-weight: 800;
            color: #1e293b;
            font-size: 0.88rem;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-sub {
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 500;
        }

        /* Modal Detallado */
        .modal-mask {
            position: fixed;
            z-index: 9998;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .modal-wrapper {
            width: 100%;
            max-width: 450px;
            margin: auto;
            padding: 1.5rem;
        }

        .modal-container {
            background-color: #fff;
            border-radius: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .modal-header-notif {
            background: var(--primary);
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .modal-body-notif {
            padding: 2rem;
        }

        @media (max-width: 600px) {
            .panel-title {
                font-size: 1.1rem !important;
                margin: .8rem 0 .3rem !important;
            }

            .panel-subtitle {
                font-size: .82rem !important;
                margin-bottom: .7rem !important;
            }

            input,
            select,
            textarea {
                font-size: 16px !important;
            }
        }

        /* ── Dashboard container padding responsive ── */
        .dashboard-container {
            padding: 1.5rem 2rem;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        @media (max-width: 900px) {
            .dashboard-container {
                padding: 1.25rem 1rem;
            }
        }
        @media (max-width: 600px) {
            .dashboard-container {
                padding: 1rem 0.75rem;
            }
        }

        /* ── KPI grid responsive ── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 600px) {
            .kpi-grid {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }
        }

        .middle-row-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 1000px) {
            .middle-row-grid {
                grid-template-columns: 1fr;
            }
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 2.5fr 1fr;
            gap: 1.5rem;
            align-items: stretch;
        }

        /* ── Chart containers responsive ── */
        @media (max-width: 1100px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 600px) {
            .mobile-chart-h { height: 350px !important; }
            .rankings-container { max-height: 400px !important; }
            .donut-container { height: 300px !important; }
            .charts-grid > div > div:last-child { padding: 1rem 0.75rem !important; } /* Reduce card padding on mobile */
        }

        /* ── Conductor banner responsive ── */
        @media (max-width: 700px) {
            .banner-container {
                flex-direction: column !important;
                padding: 1.5rem !important;
            }
            .banner-img-col {
                display: none !important;
            }
            .banner-content-col {
                padding: 0 !important;
            }
            .b-title {
                font-size: 2rem !important;
            }
            .conductor-kpi-grid {
                grid-template-columns: 1fr 1fr !important;
            }
            .dash-card-number-conductor {
                font-size: 2.2rem !important;
            }
        }
        @media (max-width: 480px) {
            .conductor-kpi-grid {
                grid-template-columns: 1fr !important;
            }
        }



        /* ══════════════════════════════════════ 
           PREMIUM CONDUCTOR DASHBOARD BANNERS 
           ══════════════════════════════════════ */
        @keyframes slideFadeDown {
            0% { opacity: 0; transform: translateY(-30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .anim-welcome {
            opacity: 0;
            animation: slideFadeDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .anim-card {
            opacity: 0;
            animation: slideFadeDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .anim-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
            filter: brightness(1.1);
        }
        /* Banner Container (Static White) */
        .banner-container {
            background-color: white !important;
            border-radius: 20px; 
            border: 1px solid #e5e7eb !important; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); 
            margin-bottom: 2.5rem; 
            display: flex; 
            overflow: hidden; 
            position: relative;
        }

        /* Banner Text Elements (Static Light) */
        .b-title {
            font-weight: 900; color: #0f172a !important; margin: 0; font-size: 3rem; letter-spacing: -1.5px; line-height: 1;
        }
        .b-subtitle {
            color: #64748b !important; margin: 0.8rem 0 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800;
        }
        .b-brand {
            color: #800000 !important; font-weight: 900;
        }
        .b-dot {
            color: #cbd5e1 !important; margin: 0 0.5rem;
        }

        /* Card Styles */
        .banner-card-orange {
            background-color: #f97316 !important; /* Vibrant Orange */
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.25) !important;
        }
        .banner-card-wine {
            background-color: #380b0d !important; /* Dark Wine */
            box-shadow: 0 10px 20px rgba(56, 11, 13, 0.25) !important;
        }

        /* Activity List Animation & Styling */
        .activity-row {
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 1rem 0.5rem; 
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
            opacity: 0;
            animation: slideFadeDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            background: transparent;
        }
        .activity-row:last-child {
            border-bottom: none;
        }
        .activity-row:hover {
            background: #f1f5f9;
            border-radius: 8px;
            border-bottom-color: transparent;
            transform: translateX(4px);
        }
        .activity-avatar {
            width: 40px; height: 40px; 
            border-radius: 50%; 
            background: #f8fafc; 
            border: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: center; 
            color: #64748b; font-size: 1rem; font-weight: 800;
            transition: all 0.3s ease;
        }
        .activity-row:hover .activity-avatar {
            background: #f97316;
            border-color: #f97316;
            color: white;
            box-shadow: 0 4px 10px rgba(249, 115, 22, 0.2);
        }
    </style>
</head>

<body>
    <div id="app">
        <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

        <div class="admin-layout">
            <?php
            $pageTitle = 'Panel de Control';
            $pageSubtitle = 'Notificaciones recientes';
            include __DIR__ . '/partials/header_admin.php';
            ?>

            <div class="container dashboard-container" style="width: 100%; max-width: 100%;">

                <?php if ($_SESSION['rol'] === 'admin'): ?>

                    <!-- TOP ROW: KPI Metrics -->
                    <div class="kpi-grid">

                        <!-- Orange Card (Usuarios) -->
                        <div class="dash-card card-orange" onclick="location.href='<?= BASE_URL ?>clientes/lista'">
                            <div class="dash-card-body">
                                <div class="dash-card-number"><?= $totales['clientes'] ?></div>
                                <div class="dash-card-text">Usuarios</div>
                                <i class='bx bx-user-plus dash-card-icon'></i>
                            </div>
                        </div>

                        <!-- Wine Card (Canjes) -->
                        <div class="dash-card card-wine" onclick="location.href='<?= BASE_URL ?>canjes-admin'">
                            <div class="dash-card-body">
                                <div class="dash-card-number"><?= $metricas_adicionales['canjes_hoy'] ?></div>
                                <div class="dash-card-text">Canjes Hoy</div>
                                <i class='bx bx-gift dash-card-icon'></i>
                            </div>
                        </div>

                        <!-- Darker Card (Puntos) -->
                        <div class="dash-card card-dark" onclick="location.href='<?= BASE_URL ?>scan'">
                            <div class="dash-card-body">
                                <div class="dash-card-number"><?= $metricas_adicionales['puntos_hoy'] ?></div>
                                <div class="dash-card-text">Puntos Dados</div>
                                <i class='bx bx-star dash-card-icon'></i>
                            </div>
                        </div>

                        <!-- Wine Card (Pendientes) -->
                    <div class="dash-card card-wine" onclick="location.href='<?= BASE_URL ?>recargas-admin'">
                            <div class="dash-card-body">
                                <div class="dash-card-number"><?= count($notificaciones_recargas) ?></div>
                                <div class="dash-card-text">Pendientes</div>
                                <i class='bx bx-wallet dash-card-icon'></i>
                            </div>
                        </div>

                    </div>

                    <!-- MIDDLE ROW: Chart & Rankings -->
                    <div class="middle-row-grid">

                        <!-- LEFT COLUMN: Main Charts -->
                        <div style="display: flex; flex-direction: column; gap: 1.5rem; min-width: 0;">
                            
                            <!-- CONSOLIDATED ROW: Top 10 + Donut -->
                            <div class="charts-grid">
                                
                                <!-- TOP 10 PREMIOS (Wider) -->
                                <div style="display: flex; flex-direction: column;">
                                    <div style="margin-bottom: 0.8rem; margin-top: 0.5rem;">
                                        <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: #111827; display: flex; align-items: center; gap: 0.5rem;">
                                            <i class='bx bx-bar-chart-alt-2' style="color: #7B1A1A; font-size: 1.1rem;"></i> Top 10 Premios Más Populares
                                        </h3>
                                        <p style="font-size: 0.73rem; color: #6B7280; margin: 0; margin-top: 0.2rem; font-weight: 400;">Los productos preferidos por los clientes</p>
                                    </div>
                                    <div style="background: white; border-radius: 14px; padding: 1.25rem 1.5rem; border: 1px solid #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.04); flex: 1; display: flex; flex-direction: column; justify-content: center;">
                                        <div class="mobile-chart-h" style="position: relative; height: 380px; width: 100%;">
                                            <canvas id="premiosBarChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <!-- DONUT: Tipo de Canjes -->
                                <div style="display: flex; flex-direction: column;">
                                    <div style="margin-bottom: 0.8rem; margin-top: 0.5rem;">
                                        <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: #111827; display: flex; align-items: center; gap: 0.5rem;">
                                            <i class='bx bx-pie-chart-alt-2' style="color: #7B1A1A; font-size: 1.1rem;"></i> Canjes
                                        </h3>
                                        <p style="font-size: 0.73rem; color: #6B7280; margin: 0; margin-top: 0.2rem; font-weight: 400;">Distribución Full vs Mix</p>
                                    </div>
                                    <div style="background: white; border-radius: 14px; padding: 1.5rem 1rem; border: 1px solid #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.04); flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <div class="donut-container" style="position: relative; height: 240px; width: 100%;">
                                            <canvas id="donutCanjesChart"></canvas>
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.6rem; padding: 1.5rem 1rem 0; font-size: 0.75rem; font-weight: 600; color: #6B7280; width: 100%;">
                                            <div style="display:flex;justify-content:space-between;align-items:center; border-bottom: 1px solid #F3F4F6; padding-bottom: 0.5rem;">
                                                <span style="display:flex;align-items:center;"><span style="width:10px;height:10px;border-radius:50%;background:#7B1A1A;display:inline-block;margin-right:8px;"></span> Canjes Full</span>
                                                <span style="color:#111827; font-size: 0.85rem; font-weight: 700;"><?= $metricas_adicionales['canjes_full'] ?></span>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;align-items:center; padding-top: 0.2rem;">
                                                <span style="display:flex;align-items:center;"><span style="width:10px;height:10px;border-radius:50%;background:#C4722A;display:inline-block;margin-right:8px;"></span> Canjes Mix</span>
                                                <span style="color:#111827; font-size: 0.85rem; font-weight: 700;"><?= $metricas_adicionales['canjes_mix'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div> <!-- End LEFT COLUMN -->

                        <!-- RIGHT COLUMN: Rankings (Minified) -->
                        <div style="display: flex; flex-direction: column; gap: 1.5rem; min-width: 0;">
                            
                            <!-- Ranking Canjeadores (Clientes) -->
                            <div style="display: flex; flex-direction: column; flex: 1;">
                                <div style="margin-bottom: 0.8rem;">
                                    <h5 style="margin: 0; font-size: 0.9rem; font-weight: 600; color: #111827; display: flex; align-items: center; gap: 0.4rem;">
                                        <i class='bx bx-star' style="color: #C4722A;"></i> Top Canjeadores
                                    </h5>
                                </div>
                                <div style="background: white; border-radius: 14px; padding: 1rem; border: 1px solid #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.04); flex: 1; display: flex; flex-direction: column;">
                                    <div class="rankings-container" style="flex: 1; overflow-y: auto; padding-right: 5px; scrollbar-width: thin; scrollbar-color: #f1f5f9 transparent; max-height: 520px;">
                                        <?php if (!empty($metricas_adicionales['ranking'])): ?>
                                            <?php foreach ($metricas_adicionales['ranking'] as $idx => $rank): 
                                                $initials = strtoupper(substr($rank['nombre'], 0, 1));
                                                $isFirst = ($idx === 0);
                                            ?>
                                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 1rem; border-radius: 1rem; background: <?= $isFirst ? '#fffcf0' : 'white' ?>; border: 1px solid <?= $isFirst ? '#fef3ac' : '#f1f5f9' ?>; margin-bottom: 0.5rem; transition: all 0.2s ease; box-shadow: <?= $isFirst ? '0 4px 12px rgba(245, 158, 11, 0.08)' : '0 2px 4px rgba(0,0,0,0.01)' ?>;">
                                                    <div style="display: flex; align-items: center; gap: 0.8rem; min-width: 0; flex: 1;">
                                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: <?= $isFirst ? '#f59e0b' : '#f1f5f9' ?>; color: <?= $isFirst ? 'white' : '#64748b' ?>; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; flex-shrink: 0; border: 2px solid <?= $isFirst ? '#fbbf24' : 'white' ?>;">
                                                            <?= $initials ?>
                                                        </div>
                                                        <div style="min-width: 0; flex: 1;">
                                                            <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                <?= htmlspecialchars($rank['nombre']) ?>
                                                            </div>
                                                            <div style="font-size: 0.68rem; font-weight: 600; color: #94a3b8; display: flex; align-items: center; gap: 0.3rem;">
                                                                <?php if ($isFirst): ?>
                                                                    <i class='bx bxs-trophy' style="color: #f59e0b;"></i> Líder Actual
                                                                <?php else: ?>
                                                                    Posición <?= $idx + 1 ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="text-align: right; flex-shrink: 0; margin-left: 0.5rem;">
                                                        <div style="font-size: 0.95rem; font-weight: 900; color: #334155; line-height: 1;"><?= $rank['total_canjes'] ?></div>
                                                        <div style="font-size: 0.6rem; font-weight: 800; color: #cbd5e1; text-transform: uppercase; margin-top: 2px;">Canjes</div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div style="text-align: center; padding: 2rem 0;">
                                                <i class='bx bx-ghost' style="font-size: 2.5rem; color: #f1f5f9; display: block; margin-bottom: 0.5rem;"></i>
                                                <p style="font-size: 0.75rem; color: #94a3b8; margin: 0;">Aún no hay canjes registrados</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['rol'] === 'admin' && !empty($notificaciones_recargas)): ?>
                    <div style="margin-top: 1.5rem; margin-bottom: 1.25rem;">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                            <i class='bx bx-wallet' style="color: var(--p-wine, #800000); font-size: 1.25rem;"></i> Solicitudes de Recarga
                        </h3>
                    </div>
                    <div class="notif-list">
                        <?php foreach ($notificaciones_recargas as $r): ?>
                            <div class="notif-card" onclick="location.href='<?= BASE_URL ?>recargas-admin'">
                                <div class="notif-icon wait">
                                    <i class='bx bx-time-five'></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title"><?= htmlspecialchars($r['cliente_nombre']) ?></div>
                                    <div class="notif-sub">Monto de recarga: <b><?= $r['puntos'] ?> puntos</b></div>
                                </div>
                                <div style="text-align: right; flex-shrink: 0;">
                                    <div style="font-size: 0.72rem; font-weight: 800; color: #800000; background: #fee2e2; padding: 2px 8px; border-radius: 6px; display: inline-block; margin-bottom: 4px;">
                                        PENDIENTE
                                    </div>
                                    <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 500;"><?= date('H:i', strtotime($r['fecha'])) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['rol'] === 'admin' && !empty($ventas_pendientes)): ?>
                    <div style="margin-top: 1.5rem; margin-bottom: 1.25rem;">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                            <i class='bx bx-bus' style="color: #f59e0b; font-size: 1.25rem;"></i> Puntos de Conductores Pendientes
                        </h3>
                    </div>
                    <div class="notif-list">
                        <?php foreach ($ventas_pendientes as $v): ?>
                            <div class="notif-card" style="cursor: default; display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                                    <div class="notif-icon wait">
                                        <i class='bx bx-user'></i>
                                    </div>
                                    <div class="notif-content">
                                        <div class="notif-title"><?= htmlspecialchars($v['cliente_nombre']) ?></div>
                                        <div class="notif-sub">Conductor: <b><?= htmlspecialchars($v['conductor_nombre']) ?></b> <span class="b-dot">•</span> <b>+<?= $v['puntos'] ?> pts</b></div>
                                        <div style="font-size: 0.75rem; color: #64748b;"><?= htmlspecialchars($v['detalle']) ?></div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button @click="validarVenta(<?= $v['id'] ?>, 'aprobado')" class="btn" style="background: #22c55e; color: white; border: none; padding: 0.5rem 0.8rem; border-radius: 8px; font-size: 0.8rem; cursor: pointer; font-weight: 700;">
                                        <i class='bx bx-check'></i> Aprobar
                                    </button>
                                    <button @click="validarVenta(<?= $v['id'] ?>, 'rechazado')" class="btn" style="background: #ef4444; color: white; border: none; padding: 0.5rem 0.8rem; border-radius: 8px; font-size: 0.8rem; cursor: pointer; font-weight: 700;">
                                        <i class='bx bx-x'></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['rol'] === 'admin' && !empty($notificaciones)): ?>
                    <div style="margin-top: 2rem; margin-bottom: 1.25rem;">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                            <i class='bx bx-gift' style="color: var(--p-wine, #800000); font-size: 1.25rem;"></i> Últimos Canjes
                        </h3>
                    </div>

                    <div class="notif-list">
                        <?php foreach ($notificaciones as $n):
                            $esMix = $n['monto'] > 0;
                            ?>
                            <div class="notif-card" @click="verDetalle(<?= htmlspecialchars(json_encode($n)) ?>)">
                                <div class="notif-icon <?= $esMix ? 'mix' : 'full' ?>">
                                    <i class='bx <?= $esMix ? 'bx-coin-stack' : 'bx-gift' ?>'></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title"><?= htmlspecialchars($n['cliente_nombre']) ?></div>
                                    <div class="notif-sub">Premio: <b><?= htmlspecialchars($n['premio_nombre']) ?></b></div>
                                </div>
                                <div style="text-align: right;">
                                    <div class="badge <?= $esMix ? 'badge-danger' : 'badge-success' ?>" style="font-size: 0.65rem; padding: 0.3rem 0.6rem;">
                                        <?= $esMix ? 'Puntos + S/' . $n['monto'] : 'Canje Full' ?>
                                    </div>
                                    <div style="font-size: 0.7rem; color: #bbb; margin-top: 0.3rem;">
                                        <?= date('H:i', strtotime($n['fecha'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($_SESSION['rol'] === 'conductor' || $_SESSION['rol'] === 'afiliado'): ?>
                    <!-- ══════════════════════════════════════ 
                         PREMIUM AFILIADO DASHBOARD 
                         ══════════════════════════════════════ -->
                    <!-- Unified Banner with Image and KPIs -->
                    <div class="anim-welcome banner-container">
                        
                        <!-- Left Image Column (hidden on mobile) -->
                        <div class="banner-img-col" style="width: 300px; background: transparent; display: flex; align-items: flex-end; justify-content: center; position: relative; flex-shrink: 0; padding-bottom: 15px;">
                            <img src="<?= BASE_URL ?>assets/premios/panelgas.png" style="width: 85%; height: auto; object-fit: contain; object-position: bottom; transform-origin: bottom center; margin-bottom: -15px;">
                        </div>

                        <!-- Right Content Column -->
                        <div class="banner-content-col" style="flex: 1; padding: 2.5rem 2.5rem 2.5rem 0; display: flex; flex-direction: column; justify-content: center; gap: 2rem;">
                            
                            <!-- Header Text -->
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="anim-welcome" style="animation-delay: 0.1s;">
                                    <h2 class="b-title">Bienvenido, <?= explode(' ', $_SESSION['nombre_usuario'])[0] ?></h2>
                                    <p class="b-subtitle">Resumen Operativo <span class="b-dot">•</span> <span class="b-brand">PREMIASURGAS</span></p>
                                </div>
                            </div>

                            <!-- KPI Cards Row -->
                            <div class="conductor-kpi-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                                
                                <!-- Puntos Hoy -->
                                <div class="anim-card banner-card-orange" style="animation-delay: 0.2s; border-radius: 16px; padding: 1.5rem; color: white; display: flex; flex-direction: column; justify-content: space-between; min-height: 130px;">
                                    <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; opacity: 0.9;">Puntos Entregados Hoy</div>
                                    <div class="dash-card-number-conductor" data-value="<?= $metricas_adicionales['puntos_hoy'] ?>" style="font-size: 3.2rem; font-weight: 900; line-height: 1; margin-bottom: 0.5rem;">0</div>
                                    <div style="font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.4rem;">
                                        <i class='bx bx-trending-up'></i> Buen ritmo hoy
                                    </div>
                                </div>

                                <!-- Total Historico -->
                                <div class="anim-card banner-card-wine" style="animation-delay: 0.3s; border-radius: 16px; padding: 1.5rem; color: white; display: flex; flex-direction: column; justify-content: space-between; min-height: 130px;">
                                    <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; opacity: 0.9;">Total Histórico</div>
                                    <div class="dash-card-number-conductor" data-value="<?= $metricas_adicionales['total_historico'] ?>" style="font-size: 3.2rem; font-weight: 900; line-height: 1; margin-bottom: 0.5rem;">0</div>
                                    <div style="font-size: 0.8rem; font-weight: 600; opacity: 0.8;">Total de puntos acumulados</div>
                                </div>

                                <!-- Clientes Atendidos -->
                                <div class="anim-card banner-card-wine" style="animation-delay: 0.4s; border-radius: 16px; padding: 1.5rem; color: white; display: flex; flex-direction: column; justify-content: space-between; min-height: 130px;">
                                    <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; opacity: 0.9;">Clientes Atendidos</div>
                                    <div class="dash-card-number-conductor" data-value="<?= $metricas_adicionales['total_clientes_propios'] ?>" style="font-size: 3.2rem; font-weight: 900; line-height: 1; margin-bottom: 0.5rem;">0</div>
                                    <div style="font-size: 0.8rem; font-weight: 600; opacity: 0.8;">Base de datos personal</div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Main Grid -->
                    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem; align-items: start;">
                        
                        <!-- LEFT COLUMN: Charts & Activity -->
                        <div style="display: flex; flex-direction: column; gap: 2rem;">
                            


                            <!-- Recent Transaction List -->
                            <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: none; overflow: hidden;">
                                <div style="background: #f4f6f8; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #fff5f5; border: 1px solid #fee2e2; display: flex; align-items: center; justify-content: center; color: #800000; font-size: 1.5rem; flex-shrink: 0;">
                                        <i class='bx bx-history'></i>
                                    </div>
                                    <div>
                                        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a; line-height: 1.2;">Última Actividad</h3>
                                        <p style="margin: 0.15rem 0 0; font-size: 0.8rem; color: #64748b; font-weight: 500;">Historial reciente de puntos entregados</p>
                                    </div>
                                </div>
                                
                                <div style="display: flex; flex-direction: column; padding: 0.5rem 1.5rem 1.5rem;">
                                    <?php if (!empty($metricas_adicionales['ultimas_ventas'])): ?>
                                        <?php foreach ($metricas_adicionales['ultimas_ventas'] as $i => $v): 
                                            $delay = 0.5 + ($i * 0.1);    
                                            $initials = strtoupper(substr($v['cliente_nombre'], 0, 1));
                                            
                                            // Spanish Month Formatting
                                            $meses = ["January"=>"Enero", "February"=>"Febrero", "March"=>"Marzo", "April"=>"Abril", "May"=>"Mayo", "June"=>"Junio", "July"=>"Julio", "August"=>"Agosto", "September"=>"Septiembre", "October"=>"Octubre", "November"=>"Noviembre", "December"=>"Diciembre"];
                                            $fecha_esp = strtr(date('d F, H:i', strtotime($v['fecha'])), $meses);
                                        ?>
                                            <div class="activity-row" style="animation-delay: <?= $delay ?>s;">
                                                <div style="display: flex; align-items: center; gap: 1rem;">
                                                    <div class="activity-avatar">
                                                        <?= $initials ?>
                                                    </div>
                                                    <div>
                                                        <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 2px;"><?= htmlspecialchars($v['cliente_nombre']) ?></div>
                                                        <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500; display: flex; align-items: center; gap: 4px;">
                                                            <?= $fecha_esp ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                                    <div style="font-weight: 900; color: <?= isset($v['estado']) && $v['estado'] === 'pendiente' ? '#f59e0b' : (isset($v['estado']) && $v['estado'] === 'rechazado' ? '#ef4444' : '#1e293b') ?>; font-size: 1.1rem; letter-spacing: -0.5px;">+ <?= $v['puntos'] ?> <span style="font-weight: 700; color: #94a3b8; font-size: 0.8rem;">pts</span></div>
                                                    <?php if (isset($v['estado'])): ?>
                                                        <?php if ($v['estado'] === 'pendiente'): ?>
                                                            <div style="font-size: 0.65rem; color: #d97706; background: #fef3c7; padding: 2px 8px; border-radius: 4px; font-weight: 800; text-transform: uppercase;">Pendiente</div>
                                                        <?php elseif ($v['estado'] === 'rechazado'): ?>
                                                            <div style="font-size: 0.65rem; color: #dc2626; background: #fee2e2; padding: 2px 8px; border-radius: 4px; font-weight: 800; text-transform: uppercase;">Rechazado</div>
                                                        <?php else: ?>
                                                            <div style="font-size: 0.65rem; color: #16a34a; background: #dcfce7; padding: 2px 8px; border-radius: 4px; font-weight: 800; text-transform: uppercase;">Aprobado</div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div style="text-align: center; padding: 2rem; color: #94a3b8; font-size: 0.85rem;">
                                            <i class='bx bx-ghost' style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                            No has realizado transacciones hoy
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN REMOVED -->
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal Detalle Canje -->
        <transition name="modal">
            <div class="modal-mask" v-cloak v-if="showModal" @click="showModal = false">
                <div class="modal-wrapper" @click.stop>
                    <div class="modal-container">
                        <div class="modal-header-notif">
                            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem;">
                                <i class='bx bx-receipt'></i>
                            </div>
                            <h3 style="margin: 0; font-weight: 800;">Detalle del Canje</h3>
                        </div>
                        <div class="modal-body-notif">
                            <div style="margin-bottom: 1.5rem; border-bottom: 1px dashed #eee; padding-bottom: 1rem;">
                                <small style="color: #999; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Cliente</small>
                                <div style="font-size: 1.2rem; font-weight: 800; color: #333; margin-top: 0.2rem;">{{ detail.cliente_nombre }}</div>
                                <div style="color: #666; font-size: 0.9rem;"><i class='bx bx-phone'></i> {{ detail.cliente_celular }}</div>
                            </div>

                            <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center;">
                                <img :src="'<?= BASE_URL ?>assets/premios/' + detail.premio_imagen" style="width: 60px; height: 60px; object-fit: contain; background: #f8fafc; border-radius: 12px; padding: 0.5rem;">
                                <div>
                                    <small style="color: #999; text-transform: uppercase; font-weight: 700; font-size: 0.7rem;">Premio Solicitado</small>
                                    <div style="font-weight: 700; color: #333;">{{ detail.premio_nombre }}</div>
                                </div>
                            </div>

                            <div style="background: #f8fafc; border-radius: 1.5rem; padding: 1.2rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="color: #666;">Puntos Usados:</span>
                                    <b style="color: var(--primary);">{{ detail.puntos_usados }} pts</b>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #666;">Monto Pagado:</span>
                                    <b :style="{ color: detail.monto > 0 ? '#ef4444' : '#22c55e' }">
                                        {{ detail.monto > 0 ? 'S/ ' + detail.monto : 'GRATIS (Full)' }}
                                    </b>
                                </div>
                            </div>

                            <div style="margin-top: 2rem;">
                                <button class="btn btn-primary w-100" style="padding: 1rem; border-radius: 1rem; background: var(--p-wine, #800000); color: white; border: none; font-weight: 700; width: 100%; cursor: pointer;" @click="showModal = false">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script> var BASE_URL = '<?= BASE_URL ?>'; </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
    <script src="<?= BASE_URL ?>views/panel.js"></script>

    <?php if ($_SESSION['rol'] === 'admin'): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                let historyChartInstance = null;

                // 1. Gráfico Top Premios (Full Width, Colorful)
                const topPremios = <?php echo json_encode($metricas_adicionales['top_premios'] ?? []); ?>;
                const ctxBar = document.getElementById('premiosBarChart');
                if (ctxBar && topPremios.length > 0) {
                    new Chart(ctxBar.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: topPremios.map(p => p.nombre),
                            datasets: [{
                                data: topPremios.map(p => p.total),
                                backgroundColor: '#7B1A1A',
                                borderRadius: 6,
                                barThickness: 22,
                                hoverBackgroundColor: '#9B2828'
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#111827',
                                    padding: 10,
                                    titleFont: { size: 12, weight: '600' },
                                    bodyFont: { size: 11 },
                                    callbacks: {
                                        label: ctx => ` ${ctx.parsed.x} canjes`
                                    }
                                }
                            },
                            layout: {
                                padding: { left: 5, right: 25, top: 0, bottom: 0 }
                            },
                            scales: {
                                x: { 
                                    beginAtZero: true, 
                                    grid: { color: '#F3F4F6', drawBorder: false }, 
                                    ticks: { font: { size: 10, weight: '500' }, color: '#9CA3AF', stepSize: 1 },
                                    suggestedMax: function(context) { return context.chart.data.datasets[0].data.reduce((a, b) => Math.max(a, b), 0) + 1; }
                                },
                                y: { 
                                    grid: { display: false }, 
                                    ticks: { 
                                        font: { size: window.innerWidth < 600 ? 9 : 11, weight: '500' }, 
                                        color: '#374151',
                                        callback: function(value) {
                                            const label = this.getLabelForValue(value);
                                            if (window.innerWidth < 600 && label.length > 12) {
                                                return label.substr(0, 10) + '..';
                                            }
                                            return label;
                                        }
                                    } 
                                }
                            }
                        }
                    });
                }

                // 2. Gráfico Ranking Conductores (Interactivo - Barras Horizontales)
                const rankingCond = <?php echo json_encode($metricas_adicionales['ranking_conductores'] ?? []); ?>;
                const ctxCond = document.getElementById('conductoresBarChart');
                if (ctxCond && rankingCond.length > 0) {
                    const condChart = new Chart(ctxCond.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: rankingCond.map(u => u.nombre),
                            datasets: [{
                                data: rankingCond.map(u => u.total_puntos),
                                backgroundColor: 'rgba(245, 158, 11, 0.15)',
                                borderColor: '#f59e0b',
                                borderWidth: 2,
                                borderRadius: 0,
                                hoverBackgroundColor: '#f59e0b'
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            onClick: (evt, elements) => {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const conductor = rankingCond[index];
                                    loadConductorHistory(conductor.id, conductor.nombre);
                                }
                            },
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f1f5f9', drawBorder: false }, ticks: { font: { size: 9, weight: '700' }, color: '#94a3b8' } },
                                y: { grid: { display: false }, ticks: { font: { size: 10, weight: '700' }, color: '#64748b' } }
                            }
                        }
                    });
                }

                // 3. Función Drill-down: Cargar Historial
                async function loadConductorHistory(id, name) {
                    document.getElementById('historyPlaceholder').style.display = 'none';
                    document.getElementById('historyContainer').style.display = 'block';
                    document.getElementById('historyTitle').innerHTML = `<i class='bx bx-line-chart' style="color: #4f46e5;"></i> Historial: ${name}`;
                    document.getElementById('historySubtitle').innerText = 'Puntos entregados en los últimos 15 días';

                    try {
                        const resp = await fetch(`${BASE_URL}panel/conductor-history?id=${id}`);
                        const json = await resp.json();
                        
                        if (json.success) {
                            renderHistoryChart(json.data);
                        }
                    } catch (e) { console.error(e); }
                }

                function renderHistoryChart(data) {
                    const ctxHist = document.getElementById('historyChart').getContext('2d');
                    if (historyChartInstance) historyChartInstance.destroy();

                    const labels = data.map(d => {
                        const date = new Date(d.fecha + 'T00:00:00');
                        return date.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
                    });
                    const values = data.map(d => d.total);

                    historyChartInstance = new Chart(ctxHist, {
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    type: 'line',
                                    label: 'Tendencia',
                                    data: values,
                                    borderColor: '#4f46e5',
                                    borderWidth: 2,
                                    pointRadius: 3,
                                    tension: 0.4
                                },
                                {
                                    type: 'bar',
                                    label: 'Puntos',
                                    data: values,
                                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                    borderRadius: 0
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, ticks: { font: { size: 9 } } },
                                x: { ticks: { font: { size: 9 } } }
                            }
                        }
                    });
                }
            });

            // 3. Donut — Tipo de Canjes
            const canjesFull = <?= $metricas_adicionales['canjes_full'] ?? 0 ?>;
            const canjesMix  = <?= $metricas_adicionales['canjes_mix']  ?? 0 ?>;
            const ctxDonut   = document.getElementById('donutCanjesChart');
            if (ctxDonut && (canjesFull + canjesMix) > 0) {
                new Chart(ctxDonut.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Full (solo puntos)', 'Mix (puntos + S/)'],
                        datasets: [{
                            data: [canjesFull, canjesMix],
                            backgroundColor: ['#7B1A1A', '#C4722A'],
                            borderWidth: 0,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#111827',
                                padding: 10,
                                callbacks: {
                                    label: ctx => ` ${ctx.parsed} canjes (${((ctx.parsed / (canjesFull + canjesMix)) * 100).toFixed(1)}%)`
                                }
                            }
                        }
                    }
                });
            }

            // 4. Area — Puntos entregados por día (últimos 14 días)
            const puntosDia = <?php echo json_encode($metricas_adicionales['puntos_por_dia'] ?? []); ?>;
            const ctxArea   = document.getElementById('puntosAreaChart');
            if (ctxArea) {
                const areaLabels = puntosDia.map(d => {
                    const date = new Date(d.fecha + 'T00:00:00');
                    return date.toLocaleDateString('es-PE', { day: 'numeric', month: 'short' });
                });
                const areaValues = puntosDia.map(d => d.total);
                new Chart(ctxArea.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: areaLabels.length ? areaLabels : ['Sin datos'],
                        datasets: [{
                            label: 'Puntos',
                            data: areaValues.length ? areaValues : [0],
                            borderColor: '#7B1A1A',
                            borderWidth: 2,
                            pointBackgroundColor: '#7B1A1A',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4,
                            fill: true,
                            backgroundColor: (ctx) => {
                                const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                                gradient.addColorStop(0, 'rgba(123,26,26,0.15)');
                                gradient.addColorStop(1, 'rgba(123,26,26,0.01)');
                                return gradient;
                            }
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#111827',
                                padding: 10,
                                callbacks: { label: ctx => ` ${ctx.parsed.y} puntos` }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9CA3AF' } },
                            y: { beginAtZero: true, grid: { color: '#F3F4F6', drawBorder: false }, ticks: { font: { size: 10 }, color: '#9CA3AF' } }
                        }
                    }
                });
            }
        </script>
    <?php endif; ?>

    <?php if ($_SESSION['rol'] === 'conductor' || $_SESSION['rol'] === 'afiliado'): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Counting Animation for Cards
                setTimeout(() => {
                    const counters = document.querySelectorAll('.dash-card-number-conductor');
                    counters.forEach(counter => {
                        const target = +counter.getAttribute('data-value');
                        const duration = 1200; // ms
                        const increment = target / (duration / 16);
                        let current = 0;
                        const updateCounter = () => {
                            current += increment;
                            if (current < target) {
                                counter.innerText = Math.ceil(current);
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.innerText = target;
                            }
                        };
                        updateCounter();
                    });
                }, 300); // slight delay to sync with entrance animation


            });
        </script>
    <?php endif; ?>

</body>

</html>