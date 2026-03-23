<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/ConfiguracionModel.php';

class TiendaController {

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

        $this->render('tienda', [
            'premios' => $premios,
            'cliente' => $cliente,
            'montoPorPunto' => $montoPorPunto
        ]);
    }

    public function canjear(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir(BASE_URL . 'tienda');
        $this->requireAuth();

        $premioId     = (int) ($_POST['premio_id'] ?? 0);
        $tipoCanje    = $_POST['tipo'] ?? 'total'; // 'total' o 'descuento'
        $puntosUsados = (int) ($_POST['puntos'] ?? 0);
        $monto        = (float) ($_POST['monto'] ?? 0);

        if (!$premioId) {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'Premio no seleccionado.'];
            redirigir(BASE_URL . 'tienda');
        }

        require_once __DIR__ . '/../models/CanjeModel.php';
        $canjeModel = new CanjeModel();

        $id_cliente = $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? null;
        $result = $canjeModel->registrar($id_cliente, $premioId, $puntosUsados, $monto);

        if ($result) {
            $_SESSION['flash'] = [
                'type' => 'success', 
                'title' => '¡Éxito!', 
                'message' => 'El canje se ha registrado correctamente.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error', 
                'title' => 'Error', 
                'message' => 'No se pudo procesar el canje. Verifica tu saldo de puntos.'
            ];
        }

        redirigir(BASE_URL . 'tienda');
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

        $puntos = (int)($_POST['puntos'] ?? 0);
        $monto = (float)($_POST['monto'] ?? 0);
        $id_cliente = $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? null;

        if (!$id_cliente || !$puntos || !isset($_FILES['comprobante'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos insuficientes']);
            return;
        }

        $uploadDir = __DIR__ . '/../assets/uploads/comprobantes/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileExtension = pathinfo($_FILES['comprobante']['name'], PATHINFO_EXTENSION);
        $fileName = 'recarga_' . time() . '_' . $id_cliente . '.' . $fileExtension;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $targetFile)) {
            require_once __DIR__ . '/../config/Database.php';
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO recargas (cliente_id, puntos, monto, comprobante) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_cliente, $puntos, $monto, $fileName]);

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error al subir comprobante']);
        }
    }

    public function checkPendientes(): void {
        $this->requireAuth();
        $id_cliente = $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? null;
        
        require_once __DIR__ . '/../config/Database.php';
        $db = Database::getConnection();
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
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
}
