<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Afiliados Comercial — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        [v-cloak] { display: none !important; }
        
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .modal-card {
            background: #fff;
            width: 100%;
            max-width: 600px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .modal-header {
            padding: 1.5rem 2rem;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
        }

        .modal-header-icon {
            width: 44px;
            height: 44px;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #991b1b;
            font-size: 1.5rem;
        }

        .modal-header-text h3 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .modal-header-text span {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }

        .modal-close {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            background: #f1f5f9;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .input-with-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-with-icon i:not(.toggle-pass):not(.select-arrow) {
            position: absolute;
            left: 1rem;
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .input-with-icon input, 
        .input-with-icon select {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            color: #1e293b;
            outline: none;
            transition: all 0.2s;
        }

        .input-with-icon input:focus, 
        .input-with-icon select:focus {
            border-color: #800000;
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.05);
        }

        .toggle-pass {
            position: absolute;
            right: 1rem;
            color: #94a3b8;
            cursor: pointer;
        }

        .select-arrow {
            position: absolute;
            right: 1rem;
            color: #94a3b8;
            pointer-events: none;
        }

        .modal-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .btn-cancel {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-save {
            padding: 0.75rem 2rem;
            border-radius: 12px;
            border: none;
            background: #000;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-save:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .animate-slide-up {
            animation: slideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
            .modal-card { border-radius: 0; height: 100%; max-width: 100%; margin: 0; }
            .modal-overlay { padding: 0; }
        }
    </style>
</head>
<body>
<div id="app" v-cloak>
    
    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Afiliados Comerciales';
            $pageSubtitle = 'Establecimientos en convenio';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="clientes-toolbar" style="margin-top: 1rem;">
                <div class="clientes-toolbar-filters">
                    <div class="header-search-modern" style="width: 220px;">
                        <i class='bx bx-filter-alt'></i>
                        <select v-model="filtroEstado">
                            <option value="todos">Todos los Estados</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                </div>
                <div class="clientes-toolbar-search" style="flex: 1; justify-content: space-between;">
                    <div class="header-search-modern clientes-search-input" style="max-width: 450px;">
                        <i class='bx bx-search'></i>
                        <input type="text" v-model="busqueda" placeholder="Buscar afiliado o usuario...">
                    </div>
                    <button @click="openModal()" class="btn-primary-premium btn-nuevo-cliente">
                        <i class='bx bx-plus-circle'></i>
                        <span>Nuevo</span>
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Afiliado Comercial</th>
                                <th class="col-hide-mobile">Departamento</th>
                                <th class="col-hide-mobile">Usuario</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="a in afiliadosFiltrados" :key="a.id">
                                <td>
                                    <div class="row-client">
                                        <div class="client-info">
                                            <span style="font-weight: 700; color: #1e293b; font-size: 0.95rem; display: block;">{{ a.nombre }}</span>
                                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">@{{ a.usuario }}</span>
                                            
                                            <!-- Meta info solo para móvil -->
                                            <div class="mobile-client-meta">
                                                <span v-if="a.departamento"><i class='bx bx-map-pin'></i> {{ a.departamento }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-hide-mobile">
                                    <span class="badge" style="background:#f1f5f9; color:#475569; font-weight:700; font-size:0.75rem; padding:6px 12px; border-radius:8px;">
                                        {{ a.departamento || 'No asignado' }}
                                    </span>
                                </td>
                                <td class="col-hide-mobile">
                                    <div style="font-weight: 600; color: #475569; font-size: 0.85rem;">{{ a.usuario }}</div>
                                </td>
                                <td class="text-center">
                                    <span :class="['chip', a.estado == 1 ? 'chip-approved' : 'chip-rejected']" style="padding: 6px 12px; font-weight: 700; font-size: 0.75rem;">
                                        <i class='bx bxs-circle' style="font-size: 0.5rem;"></i> {{ a.estado == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-flex" style="justify-content: center; gap: 8px;">
                                        <button @click="openModal(a)" class="btn-action blue" title="Editar Afiliado" style="width: 34px; height: 34px; border-radius: 10px;">
                                            <i class='bx bx-edit-alt' style="font-size: 1.1rem;"></i>
                                        </button>
                                        <button :class="['btn-action', a.estado == 1 ? 'red' : 'green']" @click="confirmInactivar(a.id)" :title="a.estado == 1 ? 'Desactivar' : 'Reactivar'" style="width: 34px; height: 34px; border-radius: 10px;">
                                            <i :class="['bx', a.estado == 1 ? 'bx-trash' : 'bx-refresh']" style="font-size: 1.1rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="afiliadosFiltrados.length === 0">
                                <td colspan="5">
                                    <div class="empty-table" style="padding: 4rem 0;">
                                        <i class='bx bx-search-alt' style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;"></i>
                                        <p style="font-weight: 600; color: #94a3b8;">No se encontraron afiliados comerciales que coincidan con la búsqueda.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer-premium">
                    <div class="footer-info">Mostrando {{ afiliadosFiltrados.length }} de {{ afiliados.length }} afiliados</div>
                </div>
            </div>

            <!-- MODAL NUEVO/EDITAR AFILIADO -->
            <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
                <div class="modal-card animate-slide-up">
                    <div class="modal-header">
                        <div class="modal-header-icon">
                            <i class='bx bxs-store-alt'></i>
                        </div>
                        <div class="modal-header-text">
                            <h3>{{ form.id ? 'Editar Afiliado' : 'Nuevo Afiliado Comercial' }}</h3>
                            <span>{{ form.id ? 'Modifica los datos del establecimiento' : 'Registra un nuevo punto de venta asociado' }}</span>
                        </div>
                        <button class="modal-close" @click="closeModal">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>

                    <form @submit.prevent="saveAfiliado" class="modal-body">
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label>Nombre del Establecimiento / Afiliado</label>
                                <div class="input-with-icon">
                                    <i class='bx bx-building-house'></i>
                                    <input type="text" v-model="form.nombre" required placeholder="Ejem: Restaurante Chite">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Usuario (Login)</label>
                                <div class="input-with-icon">
                                    <i class='bx bx-user-circle'></i>
                                    <input type="text" v-model="form.usuario" required placeholder="Usuario de acceso">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Contraseña {{ form.id ? '(Opcional)' : '' }}</label>
                                <div class="input-with-icon">
                                    <i class='bx bx-lock-alt'></i>
                                    <input :type="showPass ? 'text' : 'password'" v-model="form.password" :required="!form.id" placeholder="••••••••">
                                    <i :class="['bx', showPass ? 'bx-hide' : 'bx-show', 'toggle-pass']" @click="showPass = !showPass"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Departamento</label>
                                <div class="input-with-icon">
                                    <i class='bx bx-map'></i>
                                    <select v-model="form.departamento" required>
                                        <option value="">-- Seleccionar --</option>
                                        <option value="Tacna">Tacna</option>
                                        <option value="Moquegua">Moquegua</option>
                                        <option value="Arequipa">Arequipa</option>
                                        <option value="Ilo">Ilo</option>
                                    </select>
                                    <i class='bx bx-chevron-down select-arrow'></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Estado</label>
                                <div class="input-with-icon">
                                    <i class='bx bx-toggle-right'></i>
                                    <select v-model="form.estado">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                    <i class='bx bx-chevron-down select-arrow'></i>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" @click="closeModal">Cancelar</button>
                            <button type="submit" class="btn-save" :disabled="loading">
                                <i v-if="loading" class='bx bx-loader-alt bx-spin'></i>
                                {{ form.id ? 'Guardar Cambios' : 'Registrar Afiliado' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    var AFILIADOS = <?= json_encode($afiliados) ?>;
    var BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>views/afiliados/index.js"></script>

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
