<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();

    echo "Creando tabla pedidos si no existe...\n";
    
    $query = "CREATE TABLE IF NOT EXISTS `pedidos` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `cliente_id` int(11) NOT NULL,
      `modalidad` varchar(50) NOT NULL,
      `producto` varchar(50) DEFAULT NULL,
      `cantidad` int(11) DEFAULT NULL,
      `direccion` text DEFAULT NULL,
      `latitud` decimal(10, 8) DEFAULT NULL,
      `longitud` decimal(11, 8) DEFAULT NULL,
      `punto_venta_id` int(11) DEFAULT NULL,
      `estado` enum('pendiente','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
      `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      CONSTRAINT `fk_pedidos_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    $db->exec($query);
    echo "Tabla pedidos creada/verificada correctamente.\n";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
