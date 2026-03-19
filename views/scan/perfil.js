const { createApp } = Vue;

createApp({
    data() {
        return {
            ventas:       typeof VENTAS !== 'undefined' ? VENTAS : [],
            monto:        '',
            loading:      false,
            ventaOk:      false,
            ventaError:   '',
            puntosGanados: 0
        };
    },
    methods: {
        async registrarVenta() {
            this.ventaError = '';
            this.loading    = true;
            try {
                const url = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'scan/venta';
                const payload = {
                    cliente_id: typeof CLIENTE_ID !== 'undefined' ? CLIENTE_ID : 0,
                    monto:      this.monto
                };

                if (typeof axios !== 'undefined') {
                    const res = await axios.post(url, payload);
                    if (res.data.success) {
                        this.puntosGanados = res.data.puntos_sumados;
                        this.ventaOk       = true;
                        setTimeout(() => window.location.reload(), 2500);
                    } else {
                        this.ventaError = res.data.message;
                    }
                } else {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.puntosGanados = data.puntos_sumados;
                        this.ventaOk       = true;
                        setTimeout(() => window.location.reload(), 2500);
                    } else {
                        this.ventaError = data.message;
                    }
                }
            } catch (e) {
                this.ventaError = 'Error de conexión.';
                console.error(e);
            } finally {
                this.loading = false;
            }
        },
        formatDate(raw) {
            const d = new Date(raw.replace(' ', 'T'));
            return d.toLocaleDateString('es-PE', { day: '2-digit', month: 'short', year: 'numeric' });
        }
    }
}).mount('#app');
