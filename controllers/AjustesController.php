<?php
require_once __DIR__ . '/../models/TipoOperacionModel.php';
require_once __DIR__ . '/../models/PremioModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class AjustesController {

    public function index(): void {
        $this->requireAdmin();
        
        $opModel = new TipoOperacionModel();
        $premioModel = new PremioModel();
        $userModel = new UsuarioModel();

        $operaciones = $opModel->getAll();
        $premios     = $premioModel->getAll();
        $conductores = $userModel->getAllConductores();

        $this->render('ajustes/index', [
            'operaciones' => $operaciones,
            'premios'     => $premios,
            'conductores' => $conductores
        ]);
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }
}
