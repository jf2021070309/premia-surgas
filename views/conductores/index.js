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
                title: '¿Eliminar conductor?',
                text: "El conductor será borrado permanentemente y no podrá acceder al sistema.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#800000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'conductores/delete?id=' + id;
                }
            });
        }
    }
}).mount('#app');
