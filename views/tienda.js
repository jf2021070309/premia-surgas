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
            modal: null,
            // Buy Points Logic
            selectedPkg: {},
            paquetes: [
                { id: 1, pts: 1000, price: 50.00 },
                { id: 2, pts: 2500, price: 120.00 },
                { id: 3, pts: 5000, price: 230.00 },
                { id: 4, pts: 10000, price: 450.00 },
                { id: 5, pts: 20000, price: 850.00 },
                { id: 6, pts: 50000, price: 2000.00 }
            ],
            modalBuy: null,
            modalPay: null,
            evidenceFile: null,
            filePreview: null,
            tienePendiente: false
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
        },
        // Buy Points Methods
        abrirCompra() {
            this.modalBuy = new bootstrap.Modal(document.getElementById('modalComprarPuntos'));
            this.modalBuy.show();
        },
        seleccionarPaquete(pkg) {
            this.selectedPkg = pkg;
        },
        irAPago() {
            this.modalBuy.hide();
            this.modalPay = new bootstrap.Modal(document.getElementById('modalPagoPuntos'));
            this.modalPay.show();
        },
        onFileSelected(event) {
            const file = event.target.files[0];
            if (file) {
                this.evidenceFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.filePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        confirmarPago() {
            if (!this.evidenceFile) return;

            Swal.fire({
                title: 'Enviando comprobante...',
                text: 'Procesando tu solicitud de recarga.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('puntos', this.selectedPkg.pts);
            formData.append('monto', this.selectedPkg.price);
            formData.append('comprobante', this.evidenceFile);

            fetch(`${BASE_URL}tienda/recargar`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'info',
                        title: '¡Recarga en espera!',
                        text: 'Tu solicitud ha sido enviada. El administrador revisará el comprobante para validar la recarga. Este proceso no es instantáneo.',
                        confirmButtonColor: '#821515'
                    }).then(() => {
                        this.modalPay.hide();
                        this.evidenceFile = null;
                        this.filePreview = null;
                        this.selectedPkg = {};
                        this.tienePendiente = true;
                    });
                } else {
                    Swal.fire('Error', data.message || 'Error al procesar la recarga', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            });
        }
    },
    mounted() {
        // Check pending recharges
        fetch(`${BASE_URL}tienda/check-pendientes`)
        .then(res => res.json())
        .then(data => {
            if (data.pendientes) this.tienePendiente = true;
        });

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
