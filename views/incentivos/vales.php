<?php
$pageTitle = 'Incentivos — Vales Emitidos';
$pageSubtitle = 'Gestión y verificación de vales de descuento / premios';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }

        /* Botón Midnight */
        .btn-midnight {
            background: #1e293b !important;
            box-shadow: 0 8px 25px rgba(30, 41, 59, 0.15) !important;
        }

        .table-badge { padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .badge-activo { background: #dcfce7; color: #16a34a; }
        .badge-usado { background: #fef3c7; color: #d97706; }
        .badge-vencido { background: #fee2e2; color: #ef4444; }
        .badge-cancelado { background: #f1f5f9; color: #64748b; }

        .btn-action-sm {
            padding: 6px 12px; border-radius: 8px; font-weight: 800; font-size: 0.7rem;
            border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;
            transition: 0.2s; text-transform: uppercase;
        }
        .btn-use { background: #1e293b; color: #fff; }
        .btn-use:hover { background: #0f172a; }
        .btn-cancel { background: #fee2e2; color: #ef4444; }
        .btn-cancel:hover { background: #fecaca; }

        .filter-bar {
            display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center;
            background: #fff; padding: 1rem 1.5rem; border-radius: 16px;
            border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .filter-input {
            padding: 10px 16px; border: 1.5px solid #e2e8f0; border-radius: 12px;
            font-size: 0.85rem; font-weight: 600; outline: none; transition: 0.3s;
            min-width: 250px;
        }
        .filter-input:focus { border-color: #800000; }
        .filter-select {
            padding: 10px 16px; border: 1.5px solid #e2e8f0; border-radius: 12px;
            font-size: 0.85rem; font-weight: 600; outline: none; background: #fff;
        }

        .content-body { padding: 2.5rem; }
        
        @media (max-width: 768px) {
            .content-body { padding: 1.5rem; }
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

<div class="admin-layout">
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header_admin.php'; ?>

        <div class="content-body">
            <!-- Stats -->
            <div class="stats-row">
                <div class="dash-card card-dark">
                    <div class="dash-card-body">
                        <div class="dash-card-number"><?= $stats['reglas_activas'] ?></div>
                        <div class="dash-card-text">Reglas Activas</div>
                        <i class='bx bx-target-lock dash-card-icon'></i>
                    </div>
                </div>
                <div class="dash-card card-orange">
                    <div class="dash-card-body">
                        <div class="dash-card-number"><?= $stats['vales_activos'] ?></div>
                        <div class="dash-card-text">Vales Vigentes</div>
                        <i class='bx bx-badge-check dash-card-icon'></i>
                    </div>
                </div>
                <div class="dash-card card-wine">
                    <div class="dash-card-body">
                        <div class="dash-card-number"><?= $stats['vales_usados'] ?></div>
                        <div class="dash-card-text">Vales Usados</div>
                        <i class='bx bx-check-double dash-card-icon'></i>
                    </div>
                </div>
                <div class="dash-card card-dark">
                    <div class="dash-card-body">
                        <div class="dash-card-number"><?= $stats['vales_total'] ?></div>
                        <div class="dash-card-text">Total Emitidos</div>
                        <i class='bx bx-receipt dash-card-icon'></i>
                    </div>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="modern-section-header">
                <div class="section-title-flex">
                    <div class="section-title-text">
                        <h3>Vales Emitidos</h3>
                        <span>Historial y validación de vales generados</span>
                    </div>
                </div>
                <div class="section-actions">
                    <a href="<?= BASE_URL ?>incentivos/reglas" class="btn-primary-premium btn-midnight">
                        <i class='bx bx-cog'></i>
                        <span>Configurar Reglas</span>
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="clientes-toolbar">
                <div class="clientes-toolbar-filters">
                    <div class="header-search-modern">
                        <i class='bx bx-filter-alt'></i>
                        <select id="statusFilter" onchange="filterTable()">
                            <option value="all">Todos los estados</option>
                            <option value="activo">Activos</option>
                            <option value="usado">Usados</option>
                            <option value="vencido">Vencidos</option>
                            <option value="cancelado">Cancelados</option>
                        </select>
                    </div>
                </div>
                <div class="clientes-toolbar-search">
                    <div class="header-search-modern clientes-search-input">
                        <i class='bx bx-search'></i>
                        <input type="text" id="searchInput" placeholder="Buscar por código, cliente o DNI/RUC..." onkeyup="filterTable()">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="table-wrapper">
                    <table class="data-table" id="valesTable">
                    <thead>
                        <tr>
                            <th style="text-align: left;">Código</th>
                            <th style="text-align: left;">Cliente</th>
                            <th style="text-align: left;">Regla / Premio</th>
                            <th style="text-align: left;">Emisión/Vence</th>
                            <th class="text-center">Estado</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($vales)): ?>
                        <tr><td colspan="6" style="text-align: center; padding: 3rem; color: #94a3b8; font-weight: 600;">No hay vales registrados</td></tr>
                        <?php else: ?>
                            <?php foreach($vales as $v): ?>
                            <tr class="vale-row" data-status="<?= $v['estado'] ?>" style="border-bottom: 1px solid #f8fafc;">
                                <td style="padding: 1rem;">
                                    <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem; font-family: monospace; letter-spacing: 0.5px;"><?= $v['codigo'] ?></div>
                                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Metas: <?= $v['cantidad_lograda'] ?> ops</div>
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem;"><?= htmlspecialchars($v['cliente_nombre']) ?></div>
                                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;"><?= $v['cliente_codigo'] ?> • <?= $v['tipo_cliente'] ?></div>
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="font-weight: 700; color: #800000; font-size: 0.85rem; margin-bottom: 2px;"><?= htmlspecialchars($v['descripcion']) ?></div>
                                    <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600;">Regla: <?= htmlspecialchars($v['regla_nombre']) ?></div>
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="font-size: 0.85rem; color: #475569; font-weight: 600;"><?= date('d/m/Y', strtotime($v['fecha_emision'])) ?></div>
                                    <div style="font-size: 0.75rem; color: <?= (strtotime($v['fecha_vencimiento']) < time() && $v['estado'] === 'activo') ? '#ef4444' : '#94a3b8' ?>; font-weight: 600;">
                                        Vence: <?= date('d/m/Y', strtotime($v['fecha_vencimiento'])) ?>
                                    </div>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <span class="table-badge badge-<?= $v['estado'] ?>"><?= strtoupper($v['estado']) ?></span>
                                    <?php if($v['estado'] === 'usado'): ?>
                                        <div style="font-size: 0.65rem; color: #94a3b8; margin-top: 4px; font-weight: 600;">
                                            <?= date('d/m/Y H:i', strtotime($v['usado_fecha'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <?php if($v['estado'] === 'activo'): ?>
                                        <form method="POST" action="<?= BASE_URL ?>incentivos/vales/usar" style="display:inline;" onsubmit="return confirmAction(event, '¿Marcar vale como usado?', 'Se registrará que el cliente redimió este vale.', 'Sí, usar vale', '#1e293b')">
                                            <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                            <button type="submit" class="btn-action-sm btn-use" title="Marcar como usado">
                                                <i class='bx bx-check-circle'></i> Usar
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= BASE_URL ?>incentivos/vales/cancelar" style="display:inline;" onsubmit="return confirmAction(event, '¿Cancelar vale?', 'El vale quedará inválido de forma permanente.', 'Sí, cancelar', '#ef4444')">
                                            <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                            <button type="submit" class="btn-action-sm btn-cancel" title="Cancelar vale">
                                                <i class='bx bx-x'></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>

<script>
function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.vale-row');

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const rowStatus = row.getAttribute('data-status');
        
        let show = true;
        if (search && !text.includes(search)) show = false;
        if (status !== 'all' && rowStatus !== status) show = false;

        row.style.display = show ? '' : 'none';
    });
}

function confirmAction(e, title, text, btnText, btnColor) {
    e.preventDefault();
    const form = e.target;
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: btnColor,
        cancelButtonColor: '#94a3b8',
        confirmButtonText: btnText,
        cancelButtonText: 'No, regresar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}

// Flash Messages
<?php if (isset($_SESSION['flash'])): ?>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        timer: 3000,
        showConfirmButton: false
    });
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
</script>
<script> const BASE_URL = '<?= BASE_URL ?>'; </script>
<script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
