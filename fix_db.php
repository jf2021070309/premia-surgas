<?php
require_once __DIR__ . '/config/Database.php';
try {
    $db = Database::getConnection();
    $newHash = hash('sha256', '123456');
    $db->prepare("UPDATE usuarios SET password = ? WHERE usuario IN ('admin', 'conductor1')")->execute([$newHash]);
    echo "SUCCESS: Passwords updated to SHA256 hashes for 'admin' and 'conductor1'.\n";
} catch (Exception $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
