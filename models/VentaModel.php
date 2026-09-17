<?php
require_once __DIR__ . '/../config/Database.php';

class VentaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(int $clienteId, ?int $conductorId, float $monto, int $puntos, ?string $detalle = null, array $items = [], string $estado = 'pendiente', ?int $balonesCantidad = null, ?int $balonesVerificados = null, ?string $evidenciaFoto = null): int {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "INSERT INTO ventas (cliente_id, conductor_id, monto, puntos, balones_cantidad, balones_verificados, evidencia_foto, detalle, estado)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $clienteId,
                $conductorId ?: null,
                $monto,
                $puntos,
                $balonesCantidad,
                $balonesVerificados,
                $evidenciaFoto,
                $detalle,
                $estado
            ]);
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

    /**
     * Registra una solicitud de puntos iniciada por un Punto de Venta (pendiente de verificación por conductor)
     */
    public function solicitarPuntosPV(int $clienteId, int $balones, int $puntos, string $detalle = ''): int {
        $stmt = $this->db->prepare(
            "INSERT INTO ventas (cliente_id, conductor_id, monto, puntos, balones_cantidad, detalle, estado, fecha)
             VALUES (?, NULL, 0, ?, ?, ?, 'pendiente', NOW())"
        );
        $stmt->execute([
            $clienteId,
            $puntos,
            $balones,
            $detalle ?: "Solicitud de Puntos por $balones Balones de 10kg (+$puntos pts)"
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Obtiene solicitudes pendientes de puntos para clientes Punto de Venta
     */
    public function getPendientesPV(?int $clienteId = null): array {
        $sql = "SELECT v.*, c.nombre as cliente_nombre, c.razon_social as cliente_razon_social, 
                       c.dni as cliente_dni, c.ruc as cliente_ruc, c.celular as cliente_celular, 
                       c.direccion as cliente_direccion, c.tipo_cliente
                FROM ventas v
                JOIN clientes c ON v.cliente_id = c.id
                WHERE v.estado = 'pendiente' AND v.balones_cantidad IS NOT NULL";
        $params = [];
        if ($clienteId) {
            $sql .= " AND v.cliente_id = ?";
            $params[] = $clienteId;
        }
        $sql .= " ORDER BY v.fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Aprueba la entrega de balones tras verificación fotográfica y suma los puntos al cliente
     */
    public function aprobarEntregaPV(int $ventaId, int $conductorId, int $balonesVerificados, ?string $evidenciaFoto): bool {
        try {
            $this->db->beginTransaction();

            $venta = $this->getById($ventaId);
            if (!$venta || $venta['estado'] !== 'pendiente') {
                throw new Exception("La operación no existe o ya no está pendiente.");
            }

            $puntos = (int) $venta['puntos'];
            $clienteId = (int) $venta['cliente_id'];

            // 1. Actualizar la venta con la evidencia y conductor que aprobó
            $stmt = $this->db->prepare(
                "UPDATE ventas 
                 SET conductor_id = ?, 
                     balones_verificados = ?, 
                     evidencia_foto = ?, 
                     estado = 'aprobado', 
                     fecha_aprobacion = NOW()
                 WHERE id = ?"
            );
            $stmt->execute([$conductorId, $balonesVerificados, $evidenciaFoto, $ventaId]);

            // 2. Sumar puntos al cliente
            $stmtUpd = $this->db->prepare("UPDATE clientes SET puntos = puntos + ? WHERE id = ?");
            $stmtUpd->execute([$puntos, $clienteId]);

            // 3. Evaluar incentivos
            require_once __DIR__ . '/IncentivoModel.php';
            $incentivoModel = new IncentivoModel();
            $incentivoModel->evaluarMetas($clienteId);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error en aprobarEntregaPV: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marca la venta como notificada al admin por correo
     */
    public function marcarNotificadoAdmin(int $ventaId): bool {
        $stmt = $this->db->prepare("UPDATE ventas SET notificado_admin = 1 WHERE id = ?");
        return $stmt->execute([$ventaId]);
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
            "SELECT v.*, c.nombre as cliente_nombre, c.dni as cliente_dni, c.celular as cliente_celular, u.nombre as conductor_nombre
             FROM ventas v
             JOIN clientes c ON v.cliente_id = c.id
             LEFT JOIN usuarios u ON v.conductor_id = u.id
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
        $stmt = $this->db->prepare(
            "SELECT v.*, c.nombre as cliente_nombre, c.razon_social as cliente_razon_social,
                    c.dni as cliente_dni, c.ruc as cliente_ruc, c.celular as cliente_celular, 
                    c.direccion as cliente_direccion, c.tipo_cliente,
                    u.nombre as conductor_nombre
             FROM ventas v 
             JOIN clientes c ON v.cliente_id = c.id 
             LEFT JOIN usuarios u ON v.conductor_id = u.id
             WHERE v.id = ?"
        );
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
