const { createApp } = Vue;

createApp({
    data() {
        return {
            form: { usuario: '', password: '' },
            error: '',
            loading: false
        };
    },
    methods: {
        async handleLogin() {
            this.error   = '';
            this.loading = true;
            try {
                if (typeof axios !== 'undefined') {
                    const res = await axios.post('login', this.form);
                    if (res.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bienvenido!',
                            text: 'Sesión iniciada correctamente',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = res.data.redirect || 'panel';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Acceso Denegado',
                            text: res.data.message
                        });
                    }
                } else {
                    const response = await fetch('login', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form)
                    });
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bienvenido!',
                            text: 'Sesión iniciada correctamente',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = data.redirect || 'panel';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Acceso Denegado',
                            text: data.message
                        });
                    }
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo establecer contacto con el servidor.'
                });
            } finally {
                this.loading = false;
            }
        }
    }
}).mount('#app');
