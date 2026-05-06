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
            // Se considera saldo insuficiente si no alcanza para cubrir al menos 1 punto de la ganancia
            // o si el premio cuesta puntos y no tiene nada.
            return this.saldo < 1 && this.selected.puntos > 0;
        },
        maxSliderPct() {
            if (!this.selected.puntos || this.selected.puntos == 0) return 0;
            // Porcentaje de la ganancia que el cliente puede cubrir con sus puntos
            const pctSaldo = (this.saldo / this.selected.puntos) * 100;
            return Math.min(100, Math.floor(pctSaldo));
        },
        puntosDcto() {
            if (!this.selected.puntos) return 0;
            // Puntos que el cliente decide usar según la perilla (0% a 100% de la ganancia)
            let pts = Math.round(this.selected.puntos * (this.pct / 100));
            return Math.min(pts, this.saldo);
        },
        montoEfectivo() {
            // Lógica recalibrada: Precio Base (Mayorista) + Ganancia Restante
            const precioBase = parseFloat(this.selected.precio_base || 0);
            const gananciaPuntos = parseFloat(this.selected.puntos || 0);
            const puntosUsados = this.puntosDcto;
            
            // Cada punto reduce el valor configurado (Ej: 0.10 Soles)
            const gananciaRestante = Math.max(0, (gananciaPuntos - puntosUsados) * this.montoPorPunto);
            
            return (precioBase + gananciaRestante).toFixed(2);
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
                text: `Vas a cubrir toda la ganancia con ${this.selected.puntos} puntos. Deberás pagar la base de S/ ${parseFloat(this.selected.precio_base || 0).toFixed(2)} en efectivo/depósito.`,
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
            
            // Si es 'total', usamos el 100% de los puntos del premio
            const pts = (this.tipo === 'total') ? this.selected.puntos : this.puntosDcto;
            
            // El monto siempre se calcula: precio_base + ganancia_restante
            // Si es 'total', la ganancia restante es 0, por lo que monto = precio_base
            let monto = parseFloat(this.montoEfectivo);
            if (this.tipo === 'total') {
                monto = parseFloat(this.selected.precio_base || 0);
            }

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
            if (isHybrid || monto > 0) {
                if (this.evidenceFile) formData.append('comprobante', this.evidenceFile);
                formData.append('metodo_pago', isHybrid ? this.tipo : 'yape'); 
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

        if (this.saldo < this.selected.puntos) this.tipo = 'yape';

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
