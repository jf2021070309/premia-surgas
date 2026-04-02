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
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .chip-puntos { background: #fdf2f2; color: #800000; border: 1px solid #fee2e2; font-weight: 800; padding: 4px 10px; border-radius: 8px; font-size: 0.85rem; }
        .chip-active { background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; }
        .chip-inactive { background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; }
        
        .preview-img-circle {
            width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
            border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .header-actions-group {
            display: flex; gap: 0.75rem;
        }

        .section-separator {
            height: 1px; background: #e2e8f0; margin: 3rem 0;
            position: relative;
        }
        .section-separator::after {
            content: attr(data-label);
            position: absolute; left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            background: var(--bg); padding: 0 1.5rem;
            font-size: 0.7rem; font-weight: 800; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.15em;
        }
        .form-label-premium { display: block; font-size: 0.72rem; font-weight: 800; color: #94a3b8; margin-bottom: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .modal-overlay { z-index: 1000 !important; }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Configuración General';
            $pageSubtitle = 'Administra reglas, premios y equipo en un solo lugar';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container" style="max-width: 1200px; padding-top: 1rem;">
            
            <!-- SECTION 1: OPERACIONES -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title-icon red-premium">
                            <i class='bx bx-calculator'></i>
                        </div>
                        Reglas de Puntaje
                    </div>
                    <div class="header-actions">
                        <button class="btn-premium-pill-black" onclick="openModalOp()">
                            <i class='bx bx-plus'></i> <span>Nueva Regla</span>
                        </button>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre Operación</th>
                                <th class="text-center">Puntaje</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($operaciones as $op): ?>
                            <tr>
                                <td class="text-medium"><?= htmlspecialchars($op['nombre']) ?></td>
                                <td class="text-center"><span class="chip-puntos"><?= $op['puntos'] ?> pts</span></td>
                                <td class="text-center">
                                    <span class="chip <?= $op['estado'] ? 'chip-active' : 'chip-inactive' ?>">
                                        <?= $op['estado'] ? 'ACTIVO' : 'INACTIVO' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="actions-flex" style="justify-content: center;">
                                        <button class="btn-action blue" onclick="editOp(<?= htmlspecialchars(json_encode($op)) ?>)" title="Editar">
                                            <i class='bx bx-edit-alt'></i>
                                        </button>
                                        <button class="btn-action red" onclick="confirmDeleteOp('<?= BASE_URL ?>operaciones/delete?id=<?= $op['id'] ?>')" title="Inactivar">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: PREMIOS -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title-icon" style="background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5;">
                            <i class='bx bx-gift'></i>
                        </div>
                        Catálogo de Premios
                    </div>
                    <div class="header-actions">
                        <button class="btn-premium-pill-black" onclick="openModalPremio()">
                            <i class='bx bx-plus'></i> <span>Nuevo Premio</span>
                        </button>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="ps-3">Imagen</th>
                                <th>Premio</th>
                                <th class="text-center">Puntos</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($premios as $p): ?>
                            <tr>
                                <td class="ps-3">
                                    <img src="<?= BASE_URL ?>assets/premios/<?= $p['imagen'] ?>" class="preview-img-circle" onerror="this.src='<?= BASE_URL ?>assets/premios/default.png'">
                                </td>
                                <td class="text-medium"><?= htmlspecialchars($p['nombre']) ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark" style="border: 1px solid #e2e8f0;"><?= $p['puntos'] ?> pts</span></td>
                                <td class="text-center"><?= $p['stock'] ?></td>
                                <td class="text-center">
                                    <div class="actions-flex" style="justify-content: center;">
                                        <button class="btn-action blue" onclick="editPremio(<?= htmlspecialchars(json_encode($p)) ?>)" title="Editar">
                                            <i class='bx bx-edit-alt'></i>
                                        </button>
                                        <button class="btn-action red" onclick="confirmDeletePremio('<?= BASE_URL ?>productos/delete?id=<?= $p['id'] ?>')" title="Inactivar">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 3: CONDUCTORES -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title-icon" style="background: #f0fdf4; color: #166534; border: 1px solid #dcfce7;">
                            <i class='bx bxs-truck'></i>
                        </div>
                        Directorio de Conductores
                    </div>
                    <div class="header-actions">
                        <button class="btn-premium-pill-black" onclick="openModalCond()">
                            <i class='bx bx-plus'></i> <span>Nuevo Conductor</span>
                        </button>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre Conductor</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($conductores as $c): ?>
                            <tr>
                                <td class="text-medium"><?= htmlspecialchars($c['nombre']) ?></td>
                                <td class="text-center text-muted"><?= htmlspecialchars($c['usuario']) ?></td>
                                <td class="text-center">
                                    <span class="chip <?= $c['estado'] ? 'chip-active' : 'chip-inactive' ?>">
                                        <?= $c['estado'] ? 'ACTIVO' : 'INACTIVO' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="actions-flex" style="justify-content: center;">
                                        <button class="btn-action blue" onclick="editCond(<?= htmlspecialchars(json_encode($c)) ?>)" title="Editar">
                                            <i class='bx bx-edit-alt'></i>
                                        </button>
                                        <button class="btn-action red" onclick="confirmDeleteCond('<?= BASE_URL ?>conductores/delete?id=<?= $c['id'] ?>')" title="Inactivar">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- .container -->
    </div> <!-- .admin-layout -->

    <!-- MODAL OPERACIONES -->
    <div id="modalOp" class="modal-overlay" style="display: none;" onclick="if(event.target===this) closeModalOp()">
        <div class="modal-content-wrapper" style="max-width: 450px; padding: 0;">
            <div style="padding: 2.25rem; border-bottom: 1px solid var(--outline); position: relative; background: #fff; border-radius: 20px 20px 0 0;">
                <h2 id="modalTitleOp" style="font-weight: 800; font-size: 1.15rem; color: #1e293b; margin: 0; letter-spacing: -0.01em;">Nueva Operación</h2>
                <div class="modal-close" onclick="closeModalOp()" style="top: 2rem; right: 2rem;">
                    <i class='bx bx-x'></i>
                </div>
            </div>
            <form id="formOp" method="POST" action="<?= BASE_URL ?>operaciones/create" style="padding: 2.5rem; background: #fff; border-radius: 0 0 20px 20px;">
                <input type="hidden" name="id" id="op_id">
                <input type="hidden" name="redir" value="ajustes">
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label class="form-label-premium">Nombre Operación</label>
                    <input type="text" name="nombre" id="op_nombre" class="form-input-premium" style="width: 100%;" required>
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label class="form-label-premium">Puntos</label>
                    <input type="number" name="puntos" id="op_puntos" class="form-input-premium" style="width: 100%;" required>
                </div>
                <div class="form-group" id="group_estadoOp" style="display: none; margin-bottom: 2rem;">
                    <label class="form-label-premium">Estado</label>
                    <select name="estado" id="op_estado" class="form-input-premium" style="width: 100%;">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; margin-top: 3rem;">
                    <button type="submit" class="btn-premium-pill-black"><i class='bx bx-save'></i> Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PREMIOS -->
    <div id="modalPremio" class="modal-overlay" style="display: none;" onclick="if(event.target===this) closeModalPremio()">
        <div class="modal-content-wrapper" style="max-width: 500px; padding: 0;">
            <div style="padding: 2.25rem; border-bottom: 1px solid var(--outline); position: relative; background: #fff; border-radius: 20px 20px 0 0;">
                <h2 id="modalTitlePremio" style="font-weight: 800; font-size: 1.15rem;">Nuevo Premio</h2>
                <div class="modal-close" onclick="closeModalPremio()"><i class='bx bx-x'></i></div>
            </div>
            <form id="formPremio" method="POST" action="<?= BASE_URL ?>productos/create" enctype="multipart/form-data" style="padding: 2.5rem; background: #fff;">
                <input type="hidden" name="id" id="premio_id">
                <input type="hidden" name="redir" value="ajustes">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label-premium">Nombre</label>
                    <input type="text" name="nombre" id="premio_nombre" class="form-input-premium" style="width: 100%;" required>
                </div>
                <div class="row" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="flex: 1;">
                        <label class="form-label-premium">Puntos</label>
                        <input type="number" name="puntos" id="premio_puntos" class="form-input-premium" style="width: 100%;" required>
                    </div>
                    <div style="flex: 1;">
                        <label class="form-label-premium">Stock</label>
                        <input type="number" name="stock" id="premio_stock" class="form-input-premium" style="width: 100%;" required>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label-premium">Imagen (Archivo)</label>
                    <input type="file" name="imagen_file" class="form-input-premium" style="width: 100%;">
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label class="form-label-premium">Estado</label>
                    <select name="estado" id="premio_estado" class="form-input-premium" style="width: 100%;">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-premium-pill-black">Guardar Premio</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONDUCTORES -->
    <div id="modalCond" class="modal-overlay" style="display: none;" onclick="if(event.target===this) closeModalCond()">
        <div class="modal-content-wrapper" style="max-width: 450px; padding: 0;">
            <div style="padding: 2.25rem; border-bottom: 1px solid var(--outline); position: relative; background: #fff; border-radius: 20px 20px 0 0;">
                <h2 id="modalTitleCond" style="font-weight: 800; font-size: 1.15rem;">Nuevo Conductor</h2>
                <div class="modal-close" onclick="closeModalCond()"><i class='bx bx-x'></i></div>
            </div>
            <form id="formCond" method="POST" action="<?= BASE_URL ?>conductores/create" style="padding: 2.5rem; background: #fff;">
                <input type="hidden" name="id" id="cond_id">
                <input type="hidden" name="redir" value="ajustes">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label-premium">Nombre Completo</label>
                    <input type="text" name="nombre" id="cond_nombre" class="form-input-premium" style="width: 100%;" required>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label-premium">Usuario (Login)</label>
                    <input type="text" name="usuario" id="cond_usuario" class="form-input-premium" style="width: 100%;" required>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label-premium">Contraseña</label>
                    <input type="password" name="password" id="cond_pass" class="form-input-premium" style="width: 100%;" placeholder="Dejar en blanco para no cambiar">
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label class="form-label-premium">Estado</label>
                    <select name="estado" id="cond_estado" class="form-input-premium" style="width: 100%;">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-premium-pill-black">Guardar Conductor</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- OPERACIONES ---
        function openModalOp() {
            document.getElementById('modalOp').style.display = 'flex';
            document.getElementById('formOp').action = '<?= BASE_URL ?>operaciones/create';
            document.getElementById('modalTitleOp').innerText = 'Nueva Regla';
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
            Swal.fire({ title: '¿Inactivar regla?', text: "Se ocultará de las opciones de carga.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#000', confirmButtonText: 'Sí, inactivar' })
                .then((r) => { if(r.isConfirmed) window.location.href = url + '&redir=ajustes'; });
        }

        // --- PREMIOS ---
        function openModalPremio() {
            document.getElementById('modalPremio').style.display = 'flex';
            document.getElementById('formPremio').action = '<?= BASE_URL ?>productos/create';
            document.getElementById('modalTitlePremio').innerText = 'Nuevo Premio';
            document.getElementById('formPremio').reset();
            document.getElementById('premio_id').value = '';
        }
        function closeModalPremio() { document.getElementById('modalPremio').style.display = 'none'; }
        function editPremio(p) {
            document.getElementById('modalPremio').style.display = 'flex';
            document.getElementById('formPremio').action = '<?= BASE_URL ?>productos/update';
            document.getElementById('modalTitlePremio').innerText = 'Editar Premio';
            document.getElementById('premio_id').value = p.id;
            document.getElementById('premio_nombre').value = p.nombre;
            document.getElementById('premio_puntos').value = p.puntos;
            document.getElementById('premio_stock').value = p.stock;
            document.getElementById('premio_estado').value = p.estado;
        }
        function confirmDeletePremio(url) {
            Swal.fire({ title: '¿Inactivar premio?', text: "No será visible en la tienda.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#000', confirmButtonText: 'Sí, inactivar' })
                .then((r) => { if(r.isConfirmed) window.location.href = url + '&redir=ajustes'; });
        }

        // --- CONDUCTORES ---
        function openModalCond() {
            document.getElementById('modalCond').style.display = 'flex';
            document.getElementById('formCond').action = '<?= BASE_URL ?>conductores/create';
            document.getElementById('modalTitleCond').innerText = 'Nuevo Conductor';
            document.getElementById('formCond').reset();
            document.getElementById('cond_id').value = '';
        }
        function closeModalCond() { document.getElementById('modalCond').style.display = 'none'; }
        function editCond(c) {
            document.getElementById('modalCond').style.display = 'flex';
            document.getElementById('formCond').action = '<?= BASE_URL ?>conductores/update';
            document.getElementById('modalTitleCond').innerText = 'Editar Conductor';
            document.getElementById('cond_id').value = c.id;
            document.getElementById('cond_nombre').value = c.nombre;
            document.getElementById('cond_usuario').value = c.usuario;
            document.getElementById('cond_estado').value = c.estado;
        }
        function confirmDeleteCond(url) {
            Swal.fire({ title: '¿Inactivar conductor?', text: "Ya no podrá acceder al sistema.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#000', confirmButtonText: 'Sí, inactivar' })
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
