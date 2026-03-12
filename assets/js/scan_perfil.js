new Vue({
    el: '#app',
    data: {
        ventas:       VENTAS,
        monto:        '',
        loading:      false,
        ventaOk:      false,
        ventaError:   '',
        puntosGanados: 0
    },
    methods: {
        async registrarVenta() {
            this.ventaError = '';
            this.loading    = true;
            try {
                const res = await axios.post(BASE_URL + 'scan/venta', {
                    cliente_id: CLIENTE_ID,
                    monto:      this.monto
                });
                if (res.data.success) {
                    this.puntosGanados = res.data.puntos_sumados;
                    this.ventaOk       = true;
                    // Recargar para actualizar stats
                    setTimeout(() => window.location.reload(), 2500);
                } else {
                    this.ventaError = res.data.message;
                }
            } catch (e) {
                this.ventaError = 'Error de conexión.';
            } finally {
                this.loading = false;
            }
        },
        formatDate(raw) {
            const d = new Date(raw.replace(' ', 'T'));
            return d.toLocaleDateString('es-PE', { day: '2-digit', month: 'short', year: 'numeric' });
        }
    }
});
