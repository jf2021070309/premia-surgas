<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>[v-cloak]{display:none}</style>
</head>
<body>
<div id="app" v-cloak>
    <div class="panel-header">
        <div class="header-top-row">
            <div class="header-logo-side">
                <a href="<?= BASE_URL ?>panel" style="color:#fff; font-size:1.6rem; margin-right:1.2rem; display:flex; align-items:center; transition:0.3s;" title="Volver al Panel">
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
                    <button @click="logout" class="u-logout-btn" title="Cerrar Sesión">
                        <i class='bx bx-log-out'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="max-width:700px">

        <!-- Éxito después de guardar -->
        <div v-if="clienteGuardado" class="card" style="text-align:center; margin-top:2rem">
            <div style="font-size:3rem; margin-bottom:1rem">✅</div>
            <div class="alert alert-success" v-if="esExistente">{{ mensaje }}</div>
            <h2 style="color:var(--dark); margin-bottom:.5rem">{{ form.nombre }}</h2>
            <div class="badge-code">{{ codigoGenerado }}</div>
            <p style="color:var(--muted); font-size:.88rem; margin:.8rem 0">Cliente registrado correctamente</p>
            <div class="action-row">
                <a :href="'<?= BASE_URL ?>clientes/exito?id=' + clienteId" class="btn btn-primary">Ver QR</a>
                <a :href="'<?= BASE_URL ?>clientes/imprimir?id=' + clienteId" target="_blank" class="btn btn-dark">Imprimir</a>
                <a href="<?= BASE_URL ?>clientes/nuevo" class="btn btn-outline">Nuevo</a>
            </div>
        </div>

        <!-- Formulario -->
        <div v-else class="card elite-form-card animate-fade-in">
            <div class="card-header-premium">
                <i class='bx bx-user-pin'></i>
                <span>Datos de Registro</span>
            </div>

            <div v-if="error" class="alert alert-error">{{ error }}</div>

            <form @submit.prevent="guardar" class="premium-form">
                <div class="form-group-modern">
                    <label><i class='bx bx-id-card'></i> Nombre Completo *</label>
                    <div class="input-wrapper">
                        <input type="text" v-model="form.nombre" @input="validateName" required 
                               pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" 
                               title="Solo se permiten letras y espacios" 
                               placeholder="Ej. Juan Pérez">
                    </div>
                </div>

                <div class="form-group-modern">
                    <label><i class='bx bx-phone'></i> Número de Celular *</label>
                    <div class="input-wrapper">
                        <input type="tel" v-model="form.celular" @input="validatePhone" required 
                               pattern="\d{9}" 
                               maxlength="9"
                               title="Debe tener exactamente 9 dígitos" 
                               placeholder="987 654 321">
                    </div>
                </div>

                <div class="row-modern">
                    <div class="form-group-modern">
                        <label><i class='bx bx-map-pin'></i> Dirección</label>
                        <div class="input-wrapper">
                            <input type="text" v-model="form.direccion" placeholder="Av. Principal 123">
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label><i class='bx bx-buildings'></i> Distrito</label>
                        <div class="input-wrapper">
                            <select v-model="form.distrito" class="form-control-modern">
                                <option value="">-- Seleccionar --</option>
                                <option value="Tacna (capital)">Tacna (capital)</option>
                                <option value="Alto de la Alianza">Alto de la Alianza</option>
                                <option value="Calana">Calana</option>
                                <option value="Ciudad Nueva">Ciudad Nueva</option>
                                <option value="Coronel Gregorio Albarracín Lanchipa">Coronel Gregorio Albarracín Lanchipa</option>
                                <option value="Inclán">Inclán</option>
                                <option value="La Yarada-Los Palos">La Yarada-Los Palos</option>
                                <option value="Pachía">Pachía</option>
                                <option value="Palca">Palca</option>
                                <option value="Pocollay">Pocollay</option>
                                <option value="Sama">Sama</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-footer-actions">
                    <button type="submit" class="btn-premium-submit" :disabled="loading">
                        <span v-if="!loading">REGISTRAR</span>
                        <span v-else>
                            <i class='bx bx-loader-alt bx-spin'></i> ESPERE...
                        </span>
                    </button>
                    <p class="form-hint">Campos marcados con (*) son obligatorios</p>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    var BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/clientes_nuevo.js"></script>
</body>
</html>
