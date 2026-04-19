const { createApp } = Vue;

createApp({
    data() {
        return {
            aliados: typeof ALIADOS !== 'undefined' ? ALIADOS : [],
            busqueda: '',
            filtroEstado: 'todos'
        };
    },
    computed: {
        aliadosFiltrados() {
            let list = this.aliados;
            if (this.filtroEstado !== 'todos') {
                list = list.filter(a => a.estado == this.filtroEstado);
            }
            if (this.busqueda) {
                const q = this.busqueda.toLowerCase();
                list = list.filter(a => 
                    a.nombre.toLowerCase().includes(q) || 
                    a.usuario.toLowerCase().includes(q)
                );
            }
            return list;
        }
    },
    methods: {
        confirmInactivar(id) {
            Swal.fire({
                title: '¿Eliminar aliado?',
                text: "El aliado será borrado permanentemente y no podrá acceder al sistema.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#800000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'aliados/delete?id=' + id;
                }
            });
        }
    }
}).mount('#app');
