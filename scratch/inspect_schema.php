<?php
require_once __DIR__ . '/../config/Database.php';
$db = Database::getConnection();

$res1 = $db->query("SHOW CREATE TABLE usuarios")->fetch();
echo "--- USUARIOS ---\n" . $res1['Create Table'] . "\n\n";

$res2 = $db->query("SHOW CREATE TABLE clientes")->fetch();
echo "--- CLIENTES ---\n" . $res2['Create Table'] . "\n\n";
