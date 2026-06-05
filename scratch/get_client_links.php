<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/config.php';

$db = Database::getConnection();
$stmt = $db->query("SELECT codigo, token, nombre FROM clientes LIMIT 5");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($clientes as $c) {
    echo "Cliente: " . $c['nombre'] . "\n";
    echo "URL: " . BASE_URL . "scan?c=" . urlencode($c['codigo']) . "&t=" . urlencode($c['token']) . "\n\n";
}
