<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría de Movimientos — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

        .change-detail { margin-top: 8px; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.72rem; }
        .change-item { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; color: #475569; }
        .change-item b { color: #0f172a; text-transform: capitalize; }
        .val-old { color: #991b1b; text-decoration: line-through; opacity: 0.7; }
        .val-new { color: #15803d; font-weight: 600; }

        .filter-group { display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; background: #fff; padding: 1.5rem; border-radius: 20px; border: 1px solid #f1f5f9; }
        .filter-item { display: flex; flex-direction: column; gap: 6px; }
        .filter-item label { font-size: 0.68rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
        .filter-input { border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.6rem 1rem; font-size: 0.85rem; color: #1e293b; outline: none; transition: border 0.2s; }
        .filter-input:focus { border-color: #800000; }
    </style>
</head>
<body>
    <div id="app" v-cloak>
        <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

        <div class="admin-layout">
            <?php
            $pageTitle = 'Auditoría de Sistema';
            $pageSubtitle = 'Monitoreo dinámico de acciones y cambios estructurales';
            include __DIR__ . '/../partials/header_admin.php';
            ?>

            <div class="container animate-fade-in" style="margin-top: 2rem;">
                
                <!-- Filtros Dinámicos -->
                <div class="filter-group">
                    <div class="filter-item" style="flex: 1; min-width: 250px;">
                        <label>Buscar Evento</label>
                        <input type="text" v-model="filters.busqueda" class="filter-input" placeholder="Acción, detalles o IP...">
                    </div>
                    <div class="filter-item">
                        <label>Rol Trabajador</label>
                        <select v-model="filters.rol" class="filter-input" style="width: 160px;">
                            <option value="">TODOS</option>
                            <option value="ADMIN">ADMINISTRADORES</option>
                            <option value="CONDUCTOR">CONDUCTORES</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Desde</label>
                        <input type="date" v-model="filters.fechaInicio" class="filter-input">
                    </div>
                    <div class="filter-item">
                        <label>Hasta</label>
                        <input type="date" v-model="filters.fechaFin" class="filter-input">
                    </div>
                    <div class="filter-item" style="justify-content: flex-end;">
                        <button @click="exportToExcel" class="btn-primary-premium" style="height: 3.2rem; background: #15803d;">
                            <i class='bx bx-file'></i> Exportar a Excel
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%">Fecha / Hora</th>
                                    <th style="width: 18%">Usuario / Trabajador</th>
                                    <th style="width: 12%">Módulo</th>
                                    <th style="width: 13%">Acción</th>
                                    <th style="width: 32%">Detalles / Cambios</th>
                                    <th style="width: 10%">Dispositivo</th>
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
                                                <div style="font-weight: 700; color: #0f172a; font-size: 0.8rem; line-height: 1.2; margin-bottom: 3px;">{{ log.usuario_nombre || 'Sistema' }}</div>
                                                <div v-if="log.usuario_rol" style="font-size: 0.6rem; font-weight: 800; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; display: inline-block; text-transform: uppercase;">
                                                    {{ log.usuario_rol }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="modulo-pill">{{ log.modulo }}</span></td>
                                    <td><span :class="['audit-badge', getBadgeClass(log.accion)]">{{ log.accion }}</span></td>
                                    <td>
                                        <div style="font-size: 0.78rem; color: #475569; font-weight: 500;">{{ log.descripcion }}</div>
                                        
                                        <!-- Detalles del Cambio (Metadatos) -->
                                        <div v-if="log.parsedMetadata" class="change-detail">
                                            <div v-for="(val, field) in log.parsedMetadata" :key="field" class="change-item">
                                                <i class='bx bx-chevron-right' style="color: #94a3b8;"></i>
                                                <b>{{ field.replace('_', ' ') }}:</b> 
                                                <span class="val-old" v-if="val.ant">{{ val.ant }}</span>
                                                <i class='bx bx-right-arrow-alt' style="font-size: 0.9rem; opacity: 0.5;"></i>
                                                <span class="val-new">{{ val.des }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.72rem; color: #0f172a; font-weight: 600;">{{ log.user_agent }}</div>
                                        <div style="font-family: monospace; font-size: 0.65rem; color: #94a3b8;">IP: {{ log.ip_address }}</div>
                                    </td>
                                </tr>
                                <tr v-if="filtrados.length === 0">
                                    <td colspan="6">
                                        <div class="empty-table">
                                            <i class='bx bx-search-alt'></i>
                                            <p>No se encontraron registros con los filtros aplicados.</p>
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
                    filters: {
                        busqueda: '',
                        rol: '',
                        fechaInicio: '',
                        fechaFin: ''
                    },
                    logs: []
                }
            },
            computed: {
                filtrados() {
                    return this.logs.filter(l => {
                        // Filtro Busqueda
                        const t = this.filters.busqueda.toLowerCase();
                        const matchesSearch = !t || 
                            (l.usuario_nombre || '').toLowerCase().includes(t) ||
                            (l.accion || '').toLowerCase().includes(t) ||
                            (l.descripcion || '').toLowerCase().includes(t) ||
                            (l.ip_address || '').toLowerCase().includes(t);
                        
                        // Filtro Rol
                        const matchesRol = !this.filters.rol || (l.usuario_rol || '').toUpperCase() === this.filters.rol;

                        // Filtro Fecha
                        const date = l.fecha_hora.split(' ')[0];
                        const matchesInicio = !this.filters.fechaInicio || date >= this.filters.fechaInicio;
                        const matchesFin = !this.filters.fechaFin || date <= this.filters.fechaFin;

                        return matchesSearch && matchesRol && matchesInicio && matchesFin;
                    });
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
                    return new Date(fh).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                },
                cargarDatos() {
                    fetch('<?= BASE_URL ?>reporte/getAuditLogsJson')
                        .then(res => res.json())
                        .then(data => {
                            this.logs = data.map(l => {
                                let parsed = null;
                                try { if(l.metadata) parsed = JSON.parse(l.metadata); } catch(e){}
                                return { ...l, parsedMetadata: parsed };
                            });
                        });
                },
                exportToExcel() {
                    // Generar CSV simple para exportar
                    const header = ["Fecha", "Hora", "Usuario", "Rol", "Modulo", "Accion", "Descripcion", "IP", "Dispositivo"];
                    const rows = this.filtrados.map(l => [
                        l.fecha_hora.split(' ')[0],
                        l.fecha_hora.split(' ')[1],
                        l.usuario_nombre,
                        l.usuario_rol,
                        l.modulo,
                        l.accion,
                        l.descripcion,
                        l.ip_address,
                        l.user_agent
                    ]);

                    let csvContent = "data:text/csv;charset=utf-8," 
                        + "\uFEFF" // Byte Order Mark para Excel en español
                        + header.join(",") + "\n" 
                        + rows.map(r => r.map(c => `"${(c || '').toString().replace(/"/g, '""')}"`).join(",")).join("\n");

                    const encodedUri = encodeURI(csvContent);
                    const link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", `auditoria_surgas_${new Date().toISOString().split('T')[0]}.csv`);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            },
            mounted() {
                this.cargarDatos();
                setInterval(this.cargarDatos, 5000);
            }
        }).mount('#app');
    </script>
</body>
</html>
