new Vue({
    el: '#app',
    data: {
        form: { usuario: '', password: '' },
        error: '',
        loading: false
    },
    methods: {
        async handleLogin() {
            this.error   = '';
            this.loading = true;
            try {
                const res = await axios.post('login', this.form);
                if (res.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido!',
                        text: 'Sesión iniciada correctamente',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'panel';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Acceso Denegado',
                        text: res.data.message
                    });
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
});
