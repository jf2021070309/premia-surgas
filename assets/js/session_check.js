(function() {
    // Verificar cada 10 segundos
    let lastStatusCheck = Date.now();
    let checkInterval = 10000; 

    function checkSession() {
        fetch(BASE_URL + 'auth/check', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = BASE_URL + 'login?error=session_expired';
            }
        })
        .catch(error => {
            console.error('Error checking session:', error);
        });
    }

    // Correr de inmediato y luego cada intervalo
    setInterval(checkSession, checkInterval);

    // También verificar al enfocar la ventana para ser más rápidos
    window.addEventListener('focus', () => {
        if (Date.now() - lastStatusCheck > 3000) { // No verificar más de una vez cada 3s
            checkSession();
            lastStatusCheck = Date.now();
        }
    });

})();
