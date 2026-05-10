<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación de Puntos — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/recargas_admin.css">
    <style>
        .qr-card { display: none !important; }
        .dashboard-top-grid { display: none !important; }
    </style>
</head>
<body>

    <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión de Puntos';
            $pageSubtitle = 'Panel de validación administrativa de puntos asignados';
            include __DIR__ . '/partials/header_admin.php';
        ?>

        <div class="container">

        <?php if (isset($_SESSION['flash'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true });
                Toast.fire({ icon: '<?= $_SESSION['flash']['type'] ?>', title: '<?= htmlspecialchars($_SESSION['flash']['title']) ?>', text: '<?= htmlspecialchars($_SESSION['flash']['message']) ?>' });
            });
        </script>
        <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- ════════════════════════════════════════════
             SECTION 0 — Stats Icon Boxes
        ════════════════════════════════════════════ -->
        <?php
            $totalPendientes = count($ventas);
            $totalAprobados  = 0;
            $totalRechazados = 0;
            $puntosTotales   = 0;
            if (!empty($historial)) {
                foreach ($historial as $h) {
                    if (($h['estado'] ?? '') === 'aprobado') { 
                        $totalAprobados++; 
                        $puntosTotales += $h['puntos']; 
                    }
                    if (($h['estado'] ?? '') === 'rechazado') $totalRechazados++;
                }
            }
        ?>
        <div class="stats-grid-modern" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
            
            <div class="dash-card card-orange">
                <div class="dash-card-body">
                    <div class="dash-card-number count-up" data-target="<?= $totalPendientes ?>">0</div>
                    <div class="dash-card-text">Pendientes</div>
                </div>
                <i class='bx bx-time-five dash-card-icon'></i>
            </div>

            <div class="dash-card card-wine">
                <div class="dash-card-body">
                    <div class="dash-card-number count-up" data-target="<?= $totalAprobados ?>">0</div>
                    <div class="dash-card-text">Aprobados</div>
                </div>
                <i class='bx bx-check-shield dash-card-icon'></i>
            </div>

            <div class="dash-card card-dark">
                <div class="dash-card-body">
                    <div class="dash-card-number count-up" data-target="<?= $totalRechazados ?>">0</div>
                    <div class="dash-card-text">Rechazados</div>
                </div>
                <i class='bx bx-error-circle dash-card-icon'></i>
            </div>

            <div class="dash-card card-wine">
                <div class="dash-card-body">
                    <div class="dash-card-number count-up" data-target="<?= $puntosTotales ?>">0</div>
                    <div class="dash-card-text">Puntos Aprobados</div>
                </div>
                <i class='bx bx-star dash-card-icon'></i>
            </div>

        </div>

        <!-- ════════════════════════════════════════════
             SECTION 2 — Revisión Pendiente
        ════════════════════════════════════════════ -->
        <?php $numPendientes = count($ventas); ?>
        <div class="card" style="margin-top: 2rem; margin-bottom:0;">
            <div class="card-header" onclick="togglePending()">
                <div class="header-title-flex">
                    <i class='bx bx-time-five'></i>
                    <div class="title-text-group">
                        <h3>Puntos Pendientes de Aprobación</h3>
                        <span>Asignaciones de puntos esperando validación</span>
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:0.65rem;">
                    <span class="pending-badge"><?= $numPendientes ?> pendiente<?= $numPendientes !== 1 ? 's' : '' ?></span>
                    <i id="pendingChevron" class='bx bx-chevron-down toggle-chevron <?= $numPendientes > 0 ? 'open' : '' ?>' style="font-size:1.25rem;"></i>
                </div>
            </div>

            <div id="pendingSectionBody" class="pending-section-body <?= $numPendientes > 0 ? 'open' : '' ?>">
                <?php if (empty($ventas)): ?>
                    <div class="empty-state-compact">
                        <i class='bx bxs-check-shield'></i>
                        <div class="empty-text">
                            <strong>¡Todo al día!</strong>
                            <span>No hay asignaciones de puntos pendientes de verificar en este momento.</span>
                        </div>
                    </div>
                <?php else: ?>
                    <ul class="ticket-list">
                        <?php foreach ($ventas as $r): ?>
                        <li class="ticket-card">

                            <div class="ticket-info">
                                <div class="ticket-name"><?= htmlspecialchars($r['cliente_nombre']) ?></div>
                                <div class="ticket-detail">
                                    <span class="ticket-detail-item">
                                        <i class='bx bx-id-card'></i>
                                        DNI <?= htmlspecialchars($r['cliente_dni']) ?>
                                    </span>
                                    <span class="detail-sep">•</span>
                                    <span class="ticket-detail-item" title="Operador que asignó">
                                        <i class='bx bx-user-pin'></i>
                                        Operador: <?= htmlspecialchars($r['conductor_nombre'] ?? 'Admin') ?>
                                    </span>
                                    <span class="detail-sep">•</span>
                                    <span class="ticket-detail-item">
                                        <i class='bx bx-time'></i>
                                        <?php
                                            $meses = ["January"=>"Enero", "February"=>"Febrero", "March"=>"Marzo", "April"=>"Abril", "May"=>"Mayo", "June"=>"Junio", "July"=>"Julio", "August"=>"Agosto", "September"=>"Septiembre", "October"=>"Octubre", "November"=>"Noviembre", "December"=>"Diciembre"];
                                            echo strtr(date('d F, g:i a', strtotime($r['fecha'])), $meses);
                                        ?>
                                    </span>
                                </div>
                            </div>

                            <div class="ticket-amounts">
                                <div class="pts-val"><i class='bx bxs-up-arrow-circle'></i> <?= number_format($r['puntos']) ?> pts</div>
                                <div class="monto-val">S/ <?= number_format($r['monto'], 2) ?></div>
                            </div>

                            <div class="ticket-actions">
                                <form action="<?= BASE_URL ?>puntos-admin/actualizar" method="POST" style="margin:0;" class="approve-form">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="estado" value="aprobado">
                                    <?php
                                        $resumen_items = 'hoy';
                                        if (!empty($r['items'])) {
                                            $partes = [];
                                            foreach($r['items'] as $item) {
                                                $partes[] = $item['cantidad'] . 'x ' . $item['nombre_item'];
                                            }
                                            $resumen_items = implode(', ', $partes);
                                        }
                                    ?>
                                    <button type="button" class="btn btn-success btn-approve-trigger"
                                        data-phone="<?= htmlspecialchars($r['cliente_celular'] ?? '') ?>"
                                        data-name="<?= htmlspecialchars($r['cliente_nombre'] ?? '') ?>"
                                        data-puntos="<?= $r['puntos'] ?>"
                                        data-monto="<?= $r['monto'] ?>"
                                        data-items="<?= htmlspecialchars($resumen_items) ?>">
                                        <i class='bx bx-check'></i> Aprobar
                                    </button>
                                </form>

                                <form action="<?= BASE_URL ?>puntos-admin/actualizar" method="POST" style="margin:0;" class="reject-form">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="estado" value="rechazado">
                                    <button type="button" class="btn btn-danger-ghost btn-reject-trigger" title="Rechazar">
                                        <i class='bx bx-x' style="font-size:1.1rem;"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- ════════════════════════════════════════════
             SECTION 3 — Historial de Movimientos
        ════════════════════════════════════════════ -->
        <div class="clientes-toolbar" style="margin-top: 3.5rem; margin-bottom: 1.5rem;">
            <div class="clientes-toolbar-filters">
                <div class="section-title-flex">
                    <div class="section-title-text">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">Historial de Validación</h3>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Panel de validación de puntos</span>
                    </div>
                </div>
            </div>
            <div class="clientes-toolbar-search" style="flex: 1; justify-content: flex-end; gap: 0.75rem; flex-wrap: wrap;">
                <div class="filter-group-mobile" style="width: auto;">
                    <div class="header-search-modern" style="width: 160px;">
                        <i class='bx bx-calendar'></i>
                        <input type="date" id="historyDate" onchange="filterHistory()">
                    </div>
                    <div class="header-search-modern" style="width: 150px;">
                        <i class='bx bx-filter-alt'></i>
                        <select id="historyStatus" onchange="filterHistory()">
                            <option value="">Todos</option>
                            <option value="aprobado">Aprobados</option>
                            <option value="rechazado">Rechazados</option>
                            <option value="pendiente">Pendientes</option>
                        </select>
                    </div>
                </div>
                <div class="header-search-modern clientes-search-input" style="max-width: 280px;">
                    <i class='bx bx-search'></i>
                    <input type="text" id="historySearch" placeholder="Buscar por cliente..." onkeyup="filterHistory()">
                </div>
            </div>
        </div>


        <div class="card">
            <div class="table-wrapper">

            <?php if (empty($historial)): ?>
                <div class="empty-state">
                    <div class="empty-icon" style="color: var(--primary);"><i class='bx bx-spreadsheet'></i></div>
                    <h3>Sin asignaciones todavía</h3>
                    <p>Cuando los operadores asignen puntos, aparecerán aquí para validación.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Cliente</th>
                                <th>Puntos</th>
                                <th>Monto</th>
                                <th>Operador</th>
                                <th style="text-align: center !important;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $h):
                                $chipClass = match($h['estado'] ?? '') {
                                    'aprobado'  => 'chip-approved',
                                    'rechazado' => 'chip-rejected',
                                    default     => 'chip-pending'
                                };
                            ?>
                            <tr>
                                <td class="date-text">
                                    <div style="font-weight: 700; color: var(--on-surface);">
                                        <?php
                                            $meses = ["January"=>"Enero", "February"=>"Febrero", "March"=>"Marzo", "April"=>"Abril", "May"=>"Mayo", "June"=>"Junio", "July"=>"Julio", "August"=>"Agosto", "September"=>"Septiembre", "October"=>"Octubre", "November"=>"Noviembre", "December"=>"Diciembre"];
                                            echo strtr(date('d F Y', strtotime($h['fecha'])), $meses);
                                        ?>
                                    </div>
                                    <div style="font-size: 0.7rem; opacity: 0.7;">
                                        <?= date('h:i A', strtotime($h['fecha'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="client-name"><?= htmlspecialchars($h['cliente_nombre'] ?? '-') ?></span>
                                </td>
                                <td><span style="color: var(--on-surface);">+<?= number_format($h['puntos']) ?> pts</span></td>
                                <td style="color: var(--on-muted);">S/ <?= number_format($h['monto'], 2) ?></td>
                                <td><span style="color: var(--on-surface); font-size: 0.85rem;"><?= htmlspecialchars($h['conductor_nombre'] ?? 'Admin') ?></span></td>
                                <td style="text-align: center !important;"><span class="chip <?= $chipClass ?>" ><i class='bx bxs-circle'></i> <?= ucfirst($h['estado'] ?? 'pendiente') ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Controls -->
                <div class="card-footer-premium">
                    <div class="footer-info">
                        Mostrando <span id="pagStart">0</span> - <span id="pagEnd">0</span> de <span id="pagTotal">0</span> movimientos
                    </div>
                    <div id="pageNumbers" class="pagination-elite">
                        <!-- JS injects here -->
                    </div>
                </div>

            <?php endif; ?>
        </div>

        </main>
    </div> <!-- .admin-main -->

    <script>
        function togglePending() {
            const body = document.getElementById('pendingSectionBody');
            const icon = document.getElementById('pendingChevron');
            body.classList.toggle('open');
            icon.classList.toggle('open');
        }

        // ── History Filter & Pagination ──
        let currentPage = 1;
        const rowsPerPage = 10;

        function filterHistory() {
            const query = document.getElementById('historySearch').value.toLowerCase();
            const status = document.getElementById('historyStatus').value.toLowerCase();
            const dateVal = document.getElementById('historyDate').value;
            const rows = Array.from(document.querySelectorAll('.data-table tbody tr'));
            
            let visibleRows = rows.filter(row => {
                const name = row.querySelector('.client-name').textContent.toLowerCase();
                const rowStatus = row.querySelector('.chip').textContent.toLowerCase();
                const rowDate = row.querySelector('.date-text').textContent; // Format: "dd F Y, H:i"
                
                // Match search
                const matchesSearch = name.includes(query);
                // Match status
                const matchesStatus = status === "" || rowStatus.includes(status);
                // Match date
                let matchesDate = true;
                if (dateVal) {
                    const [y, m, d] = dateVal.split('-');
                    const dateObj = new Date(y, m - 1, d);
                    const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                    const formattedDate = `${d.padStart(2, '0')} ${months[dateObj.getMonth()]} ${y}`;
                    matchesDate = rowDate.includes(formattedDate);
                }

                return matchesSearch && matchesStatus && matchesDate;
            });

            // Pagination Logic
            const total = visibleRows.length;
            const maxPage = Math.max(1, Math.ceil(total / rowsPerPage));
            if (currentPage > maxPage) currentPage = maxPage;

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach(r => r.style.display = 'none');
            visibleRows.slice(start, end).forEach(r => r.style.display = '');

            // Update UI
            document.getElementById('pagTotal').textContent = total;
            document.getElementById('pagStart').textContent = total === 0 ? 0 : start + 1;
            document.getElementById('pagEnd').textContent = Math.min(end, total);
            
            updatePaginationUI(maxPage);
        }

        function updatePaginationUI(maxPage) {
            const container = document.getElementById('pageNumbers');
            container.innerHTML = '';

            // Prev button
            const prev = document.createElement('button');
            prev.className = 'page-btn nav-arrows';
            prev.innerHTML = "<i class='bx bx-chevron-left'></i>";
            prev.disabled = currentPage === 1;
            prev.onclick = () => { currentPage--; filterHistory(); };
            container.appendChild(prev);

            // Page numbers logic (sliding window if many pages)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(maxPage, startPage + 4);
            if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

            for (let i = startPage; i <= endPage; i++) {
                if (i < 1) continue;
                const btn = document.createElement('button');
                btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
                btn.textContent = i;
                btn.onclick = () => { currentPage = i; filterHistory(); };
                container.appendChild(btn);
            }

            // Next button
            const next = document.createElement('button');
            next.className = 'page-btn nav-arrows';
            next.innerHTML = "<i class='bx bx-chevron-right'></i>";
            next.disabled = currentPage === maxPage;
            next.onclick = () => { currentPage++; filterHistory(); };
            container.appendChild(next);
        }


        function changePage(dir) {
            currentPage += dir;
            filterHistory();
        }

        // ── Count-Up Animation ──
        function animateCountUp() {
            document.querySelectorAll('.count-up').forEach(el => {
                const target = +el.getAttribute('data-target');
                const duration = 1500;
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

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            filterHistory();
            animateCountUp();
        });

        // ── SweetAlert2 Confirms (Light Theme) ──
        document.querySelectorAll('.btn-approve-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.approve-form');
                Swal.fire({
                    title: 'Aprobar Puntos',
                    text: '¿Confirmas que deseas validar y acreditar estos puntos al cliente?',
                    icon: 'question',
                    background: '#ffffff',
                    color: '#111827',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#d1d5db',
                    confirmButtonText: '<i class="bx bx-check"></i> Sí, Aprobar',
                    cancelButtonText: 'Cancelar',
                    customClass: { popup: 'swal-light' }
                }).then(result => { 
                    if (result.isConfirmed) {
                        form.submit();
                    } 
                });
            });
        });

        document.querySelectorAll('.btn-reject-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.reject-form');
                Swal.fire({
                    title: 'Rechazar Puntos',
                    text: 'Esta acción anulará la asignación de puntos al cliente.',
                    icon: 'warning',
                    background: '#ffffff',
                    color: '#111827',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#d1d5db',
                    confirmButtonText: '<i class="bx bx-x"></i> Sí, Rechazar',
                    cancelButtonText: 'Cancelar',
                    customClass: { popup: 'swal-light' }
                }).then(result => { if (result.isConfirmed) form.submit(); });
            });
        });

        // ── Real-Time Polling ──
        let knownIds = [<?= empty($ventas) ? '' : implode(',', array_column($ventas, 'id')) ?>].map(String);

        function checkLiveAdmin() {
            fetch('<?= BASE_URL ?>panel/live-notifications?_t=' + Date.now(), {
                cache: 'no-store', credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success || !data.ventas) return;
                let nuevas = data.ventas.filter(r => !knownIds.includes(String(r.id)));
                if (!nuevas.length) return;
                nuevas.forEach(r => knownIds.push(String(r.id)));
                const first = nuevas[0];
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end',
                    showConfirmButton: false, timer: 3500, timerProgressBar: true,
                    background: '#ffffff', color: '#111827',
                    didOpen: t => { t.onclick = () => window.location.reload(); },
                    didClose: () => window.location.reload()
                });
                Toast.fire({
                    icon: 'info',
                    title: '¡Nuevos puntos pendientes!',
                    text: `El operador ${first.conductor_nombre} asignó ${first.puntos} pts a ${first.cliente_nombre}.`
                });
            })
            .catch(() => {});
        }

        setInterval(checkLiveAdmin, 4000);
    </script>
        </div> <!-- .container -->
    </div> <!-- .admin-layout -->
</body>
</html>
