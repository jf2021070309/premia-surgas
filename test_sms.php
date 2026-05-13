<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/SmsService.php';

/**
 * Script de prueba para SMS Gateway (Android)
 * Ejecuta este archivo desde tu navegador: localhost/premia-surgas/test_sms.php?to=TU_NUMERO
 */

$to = $_GET['to'] ?? null;

if (!$to) {
    die("Error: Debes pasar el número por URL. Ejemplo: test_sms.php?to=987654321");
}

echo "<h2>Prueba de SMS Gateway (Android)</h2>";
echo "Enviando mensaje de prueba a: <b>$to</b><br>";
echo "URL del Gateway: <b>" . SMS_GATEWAY_URL . "</b><br>";

$msg = "Prueba de Premia Surgas: Tu tricitracatelas es " . rand(1000, 9999);
$result = SmsService::send($to, $msg);

echo "<pre>";
print_r($result);
echo "</pre>";

if ($result['success']) {
    echo "<h3 style='color:green;'>¡Éxito! El servidor gateway aceptó la petición.</h3>";
    echo "Verifica tu celular Android para confirmar el envío.";
} else {
    echo "<h3 style='color:red;'>Falló la conexión con el Gateway.</h3>";
    echo "<b>Error:</b> " . ($result['error'] ?? 'Desconocido');
    echo "<br><br><b>Sugerencias:</b><br>";
    echo "1. Asegúrate de que el celular y la PC estén en la misma red WiFi.<br>";
    echo "2. Verifica que la IP en config/config.php sea la correcta.<br>";
    echo "3. Verifica que la App de Gateway esté abierta y escuchando.";
}
