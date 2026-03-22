<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();

    echo "Modificando usuarios...\n";
    $db->exec("ALTER TABLE usuarios ADD COLUMN departamento ENUM('Tacna', 'Moquegua', 'Arequipa', 'Ilo') NULL DEFAULT NULL AFTER rol");
    echo "usuarios modificado correctamente.\n";

    echo "Modificando clientes...\n";
    // Check if distrito exists
    $cols = $db->query("SHOW COLUMNS FROM clientes LIKE 'distrito'")->fetchAll();
    if(count($cols) > 0) {
        $db->exec("ALTER TABLE clientes DROP COLUMN distrito");
        echo "Columna distrito eliminada.\n";
    }

    // Check if departamento exists
    $cols = $db->query("SHOW COLUMNS FROM clientes LIKE 'departamento'")->fetchAll();
    if(count($cols) == 0) {
        $db->exec("ALTER TABLE clientes ADD COLUMN departamento ENUM('Tacna', 'Moquegua', 'Arequipa', 'Ilo') NULL DEFAULT NULL AFTER celular");
        echo "Columna departamento añadida.\n";
    }

    // Ensure DNI
    $colsDni = $db->query("SHOW COLUMNS FROM clientes LIKE 'dni'")->fetchAll();
    if(count($colsDni) == 0) {
        $db->exec("ALTER TABLE clientes ADD COLUMN dni VARCHAR(15) NULL UNIQUE AFTER codigo");
        echo "Columna dni añadida y seteada UNIQUE.\n";
    } else {
        // Just make sure it is UNIQUE (ignoring errors if already unique)
        try {
            $db->exec("ALTER TABLE clientes ADD UNIQUE(dni)");
            echo "DNI set as UNIQUE.\n";
        } catch(Exception $e) {}
    }

    // ─── SESION ID ────────────────────────
    echo "Verificando session_id en usuarios...\n";
    $cols = $db->query("SHOW COLUMNS FROM usuarios LIKE 'session_id'")->fetchAll();
    if(count($cols) == 0) {
        $db->exec("ALTER TABLE usuarios ADD COLUMN session_id VARCHAR(255) NULL");
        echo "session_id añadido a usuarios.\n";
    }

    echo "Verificando session_id en clientes...\n";
    $cols = $db->query("SHOW COLUMNS FROM clientes LIKE 'session_id'")->fetchAll();
    if(count($cols) == 0) {
        $db->exec("ALTER TABLE clientes ADD COLUMN session_id VARCHAR(255) NULL");
        echo "session_id añadido a clientes.\n";
    }

    echo "Migración completada.\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
