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
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }

        /* Botón de acción principal (Estilo Black Premium - Imagen 2) */
        .btn-black-premium {
            background: #000 !important;
            color: #fff !important;
            border-radius: 14px !important;
            padding: 0.8rem 1.8rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.3px !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important;
            border: none !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 10px !important;
        }
        .btn-black-premium:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25) !important;
            background: #111 !important;
        }
        .btn-black-premium i { font-size: 1.15rem !important;        .rule-card {
            background: #fff; border-radius: 20px; padding: 0;
            border: 1.5px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            transition: all 0.4s ease; 
            position: relative; overflow: hidden;
            display: flex; flex-direction: column;
        }
        .rule-card:hover { transform: translateY(-4px); box-shadow: 0 15px 35px rgba(0,0,0,0.05); border-color: #e2e8f0; }
        
        .rule-section { padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; }
        .rule-section:last-child { border-bottom: none; }

        .rule-header { display: flex; justify-content: space-between; align-items: center; }
        .rule-title { font-size: 1.2rem; font-weight: 800; color: #1e293b; line-height: 1.2; }
        
        .rule-badge {
            font-size: 0.65rem; font-weight: 800; padding: 4px 12px;
            border-radius: 100px; text-transform: uppercase; letter-spacing: 1px;
            background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;
        }
        .rule-badge.frecuencia-mensual { background: #eff6ff; color: #3b82f6; border-color: #dbeafe; }
        .rule-badge.frecuencia-semanal { background: #f0fdf4; color: #16a34a; border-color: #dcfce7; }

        .rule-qty-row { display: flex; align-items: center; gap: 1.5rem; }
        .rule-qty-num { font-size: 3.5rem; font-weight: 950; color: #0f172a; line-height: 1; font-family: 'Inter', serif; }
        .rule-qty-text { font-size: 0.85rem; color: #64748b; font-weight: 600; line-height: 1.4; }
        .rule-qty-text strong { color: #1e293b; font-weight: 800; display: block; }

        /* Elite Prize Box */
        .rule-prize-box {
            background: #0f172a; border-radius: 20px; padding: 1.5rem 1.8rem;
            border: 1px solid rgba(255, 255, 255, 0.05); position: relative;
            margin: 0.5rem 0;
        }
        .rule-prize-label {
            font-size: 0.62rem; font-weight: 900; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 2px;
            margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
        }
        .rule-prize-label::before { content: ''; width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; box-shadow: 0 0 10px #3b82f6; }
        
        .rule-prize-content { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .rule-prize-text { font-size: 1rem; font-weight: 700; color: #fff; line-height: 1.4; max-width: 65%; }
        .rule-prize-val { font-size: 2.2rem; font-weight: 950; color: #fff; font-family: 'Inter', sans-serif; letter-spacing: -1px; }

        .rule-meta-footer { display: flex; align-items: center; gap: 1.5rem; }
        .rule-meta-item { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; font-weight: 600; color: #64748b; }
        .rule-meta-item i { font-size: 1rem; color: #94a3b8; }

        .rule-actions { display: flex; gap: 1rem; padding: 1.5rem 2rem; }
        .btn-rule-ghost {
            flex: 1; height: 42px; border-radius: 12px; border: 1.5px solid #e2e8f0;
            background: #fff; color: #475569; font-size: 0.78rem; font-weight: 800;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.3s; cursor: pointer;
        }
        .btn-rule-ghost:hover { background: #f8fafc; border-color: #cbd5e1; color: #1e293b; transform: translateY(-2px); }
        .btn-rule-ghost.btn-delete:hover { background: #fff1f2; border-color: #fecaca; color: #e11d48; }
   }

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
        .modal-title i { font-size: 1.5rem; color: #800000; }

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
            border-color: #800000; box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.08); outline: none;
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
            background: linear-gradient(135deg, #800000, #5a0000); color: #fff;
            font-weight: 800; font-size: 0.85rem; cursor: pointer;
            box-shadow: 0 8px 25px rgba(128, 0, 0, 0.3); transition: 0.3s;
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
                        <h3>Reglas de Incentivos</h3>
                        <span>Define metas de compra y recompensas automáticas</span>
                    </div>
                </div>
                <div class="section-actions">
                    <a href="<?= BASE_URL ?>incentivos/vales" class="btn-primary-premium btn-black-premium">
                        <i class='bx bx-receipt'></i>
                        <span>Ver Vales</span>
                    </a>
                    <button class="btn-primary-premium btn-black-premium" onclick="openModal()">
                        <i class='bx bx-plus'></i>
                        <span>Nueva Regla</span>
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
                <div class="rules-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 1.5rem;">
                    <?php foreach($reglas as $r): ?>
                    <div class="rule-card <?= $r['estado'] ? '' : 'inactive' ?>">
                        <div class="rule-section rule-header">
                            <h3 class="rule-title"><?= htmlspecialchars($r['nombre']) ?></h3>
                            <span class="rule-badge frecuencia-<?= strtolower($r['periodo']) ?>">
                                <?= strtoupper($r['periodo']) ?>
                            </span>
                        </div>

                        <div class="rule-section">
                            <div class="rule-qty-row">
                                <div class="rule-qty-num"><?= $r['meta_cantidad'] ?></div>
                                <div class="rule-qty-text">
                                    operaciones necesarias<br>para<br><strong>completar la meta</strong>
                                </div>
                            </div>
                        </div>

                        <div class="rule-section">
                            <div class="rule-prize-box">
                                <div class="rule-prize-label">PREMIO ELITE</div>
                                <div class="rule-prize-content">
                                    <div class="rule-prize-text"><?= htmlspecialchars($r['descripcion_premio']) ?></div>
                                    <div class="rule-prize-val">
                                        <?php if ($r['tipo_premio'] === 'vale_descuento'): ?>
                                            <?= (int) $r['valor_premio'] ?>%
                                        <?php else: ?>
                                            S/<?= number_format($r['valor_premio'], 0) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="rule-meta-footer">
                                <div class="rule-meta-item" title="Tipo de Cliente"><i class='bx bx-time-five'></i> <?= $r['tipo_cliente'] ?></div>
                                <div class="rule-meta-item" title="Vigencia"><i class='bx bx-calendar'></i> <?= $r['vigencia_dias'] ?> días</div>
                                <div class="rule-meta-item" title="Categoría"><i class='bx bx-shopping-bag'></i> <?= str_replace('_', ' ', $r['tipo_premio']) ?></div>
                            </div>
                        </div>

                        <div class="rule-actions">
                            <button class="btn-rule-ghost" onclick='openModal(<?= json_encode($r) ?>)'>
                                <i class='bx bx-edit-alt'></i> Editar
                            </button>
                            <button type="button" class="btn-rule-ghost btn-delete" onclick="confirmDelete(<?= $r['id'] ?>, '<?= htmlspecialchars(addslashes($r['nombre'])) ?>')">
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
