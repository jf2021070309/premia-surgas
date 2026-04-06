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

        .notif-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0f0f0;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notif-card:hover {
            transform: translateX(5px);
            border-color: var(--primary);
        }

        .notif-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: #fffcf0;
            color: #f39c12;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .notif-icon.full {
            background: #f0fdf4;
            color: #22c55e;
        }

        .notif-icon.mix {
            background: #fdf2f2;
            color: #ef4444;
        }

        .notif-content {
            flex: 1;
        }

        .notif-title {
            font-weight: 700;
            color: #333;
            font-size: 0.95rem;
            margin-bottom: 0.1rem;
        }

        .notif-sub {
            color: #888;
            font-size: 0.8rem;
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

        .middle-row-grid {
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 1000px) {
            .middle-row-grid {
                grid-template-columns: 1fr;
            }
        }

        .dash-card {
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            position: relative;
            overflow: hidden;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .dash-card.card-blue {
            background: #17a2b8;
        }

        .dash-card.card-green {
            background: #28a745;
        }

        .dash-card.card-yellow {
            background: #ffc107;
            color: #333;
        }

        .dash-card.card-red {
            background: #dc3545;
        }

        .dash-card-body {
            padding: 15px 20px;
            flex: 1;
            position: relative;
            z-index: 2;
            min-height: 100px;
        }

        .dash-card-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2px;
            line-height: 1;
            font-family: 'Inter', sans-serif;
        }

        .dash-card-text {
            font-size: 0.95rem;
            margin-bottom: 0;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        .dash-card-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.15);
            z-index: 1;
            pointer-events: none;
        }

        .dash-card-footer {
            display: block;
            padding: 6px 15px;
            text-align: center;
            background: rgba(0, 0, 0, 0.1);
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            position: relative;
            z-index: 2;
            transition: background 0.3s;
            margin-top: auto;
        }

        .dash-card.card-yellow .dash-card-footer {
            color: #333;
        }

        .dash-card-footer:hover {
            background: rgba(0, 0, 0, 0.15);
            color: inherit;
        }

        .dash-card-footer i {
            margin-left: 5px;
            font-size: 1rem;
            vertical-align: middle;
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

            <div class="container" style="width: 100%; max-width: 100%; padding: 1.5rem 2rem;">

                <?php if ($_SESSION['rol'] === 'admin'): ?>

                    <!-- TOP ROW: KPI Metrics -->
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">

                        <!-- Blue Card (Usuarios) -->
                        <div class="dash-card card-blue">
                            <div class="dash-card-body">
                                <div class="dash-card-number"><?= $totales['clientes'] ?></div>
                                <div class="dash-card-text">Usuarios Registrados</div>
                            </div>
                            <i class='bx bx-user-plus dash-card-icon'></i>
                            <a href="<?= BASE_URL ?>clientes/lista" class="dash-card-footer">Mas información <i
                                    class='bx bx-right-arrow-circle'></i></a>
                        </div>

                        <!-- Green Card (Canjes) -->
                        <div class="dash-card card-green">
                            <div class="dash-card-body">
                                <div class="dash-card-number"><?= $metricas_adicionales['canjes_hoy'] ?></div>
                                <div class="dash-card-text">Canjes Hoy</div>
                            </div>
                            <i class='bx bx-gift dash-card-icon'></i>
                            <a href="<?= BASE_URL ?>canjes-admin" class="dash-card-footer">Mas información <i
                                    class='bx bx-right-arrow-circle'></i></a>
                        </div>

                        <!-- Yellow Card (Puntos) -->
                        <div class="dash-card card-yellow">
                            <div class="dash-card-body">
                                <div class="dash-card-number" style="display: flex; align-items: baseline; gap: 4px;">
                                    <span
                                        style="font-size: 0.5em; font-weight: 800; position: relative; top: -5px;">Pts/</span>
                                    <?= $metricas_adicionales['puntos_hoy'] ?>
                                </div>
                                <div class="dash-card-text">Puntos Dados Hoy</div>
                            </div>
                            <i class='bx bx-star dash-card-icon'></i>
                            <a href="<?= BASE_URL ?>scan" class="dash-card-footer">Mas información <i
                                    class='bx bx-right-arrow-circle'></i></a>
                        </div>

                        <!-- Red Card (Recargas) -->
                        <div class="dash-card card-red">
                            <div class="dash-card-body">
                                <div class="dash-card-number"><?= count($notificaciones_recargas) ?></div>
                                <div class="dash-card-text">Recargas Pendientes</div>
                            </div>
                            <i class='bx bx-wallet dash-card-icon'></i>
                            <a href="<?= BASE_URL ?>recargas-admin" class="dash-card-footer">Mas información <i
                                    class='bx bx-right-arrow-circle'></i></a>
                        </div>

                    </div>

                    <!-- MIDDLE ROW: Chart & Ranking -->
                    <div class="middle-row-grid">

                        <!-- CHART -->
                        <div style="display: flex; flex-direction: column;">
                            <div style="margin-bottom: 1.25rem; margin-top: 0.5rem;">
                                <h3
                                    style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class='bx bx-line-chart'
                                        style="color: var(--p-wine, #800000); font-size: 1.25rem;"></i> Actividad (8 Días)
                                </h3>
                                <p
                                    style="font-size: 0.75rem; color: #64748b; margin: 0; margin-top: 0.2rem; font-weight: 500;">
                                    Puntos y canjes de la última semana</p>
                            </div>
                            <div
                                style="background: white; border-radius: 1.25rem; padding: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.03); flex: 1;">
                                <div style="position: relative; height: 300px; width: 100%;">
                                    <canvas id="actividadChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- RANKING -->
                        <div style="display: flex; flex-direction: column;">
                            <div style="margin-bottom: 1.25rem; margin-top: 0.5rem;">
                                <h3
                                    style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class='bx bx-trophy' style="color: var(--p-wine, #800000); font-size: 1.25rem;"></i>
                                    Ranking Canjeadores</h3>
                                <p
                                    style="font-size: 0.75rem; color: #64748b; margin: 0; margin-top: 0.2rem; font-weight: 500;">
                                    Top usuarios con mayor actividad de canje</p>
                            </div>

                            <div
                                style="background: white; border-radius: 1.25rem; padding: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; flex-direction: column; flex: 1;">
                                <div
                                    style="display: flex; flex-direction: column; gap: 0.8rem; overflow-y: auto; padding-right: 0.5rem;">
                                    <?php if (!empty($metricas_adicionales['ranking'])): ?>
                                        <?php foreach ($metricas_adicionales['ranking'] as $index => $rank): ?>
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1rem; border-radius: 1rem; background: #fff; border: 1px solid <?php echo $index === 0 ? '#fef3c7' : ($index === 1 ? '#e2e8f0' : ($index === 2 ? '#ffedd5' : '#f8fafc')); ?>; box-shadow: <?php echo $index === 0 ? '0 4px 15px rgba(245,158,11,0.1)' : ($index === 1 ? '0 4px 15px rgba(148,163,184,0.1)' : ($index === 2 ? '0 4px 15px rgba(180,83,9,0.08)' : '0 2px 5px rgba(0,0,0,0.02)')); ?>; transition: all 0.3s ease; cursor: default;">
                                                <div style="display: flex; align-items: center; gap: 0.8rem;">

                                                    <!-- Medal / Rank Number -->
                                                    <?php if ($index === 0): ?>
                                                        <div style="font-size: 1.6rem; color: #fbbf24;"><i class='bx bxs-medal'></i>
                                                        </div>
                                                    <?php elseif ($index === 1): ?>
                                                        <div style="font-size: 1.6rem; color: #94a3b8;"><i class='bx bxs-medal'></i>
                                                        </div>
                                                    <?php elseif ($index === 2): ?>
                                                        <div style="font-size: 1.6rem; color: #b45309;"><i class='bx bxs-medal'></i>
                                                        </div>
                                                    <?php else: ?>
                                                        <div
                                                            style="font-size: 1rem; font-weight: 800; color: #cbd5e1; width: 1.6rem; text-align: center;">
                                                            <?= $index + 1 ?></div>
                                                    <?php endif; ?>

                                                    <!-- User Name -->
                                                    <div style="font-weight: 800; color: #1e293b; font-size: 0.9rem;">
                                                        <?= htmlspecialchars($rank['nombre']) ?>
                                                    </div>
                                                </div>

                                                <!-- Score / Canjes -->
                                                <div style="font-weight: 900; color: var(--p-wine, #800000); font-size: 1.1rem;">
                                                    <?= $rank['total_canjes'] ?> <span
                                                        style="font-size: 0.8rem; font-weight: 700; opacity: 0.7;">pts</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div
                                            style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2.5rem 0;">
                                            <div style="font-size: 3rem; color: #e2e8f0; margin-bottom: 0.5rem;"><i
                                                    class='bx bx-ghost'></i></div>
                                            <div style="font-size: 0.85rem; color: #94a3b8; font-weight: 600;">Ningún registro
                                                top aún</div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['rol'] === 'admin' && !empty($notificaciones_recargas)): ?>
                    <div style="margin-top: 1.5rem; margin-bottom: 1.25rem;">
                        <h3
                            style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                            <i class='bx bx-wallet' style="color: var(--p-wine, #800000); font-size: 1.25rem;"></i> Nuevas
                            Recargas de Puntos</h3>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0; margin-top: 0.2rem; font-weight: 500;">
                            Clientes que han enviado comprobante de pago</p>
                    </div>
                    <div class="notif-list">
                        <?php foreach ($notificaciones_recargas as $r): ?>
                            <div class="notif-card" onclick="location.href='<?= BASE_URL ?>recargas-admin'">
                                <div class="notif-icon" style="background: #eef2ff; color: #4f46e5;">
                                    <i class='bx bx-wallet'></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title"><?= htmlspecialchars($r['cliente_nombre']) ?></div>
                                    <div class="notif-sub">Solicita recarga de: <b><?= $r['puntos'] ?> puntos</b></div>
                                </div>
                                <div style="text-align: right;">
                                    <div class="badge badge-warning"
                                        style="font-size: 0.65rem; padding: 0.3rem 0.6rem; background: #fffbeb; color: #d97706; border: 1px solid #fde68a;">
                                        Pendiente Revisar
                                    </div>
                                    <div style="font-size: 0.7rem; color: #bbb; margin-top: 0.3rem;">
                                        <?= date('H:i', strtotime($r['fecha'])) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['rol'] === 'admin' && !empty($notificaciones)): ?>
                    <div
                        style="margin-top: <?= !empty($notificaciones_recargas) ? '2rem' : '1.5rem' ?>; margin-bottom: 1.25rem;">
                        <h3
                            style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                            <i class='bx bx-gift' style="color: var(--p-wine, #800000); font-size: 1.25rem;"></i>
                            Notificaciones de Canjes</h3>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0; margin-top: 0.2rem; font-weight: 500;">
                            Últimos canjes solicitados por clientes</p>
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
                                    <div class="notif-sub">Canjeó: <b><?= htmlspecialchars($n['premio_nombre']) ?></b></div>
                                </div>
                                <div style="text-align: right;">
                                    <div class="badge <?= $esMix ? 'badge-danger' : 'badge-success' ?>"
                                        style="font-size: 0.65rem; padding: 0.3rem 0.6rem;">
                                        <?= $esMix ? 'Puntos + S/' . $n['monto'] : 'Canje Full' ?>
                                    </div>
                                    <div style="font-size: 0.7rem; color: #bbb; margin-top: 0.3rem;">
                                        <?= date('H:i', strtotime($n['fecha'])) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                            <div
                                style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem;">
                                <i class='bx bx-receipt'></i>
                            </div>
                            <h3 style="margin: 0; font-weight: 800;">Detalle del Canje</h3>
                        </div>
                        <div class="modal-body-notif">
                            <div style="margin-bottom: 1.5rem; border-bottom: 1px dashed #eee; padding-bottom: 1rem;">
                                <small
                                    style="color: #999; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Cliente</small>
                                <div style="font-size: 1.2rem; font-weight: 800; color: #333; margin-top: 0.2rem;">{{
                                    detail.cliente_nombre }}</div>
                                <div style="color: #666; font-size: 0.9rem;"><i class='bx bx-phone'></i> {{
                                    detail.cliente_celular }}</div>
                            </div>

                            <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center;">
                                <img :src="'<?= BASE_URL ?>assets/premios/' + detail.premio_imagen"
                                    style="width: 60px; height: 60px; object-fit: contain; background: #f8fafc; border-radius: 12px; padding: 0.5rem;">
                                <div>
                                    <small
                                        style="color: #999; text-transform: uppercase; font-weight: 700; font-size: 0.7rem;">Premio
                                        Solicitado</small>
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
                                <button class="btn btn-primary w-100"
                                    style="padding: 1rem; border-radius: 1rem; background: var(--p-wine, #800000); color: white; border: none; font-weight: 700; width: 100%; cursor: pointer;"
                                    @click="showModal = false">Cerrar Notificación</button>
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
                const chartDataPuntos = <?php echo json_encode($metricas_adicionales['chart_puntos'] ?? []); ?>;
                const chartDataCanjes = <?php echo json_encode($metricas_adicionales['chart_canjes'] ?? []); ?>;

                const allDates = [...new Set([...chartDataPuntos.map(d => d.fecha), ...chartDataCanjes.map(d => d.fecha)])].sort();

                const puntosMap = Object.fromEntries(chartDataPuntos.map(d => [d.fecha, d.total]));
                const canjesMap = Object.fromEntries(chartDataCanjes.map(d => [d.fecha, d.total]));

                const dataPuntos = allDates.map(fecha => puntosMap[fecha] || 0);
                const dataCanjes = allDates.map(fecha => canjesMap[fecha] || 0);

                const formatFecha = (d) => {
                    const date = new Date(d + 'T00:00:00');
                    return date.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' });
                };

                const ctx = document.getElementById('actividadChart');
                if (ctx && allDates.length > 0) {
                    new Chart(ctx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: allDates.map(formatFecha),
                            datasets: [
                                {
                                    type: 'line',
                                    label: 'Canjes',
                                    data: dataCanjes,
                                    borderColor: '#4f46e5',
                                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                    borderWidth: 3,
                                    tension: 0.4,
                                    yAxisID: 'y1'
                                },
                                {
                                    type: 'bar',
                                    label: 'Puntos Entregados',
                                    data: dataPuntos,
                                    backgroundColor: 'rgba(128, 0, 0, 0.8)',
                                    borderRadius: 6,
                                    yAxisID: 'y'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: {
                                legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, font: { family: 'Inter' } } },
                                tooltip: { backgroundColor: 'rgba(0,0,0,0.8)', padding: 12, cornerRadius: 8 }
                            },
                            scales: {
                                y: {
                                    type: 'linear', display: true, position: 'left',
                                    grid: { borderDash: [4, 4], color: '#f1f5f9' }
                                },
                                y1: {
                                    type: 'linear', display: true, position: 'right',
                                    grid: { drawOnChartArea: false }
                                },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                } else if (ctx) {
                    ctx.style.display = 'none';
                    ctx.parentNode.innerHTML = '<div style="display:flex; height:100%; align-items:center; justify-content:center; color:#94a3b8; font-size:0.9rem;">Sin datos en los últimos 7 días</div>';
                }
            });
        </script>
    <?php endif; ?>

</body>

</html>