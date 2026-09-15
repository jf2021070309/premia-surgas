<?php
require_once __DIR__ . '/helpers/DetectorBalonesHelper.php';

$tests = [
    ['assets/tests/2 balones.jpeg', 2],
    ['assets/tests/3 balones.jpeg', 3],
    ['assets/tests/4 balones.jpeg', 4],
    ['assets/tests/9 balones.jpeg', 9],
];

echo "=================================================" . PHP_EOL;
echo "  PREMIASURGAS — PRUEBA LOCAL DE DETECCIÓN (PHP) " . PHP_EOL;
echo "  Microservicio: http://127.0.0.1:8001/detectar   " . PHP_EOL;
echo "=================================================" . PHP_EOL . PHP_EOL;

foreach ($tests as $t) {
    $file = $t[0];
    $declarados = $t[1];

    $t0 = microtime(true);
    $res = DetectorBalonesHelper::validar($file, $declarados);
    $tiempo = round((microtime(true) - $t0) * 1000, 1);

    $nombre = str_pad(basename($file), 16);
    $estado = $res['coincide'] ? "✅ COINCIDE" : "⚠️  DESVÍO A REVISIÓN";

    echo "📸 Imagen: {$nombre}" . PHP_EOL;
    echo "   Declarados: {$res['declarados']} | Detectados: {$res['detectados']} | Estado: {$estado}" . PHP_EOL;
    echo "   Tiempo: {$tiempo} ms | Cajas detectadas: " . count($res['cajas']) . PHP_EOL;
    if (!empty($res['cajas'])) {
        echo "   Detalle: " . json_encode(array_slice($res['cajas'], 0, 3)) . (count($res['cajas']) > 3 ? "..." : "") . PHP_EOL;
    }
    echo "-------------------------------------------------" . PHP_EOL;
}
