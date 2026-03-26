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
        /* ══════════════════════════════════════
           Design Tokens · Elegant Light Mode
        ══════════════════════════════════════ */
        :root {
            --bg:            #f0f2f5;
            --surface:       #ffffff;
            --surface-low:   #f8f9fb;
            --surface-hi:    #f3f4f6;
            --surface-br:    #e5e7eb;
            --on-surface:    #111827;
            --on-secondary:  #374151;
            --on-muted:      #6b7280;
            --on-light:      #9ca3af;
            --outline:       rgba(0,0,0,0.06);
            --outline-med:   rgba(0,0,0,0.10);
            --primary:       #e86a10;
            --primary-soft:  #fff7ed;
            --primary-dim:   #c2590d;
            --primary-glow:  rgba(232,106,16,0.15);
            --green:         #059669;
            --green-soft:    #ecfdf5;
            --green-border:  #a7f3d0;
            --amber:         #d97706;
            --amber-soft:    #fffbeb;
            --amber-border:  #fde68a;
            --red:           #dc2626;
            --red-soft:      #fef2f2;
            --red-border:    #fecaca;
            --blue:          #2563eb;
            --blue-soft:     #eff6ff;
            --blue-border:   #bfdbfe;
            --radius-xs:     6px;
            --radius-sm:     8px;
            --radius-md:     12px;
            --radius-lg:     16px;
            --radius-xl:     20px;
            --shadow-xs:     0 1px 2px rgba(0,0,0,0.04);
            --shadow-sm:     0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:     0 4px 14px rgba(0,0,0,0.06), 0 2px 6px rgba(0,0,0,0.03);
            --shadow-lg:     0 10px 30px rgba(0,0,0,0.08);
            --transition:    0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--on-surface);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ══════════════════════════════════════
           Sticky Top Nav
        ══════════════════════════════════════ */
        .top-nav {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
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
            display: inline-flex; align-items: center; gap: 0.45rem;
            color: var(--on-muted); text-decoration: none;
            font-weight: 500; font-size: 0.85rem;
            padding: 7px 14px;
            border-radius: var(--radius-sm);
            transition: all var(--transition);
        }
        .back-btn i { font-size: 1.15rem; }
        .back-btn:hover { color: var(--on-surface); background: var(--surface-hi); }

        .page-title-group { text-align: center; }
        .page-title {
            font-size: 1rem; font-weight: 700;
            letter-spacing: -0.025em; color: var(--on-surface);
        }
        .page-subtitle {
            font-size: 0.7rem; color: var(--on-light);
            font-weight: 500; letter-spacing: 0.02em;
        }

        /* ══════════════════════════════════════
           Main Layout
        ══════════════════════════════════════ */
        .container {
            max-width: 1140px;
            margin: 1.75rem auto;
            padding: 0 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* ══════════════════════════════════════
           Stats Row — Icon Boxes
        ══════════════════════════════════════ */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--outline);
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            box-shadow: var(--shadow-xs);
            transition: all var(--transition);
        }
        .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }

        .stat-icon {
            width: 46px; height: 46px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }
        .stat-icon.orange { background: var(--primary-soft); color: var(--primary); }
        .stat-icon.green  { background: var(--green-soft);   color: var(--green); }
        .stat-icon.red    { background: var(--red-soft);     color: var(--red); }
        .stat-icon.blue   { background: var(--blue-soft);    color: var(--blue); }

        .stat-content { flex: 1; min-width: 0; }
        .stat-value {
            font-size: 1.5rem; font-weight: 800;
            letter-spacing: -0.03em; color: var(--on-surface);
            line-height: 1.1;
        }
        .stat-label {
            font-size: 0.72rem; font-weight: 600;
            color: var(--on-muted); margin-top: 4px;
            letter-spacing: 0.01em;
        }

        /* ══════════════════════════════════════
           Card Component
        ══════════════════════════════════════ */
        .card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--outline);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header {
            padding: 1.1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--outline);
            background: var(--surface-low);
        }

        .card-title {
            display: flex; align-items: center; gap: 0.6rem;
            font-size: 0.88rem; font-weight: 700;
            letter-spacing: -0.01em; color: var(--on-surface);
        }

        .card-title .title-icon {
            width: 32px; height: 32px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.05rem;
        }
        .card-title .title-icon.orange { background: var(--primary-soft); color: var(--primary); }
        .card-title .title-icon.green  { background: var(--green-soft);   color: var(--green); }
        .card-title .title-icon.blue   { background: var(--blue-soft);    color: var(--blue); }
        .card-title .title-icon.red    { background: var(--red-soft);     color: var(--red); }
        .card-title .title-icon.purple { background: #f3e8ff; color: #7c3aed; }

        .card-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            font-size: 0.7rem; font-weight: 600;
            padding: 4px 10px; border-radius: 100px;
        }

        /* ══════════════════════════════════════
           QR Section — Yape Inspired Redesign
        ══════════════════════════════════════ */
        .qr-card {
            border: 1px solid var(--outline);
            box-shadow: var(--shadow-md);
            border-radius: var(--radius-xl);
            overflow: hidden;
            background: #fff;
            margin-bottom: 2rem;
        }

        .qr-section-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0, 1, 0, 1);
        }
        .qr-section-body.open {
            max-height: 2000px;
            transition: max-height 0.4s cubic-bezier(1, 0, 1, 0);
        }

        .qr-header {
            background: #fff !important;
            border-bottom: 1px solid var(--outline) !important;
            padding: 1.25rem 1.5rem !important;
            cursor: pointer;
        }

        .qr-header .card-title {
            color: var(--on-surface) !important;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .qr-grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 0;
            background: #fff;
        }

        /* Preview Side - Deep Yape Theme */
        .qr-preview-box {
            background: #742183;
            padding: 3.5rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.75rem;
            position: relative;
        }

        /* Subtle texture for preview side */
        .qr-preview-box::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 10% 20%, rgba(255,255,255,0.03) 0%, transparent 20%),
                            radial-gradient(circle at 90% 80%, rgba(255,255,255,0.03) 0%, transparent 20%);
        }

        .yape-logo-img {
            height: 100px;
            object-fit: contain;
            position: relative; z-index: 1;
        }

        .qr-frame {
            background: #fff;
            padding: 1.25rem;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            position: relative;
            z-index: 1;
        }

        .qr-frame img {
            width: 200px;
            height: 200px;
            object-fit: contain;
            display: block;
            border-radius: 12px;
        }

        .qr-empty-frame {
            width: 200px; height: 200px;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: #ccc; gap: 0.5rem;
            background: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 12px;
        }

        .yape-cta-pill {
            background: #00D1A4;
            color: #fff;
            padding: 12px 28px;
            border-radius: 100px;
            font-weight: 800;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 0.6rem;
            box-shadow: 0 4px 15px rgba(0,209,164,0.3);
            position: relative; z-index: 1;
            text-transform: uppercase;
            max-width: 90%;
            line-height: 1.2;
        }

        .status-badge {
            font-size: 0.68rem;
            font-weight: 800;
            padding: 6px 16px;
            border-radius: 100px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            position: relative; z-index: 1;
        }
        .status-badge.active { background: rgba(0,209,164,0.15); color: #00D1A4; border: 1px solid rgba(0,209,164,0.3); }
        .status-badge.inactive { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.2); }

        /* Upload Side - Clean and Structured */
        .upload-section {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1.5rem;
        }

        .upload-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--on-surface);
            margin-bottom: -0.5rem;
        }

        .upload-subtitle {
            font-size: 0.85rem;
            color: var(--on-muted);
            line-height: 1.5;
        }

        #qrDropZone {
            border: 2px dashed #742183;
            background: #fdf4ff;
            border-radius: 20px;
            height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
            padding: 1.5rem;
        }

        #qrDropZone:hover, #qrDropZone.dragover {
            border-color: #742183;
            background: #fdf4ff;
        }

        #qrDropZone i { font-size: 2.5rem; color: #742183; }
        #qrDropZone .dz-label { font-weight: 700; font-size: 0.95rem; color: #1e293b; }
        #qrDropZone .dz-hint { font-size: 0.75rem; color: #64748b; }

        #qrSubmitBtn {
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            background: #7B2D8E;
            color: #fff;
            opacity: 0.4;
        }
        #qrSubmitBtn:not(:disabled) { 
            opacity: 1; 
            box-shadow: 0 4px 14px rgba(123,45,142,0.25); 
        }
        #qrSubmitBtn:not(:disabled):hover { 
            background: #5B1F6E; 
            transform: translateY(-1px); 
        }

        .toggle-chevron { transition: transform 0.35s; color: var(--on-light); font-size: 1.15rem; }
        .toggle-chevron.open { transform: rotate(180deg); }

        /* ══════════════════════════════════════
           Pending Tickets
        ══════════════════════════════════════ */
        .pending-badge {
            background: var(--red-soft); color: var(--red); border: 1px solid var(--red-border);
            font-size: 0.68rem; font-weight: 700;
            padding: 3px 10px; border-radius: 100px;
        }

        .pulse-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--red);
            box-shadow: 0 0 0 0 rgba(220,38,38,0.5);
            animation: pulse 2s infinite;
            flex-shrink: 0;
        }
        @keyframes pulse {
            0%   { box-shadow: 0 0 0 0 rgba(220,38,38,0.4); }
            70%  { box-shadow: 0 0 0 8px rgba(220,38,38,0); }
            100% { box-shadow: 0 0 0 0 rgba(220,38,38,0); }
        }

        .ticket-list { padding: 0.5rem 0.75rem; display: flex; flex-direction: column; gap: 0.5rem; list-style: none; }

        .ticket-card {
            background: var(--surface);
            border-radius: var(--radius-md);
            padding: 1rem 1.15rem;
            display: grid;
            grid-template-columns: auto 1fr auto auto;
            align-items: center;
            gap: 1rem;
            transition: all var(--transition);
            border: 1px solid var(--outline);
        }
        .ticket-card:hover { border-color: var(--outline-med); box-shadow: var(--shadow-sm); }

        .ticket-avatar {
            width: 40px; height: 40px; border-radius: var(--radius-sm); flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), #fb923c);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem; color: #fff;
            box-shadow: 0 2px 8px var(--primary-glow);
        }

        .ticket-info { min-width: 0; }
        .ticket-name {
            font-weight: 700; font-size: 0.88rem; color: var(--on-surface);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .ticket-detail {
            display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
            margin-top: 3px;
        }
        .ticket-detail-item {
            display: inline-flex; align-items: center; gap: 3px;
            font-size: 0.72rem; color: var(--on-muted); font-weight: 500;
        }
        .ticket-detail-item i { font-size: 0.82rem; color: var(--on-light); }
        .detail-sep { color: var(--surface-br); font-size: 0.6rem; }

        .ticket-amounts { text-align: right; flex-shrink: 0; }
        .pts-val {
            font-size: 0.95rem; font-weight: 800; color: var(--green);
            display: flex; align-items: center; gap: 4px; justify-content: flex-end;
            letter-spacing: -0.02em;
        }
        .pts-val i { font-size: 1rem; }
        .monto-val { font-size: 0.72rem; color: var(--on-muted); margin-top: 2px; font-weight: 500; }

        .ticket-actions { display: flex; gap: 0.4rem; flex-shrink: 0; }

        /* ══════════════════════════════════════
           Buttons
        ══════════════════════════════════════ */
        .btn {
            display: inline-flex; align-items: center; gap: 0.3rem;
            border-radius: var(--radius-sm); font-size: 0.78rem; font-weight: 600;
            padding: 7px 13px; cursor: pointer; border: 1px solid transparent;
            transition: all var(--transition); text-decoration: none;
            white-space: nowrap;
        }

        .btn-outline {
            background: var(--surface);
            border-color: var(--surface-br);
            color: var(--on-muted);
        }
        .btn-outline:hover { color: var(--on-surface); background: var(--surface-hi); border-color: var(--outline-med); }

        .btn-success {
            background: var(--green);
            color: #fff;
            border-color: var(--green);
        }
        .btn-success:hover { background: #047857; box-shadow: 0 3px 10px rgba(5,150,105,0.25); transform: translateY(-1px); }

        .btn-danger-ghost {
            background: transparent;
            border-color: transparent;
            color: var(--on-light);
            padding: 7px 8px;
        }
        .btn-danger-ghost:hover { background: var(--red-soft); color: var(--red); }

        /* ══════════════════════════════════════
           Empty State
        ══════════════════════════════════════ */
        .empty-state {
            padding: 3.5rem 1.5rem;
            text-align: center;
            display: flex; flex-direction: column; align-items: center; gap: 0.6rem;
        }
        .empty-icon {
            width: 56px; height: 56px; border-radius: 50%;
            background: var(--green-soft);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 0.25rem;
        }
        .empty-icon i { font-size: 1.6rem; color: var(--green); }
        .empty-state h3 { font-size: 0.92rem; color: var(--on-secondary); font-weight: 700; }
        .empty-state p { font-size: 0.8rem; color: var(--on-muted); }

        /* ══════════════════════════════════════
           History Table
        ══════════════════════════════════════ */
        .table-wrapper { overflow-x: auto; }

        .data-table {
            width: 100%; border-collapse: collapse; text-align: left;
        }

        .data-table thead tr { border-bottom: 1px solid var(--outline-med); }

        .data-table th {
            padding: 1rem 1.25rem;
            font-size: 0.65rem; font-weight: 800;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: #64748b;
            background: #f8fafc;
            white-space: nowrap;
            border-bottom: 2px solid var(--outline);
        }

        .data-table td {
            padding: 0.85rem 1.25rem;
            font-size: 0.82rem;
            color: var(--on-secondary);
            border-bottom: 1px solid var(--outline);
            vertical-align: middle;
        }

        .data-table tbody tr { transition: background 0.15s; }
        .data-table tbody tr:nth-child(even) { background: #fafbfc; }
        .data-table tbody tr:hover { background: #f4f6f8 !important; }
        .data-table tbody tr:last-child td { border-bottom: none; }

        .row-client { display: flex; align-items: center; gap: 0.55rem; }
        .row-avatar {
            width: 28px; height: 28px; border-radius: var(--radius-xs); flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), #fb923c);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.68rem; font-weight: 700; color: #fff;
        }
        .client-name { font-weight: 600; font-size: 0.82rem; color: var(--on-surface); }

        .pts-positive { color: var(--green); font-weight: 700; font-size: 0.82rem; }

        /* Status Chips Modernized */
        .chip {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.7rem; font-weight: 700;
            padding: 4px 12px; border-radius: 100px; white-space: nowrap;
            letter-spacing: 0.02em;
        }
        .chip::before {
            content: ''; width: 6px; height: 6px; border-radius: 50%;
            background: currentColor; flex-shrink: 0;
        }
        .chip-pending  { background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; }
        .chip-approved { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .chip-rejected { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

        .btn-view-sm {
            display: inline-flex; align-items: center; gap: 0.25rem;
            background: var(--surface-low); border: 1px solid var(--outline-med);
            color: var(--on-muted); border-radius: var(--radius-xs);
            font-size: 0.7rem; font-weight: 600; padding: 4px 9px;
            cursor: pointer; transition: all var(--transition);
        }
        .btn-view-sm:hover { color: var(--on-surface); background: var(--surface-hi); }

        .date-text { font-size: 0.78rem; color: var(--on-muted); white-space: nowrap; }

        .pag-btn {
            width: 32px; height: 32px;
            display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid var(--outline-med); border-radius: 8px;
            background: #fff; color: #64748b;
            font-size: 0.75rem; font-weight: 700; cursor: pointer;
            transition: all 0.2s;
        }
        .pag-btn:hover { border-color: var(--primary); color: var(--primary); background: #fdf4ff; }
        .pag-btn.active { background: var(--primary); color: #fff; border-color: var(--primary); box-shadow: 0 4px 10px var(--primary-glow); }
        .pag-btn:disabled { opacity: 0.3; cursor: not-allowed; }

        /* ══════════════════════════════════════
           Image Modal
        ══════════════════════════════════════ */
        .img-modal {
            display: none; position: fixed; z-index: 1000;
            inset: 0; background: rgba(17,24,39,0.35);
            align-items: center; justify-content: center; padding: 2rem;
            backdrop-filter: blur(10px);
        }
        .img-modal.is-active { display: flex; }
        .img-modal-inner {
            background: var(--surface);
            border: 1px solid var(--outline);
            border-radius: var(--radius-xl);
            max-width: 560px; width: 100%;
            animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        @keyframes slideUp {
            from { transform: translateY(24px) scale(0.96); opacity: 0; }
            to   { transform: translateY(0) scale(1); opacity: 1; }
        }
        .img-modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--outline);
            display: flex; justify-content: space-between; align-items: center;
            background: var(--surface-low);
        }
        .img-modal-header h3 {
            font-size: 0.9rem; font-weight: 700; color: var(--on-surface);
            display: flex; align-items: center; gap: 0.4rem;
        }
        .img-modal-header h3 i { color: var(--primary); }
        .modal-close-btn {
            background: var(--surface); border: 1px solid var(--outline-med);
            color: var(--on-muted); border-radius: var(--radius-xs);
            font-size: 1.1rem; width: 30px; height: 30px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all var(--transition);
        }
        .modal-close-btn:hover { background: var(--red-soft); color: var(--red); border-color: var(--red-border); }
        .img-modal-body { padding: 1.25rem; text-align: center; background: var(--surface-low); }
        .img-modal-body img { max-width: 100%; border-radius: var(--radius-md); max-height: 60vh; object-fit: contain; box-shadow: var(--shadow-sm); }

        /* ══════════════════════════════════════
           Responsive
        ══════════════════════════════════════ */
        @media (max-width: 900px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .stats-row { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
            .stat-card { padding: 1rem; }
            .stat-value { font-size: 1.25rem; }
            .qr-grid { grid-template-columns: 1fr; }
            .ticket-card { grid-template-columns: 1fr; gap: 0.75rem; }
            .ticket-actions { justify-content: flex-start; }
            .ticket-amounts { text-align: left; }
            .container { padding: 0 1rem; margin: 1rem auto; }
            .top-nav { padding: 0 1rem; }
        }
        @media (max-width: 480px) {
            .stats-row { grid-template-columns: 1fr; }
        }
        /* Search box in header */
        .header-search {
            position: relative;
            max-width: 220px;
            width: 100%;
        }
        .header-search i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--on-light);
            font-size: 0.9rem;
        }
        .header-search input {
            width: 100%;
            padding: 6px 10px 6px 32px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--outline-med);
            background: var(--surface);
            font-size: 0.78rem;
            color: var(--on-surface);
            outline: none;
            transition: all 0.2s;
        }
        .header-search input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .copy-btn {
            background: none; border: none; padding: 2px 4px;
            border-radius: 4px; cursor: pointer; color: var(--on-light);
            transition: all 0.2s;
        }
        .copy-btn:hover { background: var(--surface-hi); color: var(--primary); }
    </style>
</head>
<body>

    <!-- ════ Top Nav ════ -->
    <nav class="top-nav">
        <a href="<?= BASE_URL ?>panel" class="back-btn">
            <i class='bx bx-chevron-left'></i> Volver al Panel
        </a>
        <div class="page-title-group">
            <div class="page-title">Verificación de Recargas</div>
            <div class="page-subtitle">Gestión de comprobantes y pagos</div>
        </div>
        <div style="width:120px;"></div>
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
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon orange"><i class='bx bx-time-five'></i></div>
                <div class="stat-content">
                    <div class="stat-value"><?= $totalPendientes ?></div>
                    <div class="stat-label">Pendientes</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class='bx bx-check-circle'></i></div>
                <div class="stat-content">
                    <div class="stat-value"><?= $totalAprobados ?></div>
                    <div class="stat-label">Aprobados</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class='bx bx-x-circle'></i></div>
                <div class="stat-content">
                    <div class="stat-value"><?= $totalRechazados ?></div>
                    <div class="stat-label">Rechazados</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class='bx bx-wallet'></i></div>
                <div class="stat-content">
                    <div class="stat-value">S/ <?= number_format($montoTotal, 0) ?></div>
                    <div class="stat-label">Monto Acreditado</div>
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════════════════
             SECTION 1 — QR Yape Manager
        ════════════════════════════════════════════ -->
        <div class="card qr-card">
            <div class="card-header qr-header" onclick="toggleQR()">
                <div class="card-title">
                    Configuración QR de Pago
                </div>
                <div style="display:flex; align-items:center; gap:0.65rem;">
                    <?php if ($qrActual): ?>
                        <span class="chip" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-size:0.65rem; font-weight:700;">Activo</span>
                    <?php else: ?>
                        <span class="chip" style="background:#f9fafb; color:#6b7280; border:1px solid #e5e7eb; font-size:0.65rem; font-weight:700;">Inactivo</span>
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
                                <label class="section-label dark" style="display:block; margin-bottom:0.5rem; font-size: 0.75rem; color: #64748b; font-weight:700;">Nombre del Titular:</label>
                                <input type="text" name="yape_nombre" id="yapeNameInput" value="<?= htmlspecialchars($nombreTitular) ?>" 
                                       placeholder="Ej: Juan Perez"
                                       style="width: 100%; padding: 0.8rem; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.88rem; outline:none; transition: border 0.3s;"
                                       onfocus="this.style.borderColor='#742183'" onblur="this.style.borderColor='#e2e8f0'"
                                       onkeyup="updateYapePreview(this.value)">
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
        </div>

        <!-- ════════════════════════════════════════════
             SECTION 2 — Revisión Pendiente
        ════════════════════════════════════════════ -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="title-icon red">
                        <div class="pulse-dot"></div>
                    </div>
                    Revisión Pendiente
                </div>
                <span class="pending-badge"><?= count($recargas) ?> pendiente<?= count($recargas) !== 1 ? 's' : '' ?></span>
            </div>

            <?php if (empty($recargas)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class='bx bx-check-shield'></i></div>
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

        <!-- ════════════════════════════════════════════
             SECTION 3 — Historial de Movimientos
        ════════════════════════════════════════════ -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="title-icon blue"><i class='bx bx-history'></i></div>
                    Historial de Movimientos
                </div>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <div class="header-search" style="max-width: 150px;">
                        <i class='bx bx-calendar'></i>
                        <input type="date" id="historyDate" onchange="filterHistory()">
                    </div>
                    <div class="header-search" style="max-width: 130px;">
                        <i class='bx bx-filter-alt'></i>
                        <select id="historyStatus" onchange="filterHistory()" style="width: 100%; padding: 6px 10px 6px 32px; border-radius: var(--radius-sm); border: 1px solid var(--outline-med); background: var(--surface); font-size: 0.78rem; color: var(--on-surface); outline: none; appearance: none;">
                            <option value="">Todos</option>
                            <option value="aprobado">Aprobados</option>
                            <option value="rechazado">Rechazados</option>
                        </select>
                    </div>
                    <div class="header-search">
                        <i class='bx bx-search'></i>
                        <input type="text" id="historySearch" placeholder="Buscar cliente..." onkeyup="filterHistory()">
                    </div>
                </div>
            </div>

            <?php if (empty($historial)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class='bx bx-spreadsheet'></i></div>
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
                                        <i class='bx bx-user' style="color: var(--on-light); font-size: 1.1rem;"></i>
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
                                        <span style="color:var(--on-light); font-size:0.78rem;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="chip <?= $chipClass ?>"><?= ucfirst($h['estado'] ?? 'pendiente') ?></span></td>
                                <td class="date-text">
                                    <div style="font-weight: 700; color: var(--on-surface);">
                                        <?= date('d M Y', strtotime($h['fecha'])) ?>
                                    </div>
                                    <div style="font-size: 0.7rem; opacity: 0.7;">
                                        <?= date('h:i A', strtotime($h['fecha'])) ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Controls -->
                <div id="paginationControls" style="padding: 1rem 1.25rem; border-top: 1px solid var(--outline); display: flex; align-items: center; justify-content: space-between; background: var(--surface-low);">
                    <div style="font-size: 0.78rem; color: var(--on-muted); font-weight: 500;">
                        Mostrando <span id="pagStart">0</span> - <span id="pagEnd">0</span> de <span id="pagTotal">0</span>
                    </div>
                    <div id="pageNumbers" style="display: flex; gap: 4px; align-items: center;">
                        <!-- JS injects here -->
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div><!-- .container -->

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
            prev.className = 'pag-btn';
            prev.innerHTML = "<i class='bx bx-chevron-left'></i>";
            prev.disabled = currentPage === 1;
            prev.onclick = () => { currentPage--; filterHistory(); };
            container.appendChild(prev);

            // Page numbers logic (sliding window if many pages)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(maxPage, startPage + 4);
            if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement('button');
                btn.className = `pag-btn ${i === currentPage ? 'active' : ''}`;
                btn.textContent = i;
                btn.onclick = () => { currentPage = i; filterHistory(); };
                container.appendChild(btn);
            }

            // Next button
            const next = document.createElement('button');
            next.className = 'pag-btn';
            next.innerHTML = "<i class='bx bx-chevron-right'></i>";
            next.disabled = currentPage === maxPage;
            next.onclick = () => { currentPage++; filterHistory(); };
            container.appendChild(next);
        }

        function changePage(dir) {
            currentPage += dir;
            filterHistory();
        }

        // Initialize pagination on load
        document.addEventListener('DOMContentLoaded', filterHistory);

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
</body>
</html>
