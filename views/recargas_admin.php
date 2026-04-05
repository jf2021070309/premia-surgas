<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Recargas — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/recargas_admin.css">

</head>
<body>

    <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión Recargas';
            $pageSubtitle = 'Panel de verificación administrativa';
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
            $totalPendientes = count($recargas);
            $totalAprobados  = 0;
            $totalRechazados = 0;
            $montoTotal      = 0;
            if (!empty($historial)) {
                foreach ($historial as $h) {
                    if (($h['estado'] ?? '') === 'aprobado') { $totalAprobados++; $montoTotal += $h['monto']; }
                    if (($h['estado'] ?? '') === 'rechazado') $totalRechazados++;
                }
            }
        ?>
        <style>
            .dash-card { border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24); position: relative; overflow: hidden; color: white; display: flex; flex-direction: column; }
            .dash-card-body { padding: 15px 20px; flex: 1; position: relative; z-index: 2; min-height: 132px; }
            .dash-card-number { font-size: 2.5rem; font-weight: 700; margin-bottom: 2px; line-height: 1; font-family: 'Inter', sans-serif; }
            .dash-card-text { font-size: 0.95rem; margin-bottom: 0; font-weight: 400; letter-spacing: 0.3px; }
            
            .dash-card-icon { position: absolute; top: 50%; right: 15px; transform: translateY(-50%); font-size: 80px; color: rgba(255,255,255,0.12); z-index: 1; pointer-events: none; }
        </style>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            
            <div class="dash-card" style="background: #f97316;">
                <div class="dash-card-body">
                    <div class="dash-card-number count-up" data-target="<?= $totalPendientes ?>">0</div>
                    <div class="dash-card-text">Pendientes</div>
                </div>
                <i class='bx bxs-time-five dash-card-icon'></i>
            </div>

            <div class="dash-card" style="background: #400000;">
                <div class="dash-card-body">
                    <div class="dash-card-number count-up" data-target="<?= $totalAprobados ?>">0</div>
                    <div class="dash-card-text">Aprobados</div>
                </div>
                <i class='bx bxs-check-shield dash-card-icon'></i>
            </div>

            <div class="dash-card" style="background: #400000;">
                <div class="dash-card-body">
                    <div class="dash-card-number count-up" data-target="<?= $totalRechazados ?>">0</div>
                    <div class="dash-card-text">Rechazados</div>
                </div>
                <i class='bx bxs-error-circle dash-card-icon'></i>
            </div>

            <div class="dash-card" style="background: #400000;">
                <div class="dash-card-body">
                    <div class="dash-card-number" style="display: flex; align-items: baseline; gap: 4px;">
                        <span style="font-size: 0.5em; font-weight: 800; position: relative; top: -5px;">S/</span> <span class="count-up" data-target="<?= $montoTotal ?>">0</span>
                    </div>
                    <div class="dash-card-text">Monto Acreditado</div>
                </div>
                <i class='bx bxs-bank dash-card-icon'></i>
            </div>

        </div>

        <!-- Dashboard Modular Grid -->
        <div class="dashboard-top-grid">
            
            <!-- COLUMN 1 — Configuración QR -->
            <div class="card qr-card" style="margin-bottom:0;">
            <div class="card-header" onclick="toggleQR()">
                <div class="header-title-flex">
                    <i class='bx bx-qr-scan'></i>
                    <div class="title-text-group">
                        <h3>Configuración QR de Pago</h3>
                        <span>Sube tu código Yape para recibir recargas</span>
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:0.65rem;">
                    <?php if ($qrActual): ?>
                        <span class="chip chip-approved"><i class='bx bxs-circle'></i> Activo</span>
                    <?php else: ?>
                        <span class="chip chip-rejected"><i class='bx bxs-circle'></i> Inactivo</span>
                    <?php endif; ?>
                    <i id="toggleIcon" class='bx bx-chevron-down toggle-chevron' style="font-size:1.25rem;"></i>
                </div>
            </div>

            <div id="qrSectionBody" class="qr-section-body">
                <div class="qr-grid">
                    <!-- Left — Yape Purple Panel -->
                    <div class="qr-preview-box">
                        <img src="<?= BASE_URL ?>assets/premios/yape.png" alt="Yape" class="yape-logo-img">
                        <div class="qr-frame">
                            <?php if ($qrActual): ?>
                                <img src="<?= BASE_URL ?>assets/uploads/qr/<?= htmlspecialchars($qrActual) ?>" alt="QR Yape">
                            <?php else: ?>
                                <div class="qr-empty-frame">
                                    <i class='bx bx-image-add' style="font-size:2rem;"></i>
                                    <span style="font-size:0.7rem;">Sin QR</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="yape-cta-pill" id="yapePreviewName"><i class='bx bx-check-double'></i> <?= htmlspecialchars($nombreTitular) ?></div>
                    </div>

                    <!-- Upload -->
                    <div class="upload-section">
                        <div class="upload-title">Configuración de Yape</div>
                        <p class="upload-subtitle">Define el nombre que verán los clientes y sube tu código QR para recibir pagos.</p>
                        
                        <form action="<?= BASE_URL ?>recargas-admin/subir-qr" method="POST" enctype="multipart/form-data">
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Nombre del Titular:</label>
                                <div style="position: relative; display: flex; align-items: center;">
                                    <i class='bx bx-user-circle' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                    <input type="text" name="yape_nombre" id="yapeNameInput" value="<?= htmlspecialchars($nombreTitular) ?>" 
                                           placeholder="Ej: Juan Perez"
                                           style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline:none; transition: border-color 0.2s; background: #fff;"
                                           onkeyup="updateYapePreview(this.value)">
                                </div>
                            </div>
                            <label for="qr_file_input" id="qrDropZone">
                                <img id="qrPreviewImg" src="" alt="" style="display:none; width:80px; height:80px; object-fit:contain; border-radius:12px; margin-bottom:0.5rem; border:2px solid #e2e8f0; padding:4px; background:#fff;">
                                <i id="qrUploadIcon" class='bx bx-cloud-upload'></i>
                                <div id="qrUploadLabel" class="dz-label">Selecciona una imagen</div>
                                <div id="qrUploadHint" class="dz-hint">Formatos: JPG, PNG o WebP (Máx 2MB)</div>
                            </label>
                            <input type="file" id="qr_file_input" name="qr_imagen" accept="image/*" style="display:none;">
                            <button type="submit" id="qrSubmitBtn" <?= $nombreTitular ? '' : 'disabled' ?>>
                                <i class='bx bx-check-circle'></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
                </div>
            </div> <!-- End COLUMN 1 -->

            <!-- COLUMN 2 — Revisión Pendiente -->
            <!-- COLUMN 2 — Revisión Pendiente -->

        <!-- ════════════════════════════════════════════
             SECTION 2 — Revisión Pendiente
        ════════════════════════════════════════════ -->
        <?php $numPendientes = count($recargas); ?>
        <div class="card" style="margin-bottom:0;">
            <div class="card-header" onclick="togglePending()">
                <div class="header-title-flex">
                    <i class='bx bx-time-five'></i>
                    <div class="title-text-group">
                        <h3>Revisión Pendiente</h3>
                        <span>Comprobantes esperando veracidad de abono</span>
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:0.65rem;">
                    <span class="pending-badge"><?= $numPendientes ?> pendiente<?= $numPendientes !== 1 ? 's' : '' ?></span>
                    <i id="pendingChevron" class='bx bx-chevron-down toggle-chevron <?= $numPendientes > 0 ? 'open' : '' ?>' style="font-size:1.25rem;"></i>
                </div>
            </div>

            <div id="pendingSectionBody" class="pending-section-body <?= $numPendientes > 0 ? 'open' : '' ?>">
                <?php if (empty($recargas)): ?>
                    <div class="empty-state-compact">
                        <i class='bx bxs-check-shield'></i>
                        <div class="empty-text">
                            <strong>¡Todo al día!</strong>
                            <span>No hay comprobantes pendientes de verificar en este momento.</span>
                        </div>
                    </div>
                <?php else: ?>
                    <ul class="ticket-list">
                        <?php foreach ($recargas as $r): ?>
                        <li class="ticket-card">
                            <div class="ticket-avatar"><?= strtoupper(substr($r['cliente_nombre'], 0, 1)) ?></div>

                            <div class="ticket-info">
                                <div class="ticket-name"><?= htmlspecialchars($r['cliente_nombre']) ?></div>
                                <div class="ticket-detail">
                                    <span class="ticket-detail-item">
                                        <i class='bx bx-phone'></i>
                                        <?= htmlspecialchars($r['cliente_celular']) ?>
                                    </span>
                                    <span class="detail-sep">•</span>
                                    <span class="ticket-detail-item">
                                        <i class='bx bx-id-card'></i>
                                        DNI <?= htmlspecialchars($r['cliente_dni']) ?>
                                        <button class="copy-btn" onclick="copyToClipboard('<?= $r['cliente_dni'] ?>', event)" title="Copiar DNI">
                                            <i class='bx bx-copy'></i>
                                        </button>
                                    </span>
                                    <span class="detail-sep">•</span>
                                    <span class="ticket-detail-item">
                                        <i class='bx bx-time'></i>
                                        <?= date('d M, g:i a', strtotime($r['fecha'])) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="ticket-amounts">
                                <div class="pts-val"><i class='bx bxs-up-arrow-circle'></i> <?= number_format($r['puntos']) ?> pts</div>
                                <div class="monto-val">S/ <?= number_format($r['monto'], 2) ?></div>
                            </div>

                            <div class="ticket-actions">
                                <button class="btn btn-outline" onclick="openModal('<?= BASE_URL ?>assets/uploads/comprobantes/<?= $r['comprobante'] ?>')">
                                    <i class='bx bx-image'></i> Evidencia
                                </button>

                                <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="margin:0;" class="approve-form">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="estado" value="aprobado">
                                    <button type="button" class="btn btn-success btn-approve-trigger">
                                        <i class='bx bx-check'></i> Aprobar
                                    </button>
                                </form>

                                <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="margin:0;" class="reject-form">
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
        </div>

        <!-- ════════════════════════════════════════════
             SECTION 3 — Historial de Movimientos
        ════════════════════════════════════════════ -->
        <div class="modern-section-header" style="margin-top: 3.5rem;">
            <div class="section-title-flex">
                <div class="section-title-text">
                    <h3>Historial de Movimientos</h3>
                    <span>Panel de verificación administrativa de recargas</span>
                </div>
            </div>
            <div class="section-actions">
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
                    </select>
                </div>
                <div class="header-search-modern" style="width: 280px;">
                    <i class='bx bx-search'></i>
                    <input type="text" id="historySearch" placeholder="Buscar" onkeyup="filterHistory()">
                </div>
            </div>
        </div>


        <div class="card">
            <div class="table-wrapper">



            <?php if (empty($historial)): ?>
                <div class="empty-state">
                    <div class="empty-icon" style="color: var(--primary);"><i class='bx bx-spreadsheet'></i></div>
                    <h3>Sin movimientos todavía</h3>
                    <p>Cuando los clientes realicen recargas, aparecerán aquí con todo su detalle.</p>
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
                                <th style="text-align: center !important;">Estado</th>
                                <th class="text-center">Evidencia</th>
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
                                        <?= date('d M Y', strtotime($h['fecha'])) ?>
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
                                <td style="text-align: center !important;"><span class="chip <?= $chipClass ?>" ><i class='bx bxs-circle'></i> <?= ucfirst($h['estado'] ?? 'pendiente') ?></span></td>
                                <td class="text-center">
                                    <?php if (!empty($h['comprobante'])): ?>
                                        <button class="btn-action indigo" onclick="openModal('<?= BASE_URL ?>assets/uploads/comprobantes/<?= $h['comprobante'] ?>')" title="Ver Comprobante">
                                            <i class='bx bx-show'></i>
                                        </button>
                                    <?php else: ?>
                                        <span style="color:var(--on-light); font-size:0.78rem;">—</span>
                                    <?php endif; ?>
                                </td>
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

    <!-- ════ Image Modal ════ -->
    <div class="img-modal" id="receiptModal">
        <div class="img-modal-inner">
            <div class="img-modal-header">
                <h3><i class='bx bx-receipt'></i> Evidencia de Pago</h3>
                <button class="modal-close-btn" onclick="closeModal()"><i class='bx bx-x'></i></button>
            </div>
            <div class="img-modal-body">
                <img id="receiptImage" src="" alt="Comprobante de pago">
            </div>
        </div>
    </div>

    <script>
        // ── QR Panel Toggle ──
        function toggleQR() {
            const body = document.getElementById('qrSectionBody');
            const icon = document.getElementById('toggleIcon');
            body.classList.toggle('open');
            icon.classList.toggle('open');
        }

        function togglePending() {
            const body = document.getElementById('pendingSectionBody');
            const icon = document.getElementById('pendingChevron');
            body.classList.toggle('open');
            icon.classList.toggle('open');
        }

        // ── QR Upload UX ──
        (function() {
            const input   = document.getElementById('qr_file_input');
            const zone    = document.getElementById('qrDropZone');
            const btn     = document.getElementById('qrSubmitBtn');
            const preview = document.getElementById('qrPreviewImg');
            const icon    = document.getElementById('qrUploadIcon');
            const lbl     = document.getElementById('qrUploadLabel');

            function showPreview(file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    icon.style.display = 'none';
                    document.getElementById('qrUploadHint').style.display = 'none';
                    lbl.textContent = file.name;
                    btn.disabled = false;
                };
                reader.readAsDataURL(file);
            }

            input.addEventListener('change', () => { if (input.files[0]) showPreview(input.files[0]); });
            zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
            zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
            zone.addEventListener('drop', e => {
                e.preventDefault(); zone.classList.remove('dragover');
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    const dt = new DataTransfer(); dt.items.add(file);
                    input.files = dt.files; showPreview(file);
                }
            });
        })();

        // ── Yape Real-time Preview ──
        function updateYapePreview(val) {
            const preview = document.getElementById('yapePreviewName');
            const btn = document.getElementById('qrSubmitBtn');
            if (preview) {
                preview.innerHTML = `<i class='bx bx-check-double'></i> ${val || 'Paga aquí con Yape'}`;
            }
            if (val.trim().length > 0) btn.disabled = false;
        }

        // ── Receipt Modal ──
        const modal = document.getElementById('receiptModal');
        const img   = document.getElementById('receiptImage');

        function openModal(src) {
            img.src = src;
            modal.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('is-active');
            document.body.style.overflow = '';
            setTimeout(() => img.src = '', 250);
        }

        modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // ── Quick Copy ──
        function copyToClipboard(text, e) {
            e.stopPropagation();
            navigator.clipboard.writeText(text);
            const btn = e.currentTarget;
            const icon = btn.querySelector('i');
            icon.classList.replace('bx-copy', 'bx-check');
            btn.style.color = 'var(--green)';
            setTimeout(() => {
                icon.classList.replace('bx-check', 'bx-copy');
                btn.style.color = '';
            }, 1500);
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
                const rowDate = row.querySelector('.date-text').textContent; // Format: "dd M Y, H:i"
                
                // Match search
                const matchesSearch = name.includes(query);
                // Match status
                const matchesStatus = status === "" || rowStatus.includes(status);
                // Match date (simplistic date check, rowDate contains "25 Mar 2026")
                let matchesDate = true;
                if (dateVal) {
                    const [y, m, d] = dateVal.split('-');
                    const dateObj = new Date(y, m - 1, d);
                    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]; // match your data format
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
                    title: 'Verificar Abono',
                    text: '¿Confirmaste que el dinero ingresó a tu cuenta bancaria?',
                    icon: 'question',
                    background: '#ffffff',
                    color: '#111827',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#d1d5db',
                    confirmButtonText: '<i class="bx bx-check"></i> Sí, Acreditar Puntos',
                    cancelButtonText: 'Cancelar',
                    customClass: { popup: 'swal-light' }
                }).then(result => { if (result.isConfirmed) form.submit(); });
            });
        });

        document.querySelectorAll('.btn-reject-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.reject-form');
                Swal.fire({
                    title: 'Rechazar Comprobante',
                    text: 'Esta acción anulará la solicitud de puntos del cliente.',
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
        let knownIds = [<?= empty($recargas) ? '' : implode(',', array_column($recargas, 'id')) ?>].map(String);

        function checkLiveAdmin() {
            fetch('<?= BASE_URL ?>panel/live-notifications?_t=' + Date.now(), {
                cache: 'no-store', credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success || !data.recargas) return;
                let nuevas = data.recargas.filter(r => !knownIds.includes(String(r.id)));
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
                    title: '¡Nueva Recarga!',
                    text: `${first.cliente_nombre} solicita +${first.puntos} pts.`
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
