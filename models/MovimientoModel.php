<?php
require_once __DIR__ . '/../config/Database.php';

class MovimientoModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(int $clienteId, int $conductorId, int $puntos, string $motivo): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO movimientos (cliente_id, conductor_id, puntos, motivo) 
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$clienteId, $conductorId, $puntos, $motivo]);
    }

    public function getByCliente(int $clienteId): array {
        $stmt = $this->db->prepare("SELECT * FROM movimientos WHERE cliente_id = ? ORDER BY fecha DESC");
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll();
    }
}
