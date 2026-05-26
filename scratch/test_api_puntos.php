<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/PuntoVentaModel.php';

header('Content-Type: text/plain; charset=utf-8');

echo "--- Iniciando Test de Integración con API Puntos de Venta (Node.js) ---\n\n";

$model = new PuntoVentaModel();

echo "Probando getAll()...\n";
$puntos = $model->getAll();

if (!is_array($puntos)) {
    echo "❌ Error: getAll() no retornó un array. Respuesta inválida de la API.\n";
} else {
    echo "✅ Éxito: getAll() retornó " . count($puntos) . " puntos de venta.\n";
    if (count($puntos) > 0) {
        echo "Primer punto de venta de ejemplo:\n";
        print_r($puntos[0]);
    } else {
        echo "Advertencia: La tabla está vacía en la base de datos.\n";
    }
}

echo "\n--- Fin del Test ---\n";
