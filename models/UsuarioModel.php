<?php
require_once __DIR__ . '/../config/Database.php';

class UsuarioModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByCredentials(string $usuario, string $password): ?array {
        $stmt = $this->db->prepare(
            "SELECT id, nombre, usuario, password, rol, estado, departamento
             FROM usuarios
             WHERE usuario = ? LIMIT 1"
        );
        $stmt->execute([$usuario]);
        $row = $stmt->fetch();

        if ($row && $row['password'] === hash('sha256', $password) && $row['estado'] == 1) {
            return $row;
        }
        return null;
    }

    public function getAllConductores(): array {
        $stmt = $this->db->prepare("SELECT id, nombre, usuario, rol, estado, departamento, fecha_creacion FROM usuarios WHERE rol = 'conductor' ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, nombre, usuario, rol, estado, departamento, fecha_creacion FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nombre, usuario, password, rol, estado, departamento)
             VALUES (:nombre, :usuario, :password, :rol, :estado, :departamento)"
        );
        return $stmt->execute([
            ':nombre'       => $data['nombre'],
            ':usuario'      => $data['usuario'],
            ':password'     => hash('sha256', $data['password']),
            ':rol'          => $data['rol'] ?? 'conductor',
            ':estado'       => $data['estado'] ?? 1,
            ':departamento' => $data['departamento'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE usuarios SET nombre = :nombre, usuario = :usuario, estado = :estado, departamento = :departamento";
        $params = [
            ':id'           => $id,
            ':nombre'       => $data['nombre'],
            ':usuario'      => $data['usuario'],
            ':estado'       => $data['estado'],
            ':departamento' => $data['departamento'] ?? null,
        ];

        if (!empty($data['password'])) {
            $sql .= ", password = :password";
            $params[':password'] = hash('sha256', $data['password']);
        }

        $sql .= " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function setEstado(int $id, int $estado): bool {
        $stmt = $this->db->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }
}
