const { createApp } = Vue;

createApp({
    data() {
        return {
            busqueda: '',
            filterTipo: '',
            filterDep: '',
            clientes: typeof CLIENTES !== 'undefined' ? CLIENTES : [],
            loading: false,
            fetching: false,
            // Modales
            showEditModal: false,
            showCarnetModal: false,
            currentCliente: null,
            form: {
                id: '',
                nombre: '',
                tipo_cliente: 'Normal',
                dni: '',
                ruc: '',
                razon_social: '',
                celular: '',
                departamento: '',
                direccion: ''
            }
        };
    },
    computed: {
        filtrados() {
            let filtered = this.clientes;
            
            // Filtro por tipo
            if (this.filterTipo) {
                filtered = filtered.filter(c => c.tipo_cliente === this.filterTipo);
            }
            
            // Filtro por departamento
            if (this.filterDep) {
                filtered = filtered.filter(c => c.departamento === this.filterDep);
            }
            
            // Búsqueda de texto
            const q = this.busqueda.toLowerCase();
            if (q) {
                filtered = filtered.filter(c =>
                    (c.nombre && c.nombre.toLowerCase().includes(q)) ||
                    (c.razon_social && c.razon_social.toLowerCase().includes(q)) ||
                    (c.celular && c.celular.toLowerCase().includes(q)) ||
                    (c.dni && c.dni.includes(q)) ||
                    (c.ruc && c.ruc.includes(q))
                );
            }
            
            return filtered;
        }
    },
    mounted() {
        // Inicialización extra si es necesaria
    },
    beforeUnmount() {
        // Limpieza si es necesaria
    },
    methods: {
        abrirCarnet(c) {
            this.currentCliente = c;
            this.showCarnetModal = true;
            this.$nextTick(() => {
                const qrContainer = document.getElementById('qrcode-modal');
                qrContainer.innerHTML = '';
                new QRCode(qrContainer, {
                    text: `${BASE_URL}scan?c=${encodeURIComponent(c.codigo)}&t=${encodeURIComponent(c.token)}`,
                    width: 200,
                    height: 200,
                    colorDark: "#0f172a",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            });
        },
        async descargarTarjeta(c) {
            // Preparar plantilla oculta
            document.getElementById('capture-name').innerText = c.nombre;
            document.getElementById('capture-doc').innerText = (c.tipo_cliente === 'Normal' ? 'DNI' : 'RUC') + ': ' + (c.tipo_cliente === 'Normal' ? c.dni : c.ruc);
            
            const qrCapture = document.getElementById('qrcode-capture');
            qrCapture.innerHTML = '';
            new QRCode(qrCapture, {
                text: `${BASE_URL}scan?c=${encodeURIComponent(c.codigo)}&t=${encodeURIComponent(c.token)}`,
                width: 280,
                height: 280,
                colorDark: "#0f172a",
                colorLight: "#f8fafc",
                correctLevel: QRCode.CorrectLevel.H
            });

            // Pequeña pausa para que el QR se renderice
            await new Promise(r => setTimeout(r, 100));

            const template = document.getElementById('card-capture-template');
            
            try {
                Swal.fire({
                    title: 'Generando Imagen',
                    text: 'Espera un momento...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const canvas = await html2canvas(template, {
                    scale: 3,
                    backgroundColor: '#ffffff',
                    logging: false,
                    useCORS: true
                });

                const link = document.createElement('a');
                link.download = `Tarjeta_Surgas_${c.nombre.replace(/ /g, '_')}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
                
                Swal.close();
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'No se pudo generar la imagen', 'error');
            }
        },
        abrirEditar(c) {
            this.currentCliente = c;
            // Clonar datos al form
            this.form.id = c.id;
            this.form.nombre = c.nombre;
            this.form.tipo_cliente = c.tipo_cliente || 'Normal';
            this.form.dni = c.dni || '';
            this.form.ruc = c.ruc || '';
            this.form.razon_social = c.razon_social || '';
            this.form.celular = c.celular || '';
            this.form.departamento = c.departamento || '';
            this.form.direccion = c.direccion || '';
            
            this.showEditModal = true;
        },
        async guardarCambios() {
            if (this.fetching) return;
            this.fetching = true;
            
            try {
                const formData = new FormData();
                for (const key in this.form) {
                    formData.append(key, this.form[key]);
                }
                
                const response = await fetch(`${BASE_URL}clientes/update`, {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: 'Los datos se guardaron correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    this.showEditModal = false;
                    // Recargar página para ver cambios (o actualizar el array local)
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    throw new Error('Error al actualizar');
                }
            } catch (err) {
                Swal.fire('Error', 'No se pudo guardar la información.', 'error');
            } finally {
                this.fetching = false;
            }
        },
        toggleEstado(id, v) {
            const text = v ? '¿Activar este cliente?' : '¿Inactivar este cliente?';
            Swal.fire({
                title: 'Confirmar',
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--primary)',
                confirmButtonText: 'Sí, proceder',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `${BASE_URL}clientes/estado?id=${id}&v=${v}`;
                }
            });
        },
        async consultarDni() {
            const dni = this.form.dni;
            if (dni.length !== 8) return;
            this.fetching = true;
            try {
                const res = await fetch(`${BASE_URL}clientes/consultarDni?dni=${dni}`);
                const r = await res.json();
                if (r.success) {
                    this.form.nombre = r.data.nombre_completo;
                }
            } catch (e) { console.error(e); }
            finally { this.fetching = false; }
        },
        async consultarRuc() {
            const ruc = this.form.ruc;
            if (ruc.length !== 11) return;
            this.fetching = true;
            try {
                const res = await fetch(`${BASE_URL}clientes/consultarRuc?ruc=${ruc}`);
                const r = await res.json();
                if (r.success) {
                    this.form.razon_social = r.data.razon_social;
                    if (r.data.direccion) this.form.direccion = r.data.direccion;
                }
            } catch (e) { console.error(e); }
            finally { this.fetching = false; }
        },
        async convertirAfiliado(c) {
            const nombre = c.razon_social || c.nombre;
            const doc = c.ruc || c.dni;
            
            const { isConfirmed } = await Swal.fire({
                title: '¿Promover a Afiliado?',
                html: `Vas a crear una cuenta de Afiliado para:<br><b>${nombre}</b><br><br>El usuario y contraseña inicial serán su documento: <b>${doc}</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                confirmButtonText: 'Sí, promover',
                cancelButtonText: 'Cancelar'
            });

            if (isConfirmed) {
                try {
                    Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                    
                    const response = await fetch(`${BASE_URL}clientes/promover-afiliado`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: c.id })
                    });
                    
                    const res = await response.json();
                    if (res.success) {
                        Swal.fire('¡Éxito!', res.message, 'success');
                    } else {
                        Swal.fire('Atención', res.message, 'info');
                    }
                } catch (err) {
                    Swal.fire('Error', 'No se pudo completar la operación.', 'error');
                }
            }
        }
    }
}).mount('#app');
