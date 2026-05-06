const { createApp } = Vue;

createApp({
    data() {
        return {
            showModal: false,
            detail: {},
            pendingTotal: 0,
            knownRecargasIds: [],
            knownVentasIds: []
        };
    },
    mounted() {
        if (typeof BASE_URL !== 'undefined') {
            this.checkLiveNotifications(true); // first check without alert
            setInterval(() => this.checkLiveNotifications(false), 10000); // 10 seconds
        }
    },
    methods: {
        async checkLiveNotifications(isFirstLoad) {
            try {
                const res = await fetch(BASE_URL + 'panel/live-notifications?_t=' + new Date().getTime(), {
                    cache: 'no-store',
                    credentials: 'same-origin'
                });
                const data = await res.json();
                
                if (data.success) {
                    this.pendingTotal = data.total;
                    
                    const currentRecargasIds = data.recargas.map(r => r.id);
                    const currentVentasIds = data.ventas.map(v => v.id);
                    
                    if (!isFirstLoad) {
                        data.recargas.forEach(r => {
                            if (!this.knownRecargasIds.includes(r.id)) {
                                this.notifyNewEvent('¡Nueva Recarga de Puntos!', `${r.cliente_nombre} solicita +${r.puntos} puntos.`, 'recargas-admin');
                            }
                        });
                        data.ventas.forEach(v => {
                            if (!this.knownVentasIds.includes(v.id)) {
                                this.notifyNewEvent('¡Puntos de Conductor Pendientes!', `${v.conductor_nombre} asignó +${v.puntos} a ${v.cliente_nombre}.`, '');
                            }
                        });
                    }
                    
                    this.knownRecargasIds = currentRecargasIds;
                    this.knownVentasIds = currentVentasIds;
                }
            } catch (err) {
                console.error('Polling failed:', err);
            }
        },
        notifyNewEvent(title, text, route) {
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                        if (route) toast.onclick = () => window.location.href = BASE_URL + route;
                    }
                });
                Toast.fire({
                    icon: 'info',
                    title: title,
                    text: text
                });
            }
        },
        async validarVenta(id, estado) {
            const label = estado === 'aprobado' ? 'aprobar' : 'rechazar';
            const result = await Swal.fire({
                title: `¿Estás seguro?`,
                text: `Vas a ${label} este movimiento de puntos.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: estado === 'aprobado' ? '#22c55e' : '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Sí, ${label}`,
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const res = await fetch(BASE_URL + 'panel/validar-venta', {
                        method: 'POST',
                        body: JSON.stringify({ id, estado }),
                        headers: { 'Content-Type': 'application/json' }
                    });
                    const data = await res.json();
                    if (data.success) {
                        Swal.fire('Éxito', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Hubo un problema al procesar la solicitud.', 'error');
                }
            }
        },
        verDetalle(notif) {

            this.detail = notif;
            this.showModal = true;
        },
        goScan() {
            const codigo = prompt('Ingresa la URL del QR o el código del cliente:');
            if (codigo) {
                window.location.href = 'scan?c=' + encodeURIComponent(codigo);
            }
        },
        logout() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Cerrar sesión?',
                    text: "Esperamos verte pronto de nuevo.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--primary)',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, salir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'logout';
                    }
                });
            }
        }
    }
}).mount('#app');
