<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/config.php';

class ClienteModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public static function generarToken(): string {
        $aleatorio = bin2hex(random_bytes(32));
        $timestamp = microtime(true);
        return hash_hmac('sha256', $aleatorio . $timestamp, SECRET_KEY);
    }

    public function buscarPorToken(string $token): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE token = ? LIMIT 1");
        $stmt->execute([$token]);
        return $stmt->fetch() ?: null;
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

    public function loginCliente(string $identificador, string $password): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE (dni = ? OR ruc = ?) AND estado = 1 LIMIT 1");
        $stmt->execute([$identificador, $identificador]);
        $cliente = $stmt->fetch() ?: null;
        
        if ($cliente) {
            if ($this->verificarLogin($password, $cliente)) {
                return $this->findById($cliente['id']);
            }
        }
        return null;
    }

    public function verificarLogin(string $input, array $cliente): bool {
        $hash = $cliente['password'] ?? '';

        // Detectar hash SHA256 viejo (64 hex chars, sin $2y$)
        $es_sha256_viejo = (strlen($hash) === 64 && ctype_xdigit($hash));

        if ($es_sha256_viejo) {
            $sha_viejo = hash('sha256', $input);
            if (hash_equals($sha_viejo, $hash)) {
                // Correcto — migrar a bcrypt ahora mismo
                $nuevo_hash = password_hash($input, PASSWORD_BCRYPT, ['cost' => 12]);
                $this->actualizarPassword($cliente['id'], $nuevo_hash);
                return true;
            }
            return false;
        }

        // Ya es bcrypt
        return password_verify($input, $hash);
    }

    public function actualizarPassword(int $id, string $hash): void {
        $stmt = $this->db->prepare("UPDATE clientes SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $id]);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->db->prepare("UPDATE clientes SET password = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function sumarPuntos(int $id, int $puntos): void {
        $this->db->prepare("UPDATE clientes SET puntos = puntos + ? WHERE id = ?")->execute([$puntos, $id]);
    }

    public function updateBasicInfo(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE clientes SET celular = :celular, direccion = :direccion WHERE id = :id");
        return $stmt->execute([
            ':id'        => $id,
            ':celular'   => $data['celular'],
            ':direccion' => $data['direccion']
        ]);
    }

    public function updateSessionId(int $id, ?string $sessionId): bool {
        $stmt = $this->db->prepare("UPDATE clientes SET session_id = ? WHERE id = ?");
        return $stmt->execute([$sessionId, $id]);
    }
}
