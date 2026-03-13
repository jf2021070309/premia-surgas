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
