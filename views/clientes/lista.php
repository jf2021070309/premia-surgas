<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>[v-cloak]{display:none}</style>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>[v-cloak]{display:none}</style>
</head>
<body>
<div id="app" v-cloak>
    <div class="panel-header">
        <div class="header-top-row">
            <div class="header-logo-side">
                <a href="<?= BASE_URL ?>panel" style="text-decoration:none; display:flex; align-items:center; gap:10px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1); padding: 8px 20px; border-radius:100px; color: white; transition:0.3s;" title="Volver al Panel">
                    <i class='bx bx-left-arrow-alt' style="font-size: 1.5rem;"></i>
                    <span style="font-weight: 700; font-size: 0.9rem; letter-spacing: 0.5px;">VOLVER</span>
                </a>
            </div>

            <div class="header-user-side">
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

        <!-- Título principal estilo Hero (debajo de la botonera) -->
        <div class="header-hero-content">
            <h1 class="hero-main-title">Directorio</h1>
            <p class="hero-welcome-msg">Toda tu base de datos a un clic.</p>
        </div>
    </div>

    <div class="container" style="margin-top: -1.5rem;">        <div class="card" style="padding:1rem">
            <div style="display: flex; gap: 1rem; margin-bottom: 1.2rem; align-items: center; flex-wrap: wrap;">
                <div style="position: relative; flex: 1; min-width: 250px;">
                    <i class='bx bx-search' style="position: absolute; left: 1.1rem; top: 50%; transform: translateY(-50%); color: #a1a5b7; font-size: 1.3rem;"></i>
                    <input type="text" v-model="busqueda" placeholder="Buscar por DNI/RUC, nombre o celular..."
                           style="width:100%; padding:.85rem 1rem .85rem 3rem; border:1px solid #e4e6ef; border-radius:8px; font-family:inherit; font-size:.95rem; color:#3f4254; box-sizing:border-box; outline:none; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.02);"
                           onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 4px 12px rgba(130,21,21,0.1)'" onblur="this.style.borderColor='#e4e6ef'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.02)'">
                </div>
                <a href="<?= BASE_URL ?>clientes/nuevo" style="background: var(--primary); color: white; display: flex; align-items: center; gap: 8px; padding: .85rem 1.6rem; border-radius: 8px; font-weight: 700; font-size: .95rem; text-decoration: none; flex-shrink: 0; box-shadow: 0 4px 10px rgba(130,21,21,0.25); border: 1px solid var(--primary); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(130,21,21,0.35)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(130,21,21,0.25)'">
                    <i class='bx bx-plus-circle' style="font-size: 1.2rem;"></i> Nuevo
                </a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Celular</th>
                            <th>Documento</th>
                            <th>Dep.</th>
                            <th>Puntos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in filtrados" :key="c.id">
                            <td>
                                <div>{{ c.tipo_cliente === 'Normal' ? c.nombre : (c.razon_social || c.nombre) }}</div>
                                <div v-if="c.tipo_cliente !== 'Normal'" style="font-size:0.75rem; color:var(--muted);">
                                    <i class="bx bx-user" style="font-size:0.75rem;"></i> {{ c.nombre }}
                                </div>
                            </td>
                            <td>
                                <span class="badge" :class="c.tipo_cliente === 'Normal' ? 'badge-primary' : 'badge-warning'" style="font-size:0.75rem; padding: 3px 6px; border-radius: 4px; background: var(--bg-soft); border: 1px solid var(--border-color); color: var(--dark);">
                                    <i :class="c.tipo_cliente === 'Normal' ? 'bx bx-user' : 'bx bx-buildings'"></i> {{ c.tipo_cliente || 'Normal' }}
                                </span>
                            </td>
                            <td>{{ c.celular }}</td>
                            <td>{{ c.ruc || c.dni || '—' }}</td>
                            <td>{{ c.departamento || '—' }}</td>
                            <td><strong style="color:var(--primary)">{{ c.puntos }}</strong></td>
                            <td style="display: flex; gap: 8px; align-items: center;">
                                <a :href="'<?= BASE_URL ?>clientes/exito?id=' + c.id" title="Ver Nuevo Carnet" style="color: #0ea5e9; font-size: 1.15rem; background: #e0f2fe; padding: 5px; border-radius: 6px; display: inline-flex; transition: 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class='bx bx-id-card'></i>
                                </a>
                                <a :href="'<?= BASE_URL ?>clientes/imprimir?id=' + c.id" target="_blank" title="Imprimir" style="color: #64748b; font-size: 1.15rem; background: #f1f5f9; padding: 5px; border-radius: 6px; display: inline-flex; transition: 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class='bx bx-printer'></i>
                                </a>
                                <?php if ($_SESSION['rol'] === 'admin'): ?>
                                <a :href="'<?= BASE_URL ?>clientes/editar?id=' + c.id" title="Editar" style="color: #f59e0b; font-size: 1.15rem; background: #fef3c7; padding: 5px; border-radius: 6px; display: inline-flex; transition: 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <a v-if="c.estado == 1" href="#" @click.prevent="toggleEstado(c.id, 0)" title="Inactivar" style="color: #ef4444; font-size: 1.15rem; background: #fee2e2; padding: 5px; border-radius: 6px; display: inline-flex; transition: 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class='bx bx-block'></i>
                                </a>
                                <a v-else href="#" @click.prevent="toggleEstado(c.id, 1)" title="Activar" style="color: #10b981; font-size: 1.15rem; background: #d1fae5; padding: 5px; border-radius: 6px; display: inline-flex; transition: 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class='bx bx-check-circle'></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr v-if="filtrados.length === 0">
                            <td colspan="5" style="text-align:center; color:var(--muted); padding:2rem">Sin resultados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
