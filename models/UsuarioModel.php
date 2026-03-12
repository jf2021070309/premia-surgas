<?php
require_once __DIR__ . '/../config/Database.php';

class UsuarioModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByCredentials(string $usuario, string $password): ?array {
        $stmt = $this->db->prepare(
            "SELECT id, nombre, password, rol, estado
             FROM usuarios
             WHERE usuario = ? LIMIT 1"
        );
        $stmt->execute([$usuario]);
        $row = $stmt->fetch();

        if ($row && $row['password'] === md5($password) && $row['estado'] == 1) {
            return $row;
        }
        return null;
    }
}
