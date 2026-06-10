<?php
require_once __DIR__ . '/../config/Database.php';
$db = Database::getConnection();
$db->query("UPDATE tipos_operaciones SET puntos = 2000, descuento = 2.00 WHERE nombre LIKE '%Recarga gas%'");
echo "Puntos y descuento actualizados en tipos_operaciones!";
