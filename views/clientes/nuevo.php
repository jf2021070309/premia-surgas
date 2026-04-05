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

        <div class="card elite-form-card animate-fade-in" style="max-width: 800px; margin: 2rem auto; border-radius: 24px; overflow: hidden; border: 1px solid #f1f5f9;">
            <div class="card-header-premium" style="padding: 1.5rem 2rem; background: #fff; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #fdf2f2; border: 1px solid #fee2e2; display: flex; align-items: center; justify-content: center; color: #800000; font-size: 1.2rem;">
                    <i class='bx bx-user-pin'></i>
                </div>
                <div>
                    <h3 style="font-weight: 800; font-size: 1.1rem; color: #0f172a; margin: 0;">Datos de Registro</h3>
                    <p style="font-size: 0.72rem; color: #64748b; margin-top: 1px; font-weight: 500;">Completa la información del nuevo beneficiario</p>
                </div>
            </div>

            <div v-if="error" class="alert alert-error" style="margin: 1.5rem 2rem 0; padding: 1rem; border-radius: 12px; background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; font-size: 0.85rem; font-weight: 600;">{{ error }}</div>

            <form @submit.prevent="guardar" style="padding: 2.5rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    
                    <div>
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Tipo de Cliente</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-category' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                            <select v-model="form.tipo_cliente" @change="onChangeTipo" style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; appearance: none; cursor: pointer; outline: none; background: #fff;">
                                <option value="Normal">Personal (Normal)</option>
                                <option value="Restaurante">Restaurante</option>
                                <option value="Punto de Venta">Punto de Venta</option>
                            </select>
                            <i class='bx bx-chevron-down' style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                        </div>
                    </div>

                    <div v-if="form.tipo_cliente === 'Normal'">
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">
                            DNI * 
                            <span v-if="buscandoDni" style="color:#800000; font-size:0.65rem; margin-left:8px;"><i class='bx bx-loader-alt bx-spin'></i> Buscando...</span>
                        </label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-id-card' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" v-model="form.dni" @input="validateDni" :required="form.tipo_cliente === 'Normal'" pattern="\d{8}" maxlength="8" placeholder="Ej. 12345678" :disabled="buscandoDni"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none; background: #fff;">
                        </div>
                    </div>

                    <div v-else>
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">
                            RUC *
                            <span v-if="buscandoRuc" style="color:#800000; font-size:0.65rem; margin-left:8px;"><i class='bx bx-loader-alt bx-spin'></i> Buscando...</span>
                        </label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-building-house' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" v-model="form.ruc" @input="validateRuc" :required="form.tipo_cliente !== 'Normal'" pattern="\d{11}" maxlength="11" placeholder="Ej. 20123456789" :disabled="buscandoRuc"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none; background: #fff;">
                        </div>
                    </div>

                    <div style="grid-column: span 2;" v-if="form.tipo_cliente !== 'Normal'">
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Razón Social / Nombre Comercial *</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-buildings' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" v-model="form.razon_social" :required="form.tipo_cliente !== 'Normal'" placeholder="Ej. Pollería El Buen Sabor S.A.C."
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none; background: #fff;">
                        </div>
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">{{ form.tipo_cliente === 'Normal' ? 'Nombre Completo' : 'Nombre del Contacto / Encargado' }} *</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-user' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" v-model="form.nombre" @input="validateName" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" placeholder="Ej. Juan Pérez"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none; background: #fff;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Número de Celular *</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-phone' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="tel" v-model="form.celular" @input="validatePhone" required pattern="\d{9}" maxlength="9" placeholder="987 654 321"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none; background: #fff;">
                        </div>
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Dirección</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-map-pin' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" v-model="form.direccion" placeholder="Av. Principal 123"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none; background: #fff;">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 3rem; border-top: 1px solid #f1f5f9; padding-top: 2rem; display: flex; justify-content: center;">
                    <button type="submit" :disabled="loading" style="background: #000; color: #fff; border: none; padding: 1rem 4rem; border-radius: 14px; font-weight: 800; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.2s, background 0.2s; text-transform: uppercase;">
                        <span v-if="!loading"><i class='bx bx-user-plus' style="font-size: 1.2rem;"></i> AGREGAR CLIENTE</span>
                        <span v-else><i class='bx bx-loader-alt bx-spin'></i> ESPERE...</span>
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
