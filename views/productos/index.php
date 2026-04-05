<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos — PremiaSurgas</title>
    <!-- Use Bootstrap 5 for the new requested UI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        [v-cloak] { display: none; }
        .dropzone-area {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .dropzone-area:hover, .dropzone-area.dragover {
            border-color: var(--primary);
            background: #fff;
        }
        .dropzone-preview {
            max-width: 100%;
            max-height: 150px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .btn-primary { background-color: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background-color: var(--primary-dk); border-color: var(--primary-dk); }
        .topbar { margin-bottom: 0; }
        .modal-header { background: var(--dark); color: white; }
        .modal-header .btn-close { filter: invert(1); }
        
        /* Table overrides for Bootstrap conflict */
        table { border-collapse: separate !important; }
        .container { max-width: 1000px; }
    </style>
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
                <button class="btn btn-primary shadow-sm me-3" style="border-radius:100px; padding:0.5rem 1.2rem; font-weight:700;" @click="nuevoProducto">
                    <i class='bx bx-plus-circle'></i> Nuevo Premio
                </button>
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
        <div class="header-hero-content" style="padding: 1rem 0;">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <div class="input-group input-group-sm shadow-sm" style="width: 250px; border-radius:10px; overflow:hidden;">
                    <span class="input-group-text bg-white border-0">🔍</span>
                    <input type="text" class="form-control border-0" placeholder="Buscar" v-model="busqueda">

                </div>
                <select class="form-select form-select-sm border-0 shadow-sm" style="width: 130px; border-radius:10px;" v-model="filtroEstado">
                    <option value="todos">Todos los Estados</option>
                    <option value="1">Solo Activos</option>
                    <option value="0">Solo Inactivos</option>
                </select>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card p-0 shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Puntos</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in productosFiltrados" :key="p.id">
                            <td class="ps-3">{{ p.id }}</td>
                            <td>
                                <img :src="'<?= BASE_URL ?>assets/premios/' + p.image_display + '?v=' + cacheBuster" 
                                     class="rounded" style="width: 40px; height: 40px; object-fit: cover;"
                                     @error="onImgError">
                            </td>
                            <td><strong>{{ p.nombre }}</strong></td>
                            <td><span class="badge bg-info text-dark">{{ p.puntos }} pts</span></td>
                            <td>{{ p.stock }}</td>
                            <td>
                                <span :class="['badge', p.estado == 1 ? 'bg-success' : 'bg-secondary']">
                                    {{ p.estado == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <button class="btn btn-link btn-sm text-primary p-0 me-2" @click="editarProducto(p)" title="Editar">✏️</button>
                                <a href="#" 
                                   class="btn btn-link btn-sm text-danger p-0"
                                   @click.prevent="eliminarProducto(p.id)" title="Eliminar">🗑️</a>
                            </td>
                        </tr>
                        <tr v-if="productos.length === 0">
                            <td colspan="7" class="text-center py-5 text-muted">No hay productos registrados.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Formulario -->
    <div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">{{ editando ? 'Editar Producto' : 'Nuevo Producto' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form :action="editando ? '<?= BASE_URL ?>productos/update' : '<?= BASE_URL ?>productos/create'" 
                      method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" v-model="form.id">
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre del Producto</label>
                            <input type="text" name="nombre" class="form-control" v-model="form.nombre" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2" v-model="form.descripcion"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Puntos</label>
                                <input type="number" name="puntos" class="form-control" v-model="form.puntos" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock" class="form-control" v-model="form.stock" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre de archivo imagen (sin .png)</label>
                            <input type="text" name="nombre_imagen" class="form-control" v-model="form.nombre_imagen" placeholder="ej: cocacola_litro">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Imagen del Producto</label>
                            <div class="dropzone-area" 
                                 @click="$refs.fileInput.click()"
                                 @dragover.prevent="onDragOver"
                                 @dragleave="onDragLeave"
                                 @drop.prevent="onDrop"
                                 :class="{ 'dragover': isDragging }">
                                <div v-if="!previewUrl">
                                    <p class="mb-0">Arrastra una imagen aquí o haz clic para subir</p>
                                    <small class="text-muted">Se guardará como .png</small>
                                </div>
                                <img v-else :src="previewUrl" class="dropzone-preview">
                                <input type="file" name="imagen_file" ref="fileInput" class="d-none" @change="onFileChange" accept="image/*">
                            </div>
                            <small class="text-muted" v-if="editando && !previewUrl">
                                Selección actual: {{ form.imagen_actual }}
                            </small>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select" v-model="form.estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            {{ editando ? 'Actualizar Cambios' : 'Crear Producto' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    var PRODUCTOS = <?= json_encode($productos) ?>;
    var BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>views/productos/index.js"></script>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
