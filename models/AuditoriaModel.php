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
     * @param array|null $metadata Datos adicionales (antes/después de un cambio)
     * @param string $tipoUsuario Tipo de usuario ('trabajador' o 'cliente')
     * @return bool
     */
    public function registrar(?int $idUsuario, string $accion, string $descripcion = '', string $modulo = 'GENERAL', ?array $metadata = null, string $tipoUsuario = 'trabajador'): bool {
        // Si no se pasa ID, lo tomamos de la sesión
        if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
            $idUsuario = $_SESSION['id_usuario'];
        }
        
        // AUTO-DETECCIÓN DE TIPO: 
        // Si el tipo es el por defecto ('trabajador') pero la sesión dice que es 'cliente', lo corregimos.
        // Esto arregla el problema globalmente sin cambiar cada controlador.
        if ($tipoUsuario === 'trabajador' && isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente') {
            $tipoUsuario = 'cliente';
        }
        
        $ip = $this->getClientIP();
        $fullAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
        
        // Parsear dispositivo básico
        $device = 'Escritorio';
        if (preg_match('/(android|iphone|ipad|mobile)/i', $fullAgent)) {
            $device = 'Móvil';
        }
        $userAgentInfo = $device . ' — ' . $this->getBrowser($fullAgent);

        try {
            // Intento con la nueva estructura (metadata, user_agent, tipo_usuario)
            $stmt = $this->db->prepare(
                "INSERT INTO auditoria (id_usuario, tipo_usuario, accion, descripcion, modulo, ip_address, user_agent, metadata) 
                 VALUES (:u, :tu, :a, :d, :m, :ip, :ua, :meta)"
            );

            return $stmt->execute([
                ':u'    => $idUsuario,
                ':tu'   => $tipoUsuario,
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

    private function getClientIP(): string {
        $keys = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];

        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                return trim($ip);
            }
        }
        return '0.0.0.0';
    }

    /**
     * Obtiene todos los logs ordenados por fecha
     * 
     * @param int $limit Límite de registros
     * @return array
     */
    public function getAll(int $limit = 500): array {
        $stmt = $this->db->prepare(
            "SELECT a.*, 
                    CASE 
                        WHEN a.tipo_usuario = 'cliente' THEN c.nombre 
                        ELSE u.nombre 
                    END as usuario_nombre,
                    CASE 
                        WHEN a.tipo_usuario = 'cliente' THEN c.dni 
                        ELSE u.usuario 
                    END as usuario_login,
                    CASE 
                        WHEN a.tipo_usuario = 'cliente' THEN 'cliente' 
                        ELSE u.rol 
                    END as usuario_rol
             FROM auditoria a
             LEFT JOIN usuarios u ON a.id_usuario = u.id AND a.tipo_usuario = 'trabajador'
             LEFT JOIN clientes c ON a.id_usuario = c.id AND a.tipo_usuario = 'cliente'
             ORDER BY a.fecha_hora DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
