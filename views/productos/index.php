<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Premios — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        [v-cloak] { display: none !important; }

        /* ── Table image ── */
        .product-img-box {
            width: 44px; height: 44px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #f1f5f9;
            background: #fff;
        }

        /* ── Points badge ── */
        .pts-badge {
            background: #e0f2fe; color: #0369a1;
            font-weight: 800; padding: 4px 10px;
            border-radius: 8px; font-size: 0.75rem;
            border: 1px solid #bae6fd;
        }

        /* ══════════════════════════════════════
           MODAL FORM — Flex Icon Inputs
        ══════════════════════════════════════ */

        /* Label premium */
        .modal-field-label {
            display: block;
            font-size: 0.63rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            margin-bottom: 0.55rem;
        }

        /* Flex-based input row: icon | field */
        .modal-input-row {
            display: flex;
            align-items: center;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .modal-input-row:focus-within {
            border-color: #1e293b;
            box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
        }
        .modal-input-row .row-icon {
            flex-shrink: 0;
            width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 1.1rem;
            border-right: 1.5px solid #f1f5f9;
            background: #fafbfc;
            align-self: stretch;
            transition: color 0.2s;
        }
        .modal-input-row:focus-within .row-icon {
            color: #1e293b;
            background: #f8fafc;
        }
        .modal-input-row input,
        .modal-input-row select {
            flex: 1;
            border: none;
            outline: none;
            padding: 0.8rem 1rem;
            font-size: 0.88rem;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: transparent;
            min-width: 0;
        }
        .modal-input-row input::placeholder { color: #cbd5e1; }
        .modal-input-row select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        /* ── Form field group ── */
        .modal-field { margin-bottom: 1.25rem; }
        .modal-field:last-child { margin-bottom: 0; }

        /* ── 2-col grid ── */
        .modal-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 520px) { .modal-grid-2 { grid-template-columns: 1fr; } }

        /* ══════════════════════════════════════
           Upload Zone
        ══════════════════════════════════════ */
        .upload-trigger {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border: 1.5px dashed #e2e8f0;
            border-radius: 12px;
            padding: 0.9rem 1.1rem;
            cursor: pointer;
            transition: all 0.2s;
            background: #fafbfc;
            user-select: none;
        }
        .upload-trigger:hover {
            border-color: #800000;
            background: #fdf5f5;
        }
        .upload-trigger.has-file {
            border-style: solid;
            border-color: #a7f3d0;
            background: #f0fdf4;
        }
        .upload-trigger-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            color: #94a3b8;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .upload-trigger:hover .upload-trigger-icon {
            background: #fdf2f2; color: #800000;
        }
        .upload-trigger.has-file .upload-trigger-icon {
            background: #dcfce7; color: #059669;
        }
        .upload-trigger-text { flex: 1; min-width: 0; }
        .upload-trigger-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #1e293b;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .upload-trigger-sub {
            font-size: 0.68rem;
            color: #94a3b8;
            font-weight: 500;
        }
        .upload-preview-thumb {
            width: 38px; height: 38px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            flex-shrink: 0;
        }
        .upload-clear-btn {
            width: 26px; height: 26px;
            border-radius: 50%;
            border: none;
            background: #fef2f2;
            color: #dc2626;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .upload-clear-btn:hover { background: #fee2e2; }

        /* ── Modal footer btns ── */
        .btn-modal-cancel {
            background: #f8fafc; color: #64748b;
            padding: 0.8rem 1.65rem;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-family: 'Inter', sans-serif;
            font-weight: 700; font-size: 0.85rem;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-modal-cancel:hover { background: #f1f5f9; color: #1e293b; border-color: #cbd5e1; }
    </style>
</head>
<body>
<div id="app" v-cloak>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Catálogo de Premios';
            $pageSubtitle = 'Gestión de productos y recompensas';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="modern-section-header">
                <div class="section-title-flex">
                    <div class="section-title-text">
                        <h3>Catálogo de Premios</h3>
                        <span>Gestión de productos y recompensas</span>
                    </div>
                </div>
                <div class="section-actions">
                    <div class="header-filter">
                        <i class='bx bx-filter-alt'></i>
                        <select v-model="filtroEstado">
                            <option value="todos">Todos los Estados</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                    <div class="header-search-modern">
                        <i class='bx bx-search'></i>
                        <input type="text" placeholder="Buscar" v-model="busqueda">
                    </div>
                    <button class="btn-primary-premium" @click="nuevoProducto">
                        <i class='bx bx-plus-circle'></i> Nuevo
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Premio</th>
                                <th class="text-center">Puntos</th>
                                <th class="text-center">Ventas</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="p in productosFiltrados" :key="p.id">
                                <td>
                                    <div class="row-client">
                                        <img :src="p.imagen ? '<?= BASE_URL ?>assets/uploads/productos/' + p.imagen : '<?= BASE_URL ?>assets/premios/no-image.png'" class="product-img-box" @error="onImgError">
                                        <div class="title-text-group">
                                            <span class="text-medium">{{ p.nombre }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><span class="pts-badge">{{ p.puntos }} pts</span></td>
                                <td class="text-center"><span class="text-medium">{{ p.ventas || 0 }} cat.</span></td>
                                <td>
                                    <span :class="['badge-status', p.estado == 1 ? 'badge-approved' : 'badge-rejected']">
                                        {{ p.estado == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-flex" style="justify-content: center;">
                                        <button class="btn-action blue" @click="editarProducto(p)" title="Editar">
                                            <i class='bx bx-edit-alt'></i>
                                        </button>
                                        <button class="btn-action red" @click="eliminarProducto(p.id)" title="Eliminar">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="productosFiltrados.length === 0">
                                <td colspan="5">
                                    <div class="empty-table">
                                        <i class='bx bx-package'></i>
                                        Catálogo vacío o sin resultados para la búsqueda.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer-premium">
                    <div class="footer-info">Mostrando {{ productosFiltrados.length }} de {{ productos.length }} premios registrados</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════
         Modal: Nuevo / Editar Premio
    ══════════════════════════════════════ -->
    <div class="modal-overlay" v-if="showModal" @click.self="cerrarModal" id="modalProducto">
        <div class="modal-content-wrapper" style="max-width: 500px;">

            <!-- Header -->
            <div class="modal-header-premium">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 42px; height: 42px; border-radius: 12px; background: #fdf2f2; border: 1px solid #fee2e2; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #800000; flex-shrink: 0;">
                        <i :class="['bx', editando ? 'bx-edit' : 'bx-gift']"></i>
                    </div>
                    <div>
                        <h2>{{ editando ? 'Editar Premio' : 'Registrar Nuevo Premio' }}</h2>
                        <p style="font-size: 0.75rem; color: #64748b; margin-top: 3px; font-weight: 500;">
                            {{ editando ? 'Modifica los datos del producto seleccionado' : 'Completa los datos del nuevo producto' }}
                        </p>
                    </div>
                </div>
                <div class="modal-close" @click="cerrarModal" title="Cerrar">
                    <i class='bx bx-x' style="font-size: 1.3rem;"></i>
                </div>
            </div>

            <!-- Form -->
            <form :action="'<?= BASE_URL ?>productos/' + (editando ? 'update' : 'store')" method="POST" enctype="multipart/form-data" @submit="submitting = true">
                <input type="hidden" name="id" :value="form.id" v-if="editando">
                <input type="hidden" name="imagen_actual" :value="form.imagen_actual" v-if="editando">

                <div class="modal-body-premium">

                    <!-- Nombre -->
                    <div style="margin-bottom: 1.1rem;">
                        <label style="display: block; font-size: 0.63rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.09em; margin-bottom: 0.5rem;">Nombre Comercial del Premio</label>
                        <div style="display: flex !important; flex-direction: row !important; align-items: center !important; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #fff; overflow: hidden;">
                            <span style="flex-shrink: 0; width: 42px; height: 44px; display: flex !important; align-items: center !important; justify-content: center !important; color: #94a3b8; font-size: 1.1rem; border-right: 1.5px solid #f1f5f9; background: #f8fafc;">
                                <i class='bx bx-purchase-tag'></i>
                            </span>
                            <input
                                type="text"
                                name="nombre"
                                v-model="form.nombre"
                                placeholder="Ej: Auriculares Bluetooth Premium"
                                required
                                autocomplete="off"
                                style="flex: 1 !important; border: none !important; outline: none !important; padding: 0.8rem 1rem !important; font-size: 0.88rem !important; font-family: 'Inter', sans-serif !important; color: #1e293b !important; background: transparent !important; min-width: 0 !important; display: block !important;"
                            >
                        </div>
                    </div>

                    <!-- Puntos + Stock -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.1rem;">
                        <div>
                            <label style="display: block; font-size: 0.63rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.09em; margin-bottom: 0.5rem;">Inversión Puntos</label>
                            <div style="display: flex !important; flex-direction: row !important; align-items: center !important; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #fff; overflow: hidden;">
                                <span style="flex-shrink: 0; width: 42px; height: 44px; display: flex !important; align-items: center !important; justify-content: center !important; color: #94a3b8; font-size: 1.1rem; border-right: 1.5px solid #f1f5f9; background: #f8fafc;">
                                    <i class='bx bx-star'></i>
                                </span>
                                <input type="number" name="puntos" v-model="form.puntos" placeholder="0" min="0" required
                                    style="flex: 1 !important; border: none !important; outline: none !important; padding: 0.8rem 1rem !important; font-size: 0.88rem !important; font-family: 'Inter', sans-serif !important; color: #1e293b !important; background: transparent !important; min-width: 0 !important; display: block !important; -moz-appearance: textfield;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.63rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.09em; margin-bottom: 0.5rem;">Stock Actual</label>
                            <div style="display: flex !important; flex-direction: row !important; align-items: center !important; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #fff; overflow: hidden;">
                                <span style="flex-shrink: 0; width: 42px; height: 44px; display: flex !important; align-items: center !important; justify-content: center !important; color: #94a3b8; font-size: 1.1rem; border-right: 1.5px solid #f1f5f9; background: #f8fafc;">
                                    <i class='bx bx-cube'></i>
                                </span>
                                <input type="number" name="stock" v-model="form.stock" placeholder="0" min="0" required
                                    style="flex: 1 !important; border: none !important; outline: none !important; padding: 0.8rem 1rem !important; font-size: 0.88rem !important; font-family: 'Inter', sans-serif !important; color: #1e293b !important; background: transparent !important; min-width: 0 !important; display: block !important; -moz-appearance: textfield;">
                            </div>
                        </div>
                    </div>

                    <!-- Imagen -->
                    <div style="margin-bottom: 1.1rem;">
                        <label style="display: block; font-size: 0.63rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.09em; margin-bottom: 0.5rem;">{{ editando ? 'Cambiar Imagen' : 'Imagen del Premio' }}</label>

                        <!-- Hidden real file input -->
                        <input type="file" name="imagen" accept="image/*" ref="fileInput" @change="onFileChange" style="position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none;" id="imgFileInput">

                        <!-- Upload trigger row -->
                        <div @click="$refs.fileInput.click()" @dragover.prevent @drop.prevent="onDrop"
                            style="display: flex !important; flex-direction: row !important; align-items: center !important; gap: 0.85rem !important; border: 1.5px dashed #e2e8f0 !important; border-radius: 12px !important; padding: 0.9rem 1rem !important; cursor: pointer !important; background: #fafbfc !important; transition: all 0.2s !important;">

                            <template v-if="previewUrl">
                                <img :src="previewUrl" alt="preview"
                                    style="width: 38px !important; height: 38px !important; border-radius: 8px !important; object-fit: cover !important; border: 2px solid #e2e8f0 !important; flex-shrink: 0 !important;">
                                <div style="flex: 1 !important; min-width: 0 !important;">
                                    <span style="display: block !important; font-size: 0.82rem !important; font-weight: 700 !important; color: #1e293b !important;">Imagen seleccionada</span>
                                    <span style="font-size: 0.68rem !important; color: #94a3b8 !important; font-weight: 500 !important;">Haz clic para cambiar</span>
                                </div>
                                <button type="button" @click.stop="clearImage"
                                    style="width: 26px !important; height: 26px !important; border-radius: 50% !important; border: none !important; background: #fef2f2 !important; color: #dc2626 !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 1rem !important; flex-shrink: 0 !important;">
                                    <i class='bx bx-x'></i>
                                </button>
                            </template>

                            <template v-else>
                                <div style="width: 38px !important; height: 38px !important; border-radius: 10px !important; background: #f1f5f9 !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 1.2rem !important; color: #94a3b8 !important; flex-shrink: 0 !important;">
                                    <i class='bx bx-image-add'></i>
                                </div>
                                <div style="flex: 1 !important; min-width: 0 !important;">
                                    <span style="display: block !important; font-size: 0.82rem !important; font-weight: 700 !important; color: #1e293b !important;">Seleccionar imagen</span>
                                    <span style="font-size: 0.68rem !important; color: #94a3b8 !important; font-weight: 500 !important;">PNG, JPG, WEBP · Arrastra o haz clic</span>
                                </div>
                                <i class='bx bx-upload' style="color: #cbd5e1 !important; font-size: 1.1rem !important; flex-shrink: 0 !important;"></i>
                            </template>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label style="display: block; font-size: 0.63rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.09em; margin-bottom: 0.5rem;">Estado en Tienda</label>
                        <div style="display: flex !important; flex-direction: row !important; align-items: center !important; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #fff; overflow: hidden;">
                            <span style="flex-shrink: 0; width: 42px; height: 44px; display: flex !important; align-items: center !important; justify-content: center !important; color: #94a3b8; font-size: 1.1rem; border-right: 1.5px solid #f1f5f9; background: #f8fafc;">
                                <i class='bx bx-show'></i>
                            </span>
                            <select name="estado" v-model="form.estado"
                                style="flex: 1 !important; border: none !important; outline: none !important; padding: 0.8rem 1rem !important; font-size: 0.88rem !important; font-family: 'Inter', sans-serif !important; color: #1e293b !important; background: transparent !important; min-width: 0 !important; cursor: pointer !important; appearance: none !important; background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%2394a3b8%27 stroke-width=%272%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E') !important; background-repeat: no-repeat !important; background-position: right 0.85rem center !important; background-size: 1rem !important; padding-right: 2.5rem !important;">
                                <option value="1">Activo / Visible</option>
                                <option value="0">Inactivo / Oculto</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer-premium" style="gap: 0.75rem;">
                    <button type="button" @click="cerrarModal"
                        style="background: #f8fafc; color: #64748b; padding: 0.8rem 1.65rem; border-radius: 10px; border: 1.5px solid #e2e8f0; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary-premium" :disabled="submitting">
                        <i :class="['bx', submitting ? 'bx-loader-alt bx-spin' : (editando ? 'bx-save' : 'bx-plus-circle')]"></i>
                        {{ submitting ? 'Guardando...' : (editando ? 'Guardar Cambios' : 'Registrar Premio') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    var PRODUCTOS = <?= json_encode($productos) ?>;
    var BASE_URL  = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>views/productos/index.js"></script>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
