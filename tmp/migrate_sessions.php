<?php
require_once 'config/Database.php';

try {
    $db = Database::getConnection();
    
    // Add columns if not exist
    try {
        $db->exec("ALTER TABLE clientes ADD COLUMN session_id VARCHAR(255) NULL");
        echo "Column session_id added to clientes\n";
    } catch (PDOException $e) {
        echo "Column session_id might already exist in clientes or failed: " . $e->getMessage() . "\n";
    }

    try {
        $db->exec("ALTER TABLE usuarios ADD COLUMN session_id VARCHAR(255) NULL");
        echo "Column session_id added to usuarios\n";
    } catch (PDOException $e) {
        echo "Column session_id might already exist in usuarios or failed: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
