<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();

    echo "Añadiendo tipo_cliente...\n";
    $db->exec("ALTER TABLE clientes ADD COLUMN tipo_cliente ENUM('Normal', 'Restaurante', 'Punto de Venta') DEFAULT 'Normal' AFTER nombre");

    echo "Añadiendo ruc...\n";
    $db->exec("ALTER TABLE clientes ADD COLUMN ruc VARCHAR(15) NULL UNIQUE AFTER tipo_cliente");

    echo "Migración completada exitosamente.\n";
} catch (Exception $e) {
    echo "Error en la migración: " . $e->getMessage() . "\n";
}
