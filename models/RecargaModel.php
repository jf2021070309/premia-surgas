<?php
require_once __DIR__ . '/../config/Database.php';

class RecargaModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllPending(): array {
        $stmt = $this->db->prepare("SELECT r.*, c.nombre as cliente_nombre, c.celular as cliente_celular, c.dni as cliente_dni 
                                    FROM recargas r 
                                    JOIN clientes c ON r.cliente_id = c.id 
                                    WHERE r.estado = 'pendiente' 
                                    ORDER BY r.fecha DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        $stmt = $this->db->prepare("SELECT r.*, c.nombre as cliente_nombre, u.nombre as validador_nombre 
                                    FROM recargas r 
                                    JOIN clientes c ON r.cliente_id = c.id 
                                    LEFT JOIN usuarios u ON r.validado_por = u.id
                                    ORDER BY r.fecha DESC LIMIT 50");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM recargas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function actualizarEstado(int $id, string $estado, int $validador_id): bool {
        try {
            $this->db->beginTransaction();
            
            // 1. Obtener datos de la recarga
            $recarga = $this->findById($id);
            if (!$recarga || $recarga['estado'] !== 'pendiente') {
                throw new Exception("Recarga no válida o ya procesada.");
            }

            // 2. Si es aprobación, sumar puntos al cliente
            if ($estado === 'aprobado') {
                $puntosRaw = str_replace(',', '', $recarga['puntos']);
                $puntosRecarga = (int) $puntosRaw;
                $stmt = $this->db->prepare("UPDATE clientes SET puntos = puntos + ? WHERE id = ?");
                $stmt->execute([$puntosRecarga, $recarga['cliente_id']]);
            }

            // 3. Actualizar estado de la recarga
            $stmt = $this->db->prepare("UPDATE recargas SET estado = ?, validado_por = ?, fecha_validacion = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$estado, $validador_id, $id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            $_SESSION['db_error'] = $e->getMessage();
            return false;
        }
    }
}
