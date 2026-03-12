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
                    window.location.href = 'panel';
                } else {
                    this.error = res.data.message;
                }
            } catch (e) {
                this.error = 'Error de conexión con el servidor.';
            } finally {
                this.loading = false;
            }
        }
    }
});
