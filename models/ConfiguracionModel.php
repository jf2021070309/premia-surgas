<?php
require_once __DIR__ . '/../config/Database.php';

class ConfiguracionModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM configuraciones ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getValor(string $clave): ?string {
        $stmt = $this->db->prepare("SELECT valor FROM configuraciones WHERE clave = ?");
        $stmt->execute([$clave]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['valor'] : null;
    }

    public function update(string $clave, string $valor): bool {
        $stmt = $this->db->prepare("UPDATE configuraciones SET valor = ? WHERE clave = ?");
        return $stmt->execute([$valor, $clave]);
    }

    public function updateById(int $id, string $valor): bool {
        $stmt = $this->db->prepare("UPDATE configuraciones SET valor = ? WHERE id = ?");
        return $stmt->execute([$valor, $id]);
    }

    public function upsert(string $clave, string $valor, string $descripcion = ''): bool {
        $stmt = $this->db->prepare("INSERT INTO configuraciones (clave, valor, descripcion) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE valor = ?");
        return $stmt->execute([$clave, $valor, $descripcion, $valor]);
    }
}
