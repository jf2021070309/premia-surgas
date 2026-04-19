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
            return Math.max(0, (puntosRestantes * this.montoPorPunto)).toFixed(2);
        }
    },
    methods: {
        abrirCanje(prize) {
            this.selected = prize;
            this.tipo = this.saldo < prize.puntos ? 'yape' : 'total';

            this.$nextTick(() => {
                this.pct = this.maxSliderPct;
            });

            if (typeof bootstrap !== 'undefined') {
                this.modal = new bootstrap.Modal(document.getElementById('modalCanje'));
                this.modal.show();
            }
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
        canjeTotalDirecto() {
            if (this.saldoInsuficiente) return;
            this.tipo = 'total';
            
            Swal.fire({
                title: '¿Confirmas tu Canje?',
                text: `Vas a canjear "${this.selected.nombre}" por ${this.selected.puntos} puntos.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#821515',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'SÍ, CANJEAR AHORA',
                cancelButtonText: 'Cancelar',
                background: '#ffffff',
                color: '#111827',
                customClass: {
                    container: 'premium-swal-container',
                    popup: 'rounded-5',
                    confirmButton: 'rounded-4 py-2 px-4 fw-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.confirmarCanje();
                }
            });
        },
        confirmarCanje() {
            const isHybrid = this.tipo !== 'total';
            const requiresReceipt = this.tipo === 'deposito';
            const pts = isHybrid ? this.puntosDcto : this.selected.puntos;
            const monto = isHybrid ? parseFloat(this.montoEfectivo) : 0;

            if (requiresReceipt && !this.evidenceFile) {
                Swal.fire('Error', 'Debes adjuntar el comprobante de pago.', 'error');
                return;
            }

            Swal.fire({
                title: isHybrid ? 'Enviando pago...' : 'Procesando canje...',
                text: 'Por favor espera un momento.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const formData = new FormData();
            formData.append('premio_id', this.selected.id);
            formData.append('puntos', pts);
            formData.append('monto', monto);
            if (isHybrid) {
                formData.append('comprobante', this.evidenceFile);
                formData.append('metodo_pago', this.tipo); // 'yape' o 'deposito'
            }

            fetch(`${BASE_URL}tienda/canjear`, {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        Swal.fire('Error', data.message || 'Error al procesar el canje', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                });
        },
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
