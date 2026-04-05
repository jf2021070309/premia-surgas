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
        .row-prize { display: flex; align-items: center; gap: 0.75rem; }
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

        <div class="container animate-fade-in" style="padding-top: 0.5rem;">
            
            <div class="modern-section-header" style="justify-content: flex-end; margin-top: 0.5rem;">
                <div class="header-search-modern" style="width: 320px;">
                    <i class='bx bx-search'></i>
                    <input type="text" id="searchBeneficiario" placeholder="Buscar beneficiario..." onkeyup="filterDeliveries()">
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
                                                <i class='bx bxs-circle'></i> Pendiente
                                            </span>
                                        </div>
                                    <?php elseif ($c['estado'] === 'entregado'): ?>
                                        <div style="display: flex; justify-content: center;">
                                            <span class="chip chip-delivered" style="padding: 6px 14px; letter-spacing: 0.05em;">
                                                <i class='bx bxs-circle'></i> Entregado
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div style="display: flex; justify-content: center;">
                                            <span class="chip chip-rejected" style="padding: 6px 14px; letter-spacing: 0.05em; text-decoration: line-through;">
                                                <i class='bx bxs-circle'></i> <?= ucfirst($c['estado']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                        <!-- Ver Imagen -->
                                        <button class="btn-action indigo" onclick="viewPrize('<?= htmlspecialchars($c['premio_nombre']) ?>', '<?= BASE_URL ?>assets/premios/<?= $c['premio_imagen'] ?>')" title="Ver Premio">
                                            <i class='bx bx-show'></i>
                                        </button>

                                        <?php if ($c['estado'] === 'pendiente'): ?>
                                            <!-- Entregar -->
                                            <form action="<?= BASE_URL ?>canjes-admin/actualizar" method="POST" style="display:inline; margin: 0;">
                                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                <input type="hidden" name="estado" value="entregado">
                                                <button type="submit" class="btn-action blue" title="Marcar como entregado">
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
