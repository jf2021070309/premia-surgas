<?php
require_once __DIR__ . '/../config/Database.php';

class ReporteModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getResumenGeneral(): array {
        $resumen = [];
        
        // Total Ventas (monto)
        $stmt = $this->db->query("SELECT SUM(monto) as total FROM ventas");
        $resumen['total_ventas'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Total Puntos Emitidos
        $stmt = $this->db->query("SELECT SUM(puntos) as total FROM ventas");
        $resumen['puntos_emitidos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Total Puntos Canjeados
        $stmt = $this->db->query("SELECT SUM(puntos_usados) as total FROM canjes");
        $resumen['puntos_canjeados'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Total Clientes (Contar todos si el campo estado aún no existe en DB local)
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM clientes");
        $resumen['total_clientes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        return $resumen;
    }

    public function getVentasPorConductor(): array {
        $sql = "SELECT u.nombre, COUNT(v.id) as cantidad_ventas, SUM(v.monto) as total_monto, SUM(v.puntos) as total_puntos
                FROM usuarios u
                LEFT JOIN ventas v ON u.id = v.conductor_id
                WHERE u.rol = 'conductor'
                GROUP BY u.id
                ORDER BY total_monto DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCanjesRecientes(int $limit = 10): array {
        $sql = "SELECT c.id, c.fecha, cl.nombre as cliente, p.nombre as premio, c.puntos_usados, c.estado
                FROM canjes c
                JOIN clientes cl ON c.cliente_id = cl.id
                JOIN premios p ON c.premio_id = p.id
                ORDER BY c.fecha DESC
                LIMIT $limit";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPremiosPopulares(): array {
        $sql = "SELECT p.nombre, COUNT(c.id) as veces_canjeado, SUM(c.puntos_usados) as puntos_totales
                FROM premios p
                LEFT JOIN canjes c ON p.id = c.premio_id
                GROUP BY p.id
                ORDER BY veces_canjeado DESC
                LIMIT 5";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasUltimosDias(int $dias = 7): array {
        $sql = "SELECT DATE(fecha) as dia, SUM(puntos) as puntos
                FROM ventas
                WHERE fecha >= DATE_SUB(NOW(), INTERVAL $dias DAY)
                GROUP BY DATE(fecha)
                ORDER BY dia ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCanjesUltimosDias(int $dias = 7): array {
        $sql = "SELECT DATE(fecha) as dia, SUM(puntos_usados) as puntos
                FROM canjes
                WHERE fecha >= DATE_SUB(NOW(), INTERVAL $dias DAY)
                GROUP BY DATE(fecha)
                ORDER BY dia ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReporteDiarioConductores(string $fecha): array {
        $sql = "SELECT 
                    v.id as venta_id,
                    v.fecha,
                    v.puntos,
                    v.balones_cantidad,
                    v.evidencia_foto,
                    v.estado,
                    c.nombre as cliente_nombre,
                    c.razon_social,
                    c.dni as cliente_dni,
                    u.nombre as conductor_nombre
                FROM ventas v
                JOIN clientes c ON v.cliente_id = c.id
                JOIN usuarios u ON v.conductor_id = u.id
                WHERE DATE(v.fecha) = ? AND v.estado = 'aprobado'
                ORDER BY u.nombre ASC, v.fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
