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
        $this->requireAuth();
        $this->render('scan/index');
    }

    /**
     * API: POST /scan/buscar
     * Recibe código QR (CLI-000001) y devuelve datos del cliente.
     */
    public function buscar(): void {
        $this->requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);
        $codigo = trim($data['codigo'] ?? '');

        if (!$codigo) {
            $this->json(['success' => false, 'message' => 'Código no proporcionado.']);
        }

        $clienteModel = new ClienteModel();
        
        // Intentar buscar por código primero
        $cliente = $clienteModel->findByCodigo($codigo);
        
        // Si no se encuentra y es numérico, intentar buscar por ID
        if (!$cliente && is_numeric($codigo)) {
            $cliente = $clienteModel->findById((int)$codigo);
        }

        if (!$cliente) {
            $this->json(['success' => false, 'message' => 'Cliente no reconocido. El QR debe contener el código (CLI-XXXXXX) o el ID numérico del cliente.']);
        }

        $this->json([
            'success' => true,
            'cliente' => [
                'id'      => $cliente['id'],
                'nombre'  => $cliente['nombre'],
                'celular' => $cliente['celular']
            ]
        ]);
    }

    /**
     * API: POST /scan/registrar
     * Guarda el movimiento de puntos.
     */
    public function registrar(): void {
        $this->requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);

        $clienteId = (int) ($data['cliente_id'] ?? 0);
        $puntos    = (int) ($data['puntos'] ?? 0);
        $motivo    = trim($data['motivo'] ?? 'Carga de puntos');

        if (!$clienteId || !$puntos) {
            $this->json(['success' => false, 'message' => 'Datos incompletos.']);
        }

        require_once __DIR__ . '/../models/MovimientoModel.php';
        $movModel     = new MovimientoModel();
        $clienteModel = new ClienteModel();

        // 1. Registrar movimiento
        $ok = $movModel->create($clienteId, $_SESSION['id_usuario'], $puntos, $motivo);

        if ($ok) {
            // 2. Actualizar puntos totales del cliente
            $clienteModel->sumarPuntos($clienteId, $puntos);
            $this->json(['success' => true, 'message' => 'Puntos registrados correctamente.']);
        } else {
            $this->json(['success' => false, 'message' => 'Error al registrar movimiento.']);
        }
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

    private function requireAuth(): void {
        if (!isset($_SESSION['id_usuario'])) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Sesión expirada.']);
            }
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    private function isAjax(): bool {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

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
