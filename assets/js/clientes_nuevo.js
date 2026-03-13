new Vue({
    el: '#app',
    data: {
        form: { nombre: '', celular: '', direccion: '', distrito: '' },
        loading:        false,
        error:          '',
        clienteGuardado: false,
        codigoGenerado: '',
        clienteId:      null,
        esExistente:    false,
        mensaje:        ''
    },
    methods: {
        validateName(e) {
            this.form.nombre = e.target.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        },
        validatePhone(e) {
            this.form.celular = e.target.value.replace(/\D/g, '').slice(0, 9);
        },
        async guardar() {
            this.error   = '';
            this.loading = true;
            try {
                const res = await axios.post(BASE_URL + 'clientes/create', this.form);
                if (res.data.success) {
                    this.codigoGenerado  = res.data.codigo;
                    this.clienteId       = res.data.id;
                    this.esExistente     = !!res.data.existing;
                    this.mensaje         = res.data.message || '';
                    this.clienteGuardado = true;
                } else {
                    this.error = res.data.message;
                }
            } catch (e) {
                this.error = 'Error al conectar con el servidor.';
            } finally {
                this.loading = false;
            }
        }
    }
});
