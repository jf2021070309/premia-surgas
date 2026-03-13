new Vue({
    el: '#app',
    data: {
        showModal: false,
        detail: {}
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
                    window.location.href = 'logout';
                }
            });
        }
    }
});
