<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../config/config.php';

class ScanController {

    /**
     * GET /scan?c=CLI-000001&t=TOKEN
     * Valida QR y registra una venta / muestra perfil del cliente.
     */
    public function index(): void {
        $codigo = trim($_GET['c'] ?? '');
        $token  = trim($_GET['t'] ?? '');

        if (!$codigo || !$token) {
            $this->render('scan/error', ['mensaje' => 'QR inválido. Faltan parámetros.']);
            return;
        }

        $clienteModel = new ClienteModel();
        $cliente = null;

        // Buscar buscando por código (no hay findByCodigo, lo haremos inline)
        $db = \Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM clientes WHERE codigo = ? LIMIT 1");
        $stmt->execute([$codigo]);
        $cliente = $stmt->fetch();

        if (!$cliente) {
            $this->render('scan/error', ['mensaje' => 'Cliente no encontrado.']);
            return;
        }

        // Validar token
        $expectedToken = hash_hmac('sha256', $codigo, SECRET_KEY);
        if (!hash_equals($expectedToken, $token)) {
            $this->render('scan/error', ['mensaje' => 'QR no válido o fue falsificado.']);
            return;
        }

        $ventas = (new VentaModel())->getByCliente($cliente['id']);
        $this->render('scan/perfil', ['cliente' => $cliente, 'ventas' => $ventas]);
    }

    /**
     * POST /scan/venta  → registra venta y suma puntos (JSON)
     */
    public function venta(): void {
        if (!isset($_SESSION['id_usuario'])) {
            $this->json(['success' => false, 'message' => 'No autenticado.']);
        }

        header('Content-Type: application/json');
        $data       = json_decode(file_get_contents('php://input'), true);
        $clienteId  = (int)($data['cliente_id'] ?? 0);
        $monto      = (float)($data['monto'] ?? 0);

        if (!$clienteId || $monto <= 0) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
            exit;
        }

        // 1 punto por cada sol
        $puntos = (int) floor($monto);

        $ventaModel   = new VentaModel();
        $clienteModel = new ClienteModel();

        $ventaModel->create($clienteId, $_SESSION['id_usuario'], $monto, $puntos);
        $clienteModel->sumarPuntos($clienteId, $puntos);

        echo json_encode(['success' => true, 'puntos_sumados' => $puntos]);
        exit;
    }

    // ── helpers ──────────────────────────────────────────────────

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function json(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
