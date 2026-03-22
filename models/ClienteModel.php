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

    public function findByDni(string $dni): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE dni = ? LIMIT 1");
        $stmt->execute([$dni]);
        return $stmt->fetch() ?: null;
    }

    public function findByRuc(string $ruc): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE ruc = ? LIMIT 1");
        $stmt->execute([$ruc]);
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
            "INSERT INTO clientes (codigo, dni, nombre, razon_social, tipo_cliente, ruc, celular, direccion, departamento, token, password, creado_por)
             VALUES (:codigo, :dni, :nombre, :razon_social, :tipo_cliente, :ruc, :celular, :direccion, :departamento, :token, :password, :creado_por)"
        );
        $stmt->execute([
            ':codigo'       => $data['codigo'],
            ':dni'          => $data['dni'] ?? null,
            ':nombre'       => $data['nombre'],
            ':razon_social' => $data['razon_social'] ?? null,
            ':tipo_cliente' => $data['tipo_cliente'],
            ':ruc'          => $data['ruc'] ?? null,
            ':celular'      => $data['celular'],
            ':direccion'    => $data['direccion'],
            ':departamento' => $data['departamento'],
            ':token'        => $data['token'],
            ':password'     => $data['password'] ?? null,
            ':creado_por'   => $data['creado_por'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE clientes 
             SET dni = :dni, nombre = :nombre, razon_social = :razon_social, tipo_cliente = :tipo_cliente, ruc = :ruc, celular = :celular, direccion = :direccion, departamento = :departamento, estado = :estado
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'           => $id,
            ':dni'          => $data['dni'] ?? null,
            ':nombre'       => $data['nombre'],
            ':razon_social' => $data['razon_social'] ?? null,
            ':tipo_cliente' => $data['tipo_cliente'],
            ':ruc'          => $data['ruc'] ?? null,
            ':celular'      => $data['celular'],
            ':direccion'    => $data['direccion'],
            ':departamento' => $data['departamento'],
            ':estado'       => $data['estado'],
        ]);
    }

    public function setEstado(int $id, int $estado): bool {
        $stmt = $this->db->prepare("UPDATE clientes SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT c.*, u.nombre as conductor FROM clientes c LEFT JOIN usuarios u ON u.id = c.creado_por ORDER BY c.id DESC");
        return $stmt->fetchAll();
    }

    public function loginCliente(string $dni, string $password): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE dni = ? AND password = ? LIMIT 1");
        $stmt->execute([$dni, hash('sha256', $password)]);
        return $stmt->fetch() ?: null;
    }

    public function sumarPuntos(int $id, int $puntos): void {
        $this->db->prepare("UPDATE clientes SET puntos = puntos + ? WHERE id = ?")->execute([$puntos, $id]);
    }

    public function updateSessionId(int $id, ?string $sessionId): bool {
        $stmt = $this->db->prepare("UPDATE clientes SET session_id = ? WHERE id = ?");
        return $stmt->execute([$sessionId, $id]);
    }
}
