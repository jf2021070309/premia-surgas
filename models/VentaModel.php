<?php
require_once __DIR__ . '/../config/Database.php';

class VentaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(int $clienteId, int $conductorId, float $monto, int $puntos, ?string $detalle = null): int {
        $stmt = $this->db->prepare(
            "INSERT INTO ventas (cliente_id, conductor_id, monto, puntos, detalle)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$clienteId, $conductorId, $monto, $puntos, $detalle]);
        return (int) $this->db->lastInsertId();
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            'data' => $data,
            'total' => $totalRows,
            'total_puntos' => $totalPuntos
        ];
    }
}
