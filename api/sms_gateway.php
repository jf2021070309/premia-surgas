<?php
/**
 * API para el SMS Gateway (Android App)
 * Esta API permite que la App Android consulte mensajes pendientes y reporte su estado.
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json; charset=utf-8');

// --- Seguridad: Validar API KEY ---
$headers = getallheaders();
$apiKey = $headers['X-API-Key'] ?? $headers['x-api-key'] ?? $_SERVER['HTTP_X_API_KEY'] ?? '';

if ($apiKey !== SMS_GATEWAY_API_KEY) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

$db = Database::getConnection();
$method = $_SERVER['REQUEST_METHOD'];

// --- 1. Obtener mensajes pendientes (POLLING) ---
if ($method === 'GET') {
    try {
        $stmt = $db->query("SELECT id, celular as phone, mensaje as message FROM sms_queue WHERE estado = 'pendiente' LIMIT 10");
        $pending = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'count'   => count($pending),
            'messages' => $pending
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// --- 2. Actualizar estado de un mensaje ---
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id      = (int)($data['id'] ?? 0);
    $status  = $data['status'] ?? ''; // 'enviado' o 'fallido'
    $error   = $data['error'] ?? null;

    if (!$id || !in_array($status, ['enviado', 'fallido'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
        exit;
    }

    try {
        $sql = "UPDATE sms_queue SET estado = ?, error_msg = ?, enviado_at = NOW() WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$status, $error, $id]);

        echo json_encode(['success' => true, 'message' => 'Estado actualizado']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Método no permitido']);
