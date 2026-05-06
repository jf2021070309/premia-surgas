<?php
// migrate_ganancia_v2.php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'surgas';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado a la base de datos...\n";
    
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM premios LIKE 'precio_base'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE premios ADD COLUMN precio_base DECIMAL(10,2) DEFAULT 0.00 AFTER descripcion");
        echo "Columna 'precio_base' añadida.\n";
    } else {
        echo "La columna 'precio_base' ya existe.\n";
    }
    
    // Update Laptop example for the user
    // Laptop (id 19 in database.sql)
    // Market: 2500, Profit: 500, Base: 2000, Puntos: 500
    $pdo->exec("UPDATE premios SET precio_base = 2000, puntos = 500 WHERE id = 19");
    echo "Laptop actualizada: Base 2000, Puntos 500.\n";

    echo "Migración terminada con éxito.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
