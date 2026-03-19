const { createApp } = Vue;

createApp({
    data() {
        return {
            showModal: false,
            detail: {}
        };
    },
    methods: {
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
