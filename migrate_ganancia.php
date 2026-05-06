<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();
    
    // Add precio_base if it doesn't exist
    $db->exec("ALTER TABLE premios ADD COLUMN IF NOT EXISTS precio_base DECIMAL(10,2) DEFAULT 0.00 AFTER descripcion");
    
    // Update existing prizes logic
    // For existing prizes, we'll assume precio_base is 0 and puntos is the total points cost
    // This maintains backward compatibility for now.
    
    echo "Migration successful: precio_base column added to premios table.\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
