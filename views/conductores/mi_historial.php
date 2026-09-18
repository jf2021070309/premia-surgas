<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> — PremiaSurgas</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --wine-primary: #800000;
            --wine-dark: #600000;
            --slate-text: #1e293b;
            --gray-sub: #64748b;
            --border-light: #e2e8f0;
            --bg-neutral: #f8fafc;
        }

        .premium-container { padding: 1.5rem 2rem; width: 100%; max-width: 100%; margin: 0; box-sizing: border-box; }

        /* Summary Header */
        .history-summary {
            background: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .sum-title { font-size: 0.75rem; font-weight: 800; color: var(--gray-sub); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem; }
        .sum-val { font-size: 2.2rem; font-weight: 900; color: var(--wine-primary); letter-spacing: -1px; }

        /* Timeline Structure */
        .timeline-section { margin-bottom: 3rem; position: relative; }
        .date-divider {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .date-label {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--slate-text);
            padding: 0.4rem 0 0.4rem 1rem;
            background: transparent;
            border-left: 4px solid var(--wine-primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            white-space: nowrap;
        }
        .date-line { height: 1px; background: var(--border-light); width: 100%; }

        /* Transaction Item */
        .timeline-header {
            display: grid;
            grid-template-columns: 80px 1.4fr 1.4fr 140px;
            gap: 2rem;
            padding: 0 2rem 0.5rem 2rem;
            font-size: 0.68rem;
            font-weight: 800;
            color: var(--gray-sub);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 2rem;
            border-bottom: 2px solid var(--border-light);
            margin-bottom: 1.5rem;
        }
        .history-card {
            background: #fff;
            border: 1px solid var(--border-light);
            border-radius: 12px;
            padding: 1.25rem 2rem;
            display: grid;
            grid-template-columns: 80px 1.4fr 1.4fr 140px;
            align-items: center;
            gap: 2rem;
            margin-bottom: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .history-card:hover { 
            transform: translateX(8px); 
            border-color: var(--wine-primary);
            box-shadow: 0 10px 25px rgba(128,0,0,0.05);
        }
        
        /* Time Column */
        .col-time { text-align: center; border-right: 1.5px solid var(--border-light); padding-right: 1rem; }
        .time-val { font-weight: 800; font-size: 1rem; color: var(--slate-text); display: block; }
        .time-label { font-size: 0.65rem; font-weight: 600; color: var(--gray-sub); text-transform: uppercase; }

        /* Info Column */
        .col-main b { font-size: 1.05rem; display: block; color: var(--slate-text); margin-bottom: 0.3rem; letter-spacing: -0.2px; }
        .col-main span { font-size: 0.8rem; color: var(--gray-sub); font-weight: 500; display: flex; align-items: center; gap: 6px; }

        /* Detail/Products Column */
        .col-detail {
            font-size: 0.8rem;
            color: var(--slate-text);
            line-height: 1.6;
        }
        .col-detail-item {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }
        .col-detail-item:before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--wine-primary);
        }
        .col-detail-total {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1.5px dashed var(--border-light);
            font-weight: 800;
            color: var(--slate-text);
            font-size: 0.75rem;
        }

        /* Points Column */
        .col-pts { 
            text-align: right; 
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
        }
        .pts-value {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--wine-primary);
            line-height: 1;
            letter-spacing: -1px;
        }
        .pts-label {
            font-size: 0.65rem;
            font-weight: 800;
            color: var(--gray-sub);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 6px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: #fff;
            border-radius: 20px;
            border: 2px dashed var(--border-light);
        }
        .empty-state i { font-size: 4rem; color: var(--border-light); margin-bottom: 1.5rem; }

        /* Filters */
        .filter-bar {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-light);
            margin-bottom: 2.5rem;
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .filter-group { display: flex; flex-direction: column; gap: 0.5rem; flex: 1; min-width: 150px; }
        .filter-group label { font-size: 0.72rem; font-weight: 800; color: var(--gray-sub); text-transform: uppercase; letter-spacing: 1px; }
        .filter-input {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1.5px solid var(--border-light);
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
            color: var(--slate-text);
            outline: none;
            transition: all 0.2s;
        }
        .filter-input:focus { border-color: var(--wine-primary); box-shadow: 0 0 0 3px rgba(128,0,0,0.05); }
        .btn-filter {
            background: var(--slate-text);
            color: white;
            padding: 0.75rem 3.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            height: 42px;
        }
        .btn-filter:hover { background: #000; }
        .search-group { justify-content: flex-end; }
        .search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .search-input-wrapper i {
            position: absolute;
            left: 1rem;
            color: var(--gray-sub);
            font-size: 1.2rem;
            pointer-events: none;
        }
        .filter-input.with-icon {
            padding-left: 2.8rem;
            width: 100%;
        }
        .btn-reset {
            background: #f1f5f9;
            color: var(--slate-text);
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-weight: 800;
            font-size: 1.1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            width: 42px;
            transition: all 0.2s;
        }
        .btn-reset:hover { background: var(--border-light); }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 3rem;
        }
        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #fff;
            border: 1.5px solid var(--border-light);
            color: var(--slate-text);
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .page-link:hover { border-color: var(--wine-primary); color: var(--wine-primary); }
        .page-link.active { background: var(--wine-primary); color: #fff; border-color: var(--wine-primary); }
        .page-link.disabled { opacity: 0.5; pointer-events: none; }

        @media (max-width: 900px) {
            .history-summary {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
                padding: 1.5rem;
            }
            .history-summary div { text-align: center !important; }
            .history-summary h2 { font-size: 1.4rem !important; }
            .sum-val { font-size: 1.8rem; }
            
            .history-card { 
                display: flex;
                flex-direction: column;
                gap: 1rem;
                padding: 1.25rem; 
            }
            .col-time { 
                text-align: left;
                border-right: none;
                border-bottom: 1.5px solid var(--border-light);
                padding-right: 0;
                padding-bottom: 0.75rem;
                display: flex;
                align-items: baseline;
                gap: 0.5rem;
            }
            .col-main {
                border-bottom: 1px dashed var(--border-light);
                padding-bottom: 0.75rem;
            }
            .col-detail { 
                width: 100%; 
            }
            .col-pts {
                border-top: 1.5px solid var(--border-light);
                padding-top: 1rem;
                text-align: left;
                align-items: center;
                flex-direction: row;
                justify-content: space-between;
                flex-wrap: wrap;
            }
            .col-pts .pts-value {
                font-size: 1.4rem;
            }
            .col-pts .pts-label {
                margin-top: 0;
                margin-left: 0.5rem;
            }
            
            .filter-bar { flex-direction: column; align-items: stretch; }
            .timeline-header { display: none; }
        }
    </style>
</head>

<body>
    <div id="app">
        <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

        <div class="admin-layout">
            <?php 
                $pageTitle = 'Operaciones';
                $pageSubtitle = 'Mi historial detallado de entregas';
                include __DIR__ . '/../partials/header_admin.php'; 
            ?>

            <div class="premium-container">
                
                <!-- Elegant Summary -->
                <div class="history-summary">
                    <div>
                        <div class="sum-title">Rendimiento Histórico</div>
                        <h2 style="font-weight: 900; color: var(--slate-text); margin: 0; font-size: 1.8rem;">Entregas de Puntos</h2>
                    </div>
                    <div style="text-align: right;">
                        <div class="sum-title">Total Generado</div>
                        <div class="sum-val"><?= number_format($totalPuntosFiltrado) ?> <span style="font-size: 1rem; opacity: 0.5;">PTS</span></div>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="<?= BASE_URL ?>conductores/mi-historial" class="filter-bar">
                    <div class="filter-group search-group">
                        <div class="search-input-wrapper">
                            <i class='bx bx-search'></i>
                            <input type="text" name="search" class="filter-input with-icon" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Busca por nombre...">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Desde</label>
                        <input type="date" name="fecha_desde" class="filter-input" value="<?= htmlspecialchars($fechaDesde ?? '') ?>">
                    </div>
                    <div class="filter-group">
                        <label>Hasta</label>
                        <input type="date" name="fecha_hasta" class="filter-input" value="<?= htmlspecialchars($fechaHasta ?? '') ?>">
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn-filter"><i class='bx bx-search'></i> Filtrar</button>
                        <?php if(!empty($search) || !empty($fechaDesde) || !empty($fechaHasta)): ?>
                            <a href="<?= BASE_URL ?>conductores/mi-historial" class="btn-reset" title="Limpiar Filtros"><i class='bx bx-x'></i></a>
                        <?php endif; ?>
                    </div>
                </form>

                <?php if (!empty($historial)): ?>
                    <div class="timeline-header">
                        <div style="text-align: center;">Hora</div>
                        <div>Cliente y Operación</div>
                        <div>Detalle del Ticket</div>
                        <div style="text-align: right;">Puntos</div>
                    </div>

                    <?php 
                        // Agrupar por fecha
                        $grupos = [];
                        foreach ($historial as $v) {
                            $meses = ["January"=>"Enero", "February"=>"Febrero", "March"=>"Marzo", "April"=>"Abril", "May"=>"Mayo", "June"=>"Junio", "July"=>"Julio", "August"=>"Agosto", "September"=>"Septiembre", "October"=>"Octubre", "November"=>"Noviembre", "December"=>"Diciembre"];
                            $fechaStr = strtr(date('d F, Y', strtotime($v['fecha'])), $meses);
                            $grupos[$fechaStr][] = $v;
                        }
                    ?>

                    <?php foreach ($grupos as $fecha => $items): ?>
                        <div class="timeline-section">
                            <div class="date-divider">
                                <span class="date-label"><?= $fecha ?></span>
                                <div class="date-line"></div>
                            </div>

                            <?php foreach ($items as $v): ?>
                                <div class="history-card">
                                    <!-- Columna 1: Hora -->
                                    <div class="col-time">
                                        <span class="time-val"><?= date('H:i', strtotime($v['fecha'])) ?></span>
                                        <span class="time-label"><?= date('a', strtotime($v['fecha'])) ?></span>
                                    </div>

                                    <!-- Columna 2: Cliente -->
                                    <div class="col-main">
                                        <b><?= htmlspecialchars($v['cliente_nombre'] ?? '') ?></b>
                                        <?php /* <span><i class='bx bx-id-card'></i> DNI <?= htmlspecialchars($v['cliente_dni'] ?? '') ?></span> */ ?>
                                        <?php if ($v['monto'] > 0): ?>
                                            <span style="margin-top: 5px; color: #10b981; font-weight: 800;">
                                                <i class='bx bx-check-double'></i> S/ <?= number_format($v['monto'], 2) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Columna 3: Detalles -->
                                    <div class="col-detail">
                                        <?php if (!empty($v['items'])): ?>
                                            <?php foreach ($v['items'] as $item): ?>
                                                <div class="col-detail-item">
                                                    <?= htmlspecialchars($item['nombre_item'] ?? '') ?> 
                                                    <span style="color: var(--gray-sub); font-weight: 500;">×<?= $item['cantidad'] ?></span>
                                                    <span style="margin-left: auto; font-weight: 700; color: var(--wine-primary);">(+<?= $item['puntos_subtotal'] ?>)</span>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php 
                                                // Fallback para registros antiguos (legacy)
                                                $detalleRaw = str_replace(["\r", "), ", ", Recarga"], ["", ")\n• Recarga", "\n• Recarga"], $v['detalle'] ?? '');
                                                $lineas = explode("\n", $detalleRaw);
                                                
                                                foreach ($lineas as $linea) {
                                                    if (!preg_match('/[a-zA-Z0-9]/', $linea)) continue;
                                                    $linea = ltrim(trim($linea), "•_ \t\n\r\0\x0B");
                                                    if (str_starts_with(strtoupper($linea), 'TOTAL:')) {
                                                        echo '<div class="col-detail-total">' . htmlspecialchars(strtoupper($linea)) . '</div>';
                                                    } else {
                                                        echo '<div class="col-detail-item">' . htmlspecialchars($linea) . '</div>';
                                                    }
                                                }
                                            ?>
                                        <?php endif; ?>

                                        <?php if (!empty($v['balones_cantidad']) || !empty($v['evidencia_foto'])): ?>
                                            <div style="margin-top: 8px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
                                                <?php if (!empty($v['balones_cantidad'])): ?>
                                                    <span style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 0.72rem; font-weight: 700; padding: 2px 7px; display: inline-flex; align-items: center; gap: 4px;">
                                                        <i class='bx bx-cube-alt'></i> <?= $v['balones_cantidad'] ?> Balones (10kg)
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($v['evidencia_foto'])): ?>
                                                    <button type="button" onclick="verEvidenciaModal('<?= BASE_URL . $v['evidencia_foto'] ?>', '<?= htmlspecialchars(addslashes($v['cliente_nombre'] ?? '')) ?>', '<?= $v['balones_cantidad'] ?? '' ?>')" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; color: #0f172a; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                                        <i class='bx bx-image-alt' style="color: #800000; font-size: 0.95rem;"></i> Ver Foto
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Columna 4: Puntos y Acciones -->
                                    <div class="col-pts">
                                        <div class="pts-value" style="color: <?= isset($v['estado']) && $v['estado'] === 'pendiente' ? '#f59e0b' : (isset($v['estado']) && $v['estado'] === 'rechazado' ? '#ef4444' : '#1e293b') ?>;">+<?= $v['puntos'] ?></div>
                                        <div class="pts-label">Pts</div>
                                        <?php if (isset($v['estado'])): ?>
                                            <?php if ($v['estado'] === 'pendiente'): ?>
                                                <div style="font-size: 0.6rem; color: #d97706; background: #fef3c7; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; font-weight: 700; text-align: center;">
                                                    PENDIENTE
                                                </div>
                                            <?php elseif ($v['estado'] === 'rechazado'): ?>
                                                <div style="font-size: 0.6rem; color: #dc2626; background: #fee2e2; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; font-weight: 700; text-align: center;">
                                                    RECHAZADO
                                                </div>
                                            <?php else: ?>
                                                <div style="font-size: 0.6rem; color: #16a34a; background: #dcfce7; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; font-weight: 700; text-align: center;">
                                                    APROBADO
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if (isset($v['estado']) && $v['estado'] === 'aprobado'): ?>
                                            <div id="btn-notificar-box-<?= $v['id'] ?>" style="margin-top: 8px;">
                                                <?php if (!empty($v['notificado_admin'])): ?>
                                                    <button type="button" onclick="notificarAdmin(<?= $v['id'] ?>, this)" title="Reenviar notificación por correo al Administrador" style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; font-size: 0.68rem; font-weight: 800; padding: 4px 8px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                                        <i class='bx bx-check-double'></i> Notificado
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" onclick="notificarAdmin(<?= $v['id'] ?>, this)" style="background: #800000; color: #fff; border: none; font-size: 0.68rem; font-weight: 800; padding: 5px 10px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 4px 10px rgba(128,0,0,0.2); transition: all 0.2s;">
                                                        <i class='bx bx-envelope'></i> Notificar Admin
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination-container">
                            <?php 
                                $qParams = $_GET;
                                unset($qParams['url']); // Remove routing params if any
                            ?>
                            
                            <!-- Previous Page -->
                            <?php 
                                $prevParams = $qParams;
                                $prevParams['page'] = $currentPage - 1;
                                $prevUrl = '?' . http_build_query($prevParams);
                            ?>
                            <a href="<?= $prevUrl ?>" class="page-link <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                <i class='bx bx-chevron-left'></i>
                            </a>

                            <!-- Page Numbers -->
                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <?php 
                                    $pageParams = $qParams;
                                    $pageParams['page'] = $i;
                                    $pageUrl = '?' . http_build_query($pageParams);
                                ?>
                                <a href="<?= $pageUrl ?>" class="page-link <?= ($i == $currentPage) ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <!-- Next Page -->
                            <?php 
                                $nextParams = $qParams;
                                $nextParams['page'] = $currentPage + 1;
                                $nextUrl = '?' . http_build_query($nextParams);
                            ?>
                            <a href="<?= $nextUrl ?>" class="page-link <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                <i class='bx bx-chevron-right'></i>
                            </a>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="empty-state">
                        <i class='bx bx-objects-vertical-bottom'></i>
                        <h3 style="font-weight: 900; color: var(--slate-text); margin-bottom: 0.5rem;">
                            <?= (!empty($search) || !empty($fechaDesde) || !empty($fechaHasta)) ? 'No hay resultados' : 'Sin historial disponible' ?>
                        </h3>
                        <p style="color: var(--gray-sub); font-weight: 500;">
                            <?= (!empty($search) || !empty($fechaDesde) || !empty($fechaHasta)) ? 'No se encontraron entregas con los filtros actuales.' : 'Aún no has registrado ninguna entrega de puntos a clientes.' ?>
                        </p>
                        <?php if(!empty($search) || !empty($fechaDesde) || !empty($fechaHasta)): ?>
                            <a href="<?= BASE_URL ?>conductores/mi-historial" class="btn-primary-premium" style="display: inline-flex; margin-top: 2rem; text-decoration: none;">
                                Limpiar Filtros
                            </a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>scan" class="btn-primary-premium" style="display: inline-flex; margin-top: 2rem; text-decoration: none;">
                                Escanear Primer Cliente
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- MODAL: PREVISUALIZAR EVIDENCIA -->
    <div id="modal-preview-evidencia" style="display: none; position: fixed; inset: 0; z-index: 999999; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 1.5rem;">
        <div style="background: #fff; border-radius: 24px; max-width: 600px; width: 100%; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 30px 80px rgba(0,0,0,0.5);">
            <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background: #f8fafc;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="background: #800000; color: #fff; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class='bx bx-image'></i>
                    </div>
                    <div>
                        <h4 id="modal-evidencia-cliente" style="margin: 0; font-size: 1rem; font-weight: 900; color: #0f172a;">Foto de Evidencia</h4>
                        <span id="modal-evidencia-balones" style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Recuento de Balones</span>
                    </div>
                </div>
                <button onclick="cerrarModalEvidencia()" style="background: #e2e8f0; border: none; width: 32px; height: 32px; border-radius: 50%; color: #475569; cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div style="padding: 1.5rem; text-align: center; overflow-y: auto; background: #0f172a; display: flex; align-items: center; justify-content: center; min-height: 300px;">
                <img id="modal-evidencia-img" src="" alt="Evidencia de balones" style="max-width: 100%; max-height: 60vh; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); object-fit: contain;">
            </div>
            <div style="padding: 1rem 1.75rem; border-top: 1px solid #e2e8f0; background: #f8fafc; text-align: right;">
                <button type="button" onclick="cerrarModalEvidencia()" style="background: #800000; color: #fff; border: none; padding: 0.6rem 1.5rem; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        function verEvidenciaModal(imgUrl, cliente, balones) {
            document.getElementById('modal-evidencia-img').src = imgUrl;
            document.getElementById('modal-evidencia-cliente').textContent = cliente || 'Foto de Evidencia';
            document.getElementById('modal-evidencia-balones').textContent = balones ? (balones + ' balones de 10kg verificados') : 'Evidencia de entrega';
            document.getElementById('modal-preview-evidencia').style.display = 'flex';
        }

        function cerrarModalEvidencia() {
            document.getElementById('modal-preview-evidencia').style.display = 'none';
        }

        async function notificarAdmin(ventaId, btn) {
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = "<i class='bx bx-loader-alt bx-spin'></i> Enviando...";

            try {
                const res = await fetch('<?= BASE_URL ?>conductores/notificar-admin', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ venta_id: ventaId })
                });
                const data = await res.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Notificación Enviada!',
                        html: 'Se envió un correo con los detalles de la operación y la evidencia adjunta a: <br><b>jaimeelias.tacna.2016@gmail.com</b>',
                        confirmButtonColor: '#800000'
                    });

                    const box = document.getElementById('btn-notificar-box-' + ventaId);
                    if (box) {
                        box.innerHTML = `<button type="button" onclick="notificarAdmin(${ventaId}, this)" title="Reenviar notificación por correo al Administrador" style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; font-size: 0.68rem; font-weight: 800; padding: 4px 8px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;"><i class='bx bx-check-double'></i> Notificado</button>`;
                    }
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al Notificar',
                        text: data.message || 'No se pudo enviar la notificación por correo.',
                        confirmButtonColor: '#800000'
                    });
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Red',
                    text: 'Ocurrió un error de conexión al enviar el correo.',
                    confirmButtonColor: '#800000'
                });
            }
        }
    </script>
</body>

</html>
