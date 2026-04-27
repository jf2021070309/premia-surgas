<?php
$pageTitle = 'Incentivos — Reglas';
$pageSubtitle = 'Configura metas y premios por volumen de compra';
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
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
        .stat-card {
            background: #fff; border-radius: 20px; padding: 1.5rem;
            border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex; align-items: center; gap: 1rem;
        }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; flex-shrink: 0;
        }
        .stat-icon.purple { background: #f3e8ff; color: #7c3aed; }
        .stat-icon.green  { background: #dcfce7; color: #16a34a; }
        .stat-icon.amber  { background: #fef3c7; color: #d97706; }
        .stat-icon.blue   { background: #dbeafe; color: #2563eb; }
        .stat-val { font-size: 1.8rem; font-weight: 900; color: #1e293b; line-height: 1; }
        .stat-label { font-size: 0.72rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

        .btn-primary-action {
            background: linear-gradient(135deg, #7c3aed, #6d28d9); color: #fff;
            border: none; padding: 14px 28px; border-radius: 14px;
            font-weight: 800; font-size: 0.9rem; cursor: pointer;
            display: inline-flex; align-items: center; gap: 10px;
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.3);
            transition: all 0.3s; text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-primary-action:hover { transform: translateY(-2px); box-shadow: 0 12px 35px rgba(124, 58, 237, 0.4); }

        .rules-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 1.5rem; }
        .rule-card {
            background: #fff; border-radius: 20px; padding: 1.8rem;
            border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: all 0.3s; position: relative; overflow: hidden;
        }
        .rule-card:hover { transform: translateY(-4px); box-shadow: 0 12px 35px rgba(0,0,0,0.06); }
        .rule-card.inactive { opacity: 0.55; }
        .rule-card.inactive::after {
            content: 'INACTIVA'; position: absolute; top: 16px; right: -30px;
            background: #ef4444; color: #fff; font-size: 0.6rem; font-weight: 900;
            padding: 4px 40px; transform: rotate(45deg); letter-spacing: 1px;
        }

        .rule-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.2rem; }
        .rule-title { font-size: 1.1rem; font-weight: 850; color: #1e293b; line-height: 1.3; }
        .rule-badge {
            font-size: 0.6rem; font-weight: 900; padding: 4px 12px;
            border-radius: 50px; text-transform: uppercase; letter-spacing: 1px;
            flex-shrink: 0;
        }
        .badge-mensual { background: #dbeafe; color: #1d4ed8; }
        .badge-semanal { background: #dcfce7; color: #16a34a; }
        .badge-trimestral { background: #fef3c7; color: #d97706; }

        .rule-meta { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
        .rule-meta-number {
            font-size: 2rem; font-weight: 900; color: #7c3aed; line-height: 1;
        }
        .rule-meta-label { font-size: 0.85rem; color: #64748b; font-weight: 600; }

        .rule-prize {
            background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
            border-radius: 14px; padding: 1rem 1.2rem; margin-bottom: 1.2rem;
            border: 1px solid #e9d5ff;
        }
        .rule-prize-title { font-size: 0.65rem; font-weight: 800; color: #7c3aed; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .rule-prize-desc { font-size: 0.95rem; font-weight: 700; color: #1e293b; }
        .rule-prize-val { font-size: 1.4rem; font-weight: 900; color: #7c3aed; }

        .rule-details { display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem; }
        .rule-detail-item { font-size: 0.78rem; color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 4px; }
        .rule-detail-item i { font-size: 1rem; color: #94a3b8; }

        .rule-actions { display: flex; gap: 0.5rem; border-top: 1px solid #f1f5f9; padding-top: 1rem; }
        .btn-rule-action {
            flex: 1; padding: 10px; border-radius: 12px;
            border: 1px solid #f1f5f9; background: #f8fafc;
            font-weight: 800; font-size: 0.75rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: 0.3s; color: #475569; text-transform: uppercase;
        }
        .btn-rule-action:hover { background: #1e293b; color: #fff; border-color: #1e293b; }
        .btn-rule-action.danger:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

        /* Modal */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px);
            display: none; align-items: center; justify-content: center;
            z-index: 10000; padding: 1.5rem;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: #fff; border-radius: 24px; width: 100%; max-width: 580px;
            max-height: 90vh; overflow-y: auto; padding: 2.5rem;
            box-shadow: 0 40px 100px rgba(0,0,0,0.15);
            animation: modalPop 0.3s ease;
        }
        @keyframes modalPop { from { transform: scale(0.95) translateY(20px); opacity: 0; } }
        .modal-title { font-size: 1.3rem; font-weight: 900; color: #1e293b; margin-bottom: 2rem; display: flex; align-items: center; gap: 10px; }
        .modal-title i { font-size: 1.5rem; color: #7c3aed; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
        .form-row.full { grid-template-columns: 1fr; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label { font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0;
            border-radius: 12px; font-size: 0.9rem; font-weight: 600;
            color: #1e293b; transition: 0.3s; font-family: inherit;
            box-sizing: border-box;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #7c3aed; box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.08); outline: none;
        }
        .form-textarea { min-height: 80px; resize: vertical; }

        .modal-actions { display: flex; gap: 1rem; margin-top: 2rem; }
        .btn-modal-cancel {
            flex: 1; padding: 14px; border-radius: 14px; border: 1.5px solid #e2e8f0;
            background: #fff; font-weight: 800; font-size: 0.85rem; cursor: pointer;
            color: #64748b; transition: 0.3s;
        }
        .btn-modal-cancel:hover { background: #f1f5f9; }
        .btn-modal-save {
            flex: 1; padding: 14px; border-radius: 14px; border: none;
            background: linear-gradient(135deg, #7c3aed, #6d28d9); color: #fff;
            font-weight: 800; font-size: 0.85rem; cursor: pointer;
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.3); transition: 0.3s;
        }
        .btn-modal-save:hover { transform: translateY(-1px); }

        .empty-state {
            padding: 4rem 2rem; text-align: center; background: #fff;
            border-radius: 24px; border: 2px dashed #e2e8f0;
        }
        .empty-state i { font-size: 4rem; color: #cbd5e1; display: block; margin-bottom: 1rem; }
        .empty-state p { font-size: 1rem; color: #94a3b8; font-weight: 600; }

        .content-body { padding: 2.5rem; }

        @media (max-width: 768px) {
            .rules-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .stats-row { grid-template-columns: 1fr 1fr; }
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
                <div class="stat-card">
                    <div class="stat-icon purple"><i class='bx bx-target-lock'></i></div>
                    <div><div class="stat-val"><?= $stats['reglas_activas'] ?></div><div class="stat-label">Reglas Activas</div></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class='bx bx-badge-check'></i></div>
                    <div><div class="stat-val"><?= $stats['vales_activos'] ?></div><div class="stat-label">Vales Vigentes</div></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon amber"><i class='bx bx-check-double'></i></div>
                    <div><div class="stat-val"><?= $stats['vales_usados'] ?></div><div class="stat-label">Vales Usados</div></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class='bx bx-receipt'></i></div>
                    <div><div class="stat-val"><?= $stats['vales_total'] ?></div><div class="stat-label">Total Emitidos</div></div>
                </div>
            </div>

            <!-- Header Actions -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h2 style="font-size: 1.3rem; font-weight: 900; color: #1e293b; margin: 0;">Reglas de Incentivos</h2>
                    <p style="font-size: 0.85rem; color: #94a3b8; font-weight: 600; margin: 4px 0 0;">Define metas de compra y recompensas automáticas</p>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <a href="<?= BASE_URL ?>incentivos/vales" style="text-decoration: none; background: #1e293b; color: #fff; padding: 14px 24px; border-radius: 14px; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; transition: 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                        <i class='bx bx-receipt'></i> Ver Vales
                    </a>
                    <button class="btn-primary-action" onclick="openModal()">
                        <i class='bx bx-plus'></i> Nueva Regla
                    </button>
                </div>
            </div>

            <!-- Rules Grid -->
            <?php if (empty($reglas)): ?>
                <div class="empty-state">
                    <i class='bx bx-target-lock'></i>
                    <p>No hay reglas de incentivos configuradas aún.<br>Crea tu primera regla para empezar a motivar a tus clientes.</p>
                </div>
            <?php else: ?>
                <div class="rules-grid">
                    <?php foreach ($reglas as $r): ?>
                    <div class="rule-card <?= !$r['estado'] ? 'inactive' : '' ?>">
                        <div class="rule-header">
                            <div class="rule-title"><?= htmlspecialchars($r['nombre']) ?></div>
                            <span class="rule-badge badge-<?= $r['periodo'] ?>"><?= strtoupper($r['periodo']) ?></span>
                        </div>
                        
                        <div class="rule-meta">
                            <span class="rule-meta-number"><?= $r['meta_cantidad'] ?></span>
                            <span class="rule-meta-label">operaciones para<br>cumplir la meta</span>
                        </div>

                        <div class="rule-prize">
                            <div class="rule-prize-title">🎁 Premio al cumplir</div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="rule-prize-desc"><?= htmlspecialchars($r['descripcion_premio']) ?></div>
                                <div class="rule-prize-val">
                                    <?php if ($r['tipo_premio'] === 'vale_descuento'): ?>
                                        <?= (int) $r['valor_premio'] ?>%
                                    <?php else: ?>
                                        S/ <?= number_format($r['valor_premio'], 2) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="rule-details">
                            <div class="rule-detail-item"><i class='bx bx-user'></i> <?= $r['tipo_cliente'] ?></div>
                            <div class="rule-detail-item"><i class='bx bx-calendar'></i> Vigencia: <?= $r['vigencia_dias'] ?> días</div>
                            <div class="rule-detail-item"><i class='bx bx-tag'></i> <?= str_replace('_', ' ', $r['tipo_premio']) ?></div>
                        </div>

                        <div class="rule-actions">
                            <button class="btn-rule-action" onclick='openModal(<?= json_encode($r) ?>)'>
                                <i class='bx bx-edit'></i> Editar
                            </button>
                            <button class="btn-rule-action danger" onclick="confirmDelete(<?= $r['id'] ?>, '<?= htmlspecialchars(addslashes($r['nombre'])) ?>')">
                                <i class='bx bx-trash'></i> Eliminar
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- MODAL: Crear / Editar Regla -->
<div class="modal-overlay" id="ruleModal">
    <div class="modal-box">
        <div class="modal-title"><i class='bx bx-target-lock'></i> <span id="modalTitle">Nueva Regla de Incentivo</span></div>
        
        <form id="ruleForm" method="POST" action="<?= BASE_URL ?>incentivos/reglas/create">
            <input type="hidden" name="id" id="ruleId">

            <div class="form-row full">
                <div class="form-group">
                    <label class="form-label">Nombre de la regla *</label>
                    <input type="text" name="nombre" id="rNombre" class="form-input" placeholder="Ej: Promo 10 Galones → Vale 50%" required>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label class="form-label">Descripción (visible al cliente)</label>
                    <textarea name="descripcion" id="rDescripcion" class="form-textarea" placeholder="Texto motivacional que verá el cliente..."></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Aplica a</label>
                    <select name="tipo_cliente" id="rTipoCliente" class="form-select">
                        <option value="Todos">Todos los clientes</option>
                        <option value="Normal">Solo Normal (persona)</option>
                        <option value="Restaurante">Solo Restaurante</option>
                        <option value="Punto de Venta">Solo Punto de Venta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Periodo de evaluación</label>
                    <select name="periodo" id="rPeriodo" class="form-select">
                        <option value="semanal">Semanal</option>
                        <option value="mensual" selected>Mensual</option>
                        <option value="trimestral">Trimestral</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Meta (cantidad de operaciones) *</label>
                    <input type="number" name="meta_cantidad" id="rMeta" class="form-input" min="1" value="10" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipo de premio</label>
                    <select name="tipo_premio" id="rTipoPremio" class="form-select">
                        <option value="vale_descuento">Vale de Descuento (%)</option>
                        <option value="vale_producto">Vale de Producto</option>
                        <option value="vale_dinero">Vale de Dinero (S/)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Valor del premio *</label>
                    <input type="number" name="valor_premio" id="rValorPremio" class="form-input" step="0.01" min="0.01" placeholder="50" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Vigencia del vale (días)</label>
                    <input type="number" name="vigencia_dias" id="rVigencia" class="form-input" min="1" value="30">
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label class="form-label">Descripción del premio *</label>
                    <input type="text" name="descripcion_premio" id="rDescPremio" class="form-input" placeholder="Ej: Vale de 50% en tu próxima compra" required>
                </div>
            </div>

            <div class="form-row full">
                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="estado" id="rEstado" class="form-select">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-modal-save">Guardar Regla</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(data = null) {
    const modal = document.getElementById('ruleModal');
    const form  = document.getElementById('ruleForm');
    
    if (data) {
        document.getElementById('modalTitle').textContent = 'Editar Regla de Incentivo';
        form.action = '<?= BASE_URL ?>incentivos/reglas/update';
        document.getElementById('ruleId').value = data.id;
        document.getElementById('rNombre').value = data.nombre;
        document.getElementById('rDescripcion').value = data.descripcion || '';
        document.getElementById('rTipoCliente').value = data.tipo_cliente;
        document.getElementById('rMeta').value = data.meta_cantidad;
        document.getElementById('rPeriodo').value = data.periodo;
        document.getElementById('rTipoPremio').value = data.tipo_premio;
        document.getElementById('rValorPremio').value = data.valor_premio;
        document.getElementById('rVigencia').value = data.vigencia_dias;
        document.getElementById('rDescPremio').value = data.descripcion_premio;
        document.getElementById('rEstado').value = data.estado;
    } else {
        document.getElementById('modalTitle').textContent = 'Nueva Regla de Incentivo';
        form.action = '<?= BASE_URL ?>incentivos/reglas/create';
        form.reset();
        document.getElementById('ruleId').value = '';
    }
    
    modal.classList.add('active');
}

function closeModal() {
    document.getElementById('ruleModal').classList.remove('active');
}

document.getElementById('ruleModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

function confirmDelete(id, nombre) {
    Swal.fire({
        title: '¿Eliminar regla?',
        text: `Se eliminará "${nombre}" y todos sus vales asociados.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASE_URL ?>incentivos/reglas/delete?id=' + id;
        }
    });
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
