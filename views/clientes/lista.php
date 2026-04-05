<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de Clientes — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/clientes_nuevo.css">
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <style>
        [v-cloak] { display: none !important; }
        [v-cloak] > * { display: none !important; }
    </style>
</head>
<body>
<div id="app" v-cloak>
    
    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Directorio de Clientes';
            $pageSubtitle = 'Gestión y administración de beneficiarios';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container animate-fade-in">
            <!-- Plantilla Oculta para Captura de Imagen -->
            <div id="capture-container" style="position: fixed; left: -9999px; top: -9999px;">
                <div id="card-capture-template" style="width: 450px; height: 550px; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding: 40px 20px; font-family: 'Inter', sans-serif; text-align: center; box-sizing: border-box;">
                    <div style="font-size: 14px; font-weight: 500; color: #94a3b8; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 20px;">GAS EXPRESS SURGAS</div>
                    <div id="capture-name" style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 8px; letter-spacing: -0.01em;">---</div>
                    <div id="capture-doc" style="font-size: 18px; font-weight: 600; color: #64748b; margin-bottom: 40px;">DNI: 00000000</div>
                    
                    <div style="background: #f8fafc; border: 1px dashed #e2e8f0; padding: 25px; border-radius: 30px; display: flex; align-items: center; justify-content: center; width: 320px; height: 320px; box-sizing: border-box;">
                        <div id="qrcode-capture"></div>
                    </div>
                </div>
            </div>

            <div class="modern-section-header">
                <!-- Fila Superior: Título y Buscador Global -->
                <div class="header-row-top">
                    <div class="section-title-flex">
                        <div class="section-title-text">
                            <h3>Directorio de Clientes</h3>
                            <span>Gestión y administración de beneficiarios</span>
                        </div>
                    </div>
                    <div class="section-actions">
                        <div class="header-search-modern" style="width: 350px;">
                            <i class='bx bx-search'></i>
                            <input type="text" v-model="busqueda" placeholder="Buscar">
                        </div>
                        <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-primary-premium">
                            <i class='bx bx-user-plus'></i> Nuevo
                        </a>
                    </div>
                </div>

                <!-- Fila Inferior: Filtros de segmentación -->
                <div class="header-row-bottom">
                    <div class="header-search-modern" style="width: 180px;">
                        <i class='bx bx-filter-alt'></i>
                        <select v-model="filterTipo">
                            <option value="">Todos los Tipos</option>
                            <option value="Normal">Personal</option>
                            <option value="Restaurante">Restaurante</option>
                            <option value="Punto de Venta">Punto de Venta</option>
                        </select>
                    </div>
                    <div class="header-search-modern" style="width: 220px;">
                        <i class='bx bx-map-alt'></i>
                        <select v-model="filterDep">
                            <option value="">Todos los Departamentos</option>
                            <option value="Tacna">Tacna</option>
                            <option value="Ilo">Ilo</option>
                            <option value="Moquegua">Moquegua</option>
                            <option value="Camaná">Camaná</option>
                            <option value="Mollendo">Mollendo</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="table-wrapper">
                    <table class="data-table">

                        <thead>
                            <tr>
                                <th>Nombre del Cliente</th>
                                <th>Categoría</th>
                                <th>Celular</th>
                                <th>Documento</th>
                                <th>Departamento</th>
                                <th>Puntos</th>
                                <th style="text-align: right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="c in filtrados" :key="c.id">
                                <td>
                                    <div class="row-client">
                                        <div class="client-info">
                                            <div class="client-name">{{ c.tipo_cliente === 'Normal' ? c.nombre : (c.razon_social || c.nombre) }}</div>
                                            <div v-if="c.tipo_cliente !== 'Normal'" class="client-subtext">{{ c.nombre }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-medium">
                                        {{ c.tipo_cliente || 'Normal' }}
                                    </span>
                                </td>
                                <td><span class="text-medium">{{ c.celular }}</span></td>
                                <td><span class="text-medium">{{ c.tipo_cliente !== 'Normal' ? (c.ruc || '—') : (c.dni || '—') }}</span></td>
                                <td><span class="text-medium">{{ c.departamento || '—' }}</span></td>
                                <td><span class="text-medium">{{ c.puntos }} pts</span></td>
                                <td>
                                    <div class="actions-flex">
                                        <button @click="abrirCarnet(c)" class="btn-action blue" title="Ver Carnet">
                                            <i class='bx bx-id-card'></i>
                                        </button>
                                        <button @click="descargarTarjeta(c)" class="btn-action gray" title="Descargar Tarjeta">
                                            <i class='bx bx-printer'></i>
                                        </button>
                                        <?php if ($_SESSION['rol'] === 'admin'): ?>
                                        <button @click="abrirEditar(c)" class="btn-action orange" title="Editar">
                                            <i class='bx bx-edit'></i>
                                        </button>
                                        <button v-if="c.estado == 1" @click="toggleEstado(c.id, 0)" class="btn-action red" title="Desactivar">
                                            <i class='bx bx-block'></i>
                                        </button>
                                        <button v-else @click="toggleEstado(c.id, 1)" class="btn-action green" title="Activar">
                                            <i class='bx bx-check-circle'></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filtrados.length === 0">
                                <td colspan="7">
                                    <div class="empty-table">
                                        <i class='bx bx-search-alt'></i>
                                        <p>No se encontraron clientes que coincidan con "{{ busqueda }}"</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- .admin-layout -->

    <!-- MODAL: VER CARNET -->
    <div class="modal-overlay" v-if="showCarnetModal" @click.self="showCarnetModal = false">
        <div class="modal-content-wrapper" style="max-width: 420px; padding: 3rem 2rem; border-radius: 25px;">
            <div class="modal-close" @click="showCarnetModal = false"><i class='bx bx-x'></i></div>
            
            <div style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:1.5rem; text-align: center;">GAS EXPRESS SURGAS</div>
            <h2 style="font-size:1.6rem; color:#0f172a; margin-bottom:0.5rem; text-align: center;">{{ currentCliente.nombre }}</h2>
            <div class="text-medium" style="color:#64748b; margin-bottom:2.5rem; text-align: center; font-size: 1.1rem; font-weight: 600;">
                {{ currentCliente.tipo_cliente === 'Normal' ? 'DNI' : 'RUC' }}: {{ currentCliente.tipo_cliente === 'Normal' ? currentCliente.dni : currentCliente.ruc }}
            </div>

            <div style="background: #f8fafc; border: 1px dashed #e2e8f0; padding: 1.5rem; border-radius: 25px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
                <div id="qrcode-modal"></div>
            </div>

            <button @click="descargarTarjeta(currentCliente)" class="btn-primary-premium" style="width:100%; justify-content:center; height: 3.5rem;">
                <i class='bx bx-download'></i> Descargar Tarjeta
            </button>
        </div>
    </div>

    <!-- MODAL: EDITAR CLIENTE -->
    <div class="modal-overlay" v-if="showEditModal" @click.self="showEditModal = false">
        <div class="modal-content-wrapper" style="max-width: 700px; padding: 0; overflow: hidden; border-radius: 20px;">
            <div class="modal-close" @click="showEditModal = false"><i class='bx bx-x'></i></div>
            
            <div class="elite-form-card" style="margin: 0; box-shadow: none;">
                <div class="card-header-premium" style="padding: 1.5rem 2rem; background: #ffffff; border-bottom: 1px solid #f1f5f9;">
                    <div style="background: #fff1f1; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; border: 1px solid #fee2e2;">
                        <i class='bx bx-edit-alt' style="color: #800000; font-size: 1.3rem;"></i>
                    </div>
                    <span style="font-size: 1.25rem; font-weight: 700; color: #0f172a; letter-spacing: -0.02em;">Editar Beneficiario</span>
                </div>

                <form @submit.prevent="guardarCambios" class="premium-form" style="padding: 2.5rem;">
                    <!-- Campos directos en el grid del formulario -->
                    <div class="form-group-modern">
                        <label>TIPO DE CLIENTE</label>
                        <div class="input-wrapper">
                            <i class='bx bx-category'></i>
                            <select v-model="form.tipo_cliente" class="form-control-modern">
                                <option value="Normal">Personal</option>
                                <option value="Restaurante">Restaurante</option>
                                <option value="Punto de Venta">Punto de Venta</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-modern" v-if="form.tipo_cliente === 'Normal'">
                        <label>DNI *</label>
                        <div class="input-wrapper">
                            <i class='bx bx-id-card'></i>
                            <input type="text" v-model="form.dni" maxlength="8" @blur="consultarDni" placeholder="8 dígitos">
                        </div>
                    </div>

                    <div class="form-group-modern" v-else>
                        <label>RUC *</label>
                        <div class="input-wrapper">
                            <i class='bx bx-building-house'></i>
                            <input type="text" v-model="form.ruc" maxlength="11" @blur="consultarRuc" placeholder="11 dígitos">
                        </div>
                    </div>

                    <div class="form-group-modern full-width" v-if="form.tipo_cliente !== 'Normal'">
                        <label>RAZÓN SOCIAL *</label>
                        <div class="input-wrapper">
                            <i class='bx bx-buildings'></i>
                            <input type="text" v-model="form.razon_social" required>
                        </div>
                    </div>

                    <div class="form-group-modern full-width">
                        <label>NOMBRE COMPLETO / CONTACTO *</label>
                        <div class="input-wrapper">
                            <i class='bx bx-user'></i>
                            <input type="text" v-model="form.nombre" required>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label>CELULAR *</label>
                        <div class="input-wrapper">
                            <i class='bx bx-phone'></i>
                            <input type="text" v-model="form.celular" maxlength="9" required>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label>DEPARTAMENTO</label>
                        <div class="input-wrapper">
                            <i class='bx bx-map'></i>
                            <select v-model="form.departamento" class="form-control-modern">
                                <option value="Tacna">Tacna</option>
                                <option value="Ilo">Ilo</option>
                                <option value="Moquegua">Moquegua</option>
                                <option value="Camaná">Camaná</option>
                                <option value="Mollendo">Mollendo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-modern full-width">
                        <label>DIRECCIÓN</label>
                        <div class="input-wrapper">
                            <i class='bx bx-map-pin'></i>
                            <input type="text" v-model="form.direccion" placeholder="Calle, número, urbanización...">
                        </div>
                    </div>

                    <!-- Footer de Botones Centrado (Ajustado) -->
                    <div class="form-footer-actions" style="margin-top: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                        <button type="submit" class="btn-primary-premium" :disabled="fetching" style="padding: 0 4rem; height: 3.5rem; width: auto; font-size: 0.95rem; border-radius: 12px; margin: 0 auto; display: flex;">
                            <i class='bx bx-save'></i> {{ fetching ? 'Guardando...' : 'Guardar Cambios' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div> <!-- #app -->

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
var CLIENTES = <?= json_encode($clientes) ?>;
var BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/session_check.js"></script>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>

<script src="<?= BASE_URL ?>views/clientes/lista.js"></script>
</body>
</html>
