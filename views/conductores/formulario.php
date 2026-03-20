<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="panel-header">
        <div class="header-top-row">
            <div class="header-logo-side">
                <a href="<?= BASE_URL ?>conductores" style="color:#fff; font-size:1.6rem; margin-right:1.2rem; display:flex; align-items:center; transition:0.3s;" title="Volver a la Lista">
                    <i class='bx bx-left-arrow-alt'></i>
                </a>
                <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="PremiaSurgas" class="header-main-logo">
            </div>

            <div class="header-user-side">
                <div class="user-card-integrated">
                    <div class="u-avatar"><?= substr($_SESSION['nombre_usuario'], 0, 1) ?></div>
                    <div class="u-details">
                        <span class="u-role-tag"><?= htmlspecialchars(strtoupper($_SESSION['rol'])) ?></span>
                        <span class="u-name-val"><?= htmlspecialchars($_SESSION['usuario'] ?? $_SESSION['nombre_usuario']) ?></span>
                    </div>
                    <div class="u-divider"></div>
                    <a href="javascript:void(0)" onclick="confirmLogout()" class="u-logout-btn" title="Cerrar Sesión" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">
                        <i class='bx bx-log-out'></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="max-width: 700px">
        <div class="card elite-form-card animate-fade-in">
            <div class="card-header-premium">
                <i class='bx bxs-truck'></i>
                <span>Datos del Conductor</span>
            </div>

            <form action="<?= BASE_URL ?>conductores/<?= $conductor ? 'update' : 'create' ?>" method="POST" class="premium-form">
                <?php if($conductor): ?>
                    <input type="hidden" name="id" value="<?= $conductor['id'] ?>">
                <?php endif; ?>

                <div class="form-group-modern">
                    <label><i class='bx bx-user'></i> Nombre Completo</label>
                    <div class="input-wrapper">
                        <input type="text" name="nombre" value="<?= htmlspecialchars($conductor['nombre'] ?? '') ?>" required placeholder="Ej. Carlos Mendoza">
                    </div>
                </div>

                <div class="form-group-modern">
                    <label><i class='bx bx-user-circle'></i> Usuario (Login)</label>
                    <div class="input-wrapper">
                        <input type="text" name="usuario" value="<?= htmlspecialchars($conductor['usuario'] ?? '') ?>" required placeholder="usuario123">
                    </div>
                </div>

                <div class="form-group-modern">
                    <label><i class='bx bx-lock-alt'></i> Contraseña <?= $conductor ? '(opcional)' : '' ?></label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="input-password" <?= $conductor ? '' : 'required' ?> placeholder="••••••••" style="padding-right: 3.2rem;">
                        <i class='bx bx-show password-toggle-modern' id="btn-toggle-pass"></i>
                    </div>
                    <?php if($conductor): ?>
                        <p class="form-hint" style="margin-top:0.3rem">Dejar en blanco para mantener la actual</p>
                    <?php endif; ?>
                </div>

                <div class="form-group-modern">
                    <label><i class='bx bx-map'></i> Departamento</label>
                    <div class="input-wrapper">
                        <select name="departamento" class="form-control-modern" required>
                            <option value="">-- Seleccionar --</option>
                            <option value="Tacna" <?= ($conductor['departamento'] ?? '') == 'Tacna' ? 'selected' : '' ?>>Tacna</option>
                            <option value="Moquegua" <?= ($conductor['departamento'] ?? '') == 'Moquegua' ? 'selected' : '' ?>>Moquegua</option>
                            <option value="Arequipa" <?= ($conductor['departamento'] ?? '') == 'Arequipa' ? 'selected' : '' ?>>Arequipa</option>
                            <option value="Ilo" <?= ($conductor['departamento'] ?? '') == 'Ilo' ? 'selected' : '' ?>>Ilo</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-modern">
                    <label><i class='bx bx-toggle-right'></i> Estado de Cuenta</label>
                    <div class="input-wrapper">
                        <select name="estado" class="form-control-modern">
                            <option value="1" <?= ($conductor['estado'] ?? 1) == 1 ? 'selected' : '' ?>>Activo (Acceso permitido)</option>
                            <option value="0" <?= ($conductor['estado'] ?? 1) == 0 ? 'selected' : '' ?>>Inactivo (Acceso denegado)</option>
                        </select>
                    </div>
                </div>

                <div class="form-footer-actions">
                    <button type="submit" class="btn-premium-submit">
                        <i class='bx bx-check-shield'></i> <?= $conductor ? 'Guardar Cambios' : 'Confirmar Registro' ?>
                    </button>
                    <p class="form-hint">Asegúrate de que los datos sean correctos</p>
                </div>
            </form>
        </div>
    </div>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: "Esperamos verte pronto de nuevo.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary)',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= BASE_URL ?>logout';
                }
            });
        }

        // Toggle Password
        document.getElementById('btn-toggle-pass').addEventListener('click', function() {
            var input = document.getElementById('input-password');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('bx-show', 'bx-hide');
            } else {
                input.type = 'password';
                this.classList.replace('bx-hide', 'bx-show');
            }
        });
    </script>
</body>
</html>
