const { createApp } = Vue;

createApp({
    data() {
        return {
            saldo: typeof CLIENTE_PUNTOS !== 'undefined' ? CLIENTE_PUNTOS : 0,
            animatedSaldo: 0,
            montoPorPunto: typeof MONTO_POR_PUNTO !== 'undefined' ? MONTO_POR_PUNTO : 0.05,
            selected: {},
            tipo: 'total',
            pct: 50,
            modal: null
        };
    },
    computed: {
        saldoInsuficiente() {
            return this.saldo < this.selected.puntos;
        },
        maxSliderPct() {
            if (!this.selected.puntos) return 0;
            const pctSaldo = (this.saldo / this.selected.puntos) * 100;
            return Math.min(90, Math.floor(pctSaldo));
        },
        puntosDcto() {
            if (!this.selected.puntos) return 0;
            let pts = Math.round(this.selected.puntos * (this.pct / 100));
            return Math.min(pts, this.saldo);
        },
        montoEfectivo() {
            if (!this.selected.puntos) return 0;
            const puntosRestantes = this.selected.puntos - this.puntosDcto;
            return (puntosRestantes * this.montoPorPunto).toFixed(2);
        }
    },
    methods: {
        abrirCanje(prize) {
            this.selected = prize;
            this.tipo = this.saldo < prize.puntos ? 'descuento' : 'total';
            
            this.$nextTick(() => {
                this.pct = this.maxSliderPct;
            });

            if (typeof bootstrap !== 'undefined') {
                this.modal = new bootstrap.Modal(document.getElementById('modalCanje'));
                this.modal.show();
            }
        }
    },
    mounted() {
        if (this.saldo < this.selected.puntos) this.tipo = 'descuento';
        
        let target = this.saldo;
        if (target > 0) {
            let step = Math.ceil(target / 50);
            let timer = setInterval(() => {
                if (this.animatedSaldo + step >= target) {
                    this.animatedSaldo = target;
                    clearInterval(timer);
                } else {
                    this.animatedSaldo += step;
                }
            }, 20);
        }
    }
}).mount('#app');
