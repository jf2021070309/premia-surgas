<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Puntos — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        .chip-puntos { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; font-weight: 800; padding: 4px 10px; border-radius: 8px; font-size: 0.85rem; }
        .chip-active { background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; }
        .chip-inactive { background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Configuración de Puntos';
            $pageSubtitle = 'Gestiona los tipos de recarga y su puntaje asignado';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title-icon" style="background: #fdf2f2; color: #800000; border: 1px solid #fee2e2;">
                            <i class='bx bx-calculator'></i>
                        </div>
                        Reglas de Puntaje
                    </div>
                    <div class="header-actions">
                        <button class="btn-primary-premium" onclick="openModal()">
                            <i class='bx bx-plus'></i> Nueva Regla
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
                                <td class="text-medium">
                                    <?= htmlspecialchars($op['nombre']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="chip-puntos"><?= $op['puntos'] ?> pts</span>
                                </td>
                                <td class="text-center">
                                    <span class="chip <?= $op['estado'] ? 'chip-approved' : 'chip-rejected' ?>">
                                        <i class='bx bxs-circle'></i> <?= $op['estado'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="actions-flex" style="justify-content: center;">
                                        <button class="btn-action blue" onclick="editOp(<?= htmlspecialchars(json_encode($op)) ?>)" title="Editar">
                                            <i class='bx bx-edit-alt'></i>
                                        </button>
                                        <button class="btn-action red" onclick="confirmDelete('<?= BASE_URL ?>operaciones/delete?id=<?= $op['id'] ?>')" title="Inactivar/Eliminar">
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

    <!-- Modal de Edición/Creación -->
    <div id="modalOp" class="modal-overlay" style="display: none; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 10000;" onclick="if(event.target===this) closeModal()">
        <div class="modal-content-wrapper" style="max-width: 450px; width: 95%; background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.3); animation: slideUp 0.3s ease;">
            
            <div class="modal-header-premium" style="padding: 2rem 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="background: #fdf2f2; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #fee2e2; flex-shrink: 0;">
                        <i class='bx bx-calculator' style="color: #800000; font-size: 1.3rem;"></i>
                    </div>
                    <div>
                        <h2 id="modalTitle" style="font-weight: 800; font-size: 1.2rem; color: #0f172a; margin: 0;">Nueva Operación</h2>
                        <p style="font-size: 0.75rem; color: #64748b; margin-top: 2px; font-weight: 500;">Configura los puntos para este tipo de recarga</p>
                    </div>
                </div>
                <div class="modal-close" onclick="closeModal()" style="cursor: pointer; width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b;">
                    <i class='bx bx-x' style="font-size: 1.3rem;"></i>
                </div>
            </div>

            <form id="formOp" method="POST" action="<?= BASE_URL ?>operaciones/create" style="padding: 2.5rem;">
                <input type="hidden" name="id" id="op_id">
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Nombre de la Operación</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class='bx bx-purchase-tag' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                        <input type="text" name="nombre" id="op_nombre" placeholder="Ej: Recarga Gas 10kg" required
                               style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none; transition: border-color 0.2s;">
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Puntos Asignados</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class='bx bx-star' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                        <input type="number" name="puntos" id="op_puntos" placeholder="0" required
                               style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                    </div>
                </div>

                <div id="group_estado" style="display: none; margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Estado</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <i class='bx bx-toggle-right' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                        <select name="estado" id="op_estado" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none; appearance: none; cursor: pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        <i class='bx bx-chevron-down' style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                    </div>
                </div>

                <div style="margin-top: 2.5rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem; display: flex; justify-content: center;">
                    <button type="submit" style="background: #000; color: #fff; border: none; padding: 0.85rem 3rem; border-radius: 12px; font-weight: 800; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.2s, background 0.2s; text-transform: uppercase;">
                        <i class='bx bx-save'></i> <span>ACTUALIZAR REGLA</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalOp');
        const form = document.getElementById('formOp');
        const title = document.getElementById('modalTitle');

        function openModal() {
            modal.style.display = 'flex';
            form.action = '<?= BASE_URL ?>operaciones/create';
            title.innerText = 'Nueva Operación';
            form.reset();
            document.getElementById('op_id').value = '';
            document.getElementById('group_estado').style.display = 'none';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function editOp(op) {
            modal.style.display = 'flex';
            form.action = '<?= BASE_URL ?>operaciones/update';
            title.innerText = 'Editar Operación';
            
            document.getElementById('op_id').value = op.id;
            document.getElementById('op_nombre').value = op.nombre;
            document.getElementById('op_puntos').value = op.puntos;
            document.getElementById('op_estado').value = op.estado;
            document.getElementById('group_estado').style.display = 'block';
        }

        function confirmDelete(url) {
            Swal.fire({
                title: '¿Inactivar operación?',
                text: "Ya no se podrá usar para nuevas recargas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, inactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
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
            showConfirmButton: false,
            background: '#ffffff',
            color: '#111827',
            confirmButtonColor: '#000000'
        });
    </script>
    <?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
