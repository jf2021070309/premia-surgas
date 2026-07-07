<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Aplicando parche a Base de Datos...</h1>";

    // 1. Agregar referido_por_id a clientes si no existe
    try {
        $db->exec("ALTER TABLE clientes ADD COLUMN referido_por_id INT(11) NULL DEFAULT NULL AFTER creado_por");
        $db->exec("ALTER TABLE clientes ADD CONSTRAINT fk_cliente_referido FOREIGN KEY (referido_por_id) REFERENCES clientes(id) ON DELETE SET NULL");
        echo "<p>✅ Columna <b>referido_por_id</b> agregada a la tabla clientes.</p>";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') { // 42S21: Duplicate column name
            echo "<p>ℹ️ La columna <b>referido_por_id</b> ya existe en la tabla clientes.</p>";
        } else {
            echo "<p>❌ Error al agregar referido_por_id: " . $e->getMessage() . "</p>";
        }
    }

    // 2. Crear tabla sms_queue si no existe
    $sqlSms = "CREATE TABLE IF NOT EXISTS `sms_queue` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `celular` varchar(20) NOT NULL,
        `mensaje` text NOT NULL,
        `estado` enum('pendiente','enviado','error') DEFAULT 'pendiente',
        `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $db->exec($sqlSms);
    echo "<p>✅ Tabla <b>sms_queue</b> verificada/creada.</p>";

    echo "<h3>¡Migración completada con éxito! Ya puedes probar el registro.</h3>";

} catch (Exception $e) {
    echo "<h3>❌ Error fatal en la migración:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
