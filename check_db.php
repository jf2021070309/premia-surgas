<?php
require_once __DIR__ . '/config/Database.php';
try {
    $db = Database::getConnection();
    $users = $db->query("SELECT * FROM usuarios")->fetchAll();
    $log = "";
    foreach ($users as $u) {
        $exp = hash('sha256', '123456');
        $match = ($u['password'] === $exp) ? "OK" : "ERR";
        $log .= "ID:{$u['id']} | User:{$u['usuario']} | Pass:{$u['password']} | Match(123456):$match\n";
    }
    file_put_contents(__DIR__ . '/db_log.txt', $log);
} catch (Exception $e) { file_put_contents(__DIR__ . '/db_log.txt', $e->getMessage()); }
