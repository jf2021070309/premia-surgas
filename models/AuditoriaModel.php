<?php
require_once __DIR__ . '/../config/Database.php';

class AuditoriaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
        // Intentar crear la tabla si no existe (opcional, mejor que el user la cree)
    }

    /**
     * Registra un evento en la auditoría
     * 
     * @param int|null $idUsuario ID del usuario que realiza la acción
     * @param string $accion Título o nombre de la acción (e.g., 'INICIO_SESION', 'REGISTRO_CLIENTE')
     * @param string $descripcion Detalle de lo que ocurrió
     * @param string $modulo Nombre del módulo afectado
     * @return bool
     */
    public function registrar(?int $idUsuario, string $accion, string $descripcion = '', string $modulo = 'GENERAL'): bool {
        if ($idUsuario === null && isset($_SESSION['user_id'])) {
            $idUsuario = $_SESSION['user_id'];
        }
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

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
