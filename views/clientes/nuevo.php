<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/clientes_nuevo.css">
    <style>[v-cloak]{display:none}</style>
</head>
<body>
<div id="app" v-cloak>
    
    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Nuevo Cliente';
            $pageSubtitle = 'Registrar un nuevo beneficiario en el sistema';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

    <div class="container">

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

                <div class="form-group-modern full-width">
                    <label>Dirección</label>
                    <div class="input-wrapper">
                        <i class='bx bx-map-pin'></i>
                        <input type="text" v-model="form.direccion" placeholder="Av. Principal 123">
                    </div>
                </div>

                <div class="form-footer-actions">
                    <button type="submit" class="btn-premium-submit" :disabled="loading">
                        <span v-if="!loading" style="display: flex; align-items: center; gap: 8px;">
                            <i class='bx bx-user-plus' style="font-size: 1.1rem;"></i>
                            REGISTRAR CLIENTE
                        </span>
                        <span v-else>
                            <i class='bx bx-loader-alt bx-spin'></i> ESPERE...
                        </span>
                    </button>
                </div>
            </form>
        </div>

    </div> <!-- .container -->
    </div> <!-- .admin-layout -->
</div> <!-- #app -->

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    var BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>views/clientes/nuevo.js?v=<?= time() ?>"></script>
</body>
</html>
