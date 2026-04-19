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
                <a href="<?= BASE_URL ?>aliados" style="color:#fff; font-size:1.6rem; margin-right:1.2rem; display:flex; align-items:center; transition:0.3s;" title="Volver a la Lista">
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

    <div class="container" style="max-width: 750px; margin: 3rem auto;">
        <div class="card elite-form-card animate-fade-in" style="border-radius: 24px; overflow: hidden; border: 1px solid #f1f5f9; background: #fff;">
            <div class="card-header-premium" style="padding: 1.5rem 2rem; background: #fff; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #fdf2f2; border: 1px solid #fee2e2; display: flex; align-items: center; justify-content: center; color: #800000; font-size: 1.2rem;">
                    <i class='bx bxs-store-alt'></i>
                </div>
                <div>
                    <h3 style="font-weight: 800; font-size: 1.1rem; color: #0f172a; margin: 0;">Datos del Aliado Comercial</h3>
                    <p style="font-size: 0.72rem; color: #64748b; margin-top: 1px; font-weight: 500;">Establecimientos autorizados para la asignación de puntos</p>
                </div>
            </div>

            <form action="<?= BASE_URL ?>aliados/<?= $aliado ? 'update' : 'create' ?>" method="POST" style="padding: 2.5rem;">
                <?php if($aliado): ?>
                    <input type="hidden" name="id" value="<?= $aliado['id'] ?>">
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Nombre del Establecimiento / Aliado</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-building-house' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" name="nombre" value="<?= htmlspecialchars($aliado['nombre'] ?? '') ?>" required 
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none; transition: border-color 0.2s;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Usuario (Login)</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-user-circle' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" name="usuario" value="<?= htmlspecialchars($aliado['usuario'] ?? '') ?>" required 
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Contraseña <?= $aliado ? '(opcional)' : '' ?></label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-lock-alt' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="password" name="password" id="input-password" <?= $aliado ? '' : 'required' ?> 
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 3rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                            <i class='bx bx-show' id="btn-toggle-pass" style="position: absolute; right: 1rem; color: #94a3b8; cursor: pointer; font-size: 1.2rem;"></i>
                        </div>
                        <?php if($aliado): ?><p style="font-size: 0.65rem; color: #94a3b8; margin-top: 0.3rem;">Dejar en blanco para mantener la actual</p><?php endif; ?>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Departamento</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-map' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                            <select name="departamento" required style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none; appearance: none; cursor: pointer;">
                                <option value="">-- Seleccionar --</option>
                                <option value="Tacna" <?= ($aliado['departamento'] ?? '') == 'Tacna' ? 'selected' : '' ?>>Tacna</option>
                                <option value="Moquegua" <?= ($aliado['departamento'] ?? '') == 'Moquegua' ? 'selected' : '' ?>>Moquegua</option>
                                <option value="Arequipa" <?= ($aliado['departamento'] ?? '') == 'Arequipa' ? 'selected' : '' ?>>Arequipa</option>
                                <option value="Ilo" <?= ($aliado['departamento'] ?? '') == 'Ilo' ? 'selected' : '' ?>>Ilo</option>
                            </select>
                            <i class='bx bx-chevron-down' style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Estado</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-toggle-right' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                            <select name="estado" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none; appearance: none; cursor: pointer;">
                                <option value="1" <?= ($aliado['estado'] ?? 1) == 1 ? 'selected' : '' ?>>Activo</option>
                                <option value="0" <?= ($aliado['estado'] ?? 1) == 0 ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                            <i class='bx bx-chevron-down' style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                        </div>
                    </div>

                </div>

                <div style="margin-top: 1.5rem; border-top: 1px solid #f1f5f9; padding-top: 2rem; display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: #000; color: #fff; border: none; padding: 0.75rem 2.5rem; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <?= $aliado ? 'Actualizar Aliado' : 'Registrar Aliado' ?>
                    </button>
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
