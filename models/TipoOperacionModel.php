<?php
require_once __DIR__ . '/../config/Database.php';

class TipoOperacionModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM tipos_operaciones ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive(): array {
        $stmt = $this->db->query("SELECT * FROM tipos_operaciones WHERE estado = 1 ORDER BY puntos DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM tipos_operaciones WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("INSERT INTO tipos_operaciones (nombre, puntos, estado) VALUES (?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['puntos'],
            $data['estado'] ?? 1
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE tipos_operaciones SET nombre = ?, puntos = ?, estado = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['puntos'],
            $data['estado'],
            $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM tipos_operaciones WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
