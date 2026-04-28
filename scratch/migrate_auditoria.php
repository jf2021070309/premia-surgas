<?php
require_once 'config/Database.php';
$db = Database::getConnection();
try {
    $db->exec("ALTER TABLE auditoria ADD COLUMN tipo_usuario ENUM('trabajador', 'cliente') DEFAULT 'trabajador' AFTER id_usuario");
    echo "Columna tipo_usuario añadida correctamente.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
