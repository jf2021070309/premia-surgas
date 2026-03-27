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
                        <i class='bx bx-group title-icon orange'></i>
                        <span>Lista de Beneficiarios</span>
                    </div>
                    
                    <div class="header-actions">
                        <div class="header-filter">
                            <i class='bx bx-filter-alt'></i>
                            <select v-model="filterTipo">
                                <option value="">Todos los Tipos</option>
                                <option value="Normal">Personal</option>
                                <option value="Empresa">Empresa</option>
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
                            <i class='bx bx-plus-circle'></i> Nuevo
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
                                        <div class="row-avatar">{{ c.tipo_cliente === 'Normal' ? (c.nombre ? c.nombre.charAt(0) : '?') : (c.razon_social ? c.razon_social.charAt(0) : '?') }}</div>
                                        <div class="client-info">
                                            <div class="client-name">{{ c.tipo_cliente === 'Normal' ? c.nombre : (c.razon_social || c.nombre) }}</div>
                                            <div v-if="c.tipo_cliente !== 'Normal'" class="client-subtext">{{ c.nombre }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="chip" :class="c.tipo_cliente === 'Normal' ? 'chip-normal' : 'chip-business'">
                                        <i :class="c.tipo_cliente === 'Normal' ? 'bx bx-user' : 'bx bx-buildings'"></i>
                                        {{ c.tipo_cliente || 'Normal' }}
                                    </span>
                                </td>
                                <td><span class="text-medium">{{ c.celular }}</span></td>
                                <td><span class="text-mono"><?= htmlspecialchars($c->ruc ?? $c->dni ?? '—') ?></span></td>
                                <td>{{ c.departamento || '—' }}</td>
                                <td><strong class="pts-positive">{{ c.puntos }} pts</strong></td>
                                <td>
                                    <div class="actions-flex">
                                        <a :href="'<?= BASE_URL ?>clientes/exito?id=' + c.id" class="btn-action blue" title="Ver Carnet">
                                            <i class='bx bx-id-card'></i>
                                        </a>
                                        <a :href="'<?= BASE_URL ?>clientes/imprimir?id=' + c.id" target="_blank" class="btn-action gray" title="Imprimir">
                                            <i class='bx bx-printer'></i>
                                        </a>
                                        <?php if ($_SESSION['rol'] === 'admin'): ?>
                                        <a :href="'<?= BASE_URL ?>clientes/editar?id=' + c.id" class="btn-action orange" title="Editar">
                                            <i class='bx bx-edit'></i>
                                        </a>
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
</div> <!-- #app -->
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
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
