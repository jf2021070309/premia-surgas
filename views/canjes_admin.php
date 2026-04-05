<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Entregas — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        .chip-pendiente { 
            background: #fff7ed; 
            color: #ff6600; 
            border: 1px solid #ffedd5; 
            animation: pulse-orange 2s infinite; 
            box-shadow: 0 0 0 0 rgba(255, 102, 0, 0.4);
            font-weight: 800;
        }
        .chip-entregado { background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; }
        
        @keyframes pulse-orange {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 102, 0, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 102, 0, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 102, 0, 0); }
        }
        .row-prize { display: flex; align-items: center; gap: 0.75rem; }
        .prize-img { width: 40px; height: 40px; object-fit: contain; background: #f8fafc; border-radius: 8px; padding: 4px; border: 1px solid #e2e8f0; }
        .btn-deliver { background: #000; color: #fff; border: none; padding: 6px 14px; border-radius: 8px; font-weight: 700; font-size: 0.78rem; cursor: pointer; transition: 0.2s; }
        .btn-deliver:hover { background: #222; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión de Entregas';
            $pageSubtitle = 'Control de canjes y premios solicitados';
            include __DIR__ . '/partials/header_admin.php';
        ?>

        <div class="container animate-fade-in">
            
            <div class="modern-section-header">
                <div class="section-title-flex">
                    <div class="section-title-text">
                        <h3>Gestión de Entregas</h3>
                        <span>Control de canjes y premios solicitados</span>
                    </div>
                </div>
                <div class="section-actions">
                    <div class="header-search-modern" style="width: 320px;">
                        <i class='bx bx-search'></i>
                        <input type="text" id="searchBeneficiario" placeholder="Buscar beneficiario..." onkeyup="filterDeliveries()">
                    </div>
                    <div class="chip" style="background: #fff; color: #64748b; border: 1px solid #e2e8f0; font-weight: 700; height: 42px; display: flex; align-items: center; padding: 0 1rem; border-radius: 8px;">
                        Total: <?= count($canjes) ?> registros
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="table-wrapper">
                    <table class="data-table" id="tableDeliveries">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Beneficiario</th>
                                <th>Premio Canjeado</th>
                                <th class="text-center">Estado</th>
                                <th style="text-align: center !important;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($canjes)): ?>
                                <tr>
                                    <td colspan="5" class="empty-table">
                                        <i class='bx bx-info-circle'></i>
                                        Sin canjes registrados en este momento.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($canjes as $c): ?>
                            <tr class="delivery-row">
                                <td class="date-text">
                                    <div style="font-weight: 700; color: #1e293b;">
                                        <?= date('d M Y', strtotime($c['fecha'])) ?>
                                    </div>
                                    <div style="font-size: 0.72rem; opacity: 0.7;">
                                        <?= date('h:i A', strtotime($c['fecha'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="row-client">
                                        <div class="client-info">
                                            <span class="client-name"><?= htmlspecialchars($c['cliente_nombre']) ?></span>
                                            <span class="client-subtext"><?= $c['cliente_celular'] ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row-prize">
                                        <span class="text-medium"><?= htmlspecialchars($c['premio_nombre']) ?></span>
                                    </div>
                                </td>
                                <td style="text-align: center !important;">
                                    <?php if ($c['estado'] === 'pendiente'): ?>
                                        <div style="display: flex; justify-content: center;">
                                            <span class="chip chip-pending" style="padding: 6px 14px; letter-spacing: 0.05em;">
                                                <i class='bx bxs-circle'></i> PENDIENTE
                                            </span>
                                        </div>
                                    <?php elseif ($c['estado'] === 'entregado'): ?>
                                        <div style="display: flex; justify-content: center;">
                                            <span class="chip chip-delivered" style="padding: 6px 14px; letter-spacing: 0.05em;">
                                                <i class='bx bxs-circle'></i> ENTREGADO
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div style="display: flex; justify-content: center;">
                                            <span class="chip chip-rejected" style="padding: 6px 14px; letter-spacing: 0.05em; text-decoration: line-through;">
                                                <i class='bx bxs-circle'></i> <?= strtoupper($c['estado']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                        <!-- Ver Imagen -->
                                        <button class="btn-action blue" onclick="viewPrize('<?= htmlspecialchars($c['premio_nombre']) ?>', '<?= BASE_URL ?>assets/premios/<?= $c['premio_imagen'] ?>')" title="Ver Premio">
                                            <i class='bx bx-show'></i>
                                        </button>

                                        <?php if ($c['estado'] === 'pendiente'): ?>
                                            <!-- Entregar -->
                                            <form action="<?= BASE_URL ?>canjes-admin/actualizar" method="POST" style="display:inline; margin: 0;">
                                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                <input type="hidden" name="estado" value="entregado">
                                                <button type="submit" class="btn-action green" title="Marcar como entregado">
                                                    <i class='bx bx-check'></i>
                                                </button>
                                            </form>

                                            <!-- Cancelar / No Entregar -->
                                            <form action="<?= BASE_URL ?>canjes-admin/actualizar" method="POST" style="display:inline; margin: 0;" onsubmit="return confirmarCancelacion(event, this);">
                                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                <input type="hidden" name="estado" value="cancelado">
                                                <button type="submit" class="btn-action red" title="No entregar/Cancelar">
                                                    <i class='bx bx-block'></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination / Footer Info -->
                <div class="card-footer-premium">
                    <div class="footer-info">
                        Mostrando <span id="pagCount"><?= count($canjes) ?></span> de <span><?= count($canjes) ?></span> registros
                    </div>
                </div>
            </div>

        </div> <!-- .container -->
    </div> <!-- .admin-layout -->

    <script>
        function viewPrize(title, url) {
            Swal.fire({
                title: title,
                imageUrl: url,
                imageWidth: 400,
                imageAlt: title,
                showConfirmButton: false,
                background: '#ffffff',
                color: '#1e293b',
                closeButtonHtml: '<i class="bx bx-x"></i>',
                showCloseButton: true
            });
        }

        function filterDeliveries() {
            const input = document.getElementById('searchBeneficiario');
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll('.delivery-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.querySelector('.client-name').innerText.toLowerCase();
                const celular = row.querySelector('.client-subtext').innerText.toLowerCase();
                const prize = row.querySelector('.text-medium').innerText.toLowerCase();

                if (name.includes(filter) || celular.includes(filter) || prize.includes(filter)) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });

            document.getElementById('pagCount').innerText = visibleCount;
        }

        function confirmarCancelacion(event, form) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se cancelará el canje. Si deseas que los puntos se devuelvan al cliente, asegúrate de procesar la devolución.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff6600',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, esperar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>

    <?php if (isset($_SESSION['flash'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['flash']['type'] ?>',
            title: '<?= $_SESSION['flash']['title'] ?>',
            text: '<?= $_SESSION['flash']['message'] ?>',
            timer: 3000,
            showConfirmButton: false,
            background: '#ffffff',
            color: '#111827',
            confirmButtonColor: '#000000'
        });
    </script>
    <?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
