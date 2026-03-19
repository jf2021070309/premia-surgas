const { createApp } = Vue;

createApp({
    data() {
        return {
            form: { tipo_cliente: 'Normal', dni: '', ruc: '', razon_social: '', nombre: '', celular: '', direccion: '' },
            loading: false,
            error: '',
            clienteGuardado: false,
            codigoGenerado: '',
            clienteId: null,
            esExistente: false,
            mensaje: '',
            buscandoDni: false,
            buscandoRuc: false
        };
    },
    methods: {
        onChangeTipo() {
            if (this.form.tipo_cliente === 'Normal') {
                this.form.ruc = '';
                this.form.razon_social = '';
            } else {
                this.form.dni = '';
            }
        },
        validateDni(e) {
            this.form.dni = e.target.value.replace(/\D/g, '').slice(0, 8);
            if (this.form.tipo_cliente === 'Normal' && this.form.dni.length === 8) {
                this.buscarDni();
            }
        },
        async buscarDni() {
            if (this.buscandoDni) return;
            this.buscandoDni = true;
            try {
                const res = await fetch(BASE_URL + 'clientes/consultarDni?dni=' + this.form.dni);
                const data = await res.json();
                if (data.success && data.data.nombre_completo) {
                    this.form.nombre = data.data.nombre_completo;
                }
            } catch (error) {
                console.error("Error al buscar DNI:", error);
            } finally {
                this.buscandoDni = false;
            }
        },
        validateRuc(e) {
            this.form.ruc = e.target.value.replace(/\D/g, '').slice(0, 11);
            if (this.form.tipo_cliente !== 'Normal' && this.form.ruc.length === 11) {
                this.buscarRuc();
            }
        },
        async buscarRuc() {
            if (this.buscandoRuc) return;
            this.buscandoRuc = true;
            try {
                const res = await fetch(BASE_URL + 'clientes/consultarRuc?ruc=' + this.form.ruc);
                const data = await res.json();
                if (data.success && data.data.razon_social) {
                    this.form.razon_social = data.data.razon_social;
                    if (data.data.direccion) {
                        this.form.direccion = data.data.direccion;
                    }
                }
            } catch (error) {
                console.error("Error al buscar RUC:", error);
            } finally {
                this.buscandoRuc = false;
            }
        },
        validateName(e) {
            this.form.nombre = e.target.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        },
        validatePhone(e) {
            this.form.celular = e.target.value.replace(/\D/g, '').slice(0, 9);
        },
        async guardar() {
            this.error = '';
            this.loading = true;
            try {
                // Ensure axios is available, otherwise use fetch
                const url = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'clientes/create';

                if (typeof axios !== 'undefined') {
                    const res = await axios.post(url, this.form);
                    if (res.data.success) {
                        this.codigoGenerado = res.data.codigo;
                        this.clienteId = res.data.id;
                        this.esExistente = !!res.data.existing;
                        this.mensaje = res.data.message || '';
                        this.clienteGuardado = true;
                    } else {
                        this.error = res.data.message;
                    }
                } else {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form)
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.codigoGenerado = data.codigo;
                        this.clienteId = data.id;
                        this.esExistente = !!data.existing;
                        this.mensaje = data.message || '';
                        this.clienteGuardado = true;
                    } else {
                        this.error = data.message;
                    }
                }
            } catch (e) {
                this.error = 'Error al conectar con el servidor.';
                console.error(e);
            } finally {
                this.loading = false;
            }
        },
        logout() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Cerrar sesión?',
                    text: "Esperamos verte pronto de nuevo.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--primary)',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, salir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'logout';
                    }
                });
            }
        }
    }
}).mount('#app');
