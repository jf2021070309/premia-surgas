<?php
require_once __DIR__ . '/../models/ClienteModel.php';

class TiendaController {

    public function index(): void {
        $this->requireAuth();
        
        $premios = [
            'bajo' => [
                'titulo' => 'Nivel Bajo',
                'puntos' => 'pocos puntos',
                'clase' => 'level-low',
                'items' => [
                    ['nombre' => 'Tazas', 'puntos' => 100, 'img' => 'taza.png'],
                    ['nombre' => 'Vasos', 'puntos' => 100, 'img' => 'vasos.png'],
                    ['nombre' => 'Platos', 'puntos' => 150, 'img' => 'platos.png'],
                    ['nombre' => 'Juego de 2 tazas con logo', 'puntos' => 250, 'img' => 'juegos de tazas.png'],
                    ['nombre' => 'Juego de 2 vasos', 'puntos' => 200, 'img' => '2 vasos.png'],
                ]
            ],
            'medio' => [
                'titulo' => 'Nivel Medio',
                'puntos' => 'cliente frecuente',
                'clase' => 'level-medium',
                'items' => [
                    ['nombre' => 'Licuadora', 'puntos' => 1200, 'img' => 'Licuadora.png'],
                    ['nombre' => 'Juego de ollas', 'puntos' => 2500, 'img' => 'Juego de ollas.png'],
                    ['nombre' => 'Cocina pequeña', 'puntos' => 3000, 'img' => 'Cocina.png'],
                    ['nombre' => 'Set de utensilios de cocina', 'puntos' => 800, 'img' => 'Set de utensilios de cocina.png'],
                    ['nombre' => 'Platos y vasos combinados', 'puntos' => 1000, 'img' => 'Platos y vasos combinados.png'],
                ]
            ],
            'alto' => [
                'titulo' => 'Nivel Alto',
                'puntos' => 'cliente fiel',
                'clase' => 'level-high',
                'items' => [
                    ['nombre' => 'Televisor pequeño (32")', 'puntos' => 8000, 'img' => 'Televisor pequeño.png'],
                    ['nombre' => 'Cocina mediana / eléctrica', 'puntos' => 7000, 'img' => 'cocina electrica.png'],
                    ['nombre' => 'Refrigera chica', 'puntos' => 10000, 'img' => 'Refrigera chica.png'],
                    ['nombre' => 'Licuadora profesional', 'puntos' => 4500, 'img' => 'Licuadora profesional.png'],
                    ['nombre' => 'Juego completo de ollas', 'puntos' => 5000, 'img' => 'Juego completo de ollas.png'],
                ]
            ],
            'vip' => [
                'titulo' => 'Nivel VIP',
                'puntos' => 'clientes muy fieles',
                'clase' => 'level-vip',
                'items' => [
                    ['nombre' => 'Laptop', 'puntos' => 25000, 'img' => 'laptop.png'],
                    ['nombre' => 'iPhone', 'puntos' => 35000, 'img' => 'iPhone.png'],
                    ['nombre' => 'Refrigera mediana/grande', 'puntos' => 20000, 'img' => 'Refrigera medianagrande.png'],
                    ['nombre' => 'Televisor 50" o más', 'puntos' => 18000, 'img' => 'Televisor50.png'],
                    ['nombre' => 'Cocina moderna / horno eléctrico', 'puntos' => 15000, 'img' => 'horno eléctrico.png'],
                ]
            ]
        ];

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
