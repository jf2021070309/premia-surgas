<?php
require_once __DIR__ . '/config/Database.php';
$db = Database::getConnection();
$stmt = $db->query("DESCRIBE canjes");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
