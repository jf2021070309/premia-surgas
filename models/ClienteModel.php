<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/config.php';

class ClienteModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByCelular(string $celular): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE celular = ? LIMIT 1");
        $stmt->execute([$celular]);
        return $stmt->fetch() ?: null;
    }

    public function findByCodigo(string $codigo): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE codigo = ? LIMIT 1");
        $stmt->execute([$codigo]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function generarCodigo(): string {
        $stmt = $this->db->query("SELECT codigo FROM clientes ORDER BY id DESC LIMIT 1");
        $row  = $stmt->fetch();
        $num  = $row ? (intval(substr($row['codigo'], 4)) + 1) : 1;
        return 'CLI-' . str_pad($num, 6, '0', STR_PAD_LEFT);
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO clientes (codigo, nombre, celular, direccion, distrito, token, creado_por)
             VALUES (:codigo, :nombre, :celular, :direccion, :distrito, :token, :creado_por)"
        );
        $stmt->execute([
            ':codigo'     => $data['codigo'],
            ':nombre'     => $data['nombre'],
            ':celular'    => $data['celular'],
            ':direccion'  => $data['direccion'],
            ':distrito'   => $data['distrito'],
            ':token'      => $data['token'],
            ':creado_por' => $data['creado_por'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT c.*, u.nombre as conductor FROM clientes c LEFT JOIN usuarios u ON u.id = c.creado_por ORDER BY c.id DESC");
        return $stmt->fetchAll();
    }

    public function sumarPuntos(int $id, int $puntos): void {
        $this->db->prepare("UPDATE clientes SET puntos = puntos + ? WHERE id = ?")->execute([$puntos, $id]);
    }
}
