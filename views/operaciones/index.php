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
                                    <span class="chip <?= $op['estado'] ? 'chip-active' : 'chip-inactive' ?>">
                                        <?= $op['estado'] ? 'ACTIVO' : 'INACTIVO' ?>
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
    <div id="modalOp" class="modal-overlay" onclick="if(event.target===this) closeModal()">
        <div class="modal-card" style="max-width: 450px;">
            <div class="modal-header">
                <h2 id="modalTitle" style="font-weight: 800; font-size: 1.1rem; margin: 0;">Nueva Operación</h2>
                <button class="modal-close-btn" onclick="closeModal()">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            
            <form id="formOp" method="POST" action="<?= BASE_URL ?>operaciones/create" style="padding: 1.5rem;">
                <input type="hidden" name="id" id="op_id">
                
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase;">Nombre de la Operación</label>
                    <input type="text" name="nombre" id="op_nombre" class="form-input" 
                           style="width: 100%; padding: 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 12px; outline: none; transition: 0.3s;"
                           placeholder="Ej: Recarga Gas 10kg" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase;">Puntos Asignados</label>
                    <input type="number" name="puntos" id="op_puntos" class="form-input" 
                           style="width: 100%; padding: 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 12px; outline: none; transition: 0.3s;"
                           placeholder="0" required>
                </div>

                <div class="form-group" id="group_estado" style="display: none; margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase;">Estado</label>
                    <select name="estado" id="op_estado" class="form-input"
                            style="width: 100%; padding: 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 12px; outline: none; transition: 0.3s; background: #fff;">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 2rem;">
                    <button type="submit" class="btn-primary-premium" style="flex: 1; justify-content: center; height: 48px;">
                        <i class='bx bx-save'></i> Guardar Cambios
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
