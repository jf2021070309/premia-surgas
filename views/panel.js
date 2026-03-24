const { createApp } = Vue;

createApp({
    data() {
        return {
            showModal: false,
            detail: {},
            pendingTotal: 0,
            knownRecargasIds: []
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
                const res = await fetch(BASE_URL + 'panel/live-notifications');
                const data = await res.json();
                
                if (data.success) {
                    this.pendingTotal = data.total;
                    
                    const currentIds = data.recargas.map(r => r.id);
                    
                    if (!isFirstLoad) {
                        data.recargas.forEach(r => {
                            if (!this.knownRecargasIds.includes(r.id)) {
                                this.notifyNewRecarga(r);
                            }
                        });
                    }
                    
                    this.knownRecargasIds = currentIds;
                }
            } catch (err) {
                console.error('Polling failed:', err);
            }
        },
        notifyNewRecarga(recarga) {
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
                        toast.onclick = () => window.location.href = BASE_URL + 'recargas-admin';
                    }
                });
                Toast.fire({
                    icon: 'info',
                    title: '¡Nueva Recarga de Puntos!',
                    text: `${recarga.cliente_nombre} solicita +${recarga.puntos} puntos.`
                });
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
