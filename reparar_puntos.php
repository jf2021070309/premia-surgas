<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();

    // 1. Obtener todos los clientes que tienen recargas aprobadas
    $stmt = $db->query("SELECT DISTINCT cliente_id FROM recargas WHERE estado = 'aprobado'");
    $clientes = $stmt->fetchAll();

    if (empty($clientes)) {
        echo "No hay recargas aprobadas para reparar.";
        exit;
    }

    foreach ($clientes as $c) {
        $id_cliente = $c['cliente_id'];
        
        // 2. Sumar puntos reales de sus recargas (limpiando mas de forma robusta)
        $stmtSum = $db->prepare("SELECT puntos FROM recargas WHERE cliente_id = ? AND estado = 'aprobado'");
        $stmtSum->execute([$id_cliente]);
        $recargas = $stmtSum->fetchAll();
        
        $totalPuntosRecargas = 0;
        foreach ($recargas as $r) {
            $val = str_replace([',', '.'], '', $r['puntos']); // Quitar comas y puntos
            $totalPuntosRecargas += (int) $val;
        }

        // 3. Obtener puntos de ventas normales (si existen en la tabla ventas)
        $stmtVentas = $db->prepare("SELECT SUM(puntos) FROM ventas WHERE cliente_id = ?");
        $stmtVentas->execute([$id_cliente]);
        $puntosVentas = (int) $stmtVentas->fetchColumn();

        // 4. Obtener puntos usados en canjes (restar)
        $stmtCanjes = $db->prepare("SELECT SUM(puntos_usados) FROM canjes WHERE cliente_id = ? AND estado != 'rechazado'");
        $stmtCanjes->execute([$id_cliente]);
        $puntosCanjes = (int) $stmtCanjes->fetchColumn();

        $saldoCorrecto = ($totalPuntosRecargas + $puntosVentas) - $puntosCanjes;

        // 5. Actualizar el saldo final del cliente
        $db->prepare("UPDATE clientes SET puntos = ? WHERE id = ?")
           ->execute([$saldoCorrecto, $id_cliente]);

        echo "<div style='padding:10px; border-bottom:1px solid #eee;'>Cliente ID <b>$id_cliente</b>: Saldo reparado a <b>$saldoCorrecto</b> puntos.<br>";
        echo "<small>(Recargas: $totalPuntosRecargas | Ventas: $puntosVentas | Gastado: $puntosCanjes)</small></div>";
    }

    echo "<h2 style='color:#25D366; text-align:center;'>¡Reparación de Puntos Lista! ✅</h2>";
    echo "<p style='text-align:center;'><a href='" . BASE_URL . "panel'>VOLVER AL PANEL</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color:red;'>Error:</h2> " . $e->getMessage();
}
