<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Operaciones — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f0f2f5; }
        .container { max-width: 900px; margin: 3rem auto; padding: 0 1.5rem; }
        .card {
            background: white; border-radius: 2rem; padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.05); margin-bottom: 2rem;
        }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; }
        
        .table-custom { width: 100%; border-collapse: separate; border-spacing: 0 0.8rem; }
        .table-custom th { padding: 1rem; color: #666; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
        .table-custom td { padding: 1.2rem 1rem; background: #f8fafc; border-top: 1px solid #edf2f7; border-bottom: 1px solid #edf2f7; transition: 0.3s; }
        .table-custom tr td:first-child { border-left: 1px solid #edf2f7; border-radius: 1rem 0 0 1rem; }
        .table-custom tr td:last-child { border-right: 1px solid #edf2f7; border-radius: 0 1rem 1rem 0; }
        .table-custom tr:hover td { background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }

        .badge { padding: 0.4rem 0.8rem; border-radius: 50px; font-size: 0.75rem; font-weight: 700; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        .btn-add { background: #821515; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 1rem; font-weight: 700; cursor: pointer; transition: 0.3s; text-decoration: none; }
        .btn-add:hover { background: #4a0c0c; transform: translateY(-2px); }

        .btn-action { background: none; border: none; cursor: pointer; font-size: 1.1rem; color: #666; padding: 0.3rem; transition: 0.2s; }
        .btn-edit:hover { color: #821515; }
        .btn-delete:hover { color: #e74c3c; }

        .back-nav { margin-bottom: 1.5rem; }
        .btn-back { color: #821515; text-decoration: none; font-weight: 600; font-size: 0.9rem; }

        /* Modal simple */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); }
        .modal-card { background: white; width: 400px; padding: 2rem; border-radius: 2rem; animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .form-group { margin-bottom: 1.2rem; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; }
        .form-input { width: 100%; padding: 0.8rem; border: 2px solid #e2e8f0; border-radius: 0.8rem; outline: none; font-family: inherit; }
        .form-input:focus { border-color: #821515; }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-nav">
            <a href="<?= BASE_URL ?>panel" class="btn-back">← Volver al Panel</a>
        </div>

        <div class="card">
            <div class="header">
                <div>
                    <h1 style="font-size: 1.8rem; font-weight: 800; margin: 0;">Gestión de Operaciones</h1>
                    <p style="color: #666; margin: 0.3rem 0 0;">Configura los tipos de recarga y sus puntos.</p>
                </div>
                <button class="btn-add" onclick="openModal()">+ Nueva Operación</button>
            </div>

            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="text-align: left;">Nombre</th>
                        <th>Puntos</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operaciones as $op): ?>
                    <tr>
                        <td><b><?= htmlspecialchars($op['nombre']) ?></b></td>
                        <td style="text-align: center; font-weight: 700; color: #821515;"><?= $op['puntos'] ?> pts</td>
                        <td style="text-align: center;">
                            <span class="badge <?= $op['estado'] ? 'badge-success' : 'badge-danger' ?>">
                                <?= $op['estado'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <button class="btn-action btn-edit" onclick="editOp(<?= htmlspecialchars(json_encode($op)) ?>)">✏️</button>
                            <a href="<?= BASE_URL ?>operaciones/delete?id=<?= $op['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Inactivar esta operación?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Formulario -->
    <div id="modalOp" class="modal-overlay">
        <div class="modal-card">
            <h2 id="modalTitle" style="font-weight: 800; margin-bottom: 1.5rem;">Nueva Operación</h2>
            <form id="formOp" method="POST" action="<?= BASE_URL ?>operaciones/create">
                <input type="hidden" name="id" id="op_id">
                
                <div class="form-group">
                    <label class="form-label">Nombre de la Operación</label>
                    <input type="text" name="nombre" id="op_nombre" class="form-input" placeholder="Ej: Recarga Gas 10kg" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Puntos Asignados</label>
                    <input type="number" name="puntos" id="op_puntos" class="form-input" placeholder="0" required>
                </div>

                <div class="form-group" id="group_estado" style="display: none;">
                    <label class="form-label">Estado</label>
                    <select name="estado" id="op_estado" class="form-input">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn-add" style="flex: 1;">Guardar</button>
                    <button type="button" class="btn-add" style="flex: 1; background: #e2e8f0; color: #666;" onclick="closeModal()">Cancelar</button>
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

        window.onclick = function(event) {
            if (event.target == modal) closeModal();
        }
    </script>

    <?php if (isset($_SESSION['flash'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['flash']['type'] ?>',
            title: '<?= $_SESSION['flash']['title'] ?>',
            text: '<?= $_SESSION['flash']['message'] ?>',
            timer: 3000,
            timerProgressBar: true
        });
    </script>
    <?php unset($_SESSION['flash']); endif; ?>
</body>
</html>
