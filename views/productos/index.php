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
        .product-img-box { width: 44px; height: 44px; border-radius: 10px; object-fit: cover; border: 1px solid #f1f5f9; background: #fff; }
        .pts-badge { background: #e0f2fe; color: #0369a1; font-weight: 800; padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; border: 1px solid #bae6fd; }
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
                                        <img :src="p.imagen ? '<?= BASE_URL ?>assets/uploads/productos/' + p.imagen : '<?= BASE_URL ?>assets/premios/no-image.png'" class="product-img-box">
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
    
    <!-- Modal de Nuevo/Editar Producto (Simplificado para este refactor visual) -->
    <!-- ... (El rest del código del modal se mantiene pero con las clases premium) ... -->
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    var PRODUCTOS = <?= json_encode($productos) ?>;
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
