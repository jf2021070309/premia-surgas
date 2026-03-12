new Vue({
    el: '#app',
    methods: {
        goScan() {
            const codigo = prompt('Ingresa la URL del QR o el código del cliente:');
            if (codigo) {
                window.location.href = 'scan?c=' + encodeURIComponent(codigo);
            }
        }
    }
});
