const { createApp } = Vue;

createApp({
    data() {
        return {
            loading: false
        };
    },
    methods: {
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
        },
        soloDni(e) {
            let val = e.target.value.replace(/\D/g, '').slice(0, 8);
            e.target.value = val;
            
            if (val.length === 8) {
                const tipo = document.querySelector('input[name="tipo_cliente"]:checked').value;
                if (tipo === 'Normal') {
                    this.buscarDni(val);
                }
            }
        },
        async buscarDni(dni) {
            const spinner = document.getElementById('dni-spinner');
            const inputNombre = document.getElementById('input-nombre');
            const inputDni = document.getElementById('input-dni');
            
            if (spinner) spinner.style.display = 'inline';
            if (inputDni) inputDni.disabled = true;
            
            try {
                const res = await fetch(BASE_URL + 'clientes/consultarDni?dni=' + dni);
                const data = await res.json();
                if (data.success && data.data.nombre_completo) {
                    inputNombre.value = data.data.nombre_completo;
                }
            } catch (err) {
                console.error(err);
            } finally {
                if (spinner) spinner.style.display = 'none';
                if (inputDni) inputDni.disabled = false;
                if (inputDni) inputDni.focus();
            }
        },
        soloRuc(e) {
            let val = e.target.value.replace(/\D/g, '').slice(0, 11);
            e.target.value = val;
            
            if (val.length === 11) {
                const tipo = document.querySelector('input[name="tipo_cliente"]:checked').value;
                if (tipo !== 'Normal') {
                    this.buscarRuc(val);
                }
            }
        },
        async buscarRuc(ruc) {
            const spinner = document.getElementById('ruc-spinner');
            const inputRazon = document.getElementById('input-razon-social');
            const inputRuc = document.getElementById('input-ruc');
            
            if (spinner) spinner.style.display = 'inline';
            if (inputRuc) inputRuc.disabled = true;
            
            try {
                const res = await fetch(BASE_URL + 'clientes/consultarRuc?ruc=' + ruc);
                const data = await res.json();
                if (data.success && data.data.razon_social) {
                    inputRazon.value = data.data.razon_social;
                    const inputDir = document.querySelector('input[name="direccion"]');
                    if (inputDir && data.data.direccion) {
                        inputDir.value = data.data.direccion;
                    }
                }
            } catch (err) {
                console.error(err);
            } finally {
                if (spinner) spinner.style.display = 'none';
                if (inputRuc) inputRuc.disabled = false;
                if (inputRuc) inputRuc.focus();
            }
        },
        soloLetrasOpcional(e) {
            e.target.value = e.target.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        },
        soloLetras(e) {
            e.target.value = e.target.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        },
        soloNumeros(e) {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 9);
        }
    }
}).mount('#app');
