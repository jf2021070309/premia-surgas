<?php
require_once __DIR__ . '/../config/Database.php';

class VentaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(int $clienteId, int $conductorId, float $monto, int $puntos): int {
        $stmt = $this->db->prepare(
            "INSERT INTO ventas (cliente_id, conductor_id, monto, puntos)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$clienteId, $conductorId, $monto, $puntos]);
        return (int) $this->db->lastInsertId();
    }

    public function getByCliente(int $clienteId): array {
        $stmt = $this->db->prepare(
            "SELECT v.*, u.nombre as conductor
             FROM ventas v
             LEFT JOIN usuarios u ON u.id = v.conductor_id
             WHERE v.cliente_id = ?
             ORDER BY v.fecha DESC"
        );
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll();
    }
}
