<?php
// migrate_pv_evidencia.php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/ConfiguracionModel.php';

try {
    $db = Database::getConnection();

    echo "=== Iniciando Migración para Flujo Punto de Venta & Evidencia ===\n";

    // 1. Columnas en tabla `ventas`
    $columnas = [
        'balones_cantidad' => "INT NULL DEFAULT NULL AFTER puntos",
        'balones_verificados' => "INT NULL DEFAULT NULL AFTER balones_cantidad",
        'evidencia_foto' => "VARCHAR(255) NULL DEFAULT NULL AFTER balones_verificados",
        'notificado_admin' => "TINYINT(1) NOT NULL DEFAULT 0 AFTER evidencia_foto",
        'fecha_aprobacion' => "DATETIME NULL DEFAULT NULL AFTER fecha"
    ];

    foreach ($columnas as $columna => $definicion) {
        $check = $db->query("SHOW COLUMNS FROM ventas LIKE '$columna'");
        if ($check->rowCount() === 0) {
            $db->exec("ALTER TABLE ventas ADD COLUMN $columna $definicion");
            echo " [OK] Columna '$columna' agregada a tabla ventas.\n";
        } else {
            echo " [INFO] Columna '$columna' ya existe en ventas.\n";
        }
    }

    // 2. Configuración en tabla `configuraciones`
    $configModel = new ConfiguracionModel();
    $puntosBalon = $configModel->getValor('puntos_por_balon_10kg');
    if ($puntosBalon === null) {
        $configModel->upsert('puntos_por_balon_10kg', '10', 'Puntos otorgados a Puntos de Venta por cada balón de 10kg entregado');
        echo " [OK] Configuración 'puntos_por_balon_10kg' creada con valor por defecto: 10.\n";
    } else {
        echo " [INFO] Configuración 'puntos_por_balon_10kg' ya existe con valor: $puntosBalon.\n";
    }

    // 3. Crear directorio de evidencias
    $uploadDir = __DIR__ . '/assets/uploads/evidencias/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        echo " [OK] Directorio '$uploadDir' creado con éxito.\n";
    } else {
        echo " [INFO] Directorio de evidencias ya existe.\n";
    }

    echo "=== Migración completada exitosamente ===\n";

} catch (Exception $e) {
    echo " [ERROR] Falló la migración: " . $e->getMessage() . "\n";
    exit(1);
}
