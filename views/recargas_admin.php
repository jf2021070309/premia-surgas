<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Recargas — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #821515;
            --primary-light: #a32222;
            --primary-dark: #5c0f0f;
            --secondary: #1a1a2e;
            --accent: #f59e0b;
            --bg-page: #f4f6f9;
            --card-bg: #ffffff;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
        }

        body { 
            background-color: var(--bg-page); 
            font-family: 'Inter', sans-serif; 
            margin: 0;
            padding: 0;
        }

        /* Hero Header */
        .admin-hero {
            background: linear-gradient(135deg, var(--secondary) 0%, #2a2a4a 100%);
            color: white; 
            padding: 3rem 1rem 4rem; 
            text-align: center; 
            border-radius: 0 0 2.5rem 2.5rem;
            margin-bottom: -3rem; 
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        
        .admin-hero::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
            pointer-events: none;
        }

        .hero-title {
            margin: 0.5rem 0 0.5rem; 
            font-weight: 800; 
            letter-spacing: -1px; 
            font-size: 2.2rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            opacity: 0.8; 
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
            font-weight: 400;
        }

        .back-btn { 
            display: inline-flex; align-items: center; gap: .5rem; 
            color: rgba(255,255,255,0.8); text-decoration: none; 
            font-size: 0.95rem; font-weight: 600;
            transition: all 0.3s; margin-bottom: 1rem; 
            background: rgba(255,255,255,0.1);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            backdrop-filter: blur(5px);
        }
        .back-btn:hover { color: white; background: rgba(255,255,255,0.2); transform: translateX(-3px); }

        .container { max-width: 1050px; margin: 0 auto; padding: 0 1rem 3rem; position: relative; z-index: 2; }

        /* Modern Cards */
        .glass-card {
            background: var(--card-bg); 
            border-radius: 1.5rem; 
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.04), 0 5px 15px rgba(0,0,0,0.02); 
            margin-bottom: 2.5rem;
            border: 1px solid rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .section-header { 
            display: flex; align-items: center; gap: 0.8rem; 
            margin-bottom: 1.8rem; 
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }
        .section-title { font-weight: 800; font-size: 1.4rem; color: var(--text-dark); margin:0;}
        .section-icon { 
            background: linear-gradient(135deg, var(--accent), #d97706);
            color: white;
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px;
            font-size: 1.4rem;
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
        }

        /* Pending Items */
        .recarga-grid { display: grid; gap: 1rem; }
        .recarga-item {
            display: flex; align-items: center; justify-content: space-between;
            background: #fafaf9;
            padding: 1.2rem 1.5rem; 
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .recarga-item:hover { 
            background: #ffffff;
            transform: translateY(-3px);
            border-color: #d1d5db;
            box-shadow: 0 10px 20px rgba(0,0,0,0.04);
        }

        .client-info { display: flex; align-items: center; gap: 1.2rem; flex: 1; }
        .avatar-gradient { 
            width: 50px; height: 50px; 
            border-radius: 14px; 
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); 
            color: white; 
            display: flex; align-items: center; justify-content: center; 
            font-weight: 800; font-size: 1.3rem; 
            box-shadow: 0 4px 10px rgba(130, 21, 21, 0.2);
        }
        .info-txt h4 { margin: 0 0 0.2rem; color: var(--text-dark); font-size: 1.05rem; font-weight: 700; }
        .info-txt p { margin: 0; color: var(--text-muted); font-size: 0.85rem; display: flex; gap: 10px;}
        .info-tag { background: #e5e7eb; padding: 2px 6px; border-radius: 4px; font-weight: 600; color: #4b5563; }

        .mount-wrapper { 
            text-align: right; 
            margin: 0 2.5rem; 
            padding-right: 2.5rem;
            border-right: 1px solid #e5e7eb;
        }
        .mount-pts { 
            font-weight: 900; color: var(--primary); font-size: 1.3rem; 
            display: flex; align-items: center; gap: 5px; justify-content: flex-end;
        }
        .mount-price { 
            font-size: 0.9rem; color: var(--text-muted); font-weight: 600;
            display: inline-block; margin-top: 0.2rem;
        }

        .action-group { display: flex; gap: 0.8rem; }
        .btn-modern { 
            width: 45px; height: 45px; 
            border-radius: 12px; border: none; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 1.4rem; cursor: pointer; 
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .btn-view { background: #f3f4f6; color: #374151; }
        .btn-view:hover { background: #e5e7eb; color: #111827; }
        
        .btn-approve { background: #ecfdf5; color: #10b981; }
        .btn-approve:hover { background: #10b981; color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
        
        .btn-reject { background: #fef2f2; color: #ef4444; }
        .btn-reject:hover { background: #ef4444; color: white; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }

        .btn-modern:active { transform: scale(0.95); }

        /* Modern Badges */
        .status-badge { 
            padding: 0.4rem 0.8rem; border-radius: 8px; 
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase; 
            letter-spacing: 0.5px; display: inline-block;
        }
        .bg-pending { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        .bg-approved { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .bg-rejected { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

        /* Empty State */
        .modern-empty { 
            text-align: center; padding: 4rem 2rem; 
            background: #fafaf9; border-radius: 1rem; border: 2px dashed #e5e7eb;
        }
        .modern-empty i { font-size: 4rem; color: #d1d5db; margin-bottom: 1rem; display: block; }
        .modern-empty h3 { color: var(--text-dark); margin: 0 0 0.5rem; font-size: 1.2rem; }
        .modern-empty p { color: var(--text-muted); margin: 0; }

        /* Modern Table */
        .table-wrapper { overflow-x: auto; border-radius: 1rem; border: 1px solid #f3f4f6; }
        .modern-table { width: 100%; border-collapse: collapse; text-align: left; }
        .modern-table th { 
            background: #f9fafb; padding: 1.2rem 1.5rem; 
            font-weight: 700; color: #4b5563; font-size: 0.85rem; 
            text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }
        .modern-table td { 
            padding: 1.2rem 1.5rem; border-bottom: 1px solid #f3f4f6;
            color: var(--text-dark); font-size: 0.95rem;
            vertical-align: middle;
        }
        .modern-table tbody tr:hover { background: #fafaf9; }
        .modern-table tbody tr:last-child td { border-bottom: none; }

        .tb-client { display: flex; align-items: center; gap: 1rem; font-weight: 600; }
        .tb-avatar { width: 32px; height: 32px; border-radius: 8px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: #4b5563; }
        .tb-pts { font-weight: 800; color: var(--primary); }
        .tb-date { color: var(--text-muted); font-size: 0.85rem; }

        /* Modal Image Viewer */
        .viewer-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px);
            z-index: 9999; display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .viewer-overlay.active { opacity: 1; visibility: visible; }
        .viewer-content {
            background: transparent; max-width: 90vw; max-height: 90vh;
            position: relative; transform: scale(0.9); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .viewer-overlay.active .viewer-content { transform: scale(1); }
        .viewer-img {
            max-width: 100%; max-height: 85vh; border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255,255,255,0.1);
        }
        .viewer-close {
            position: absolute; top: -1.5rem; right: -1.5rem;
            background: white; color: var(--text-dark);
            width: 40px; height: 40px; border-radius: 50%; border: none;
            font-size: 1.5rem; display: flex; align-items: center; justify-content: center;
            cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .viewer-close:hover { transform: scale(1.1); }
        
        .viewer-footer { text-align: center; color: rgba(255,255,255,0.7); margin-top: 1rem; font-size: 0.9rem; }

        @media (max-width: 768px) {
            .recarga-item { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .mount-wrapper { margin: 0; padding: 1rem 0; border-right: none; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; width: 100%; text-align: left; display: flex; align-items: center; justify-content: space-between; }
            .action-group { width: 100%; justify-content: space-between; }
            .btn-modern { flex: 1; }
            .hero-title { font-size: 1.8rem; }
            .admin-hero { padding: 2rem 1rem 3.5rem; }
        }
    </style>
</head>
<body>

    <div class="admin-hero">
        <a href="<?= BASE_URL ?>panel" class="back-btn"><i class='bx bx-left-arrow-alt'></i> Volver al Panel</a>
        <h1 class="hero-title">Verificación de Recargas</h1>
        <p class="hero-subtitle">Valida los depósitos y transfiere los puntos a las cuentas de tus clientes de forma segura.</p>
    </div>

    <div class="container" id="app">
        
        <?php if (isset($_SESSION['flash'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: '<?= $_SESSION['flash']['type'] ?>',
                        title: '<?= $_SESSION['flash']['title'] ?>',
                        text: '<?= $_SESSION['flash']['message'] ?>',
                        confirmButtonColor: '#821515',
                        customClass: { popup: 'modern-popup' }
                    });
                });
            </script>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- Pendientes -->
        <div class="glass-card">
            <div class="section-header">
                <div class="section-icon"><i class='bx bxs-inbox'></i></div>
                <h3 class="section-title">Pendientes de Aprobación</h3>
            </div>

            <?php if (empty($recargas)): ?>
                <div class="modern-empty">
                    <i class='bx bx-check-shield'></i>
                    <h3>Todo al Día</h3>
                    <p>No hay recargas pendientes de verificación en este momento.</p>
                </div>
            <?php else: ?>
                <div class="recarga-grid">
                    <?php foreach ($recargas as $r): ?>
                        <div class="recarga-item">
                            
                            <div class="client-info">
                                <div class="avatar-gradient"><?= substr($r['cliente_nombre'], 0, 1) ?></div>
                                <div class="info-txt">
                                    <h4><?= htmlspecialchars($r['cliente_nombre']) ?></h4>
                                    <p>
                                        <span><i class='bx bx-phone'></i> <?= htmlspecialchars($r['cliente_celular']) ?></span>
                                        <span class="info-tag">DNI: <?= htmlspecialchars($r['cliente_dni']) ?></span>
                                    </p>
                                </div>
                            </div>

                            <div class="mount-wrapper">
                                <div class="mount-pts"><i class='bx bxs-star'></i> +<?= number_format($r['puntos']) ?></div>
                                <div class="mount-price">Depósito: S/ <?= number_format($r['monto'], 2) ?></div>
                            </div>

                            <div class="action-group">
                                <button class="btn-modern btn-view" onclick="openViewer('<?= BASE_URL ?>assets/uploads/comprobantes/<?= $r['comprobante'] ?>')" title="Ver Evidencia">
                                    <i class='bx bx-image'></i>
                                </button>
                                
                                <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="margin:0; display:flex; flex:1;" class="approve-form">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="estado" value="aprobado">
                                    <button type="button" class="btn-modern btn-approve btn-approve-trigger" title="Aprobar Transacción" style="width:100%;">
                                        <i class='bx bx-check-double'></i>
                                    </button>
                                </form>

                                <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="margin:0; display:flex; flex:1;" class="reject-form">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="estado" value="rechazado">
                                    <button type="button" class="btn-modern btn-reject btn-reject-trigger" title="Rechazar" style="width:100%;">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Historial Reciente -->
        <div class="glass-card">
            <div class="section-header" style="border-bottom: none; margin-bottom: 0.5rem;">
                <h3 class="section-title" style="font-size: 1.2rem; display:flex; align-items:center; gap:8px;">
                    <i class='bx bx-history' style="color:var(--text-muted); font-size: 1.5rem;"></i> Registro Histórico
                </h3>
            </div>

            <div class="table-wrapper">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Puntos Acreditados</th>
                            <th>Monto Abonado</th>
                            <th>Estado</th>
                            <th>Responsable</th>
                            <th>Fecha y Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $h): ?>
                        <tr>
                            <td>
                                <div class="tb-client">
                                    <div class="tb-avatar"><?= substr($h['cliente_nombre'], 0, 1) ?></div>
                                    <?= htmlspecialchars($h['cliente_nombre']) ?>
                                </div>
                            </td>
                            <td class="tb-pts">+<?= number_format($h['puntos']) ?> pts</td>
                            <td style="font-weight: 500;">S/ <?= number_format($h['monto'], 2) ?></td>
                            <td>
                                <?php
                                    $bgClass = '';
                                    if ($h['estado'] === 'pendiente') $bgClass = 'bg-pending';
                                    if ($h['estado'] === 'aprobado') $bgClass = 'bg-approved';
                                    if ($h['estado'] === 'rechazado') $bgClass = 'bg-rejected';
                                ?>
                                <span class="status-badge <?= $bgClass ?>"><?= $h['estado'] ?></span>
                            </td>
                            <td><span style="background: #f3f4f6; padding: 3px 8px; border-radius: 6px; font-size: 0.8rem; font-weight:600;"><?= htmlspecialchars($h['validador_nombre'] ?? 'Sistema') ?></span></td>
                            <td class="tb-date">
                                <i class='bx bx-calendar-alt'></i> <?= date('d M, Y', strtotime($h['fecha'])) ?><br>
                                <i class='bx bx-time'></i> <?= date('H:i A', strtotime($h['fecha'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modern Image Viewer -->
    <div class="viewer-overlay" id="imageViewer">
        <div class="viewer-content">
            <button class="viewer-close" onclick="closeViewer()"><i class='bx bx-x'></i></button>
            <img class="viewer-img" id="viewerImg" src="" alt="Comprobante de Pago">
            <div class="viewer-footer"><i class='bx bx-shield-quarter'></i> Verifique el Nro. de Operación y el monto antes de aprobar.</div>
        </div>
    </div>

    <script>
        // Modal Logic
        const overlay = document.getElementById('imageViewer');
        const img = document.getElementById('viewerImg');

        function openViewer(src) {
            img.src = src;
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeViewer() {
            overlay.classList.remove('active');
            setTimeout(() => { img.src = ''; }, 300);
            document.body.style.overflow = '';
        }

        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) closeViewer();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && overlay.classList.contains('active')) {
                closeViewer();
            }
        });

        // SweetAlert2 Form Confirmations
        document.querySelectorAll('.btn-approve-trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const form = this.closest('.approve-form');
                Swal.fire({
                    title: '¿Confirmar Aprobación?',
                    text: "Se sumarán automáticamente los puntos a la cuenta del cliente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="bx bx-check-double"></i> Sí, Aprobar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        document.querySelectorAll('.btn-reject-trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const form = this.closest('.reject-form');
                Swal.fire({
                    title: '¿Rechazar Recarga?',
                    text: "El cliente no recibirá los puntos y la recarga será cancelada.",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="bx bx-trash"></i> Sí, Rechazar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
</body>
</html>
