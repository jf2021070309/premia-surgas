<?php
require_once __DIR__ . '/../config/Database.php';

class AuditoriaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
        // Intentar crear la tabla si no existe (opcional, mejor que el user la cree)
    }

    /**
     * Registra un evento en la auditoría con soporte para metadatos y detección de dispositivo
     * 
     * @param int|null $idUsuario ID del usuario
     * @param string $accion Nombre de la acción
     * @param string $descripcion Detalle legible
     * @param string $modulo Módulo afectado
     * @param array|null $metadata Datos adicionales (antes/después de un cambio)
     * @return bool
     */
    public function registrar(?int $idUsuario, string $accion, string $descripcion = '', string $modulo = 'GENERAL', ?array $metadata = null): bool {
        if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
            $idUsuario = $_SESSION['id_usuario'];
        }
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $fullAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
        
        // Parsear dispositivo básico
        $device = 'Escritorio';
        if (preg_match('/(android|iphone|ipad|mobile)/i', $fullAgent)) {
            $device = 'Móvil';
        }
        $userAgentInfo = $device . ' — ' . $this->getBrowser($fullAgent);

        try {
            // Intento con la nueva estructura (metadata y user_agent)
            $stmt = $this->db->prepare(
                "INSERT INTO auditoria (id_usuario, accion, descripcion, modulo, ip_address, user_agent, metadata) 
                 VALUES (:u, :a, :d, :m, :ip, :ua, :meta)"
            );

            return $stmt->execute([
                ':u'    => $idUsuario,
                ':a'    => strtoupper($accion),
                ':d'    => $descripcion,
                ':m'    => strtoupper($modulo),
                ':ip'   => $ip,
                ':ua'   => $userAgentInfo,
                ':meta' => $metadata ? json_encode($metadata) : null
            ]);
        } catch (PDOException $e) {
            // FALLBACK: Si las columnas no existen (Error 1054), registrar solo lo básico
            if ($e->getCode() == '42S22' || strpos($e->getMessage(), 'user_agent') !== false) {
                $stmt = $this->db->prepare(
                    "INSERT INTO auditoria (id_usuario, accion, descripcion, modulo, ip_address) 
                     VALUES (:u, :a, :d, :m, :ip)"
                );
                return $stmt->execute([
                    ':u'  => $idUsuario,
                    ':a'  => strtoupper($accion),
                    ':d'  => $descripcion,
                    ':m'  => strtoupper($modulo),
                    ':ip' => $ip
                ]);
            }
            throw $e; // Si es otro error, lanzarlo
        }
    }

    private function getBrowser(string $agent): string {
        if (strpos($agent, 'Firefox') !== false) return 'Firefox';
        if (strpos($agent, 'Chrome') !== false) return 'Chrome';
        if (strpos($agent, 'Safari') !== false) return 'Safari';
        if (strpos($agent, 'Edge') !== false) return 'Edge';
        if (strpos($agent, 'MSIE') !== false || strpos($agent, 'Trident') !== false) return 'IE';
        return 'Browser';
    }

    /**
     * Obtiene todos los logs ordenados por fecha
     * 
     * @param int $limit Límite de registros
     * @return array
     */
    public function getAll(int $limit = 500): array {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.nombre as usuario_nombre, u.usuario as usuario_login, u.rol as usuario_rol
             FROM auditoria a
             LEFT JOIN usuarios u ON a.id_usuario = u.id
             ORDER BY a.fecha_hora DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
