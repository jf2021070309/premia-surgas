<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conductores — PremiaSurgas</title>
    <!-- Bootstrap 5 for consistent UI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>
        [v-cloak] { display: none; }
        .btn-primary { background-color: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background-color: var(--primary-dk); border-color: var(--primary-dk); }
        .topbar { margin-bottom: 0; }
        /* Table overrides */
        table { border-collapse: separate !important; }
        .container { max-width: 1000px; }
    </style>
</head>
<body>
<div id="app" v-cloak>
    <div class="topbar">
        <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center py-2">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <a href="<?= BASE_URL ?>panel" class="text-white text-decoration-none fs-4 me-3">←</a>
                <span class="topbar-logo text-white">🚚 Conductores</span>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-0">🔍</span>
                    <input type="text" class="form-control border-0" placeholder="Buscar" v-model="busqueda">

                </div>
                <select class="form-select form-select-sm border-0" style="width: 130px;" v-model="filtroEstado">
                    <option value="todos">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
                <a href="<?= BASE_URL ?>conductores/nuevo" class="btn btn-primary btn-sm">
                    + Nuevo
                </a>
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
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in conductoresFiltrados" :key="c.id">
                            <td class="ps-3">{{ c.id }}</td>
                            <td><strong>{{ c.nombre }}</strong></td>
                            <td>{{ c.usuario }}</td>
                            <td>
                                <span :class="['badge', c.estado == 1 ? 'bg-success' : 'bg-secondary']">
                                    {{ c.estado == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a :href="'<?= BASE_URL ?>conductores/editar?id=' + c.id" class="btn btn-link btn-sm text-primary p-0 me-2" title="Editar">✏️</a>
                                <button class="btn btn-link btn-sm text-danger p-0" 
                                        @click="confirmInactivar(c.id)" title="Inactivar">🗑️</button>
                            </td>
                        </tr>
                        <tr v-if="conductoresFiltrados.length === 0">
                            <td colspan="5" class="text-center py-5 text-muted">No se encontraron conductores.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
