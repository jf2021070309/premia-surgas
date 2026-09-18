<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario - <?= htmlspecialchars($fecha) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #800000;
            --text-main: #0f172a;
            --text-muted: #475569;
            --border: #e2e8f0;
        }
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background: #f8fafc;
            margin: 0;
            padding: 2rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }
        .header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 900;
            color: var(--primary);
        }
        .header p {
            margin: 0.5rem 0 0;
            color: var(--text-muted);
            font-weight: 600;
        }
        .header-meta {
            text-align: right;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        
        .conductor-section {
            margin-bottom: 3rem;
            page-break-inside: avoid;
        }
        .conductor-title {
            background: var(--primary);
            color: #fff;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .conductor-title span.total-pts {
            background: rgba(255,255,255,0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        th {
            background: #f1f5f9;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid var(--border);
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }
        tr:last-child td { border-bottom: none; }
        
        .img-preview {
            width: 120px;
            height: 120px;
            object-fit: contain;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: #f8fafc;
        }

        .action-bar {
            text-align: right;
            margin-bottom: 2rem;
        }
        .btn-print {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(128,0,0,0.2);
        }
        
        @media print {
            body { background: #fff; padding: 0; }
            .report-container { box-shadow: none; padding: 0; max-width: 100%; }
            .action-bar { display: none; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="<?= BASE_URL ?>reportes" style="text-decoration: none; color: var(--text-muted); font-weight: 600; margin-right: 1rem;">&larr; Volver al Panel</a>
        <button onclick="window.print()" class="btn-print">Imprimir / Guardar PDF</button>
    </div>

    <div class="report-container">
        <div class="header">
            <div>
                <h1>PremiaSurgas</h1>
                <p>Reporte Diario de Entregas (Puntos de Venta)</p>
            </div>
            <div class="header-meta">
                <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($fecha)) ?><br>
                <strong>Total Puntos Asignados:</strong> <?= number_format($totalPuntosDia) ?> pts<br>
                <strong>Generado el:</strong> <?= date('d/m/Y H:i:s') ?>
            </div>
        </div>

        <?php if (empty($agrupadoPorConductor)): ?>
            <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                <h2>No hay registros aprobados para esta fecha.</h2>
                <p>No se encontraron asignaciones de puntos de venta el día <?= date('d/m/Y', strtotime($fecha)) ?>.</p>
            </div>
        <?php else: ?>
            <?php foreach ($agrupadoPorConductor as $conductor => $ventas): ?>
                <?php
                    $subtotalPts = array_sum(array_column($ventas, 'puntos'));
                    $subtotalBalones = array_sum(array_column($ventas, 'balones_cantidad'));
                ?>
                <div class="conductor-section">
                    <div class="conductor-title">
                        Conductor: <?= htmlspecialchars($conductor) ?>
                        <span class="total-pts"><?= $subtotalBalones ?> Balones | +<?= number_format($subtotalPts) ?> pts</span>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 80px;">Hora</th>
                                <th>Cliente</th>
                                <th style="text-align: center; width: 100px;">Balones</th>
                                <th style="text-align: right; width: 100px;">Puntos</th>
                                <th style="text-align: center; width: 140px;">Evidencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas as $v): ?>
                            <tr>
                                <td><strong><?= date('H:i', strtotime($v['fecha'])) ?></strong></td>
                                <td>
                                    <strong><?= htmlspecialchars($v['cliente_nombre'] ?? '') ?></strong>
                                    <?php if(!empty($v['razon_social'])): ?>
                                        <br><span style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($v['razon_social']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center; font-weight: 700; color: #0284c7;">
                                    <?= $v['balones_cantidad'] ?>
                                </td>
                                <td style="text-align: right; font-weight: 900; color: var(--primary);">
                                    +<?= $v['puntos'] ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if (!empty($v['evidencia_foto'])): ?>
                                        <img src="<?= BASE_URL . $v['evidencia_foto'] ?>" alt="Foto" class="img-preview">
                                    <?php else: ?>
                                        <span style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">Sin Foto</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
</html>
