<?php
require_once __DIR__ . '/../models/ClienteModel.php';

class TiendaController {

    public function index(): void {
        // Permitir acceso público para que los clientes vean los premios
        
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

        $this->render('tienda', ['premios' => $premios]);
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function requireAuth(): void {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
}
