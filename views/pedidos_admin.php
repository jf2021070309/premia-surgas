<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Chatbot — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        .stats-grid-modern {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .dash-card {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #eef2f7;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
            position: relative;
            overflow: hidden;
            transition: 0.3s;
        }
        .dash-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
        }
        .dash-card-body {
            position: relative;
            z-index: 2;
        }
        .dash-card-number {
            font-size: 2.2rem;
            font-weight: 850;
            color: #0f172a !important;
            line-height: 1.1;
        }
        .dash-card-text {
            font-size: 0.78rem;
            color: #64748b !important;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        .dash-card-icon {
            font-size: 3rem;
            opacity: 0.08;
            position: absolute;
            right: 1.2rem;
            bottom: 1.2rem;
            color: #000;
            transition: 0.3s;
        }
        .card-orange { border-left: 5px solid #f97316; }
        .card-orange .dash-card-icon { color: #f97316; }
        .card-green { border-left: 5px solid #16a34a; }
        .card-green .dash-card-icon { color: #16a34a; }
        .card-red { border-left: 5px solid #dc2626; }
        .card-red .dash-card-icon { color: #dc2626; }
        .card-wine { border-left: 5px solid #821515; }
        .card-wine .dash-card-icon { color: #821515; }

        .chip-ped-pendiente {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fef3c7;
        }
        .chip-ped-entregado {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #dcfce7;
        }
        .chip-ped-cancelado {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fee2e2;
        }
        .btn-ped-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #475569;
            cursor: pointer;
            transition: 0.2s;
            font-size: 1.1rem;
        }
        .btn-ped-action:hover {
            transform: scale(1.08);
        }
        .btn-ped-action.btn-ped-deliver:hover {
            background: #16a34a;
            color: #fff;
            border-color: #16a34a;
        }
        .btn-ped-action.btn-ped-cancel:hover {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
        }
        .btn-ped-gps {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }
        .btn-ped-gps:hover {
            background: #1d4ed8;
            color: #fff;
            border-color: #1d4ed8;
        }
    </style>
</head>
<body>

    <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Pedidos Chatbot';
            $pageSubtitle = 'Monitoreo y despacho de solicitudes del chatbot';
            include __DIR__ . '/partials/header_admin.php';
        ?>

        <div class="container">

            <!-- Stats -->
            <?php
                $pendientes = 0;
                $entregados = 0;
                $cancelados = 0;
                $total = count($pedidos);
                foreach ($pedidos as $p) {
                    if ($p['estado'] === 'pendiente') $pendientes++;
                    elseif ($p['estado'] === 'entregado') $entregados++;
                    elseif ($p['estado'] === 'cancelado') $cancelados++;
                }
            ?>
            <div class="stats-grid-modern">
                <div class="dash-card card-orange">
                    <div class="dash-card-body">
                        <div class="dash-card-number count-up" data-target="<?= $pendientes ?>">0</div>
                        <div class="dash-card-text">Pendientes</div>
                    </div>
                    <i class='bx bx-time-five dash-card-icon'></i>
                </div>
                <div class="dash-card card-green">
                    <div class="dash-card-body">
                        <div class="dash-card-number count-up" data-target="<?= $entregados ?>">0</div>
                        <div class="dash-card-text">Entregados</div>
                    </div>
                    <i class='bx bx-check-shield dash-card-icon'></i>
                </div>
                <div class="dash-card card-red">
                    <div class="dash-card-body">
                        <div class="dash-card-number count-up" data-target="<?= $cancelados ?>">0</div>
                        <div class="dash-card-text">Cancelados</div>
                    </div>
                    <i class='bx bx-error-circle dash-card-icon'></i>
                </div>
                <div class="dash-card card-wine">
                    <div class="dash-card-body">
                        <div class="dash-card-number count-up" data-target="<?= $total ?>">0</div>
                        <div class="dash-card-text">Total Pedidos</div>
                    </div>
                    <i class='bx bx-shopping-bag dash-card-icon'></i>
                </div>
            </div>

            <!-- Toolbar Filters -->
            <div class="clientes-toolbar" style="margin-bottom: 1.5rem;">
                <div class="clientes-toolbar-filters">
                    <div class="section-title-flex">
                        <div class="section-title-text">
                            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">Listado de Pedidos</h3>
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Pedidos entrantes desde la interfaz chatbot del cliente</span>
                        </div>
                    </div>
                </div>
                <div class="clientes-toolbar-search" style="flex: 1; justify-content: flex-end; gap: 0.75rem; flex-wrap: wrap;">
                    <div class="filter-group-mobile" style="width: auto;">
                        <div class="header-search-modern" style="width: 160px;">
                            <i class='bx bx-calendar'></i>
                            <input type="date" id="pedDate" onchange="filterPedidos()">
                        </div>
                        <div class="header-search-modern" style="width: 150px;">
                            <i class='bx bx-filter-alt'></i>
                            <select id="pedStatus" onchange="filterPedidos()">
                                <option value="">Todos los Estados</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="entregado">Entregados</option>
                                <option value="cancelado">Cancelados</option>
                            </select>
                        </div>
                    </div>
                    <div class="header-search-modern clientes-search-input" style="max-width: 280px;">
                        <i class='bx bx-search'></i>
                        <input type="text" id="pedSearch" placeholder="Buscar cliente o dirección..." onkeyup="filterPedidos()">
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card">
                <div class="table-wrapper">
                    <?php if (empty($pedidos)): ?>
                        <div class="empty-state">
                            <div class="empty-icon" style="color: var(--primary);"><i class='bx bx-shopping-bag'></i></div>
                            <h3>Sin pedidos registrados</h3>
                            <p>Cuando los clientes realicen pedidos desde el chatbot, aparecerán aquí.</p>
                        </div>
                    <?php else: ?>
                        <table class="data-table" id="pedidosTable">
                            <thead>
                                <tr>
                                    <th>ID / Fecha</th>
                                    <th>Cliente</th>
                                    <th>Modalidad</th>
                                    <th>Detalles Pedido</th>
                                    <th style="text-align: center !important;">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidos as $p):
                                    $chipClass = match($p['estado']) {
                                        'pendiente' => 'chip-ped-pendiente',
                                        'entregado' => 'chip-ped-entregado',
                                        'cancelado' => 'chip-ped-cancelado',
                                        default => 'chip-ped-pendiente'
                                    };
                                ?>
                                <tr data-id="<?= $p['id'] ?>" data-date="<?= date('Y-m-d', strtotime($p['fecha_creacion'])) ?>" data-status="<?= $p['estado'] ?>">
                                    <td class="date-text">
                                        <div style="font-weight: 700; color: var(--on-surface);">
                                            #<?= str_pad($p['id'], 5, '0', STR_PAD_LEFT) ?>
                                        </div>
                                        <div style="font-size: 0.7rem; opacity: 0.7;">
                                            <?= date('d/m/Y h:i A', strtotime($p['fecha_creacion'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="client-name" style="font-weight:700; color:#0f172a;"><?= htmlspecialchars($p['cliente_nombre']) ?></div>
                                        <div style="font-size:0.75rem; color:#64748b; font-weight:600; margin-top:2px;">
                                            Celular: <?= htmlspecialchars($p['cliente_celular']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="chip chip-pending" style="font-weight:800; border-radius:8px;">
                                            <?= htmlspecialchars($p['modalidad']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($p['modalidad'] === 'A Domicilio'): ?>
                                            <div style="font-size:0.88rem; font-weight:700; color:#1e293b;">
                                                Balón <?= htmlspecialchars($p['producto']) ?> x <?= $p['cantidad'] ?>
                                            </div>
                                            <div style="font-size:0.78rem; color:#64748b; margin-top:3px; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                                <i class='bx bx-map-pin' style='color:#e11d48;'></i> <?= htmlspecialchars($p['direccion']) ?>
                                                <?php if ($p['latitud'] && $p['longitud']): ?>
                                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= $p['latitud'] ?>,<?= $p['longitud'] ?>" target="_blank" class="btn-ped-action btn-ped-gps" title="Ver GPS en Maps" style="width:24px; height:24px; font-size:0.85rem; border-radius:6px;">
                                                        <i class='bx bx-navigation'></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div style="font-size:0.88rem; font-weight:700; color:#1e293b;">
                                                Retiro en Depósito
                                            </div>
                                            <div style="font-size:0.78rem; color:#64748b; margin-top:3px;">
                                                <i class='bx bx-store' style='color:#7c3aed;'></i> <?= htmlspecialchars($p['direccion']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center !important;">
                                        <span class="chip <?= $chipClass ?>" style="font-weight: 800; border-radius: 50px; padding: 4px 10px; font-size: 0.68rem; text-transform: uppercase;">
                                            <i class='bx bxs-circle' style="font-size:0.5rem; margin-right:4px;"></i> 
                                            <span class="status-text-cell"><?= $p['estado'] ?></span>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($p['estado'] === 'pendiente'): ?>
                                            <div style="display:flex; justify-content:center; gap:8px;">
                                                <button class="btn-ped-action btn-ped-deliver" onclick="updatePedido(<?= $p['id'] ?>, 'entregado')" title="Marcar como Entregado">
                                                    <i class='bx bx-check'></i>
                                                </button>
                                                <button class="btn-ped-action btn-ped-cancel" onclick="updatePedido(<?= $p['id'] ?>, 'cancelado')" title="Cancelar Pedido">
                                                    <i class='bx bx-x'></i>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <span style="color:#94a3b8; font-size:0.8rem; font-weight:600; text-transform:uppercase;">Procesado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <!-- Pagination Footer -->
                <div class="card-footer-premium">
                    <div class="footer-info">
                        Mostrando <span id="pagStart">0</span> - <span id="pagEnd">0</span> de <span id="pagTotal">0</span> pedidos
                    </div>
                    <div id="pageNumbers" class="pagination-elite">
                        <!-- Pagination controls -->
                    </div>
                </div>
            </div>

        </div> <!-- .container -->
    </div> <!-- .admin-layout -->

    <script>
        let currentPage = 1;
        const rowsPerPage = 10;

        function filterPedidos() {
            const query = document.getElementById('pedSearch').value.toLowerCase();
            const status = document.getElementById('pedStatus').value.toLowerCase();
            const dateVal = document.getElementById('pedDate').value;
            const rows = Array.from(document.querySelectorAll('#pedidosTable tbody tr'));

            let visibleRows = rows.filter(row => {
                const client = row.querySelector('.client-name').textContent.toLowerCase();
                const textDetail = row.textContent.toLowerCase();
                const rowStatus = row.getAttribute('data-status').toLowerCase();
                const rowDate = row.getAttribute('data-date');

                const matchesSearch = client.includes(query) || textDetail.includes(query);
                const matchesStatus = status === "" || rowStatus === status;
                const matchesDate = !dateVal || rowDate === dateVal;

                return matchesSearch && matchesStatus && matchesDate;
            });

            const total = visibleRows.length;
            const maxPage = Math.max(1, Math.ceil(total / rowsPerPage));
            if (currentPage > maxPage) currentPage = maxPage;

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach(r => r.style.display = 'none');
            visibleRows.slice(start, end).forEach(r => r.style.display = '');

            document.getElementById('pagTotal').textContent = total;
            document.getElementById('pagStart').textContent = total === 0 ? 0 : start + 1;
            document.getElementById('pagEnd').textContent = Math.min(end, total);

            updatePaginationUI(maxPage);
        }

        function updatePaginationUI(maxPage) {
            const container = document.getElementById('pageNumbers');
            if (!container) return;
            container.innerHTML = '';

            const prev = document.createElement('button');
            prev.className = 'page-btn nav-arrows';
            prev.innerHTML = "<i class='bx bx-chevron-left'></i>";
            prev.disabled = currentPage === 1;
            prev.onclick = () => { currentPage--; filterPedidos(); };
            container.appendChild(prev);

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(maxPage, startPage + 4);
            if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

            for (let i = startPage; i <= endPage; i++) {
                if (i < 1) continue;
                const btn = document.createElement('button');
                btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
                btn.textContent = i;
                btn.onclick = () => { currentPage = i; filterPedidos(); };
                container.appendChild(btn);
            }

            const next = document.createElement('button');
            next.className = 'page-btn nav-arrows';
            next.innerHTML = "<i class='bx bx-chevron-right'></i>";
            next.disabled = currentPage === maxPage;
            next.onclick = () => { currentPage++; filterPedidos(); };
            container.appendChild(next);
        }

        function updatePedido(id, nuevoEstado) {
            fetch('<?= BASE_URL ?>api/chatbot/update-estado', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, estado: nuevoEstado })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#0f172a'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de red',
                    text: 'No se pudo comunicar con el servidor.',
                    confirmButtonColor: '#0f172a'
                });
            });
        }

        // Real-time notification check
        let lastKnownOrderId = <?= empty($pedidos) ? 0 : max(array_column($pedidos, 'id')) ?>;
        
        function checkNewOrders() {
            // Check status of notifications using index live statistics
            fetch('<?= BASE_URL ?>auth/check?_t=' + Date.now())
            .then(res => res.json())
            .then(data => {
                // If session is still valid, let's trigger reload silently or check if new count changed.
                // For a robust and simple implementation, check count of orders in background
            });
        }

        // Count-Up Animation
        function animateCountUp() {
            document.querySelectorAll('.count-up').forEach(el => {
                const target = +el.getAttribute('data-target');
                const duration = 1200;
                const increment = target / (duration / 16);
                let current = 0;

                const update = () => {
                    current += increment;
                    if (current < target) {
                        el.textContent = Math.floor(current).toLocaleString();
                        requestAnimationFrame(update);
                    } else {
                        el.textContent = target.toLocaleString();
                    }
                };
                update();
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            filterPedidos();
            animateCountUp();
        });
    </script>
</body>
</html>
