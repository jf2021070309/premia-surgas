<?php
// models/PedidoModel.php
require_once __DIR__ . '/../config/Database.php';

class PedidoModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO pedidos (cliente_id, modalidad, producto, cantidad, direccion, latitud, longitud, punto_venta_id, estado)
             VALUES (:cliente_id, :modalidad, :producto, :cantidad, :direccion, :latitud, :longitud, :punto_venta_id, 'pendiente')"
        );
        
        $stmt->execute([
            ':cliente_id'     => $data['cliente_id'],
            ':modalidad'      => $data['modalidad'],
            ':producto'       => $data['producto'] ?? null,
            ':cantidad'       => $data['cantidad'] ?? null,
            ':direccion'      => $data['direccion'] ?? null,
            ':latitud'        => $data['latitud'] ?? null,
            ':longitud'       => $data['longitud'] ?? null,
            ':punto_venta_id' => $data['punto_venta_id'] ?? null
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function getAll(): array {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.nombre as cliente_nombre, c.celular as cliente_celular, c.dni as cliente_dni
             FROM pedidos p
             JOIN clientes c ON p.cliente_id = c.id
             ORDER BY p.fecha_creacion DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCliente(int $clienteId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM pedidos WHERE cliente_id = ? ORDER BY fecha_creacion DESC"
        );
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateEstado(int $id, string $estado): bool {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }
}
