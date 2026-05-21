<?php
require_once __DIR__ . '/../config/Database.php';

class PuntoVentaModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM puntos_venta ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM puntos_venta WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nombre, $latitud, $longitud, $foto): bool {
        $stmt = $this->db->prepare("INSERT INTO puntos_venta (nombre, latitud, longitud, foto) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $latitud, $longitud, $foto]);
    }

    public function delete($id): bool {
        $stmt = $this->db->prepare("DELETE FROM puntos_venta WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
