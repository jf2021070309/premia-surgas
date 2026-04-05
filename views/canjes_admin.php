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
                <div class="header-search-modern" style="width: 170px;">
                    <i class='bx bx-calendar'></i>
                    <input type="date" id="filterFecha" onchange="filterDeliveries()" title="Filtrar por fecha">
                </div>
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
        function viewPrize(name, url) {
            const existing = document.getElementById('prizePreviewOverlay');
            if (existing) existing.remove();

            const overlay = document.createElement('div');
            overlay.id = 'prizePreviewOverlay';
            overlay.style.cssText = `
                position: fixed; inset: 0; z-index: 9999;
                background: rgba(15, 23, 42, 0.7);
                backdrop-filter: blur(8px);
                display: flex; align-items: center; justify-content: center;
                animation: fadeInOverlay 0.2s ease;
            `;
            overlay.onclick = (e) => { if (e.target === overlay) overlay.remove(); };

            overlay.innerHTML = `
                <div style="
                    background: #fff; border-radius: 24px;
                    padding: 0; max-width: 400px; width: 90%;
                    box-shadow: 0 40px 80px rgba(0,0,0,0.25);
                    overflow: hidden;
                    animation: slideUpModal 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
                    position: relative;
                ">
                    <button onclick="document.getElementById('prizePreviewOverlay').remove()" style="
                        position: absolute; top: 12px; right: 12px;
                        width: 32px; height: 32px; border-radius: 50%;
                        border: none; background: rgba(0,0,0,0.06);
                        color: #64748b; font-size: 1.3rem; cursor: pointer;
                        display: flex; align-items: center; justify-content: center;
                        z-index: 10; transition: background 0.2s;
                    " onmouseover="this.style.background='rgba(0,0,0,0.12)'" onmouseout="this.style.background='rgba(0,0,0,0.06)'">
                        <i class='bx bx-x'></i>
                    </button>
                    <div style="
                        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                        padding: 44px 40px 32px;
                        display: flex; align-items: center; justify-content: center;
                        min-height: 250px;
                    ">
                        <img src="${url}" alt="${name}" style="
                            max-width: 200px; max-height: 200px; object-fit: contain;
                            filter: drop-shadow(0 16px 32px rgba(0,0,0,0.14));
                        " onerror="this.src='https://placehold.co/200x200?text=Sin+Imagen'">
                    </div>
                    <div style="padding: 20px 28px 26px; border-top: 1px solid #f1f5f9;">
                        <div style="font-size: 0.62rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px;">Premio Canjeado</div>
                        <div style="font-size: 1.1rem; font-weight: 700; color: #1e293b;">${name}</div>
                    </div>
                </div>
                <style>
                    @keyframes fadeInOverlay { from { opacity:0; } to { opacity:1; } }
                    @keyframes slideUpModal  { from { opacity:0; transform: translateY(18px) scale(0.96); } to { opacity:1; transform: translateY(0) scale(1); } }
                </style>
            `;
            document.body.appendChild(overlay);
        }

        function filterDeliveries() {
            const searchVal = document.getElementById('searchBeneficiario').value.toLowerCase();
            const dateVal   = document.getElementById('filterFecha').value; // "YYYY-MM-DD"
            const rows = document.querySelectorAll('.delivery-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name    = row.querySelector('.client-name').innerText.toLowerCase();
                const celular = row.querySelector('.client-subtext').innerText.toLowerCase();
                const prize   = row.querySelector('.text-medium').innerText.toLowerCase();

                // Search match
                const matchesSearch = !searchVal ||
                    name.includes(searchVal) ||
                    celular.includes(searchVal) ||
                    prize.includes(searchVal);

                // Date match — row date-text contains e.g. "25 Mar 2026"
                let matchesDate = true;
                if (dateVal) {
                    const [y, m, d] = dateVal.split('-');
                    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    const jsDate = new Date(+y, +m - 1, +d);
                    const formatted = `${String(+d).padStart(2,'0')} ${months[jsDate.getMonth()]} ${y}`;
                    const rowDateEl = row.querySelector('.date-text');
                    matchesDate = rowDateEl ? rowDateEl.innerText.includes(formatted) : true;
                }

                if (matchesSearch && matchesDate) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
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
