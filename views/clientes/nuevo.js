const { createApp } = Vue;

createApp({
    data() {
        return {
            form: { tipo_cliente: 'Normal', dni: '', ruc: '', razon_social: '', nombre: '', celular: '', direccion: '' },
            loading: false,
            error: '',
            buscandoDni: false,
            buscandoRuc: false
        };
    },
    methods: {
        onChangeTipo() {
            // Se eliminó la limpieza de campos para que persistan los datos si se cambia de tipo y se vuelve
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
                        if (res.data.existing) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Cliente ya registrado',
                                    text: res.data.message || 'Este cliente ya se encuentra en el sistema.',
                                    confirmButtonText: 'Ver Perfil / QR',
                                    confirmButtonColor: 'var(--primary)',
                                    background: '#fff',
                                    customClass: {
                                        popup: 'swal2-premium'
                                    }
                                }).then(() => {
                                    window.location.href = BASE_URL + 'clientes/exito?id=' + res.data.id;
                                });
                            } else {
                                alert(res.data.message || 'Cliente ya registrado.');
                                window.location.href = BASE_URL + 'clientes/exito?id=' + res.data.id;
                            }
                        } else {
                            window.location.href = BASE_URL + 'clientes/exito?id=' + res.data.id;
                        }
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
                        if (data.existing) {
                             if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Cliente ya registrado',
                                    text: data.message || 'Este cliente ya se encuentra en el sistema.',
                                    confirmButtonText: 'Ver Perfil / QR',
                                    confirmButtonColor: 'var(--primary)'
                                }).then(() => {
                                    window.location.href = BASE_URL + 'clientes/exito?id=' + data.id;
                                });
                            } else {
                                alert(data.message || 'Cliente ya registrado.');
                                window.location.href = BASE_URL + 'clientes/exito?id=' + data.id;
                            }
                        } else {
                            window.location.href = BASE_URL + 'clientes/exito?id=' + data.id;
                        }
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
