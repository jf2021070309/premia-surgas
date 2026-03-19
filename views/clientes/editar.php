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
<div id="app" v-cloak>
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
                    <a href="javascript:void(0)" @click.prevent="logout" class="u-logout-btn" title="Cerrar Sesión" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">
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

                <div class="form-group" style="display: flex; gap: 10px; align-items: center; margin-bottom: 1rem;">
                    <label style="margin-bottom: 0;">Tipo de Cliente:</label>
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <input type="radio" name="tipo_cliente" value="Normal" <?= ($cliente['tipo_cliente'] ?? 'Normal') == 'Normal' ? 'checked' : '' ?> onchange="toggleTipos()"> Normal
                    </label>
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <input type="radio" name="tipo_cliente" value="Restaurante" <?= ($cliente['tipo_cliente'] ?? 'Normal') == 'Restaurante' ? 'checked' : '' ?> onchange="toggleTipos()"> Restaurante
                    </label>
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <input type="radio" name="tipo_cliente" value="Punto de Venta" <?= ($cliente['tipo_cliente'] ?? 'Normal') == 'Punto de Venta' ? 'checked' : '' ?> onchange="toggleTipos()"> Punto de Venta
                    </label>
                </div>

                <div class="form-group" id="group-dni">
                    <label>
                        DNI
                        <span id="dni-spinner" style="display:none; color:var(--primary); font-size:0.8rem; margin-left:8px;">
                            <i class='bx bx-loader-alt bx-spin'></i> Buscando...
                        </span>
                    </label>
                    <input type="text" name="dni" id="input-dni" value="<?= htmlspecialchars($cliente['dni'] ?? '') ?>"
                           pattern="\d{8}" 
                           maxlength="8"
                           title="Debe tener exactamente 8 dígitos"
                           @input="soloDni">
                </div>

                <div class="form-group" id="group-ruc">
                    <label>
                        RUC
                        <span id="ruc-spinner" style="display:none; color:var(--primary); font-size:0.8rem; margin-left:8px;">
                            <i class='bx bx-loader-alt bx-spin'></i> Buscando...
                        </span>
                    </label>
                    <input type="text" name="ruc" id="input-ruc" value="<?= htmlspecialchars($cliente['ruc'] ?? '') ?>"
                           pattern="\d{11}" 
                           maxlength="11"
                           title="Debe tener exactamente 11 dígitos"
                           @input="soloRuc">
                </div>

                <div class="form-group" id="group-razon-social" style="display:none;">
                    <label>Razón Social / Nombre Comercial</label>
                    <input type="text" name="razon_social" id="input-razon-social" value="<?= htmlspecialchars($cliente['razon_social'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label id="lbl-nombre">Nombre Completo</label>
                    <input type="text" name="nombre" id="input-nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required
                           @input="soloLetrasOpcional">
                </div>

                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" name="celular" value="<?= htmlspecialchars($cliente['celular']) ?>" required
                           pattern="\d{9}" 
                           maxlength="9"
                           title="Debe tener exactamente 9 dígitos"
                           @input="soloNumeros">
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>">
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
</div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>
        var BASE_URL = '<?= BASE_URL ?>';
        
        function toggleTipos() {
            const val = document.querySelector('input[name="tipo_cliente"]:checked').value;
            const gDni = document.getElementById('group-dni');
            const gRuc = document.getElementById('group-ruc');
            const gRs  = document.getElementById('group-razon-social');
            const iDni = document.getElementById('input-dni');
            const iRuc = document.getElementById('input-ruc');
            const iRs  = document.getElementById('input-razon-social');
            const lblNombre = document.getElementById('lbl-nombre');
            const iNombre = document.getElementById('input-nombre');

            if (val === 'Normal') {
                gDni.style.display = 'block';
                iDni.setAttribute('required', 'required');
                gRuc.style.display = 'none';
                iRuc.removeAttribute('required');
                gRs.style.display = 'none';
                iRs.removeAttribute('required');
                lblNombre.innerText = 'Nombre Completo';
                iNombre.setAttribute('pattern', '[A-Za-zñÑáéíóúÁÉÍÓÚ\\s]+');
                iNombre.setAttribute('title', 'Solo se permiten letras y espacios');
            } else {
                gDni.style.display = 'none';
                iDni.removeAttribute('required');
                gRuc.style.display = 'block';
                iRuc.setAttribute('required', 'required');
                gRs.style.display = 'block';
                iRs.setAttribute('required', 'required');
                lblNombre.innerText = 'Nombre del Contacto / Encargado';
                iNombre.setAttribute('pattern', '[A-Za-zñÑáéíóúÁÉÍÓÚ\\s]+');
                iNombre.setAttribute('title', 'Solo se permiten letras y espacios');
            }
        }
        
        document.addEventListener("DOMContentLoaded", toggleTipos);
    </script>
    <script src="<?= BASE_URL ?>views/clientes/editar.js"></script>
</body>
</html>
