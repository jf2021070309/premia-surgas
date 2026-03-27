const { createApp } = Vue;

createApp({
    data() {
        return {
            busqueda: '',
            filterTipo: '',
            filterDep: '',
            clientes: typeof CLIENTES !== 'undefined' ? CLIENTES : [],
            loading: false,
            fetching: false
        };
    },
    computed: {
        filtrados() {
            let filtered = this.clientes;
            
            // Filtro por tipo
            if (this.filterTipo) {
                filtered = filtered.filter(c => c.tipo_cliente === this.filterTipo);
            }
            
            // Filtro por departamento
            if (this.filterDep) {
                filtered = filtered.filter(c => c.departamento === this.filterDep);
            }
            
            // Búsqueda de texto
            const q = this.busqueda.toLowerCase();
            if (q) {
                filtered = filtered.filter(c =>
                    (c.nombre && c.nombre.toLowerCase().includes(q)) ||
                    (c.razon_social && c.razon_social.toLowerCase().includes(q)) ||
                    (c.celular && c.celular.toLowerCase().includes(q)) ||
                    (c.dni && c.dni.includes(q)) ||
                    (c.ruc && c.ruc.includes(q))
                );
            }
            
            return filtered;
        }
    },
    mounted() {
        // Inicialización extra si es necesaria
    },
    beforeUnmount() {
        // Limpieza si es necesaria
    },
    methods: {
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
                        window.location.href = typeof BASE_URL !== 'undefined' ? BASE_URL + 'logout' : '/logout';
                    }
                });
            }
        },
        toggleEstado(id, v) {
            const text = v ? '¿Activar este cliente?' : '¿Inactivar este cliente?';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Confirmar',
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--primary)',
                    confirmButtonText: 'Sí, proceder',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'clientes/estado?id=' + id + '&v=' + v;
                    }
                });
            }
        }
    }
}).mount('#app');
