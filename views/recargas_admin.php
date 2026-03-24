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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #821515;
            --primary-hover: #6a1111;
            --text-dark: #202223;
            --text-gray: #6d7175;
            --bg-body: #f4f6f8;
            --bg-surface: #ffffff;
            --border: #e1e3e5;
            --success: #008060;
            --success-bg: #e3f1df;
            --warning: #b38000;
            --warning-bg: #fff5ea;
            --danger: #d82c0d;
            --danger-bg: #ffe9e5;
        }

        body { 
            background-color: var(--bg-body); 
            font-family: 'Inter', sans-serif; 
            margin: 0;
            padding: 0;
            color: var(--text-dark);
        }

        /* Top Navigation Bar */
        .top-nav {
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .back-btn { 
            display: inline-flex; align-items: center; gap: .5rem; 
            color: var(--text-gray); text-decoration: none; 
            font-weight: 500; transition: color 0.2s;
            font-size: 0.95rem;
        }
        .back-btn:hover { color: var(--primary); }

        .page-title { margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--text-dark); }

        /* Main Layout */
        .container { 
            max-width: 1200px; 
            margin: 2rem auto; 
            padding: 0 1.5rem; 
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        /* Clean Section Cards */
        .section-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .section-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            background-color: #fafbfb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-header h2 { margin: 0; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        .badge-count { background: var(--primary); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }

        /* Pending Tickets (List format) */
        .ticket-list { padding: 0; margin: 0; list-style: none; }
        .ticket-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            transition: background-color 0.2s;
        }
        .ticket-item:last-child { border-bottom: none; }
        .ticket-item:hover { background-color: #f9fafb; }

        .ticket-main { display: flex; align-items: center; gap: 1rem; flex: 1; }
        .avatar-initial {
            width: 40px; height: 40px;
            border-radius: 50%;
            background-color: #f1f2f4;
            color: var(--text-dark);
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 1.1rem;
            border: 1px solid var(--border);
        }
        .client-info h4 { margin: 0 0 0.2rem; font-size: 1rem; font-weight: 600; }
        .client-info span { color: var(--text-gray); font-size: 0.85rem; display: block; }

        .ticket-details { text-align: right; margin-right: 1.5rem; }
        .val-points { font-size: 1.15rem; font-weight: 700; color: var(--success); display: flex; align-items: center; gap: 4px; justify-content: flex-end;}
        .val-money { font-size: 0.85rem; color: var(--text-gray); font-weight: 500; }

        .ticket-actions { display: flex; gap: 0.75rem; align-items: center; }
        
        /* Modern Buttons */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-outline { background: white; border-color: var(--border); color: var(--text-dark); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .btn-outline:hover { background: #f9fafb; border-color: #c4cdd5; }
        
        .btn-primary { background: var(--success); color: white; }
        .btn-primary:hover { background: #006e52; }

        .btn-danger { background: white; border-color: var(--border); color: var(--danger); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .btn-danger:hover { background: var(--danger-bg); border-color: #fecaca; }

        /* Empty State */
        .empty-state { text-align: center; padding: 3rem 1rem; }
        .empty-icon { font-size: 3rem; color: var(--border); margin-bottom: 1rem; }
        .empty-state h3 { margin: 0 0 0.5rem; color: var(--text-dark); font-size: 1.1rem; }
        .empty-state p { margin: 0; color: var(--text-gray); font-size: 0.9rem; }

        /* Data Table */
        .data-table-wrapper { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th, .data-table td { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); }
        .data-table th { 
            background-color: #f9fafb; 
            font-size: 0.8rem; 
            font-weight: 600; 
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table td { font-size: 0.9rem; color: var(--text-dark); vertical-align: middle; }
        .data-table tbody tr:hover { background-color: #fafbfb; }

        /* Table Badges */
        .status { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; }
        .status-pendiente { background-color: var(--warning-bg); color: var(--warning); }
        .status-aprobado { background-color: var(--success-bg); color: var(--success); }
        .status-rechazado { background-color: var(--danger-bg); color: var(--danger); }

        .pts-col { font-weight: 600; }
        .date-col { color: var(--text-gray); font-size: 0.85rem; }

        /* Image Modal */
        .modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0;
            width: 100%; height: 100%; background-color: rgba(32, 34, 35, 0.8);
            align-items: center; justify-content: center; padding: 2rem;
            backdrop-filter: blur(4px);
        }
        .modal.is-active { display: flex; }
        .modal-content {
            background: var(--bg-surface);
            border-radius: 8px;
            max-width: 600px; width: 100%;
            display: flex; flex-direction: column;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            animation: modalIn 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        @keyframes modalIn {
            0% { transform: scale(0.95); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-header h3 { margin: 0; font-size: 1.1rem; }
        .close-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-gray); }
        .close-btn:hover { color: var(--text-dark); }
        .modal-body { padding: 1.5rem; text-align: center; }
        .modal-body img { max-width: 100%; border: 1px solid var(--border); border-radius: 4px; max-height: 60vh; object-fit: contain; }

        @media (max-width: 768px) {
            .ticket-item { flex-direction: column; align-items: stretch; gap: 1rem; }
            .ticket-details { text-align: left; margin: 0; display: flex; justify-content: space-between; align-items: center; }
            .ticket-actions { justify-content: space-between; }
            .btn { flex: 1; justify-content: center; }
            .top-nav { padding: 1rem; }
        }
    </style>
</head>
<body>

    <nav class="top-nav">
        <a href="<?= BASE_URL ?>panel" class="back-btn"><i class='bx bx-arrow-back'></i> Volver</a>
        <h1 class="page-title">Verificación de Recargas</h1>
        <div style="width: 70px;"></div> <!-- Spacer for centering -->
    </nav>

    <div class="container" id="app">
        
        <?php if (isset($_SESSION['flash'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: '<?= $_SESSION['flash']['type'] ?>',
                        title: '<?= $_SESSION['flash']['title'] ?>',
                        text: '<?= $_SESSION['flash']['message'] ?>',
                        confirmButtonColor: '#008060',
                        customClass: { popup: 'modern-popup' }
                    });
                });
            </script>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- Pendientes -->
        <div class="section-card">
            <div class="section-header">
                <h2>Revisión Pendiente <span class="badge-count"><?= count($recargas) ?></span></h2>
            </div>

            <?php if (empty($recargas)): ?>
                <div class="empty-state">
                    <i class='bx bx-check-shield empty-icon'></i>
                    <h3>No hay comprobantes pendientes</h3>
                    <p>Todos los pagos han sido verificados y procesados.</p>
                </div>
            <?php else: ?>
                <ul class="ticket-list">
                    <?php foreach ($recargas as $r): ?>
                        <li class="ticket-item">
                            <div class="ticket-main">
                                <div class="avatar-initial"><?= substr($r['cliente_nombre'], 0, 1) ?></div>
                                <div class="client-info">
                                    <h4><?= htmlspecialchars($r['cliente_nombre']) ?></h4>
                                    <span><?= htmlspecialchars($r['cliente_celular']) ?> | DNI: <?= htmlspecialchars($r['cliente_dni']) ?></span>
                                </div>
                            </div>

                            <div class="ticket-details">
                                <div class="val-points"><i class='bx bxs-up-arrow-circle'></i> <?= number_format($r['puntos']) ?> pts</div>
                                <div class="val-money">S/ <?= number_format($r['monto'], 2) ?></div>
                            </div>

                            <div class="ticket-actions">
                                <button class="btn btn-outline" onclick="openModal('<?= BASE_URL ?>assets/uploads/comprobantes/<?= $r['comprobante'] ?>')" title="Ver Comprobante">
                                    <i class='bx bx-receipt'></i> Ver Pago
                                </button>
                                
                                <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="margin:0;" class="action-form approve-form">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="estado" value="aprobado">
                                    <button type="button" class="btn btn-primary btn-approve">
                                        <i class='bx bx-check'></i> Aprobar
                                    </button>
                                </form>

                                <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="margin:0;" class="action-form reject-form">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="estado" value="rechazado">
                                    <button type="button" class="btn btn-danger btn-reject" title="Rechazar y Anular">
                                        <i class='bx bx-x'></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Historial -->
        <div class="section-card">
            <div class="section-header">
                <h2>Historial de Movimientos</h2>
            </div>

            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Puntos Acreditados</th>
                            <th>Monto Abonado</th>
                            <th>Evidencia</th>
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
                                <?php if (!empty($h['comprobante'])): ?>
                                <button class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;" onclick="openModal('<?= BASE_URL ?>assets/uploads/comprobantes/<?= $h['comprobante'] ?>')" title="Ver Foto">
                                    <i class='bx bx-image' style="font-size: 1.1rem; vertical-align: middle;"></i> Ver
                                </button>
                                <?php else: ?>
                                <span style="color:var(--text-gray); font-size: 0.8rem;">Ninguna</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $bgClass = '';
                                    if ($h['estado'] === 'pendiente') $bgClass = 'status-pendiente';
                                    if ($h['estado'] === 'aprobado') $bgClass = 'status-aprobado';
                                    if ($h['estado'] === 'rechazado') $bgClass = 'status-rechazado';
                                ?>
                                <span class="status <?= $bgClass ?>"><?= ucfirst($h['estado']) ?></span>
                            </td>
                            <td style="color: var(--text-gray); font-size: 0.85rem;">
                                <?= htmlspecialchars($h['validador_nombre'] ?? '-') ?>
                            </td>
                            <td class="date-col">
                                <?= date('d M Y, H:i', strtotime($h['fecha'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Comprobante -->
    <div class="modal" id="receiptModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Evidencia del Depósito</h3>
                <button class="close-btn" onclick="closeModal()"><i class='bx bx-x'></i></button>
            </div>
            <div class="modal-body">
                <img id="receiptImage" src="" alt="Comprobante cargando...">
            </div>
        </div>
    </div>

    <script>
        // Modal Handlers
        const modal = document.getElementById('receiptModal');
        const img = document.getElementById('receiptImage');

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

        modal.addEventListener('click', e => {
            if(e.target === modal) closeModal();
        });

        // SweetAlert2 Triggers
        document.querySelectorAll('.btn-approve').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.approve-form');
                Swal.fire({
                    title: 'Verificar Abono',
                    text: "¿Has confirmado que el dinero ingresó a la cuenta bancaria?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#008060', // success color
                    cancelButtonColor: '#6d7175',
                    confirmButtonText: 'Sí, Acreditar Puntos',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        document.querySelectorAll('.btn-reject').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.reject-form');
                Swal.fire({
                    title: 'Rechazar Comprobante',
                    text: "Esta acción anulará la solicitud de puntos del cliente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d82c0d', // danger color
                    cancelButtonColor: '#6d7175',
                    confirmButtonText: 'Sí, Rechazar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
</body>
</html>
