<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de Clientes — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/clientes_nuevo.css">
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <style>
        [v-cloak] {
            display: none !important;
        }

        [v-cloak]>* {
            display: none !important;
        }
    </style>
</head>

<body>
    <div id="app" v-cloak>

        <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

        <div class="admin-layout">
            <?php
            $pageTitle = 'Directorio de Clientes';
            $pageSubtitle = 'Gestión y administración de beneficiarios';
            include __DIR__ . '/../partials/header_admin.php';
            ?>

            <div class="container animate-fade-in">
                <!-- Plantilla Oculta para Captura de Imagen -->
                <div id="capture-container" style="position: fixed; left: -9999px; top: -9999px;">
                    <div id="card-capture-template"
                        style="width: 450px; height: 550px; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding: 40px 20px; font-family: 'Inter', sans-serif; text-align: center; box-sizing: border-box;">
                        <div
                            style="font-size: 14px; font-weight: 500; color: #94a3b8; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 20px;">
                            GAS EXPRESS SURGAS</div>
                        <div id="capture-name"
                            style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 8px; letter-spacing: -0.01em;">
                            ---</div>
                        <div id="capture-doc"
                            style="font-size: 18px; font-weight: 600; color: #64748b; margin-bottom: 40px;">DNI:
                            00000000</div>

                        <div
                            style="background: #f8fafc; border: 1px dashed #e2e8f0; padding: 25px; border-radius: 30px; display: flex; align-items: center; justify-content: center; width: 320px; height: 320px; box-sizing: border-box;">
                            <div id="qrcode-capture"></div>
                        </div>
                    </div>
                </div>

                <!-- Toolbar responsiva: Fila 1 (filtros) + Fila 2 (buscador + botón) -->
                <div class="clientes-toolbar">
                    <div class="clientes-toolbar-filters">
                        <div class="header-search-modern">
                            <i class='bx bx-filter-alt'></i>
                            <select v-model="filterTipo">
                                <option value="">Todos los Tipos</option>
                                <option value="Normal">Personal</option>
                                <option value="Restaurante">Restaurante</option>
                                <option value="Punto de Venta">Punto de Venta</option>
                            </select>
                        </div>
                        <div class="header-search-modern">
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
                    <div class="clientes-toolbar-search">
                        <div class="header-search-modern clientes-search-input">
                            <i class='bx bx-search'></i>
                            <input type="text" v-model="busqueda" placeholder="Buscar cliente...">
                        </div>
                        <a href="<?= BASE_URL ?>clientes/nuevo" class="btn-primary-premium btn-nuevo-cliente">
                            <i class='bx bx-user-plus'></i>
                            <span>Nuevo Cliente</span>
                        </a>
                    </div>
                </div>


                <div class="card">
                    <div class="table-wrapper">
                        <table class="data-table">

                            <thead>
                                <tr>
                                    <th>Nombre del Cliente</th>
                                    <th>Categoría</th>
                                    <th class="col-hide-mobile">Celular</th>
                                    <th class="col-hide-mobile">Documento</th>
                                    <th class="col-hide-mobile">Departamento</th>
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
                                                <!-- Info compacta visible solo en móvil -->
                                                <div class="mobile-client-meta">
                                                    <span v-if="c.celular"><i class='bx bx-phone'></i> {{ c.celular }}</span>
                                                    <span v-if="c.departamento"><i class='bx bx-map-pin'></i> {{ c.departamento }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-medium">
                                            {{ c.tipo_cliente || 'Normal' }}
                                        </span>
                                    </td>
                                    <td class="col-hide-mobile"><span class="text-medium">{{ c.celular }}</span></td>
                                    <td class="col-hide-mobile"><span class="text-medium">{{ c.tipo_cliente !== 'Normal' ? (c.ruc || '—') : (c.dni || '—') }}</span></td>
                                    <td class="col-hide-mobile"><span class="text-medium">{{ c.departamento || '—' }}</span></td>
                                    <td><span class="text-medium">{{ c.puntos }} pts</span></td>
                                    <td>
                                        <div class="actions-flex">
                                            <!-- Ver Carnet (Azul) -->
                                            <button @click="abrirCarnet(c)" class="btn-action blue" title="Ver Carnet">
                                                <i class='bx bx-id-card'></i>
                                            </button>
                                            <!-- Descargar/Imprimir (Gris) -->
                                            <button @click="descargarTarjeta(c)" class="btn-action gray"
                                                title="Descargar Tarjeta">
                                                <i class='bx bx-printer'></i>
                                            </button>
                                            <?php if ($_SESSION['rol'] === 'admin'): ?>
                                                <!-- Editar (Índigo/Morado) -->
                                                <button @click="abrirEditar(c)" class="btn-action indigo" title="Editar">
                                                    <i class='bx bx-edit'></i>
                                                </button>
                                                <!-- Promover a Afiliado (Naranja) -->
                                                <button v-if="c.tipo_cliente !== 'Normal'" @click="convertirAfiliado(c)"
                                                    class="btn-action orange" title="Promover a Afiliado">
                                                    <i class='bx bx-award'></i>
                                                </button>
                                                <!-- Desactivar (Rojo) -->
                                                <button v-if="c.estado == 1" @click="toggleEstado(c.id, 0)"
                                                    class="btn-action red" title="Desactivar">
                                                    <i class='bx bx-block'></i>
                                                </button>
                                                <!-- Activar (Verde - se mantiene como acción positiva) -->
                                                <button v-else @click="toggleEstado(c.id, 1)" class="btn-action green"
                                                    title="Activar">
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

                <div
                    style="font-size:0.75rem; color:#94a3b8; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:1.5rem; text-align: center;">
                    GAS EXPRESS SURGAS</div>
                <h2 style="font-size:1.6rem; color:#0f172a; margin-bottom:0.5rem; text-align: center;">{{
                    currentCliente.nombre }}</h2>
                <div class="text-medium"
                    style="color:#64748b; margin-bottom:2.5rem; text-align: center; font-size: 1.1rem; font-weight: 600;">
                    {{ currentCliente.tipo_cliente === 'Normal' ? 'DNI' : 'RUC' }}: {{ currentCliente.tipo_cliente ===
                    'Normal' ? currentCliente.dni : currentCliente.ruc }}
                </div>

                <div
                    style="background: #f8fafc; border: 1px dashed #e2e8f0; padding: 1.5rem; border-radius: 25px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
                    <div id="qrcode-modal"></div>
                </div>

                <button @click="descargarTarjeta(currentCliente)" class="btn-primary-premium"
                    style="width:100%; justify-content:center; height: 3.5rem;">
                    <i class='bx bx-download'></i> Descargar Tarjeta
                </button>
            </div>
        </div>

        <div class="modal-overlay" v-if="showEditModal" @click.self="showEditModal = false"
            style="display: flex; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 10000;">
            <div class="modal-content-wrapper"
                style="max-width: 650px; width: 95%; background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.3); animation: slideUp 0.3s ease;">

                <div class="modal-header-premium"
                    style="padding: 2rem 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div
                            style="background: #fdf2f2; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #fee2e2; flex-shrink: 0;">
                            <i class='bx bx-edit-alt' style="color: #800000; font-size: 1.3rem;"></i>
                        </div>
                        <div>
                            <h2 style="font-weight: 800; font-size: 1.2rem; color: #0f172a; margin: 0;">Editar
                                Beneficiario</h2>
                            <p style="font-size: 0.75rem; color: #64748b; margin-top: 2px; font-weight: 500;">Modifica
                                los datos del cliente seleccionado</p>
                        </div>
                    </div>
                    <div class="modal-close" @click="showEditModal = false"
                        style="cursor: pointer; width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b;">
                        <i class='bx bx-x' style="font-size: 1.3rem;"></i>
                    </div>
                </div>

                <form @submit.prevent="guardarCambios" style="padding: 2.5rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">

                        <div>
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Tipo
                                de Cliente</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-category'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                                <select v-model="form.tipo_cliente"
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; appearance: none; cursor: pointer; outline: none; background: #fff;">
                                    <option value="Normal">Personal</option>
                                    <option value="Restaurante">Restaurante</option>
                                    <option value="Punto de Venta">Punto de Venta</option>
                                </select>
                                <i class='bx bx-chevron-down'
                                    style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                            </div>
                        </div>

                        <div v-if="form.tipo_cliente === 'Normal'">
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">DNI
                                *</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-id-card'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="text" v-model="form.dni" maxlength="8" @blur="consultarDni"
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                            </div>
                        </div>

                        <div v-else>
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">RUC
                                *</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-building-house'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="text" v-model="form.ruc" maxlength="11" @blur="consultarRuc"
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                            </div>
                        </div>

                        <div style="grid-column: span 2;" v-if="form.tipo_cliente !== 'Normal'">
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Razón
                                Social *</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-buildings'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="text" v-model="form.razon_social" required
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                            </div>
                        </div>

                        <div style="grid-column: span 2;">
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Nombre
                                Completo / Contacto *</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-user'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="text" v-model="form.nombre" required
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                            </div>
                        </div>

                        <div>
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Celular
                                *</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-phone'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="text" v-model="form.celular" maxlength="9" required
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                            </div>
                        </div>

                        <div>
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Departamento</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-map'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                                <select v-model="form.departamento"
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; appearance: none; cursor: pointer; outline: none; background: #fff;">
                                    <option value="Tacna">Tacna</option>
                                    <option value="Ilo">Ilo</option>
                                    <option value="Moquegua">Moquegua</option>
                                    <option value="Camaná">Camaná</option>
                                    <option value="Mollendo">Mollendo</option>
                                </select>
                                <i class='bx bx-chevron-down'
                                    style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                            </div>
                        </div>

                        <div style="grid-column: span 2;">
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Dirección</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-map-pin'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="text" v-model="form.direccion"
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                            </div>
                        </div>
                    </div>

                    <div
                        style="margin-top: 1.5rem; border-top: 1px solid #f1f5f9; padding-top: 2rem; display: flex; justify-content: flex-end;">
                        <button type="submit" :disabled="fetching"
                            style="background: #1e293b; color: #fff; border: none; padding: 0.75rem 2.2rem; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            <span v-if="!fetching">Actualizar</span>
                            <span v-else><i class='bx bx-loader-alt bx-spin'></i> ESPERE...</span>
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
