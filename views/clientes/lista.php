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
    <style>[v-cloak]{display:none}</style>
</head>
<body>
<div id="app" v-cloak>
    
    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Directorio';
            $pageSubtitle = 'Panel de gestión de beneficiarios registrados';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class='bx bx-id-card title-icon red-premium'></i>
                        <span>Lista de Beneficiarios</span>
                    </div>
                    
                    <div class="header-actions">
                        <div class="header-filter">
                            <i class='bx bx-filter-alt'></i>
                            <select v-model="filterTipo">
                                <option value="">Todos los Tipos</option>
                                <option value="Normal">Personal</option>
                                <option value="Restaurante">Restaurante</option>
                                <option value="Punto de Venta">Punto de Venta</option>
                            </select>
                        </div>
                        <div class="header-filter">
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
                        <div class="header-search">
                            <i class='bx bx-search'></i>
                            <input type="text" v-model="busqueda" placeholder="Buscar cliente...">
                        </div>
                        <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-primary-premium">
                            <i class='bx bx-user-plus'></i> Nuevo
                        </a>
                    </div>
                </div>

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
                                        <a :href="'<?= BASE_URL ?>clientes/imprimir?id=' + c.id" target="_blank" class="btn-action gray" title="Imprimir">
                                            <i class='bx bx-printer'></i>
                                        </a>
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
        <div class="modal-content-wrapper" style="max-width: 400px; padding: 2.5rem 2rem; text-align: center;">
            <div class="modal-close" @click="showCarnetModal = false"><i class='bx bx-x'></i></div>
            
            <div style="font-size:0.7rem; color:var(--on-muted); text-transform:uppercase; letter-spacing:2px; margin-bottom:1rem">GAS EXPRESS SURGAS</div>
            <h2 style="font-size:1.4rem; color:var(--on-surface); margin-bottom:0.5rem">{{ currentCliente.nombre }}</h2>
            <div class="text-medium" style="color:var(--on-muted); margin-bottom:1.5rem">
                {{ currentCliente.tipo_cliente === 'Normal' ? 'DNI' : 'RUC' }}: {{ currentCliente.tipo_cliente === 'Normal' ? currentCliente.dni : currentCliente.ruc }}
            </div>

            <div id="qrcode-modal" style="display:flex; justify-content:center; margin: 2rem 0; padding: 1rem; background: #f8fafc; border-radius: 15px; border: 1px dashed #e2e8f0;"></div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1.5rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                <div style="text-align: left;">
                    <span style="font-size:0.65rem; color:#94a3b8; text-transform: uppercase;">Puntos</span>
                    <div style="font-size:1.1rem; font-weight:800; color:var(--primary)">{{ currentCliente.puntos }} pts</div>
                </div>
                <div style="text-align: right;">
                    <span style="font-size:0.65rem; color:#94a3b8; text-transform: uppercase;">Código</span>
                    <div style="font-size:1.1rem; font-weight:800; color:var(--on-surface)">{{ currentCliente.codigo }}</div>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <a :href="'<?= BASE_URL ?>clientes/imprimir?id=' + currentCliente.id" target="_blank" class="btn-primary-premium" style="width:100%; justify-content:center; height: 3.5rem;">
                    <i class='bx bx-printer'></i> Imprimir Tarjeta
                </a>
            </div>
        </div>
    </div>

    <!-- MODAL: EDITAR CLIENTE -->
    <div class="modal-overlay" v-if="showEditModal" @click.self="showEditModal = false">
        <div class="modal-content-wrapper" style="max-width: 650px;">
            <div class="modal-close" @click="showEditModal = false"><i class='bx bx-x'></i></div>
            
            <div class="card elite-form-card" style="margin: 0; box-shadow: none; border: none;">
                <div class="card-header-premium">
                    <i class='bx bx-edit-alt'></i>
                    <span>Editar Beneficiario</span>
                </div>

                <form @submit.prevent="guardarCambios" class="premium-form" style="padding: 2rem;">
                    <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        
                        <div class="form-group-modern">
                            <label>Tipo de Cliente</label>
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

                        <div class="form-group-modern" v-if="form.tipo_cliente !== 'Normal'" style="grid-column: span 2;">
                            <label>Razón Social *</label>
                            <div class="input-wrapper">
                                <i class='bx bx-buildings'></i>
                                <input type="text" v-model="form.razon_social" required>
                            </div>
                        </div>

                        <div class="form-group-modern" style="grid-column: span 2;">
                            <label>Nombre Completo / Contacto *</label>
                            <div class="input-wrapper">
                                <i class='bx bx-user'></i>
                                <input type="text" v-model="form.nombre" required>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label>Celular *</label>
                            <div class="input-wrapper">
                                <i class='bx bx-phone'></i>
                                <input type="text" v-model="form.celular" maxlength="9" required>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label>Departamento</label>
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

                        <div class="form-group-modern" style="grid-column: span 2;">
                            <label>Dirección</label>
                            <div class="input-wrapper">
                                <i class='bx bx-map-pin'></i>
                                <input type="text" v-model="form.direccion" placeholder="Calle, número, urbanización...">
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                        <button type="button" @click="showEditModal = false" class="btn-primary-premium gray" style="width: auto; padding: 0.8rem 2rem; height: 3.5rem; background: #64748b; box-shadow: none;">Cancelar</button>
                        <button type="submit" class="btn-primary-premium" :disabled="fetching" style="padding: 0.8rem 2rem; height: 3.5rem;">
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
