<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Conductores — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        [v-cloak] { display: none !important; }
    </style>
</head>
<body>
<div id="app" v-cloak>
    
    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Conductores';
            $pageSubtitle = 'Administración de equipo de reparto';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title-icon red-premium"><i class='bx bx-bus'></i></div>
                        <span>Directorio de Conductores</span>
                    </div>
                    <div class="header-actions">
                        <div class="header-filter">
                            <i class='bx bx-filter-alt'></i>
                            <select v-model="filtroEstado">
                                <option value="todos">Todos los Estados</option>
                                <option value="1">Activos</option>
                                <option value="0">Inactivos</option>
                            </select>
                        </div>
                        <div class="header-search">
                            <i class='bx bx-search'></i>
                            <input type="text" placeholder="Buscar" v-model="busqueda">
                        </div>
                        <a href="<?= BASE_URL ?>conductores/nuevo" class="btn-primary-premium">
                            <i class='bx bx-user-plus'></i> Nuevo
                        </a>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th># ID</th>
                                <th>Conductor</th>
                                <th>Usuario</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="c in conductoresFiltrados" :key="c.id">
                                <td><span class="text-medium">#{{ c.id }}</span></td>
                                <td>
                                    <div class="row-client">
                                        <div class="row-avatar" style="background:#fdf2f2; color:#800000; display:flex; align-items:center; justify-content:center; border-radius:50%; width:36px; height:36px; border:1px solid #fee2e2;">
                                            <i class='bx bx-user'></i>
                                        </div>
                                        <span class="text-medium">{{ c.nombre }}</span>
                                    </div>
                                </td>
                                <td><span class="text-medium">{{ c.usuario }}</span></td>
                                <td>
                                    <span :class="['badge-status', c.estado == 1 ? 'badge-approved' : 'badge-rejected']">
                                        {{ c.estado == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-flex" style="justify-content: center;">
                                        <a :href="'<?= BASE_URL ?>conductores/editar?id=' + c.id" class="btn-action blue" title="Editar">
                                            <i class='bx bx-edit-alt'></i>
                                        </a>
                                        <button class="btn-action red" @click="confirmInactivar(c.id)" :title="c.estado == 1 ? 'Desactivar' : 'Reactivar'">
                                            <i :class="['bx', c.estado == 1 ? 'bx-trash' : 'bx-refresh']"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="conductoresFiltrados.length === 0">
                                <td colspan="5" class="text-center py-5">
                                    <div style="color: #94a3b8; font-size: 0.9rem;">
                                        <i class='bx bx-search-alt' style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                        No se encontraron conductores.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer-premium">
                    <div class="footer-info">Mostrando {{ conductoresFiltrados.length }} de {{ conductores.length }} conductores</div>
                    <!-- Sin paginación por ahora o implementada en JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    var CONDUCTORES = <?= json_encode($conductores) ?>;
    var BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>views/conductores/index.js"></script>

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
