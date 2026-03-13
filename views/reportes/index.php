<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes — PremiaSurgas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card-stat { border: none; border-radius: 15px; transition: transform 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
        .bg-gradient-primary { background: linear-gradient(45deg, var(--primary), var(--primary-dk)); color: white; }
        .bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c); color: white; }
        .bg-gradient-info { background: linear-gradient(45deg, #36b9cc, #258391); color: white; }
        .bg-gradient-warning { background: linear-gradient(45deg, #f6c23e, #dda20a); color: white; }
        .topbar { margin-bottom: 0; }
        table { font-size: 0.9rem; }
    </style>
</head>
<body class="bg-light">

    <div class="topbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <a href="<?= BASE_URL ?>panel" class="text-white text-decoration-none fs-4 me-3">←</a>
                <span class="topbar-logo text-white">📊 Dashboard de Reportes</span>
            </div>
            <div class="text-white small">
                Actualizado: <?= date('d/m/Y H:i') ?>
            </div>
        </div>
    </div>

    <div class="container mt-4 pb-5">
        
        <!-- Tarjetas de Resumen -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card card-stat bg-gradient-primary shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-white-50">Ventas Totales</h6>
                        <h3>S/ <?= number_format($data['resumen']['total_ventas'], 2) ?></h3>
                        <div class="small">Ingresos acumulados</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-gradient-success shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-white-50">Puntos Emitidos</h6>
                        <h3><?= number_format($data['resumen']['puntos_emitidos']) ?></h3>
                        <div class="small">Fidelización activa</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-gradient-info shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-white-50">Puntos Canjeados</h6>
                        <h3><?= number_format($data['resumen']['puntos_canjeados']) ?></h3>
                        <div class="small">Premios entregados</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-gradient-warning shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-white-50">Clientes Activos</h6>
                        <h3><?= $data['resumen']['total_clientes'] ?></h3>
                        <div class="small">Directorio actual</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Gráfico de Ventas -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-0">Ventas y Puntos (Últimos 15 días)</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <canvas id="ventasChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Premios Populares -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-0">Top Premios</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach($data['premiosPopulares'] as $p): ?>
                            <div class="list-group-item border-0 d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-0"><?= $p['nombre'] ?></h6>
                                    <small class="text-muted"><?= $p['veces_canjeado'] ?> canjes</small>
                                </div>
                                <span class="badge bg-light text-primary rounded-pill"><?= $p['puntos_totales'] ?> pts</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ventas por Conductor -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-0">Rendimiento por Conductor</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Ventas</th>
                                        <th>Monto</th>
                                        <th>Puntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['ventasConductor'] as $v): ?>
                                    <tr>
                                        <td><?= $v['nombre'] ?></td>
                                        <td><?= $v['cantidad_ventas'] ?></td>
                                        <td>S/ <?= number_format($v['total_monto'], 2) ?></td>
                                        <td><?= number_format($v['total_puntos'] ?? 0) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Canjes Recientes -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-0">Últimos Canjes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Premio</th>
                                        <th>Puntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['canjesRecientes'] as $c): ?>
                                    <tr>
                                        <td><?= date('d/m/H:i', strtotime($c['fecha'])) ?></td>
                                        <td><?= $c['cliente'] ?></td>
                                        <td><?= $c['premio'] ?></td>
                                        <td><span class="text-danger">-<?= $c['puntos_usados'] ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('ventasChart').getContext('2d');
    const chartData = <?= json_encode($data['ventasGrafico']) ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.dia.split('-').slice(1).reverse().join('/')),
            datasets: [{
                label: 'Monto Ventas (S/)',
                data: chartData.map(d => d.total),
                borderColor: '#821515',
                backgroundColor: 'rgba(130, 21, 21, 0.1)',
                fill: true,
                tension: 0.3
            }, {
                label: 'Puntos Emitidos',
                data: chartData.map(d => d.puntos),
                borderColor: '#258391',
                borderDash: [5, 5],
                fill: false,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    </script>
</body>
</html>
