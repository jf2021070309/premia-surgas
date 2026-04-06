<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría de Movimientos — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        [v-cloak] { display: none !important; }
        .audit-badge { 
            padding: 4px 10px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; 
            letter-spacing: 0.03em; display: inline-flex; align-items: center; gap: 5px;
        }
        .badge-login { background: #e0f2fe; color: #0369a1; }
        .badge-create { background: #dcfce7; color: #15803d; }
        .badge-update { background: #fef9c3; color: #854d0e; }
        .badge-delete { background: #fee2e2; color: #991b1b; }
        .badge-other { background: #f1f5f9; color: #475569; }

        .modulo-pill { background: #f8fafc; padding: 4px 10px; border-radius: 8px; font-size: 0.65rem; font-weight: 700; color: #64748b; border: 1px solid #e2e8f0; text-transform: uppercase; }
        
        .user-tag { display: flex; align-items: center; gap: 0.75rem; }
        .user-avatar { width: 32px; height: 32px; border-radius: 9px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #800000; font-weight: 700; font-size: 0.75rem; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div id="app" v-cloak>
        <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

        <div class="admin-layout">
            <?php
            $pageTitle = 'Auditoría de Sistema';
            $pageSubtitle = 'Monitoreo de movimientos y acciones del personal';
            include __DIR__ . '/../partials/header_admin.php';
            ?>

            <div class="container animate-fade-in" style="margin-top: 2rem;">
                
                <div class="modern-section-header" style="margin-bottom: 1.5rem; justify-content: space-between;">
                    <div class="header-search-modern" style="flex: 1; max-width: 400px;">
                        <i class='bx bx-search'></i>
                        <input type="text" v-model="busqueda" placeholder="Buscar por acción, usuario o descripción...">
                    </div>
                    <div class="d-flex gap-2">
                        <button @click="cargarDatos" class="btn-action gray" title="Actualizar" style="width: auto; padding: 0 1.2rem; border-radius: 12px; height: 3.2rem;">
                            <i class='bx bx-refresh'></i> <span style="font-size: 0.8rem; font-weight: 600; margin-left: 5px;">Actualizar</span>
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%">Fecha / Hora</th>
                                    <th style="width: 20%">Usuario / Trabajador</th>
                                    <th style="width: 15%">Módulo</th>
                                    <th style="width: 15%">Acción</th>
                                    <th style="width: 25%">Detalles</th>
                                    <th style="width: 10%">IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="log in filtrados" :key="log.id">
                                    <td>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 0.82rem;">{{ formatFecha(log.fecha_hora) }}</div>
                                        <div style="font-size: 0.7rem; color: #94a3b8;">{{ formatHora(log.fecha_hora) }}</div>
                                    </td>
                                    <td>
                                        <div class="user-tag">
                                            <div class="user-avatar">{{ (log.usuario_nombre || 'S').charAt(0) }}</div>
                                            <div>
                                                <div style="font-weight: 700; color: #0f172a; font-size: 0.8rem; line-height: 1.2; margin-bottom: 4px;">{{ log.usuario_nombre || 'Sistema' }}</div>
                                                <div v-if="log.usuario_rol" style="font-size: 0.6rem; font-weight: 800; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; display: inline-block; text-transform: uppercase; letter-spacing: 0.02em;">
                                                    {{ log.usuario_rol }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="modulo-pill">{{ log.modulo }}</span></td>
                                    <td><span :class="['audit-badge', getBadgeClass(log.accion)]">{{ log.accion }}</span></td>
                                    <td style="font-size: 0.78rem; color: #475569; line-height: 1.4;">{{ log.descripcion }}</td>
                                    <td style="font-family: monospace; font-size: 0.7rem; color: #94a3b8;">{{ log.ip_address }}</td>
                                </tr>
                                <tr v-if="filtrados.length === 0">
                                    <td colspan="6">
                                        <div class="empty-table">
                                            <i class='bx bx-info-circle'></i>
                                            <p>No se encontraron registros de auditoría.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>
        const { createApp } = Vue;
        createApp({
            data() {
                return {
                    busqueda: '',
                    logs: <?= json_encode($logs) ?>
                }
            },
            computed: {
                filtrados() {
                    if (!this.busqueda) return this.logs;
                    const t = this.busqueda.toLowerCase();
                    return this.logs.filter(l => 
                        (l.usuario_nombre || '').toLowerCase().includes(t) ||
                        (l.accion || '').toLowerCase().includes(t) ||
                        (l.modulo || '').toLowerCase().includes(t) ||
                        (l.descripcion || '').toLowerCase().includes(t)
                    );
                }
            },
            methods: {
                getBadgeClass(accion) {
                    const a = accion.toUpperCase();
                    if (a.includes('LOGIN') || a.includes('SESION')) return 'badge-login';
                    if (a.includes('REGISTRO') || a.includes('NUEVO') || a.includes('CREAR')) return 'badge-create';
                    if (a.includes('ACTUALIZAR') || a.includes('EDITAR')) return 'badge-update';
                    if (a.includes('ELIMINAR') || a.includes('QUITAR') || a.includes('BORRAR')) return 'badge-delete';
                    return 'badge-other';
                },
                formatFecha(fh) {
                    return new Date(fh).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                },
                formatHora(fh) {
                    return new Date(fh).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                },
                cargarDatos() {
                    fetch('<?= BASE_URL ?>reporte/getAuditLogsJson')
                        .then(res => res.json())
                        .then(data => {
                            this.logs = data;
                        })
                        .catch(err => console.error('Error al actualizar auditoría:', err));
                }
            },
            mounted() {
                // Actualizar cada 5 segundos
                setInterval(() => {
                    this.cargarDatos();
                }, 5000);
            }
        }).mount('#app');
    </script>
</body>
</html>
