<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="panel-header">
        <div class="header-top-row">
            <div class="header-logo-side">
                <a href="<?= BASE_URL ?>clientes/lista" style="color:#fff; font-size:1.6rem; margin-right:1.2rem; display:flex; align-items:center; transition:0.3s;" title="Volver a la Lista">
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
        <div class="card">
            <form action="<?= BASE_URL ?>clientes/update" method="POST" style="padding: 1.5rem;">
                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required
                           pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" 
                           title="Solo se permiten letras y espacios"
                           oninput="this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '')">
                </div>

                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" name="celular" value="<?= htmlspecialchars($cliente['celular']) ?>" required
                           pattern="\d{9}" 
                           maxlength="9"
                           title="Debe tener exactamente 9 dígitos"
                           oninput="this.value = this.value.replace(/\D/g, '').slice(0, 9)">
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>">
                </div>

                <div class="form-group">
                    <label>Distrito</label>
                    <select name="distrito" class="form-control">
                        <option value="">-- Seleccionar --</option>
                        <?php 
                        $distritos = [
                            "Tacna (capital)", "Alto de la Alianza", "Calana", "Ciudad Nueva", 
                            "Coronel Gregorio Albarracín Lanchipa", "Inclán", "La Yarada-Los Palos", 
                            "Pachía", "Palca", "Pocollay", "Sama"
                        ];
                        foreach ($distritos as $d): ?>
                            <option value="<?= $d ?>" <?= $cliente['distrito'] == $d ? 'selected' : '' ?>><?= $d ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="1" <?= $cliente['estado'] == 1 ? 'selected' : '' ?>>Activo</option>
                        <option value="0" <?= $cliente['estado'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Guardar Cambios</button>
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
    </script>
</body>
</html>
