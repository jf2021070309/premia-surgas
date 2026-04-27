<?php
require_once __DIR__ . '/../config/Database.php';

class IncentivoModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // ═══════════════════════════════════════════════════════════
    // REGLAS
    // ═══════════════════════════════════════════════════════════

    public function getAllReglas(): array {
        $stmt = $this->db->query("SELECT * FROM incentivos_reglas ORDER BY estado DESC, fecha_creacion DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReglasActivas(): array {
        $stmt = $this->db->query("SELECT * FROM incentivos_reglas WHERE estado = 1 ORDER BY fecha_creacion DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findRegla(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM incentivos_reglas WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function createRegla(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO incentivos_reglas (nombre, descripcion, tipo_cliente, meta_cantidad, periodo, tipo_premio, valor_premio, descripcion_premio, vigencia_dias, estado)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'] ?? null,
            $data['tipo_cliente'],
            (int) $data['meta_cantidad'],
            $data['periodo'],
            $data['tipo_premio'],
            (float) $data['valor_premio'],
            $data['descripcion_premio'],
            (int) $data['vigencia_dias'],
            (int) ($data['estado'] ?? 1),
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateRegla(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE incentivos_reglas SET nombre = ?, descripcion = ?, tipo_cliente = ?, meta_cantidad = ?, periodo = ?, tipo_premio = ?, valor_premio = ?, descripcion_premio = ?, vigencia_dias = ?, estado = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'] ?? null,
            $data['tipo_cliente'],
            (int) $data['meta_cantidad'],
            $data['periodo'],
            $data['tipo_premio'],
            (float) $data['valor_premio'],
            $data['descripcion_premio'],
            (int) $data['vigencia_dias'],
            (int) ($data['estado'] ?? 1),
            $id
        ]);
    }

    public function deleteRegla(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM incentivos_reglas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ═══════════════════════════════════════════════════════════
    // VALES
    // ═══════════════════════════════════════════════════════════

    public function getAllVales(int $limit = 200): array {
        $stmt = $this->db->prepare(
            "SELECT v.*, c.nombre AS cliente_nombre, c.codigo AS cliente_codigo, c.tipo_cliente,
                    r.nombre AS regla_nombre
             FROM incentivos_vales v
             JOIN clientes c ON c.id = v.cliente_id
             JOIN incentivos_reglas r ON r.id = v.regla_id
             ORDER BY v.fecha_emision DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getValesByCliente(int $clienteId): array {
        $stmt = $this->db->prepare(
            "SELECT v.*, r.nombre AS regla_nombre
             FROM incentivos_vales v
             JOIN incentivos_reglas r ON r.id = v.regla_id
             WHERE v.cliente_id = ?
             ORDER BY v.fecha_emision DESC"
        );
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getValesActivosByCliente(int $clienteId): array {
        // Also auto-expire stale vouchers
        $this->expirarValesVencidos();

        $stmt = $this->db->prepare(
            "SELECT v.*, r.nombre AS regla_nombre
             FROM incentivos_vales v
             JOIN incentivos_reglas r ON r.id = v.regla_id
             WHERE v.cliente_id = ? AND v.estado = 'activo'
             ORDER BY v.fecha_vencimiento ASC"
        );
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findVale(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT v.*, c.nombre AS cliente_nombre, r.nombre AS regla_nombre
             FROM incentivos_vales v
             JOIN clientes c ON c.id = v.cliente_id
             JOIN incentivos_reglas r ON r.id = v.regla_id
             WHERE v.id = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findValeByCodigo(string $codigo): ?array {
        $stmt = $this->db->prepare(
            "SELECT v.*, c.nombre AS cliente_nombre, r.nombre AS regla_nombre
             FROM incentivos_vales v
             JOIN clientes c ON c.id = v.cliente_id
             JOIN incentivos_reglas r ON r.id = v.regla_id
             WHERE v.codigo = ?"
        );
        $stmt->execute([$codigo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function marcarValeUsado(int $id, int $usadoPor): bool {
        $stmt = $this->db->prepare(
            "UPDATE incentivos_vales SET estado = 'usado', usado_fecha = NOW(), usado_por = ? WHERE id = ? AND estado = 'activo'"
        );
        return $stmt->execute([$usadoPor, $id]);
    }

    public function cancelarVale(int $id): bool {
        $stmt = $this->db->prepare("UPDATE incentivos_vales SET estado = 'cancelado' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function expirarValesVencidos(): int {
        $stmt = $this->db->prepare(
            "UPDATE incentivos_vales SET estado = 'vencido' WHERE estado = 'activo' AND fecha_vencimiento < CURDATE()"
        );
        $stmt->execute();
        return $stmt->rowCount();
    }

    // ═══════════════════════════════════════════════════════════
    // EVALUACIÓN DE METAS (Core del sistema)
    // ═══════════════════════════════════════════════════════════

    /**
     * Evalúa todas las reglas activas para un cliente dado.
     * Retorna array de vales generados (puede ser vacío).
     */
    public function evaluarMetas(int $clienteId): array {
        $clienteStmt = $this->db->prepare("SELECT tipo_cliente FROM clientes WHERE id = ?");
        $clienteStmt->execute([$clienteId]);
        $tipoCliente = $clienteStmt->fetchColumn();

        if (!$tipoCliente) return [];

        $reglas = $this->getReglasActivas();
        $valesGenerados = [];

        foreach ($reglas as $regla) {
            // Verificar si aplica al tipo de cliente
            if ($regla['tipo_cliente'] !== 'Todos' && $regla['tipo_cliente'] !== $tipoCliente) {
                continue;
            }

            // Determinar el periodo actual
            $periodoKey = $this->getPeriodoKey($regla['periodo']);

            // Verificar si ya tiene un vale para esta regla+periodo
            $existeStmt = $this->db->prepare(
                "SELECT COUNT(*) FROM incentivos_vales WHERE cliente_id = ? AND regla_id = ? AND periodo_evaluado = ?"
            );
            $existeStmt->execute([$clienteId, $regla['id'], $periodoKey]);
            if ((int) $existeStmt->fetchColumn() > 0) {
                continue; // Ya tiene vale de este periodo
            }

            // Contar operaciones del cliente en el periodo
            $cantidadOps = $this->contarOperacionesPeriodo($clienteId, $regla['periodo']);

            // ¿Cumple la meta?
            if ($cantidadOps >= $regla['meta_cantidad']) {
                $vale = $this->generarVale($clienteId, $regla, $periodoKey, $cantidadOps);
                if ($vale) {
                    $valesGenerados[] = $vale;
                }
            }
        }

        return $valesGenerados;
    }

    /**
     * Retorna la cantidad de operaciones (ventas) de un cliente en el periodo.
     */
    public function contarOperacionesPeriodo(int $clienteId, string $periodo): int {
        $fechas = $this->getFechasPeriodo($periodo);
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM ventas WHERE cliente_id = ? AND fecha >= ? AND fecha < ?"
        );
        $stmt->execute([$clienteId, $fechas['inicio'], $fechas['fin']]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Retorna el progreso de un cliente para cada regla activa aplicable.
     */
    public function getProgresoCliente(int $clienteId): array {
        $clienteStmt = $this->db->prepare("SELECT tipo_cliente FROM clientes WHERE id = ?");
        $clienteStmt->execute([$clienteId]);
        $tipoCliente = $clienteStmt->fetchColumn();
        if (!$tipoCliente) return [];

        $reglas = $this->getReglasActivas();
        $progreso = [];

        foreach ($reglas as $regla) {
            if ($regla['tipo_cliente'] !== 'Todos' && $regla['tipo_cliente'] !== $tipoCliente) {
                continue;
            }

            $periodoKey = $this->getPeriodoKey($regla['periodo']);
            $cantidadOps = $this->contarOperacionesPeriodo($clienteId, $regla['periodo']);

            // ¿Ya tiene vale generado?
            $existeStmt = $this->db->prepare(
                "SELECT COUNT(*) FROM incentivos_vales WHERE cliente_id = ? AND regla_id = ? AND periodo_evaluado = ?"
            );
            $existeStmt->execute([$clienteId, $regla['id'], $periodoKey]);
            $yaTieneVale = (int) $existeStmt->fetchColumn() > 0;

            $progreso[] = [
                'regla_id'       => $regla['id'],
                'regla_nombre'   => $regla['nombre'],
                'descripcion'    => $regla['descripcion'],
                'meta'           => (int) $regla['meta_cantidad'],
                'actual'         => $cantidadOps,
                'porcentaje'     => min(100, round(($cantidadOps / max(1, $regla['meta_cantidad'])) * 100)),
                'periodo'        => $regla['periodo'],
                'periodo_key'    => $periodoKey,
                'premio'         => $regla['descripcion_premio'],
                'tipo_premio'    => $regla['tipo_premio'],
                'valor_premio'   => $regla['valor_premio'],
                'cumplida'       => $cantidadOps >= $regla['meta_cantidad'],
                'vale_generado'  => $yaTieneVale,
            ];
        }

        return $progreso;
    }

    // ═══════════════════════════════════════════════════════════
    // HELPERS PRIVADOS
    // ═══════════════════════════════════════════════════════════

    private function generarVale(int $clienteId, array $regla, string $periodoKey, int $cantidadLograda): ?array {
        $codigo = 'VALE-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $fechaVencimiento = date('Y-m-d', strtotime("+{$regla['vigencia_dias']} days"));

        $stmt = $this->db->prepare(
            "INSERT INTO incentivos_vales (codigo, cliente_id, regla_id, periodo_evaluado, cantidad_lograda, tipo_premio, valor, descripcion, fecha_vencimiento)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        try {
            $stmt->execute([
                $codigo,
                $clienteId,
                $regla['id'],
                $periodoKey,
                $cantidadLograda,
                $regla['tipo_premio'],
                $regla['valor_premio'],
                $regla['descripcion_premio'],
                $fechaVencimiento
            ]);
            return [
                'id'           => (int) $this->db->lastInsertId(),
                'codigo'       => $codigo,
                'regla_nombre' => $regla['nombre'],
                'descripcion'  => $regla['descripcion_premio'],
                'valor'        => $regla['valor_premio'],
                'tipo_premio'  => $regla['tipo_premio'],
                'vencimiento'  => $fechaVencimiento
            ];
        } catch (PDOException $e) {
            // Código duplicado u otro error
            return null;
        }
    }

    private function getPeriodoKey(string $periodo): string {
        return match ($periodo) {
            'semanal'     => date('Y') . '-W' . date('W'),
            'trimestral'  => date('Y') . '-Q' . ceil(date('n') / 3),
            default       => date('Y-m'), // mensual
        };
    }

    private function getFechasPeriodo(string $periodo): array {
        return match ($periodo) {
            'semanal' => [
                'inicio' => date('Y-m-d', strtotime('monday this week')),
                'fin'    => date('Y-m-d', strtotime('monday next week')),
            ],
            'trimestral' => [
                'inicio' => date('Y-m-d', mktime(0, 0, 0, (ceil(date('n') / 3) - 1) * 3 + 1, 1, date('Y'))),
                'fin'    => date('Y-m-d', mktime(0, 0, 0, ceil(date('n') / 3) * 3 + 1, 1, date('Y'))),
            ],
            default => [ // mensual
                'inicio' => date('Y-m-01'),
                'fin'    => date('Y-m-01', strtotime('+1 month')),
            ],
        };
    }

    // ═══════════════════════════════════════════════════════════
    // ESTADÍSTICAS
    // ═══════════════════════════════════════════════════════════

    public function getEstadisticas(): array {
        $stats = [];

        $stmt = $this->db->query("SELECT COUNT(*) FROM incentivos_reglas WHERE estado = 1");
        $stats['reglas_activas'] = (int) $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM incentivos_vales WHERE estado = 'activo'");
        $stats['vales_activos'] = (int) $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM incentivos_vales WHERE estado = 'usado'");
        $stats['vales_usados'] = (int) $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM incentivos_vales");
        $stats['vales_total'] = (int) $stmt->fetchColumn();

        return $stats;
    }
}
