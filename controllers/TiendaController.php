<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/ConfiguracionModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class TiendaController {
    private AuditoriaModel $audit;

    public function __construct() {
        $this->audit = new AuditoriaModel();
    }

    public function index(): void {
        $this->requireAuth();
        
        require_once __DIR__ . '/../models/ClienteModel.php';
        $clienteModel = new ClienteModel();
        $id_cliente = $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? null;
        $cliente = $clienteModel->findById($id_cliente);

        require_once __DIR__ . '/../models/PremioModel.php';
        $premioModel = new PremioModel();
        $todos = $premioModel->getAllActive();

        $premios = [
            'bajo' => [
                'titulo' => 'Nivel Bajo',
                'puntos' => 'punto de entrada',
                'clase' => 'level-low',
                'items' => []
            ],
            'medio' => [
                'titulo' => 'Nivel Medio',
                'puntos' => 'cliente frecuente',
                'clase' => 'level-medium',
                'items' => []
            ],
            'alto' => [
                'titulo' => 'Nivel Alto',
                'puntos' => 'cliente fiel',
                'clase' => 'level-high',
                'items' => []
            ],
            'vip' => [
                'titulo' => 'Nivel VIP',
                'puntos' => 'clientes muy fieles',
                'clase' => 'level-vip',
                'items' => []
            ]
        ];

        foreach ($todos as $p) {
            if ($p['puntos'] <= 250) {
                $premios['bajo']['items'][] = $p;
            } elseif ($p['puntos'] <= 3000) {
                $premios['medio']['items'][] = $p;
            } elseif ($p['puntos'] <= 10000) {
                $premios['alto']['items'][] = $p;
            } else {
                $premios['vip']['items'][] = $p;
            }
        }

        $configModel = new ConfiguracionModel();
        $montoPorPunto = (float) ($configModel->getValor('monto_por_punto') ?? 0.05);
        $yapeQrImagen  = $configModel->getValor('yape_qr_imagen');
        $bbvaQrImagen  = $configModel->getValor('bbva_qr_imagen');

        $this->render('tienda', [
            'premios'      => $premios,
            'cliente'      => $cliente,
            'montoPorPunto'=> $montoPorPunto,
            'yapeQrImagen' => $yapeQrImagen,
            'bbvaQrImagen' => $bbvaQrImagen
        ]);
    }

    public function canjear(): void {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $this->requireAuth();

        $premioId     = (int) ($_POST['premio_id'] ?? 0);
        $puntosUsados = (int) ($_POST['puntos'] ?? 0);
        $monto        = (float) ($_POST['monto'] ?? 0);
        $comprobanteUrl = null;

        if (!$premioId) {
            echo json_encode(['success' => false, 'message' => 'Premio no seleccionado.']);
            return;
        }

        $metodoPago   = $_POST['metodo_pago'] ?? 'yape'; // 'yape' = Efectivo, 'deposito' = QR/Transferencia

        // Si es un canje híbrido (con pago)
        if ($monto > 0) {
            // Solo exigimos comprobante si es Depósito
            if ($metodoPago === 'deposito') {
                if (!isset($_FILES['comprobante']) || $_FILES['comprobante']['error'] !== UPLOAD_ERR_OK) {
                    echo json_encode(['success' => false, 'message' => 'Debes adjuntar el comprobante de pago para la opción de depósito.']);
                    return;
                }

                require_once __DIR__ . '/../helpers/UploadHelper.php';
                $comprobanteUrl = UploadHelper::uploadToImgBB($_FILES['comprobante']['tmp_name']);

                if (!$comprobanteUrl) {
                    echo json_encode(['success' => false, 'message' => 'Error al subir el comprobante a ImgBB. Inténtalo de nuevo.']);
                    return;
                }
            }
        }

        require_once __DIR__ . '/../models/CanjeModel.php';
        $canjeModel = new CanjeModel();

        $id_cliente   = $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? null;
        $result       = $canjeModel->registrar($id_cliente, $premioId, $puntosUsados, $monto, $comprobanteUrl);

        if ($result) {
            // AUDITORIA
            require_once __DIR__ . '/../models/PremioModel.php';
            $pModel = new PremioModel();
            $p = $pModel->findById($premioId);
            
            $metodoDesc = ($metodoPago === 'deposito') ? 'DEPÓSITO BBVA' : 'YAPE/EFECTIVO';
            $desc = "Cliente solicitó canje " . ($monto > 0 ? "HÍBRIDO ($metodoDesc)" : "TOTAL") . " de: {$p['nombre']}";
            
            if ($monto > 0) $desc .= " (S/ $monto + $puntosUsados pts)";
            else $desc .= " ($puntosUsados pts)";

            $this->audit->registrar($_SESSION['id_usuario'], 'SOLICITUD_CANJE', $desc, 'FIDELIZACION');

            $successMsg = 'Tu solicitud de canje ha sido registrada correctamente.';
            if ($monto > 0) {
                if ($metodoPago === 'deposito') {
                    $successMsg .= ' En espera de validación de pago.';
                } else {
                    $successMsg .= ' Recuerda pagar S/ ' . number_format($monto, 2) . ' en efectivo al recoger tu premio.';
                }
            }

            require_once __DIR__ . '/../models/PremioModel.php';
            $pModel = new PremioModel();
            $p = $pModel->findById($premioId);

            $_SESSION['flash'] = [
                'type'    => 'success',
                'title'   => '¡CANJE EXITOSO!',
                'message' => $successMsg,
                'prize_name' => $p['nombre'] ?? 'Premio',
                'prize_image' => $p['imagen'] ?? ''
            ];

            echo json_encode([
                'success' => true,
                'message' => $successMsg
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo procesar el canje. Verifica tu saldo de puntos o el stock del premio.']);
        }
    }

    public function historial(): void {
        $this->requireAuth();

        require_once __DIR__ . '/../models/ClienteModel.php';
        $clienteModel = new ClienteModel();
        $id_cliente = $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? null;
        $cliente = $clienteModel->findById($id_cliente);

        require_once __DIR__ . '/../models/CanjeModel.php';
        $canjeModel = new CanjeModel();
        $canjes = $canjeModel->getByCliente($id_cliente);

        $this->render('tienda_historial', [
            'cliente' => $cliente,
            'canjes' => $canjes
        ]);
    }

    public function recargar(): void {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        if (ob_get_length()) ob_clean();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');

        require_once __DIR__ . '/../config/Database.php';
        $db = Database::getConnection();

        // Asegurar que la tabla existe (Auto-migración)
        $db->exec("CREATE TABLE IF NOT EXISTS recargas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cliente_id INT,
            puntos INT,
            monto DECIMAL(10,2),
            comprobante VARCHAR(255),
            estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
            fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            fecha_validacion TIMESTAMP NULL,
            validado_por INT,
            FOREIGN KEY (cliente_id) REFERENCES clientes(id),
            FOREIGN KEY (validado_por) REFERENCES usuarios(id)
        )");

        $puntosRaw = $_POST['puntos'] ?? '0';
        $puntos = (int) preg_replace('/[^0-9]/', '', (string)$puntosRaw);
        $monto = (float)($_POST['monto'] ?? 0);
        $id_cliente = $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? null;

        if (!$id_cliente || !$puntos || !isset($_FILES['comprobante'])) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos de la recarga']);
            return;
        }

        $uploadDir = __DIR__ . '/../assets/uploads/comprobantes/';
        if (!is_dir($uploadDir)) {
            if (!@mkdir($uploadDir, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'Error de permisos al crear directorio de subida']);
                return;
            }
        }

        $fileExtension = pathinfo($_FILES['comprobante']['name'], PATHINFO_EXTENSION);
        $fileName = 'recarga_' . time() . '_' . $id_cliente . '.' . $fileExtension;
        $targetFile = $uploadDir . $fileName;

        if (@move_uploaded_file($_FILES['comprobante']['tmp_name'], $targetFile)) {
            try {
                $stmt = $db->prepare("INSERT INTO recargas (cliente_id, puntos, monto, comprobante) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_cliente, $puntos, $monto, $fileName]);
                
                // AUDITORIA
                $this->audit->registrar($_SESSION['id_usuario'], 'ENVIO_COMPROBANTE', "Cliente envió comprobante por $puntos puntos (S/ $monto)", 'RECARGAS');

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error BD: ' . $e->getMessage()]);
            }
        } else {
            $errCode = $_FILES['comprobante']['error'] ?? 'unknown';
            $msg = 'No se pudo guardar el archivo en el servidor.';
            if ($errCode === 1) $msg .= ' El archivo es demasiado grande (limite PHP).';
            if ($errCode === 3) $msg .= ' La subida fue parcial.';
            if ($errCode === 4) $msg .= ' No se recibió ningún archivo.';
            if ($errCode === 6) $msg .= ' Falta carpeta temporal.';
            if ($errCode === 7) $msg .= ' Error al escribir en disco.';
            
            echo json_encode(['success' => false, 'message' => $msg . " (Error: $errCode)"]);
        }
    }

    public function checkPendientes(): void {
        $this->requireAuth();
        $id_cliente = $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? null;
        
        require_once __DIR__ . '/../config/Database.php';
        $db = Database::getConnection();

        // Auto-migración también aquí por si acaso
        $db->exec("CREATE TABLE IF NOT EXISTS recargas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cliente_id INT,
            puntos INT,
            monto DECIMAL(10,2),
            comprobante VARCHAR(255),
            estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
            fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            fecha_validacion TIMESTAMP NULL,
            validado_por INT,
            FOREIGN KEY (cliente_id) REFERENCES clientes(id),
            FOREIGN KEY (validado_por) REFERENCES usuarios(id)
        )");

        $stmt = $db->prepare("SELECT COUNT(*) FROM recargas WHERE cliente_id = ? AND estado = 'pendiente'");
        $stmt->execute([$id_cliente]);
        $count = (int)$stmt->fetchColumn();

        header('Content-Type: application/json');
        echo json_encode(['pendientes' => $count > 0]);
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function requireAuth(): void {
        if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['id_cliente'])) {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            if ($isAjax || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Sesión expirada']);
                exit;
            }
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
}
