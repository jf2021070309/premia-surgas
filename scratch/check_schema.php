<?php
require_once 'config/Database.php';
$db = Database::getConnection();
$res = $db->query("DESCRIBE auditoria");
echo json_encode($res->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT);
