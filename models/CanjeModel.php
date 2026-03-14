<?php
require_once __DIR__ . '/../config/Database.php';

class CanjeModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Registra un canje, descuenta puntos al cliente y resta stock al premio.
     * Todo dentro de una transacción para asegurar integridad.
     */
    public function registrar(int $clienteId, int $premioId, int $puntosUsados, float $monto = 0): bool {
        try {
            $this->db->beginTransaction();

            // 1. Insertar el canje
            $stmt = $this->db->prepare(
                "INSERT INTO canjes (cliente_id, premio_id, puntos_usados, monto) 
                 VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$clienteId, $premioId, $puntosUsados, $monto]);

            // 2. Descontar puntos al cliente
            $stmt = $this->db->prepare("UPDATE clientes SET puntos = puntos - ? WHERE id = ?");
            $stmt->execute([$puntosUsados, $clienteId]);

            // 3. Restar stock del premio
            $stmt = $this->db->prepare("UPDATE premios SET stock = stock - 1 WHERE id = ?");
            $stmt->execute([$premioId]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    public function getRecientes(int $limit = 5): array {
        $sql = "SELECT c.*, cl.nombre as cliente_nombre, cl.celular as cliente_celular, p.nombre as premio_nombre, p.imagen as premio_imagen
                FROM canjes c
                JOIN clientes cl ON c.cliente_id = cl.id
                JOIN premios p ON c.premio_id = p.id
                ORDER BY c.fecha DESC
                LIMIT $limit";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstado(int $id, string $estado): bool {
        $stmt = $this->db->prepare("UPDATE canjes SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function getByCliente(int $clienteId): array {
        $stmt = $this->db->prepare(
            "SELECT c.*, p.nombre as premio_nombre, p.imagen as premio_imagen, p.descripcion as premio_descripcion
             FROM canjes c
             JOIN premios p ON c.premio_id = p.id
             WHERE c.cliente_id = ?
             ORDER BY c.fecha DESC"
        );
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
