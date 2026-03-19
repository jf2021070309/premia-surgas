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
                <a href="<?= BASE_URL ?>panel" style="text-decoration:none; display:flex; align-items:center; gap:10px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1); padding: 8px 20px; border-radius:100px; color: white; transition:0.3s;" title="Volver al Panel">
                    <i class='bx bx-left-arrow-alt' style="font-size: 1.5rem;"></i>
                    <span style="font-weight: 700; font-size: 0.9rem; letter-spacing: 0.5px;">VOLVER</span>
                </a>
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

        <!-- Título principal estilo Hero (debajo de la botonera) -->
        <div class="header-hero-content">
            <h1 class="hero-main-title">Nuevo Cliente</h1>
            <p class="hero-welcome-msg">Completa los datos para registrar un nuevo cliente en el sistema</p>
        </div>
    </div>

    <div class="container" style="max-width:700px; margin-top: -1.5rem;">

        <!-- Formulario -->
        <div class="card elite-form-card animate-fade-in">
            <div class="card-header-premium">
                <i class='bx bx-user-pin'></i>
                <span>Datos de Registro</span>
            </div>

            <div v-if="error" class="alert alert-error">{{ error }}</div>

            <form @submit.prevent="guardar" class="premium-form">
                <div class="form-group-modern">
                    <label>Tipo de Cliente</label>
                    <div class="input-wrapper">
                        <i class='bx bx-category'></i>
                        <select v-model="form.tipo_cliente" class="form-control-modern" @change="onChangeTipo">
                            <option value="Normal">Personal (Normal)</option>
                            <option value="Restaurante">Restaurante</option>
                            <option value="Punto de Venta">Punto de Venta</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-modern" v-if="form.tipo_cliente === 'Normal'">
                    <label>
                        DNI * 
                        <span v-if="buscandoDni" style="color:var(--primary); font-size:0.8rem; margin-left:8px;">
                            <i class='bx bx-loader-alt bx-spin'></i> Buscando...
                        </span>
                    </label>
                    <div class="input-wrapper">
                        <i class='bx bx-id-card'></i>
                        <input type="text" v-model="form.dni" @input="validateDni" :required="form.tipo_cliente === 'Normal'" 
                               pattern="\d{8}" 
                               maxlength="8"
                               title="Debe tener exactamente 8 dígitos" 
                               placeholder="Ej. 12345678"
                               :disabled="buscandoDni">
                    </div>
                </div>

                <div class="form-group-modern" v-else>
                    <label>
                        RUC *
                        <span v-if="buscandoRuc" style="color:var(--primary); font-size:0.8rem; margin-left:8px;">
                            <i class='bx bx-loader-alt bx-spin'></i> Buscando...
                        </span>
                    </label>
                    <div class="input-wrapper">
                        <i class='bx bx-building-house'></i>
                        <input type="text" v-model="form.ruc" @input="validateRuc" :required="form.tipo_cliente !== 'Normal'" 
                               pattern="\d{11}" 
                               maxlength="11"
                               title="Debe tener exactamente 11 dígitos" 
                               placeholder="Ej. 20123456789"
                               :disabled="buscandoRuc">
                    </div>
                </div>

                <div class="form-group-modern" v-if="form.tipo_cliente !== 'Normal'">
                    <label>Razón Social / Nombre Comercial *</label>
                    <div class="input-wrapper">
                        <i class='bx bx-buildings'></i>
                        <input type="text" v-model="form.razon_social" :required="form.tipo_cliente !== 'Normal'" 
                               placeholder="Ej. Pollería El Buen Sabor S.A.C.">
                    </div>
                </div>

                <div class="form-group-modern">
                    <label>{{ form.tipo_cliente === 'Normal' ? 'Nombre Completo' : 'Nombre del Contacto / Encargado' }} *</label>
                    <div class="input-wrapper">
                        <i class='bx bx-user'></i>
                        <input type="text" v-model="form.nombre" @input="validateName" required 
                               pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" 
                               title="Solo se permiten letras y espacios" 
                               :placeholder="form.tipo_cliente === 'Normal' ? 'Ej. Juan Pérez' : 'Ej. Carlos Gómez'">
                    </div>
                </div>

                <div class="form-group-modern">
                    <label>Número de Celular *</label>
                    <div class="input-wrapper">
                        <i class='bx bx-phone'></i>
                        <input type="tel" v-model="form.celular" @input="validatePhone" required 
                               pattern="\d{9}" 
                               maxlength="9"
                               title="Debe tener exactamente 9 dígitos" 
                               placeholder="987 654 321">
                    </div>
                </div>

                <div class="row-modern" style="grid-template-columns: 1fr;">
                    <div class="form-group-modern">
                        <label>Dirección</label>
                        <div class="input-wrapper">
                            <i class='bx bx-map-pin'></i>
                            <input type="text" v-model="form.direccion" placeholder="Av. Principal 123">
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

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    var BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>views/clientes/nuevo.js?v=<?= time() ?>"></script>
</body>
</html>
