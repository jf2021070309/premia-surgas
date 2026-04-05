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

        /* Product image in table */
        .product-img-box {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #f1f5f9;
            background: #fff;
        }

        /* Points badge */
        .pts-badge {
            background: #e0f2fe;
            color: #0369a1;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            border: 1px solid #bae6fd;
        }

        /* ── Modal Form Enhancements ── */
        .modal-icon-focal {
            position: absolute;
            left: 1.15rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.15rem;
            pointer-events: none;
            transition: color 0.2s;
            z-index: 2;
        }

        .form-group:focus-within .modal-icon-focal { color: #1e293b; }

        /* File upload zone */
        .upload-zone {
            border: 2px dashed #e2e8f0;
            border-radius: 14px;
            padding: 1.5rem 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            background: #fafbfc;
            position: relative;
            overflow: hidden;
        }
        .upload-zone:hover, .upload-zone.drag-over {
            border-color: #800000;
            background: #fdf5f5;
        }
        .upload-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .upload-zone-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.5rem;
            color: #94a3b8;
            transition: all 0.2s;
        }
        .upload-zone:hover .upload-zone-icon, .upload-zone.drag-over .upload-zone-icon {
            background: #fdf2f2;
            color: #800000;
        }
        .upload-zone-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 3px;
        }
        .upload-zone-sub {
            font-size: 0.7rem;
            color: #94a3b8;
            font-weight: 500;
        }
        /* Image preview inside upload zone */
        .upload-preview-wrap {
            position: relative;
            display: inline-block;
        }
        .upload-preview-wrap img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }
        .upload-preview-clear {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #dc2626;
            color: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        /* Select icon focal */
        .form-select-premium {
            width: 100%;
            padding: 0.85rem 1.15rem 0.85rem 2.8rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #fff;
            outline: none;
            transition: all 0.25s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
            cursor: pointer;
        }
        .form-select-premium:focus {
            border-color: #000;
            box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
        }

        /* Modal footer btn cancel */
        .btn-modal-cancel {
            background: #f8fafc;
            color: #64748b;
            padding: 0.85rem 1.75rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-modal-cancel:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        /* Modal form group spacing */
        .modal-body-premium .form-group { margin-bottom: 1.5rem; }
        .modal-body-premium .form-group:last-child { margin-bottom: 0; }

        /* Two-column grid in modal */
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        @media (max-width: 540px) {
            .form-grid-2 { grid-template-columns: 1fr; }
        }
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
                    <i class='bx bx-gift'></i>
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
                                <td colspan="5" class="text-center py-5">
                                    <div style="color: #94a3b8; font-size: 0.9rem;">
                                        <i class='bx bx-package' style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem; opacity: 0.5;"></i>
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
        <div class="modal-content-wrapper" style="max-width: 520px;">

            <!-- Header -->
            <div class="modal-header-premium">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 42px; height: 42px; border-radius: 12px; background: #fdf2f2; border: 1px solid #fee2e2; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #800000; flex-shrink: 0;">
                        <i :class="['bx', editando ? 'bx-edit' : 'bx-gift']"></i>
                    </div>
                    <div>
                        <h2 style="font-size: 1.1rem;">{{ editando ? 'Editar Premio' : 'Registrar Nuevo Premio' }}</h2>
                        <p style="font-size: 0.75rem; color: #64748b; margin-top: 2px; font-weight: 500;">{{ editando ? 'Modifica los datos del producto seleccionado' : 'Completa los datos del nuevo producto' }}</p>
                    </div>
                </div>
                <div class="modal-close" @click="cerrarModal" title="Cerrar">
                    <i class='bx bx-x' style="font-size: 1.3rem;"></i>
                </div>
            </div>

            <!-- Body -->
            <form :action="'<?= BASE_URL ?>productos/' + (editando ? 'update' : 'store')" method="POST" enctype="multipart/form-data" @submit="submitting = true">
                <input type="hidden" name="id" :value="form.id" v-if="editando">
                <input type="hidden" name="imagen_actual" :value="form.imagen_actual" v-if="editando">

                <div class="modal-body-premium">

                    <!-- Nombre del Premio -->
                    <div class="form-group">
                        <label class="form-label-premium">Nombre Comercial del Premio</label>
                        <div style="position: relative;">
                            <i class='bx bx-purchase-tag modal-icon-focal'></i>
                            <input
                                type="text"
                                name="nombre"
                                class="form-input-premium"
                                placeholder="Ej: Auriculares Bluetooth Premium"
                                v-model="form.nombre"
                                required
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    <!-- Puntos & Stock (2 columnas) -->
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label-premium">Inversión Puntos</label>
                            <div style="position: relative;">
                                <i class='bx bx-star modal-icon-focal'></i>
                                <input
                                    type="number"
                                    name="puntos"
                                    class="form-input-premium"
                                    placeholder="0"
                                    v-model="form.puntos"
                                    min="0"
                                    required
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label-premium">Stock Actual</label>
                            <div style="position: relative;">
                                <i class='bx bx-cube modal-icon-focal'></i>
                                <input
                                    type="number"
                                    name="stock"
                                    class="form-input-premium"
                                    placeholder="0"
                                    v-model="form.stock"
                                    min="0"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Imagen -->
                    <div class="form-group">
                        <label class="form-label-premium">{{ editando ? 'Cambiar Imagen' : 'Imagen del Premio' }}</label>
                        <div
                            class="upload-zone"
                            :class="{ 'drag-over': isDragging }"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="onDrop"
                        >
                            <input type="file" name="imagen" accept="image/*" ref="fileInput" @change="onFileChange">

                            <!-- Sin preview -->
                            <template v-if="!previewUrl">
                                <div class="upload-zone-icon">
                                    <i class='bx bx-image-add'></i>
                                </div>
                                <div class="upload-zone-title">Arrastra aquí o haz clic para subir</div>
                                <div class="upload-zone-sub">PNG, JPG, WEBP — máx. 2 MB</div>
                            </template>

                            <!-- Con preview -->
                            <template v-else>
                                <div class="upload-preview-wrap" @click.stop>
                                    <img :src="previewUrl" alt="preview">
                                    <button type="button" class="upload-preview-clear" @click.stop="clearImage" title="Quitar imagen">
                                        <i class='bx bx-x'></i>
                                    </button>
                                </div>
                                <div class="upload-zone-sub" style="margin-top: 0.5rem;">Haz clic o arrastra para cambiar</div>
                            </template>
                        </div>
                    </div>

                    <!-- Estado en Tienda -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label-premium">Estado en Tienda</label>
                        <div style="position: relative;">
                            <i class='bx bx-show modal-icon-focal'></i>
                            <select name="estado" class="form-select-premium" v-model="form.estado">
                                <option value="1">Activo / Visible</option>
                                <option value="0">Inactivo / Oculto</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer-premium" style="gap: 0.75rem;">
                    <button type="button" class="btn-modal-cancel" @click="cerrarModal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-premium-pill-black" :disabled="submitting">
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
