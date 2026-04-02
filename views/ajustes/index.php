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
                        <button class="btn-premium-pill-black" onclick="openModalOp()" style="padding: 0.5rem 1.5rem; font-size: 0.85rem; border-radius: 100px;">
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
                        <a href="<?= BASE_URL ?>productos" class="btn-primary-premium">
                            <i class='bx bx-package'></i> Gestionar Inventario
                        </a>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($premios, 0, 5) as $p): ?>
                            <tr>
                                <td class="ps-3">
                                    <img src="<?= BASE_URL ?>assets/premios/<?= $p['imagen'] ?>" class="preview-img-circle" onerror="this.src='<?= BASE_URL ?>assets/premios/default.png'">
                                </td>
                                <td class="text-medium"><?= htmlspecialchars($p['nombre']) ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark" style="border: 1px solid #e2e8f0;"><?= $p['puntos'] ?> pts</span></td>
                                <td class="text-center"><?= $p['stock'] ?></td>
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
                        <a href="<?= BASE_URL ?>conductores" class="btn-primary-premium">
                            <i class='bx bx-group'></i> Gestionar Equipo
                        </a>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre Conductor</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($conductores, 0, 5) as $c): ?>
                            <tr>
                                <td class="text-medium"><?= htmlspecialchars($c['nombre']) ?></td>
                                <td class="text-center text-muted"><?= htmlspecialchars($c['usuario']) ?></td>
                                <td class="text-center">
                                    <span class="chip <?= $c['estado'] ? 'chip-active' : 'chip-inactive' ?>">
                                        <?= $c['estado'] ? 'ACTIVO' : 'INACTIVO' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- .container -->
    </div> <!-- .admin-layout -->

    <!-- Modal de Edición/Creación para Operaciones -->
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
                    <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #94a3b8; margin-bottom: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Nombre de la Operación</label>
                    <input type="text" name="nombre" id="op_nombre" class="form-input" 
                           style="width: 100%; padding: 0.85rem 1.15rem; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; outline: none; transition: 0.3s; font-family: 'Inter', sans-serif;"
                           placeholder="Ej: Recarga Gas 10kg" required>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #94a3b8; margin-bottom: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Puntos Asignados</label>
                    <input type="number" name="puntos" id="op_puntos" class="form-input" 
                           style="width: 100%; padding: 0.85rem 1.15rem; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; outline: none; transition: 0.3s; font-family: 'Inter', sans-serif;"
                           placeholder="0" required>
                </div>

                <div class="form-group" id="group_estadoOp" style="display: none; margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #94a3b8; margin-bottom: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Estado</label>
                    <select name="estado" id="op_estado" class="form-input"
                            style="width: 100%; padding: 0.85rem 1.15rem; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; outline: none; transition: 0.3s; background: #fff; cursor: pointer;">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 3rem;">
                    <button type="submit" class="btn-premium-pill-black" style="padding: 1rem 3.5rem; background: #000000; border: none; border-radius: 10px; color: white; font-weight: 500; font-size: 1rem; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.15); transition: 0.3s; display: flex; align-items: center; gap: 10px;">
                        <i class='bx bx-save'></i> <span>Guardar cambios</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Lógica Modal Operaciones
        const modalOp = document.getElementById('modalOp');
        const formOp = document.getElementById('formOp');
        const titleOp = document.getElementById('modalTitleOp');

        function openModalOp() {
            modalOp.style.display = 'flex';
            formOp.action = '<?= BASE_URL ?>operaciones/create';
            titleOp.innerText = 'Nueva Regla';
            formOp.reset();
            document.getElementById('op_id').value = '';
            document.getElementById('group_estadoOp').style.display = 'none';
        }

        function closeModalOp() {
            modalOp.style.display = 'none';
        }

        function editOp(op) {
            modalOp.style.display = 'flex';
            formOp.action = '<?= BASE_URL ?>operaciones/update';
            titleOp.innerText = 'Editar Regla';
            
            document.getElementById('op_id').value = op.id;
            document.getElementById('op_nombre').value = op.nombre;
            document.getElementById('op_puntos').value = op.puntos;
            document.getElementById('op_estado').value = op.estado;
            document.getElementById('group_estadoOp').style.display = 'block';
        }

        function confirmDeleteOp(url) {
            Swal.fire({
                title: '¿Inactivar regla?',
                text: "Ya no se podrá usar para nuevas recargas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, inactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url + '&redir=ajustes';
                }
            });
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
