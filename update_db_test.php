<?php
require 'config/Database.php';
$conn = Database::getConnection();
$stmt = $conn->query("UPDATE tipos_operaciones SET precio_estandar = 54.00, descuento = 2.00, puntos = 32 WHERE nombre LIKE '%Recarga gas%'");
echo "Updated!";
