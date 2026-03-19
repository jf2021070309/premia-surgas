<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();

    echo "Añadiendo razon_social...\n";
    $db->exec("ALTER TABLE clientes ADD COLUMN razon_social VARCHAR(150) NULL AFTER nombre");

    echo "Migración completada exitosamente.\n";
} catch (Exception $e) {
    echo "Error en la migración: " . $e->getMessage() . "\n";
}
