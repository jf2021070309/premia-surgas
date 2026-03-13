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
}
