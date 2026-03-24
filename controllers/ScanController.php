<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../models/ConfiguracionModel.php';
require_once __DIR__ . '/../models/TipoOperacionModel.php';
require_once __DIR__ . '/../config/config.php';

class ScanController {

    /**
     * GET /scan?c=CLI-000001&t=TOKEN
     * Valida QR y registra una venta / muestra perfil del cliente.
     */
    public function index(): void {
        $codigo = $_GET['c'] ?? '';
        $token  = $_GET['t'] ?? '';

        if ($codigo && $token) {
            $clienteModel = new ClienteModel();
            $cliente = $clienteModel->findByCodigo($codigo);

            if ($cliente && $cliente['token'] === $token) {
                // Seteamos sesión completa de cliente como si fuera login
                $sessId = session_id();
                $clienteModel->updateSessionId($cliente['id'], $sessId);
                
                $_SESSION['id_usuario']     = $cliente['id'];
                $_SESSION['rol']            = 'cliente';
                $_SESSION['session_id']     = $sessId;
                
                $_SESSION['id_cliente']     = $cliente['id'];
                $_SESSION['nombre_cliente'] = $cliente['nombre'];
                $_SESSION['codigo_cliente'] = $codigo;
                $_SESSION['token_cliente']  = $token;

                $ventaModel = new VentaModel();
                $ventas = $ventaModel->getByCliente($cliente['id']);

                // Fetch approved recharges to show in history
                $stmtRecargas = Database::getConnection()->prepare("SELECT puntos, fecha, 'Recarga Aprobada' as detalle FROM recargas WHERE cliente_id = ? AND estado = 'aprobado' ORDER BY fecha DESC");
                $stmtRecargas->execute([$cliente['id']]);
                $recargasHistory = $stmtRecargas->fetchAll(PDO::FETCH_ASSOC);

                // Merge and sort both arrays by date descending
                $historial = array_merge($ventas, $recargasHistory);
                usort($historial, function($a, $b) {
                    return strtotime($b['fecha']) - strtotime($a['fecha']);
                });

                $this->render('scan/perfil_cliente', [
                    'cliente' => $cliente,
                    'ventas'  => $historial
                ]);
                return;
            }
        }

        // Escenario 2: El conductor escanea el QR del cliente
        $this->requireAuth();
        
        $opModel = new TipoOperacionModel();
        $operaciones = $opModel->getActive();

        $this->render('scan/index', ['operaciones' => $operaciones]);
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
        
        // Intentar buscar por código ("CLI-...")
        $cliente = $clienteModel->findByCodigo($codigo);
        
        // Si no se encuentra y es numérico de 8 dígitos, buscar por DNI
        if (!$cliente && preg_match('/^\d{8}$/', $codigo)) {
            $cliente = $clienteModel->findByDni($codigo);
        }

        // Buscar por RUC si son 11 dígitos
        if (!$cliente && preg_match('/^\d{11}$/', $codigo)) {
            $cliente = $clienteModel->findByRuc($codigo);
        }

        // Si no se encuentra y es numérico general, buscar por ID antiguo
        if (!$cliente && is_numeric($codigo) && strlen($codigo) < 8) {
            $cliente = $clienteModel->findById((int)$codigo);
        }

        if (!$cliente) {
            $this->json(['success' => false, 'message' => 'Cliente o Empresa no reconocido. Ingrese el DNI (8) o RUC (11 dígitos) o escanee un QR válido.']);
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
        $detalle   = trim($data['detalle'] ?? '');

        if (!$clienteId || !$puntos) {
            $this->json(['success' => false, 'message' => 'Datos incompletos.']);
        }

        $ventaModel   = new VentaModel();
        $clienteModel = new ClienteModel();

        // 1. Registrar "venta" con monto 0 (carga manual de puntos)
        $idVenta = $ventaModel->create($clienteId, $_SESSION['id_usuario'], 0, $puntos, $detalle);

        if ($idVenta) {
            // 2. Actualizar puntos totales del cliente
            $clienteModel->sumarPuntos($clienteId, $puntos);
            $this->json(['success' => true, 'message' => 'Puntos registrados correctamente.']);
        } else {
            $this->json(['success' => false, 'message' => 'Error al registrar puntos.']);
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

        // Obtener factor de puntos desde configuración
        $configModel = new ConfiguracionModel();
        $factor = (float) ($configModel->getValor('puntos_por_sol') ?? 1);

        $puntos = (int) floor($monto * $factor);

        $ventaModel   = new VentaModel();
        $clienteModel = new ClienteModel();

        $ventaModel->create($clienteId, $_SESSION['id_usuario'], $monto, $puntos, "Compra por monto: S/ $monto (+$puntos pts)");
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
