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
    <style>
        /* ── Design Tokens · Light Mode ── */
        :root {
            --bg:           #f4f5f7;
            --surface:      #ffffff;
            --surface-low:  #f9fafb;
            --surface-hi:   #f0f1f3;
            --surface-br:   #e5e7eb;
            --on-surface:   #1a1d26;
            --on-muted:     #6b7280;
            --on-light:     #9ca3af;
            --outline:      rgba(0,0,0,0.07);
            --outline-med:  rgba(0,0,0,0.10);
            --primary:      #e86a10;
            --primary-soft: #fff3e8;
            --primary-dim:  #d45e0e;
            --primary-glow: rgba(232,106,16,0.18);
            --green:        #16a34a;
            --green-soft:   #ecfdf5;
            --green-border: #bbf7d0;
            --amber:        #d97706;
            --amber-soft:   #fffbeb;
            --red:          #dc2626;
            --red-soft:     #fef2f2;
            --red-border:   #fecaca;
            --radius-sm:    8px;
            --radius-md:    12px;
            --radius-lg:    16px;
            --shadow-sm:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:    0 4px 12px rgba(0,0,0,0.06), 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg:    0 10px 30px rgba(0,0,0,0.08), 0 4px 10px rgba(0,0,0,0.04);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg);
            font-family: 'Inter', sans-serif;
            color: var(--on-surface);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Sticky Top Nav ── */
        .top-nav {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--outline);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 200;
        }

        .back-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            color: var(--on-muted); text-decoration: none;
            font-weight: 500; font-size: 0.875rem;
            padding: 8px 14px;
            border-radius: var(--radius-sm);
            transition: all 0.2s;
        }
        .back-btn:hover { color: var(--on-surface); background: var(--surface-hi); }

        .page-title {
            font-size: 1.05rem; font-weight: 700;
            letter-spacing: -0.02em; color: var(--on-surface);
        }

        .nav-right { display: flex; align-items: center; gap: 0.75rem; }

        /* ── Main Layout ── */
        .container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        /* ── Premium Card ── */
        .card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--outline);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .card:hover { box-shadow: var(--shadow-md); }

        .card-header {
            padding: 1.15rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--outline);
        }

        .card-title {
            display: flex; align-items: center; gap: 0.6rem;
            font-size: 0.925rem; font-weight: 700;
            letter-spacing: -0.01em; color: var(--on-surface);
        }

        .card-title i { font-size: 1.2rem; color: var(--primary); }

        /* ── QR Section ── */
        .qr-section-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.45s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .qr-section-body.open { max-height: 550px; }

        .qr-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            padding: 1.75rem 1.5rem;
            align-items: start;
        }

        .qr-preview-box {
            display: flex; flex-direction: column; align-items: center; gap: 1rem;
        }

        .qr-preview-label {
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--on-muted);
        }

        .qr-frame {
            background: linear-gradient(145deg, #e86a10, #f59e0b);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            box-shadow: 0 8px 24px var(--primary-glow), 0 4px 12px rgba(0,0,0,0.08);
        }

        .qr-frame img {
            width: 170px; height: 170px;
            object-fit: contain;
            border-radius: var(--radius-sm);
            background: #fff;
            display: block;
            padding: 6px;
        }

        .qr-empty-frame {
            width: 170px; height: 170px;
            background: var(--surface-hi);
            border-radius: var(--radius-sm);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: var(--on-light);
            gap: 0.5rem;
        }

        .qr-status-tag {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.78rem; font-weight: 600; padding: 5px 14px;
            border-radius: 100px;
        }
        .tag-active { background: var(--green-soft); color: var(--green); border: 1px solid var(--green-border); }
        .tag-inactive { background: var(--amber-soft); color: var(--amber); border: 1px solid rgba(217,119,6,0.2); }

        /* Upload Zone */
        .upload-label-text {
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--on-muted); margin-bottom: 0.75rem;
        }

        #qrDropZone {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            border: 2px dashed rgba(232,106,16,0.35);
            border-radius: var(--radius-md);
            padding: 2rem 1rem;
            cursor: pointer;
            background: var(--primary-soft);
            transition: all 0.3s;
            text-align: center;
        }
        #qrDropZone:hover, #qrDropZone.dragover {
            border-color: var(--primary);
            background: rgba(232,106,16,0.08);
        }
        #qrDropZone i { font-size: 2.2rem; color: var(--primary); }
        #qrDropZone .dz-label { font-weight: 600; font-size: 0.9rem; color: var(--on-surface); margin-top: 0.5rem; }
        #qrDropZone .dz-sub { font-size: 0.78rem; color: var(--on-muted); margin-top: 0.25rem; }
        #qrDropZone .dz-hint {
            font-size: 0.65rem; margin-top: 0.75rem;
            background: rgba(232,106,16,0.12); color: var(--primary-dim);
            padding: 3px 10px; border-radius: 100px;
            font-weight: 600;
        }

        #qrSubmitBtn {
            width: 100%; margin-top: 1rem;
            padding: 0.75rem;
            border-radius: var(--radius-sm);
            font-weight: 700; font-size: 0.875rem;
            background: linear-gradient(135deg, var(--primary-dim), var(--primary));
            border: none; color: #fff;
            cursor: pointer; opacity: 0.45;
            transition: all 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        #qrSubmitBtn:not(:disabled) { opacity: 1; box-shadow: 0 4px 16px var(--primary-glow); }
        #qrSubmitBtn:not(:disabled):hover { filter: brightness(1.08); transform: translateY(-1px); }

        .toggle-icon { transition: transform 0.35s; color: var(--on-muted) !important; }
        .toggle-icon.open { transform: rotate(90deg); }

        /* ── Pending Section ── */
        .pending-header {
            padding: 1.15rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--outline);
        }

        .pending-title {
            display: flex; align-items: center; gap: 0.7rem;
            font-size: 0.925rem; font-weight: 700;
        }

        .pulse-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--red);
            box-shadow: 0 0 0 0 rgba(220,38,38,0.5);
            animation: pulse 1.8s infinite;
        }
        @keyframes pulse {
            0%   { box-shadow: 0 0 0 0 rgba(220,38,38,0.5); }
            70%  { box-shadow: 0 0 0 8px rgba(220,38,38,0); }
            100% { box-shadow: 0 0 0 0 rgba(220,38,38,0); }
        }

        .count-badge {
            background: var(--red-soft);
            color: var(--red);
            border: 1px solid var(--red-border);
            font-size: 0.72rem; font-weight: 700;
            padding: 3px 10px; border-radius: 100px;
            letter-spacing: 0.02em;
        }

        /* Ticket Cards */
        .ticket-list { padding: 0.75rem; display: flex; flex-direction: column; gap: 0.6rem; list-style: none; }

        .ticket-card {
            background: var(--surface-low);
            border-radius: var(--radius-md);
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.2s;
            border: 1px solid var(--outline);
        }
        .ticket-card:hover { background: var(--surface-hi); border-color: var(--outline-med); box-shadow: var(--shadow-sm); }

        .ticket-avatar {
            width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), #f59e0b);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1.1rem; color: #fff;
            box-shadow: 0 2px 8px var(--primary-glow);
        }

        .ticket-info { flex: 1; min-width: 0; }
        .ticket-name { font-weight: 700; font-size: 0.925rem; color: var(--on-surface); }
        .ticket-meta { font-size: 0.78rem; color: var(--on-muted); margin-top: 2px; }
        .ticket-time { font-size: 0.72rem; color: var(--on-light); margin-top: 4px; display: flex; align-items: center; gap: 4px; }

        .ticket-amounts { text-align: right; margin-right: 1rem; flex-shrink: 0; }
        .pts-val { font-size: 1rem; font-weight: 700; color: var(--green); display: flex; align-items: center; gap: 4px; justify-content: flex-end; }
        .monto-val { font-size: 0.78rem; color: var(--on-muted); margin-top: 2px; }

        .ticket-actions { display: flex; gap: 0.5rem; flex-shrink: 0; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 0.35rem;
            border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600;
            padding: 7px 14px; cursor: pointer; border: 1px solid transparent;
            transition: all 0.2s; text-decoration: none;
        }

        .btn-ghost {
            background: var(--surface);
            border-color: var(--outline-med);
            color: var(--on-muted);
        }
        .btn-ghost:hover { color: var(--on-surface); background: var(--surface-hi); border-color: var(--surface-br); }

        .btn-approve {
            background: var(--green-soft);
            border-color: var(--green-border);
            color: var(--green);
        }
        .btn-approve:hover { background: var(--green); color: white; border-color: var(--green); box-shadow: 0 2px 8px rgba(22,163,74,0.25); }

        .btn-reject {
            background: transparent;
            border-color: transparent;
            color: rgba(220,38,38,0.5);
            padding: 7px 8px;
        }
        .btn-reject:hover { background: var(--red-soft); color: var(--red); border-radius: var(--radius-sm); }

        /* Empty State */
        .empty-state {
            padding: 3.5rem 1.5rem;
            text-align: center;
            display: flex; flex-direction: column; align-items: center; gap: 0.75rem;
        }
        .empty-state i { font-size: 2.5rem; color: var(--surface-br); }
        .empty-state h3 { font-size: 0.95rem; color: var(--on-muted); font-weight: 600; }
        .empty-state p { font-size: 0.82rem; color: var(--on-light); }

        /* ── History Table ── */
        .table-wrapper { overflow-x: auto; }

        .data-table {
            width: 100%; border-collapse: collapse; text-align: left;
        }

        .data-table thead tr {
            border-bottom: 1px solid var(--outline-med);
        }

        .data-table th {
            padding: 0.9rem 1.25rem;
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--on-muted);
            background: var(--surface-low);
            white-space: nowrap;
        }

        .data-table td {
            padding: 0.9rem 1.25rem;
            font-size: 0.85rem;
            color: var(--on-surface);
            border-bottom: 1px solid var(--outline);
            vertical-align: middle;
        }

        .data-table tbody tr { transition: background 0.15s; }
        .data-table tbody tr:hover { background: var(--surface-low); }
        .data-table tbody tr:last-child td { border-bottom: none; }

        /* Row client info */
        .row-client { display: flex; align-items: center; gap: 0.6rem; }
        .row-avatar {
            width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), #f59e0b);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 700; color: #fff;
        }
        .client-name { font-weight: 600; font-size: 0.85rem; }

        .pts-positive { color: var(--green); font-weight: 700; font-size: 0.85rem; }

        /* Status Chips */
        .chip {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 0.7rem; font-weight: 700;
            padding: 3px 10px; border-radius: 100px; white-space: nowrap;
        }
        .chip::before {
            content: ''; width: 5px; height: 5px; border-radius: 50%;
            background: currentColor; flex-shrink: 0;
        }
        .chip-pending { background: var(--amber-soft); color: var(--amber); border: 1px solid rgba(217,119,6,0.15); }
        .chip-approved { background: var(--green-soft); color: var(--green); border: 1px solid var(--green-border); }
        .chip-rejected { background: var(--red-soft); color: var(--red); border: 1px solid var(--red-border); }

        .btn-view-sm {
            display: inline-flex; align-items: center; gap: 0.3rem;
            background: var(--surface); border: 1px solid var(--outline-med);
            color: var(--on-muted); border-radius: 6px;
            font-size: 0.72rem; font-weight: 600; padding: 4px 10px;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-view-sm:hover { color: var(--on-surface); background: var(--surface-hi); }

        .date-text { font-size: 0.8rem; color: var(--on-muted); white-space: nowrap; }

        /* ── Image Modal ── */
        .img-modal {
            display: none; position: fixed; z-index: 1000;
            inset: 0; background: rgba(0,0,0,0.45);
            align-items: center; justify-content: center; padding: 2rem;
            backdrop-filter: blur(8px);
        }
        .img-modal.is-active { display: flex; }
        .img-modal-inner {
            background: var(--surface);
            border: 1px solid var(--outline);
            border-radius: var(--radius-lg);
            max-width: 600px; width: 100%;
            animation: fadeUp 0.25s ease;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        @keyframes fadeUp {
            from { transform: translateY(20px) scale(0.97); opacity: 0; }
            to   { transform: translateY(0) scale(1); opacity: 1; }
        }
        .img-modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--outline);
            display: flex; justify-content: space-between; align-items: center;
        }
        .img-modal-header h3 { font-size: 0.95rem; font-weight: 700; color: var(--on-surface); }
        .modal-close-btn {
            background: var(--surface-hi); border: 1px solid var(--outline);
            color: var(--on-muted); border-radius: 6px;
            font-size: 1.1rem; width: 32px; height: 32px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .modal-close-btn:hover { background: var(--surface-br); color: var(--on-surface); }
        .img-modal-body { padding: 1.25rem; text-align: center; }
        .img-modal-body img { max-width: 100%; border-radius: var(--radius-sm); max-height: 60vh; object-fit: contain; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .qr-grid { grid-template-columns: 1fr; }
            .ticket-card { flex-wrap: wrap; }
            .ticket-actions { width: 100%; justify-content: space-between; }
            .ticket-amounts { margin-right: 0; }
            .container { padding: 0 1rem; margin: 1rem auto; }
            .top-nav { padding: 0 1rem; }
        }
    </style>
</head>
<body>

    <!-- Top Nav -->
    <nav class="top-nav">
        <a href="<?= BASE_URL ?>panel" class="back-btn">
            <i class='bx bx-arrow-back'></i> Volver
        </a>
        <span class="page-title">Verificación de Recargas</span>
        <div class="nav-right" style="width:80px; justify-content: flex-end;">
            <!-- placeholder for symmetry -->
        </div>
    </nav>

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
             SECTION 1 — QR Yape Manager
        ════════════════════════════════════════════ -->
        <div class="card">
            <div class="card-header" style="cursor:pointer;" onclick="toggleQR()">
                <div class="card-title">
                    <i class='bx bx-qr'></i>
                    Configuración QR de Pago Yape
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <?php if ($qrActual): ?>
                        <span class="chip chip-approved">Activo</span>
                    <?php else: ?>
                        <span class="chip chip-pending">Sin configurar</span>
                    <?php endif; ?>
                    <i id="toggleIcon" class='bx bx-chevron-right toggle-icon' style="font-size:1.2rem;"></i>
                </div>
            </div>

            <div id="qrSectionBody" class="qr-section-body">
                <div class="qr-grid">
                    <!-- Preview -->
                    <div class="qr-preview-box">
                        <span class="qr-preview-label">QR Actual</span>
                        <div class="qr-frame">
                            <?php if ($qrActual): ?>
                                <img src="<?= BASE_URL ?>assets/uploads/qr/<?= htmlspecialchars($qrActual) ?>" alt="QR Yape">
                            <?php else: ?>
                                <div class="qr-empty-frame">
                                    <i class='bx bx-image' style="font-size:2.5rem;"></i>
                                    <span style="font-size:0.78rem;">Sin imagen</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($qrActual): ?>
                            <span class="qr-status-tag tag-active"><i class='bx bx-check-circle'></i> Visible para clientes</span>
                        <?php else: ?>
                            <span class="qr-status-tag tag-inactive"><i class='bx bx-error-circle'></i> Sube un QR primero</span>
                        <?php endif; ?>
                    </div>

                    <!-- Upload -->
                    <div>
                        <p class="upload-label-text">Cambiar imagen QR</p>
                        <form action="<?= BASE_URL ?>recargas-admin/subir-qr" method="POST" enctype="multipart/form-data">
                            <label for="qr_file_input" id="qrDropZone">
                                <img id="qrPreviewImg" src="" alt="" style="display:none; width:100px; height:100px; object-fit:contain; border-radius:8px; margin-bottom:0.75rem; border:1px solid var(--outline);">
                                <i id="qrUploadIcon" class='bx bxs-cloud-upload'></i>
                                <span id="qrUploadLabel" class="dz-label">Arrastra tu imagen aquí</span>
                                <span class="dz-sub">o haz clic para buscarla</span>
                                <span class="dz-hint">JPG · PNG · GIF · WebP</span>
                            </label>
                            <input type="file" id="qr_file_input" name="qr_imagen" accept="image/*" style="display:none;">
                            <button type="submit" id="qrSubmitBtn" disabled>
                                <i class='bx bx-upload'></i> Subir QR de Yape
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════════════════
             SECTION 2 — Revisión Pendiente
        ════════════════════════════════════════════ -->
        <div class="card">
            <div class="pending-header">
                <div class="pending-title">
                    <div class="pulse-dot"></div>
                    Revisión Pendiente
                    <span class="count-badge"><?= count($recargas) ?> pendiente<?= count($recargas) !== 1 ? 's' : '' ?></span>
                </div>
            </div>

            <?php if (empty($recargas)): ?>
                <div class="empty-state">
                    <i class='bx bx-check-shield'></i>
                    <h3>Todo al día</h3>
                    <p>No hay comprobantes pendientes de verificar.</p>
                </div>
            <?php else: ?>
                <ul class="ticket-list">
                    <?php foreach ($recargas as $r): ?>
                    <li class="ticket-card">
                        <div class="ticket-avatar"><?= strtoupper(substr($r['cliente_nombre'], 0, 1)) ?></div>

                        <div class="ticket-info">
                            <div class="ticket-name"><?= htmlspecialchars($r['cliente_nombre']) ?></div>
                            <div class="ticket-meta"><?= htmlspecialchars($r['cliente_celular']) ?> · DNI <?= htmlspecialchars($r['cliente_dni']) ?></div>
                            <div class="ticket-time">
                                <i class='bx bx-time'></i>
                                <?= date('d M, g:i a', strtotime($r['fecha'])) ?>
                            </div>
                        </div>

                        <div class="ticket-amounts">
                            <div class="pts-val"><i class='bx bxs-up-arrow-circle'></i> <?= number_format($r['puntos']) ?> pts</div>
                            <div class="monto-val">S/ <?= number_format($r['monto'], 2) ?></div>
                        </div>

                        <div class="ticket-actions">
                            <button class="btn btn-ghost" onclick="openModal('<?= BASE_URL ?>assets/uploads/comprobantes/<?= $r['comprobante'] ?>')">
                                <i class='bx bx-receipt'></i> Ver Pago
                            </button>

                            <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="margin:0;" class="approve-form">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <input type="hidden" name="estado" value="aprobado">
                                <button type="button" class="btn btn-approve btn-approve-trigger">
                                    <i class='bx bx-check'></i> Aprobar
                                </button>
                            </form>

                            <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="margin:0;" class="reject-form">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <input type="hidden" name="estado" value="rechazado">
                                <button type="button" class="btn btn-reject btn-reject-trigger" title="Rechazar">
                                    <i class='bx bx-x'></i>
                                </button>
                            </form>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- ════════════════════════════════════════════
             SECTION 3 — Historial de Movimientos
        ════════════════════════════════════════════ -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class='bx bx-history'></i>
                    Historial de Movimientos
                </div>
            </div>

            <?php if (empty($historial)): ?>
                <div class="empty-state">
                    <i class='bx bx-spreadsheet'></i>
                    <h3>Sin historial</h3>
                    <p>Aquí aparecerán todas las recargas procesadas.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Puntos</th>
                                <th>Monto</th>
                                <th>Evidencia</th>
                                <th>Estado</th>
                                <th>Responsable</th>
                                <th>Fecha y Hora</th>
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
                                <td>
                                    <div class="row-client">
                                        <div class="row-avatar"><?= strtoupper(substr($h['cliente_nombre'] ?? 'C', 0, 1)) ?></div>
                                        <span class="client-name"><?= htmlspecialchars($h['cliente_nombre'] ?? '-') ?></span>
                                    </div>
                                </td>
                                <td><span class="pts-positive">+<?= number_format($h['puntos']) ?> pts</span></td>
                                <td style="color: var(--on-muted);">S/ <?= number_format($h['monto'], 2) ?></td>
                                <td>
                                    <?php if (!empty($h['comprobante'])): ?>
                                        <button class="btn-view-sm" onclick="openModal('<?= BASE_URL ?>assets/uploads/comprobantes/<?= $h['comprobante'] ?>')">
                                            <i class='bx bx-image'></i> Ver
                                        </button>
                                    <?php else: ?>
                                        <span style="color:var(--on-light); font-size:0.8rem;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="chip <?= $chipClass ?>"><?= ucfirst($h['estado'] ?? 'pendiente') ?></span></td>
                                <td style="color: var(--on-muted); font-size:0.82rem;"><?= htmlspecialchars($h['validador_nombre'] ?? '—') ?></td>
                                <td class="date-text"><?= date('d M Y, H:i', strtotime($h['fecha'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div><!-- .container -->

    <!-- Image Modal -->
    <div class="img-modal" id="receiptModal">
        <div class="img-modal-inner">
            <div class="img-modal-header">
                <h3>Evidencia de Pago</h3>
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
            setTimeout(() => img.src = '', 200);
        }

        modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // ── SweetAlert2 Confirms (Light Theme) ──
        document.querySelectorAll('.btn-approve-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.approve-form');
                Swal.fire({
                    title: 'Verificar Abono',
                    text: '¿Confirmaste que el dinero ingresó a tu cuenta bancaria?',
                    icon: 'question',
                    background: '#ffffff',
                    color: '#1a1d26',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Sí, Acreditar Puntos',
                    cancelButtonText: 'Cancelar'
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
                    color: '#1a1d26',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Sí, Rechazar',
                    cancelButtonText: 'Cancelar'
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
                    background: '#ffffff', color: '#1a1d26',
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
</body>
</html>
