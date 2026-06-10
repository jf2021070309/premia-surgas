<?php
require_once __DIR__ . '/../config/Database.php';
$db = Database::getConnection();
try {
    $db->query("ALTER TABLE clientes ADD COLUMN referido_por_id INT DEFAULT NULL AFTER creado_por");
    $db->query("ALTER TABLE clientes ADD CONSTRAINT fk_clientes_referido FOREIGN KEY (referido_por_id) REFERENCES clientes(id) ON DELETE SET NULL");
    echo "Column referido_por_id added to clientes!";
} catch (Exception $e) {
    echo "Error or column already exists: " . $e->getMessage();
}
