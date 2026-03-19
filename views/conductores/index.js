const { createApp } = Vue;

createApp({
    data() {
        return {
            conductores: typeof CONDUCTORES !== 'undefined' ? CONDUCTORES : [],
            busqueda: '',
            filtroEstado: 'todos'
        };
    },
    computed: {
        conductoresFiltrados() {
            let list = this.conductores;
            if (this.filtroEstado !== 'todos') {
                list = list.filter(c => c.estado == this.filtroEstado);
            }
            if (this.busqueda) {
                const q = this.busqueda.toLowerCase();
                list = list.filter(c => 
                    c.nombre.toLowerCase().includes(q) || 
                    c.usuario.toLowerCase().includes(q)
                );
            }
            return list;
        }
    },
    methods: {
        confirmInactivar(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "El conductor ya no podrá usar el sistema hasta que sea reactivado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary)',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, inactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'conductores/delete?id=' + id;
                }
            });
        }
    }
}).mount('#app');
