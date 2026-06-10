<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../models/ConfiguracionModel.php';
require_once __DIR__ . '/../models/TipoOperacionModel.php';
require_once __DIR__ . '/../models/CanjeModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../models/IncentivoModel.php';
require_once __DIR__ . '/../models/AfiliadoAnuncioModel.php';
require_once __DIR__ . '/../helpers/SmsService.php';
require_once __DIR__ . '/../config/config.php';



class ScanController
{

    /**
     * GET /scan?c=CLI-000001&t=TOKEN
     * Valida QR y registra una venta / muestra perfil del cliente.
     */
    public function index(): void
    {
        $codigo = $_GET['c'] ?? '';
        $token = $_GET['t'] ?? '';

        $sessUserId = $_SESSION['id_usuario'] ?? null;
        $sessRol = $_SESSION['rol'] ?? '';

        // Escenario 1: Conductor/Admin escaneó un QR y tiene un token
        if (($sessRol === 'conductor' || $sessRol === 'admin') && $token) {
            $opModel = new TipoOperacionModel();
            $operaciones = $opModel->getActive();
            $this->render('scan/index', [
                'operaciones' => $operaciones,
                'autoScanToken' => $token
            ]);
            return;
        }

        // Escenario 2: Es un cliente autenticado por formulario/sesión
        if ($sessRol === 'cliente') {
            $clienteModel = new ClienteModel();
            $id_cliente = $_SESSION['id_cliente'] ?? $sessUserId;
            $cliente = $clienteModel->findById($id_cliente);
            if ($cliente) {
                $this->renderFullProfile($cliente, false); // Acceso total
                return;
            }
        }

        // Escenario 3: Invitado abre un link de QR/Token directo (?t=...)
        if ($token) {
            $clienteModel = new ClienteModel();
            if ($codigo) {
                $cliente = $clienteModel->findByCodigo($codigo);
            } else {
                $cliente = $clienteModel->buscarPorToken($token);
            }

            if ($cliente && $cliente['token'] === $token) {
                // Se renderiza en modo SOLO LECTURA, sin iniciar sesión.
                $this->renderFullProfile($cliente, true); // Acceso restringido (read-only)
                return;
            }
        }

        // Escenario 4: Si no hay sesión válida
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Si es conductor/admin y entra normal
        if ($sessRol === 'conductor' || $sessRol === 'admin') {
            $opModel = new TipoOperacionModel();
            $operaciones = $opModel->getActive();
            $this->render('scan/index', ['operaciones' => $operaciones]);
            return;
        }

        // Si es cliente y entra normal a /scan sin token, mostrar su perfil
        if ($sessRol === 'cliente') {
            $clienteModel = new ClienteModel();
            $cliente = $clienteModel->findById($_SESSION['id_cliente'] ?? $_SESSION['id_usuario']);
            if ($cliente) {
                $this->renderFullProfile($cliente, false);
                return;
            }
        }

        header('Location: ' . BASE_URL . 'login');
        exit;
    }

    /**
     * Renderiza el perfil completo del cliente.
     */
    private function renderFullProfile(array $cliente, bool $readonly): void
    {
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
        usort($historial, function ($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });

        // Fetch redemption history
        $canjeModel = new CanjeModel();
        $canjes = $canjeModel->getByCliente($cliente['id']);

        $isDefaultPassword = false;
        if (!$readonly) {
            $isDefaultPassword = (
                ($cliente['password'] === hash('sha256', $cliente['dni'] ?? '')) ||
                ($cliente['password'] === hash('sha256', $cliente['ruc'] ?? ''))
            );
        }

        // Fetch active announcements for carousel
        $anuncioModel = new AfiliadoAnuncioModel();
        $anuncios = $anuncioModel->getAllActive();

        $this->render('scan/perfil_cliente', [
            'cliente' => $cliente,
            'ventas' => $historial,
            'canjes' => $canjes,
            'anuncios' => $anuncios,
            'isDefaultPassword' => $isDefaultPassword,
            'readonly' => $readonly
        ]);
    }

    /**
     * API: POST /scan/buscar
     * Recibe código QR (CLI-000001) y devuelve datos del cliente.
     */
    public function buscar(): void
    {
        $this->requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);
        $codigo = trim($data['codigo'] ?? '');

        if (!$codigo) {
            $this->json(['success' => false, 'message' => 'Código no proporcionado.']);
        }

        $clienteModel = new ClienteModel();
        $cliente = null;

        // Intentar buscar por token primero (si tiene 64 hex chars)
        if (strlen($codigo) === 64 && ctype_xdigit($codigo)) {
            $cliente = $clienteModel->buscarPorToken($codigo);
        }

        // Intentar buscar por código ("CLI-...")
        if (!$cliente) {
            $cliente = $clienteModel->findByCodigo($codigo);
        }

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
            $cliente = $clienteModel->findById((int) $codigo);
        }

        if (!$cliente) {
            $this->json(['success' => false, 'message' => 'Cliente o Empresa no reconocido. Ingrese el DNI (8) o RUC (11 dígitos) o escanee un QR válido.']);
        }

        $rawCelular = $cliente['celular'] ?? '';
        $maskedCelular = '';
        if (!empty($rawCelular)) {
            $len = strlen($rawCelular);
            if ($len >= 9) {
                $maskedCelular = substr($rawCelular, 0, 3) . '***' . substr($rawCelular, -3);
            } elseif ($len > 4) {
                $maskedCelular = substr($rawCelular, 0, 2) . str_repeat('*', $len - 4) . substr($rawCelular, -2);
            } else {
                $maskedCelular = str_repeat('*', $len);
            }
        }

        $this->json([
            'success' => true,
            'cliente' => [
                'id' => $cliente['id'],
                'nombre' => $cliente['nombre'],
                'celular' => $maskedCelular
            ]
        ]);
    }

    /**
     * API: POST /scan/registrar
     * Guarda el movimiento de puntos.
     */
    public function registrar(): void
    {
        $this->requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);

        $clienteId = (int) ($data['cliente_id'] ?? 0);
        $puntos = (int) ($data['puntos'] ?? 0);
        $monto = (float) ($data['monto'] ?? 0);
        $detalle = trim($data['detalle'] ?? '');
        $items = $data['items'] ?? [];
        $recomendadorCodigo = trim($data['recomendador_codigo'] ?? '');

        if (!$clienteId || !$puntos) {
            $this->json(['success' => false, 'message' => 'Datos incompletos.']);
        }

        $ventaModel = new VentaModel();
        $clienteModel = new ClienteModel();
        $rol = $_SESSION['rol'] ?? '';

        // Si es admin, se aprueba automáticamente. Si es conductor, queda pendiente.
        $estado = ($rol === 'admin') ? 'aprobado' : 'pendiente';

        // Determinar puntos para Comprador y Recomendador
        $puntosComprador = $puntos;
        $puntosRecomendador = 0;
        $recomendador = null;

        if (!empty($recomendadorCodigo)) {
            if (strlen($recomendadorCodigo) === 64 && ctype_xdigit($recomendadorCodigo)) {
                $recomendador = $clienteModel->buscarPorToken($recomendadorCodigo);
            }
            if (!$recomendador) {
                $recomendador = $clienteModel->findByCodigo($recomendadorCodigo);
            }
            if (!$recomendador && preg_match('/^\d{8}$/', $recomendadorCodigo)) {
                $recomendador = $clienteModel->findByDni($recomendadorCodigo);
            }
            if (!$recomendador && preg_match('/^\d{11}$/', $recomendadorCodigo)) {
                $recomendador = $clienteModel->findByRuc($recomendadorCodigo);
            }

            if ($recomendador && $recomendador['id'] !== $clienteId) {
                $puntosComprador = 0;
                $puntosRecomendador = $puntos;
            }
        }

        // 1. Registrar "venta" para el comprador
        $idVenta = $ventaModel->create($clienteId, $_SESSION['id_usuario'], $monto, $puntosComprador, $detalle, $items, $estado);

        if ($idVenta) {
            $message = 'Puntos registrados correctamente.';

            // Obtener datos del cliente para SMS y Auditoría
            $c = $clienteModel->findById($clienteId);

            if ($estado === 'aprobado') {
                // 2. Actualizar puntos totales del cliente (solo si está aprobado)
                $clienteModel->sumarPuntos($clienteId, $puntosComprador);

                // 3. Evaluar reglas de incentivos
                $incentivoModel = new IncentivoModel();
                $incentivoModel->evaluarMetas($clienteId);

                // --- SMS Gateway ---
                if ($puntosComprador > 0 && !empty($c['celular'])) {
                    $msg = "Hola {$c['nombre']}, se te han asignado $puntosComprador puntos. ¡Sigue acumulando para grandes premios en Premia Surgas!";
                    SmsService::send($c['celular'], $msg);
                }

                // 4. Bono a Recomendador si existe
                if ($puntosRecomendador > 0 && $recomendador) {
                    $clienteModel->sumarPuntos($recomendador['id'], $puntosRecomendador);
                    
                    // Registrar el movimiento en el historial de ventas para que se refleje en su Actividad
                    $ventaModel->create($recomendador['id'], $_SESSION['id_usuario'], 0, $puntosRecomendador, "Bono por Recomendar venta a {$c['nombre']}", [], 'aprobado');

                    if (!empty($recomendador['celular'])) {
                        $msgReco = "¡Felicidades {$recomendador['nombre']}! Ganaste $puntosRecomendador puntos porque un vecino que recomendaste hizo un pedido.";
                        SmsService::send($recomendador['celular'], $msgReco);
                    }
                    $audit = new AuditoriaModel();
                    $audit->registrar($_SESSION['id_usuario'], 'BONO_RECOMENDACION', "Otorgó $puntosRecomendador pts a {$recomendador['nombre']} por recomendar venta a {$c['nombre']}", 'RECARGAS');
                }
            } else {
                $message = 'Puntos registrados. Pendiente de aprobación por administración.';
            }

            // AUDITORIA
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
    public function venta(): void
    {
        if (!isset($_SESSION['id_usuario'])) {
            $this->json(['success' => false, 'message' => 'No autenticado.']);
        }

        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $clienteId = (int) ($data['cliente_id'] ?? 0);
        $monto = (float) ($data['monto'] ?? 0);

        if (!$clienteId || $monto <= 0) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
            exit;
        }

        // Obtener factor de puntos desde configuración
        $configModel = new ConfiguracionModel();
        $factor = (float) ($configModel->getValor('puntos_por_sol') ?? 1);

        $puntos = (int) floor($monto * $factor);

        $ventaModel = new VentaModel();
        $clienteModel = new ClienteModel();
        $rol = $_SESSION['rol'] ?? '';

        $estado = ($rol === 'admin') ? 'aprobado' : 'pendiente';

        $ventaModel->create($clienteId, $_SESSION['id_usuario'], $monto, $puntos, "Compra por monto: S/ $monto (+$puntos pts)", [], $estado);

        $message = "Puntos registrados correctamente.";

        // Obtener datos del cliente para SMS y Auditoría
        $c = $clienteModel->findById($clienteId);

        if ($estado === 'aprobado') {
            $clienteModel->sumarPuntos($clienteId, $puntos);

            // Evaluar reglas de incentivos
            $incentivoModel = new IncentivoModel();
            $incentivoModel->evaluarMetas($clienteId);

            // --- SMS Gateway ---
            if (!empty($c['celular'])) {
                $msg = "Hola {$c['nombre']}, ganaste $puntos puntos por tu compra de S/ " . number_format($monto, 2) . ". ¡Gracias por preferir Premia Surgas!";
                SmsService::send($c['celular'], $msg);
            }
        } else {
            $message = "Puntos registrados. Pendiente de aprobación.";
        }

        // AUDITORIA
        $audit = new AuditoriaModel();
        $accion = ($estado === 'aprobado') ? 'VENTA_PUNTOS' : 'SOLICITUD_VENTA_PENDIENTE';
        $audit->registrar($_SESSION['id_usuario'], $accion, "Asignó $puntos puntos por venta de S/ $monto a {$c['nombre']}. Estado: $estado", 'RECARGAS');

        echo json_encode(['success' => true, 'puntos_sumados' => $puntos, 'message' => $message]);
        exit;
    }

    // ── helpers ──────────────────────────────────────────────────

    private function requireAuth(): void
    {
        if (!isset($_SESSION['id_usuario'])) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Sesión expirada.']);
            }
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    private function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
