<?php
require_once __DIR__ . '/../config/Database.php';

class VentaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(int $clienteId, int $conductorId, float $monto, int $puntos, ?string $detalle = null, array $items = [], string $estado = 'pendiente'): int {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "INSERT INTO ventas (cliente_id, conductor_id, monto, puntos, detalle, estado)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$clienteId, $conductorId, $monto, $puntos, $detalle, $estado]);
            $ventaId = (int) $this->db->lastInsertId();

            if (!empty($items)) {
                $stmtDet = $this->db->prepare(
                    "INSERT INTO venta_detalles (venta_id, nombre_item, cantidad, puntos_unitarios, puntos_subtotal)
                     VALUES (?, ?, ?, ?, ?)"
                );
                foreach ($items as $item) {
                    $stmtDet->execute([
                        $ventaId,
                        $item['nombre'],
                        $item['cantidad'],
                        $item['puntos_unitarios'],
                        $item['subtotal']
                    ]);
                }
            }

            $this->db->commit();
            return $ventaId;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function validar(int $id, string $estado, int $validadorId): bool {
        try {
            $this->db->beginTransaction();
            
            // 1. Obtener datos de la venta
            $venta = $this->getById($id);
            if (!$venta || $venta['estado'] !== 'pendiente') {
                throw new Exception("Movimiento no válido o ya procesada.");
            }

            // 2. Si es aprobación, sumar puntos al cliente
            if ($estado === 'aprobado') {
                $puntos = (int) $venta['puntos'];
                $clienteId = $venta['cliente_id'];

                $stmtUpd = $this->db->prepare("UPDATE clientes SET puntos = puntos + ? WHERE id = ?");
                $stmtUpd->execute([$puntos, $clienteId]);

                // Evaluar incentivos
                require_once __DIR__ . '/IncentivoModel.php';
                $incentivoModel = new IncentivoModel();
                $incentivoModel->evaluarMetas($clienteId);
            }

            // 3. Actualizar estado de la venta
            // Reutilizamos el campo 'detalle' para añadir quién validó o simplemente actualizamos el estado
            $stmt = $this->db->prepare("UPDATE ventas SET estado = ? WHERE id = ?");
            $stmt->execute([$estado, $id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            return false;
        }
    }

    public function getPendientes(): array {
        $stmt = $this->db->prepare(
            "SELECT v.*, c.nombre as cliente_nombre, c.dni as cliente_dni, u.nombre as conductor_nombre
             FROM ventas v
             JOIN clientes c ON v.cliente_id = c.id
             JOIN usuarios u ON v.conductor_id = u.id
             WHERE v.estado = 'pendiente'
             ORDER BY v.fecha DESC"
        );
        $stmt->execute();
        return $this->attachDetalles($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getAllAdmin(): array {
        $stmt = $this->db->prepare(
            "SELECT v.*, c.nombre as cliente_nombre, c.dni as cliente_dni, u.nombre as conductor_nombre
             FROM ventas v
             JOIN clientes c ON v.cliente_id = c.id
             LEFT JOIN usuarios u ON v.conductor_id = u.id
             ORDER BY v.fecha DESC LIMIT 100"
        );
        $stmt->execute();
        return $this->attachDetalles($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM ventas WHERE id = ?");
        $stmt->execute([$id]);
        $venta = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$venta) return null;
        
        $detalles = $this->getDetalles($id);
        $venta['items'] = $detalles;
        return $venta;
    }

    public function getDetalles(int $ventaId): array {
        $stmt = $this->db->prepare("SELECT * FROM venta_detalles WHERE venta_id = ?");
        $stmt->execute([$ventaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCliente(int $clienteId): array {
        $stmt = $this->db->prepare(
            "SELECT v.*, u.nombre as conductor
             FROM ventas v
             LEFT JOIN usuarios u ON u.id = v.conductor_id
             WHERE v.cliente_id = ?
             ORDER BY v.fecha DESC"
        );
        $stmt->execute([$clienteId]);
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->attachDetalles($ventas);
    }
    public function getByConductor(int $conductorId): array {
        $stmt = $this->db->prepare(
            "SELECT v.*, c.nombre as cliente_nombre, c.dni as cliente_dni, c.celular as cliente_celular
             FROM ventas v
             JOIN clientes c ON c.id = v.cliente_id
             WHERE v.conductor_id = ?
             ORDER BY v.fecha DESC"
        );
        $stmt->execute([$conductorId]);
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->attachDetalles($ventas);
    }

    public function getByConductorPaginated(int $conductorId, int $offset, int $limit, string $search = '', string $fechaDesde = '', string $fechaHasta = ''): array {
        $params = [$conductorId];
        $where = "v.conductor_id = ?";

        if (!empty($search)) {
            $where .= " AND (c.nombre LIKE ? OR c.dni LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($fechaDesde)) {
            $where .= " AND DATE(v.fecha) >= ?";
            $params[] = $fechaDesde;
        }

        if (!empty($fechaHasta)) {
            $where .= " AND DATE(v.fecha) <= ?";
            $params[] = $fechaHasta;
        }

        // Count total
        $countQuery = "SELECT COUNT(*) as total FROM ventas v JOIN clientes c ON c.id = v.cliente_id WHERE $where";
        $stmtCount = $this->db->prepare($countQuery);
        $stmtCount->execute($params);
        $totalRows = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

        // Sum points
        $sumQuery = "SELECT SUM(v.puntos) as total_puntos FROM ventas v JOIN clientes c ON c.id = v.cliente_id WHERE $where";
        $stmtSum = $this->db->prepare($sumQuery);
        $stmtSum->execute($params);
        $totalPuntos = $stmtSum->fetch(PDO::FETCH_ASSOC)['total_puntos'] ?? 0;

        // Data
        $query = "SELECT v.*, c.nombre as cliente_nombre, c.dni as cliente_dni, c.celular as cliente_celular
                 FROM ventas v
                 JOIN clientes c ON c.id = v.cliente_id
                 WHERE $where
                 ORDER BY v.fecha DESC
                 LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $this->attachDetalles($data),
            'total' => $totalRows,
            'total_puntos' => $totalPuntos
        ];
    }

    private function attachDetalles(array $ventas): array {
        if (empty($ventas)) return [];
        
        $ids = array_column($ventas, 'id');
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        
        $stmt = $this->db->prepare("SELECT * FROM venta_detalles WHERE venta_id IN ($placeholders)");
        $stmt->execute($ids);
        $allDetalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Group by venta_id
        $grouped = [];
        foreach ($allDetalles as $det) {
            $grouped[$det['venta_id']][] = $det;
        }
        
        // Assign to ventas
        foreach ($ventas as &$v) {
            $v['items'] = $grouped[$v['id']] ?? [];
        }
        
        return $ventas;
    }
}
