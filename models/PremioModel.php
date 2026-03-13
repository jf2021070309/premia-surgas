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

    public function getAll(): array {
        $stmt = $this->db->prepare("SELECT * FROM premios ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM premios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO premios (nombre, descripcion, puntos, stock, imagen, estado)
             VALUES (:nombre, :descripcion, :puntos, :stock, :imagen, :estado)"
        );
        return $stmt->execute([
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':puntos'      => $data['puntos'],
            ':stock'       => $data['stock'],
            ':imagen'      => $data['imagen'],
            ':estado'      => $data['estado'] ?? 1,
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE premios 
             SET nombre = :nombre, descripcion = :descripcion, puntos = :puntos, 
                 stock = :stock, imagen = :imagen, estado = :estado
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'          => $id,
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':puntos'      => $data['puntos'],
            ':stock'       => $data['stock'],
            ':imagen'      => $data['imagen'],
            ':estado'      => $data['estado'],
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("UPDATE premios SET estado = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateStock(int $id, int $cantidad): bool {
        $stmt = $this->db->prepare("UPDATE premios SET stock = stock + ? WHERE id = ?");
        return $stmt->execute([$cantidad, $id]);
    }
}
