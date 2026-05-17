<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/WhatsAppService.php';

$to = $_GET['to'] ?? ($argv[1] ?? null);
$template = $_GET['template'] ?? ($argv[2] ?? 'saludo');

if (!$to) {
    if (php_sapi_name() === 'cli') {
        die("Error: Debes pasar el número como argumento. Ejemplo: php test_templates.php 51987654321 saludo\n");
    } else {
        die("Error: Debes pasar el número por URL. Ejemplo: test_templates.php?to=51987654321&template=saludo");
    }
}

echo "<h2>Prueba de Plantillas WhatsApp</h2>";
echo "Enviando plantilla '<b>$template</b>' a: <b>$to</b><br>";

// Dependiendo de la plantilla, configuramos los parámetros
$params = [];
if ($template === 'recepcion_completa') {
    // Meta indicó que esta plantilla espera 0 parámetros
    $params = []; 
} else if ($template === 'saludo') {
    // Si saludo tiene variables
    $params = [];
}

// Nota: En la imagen dice Spanish (PER), así que el código de idioma podría ser es_PE o es. 
// Vamos a probar con es_PE primero, si falla, Meta devuelve un error claro.
$language = 'es_PE'; 

$result = WhatsAppService::sendTemplate($to, $template, $params, $language);

echo "<pre>";
print_r($result);
echo "</pre>";

if ($result['success']) {
    echo "<h3 style='color:green;'>¡Éxito! El mensaje debería llegar pronto.</h3>";
} else {
    echo "<h3 style='color:red;'>Falló el envío.</h3>";
    echo "<b>Error:</b> " . ($result['error'] ?? 'Desconocido');
}
