const { createApp } = Vue;

createApp({
    data() {
        return {
            afiliados: typeof AFILIADOS !== 'undefined' ? AFILIADOS : [],
            busqueda: '',
            filtroEstado: 'todos',
            showModal: false,
            showPass: false,
            loading: false,
            form: {
                id: null,
                nombre: '',
                usuario: '',
                password: '',
                departamento: '',
                estado: '1'
            }
        };
    },
    computed: {
        afiliadosFiltrados() {
            let list = this.afiliados;
            if (this.filtroEstado !== 'todos') {
                list = list.filter(a => a.estado == this.filtroEstado);
            }
            if (this.busqueda) {
                const q = this.busqueda.toLowerCase();
                list = list.filter(a => 
                    a.nombre.toLowerCase().includes(q) || 
                    a.usuario.toLowerCase().includes(q)
                );
            }
            return list;
        }
    },
    methods: {
        openModal(afiliado = null) {
            this.showPass = false;
            if (afiliado) {
                this.form = { ...afiliado, password: '' };
            } else {
                this.form = { id: null, nombre: '', usuario: '', password: '', departamento: '', estado: '1' };
            }
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        async saveAfiliado() {
            this.loading = true;
            try {
                const formData = new FormData();
                for (let key in this.form) {
                    formData.append(key, this.form[key]);
                }

                const action = this.form.id ? 'update' : 'create';
                const response = await fetch(`${BASE_URL}afiliados/${action}`, {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: this.form.id ? 'Afiliado actualizado correctamente' : 'Afiliado registrado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error('Error en la respuesta del servidor');
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
            } finally {
                this.loading = false;
            }
        },
        confirmInactivar(id) {
            Swal.fire({
                title: '¿Eliminar afiliado?',
                text: "El afiliado será borrado permanentemente y no podrá acceder al sistema.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#800000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'afiliados/delete?id=' + id;
                }
            });
        }
    }
}).mount('#app');
