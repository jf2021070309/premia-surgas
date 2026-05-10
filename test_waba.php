<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/WhatsAppService.php';

/**
 * Script de prueba para Meta WhatsApp Cloud API
 * Ejecuta este archivo desde tu navegador: localhost/premia-surgas/test_waba.php?to=TU_NUMERO
 */

$to = $_GET['to'] ?? null;

if (!$to) {
    die("Error: Debes pasar el número por URL. Ejemplo: test_waba.php?to=51987654321");
}

echo "<h2>Prueba de WhatsApp Cloud API</h2>";
echo "Enviando plantilla 'hello_world' a: <b>$to</b><br>";

// 'hello_world' es una plantilla que Meta aprueba automáticamente para todas las apps nuevas
$result = WhatsAppService::sendTemplate($to, 'hello_world');

echo "<pre>";
print_r($result);
echo "</pre>";

if ($result['success']) {
    echo "<h3 style='color:green;'>¡Éxito! El mensaje debería llegar pronto.</h3>";
} else {
    echo "<h3 style='color:red;'>Falló el envío.</h3>";
    echo "<b>Error:</b> " . ($result['error'] ?? 'Desconocido');
    echo "<br><br><b>Sugerencia:</b> Verifica que WABA_TOKEN y WABA_PHONE_ID en config/config.php sean correctos. Recuerda que el Phone ID NO es tu número de celular.";
}
