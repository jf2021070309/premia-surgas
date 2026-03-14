new Vue({
    el: '#app',
    data: {
        busqueda: '',
        clientes: CLIENTES
    },
    computed: {
        filtrados() {
            const q = this.busqueda.toLowerCase();
            if (!q) return this.clientes;
            return this.clientes.filter(c =>
                c.nombre.toLowerCase().includes(q) ||
                c.celular.toLowerCase().includes(q) ||
                c.codigo.toLowerCase().includes(q)
            );
        }
    },
    methods: {
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
                    window.location.href = BASE_URL + 'logout';
                }
            });
        }
    }
});
