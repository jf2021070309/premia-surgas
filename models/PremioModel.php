<?php
require_once __DIR__ . '/../config/Database.php';

class PremioModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllActive(): array {
        $stmt = $this->db->prepare("SELECT * FROM premios WHERE estado = 1 ORDER BY puntos ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM premios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateStock(int $id, int $cantidad): bool {
        $stmt = $this->db->prepare("UPDATE premios SET stock = stock + ? WHERE id = ?");
        return $stmt->execute([$cantidad, $id]);
    }
}
