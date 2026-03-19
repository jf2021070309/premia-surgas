const { createApp } = Vue;

createApp({
    data() {
        let prods = typeof PRODUCTOS !== 'undefined' ? PRODUCTOS : [];
        prods = prods.map(p => {
            p.image_display = p.imagen ? p.imagen : 'placeholder.png';
            return p;
        });

        return {
            productos: prods,
            cacheBuster: Date.now(),
            busqueda: '',
            filtroEstado: 'todos',
            editando: false,
            isDragging: false,
            previewUrl: null,
            form: {
                id: '',
                nombre: '',
                descripcion: '',
                puntos: 0,
                stock: 0,
                nombre_imagen: '',
                estado: 1,
                imagen_actual: ''
            }
        };
    },
    computed: {
        productosFiltrados() {
            let list = this.productos;
            if (this.filtroEstado !== 'todos') {
                list = list.filter(p => p.estado == this.filtroEstado);
            }
            if (this.busqueda) {
                const q = this.busqueda.toLowerCase();
                list = list.filter(p => p.nombre.toLowerCase().includes(q));
            }
            return list;
        }
    },
    methods: {
        nuevoProducto() {
            this.editando = false;
            this.previewUrl = null;
            this.form = {
                id: '', nombre: '', descripcion: '', puntos: 0, stock: 0, 
                nombre_imagen: '', estado: 1, imagen_actual: ''
            };
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Modal(document.getElementById('modalProducto')).show();
            }
        },
        editarProducto(p) {
            this.editando = true;
            this.previewUrl = p.imagen ? (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'assets/premios/' + p.imagen : null;
            this.form = {
                id: p.id,
                nombre: p.nombre,
                descripcion: p.descripcion,
                puntos: p.puntos,
                stock: p.stock,
                nombre_imagen: p.imagen ? p.imagen.split('.')[0] : '',
                estado: p.estado,
                imagen_actual: p.imagen
            };
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Modal(document.getElementById('modalProducto')).show();
            }
        },
        onFileChange(e) {
            const file = e.target.files[0];
            if (file) this.createPreview(file);
        },
        onDrop(e) {
            this.isDragging = false;
            const file = e.dataTransfer.files[0];
            if (file) {
                this.$refs.fileInput.files = e.dataTransfer.files;
                this.createPreview(file);
            }
        },
        createPreview(file) {
            const reader = new FileReader();
            reader.onload = (e) => { this.previewUrl = e.target.result; };
            reader.readAsDataURL(file);
        },
        onDragOver() { this.isDragging = true; },
        onDragLeave() { this.isDragging = false; },
        onImgError(e) {
            e.target.src = 'https://placehold.co/100x100?text=No+Img';
        },
        eliminarProducto(id) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Eliminar producto?',
                    text: "El producto quedará inactivo y no se mostrará en la tienda.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--primary)',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'productos/delete?id=' + id;
                    }
                });
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
