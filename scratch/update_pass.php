<?php
require_once __DIR__ . '/../config/Database.php';
$db = Database::getConnection();
$stmt = $db->prepare("UPDATE clientes SET password = ? WHERE id = 1");
$stmt->execute([hash('sha256', '72883481')]);
echo "PASSWORD UPDATE COMPLETED FOR CLI-000001\n";
