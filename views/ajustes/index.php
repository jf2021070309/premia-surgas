<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración General — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        .settings-grid { display: grid; grid-template-columns: 1fr; gap: 2.5rem; margin-bottom: 3rem; }
        .preview-img-circle { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        [data-pagination] { display: none; } /* Hidden by default till JS runs */
    </style>
</head>
<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Configuración General';
            $pageSubtitle = 'Panel maestro de reglas, catálogo y equipo';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container" style="max-width: 1200px; padding-top: 1rem;">
            
            <!-- SECTION 1: PREMIOS -->
            <div class="modern-section-header">
                <div class="section-title-flex">
                    <i class='bx bx-gift'></i>
                    <div class="section-title-text">
                        <h3>Catálogo de Premios</h3>
                        <span>Productos disponibles y sus costos</span>
                    </div>
                </div>
                <div class="section-actions">
                    <div class="header-search-modern">
                        <i class='bx bx-search'></i>
                        <input type="text" placeholder="Buscar" onkeyup="handleSearch('tablePremios', this.value)">
                    </div>
                    <button class="btn-primary-premium" onclick="openModalPremio()">
                        <i class='bx bxs-award'></i> Nuevo Premio
                    </button>

                </div>
            </div>

            <div class="card shadow-sm" id="cardPremios">
                <div class="table-wrapper">
                    <table class="data-table" id="tablePremios">

                        <thead>
                            <tr>
                                <th class="ps-3 text-start">Premio</th>
                                <th class="text-center">Puntos</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center" style="width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($premios as $p): ?>
                            <tr class="table-row">
                                <td class="text-medium ps-3"><?= htmlspecialchars($p['nombre']) ?></td>
                                <td class="text-center"><span class="text-pts-plus"><?= $p['puntos'] ?> pts</span></td>
                                <td class="text-center"><?= $p['stock'] ?></td>
                                <td class="text-center">
                                    <span class="chip <?= $p['estado'] ? 'chip-approved' : 'chip-rejected' ?>">
                                        <i class='bx bxs-circle'></i> <?= $p['estado'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="actions-flex" style="justify-content: center;">
                                        <button class="btn-action indigo" onclick="viewPremioImage('<?= $p['nombre'] ?>', '<?= BASE_URL ?>assets/premios/<?= $p['imagen'] ?>')"><i class='bx bx-show'></i></button>
                                        <button class="btn-action blue" onclick="editPremio(<?= htmlspecialchars(json_encode($p)) ?>)"><i class='bx bx-edit-alt'></i></button>
                                        <button class="btn-action red" onclick="confirmDeletePremio('<?= BASE_URL ?>productos/delete?id=<?= $p['id'] ?>')"><i class='bx bx-trash'></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer-premium" id="footerPremios">
                    <div class="footer-info">Mostrando <span class="range"></span> de <span class="total"></span></div>
                    <div class="pagination-elite" data-pagination="tablePremios"></div>
                </div>
            </div>

            <!-- SECTION 2: OPERACIONES -->
            <div class="modern-section-header" style="margin-top: 3.5rem;">
                <div class="section-title-flex">
                    <i class='bx bx-calculator'></i>
                    <div class="section-title-text">
                        <h3>Reglas de Puntaje</h3>
                        <span>Puntos acumulados por cada operación</span>
                    </div>
                </div>
                <div class="section-actions">
                    <div class="header-search-modern">
                        <i class='bx bx-search'></i>
                        <input type="text" placeholder="Buscar" onkeyup="handleSearch('tableOp', this.value)">
                    </div>
                    <button class="btn-primary-premium" onclick="openModalOp()">
                        <i class='bx bx-layer-plus'></i> Nueva Regla
                    </button>

                </div>
            </div>

            <div class="card shadow-sm" id="cardOp">
                <div class="table-wrapper">
                    <table class="data-table" id="tableOp">

                        <thead>
                            <tr>
                                <th>Nombre Operación</th>
                                <th class="text-center">Puntaje</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center" style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($operaciones as $op): ?>
                            <tr class="table-row">
                                <td class="text-medium"><?= htmlspecialchars($op['nombre']) ?></td>
                                <td class="text-center"><span class="text-pts-plus"><?= $op['puntos'] ?> pts</span></td>
                                <td class="text-center">
                                    <span class="chip <?= $op['estado'] ? 'chip-approved' : 'chip-rejected' ?>">
                                        <i class='bx bxs-circle'></i> <?= $op['estado'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="actions-flex" style="justify-content: center;">
                                        <button class="btn-action blue" onclick="editOp(<?= htmlspecialchars(json_encode($op)) ?>)"><i class='bx bx-edit-alt'></i></button>
                                        <button class="btn-action red" onclick="confirmDeleteOp('<?= BASE_URL ?>operaciones/delete?id=<?= $op['id'] ?>')"><i class='bx bx-trash'></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer-premium" id="footerOp">
                    <div class="footer-info">Mostrando <span class="range"></span> de <span class="total"></span></div>
                    <div class="pagination-elite" data-pagination="tableOp"></div>
                </div>
            </div>

            <!-- SECTION 3: CONDUCTORES -->
            <div class="modern-section-header" style="margin-top: 3.5rem;">
                <div class="section-title-flex">
                    <i class='bx bxs-truck'></i>
                    <div class="section-title-text">
                        <h3>Directorio de Conductores</h3>
                        <span>Control de acceso y perfiles del personal</span>
                    </div>
                </div>
                <div class="section-actions">
                    <div class="header-search-modern">
                        <i class='bx bx-search'></i>
                        <input type="text" placeholder="Buscar" onkeyup="handleSearch('tableCond', this.value)">
                    </div>
                    <button class="btn-primary-premium" onclick="openModalCond()">
                        <i class='bx bx-user-plus'></i> Nuevo Conductor
                    </button>

                </div>
            </div>

            <div class="card shadow-sm" id="cardCond">
                <div class="table-wrapper">
                    <table class="data-table" id="tableCond">

                        <thead>
                            <tr>
                                <th>Nombre Conductor</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center" style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($conductores as $c): ?>
                            <tr class="table-row">
                                <td class="text-medium"><?= htmlspecialchars($c['nombre']) ?></td>
                                <td class="text-center text-muted"><?= htmlspecialchars($c['usuario']) ?></td>
                                <td class="text-center">
                                    <span class="chip <?= $c['estado'] ? 'chip-approved' : 'chip-rejected' ?>">
                                        <i class='bx bxs-circle'></i> <?= $c['estado'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="actions-flex" style="justify-content: center;">
                                        <button class="btn-action blue" onclick="editCond(<?= htmlspecialchars(json_encode($c)) ?>)"><i class='bx bx-edit-alt'></i></button>
                                        <button class="btn-action red" onclick="confirmDeleteCond('<?= BASE_URL ?>conductores/delete?id=<?= $c['id'] ?>')"><i class='bx bx-trash'></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer-premium" id="footerCond">
                    <div class="footer-info">Mostrando <span class="range"></span> de <span class="total"></span></div>
                    <div class="pagination-elite" data-pagination="tableCond"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL OPERACIONES -->
    <div id="modalOp" class="modal-overlay" style="display: none;" onclick="if(event.target===this) closeModalOp()">
        <div class="modal-content-wrapper" style="max-width: 480px; padding: 0;">
            <div class="modal-header-premium">
                <h2 id="modalTitleOp">Nueva Regla de Puntaje</h2>
                <div class="modal-close" onclick="closeModalOp()"><i class='bx bx-x'></i></div>
            </div>
            <form id="formOp" method="POST" action="<?= BASE_URL ?>operaciones/create">
                <div class="modal-body-premium">
                    <input type="hidden" name="id" id="op_id">
                    <input type="hidden" name="redir" value="ajustes">
                    <div class="form-group">
                        <label class="form-label-premium">Nombre de la Operación</label>
                        <div class="input-icon-wrapper">
                            <i class='bx bx-rename'></i>
                            <input type="text" name="nombre" id="op_nombre" class="form-input-premium" placeholder="Ej: Recarga Gas 10kg" required>
                        </div>
                    </div>
                    <div class="modal-grid-2">
                        <div class="form-group">
                            <label class="form-label-premium">Puntos</label>
                            <div class="input-icon-wrapper">
                                <i class='bx bx-medal'></i>
                                <input type="number" name="puntos" id="op_puntos" class="form-input-premium" value="0" required>
                            </div>
                        </div>
                        <div class="form-group" id="group_estadoOp" style="display: none;">
                            <label class="form-label-premium">Estado</label>
                            <div class="input-icon-wrapper">
                                <i class='bx bx-toggle-right'></i>
                                <select name="estado" id="op_estado" class="form-input-premium">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-premium">
                    <button type="submit" class="btn-premium-pill-black">Guardar Regla</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PREMIOS -->
    <div id="modalPremio" class="modal-overlay" style="display: none;" onclick="if(event.target===this) closeModalPremio()">
        <div class="modal-content-wrapper" style="max-width: 550px; padding: 0;">
            <div class="modal-header-premium">
                <h2 id="modalTitlePremio">Gestionar Premio</h2>
                <div class="modal-close" onclick="closeModalPremio()"><i class='bx bx-x'></i></div>
            </div>
            <form id="formPremio" method="POST" action="<?= BASE_URL ?>productos/create" enctype="multipart/form-data">
                <div class="modal-body-premium">
                    <input type="hidden" name="id" id="premio_id">
                    <input type="hidden" name="redir" value="ajustes">
                    <div class="form-group">
                        <label class="form-label-premium">Nombre Comercial del Premio</label>
                        <div class="input-icon-wrapper">
                            <i class='bx bx-shopping-bag'></i>
                            <input type="text" name="nombre" id="premio_nombre" class="form-input-premium" required>
                        </div>
                    </div>
                    <div class="modal-grid-2">
                        <div class="form-group">
                            <label class="form-label-premium">Inversión Puntos</label>
                            <div class="input-icon-wrapper">
                                <i class='bx bx-star'></i>
                                <input type="number" name="puntos" id="premio_puntos" class="form-input-premium" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Stock Actual</label>
                            <div class="input-icon-wrapper">
                                <i class='bx bx-box'></i>
                                <input type="number" name="stock" id="premio_stock" class="form-input-premium" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-premium">Cambiar Imagen</label>
                        <div class="input-icon-wrapper">
                            <i class='bx bx-image-add'></i>
                            <input type="file" name="imagen_file" class="form-input-premium">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-premium">Estado en Tienda</label>
                        <div class="input-icon-wrapper">
                            <i class='bx bx-show-alt'></i>
                            <select name="estado" id="premio_estado" class="form-input-premium">
                                <option value="1">Activo / Visible</option>
                                <option value="0">Inactivo / Oculto</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-premium">
                    <button type="submit" class="btn-premium-pill-black">Actualizar Catálogo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONDUCTORES -->
    <div id="modalCond" class="modal-overlay" style="display: none;" onclick="if(event.target===this) closeModalCond()">
        <div class="modal-content-wrapper" style="max-width: 480px; padding: 0;">
            <div class="modal-header-premium">
                <h2 id="modalTitleCond">Datos del Conductor</h2>
                <div class="modal-close" onclick="closeModalCond()"><i class='bx bx-x'></i></div>
            </div>
            <form id="formCond" method="POST" action="<?= BASE_URL ?>conductores/create">
                <div class="modal-body-premium">
                    <input type="hidden" name="id" id="cond_id">
                    <input type="hidden" name="redir" value="ajustes">
                    <div class="form-group">
                        <label class="form-label-premium">Nombre y Apellidos</label>
                        <div class="input-icon-wrapper">
                            <i class='bx bx-user'></i>
                            <input type="text" name="nombre" id="cond_nombre" class="form-input-premium" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-premium">Nombre de Usuario</label>
                        <div class="input-icon-wrapper">
                            <i class='bx bx-at'></i>
                            <input type="text" name="usuario" id="cond_usuario" class="form-input-premium" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-premium">Nueva Contraseña</label>
                        <div class="input-icon-wrapper">
                            <i class='bx bx-lock-alt'></i>
                            <input type="password" name="password" id="cond_pass" class="form-input-premium" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-premium">Estado de Acceso</label>
                        <div class="input-icon-wrapper">
                            <i class='bx bx-user-check'></i>
                            <select name="estado" id="cond_estado" class="form-input-premium">
                                <option value="1">Acceso Permitido</option>
                                <option value="0">Bloqueado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-premium">
                    <button type="submit" class="btn-premium-pill-black">Guardar Perfil</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- PHOTO PREVIEW ---
        function viewPremioImage(name, url) {
            const existing = document.getElementById('premioPreviewOverlay');
            if (existing) existing.remove();

            const overlay = document.createElement('div');
            overlay.id = 'premioPreviewOverlay';
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
                    <button onclick="document.getElementById('premioPreviewOverlay').remove()" style="
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
                        <div style="font-size: 0.62rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px;">Premio</div>
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

        // --- PAGINATION MODULE ---
        const PagData = {
            tableOp:      { page: 1, size: 5, search: '', footer: 'footerOp' },
            tablePremios: { page: 1, size: 10, search: '', footer: 'footerPremios' },
            tableCond:    { page: 1, size: 5, search: '', footer: 'footerCond' }
        };

        function renderPagination(tableId) {
            const config = PagData[tableId];
            const table = document.getElementById(tableId);
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            
            // Filter
            const visibleRows = allRows.filter(row => {
                const matchesSearch = row.innerText.toLowerCase().includes(config.search.toLowerCase());
                return matchesSearch;
            });

            const total = visibleRows.length;
            const totalPages = Math.ceil(total / config.size);
            
            if (config.page > totalPages) config.page = Math.max(1, totalPages);
            allRows.forEach(r => r.style.display = 'none');
            const start = (config.page - 1) * config.size;
            const end = start + config.size;
            const pageRows = visibleRows.slice(start, end);
            pageRows.forEach(r => r.style.display = '');

            const footer = document.getElementById(config.footer);
            const rangeSpan = footer.querySelector('.range');
            const totalSpan = footer.querySelector('.total');
            rangeSpan.innerText = total > 0 ? `${start + 1} - ${Math.min(end, total)}` : '0';
            totalSpan.innerText = total;

            const pagContainer = document.querySelector(`[data-pagination="${tableId}"]`);
            if (totalPages <= 1) {
                pagContainer.style.display = 'none';
                return;
            }

            pagContainer.style.display = 'flex';
            let html = `<a href="javascript:void(0)" class="page-btn nav-arrows ${config.page === 1 ? 'disabled' : ''}" onclick="changePage('${tableId}', ${config.page - 1})"><i class='bx bx-chevron-left'></i></a>`;
            for (let i = 1; i <= totalPages; i++) {
                html += `<a href="javascript:void(0)" class="page-btn ${i === config.page ? 'active' : ''}" onclick="changePage('${tableId}', i)">${i}</a>`;
            }
            html += `<a href="javascript:void(0)" class="page-btn nav-arrows ${config.page === totalPages ? 'disabled' : ''}" onclick="changePage('${tableId}', ${config.page + 1})"><i class='bx bx-chevron-right'></i></a>`;
            // Fix loop variable i check
            html = `<a href="javascript:void(0)" class="page-btn nav-arrows ${config.page === 1 ? 'disabled' : ''}" onclick="changePage('${tableId}', ${config.page - 1})"><i class='bx bx-chevron-left'></i></a>`;
            for (let i = 1; i <= totalPages; i++) {
                html += `<a href="javascript:void(0)" class="page-btn ${i === config.page ? 'active' : ''}" onclick="changePage('${tableId}', ${i})">${i}</a>`;
            }
            html += `<a href="javascript:void(0)" class="page-btn nav-arrows ${config.page === totalPages ? 'disabled' : ''}" onclick="changePage('${tableId}', ${config.page + 1})"><i class='bx bx-chevron-right'></i></a>`;
            pagContainer.innerHTML = html;
        }

        function changePage(tableId, targetPage) {
            const config = PagData[tableId];
            if (targetPage < 1) return;
            config.page = targetPage;
            renderPagination(tableId);
        }

        function handleSearch(tableId, val) {
            PagData[tableId].search = val;
            PagData[tableId].page = 1;
            renderPagination(tableId);
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderPagination('tableOp');
            renderPagination('tablePremios');
            renderPagination('tableCond');
        });

        function openModalOp() {
            document.getElementById('modalOp').style.display = 'flex';
            document.getElementById('formOp').action = '<?= BASE_URL ?>operaciones/create';
            document.getElementById('modalTitleOp').innerText = 'Nueva Regla de Puntaje';
            document.getElementById('formOp').reset();
            document.getElementById('group_estadoOp').style.display = 'none';
        }
        function closeModalOp() { document.getElementById('modalOp').style.display = 'none'; }
        
        function editOp(op) {
            document.getElementById('modalOp').style.display = 'flex';
            document.getElementById('formOp').action = '<?= BASE_URL ?>operaciones/update';
            document.getElementById('modalTitleOp').innerText = 'Editar Regla';
            document.getElementById('op_id').value = op.id;
            document.getElementById('op_nombre').value = op.nombre;
            document.getElementById('op_puntos').value = op.puntos;
            document.getElementById('op_estado').value = op.estado;
            document.getElementById('group_estadoOp').style.display = 'block';
        }

        function confirmDeleteOp(url) {
            Swal.fire({ title: '¿Inactivar regla?', text: "Se quitará de las opciones de carga de puntos.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#400000', confirmButtonText: 'Sí, inactivar' })
                .then((r) => { if(r.isConfirmed) window.location.href = url + '&redir=ajustes'; });
        }

        function openModalPremio() {
            document.getElementById('modalPremio').style.display = 'flex';
            document.getElementById('formPremio').action = '<?= BASE_URL ?>productos/create';
            document.getElementById('modalTitlePremio').innerText = 'Registrar Nuevo Premio';
            document.getElementById('formPremio').reset();
            document.getElementById('premio_id').value = '';
        }
        function closeModalPremio() { document.getElementById('modalPremio').style.display = 'none'; }
        
        function editPremio(p) {
            document.getElementById('modalPremio').style.display = 'flex';
            document.getElementById('formPremio').action = '<?= BASE_URL ?>productos/update';
            document.getElementById('modalTitlePremio').innerText = 'Ajustar Inventario';
            document.getElementById('premio_id').value = p.id;
            document.getElementById('premio_nombre').value = p.nombre;
            document.getElementById('premio_puntos').value = p.puntos;
            document.getElementById('premio_stock').value = p.stock;
            document.getElementById('premio_estado').value = p.estado;
        }

        function confirmDeletePremio(url) {
            Swal.fire({ title: '¿Ocultar premio?', text: "Los clientes ya no podrán verlo en el catálogo.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#400000', confirmButtonText: 'Sí, ocultar' })
                .then((r) => { if(r.isConfirmed) window.location.href = url + '&redir=ajustes'; });
        }

        function openModalCond() {
            document.getElementById('modalCond').style.display = 'flex';
            document.getElementById('formCond').action = '<?= BASE_URL ?>conductores/create';
            document.getElementById('modalTitleCond').innerText = 'Dar de alta Conductor';
            document.getElementById('formCond').reset();
            document.getElementById('cond_id').value = '';
        }
        function closeModalCond() { document.getElementById('modalCond').style.display = 'none'; }
        
        function editCond(c) {
            document.getElementById('modalCond').style.display = 'flex';
            document.getElementById('formCond').action = '<?= BASE_URL ?>conductores/update';
            document.getElementById('modalTitleCond').innerText = 'Modificar Perfil';
            document.getElementById('cond_id').value = c.id;
            document.getElementById('cond_nombre').value = c.nombre;
            document.getElementById('cond_usuario').value = c.usuario;
            document.getElementById('cond_estado').value = c.estado;
        }

        function confirmDeleteCond(url) {
            Swal.fire({ title: '¿Bloquear conductor?', text: "Se le denegará el acceso al sistema de escaneo.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#400000', confirmButtonText: 'Sí, bloquear' })
                .then((r) => { if(r.isConfirmed) window.location.href = url + '&redir=ajustes'; });
        }
    </script>

    <?php if (isset($_SESSION['flash'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['flash']['type'] ?>',
            title: '<?= $_SESSION['flash']['title'] ?>',
            text: '<?= $_SESSION['flash']['message'] ?>',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    <?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
