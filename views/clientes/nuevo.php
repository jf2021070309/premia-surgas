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
    <style>
        [v-cloak]{display:none}
        /* Responsividad para Formulario de Nuevo Cliente */
        .form-grid {
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 1.25rem;
        }
        .form-footer {
            margin-top: 2rem; 
            padding-top: 1.5rem; 
            border-top: 1px solid #f1f5f9; 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
        }
        @media (max-width: 600px) {
            .form-grid {
                display: flex !important;
                flex-direction: column !important;
            }
            .form-footer {
                flex-direction: column-reverse; /* El texto required abajo o arriba del boton */
                gap: 1.25rem;
                align-items: stretch !important;
                text-align: center;
            }
            .form-footer button {
                width: 100%;
                justify-content: center;
                padding: 1rem !important; /* Más grande en celular */
            }
            form {
                padding: 1.5rem !important;
            }
        }
    </style>
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

    <div class="container dashboard-container">

        <div class="card elite-form-card animate-fade-in" style="max-width: 780px; margin: 1.5rem auto; border-radius: 18px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 2px 16px rgba(0,0,0,0.06);">

            <div v-if="error" class="alert alert-error" style="margin: 1.5rem 2rem 0; padding: 1rem; border-radius: 12px; background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; font-size: 0.85rem; font-weight: 600;">{{ error }}</div>

            <form @submit.prevent="guardar" style="padding: 2.5rem;">

                <div class="form-grid">

                    <!-- Tipo de Cliente -->
                    <div>
                        <label style="display: block; font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem;">Tipo de Cliente</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-category' style="position: absolute; left: 1rem; color: #94a3b8; font-size: 1.1rem; pointer-events: none;"></i>
                            <select v-model="form.tipo_cliente" @change="onChangeTipo" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.78rem 1rem 0.78rem 2.75rem; font-size: 0.88rem; font-family:'Inter',sans-serif; color: #1e293b; appearance: none; cursor: pointer; outline: none; background: #fff; transition: border-color 0.2s, box-shadow 0.2s;">
                                <option value="Normal">Personal (Normal)</option>
                                <?php if ($_SESSION['rol'] === 'admin'): ?>
                                    <option value="Restaurante">Restaurante</option>
                                    <option value="Punto de Venta">Punto de Venta</option>
                                <?php endif; ?>
                            </select>
                            <i class='bx bx-chevron-down' style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                        </div>
                    </div>

                    <!-- DNI -->
                    <div v-if="form.tipo_cliente === 'Normal'">
                        <label style="display: block; font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem;">
                            DNI *
                            <span v-if="buscandoDni" style="color:#800000; font-size:0.65rem; margin-left:8px;"><i class='bx bx-loader-alt bx-spin'></i> Buscando...</span>
                        </label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-id-card' style="position: absolute; left: 1rem; color: #94a3b8; font-size: 1.1rem;"></i>
                            <input type="text" v-model="form.dni" @input="validateDni" :required="form.tipo_cliente === 'Normal'" pattern="\d{8}" maxlength="8" :disabled="buscandoDni"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.78rem 1rem 0.78rem 2.75rem; font-size: 0.88rem; font-family:'Inter',sans-serif; color: #1e293b; outline: none; background: #fff; transition: border-color 0.2s, box-shadow 0.2s;">
                        </div>
                    </div>

                    <!-- RUC -->
                    <div v-else>
                        <label style="display: block; font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem;">
                            RUC *
                            <span v-if="buscandoRuc" style="color:#800000; font-size:0.65rem; margin-left:8px;"><i class='bx bx-loader-alt bx-spin'></i> Buscando...</span>
                        </label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-building-house' style="position: absolute; left: 1rem; color: #94a3b8; font-size: 1.1rem;"></i>
                            <input type="text" v-model="form.ruc" @input="validateRuc" :required="form.tipo_cliente !== 'Normal'" pattern="\d{11}" maxlength="11" :disabled="buscandoRuc"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.78rem 1rem 0.78rem 2.75rem; font-size: 0.88rem; font-family:'Inter',sans-serif; color: #1e293b; outline: none; background: #fff; transition: border-color 0.2s, box-shadow 0.2s;">
                        </div>
                    </div>

                    <!-- Razón Social -->
                    <div style="grid-column: span 2;" v-if="form.tipo_cliente !== 'Normal'">
                        <label style="display: block; font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem;">Razón Social / Nombre Comercial *</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-buildings' style="position: absolute; left: 1rem; color: #94a3b8; font-size: 1.1rem;"></i>
                            <input type="text" v-model="form.razon_social" :required="form.tipo_cliente !== 'Normal'"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.78rem 1rem 0.78rem 2.75rem; font-size: 0.88rem; font-family:'Inter',sans-serif; color: #1e293b; outline: none; background: #fff; transition: border-color 0.2s, box-shadow 0.2s;">
                        </div>
                    </div>

                    <!-- Nombre Completo -->
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem;">{{ form.tipo_cliente === 'Normal' ? 'Nombre Completo' : 'Nombre del Contacto / Encargado' }} *</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-user' style="position: absolute; left: 1rem; color: #94a3b8; font-size: 1.1rem;"></i>
                            <input type="text" v-model="form.nombre" @input="validateName" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.78rem 1rem 0.78rem 2.75rem; font-size: 0.88rem; font-family:'Inter',sans-serif; color: #1e293b; outline: none; background: #fff; transition: border-color 0.2s, box-shadow 0.2s;">
                        </div>
                    </div>

                    <!-- Celular -->
                    <div>
                        <label style="display: block; font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem;">Número de Celular *</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-phone' style="position: absolute; left: 1rem; color: #94a3b8; font-size: 1.1rem;"></i>
                            <input type="tel" v-model="form.celular" @input="validatePhone" required pattern="\d{9}" maxlength="9"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.78rem 1rem 0.78rem 2.75rem; font-size: 0.88rem; font-family:'Inter',sans-serif; color: #1e293b; outline: none; background: #fff; transition: border-color 0.2s, box-shadow 0.2s;">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label style="display: block; font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem;">Dirección</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-map-pin' style="position: absolute; left: 1rem; color: #94a3b8; font-size: 1.1rem;"></i>
                            <input type="text" v-model="form.direccion"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.78rem 1rem 0.78rem 2.75rem; font-size: 0.88rem; font-family:'Inter',sans-serif; color: #1e293b; outline: none; background: #fff; transition: border-color 0.2s, box-shadow 0.2s;">
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="form-footer">
                    <span style="font-size: 0.72rem; color: #94a3b8; font-weight: 500;">* Campos requeridos</span>
                    <button type="submit" :disabled="loading"
                        style="background: #000; color: #fff; border: none; padding: 0.78rem 2.2rem; border-radius: 10px; font-weight: 700; font-size: 0.85rem; font-family:'Inter',sans-serif; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <i class='bx bx-user-plus'></i>
                        {{ loading ? 'Guardando...' : 'Registrar Cliente' }}
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
