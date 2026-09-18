<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes — PremiaSurgas</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Ajustes específicos para el layout de reportes dentro del admin-layout */
        .premium-container.reportes-container {
            padding: 1.5rem 2rem;
            display: flex; flex-direction: column; height: calc(100vh - 80px); gap: 1rem; overflow: hidden; padding-bottom: 1rem;
            box-sizing: border-box;
        }
        
        .topbar-compact { 
            background: #2d3436; color: white; border-radius: 1rem; padding: 0.6rem 1.2rem;
            display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;
        }

        .stat-bar { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; flex-shrink: 0; }
        .stat-card-mini { 
            background: white; border-radius: 1rem; padding: 0.8rem 1.2rem; border: none;
            display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
        .stat-icon-box { 
            width: 40px; height: 40px; border-radius: 0.8rem; display: flex; 
            align-items: center; justify-content: center; font-size: 1.2rem;
        }

        .dashboard-grid { display: grid; grid-template-columns: 1.3fr 1fr; gap: 1rem; flex-grow: 1; overflow: hidden; min-height: 0; }
        
        .rpt-card { 
            background: white; border-radius: 1.5rem; display: flex; flex-direction: column; overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }
        .rpt-header { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f4f8; display: flex; justify-content: space-between; align-items: center; background: white !important; text-align: left !important; box-shadow: none !important; margin-bottom: 0 !important; }
        .rpt-body { padding: 1rem; flex-grow: 1; overflow-y: auto; }
        .rpt-title { font-size: 1rem; font-weight: 700; margin: 0; color: #2d3436; text-transform: none !important; }

        .table-sm-custom { font-size: 0.82rem; margin: 0; }
        .table-sm-custom th { color: #888; font-weight: 600; text-transform: uppercase; font-size: 0.7rem; border: none; }
        .table-sm-custom td { vertical-align: middle; border-color: #f8f9fa; }

        .prize-row { display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #f8f9fa; }
        .prize-row:last-child { border: none; }
        
        @media (max-width: 900px) {
            .premium-container.reportes-container { height: auto; overflow: visible; }
            .dashboard-grid { grid-template-columns: 1fr; overflow: visible; }
            .stat-bar { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div id="app">
        <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

        <div class="admin-layout">
            <?php 
                $pageTitle = 'Reportes';
                $pageSubtitle = 'Reportes de Fidelización';
                include __DIR__ . '/../partials/header_admin.php'; 
            ?>
            
            <div class="premium-container reportes-container">
                <div class="topbar-compact shadow-sm">
                    <div>
                        <span class="fw-bold">Reportes de Fidelización</span>
                    </div>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <a href="<?= BASE_URL ?>reporte/diario-conductores" target="_blank" class="btn btn-sm btn-light fw-bold" style="border-radius: 8px;">
                            <i class='bx bx-printer'></i> Reporte Conductores
                        </a>
                        <div class="small opacity-75 d-none d-md-block">Actualizado: <?= date('d/m H:i') ?></div>
                    </div>
                </div>

                <!-- Mini Stats -->
                <div class="stat-bar">
                    <div class="stat-card-mini">
                <div class="stat-icon-box" style="background: #dcfce7; color: #166534;">🌟</div>
                <div>
                    <div class="small text-muted fw-bold" style="font-size: 0.7rem;">PUNTOS EMITIDOS</div>
                    <div class="h5 mb-0 fw-bold"><?= number_format($data['resumen']['puntos_emitidos']) ?></div>
                </div>
            </div>
            <div class="stat-card-mini">
                <div class="stat-icon-box" style="background: #e0f2fe; color: #0369a1;">🛍️</div>
                <div>
                    <div class="small text-muted fw-bold" style="font-size: 0.7rem;">CANJES REALIZADOS</div>
                    <div class="h5 mb-0 fw-bold"><?= number_format($data['resumen']['puntos_canjeados']) ?></div>
                </div>
            </div>
            <div class="stat-card-mini">
                <div class="stat-icon-box" style="background: #fef3c7; color: #92400e;">👥</div>
                <div>
                    <div class="small text-muted fw-bold" style="font-size: 0.7rem;">CLIENTES ACTIVOS</div>
                    <div class="h5 mb-0 fw-bold"><?= $data['resumen']['total_clientes'] ?></div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Columna Izquierda: Gráfico y Canjes -->
            <div style="display: flex; flex-direction: column; gap: 1rem; overflow: hidden;">
                <div class="rpt-card" style="flex: 1.2;">
                    <div class="rpt-header">
                        <h2 class="rpt-title">Movimiento de Puntos (15 días)</h2>
                    </div>
                    <div class="rpt-body d-flex align-items-center">
                        <canvas id="puntosChart" style="width: 100%; max-height: 100%;"></canvas>
                    </div>
                </div>

                <div class="rpt-card" style="flex: 1;">
                    <div class="rpt-header">
                        <h2 class="rpt-title">Últimos Canjes</h2>
                    </div>
                    <div class="rpt-body">
                        <table class="table table-sm-custom">
                            <thead>
                                <tr><th>Fecha</th><th>Cliente</th><th>Premio</th><th>Puntos</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['canjesRecientes'] as $c): ?>
                                <tr>
                                    <td><?= date('d/m', strtotime($c['fecha'])) ?></td>
                                    <td><b><?= $c['cliente'] ?></b></td>
                                    <td><?= $c['premio'] ?></td>
                                    <td class="text-danger fw-bold">-<?= $c['puntos_usados'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Conductores y Top Premios -->
            <div style="display: flex; flex-direction: column; gap: 1rem; overflow: hidden;">
                <div class="rpt-card" style="flex: 1;">
                    <div class="rpt-header">
                        <h2 class="rpt-title">Ranking Conductores</h2>
                    </div>
                    <div class="rpt-body">
                        <table class="table table-sm-custom">
                            <thead>
                                <tr><th>Nombre</th><th class="text-center">Ops</th><th class="text-end">Puntos</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['ventasConductor'] as $v): ?>
                                <tr>
                                    <td><b><?= $v['nombre'] ?></b></td>
                                    <td class="text-center"><?= $v['cantidad_ventas'] ?></td>
                                    <td class="text-end fw-bold text-success"><?= number_format($v['total_puntos'] ?? 0) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rpt-card" style="flex: 1;">
                    <div class="rpt-header">
                        <h2 class="rpt-title">Premios más Canjeados</h2>
                    </div>
                    <div class="rpt-body">
                        <?php foreach($data['premiosPopulares'] as $p): ?>
                        <div class="prize-row">
                            <div>
                                <div class="fw-bold" style="font-size: 0.85rem;"><?= $p['nombre'] ?></div>
                                <div class="small text-muted" style="font-size: 0.72rem;"><?= $p['veces_canjeado'] ?> entregados</div>
                            </div>
                            <span class="badge rounded-pill bg-light text-dark" style="font-size: 0.7rem;"><?= $p['puntos_totales'] ?> pts</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('puntosChart').getContext('2d');
    const chartVentas = <?= json_encode($data['ventasGrafico']) ?>;
    const chartCanjes = <?= json_encode($data['canjesGrafico']) ?>;
    
    // Unir fechas únicas de ambos conjuntos de datos
    const allLabels = [...new Set([...chartVentas.map(v => v.dia), ...chartCanjes.map(c => c.dia)])].sort();
    
    const dataVentas = allLabels.map(label => {
        const found = chartVentas.find(v => v.dia === label);
        return found ? found.puntos : 0;
    });

    const dataCanjes = allLabels.map(label => {
        const found = chartCanjes.find(c => c.dia === label);
        return found ? found.puntos : 0;
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: allLabels.map(l => l.split('-').slice(2).join('/')),
            datasets: [{
                label: 'Emitidos',
                data: dataVentas,
                backgroundColor: '#258391',
                borderRadius: 4
            }, {
                label: 'Canjeados',
                data: dataCanjes,
                backgroundColor: '#d63031',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    position: 'top',
                    labels: { boxWidth: 12, font: { size: 10 } }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: '#f0f0f0' },
                    ticks: { font: { size: 10 } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
    </script>
        </div>
    </div>
</div>
</body>
</html>
