<?php
require_once __DIR__ . '/config/config.php';

try {
    // Usamos mysqli para multi_query que es más robusto para archivos .sql
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    if ($conn->connect_error) {
        throw new Exception("Conexión fallida: " . $conn->connect_error);
    }

    $sql = file_get_contents(__DIR__ . '/database_railway.sql');
    
    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
    }

    if ($conn->error) {
        throw new Exception("Error en SQL: " . $conn->error);
    }

    echo "<div style='font-family:sans-serif; text-align:center; padding:50px;'>";
    echo "<h1 style='color:#25D366;'>¡Base de Datos de Railway Lista! ✅</h1>";
    echo "<p>Las tablas y datos iniciales (admin/123456) han sido cargados.</p>";
    echo "<a href='" . BASE_URL . "login' style='background:#000; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;'>IR AL LOGIN</a>";
    echo "</div>";

    $conn->close();

} catch (Exception $e) {
    echo "<h1 style='color:red;'>Error de Migración</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
