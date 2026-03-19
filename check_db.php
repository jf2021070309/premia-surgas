<?php
require_once __DIR__ . '/config/Database.php';

$db = Database::getConnection();

echo "--- Tabla usuarios ---\n";
$stmt = $db->query("DESCRIBE usuarios");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n--- Tabla clientes ---\n";
$stmt = $db->query("DESCRIBE clientes");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
