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
    public function registrar(int $clienteId, int $premioId, int $puntosUsados, float $monto = 0, ?string $comprobanteUrl = null): bool {
        try {
            $this->db->beginTransaction();

            // 1. Verificar puntos del cliente con bloqueo (FOR UPDATE)
            $stmt = $this->db->prepare("SELECT puntos FROM clientes WHERE id = ? FOR UPDATE");
            $stmt->execute([$clienteId]);
            $puntosActuales = (int) $stmt->fetchColumn();

            if ($puntosActuales < $puntosUsados) {
                throw new Exception("Puntos insuficientes.");
            }

            // 2. Verificar stock del premio con bloqueo (FOR UPDATE)
            $stmt = $this->db->prepare("SELECT stock FROM premios WHERE id = ? FOR UPDATE");
            $stmt->execute([$premioId]);
            $stockActual = (int) $stmt->fetchColumn();

            if ($stockActual <= 0) {
                throw new Exception("Sin stock disponible.");
            }

            // Determinar estado inicial
            // Si hay monto > 0 y el método es depósito, esperamos validación digital (pago_pendiente).
            // Si es efectivo (yape), el cliente paga en planta, así que no hay "espera" de confirmación digital (pendiente).
            $metodoPago = $_POST['metodo_pago'] ?? '';
            $estadoInicial = ($monto > 0 && $metodoPago === 'deposito') ? 'pago_pendiente' : 'pendiente';

            // 3. Insertar el canje
            $stmt = $this->db->prepare(
                "INSERT INTO canjes (cliente_id, premio_id, puntos_usados, monto, comprobante_url, estado) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$clienteId, $premioId, $puntosUsados, $monto, $comprobanteUrl, $estadoInicial]);

            // 4. Descontar puntos al cliente (Incluso si es híbrido, se descuentan los puntos que puso)
            $stmt = $this->db->prepare("UPDATE clientes SET puntos = puntos - ? WHERE id = ?");
            $stmt->execute([$puntosUsados, $clienteId]);

            // 5. Restar stock del premio (Se reserva el item)
            $stmt = $this->db->prepare("UPDATE premios SET stock = stock - 1 WHERE id = ?");
            $stmt->execute([$premioId]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error al registrar canje: " . $e->getMessage());
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
        // Estados que requieren devolución de puntos y stock
        $estadosDevolucion = ['cancelado', 'pago_rechazado'];

        if (in_array($estado, $estadosDevolucion)) {
            try {
                $this->db->beginTransaction();

                // 1. Obtener datos del canje (Solo si no ha sido devuelto ya)
                $stmt = $this->db->prepare("SELECT cliente_id, premio_id, puntos_usados, estado FROM canjes WHERE id = ? FOR UPDATE");
                $stmt->execute([$id]);
                $canje = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$canje || in_array($canje['estado'], $estadosDevolucion)) {
                     throw new Exception("Canje no válido para devolución o ya procesado.");
                }

                // 2. Devolver puntos al cliente
                $stmt = $this->db->prepare("UPDATE clientes SET puntos = puntos + ? WHERE id = ?");
                $stmt->execute([$canje['puntos_usados'], $canje['cliente_id']]);

                // 3. Devolver stock al premio
                $stmt = $this->db->prepare("UPDATE premios SET stock = stock + 1 WHERE id = ?");
                $stmt->execute([$canje['premio_id']]);

                // 4. Actualizar estado del canje
                $stmt = $this->db->prepare("UPDATE canjes SET estado = ? WHERE id = ?");
                $stmt->execute([$estado, $id]);

                $this->db->commit();
                return true;
            } catch (Exception $e) {
                if ($this->db->inTransaction()) $this->db->rollBack();
                error_log("Error al actualizar estado canje: " . $e->getMessage());
                return false;
            }
        }

        // Caso normal (ej: pago_pendiente -> pago_aprobado, o pendiente -> entregado)
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
