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
    }
});
