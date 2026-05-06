<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../models/ConfiguracionModel.php';
require_once __DIR__ . '/../models/TipoOperacionModel.php';
require_once __DIR__ . '/../models/CanjeModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../models/IncentivoModel.php';
require_once __DIR__ . '/../models/AfiliadoAnuncioModel.php';
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
                $db = Database::getConnection();
                $stmtRecargas = $db->prepare("SELECT puntos, fecha, 'Recarga Aprobada' as detalle FROM recargas WHERE cliente_id = ? AND estado = 'aprobado' ORDER BY fecha DESC");
                $stmtRecargas->execute([$cliente['id']]);
                $recargasHistory = $stmtRecargas->fetchAll(PDO::FETCH_ASSOC);

                // Fetch redeemed vouchers to show in history
                $stmtVales = $db->prepare("SELECT 0 as puntos, usado_fecha as fecha, CONCAT('Vale Canjeado: ', descripcion) as detalle, 'VALE' as tipo_ext FROM incentivos_vales WHERE cliente_id = ? AND estado = 'usado' ORDER BY usado_fecha DESC");
                $stmtVales->execute([$cliente['id']]);
                $valesHistory = $stmtVales->fetchAll(PDO::FETCH_ASSOC);

                // Merge and sort all arrays by date descending
                $historial = array_merge($ventas, $recargasHistory, $valesHistory);
                usort($historial, function($a, $b) {
                    return strtotime($b['fecha']) - strtotime($a['fecha']);
                });

                // Fetch redemption history
                $canjeModel = new CanjeModel();
                $canjes = $canjeModel->getByCliente($cliente['id']);

                $isDefaultPassword = (
                    ($cliente['password'] === hash('sha256', $cliente['dni'] ?? '')) || 
                    ($cliente['password'] === hash('sha256', $cliente['ruc'] ?? ''))
                );

                // Fetch active announcements for carousel
                $anuncioModel = new AfiliadoAnuncioModel();
                $anuncios = $anuncioModel->getAllActive();

                $this->render('scan/perfil_cliente', [
                    'cliente'  => $cliente,
                    'ventas'   => $historial,
                    'canjes'   => $canjes,
                    'anuncios' => $anuncios,
                    'isDefaultPassword' => $isDefaultPassword
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
        $items     = $data['items'] ?? []; 

        if (!$clienteId || !$puntos) {
            $this->json(['success' => false, 'message' => 'Datos incompletos.']);
        }

        $ventaModel   = new VentaModel();
        $clienteModel = new ClienteModel();
        $rol = $_SESSION['rol'] ?? '';

        // Si es admin, se aprueba automáticamente. Si es conductor, queda pendiente.
        $estado = ($rol === 'admin') ? 'aprobado' : 'pendiente';

        // 1. Registrar "venta"
        $idVenta = $ventaModel->create($clienteId, $_SESSION['id_usuario'], 0, $puntos, $detalle, $items, $estado);

        if ($idVenta) {
            $message = 'Puntos registrados correctamente.';
            
            if ($estado === 'aprobado') {
                // 2. Actualizar puntos totales del cliente (solo si está aprobado)
                $clienteModel->sumarPuntos($clienteId, $puntos);

                // 3. Evaluar reglas de incentivos
                $incentivoModel = new IncentivoModel();
                $incentivoModel->evaluarMetas($clienteId);
            } else {
                $message = 'Puntos registrados. Pendiente de aprobación por administración.';
            }

            // AUDITORIA
            $c = $clienteModel->findById($clienteId);
            $audit = new AuditoriaModel();
            $accion = ($estado === 'aprobado') ? 'CARGA_PUNTOS' : 'SOLICITUD_PUNTOS_PENDIENTE';
            $audit->registrar($_SESSION['id_usuario'], $accion, "Registró $puntos puntos a {$c['nombre']} ($detalle). Estado: $estado", 'RECARGAS');
            
            $this->json(['success' => true, 'message' => $message]);
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
        $rol = $_SESSION['rol'] ?? '';

        $estado = ($rol === 'admin') ? 'aprobado' : 'pendiente';

        $ventaModel->create($clienteId, $_SESSION['id_usuario'], $monto, $puntos, "Compra por monto: S/ $monto (+$puntos pts)", [], $estado);
        
        $message = "Puntos registrados correctamente.";
        if ($estado === 'aprobado') {
            $clienteModel->sumarPuntos($clienteId, $puntos);

            // Evaluar reglas de incentivos
            $incentivoModel = new IncentivoModel();
            $incentivoModel->evaluarMetas($clienteId);
        } else {
            $message = "Puntos registrados. Pendiente de aprobación.";
        }

        // AUDITORIA
        $c = $clienteModel->findById($clienteId);
        $audit = new AuditoriaModel();
        $accion = ($estado === 'aprobado') ? 'VENTA_PUNTOS' : 'SOLICITUD_VENTA_PENDIENTE';
        $audit->registrar($_SESSION['id_usuario'], $accion, "Asignó $puntos puntos por venta de S/ $monto a {$c['nombre']}. Estado: $estado", 'RECARGAS');

        echo json_encode(['success' => true, 'puntos_sumados' => $puntos, 'message' => $message]);
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
