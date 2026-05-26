<?php
require_once __DIR__ . '/config/Database.php';

$db = Database::getConnection();

$sql = "
CREATE TABLE IF NOT EXISTS puntos_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    propietario VARCHAR(255) NOT NULL,
    latitud DECIMAL(10, 8) NOT NULL,
    longitud DECIMAL(11, 8) NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

try {
    $db->exec($sql);
    echo "Tabla 'puntos_venta' creada exitosamente.\n";
} catch (PDOException $e) {
    echo "Error creando la tabla: " . $e->getMessage() . "\n";
}
