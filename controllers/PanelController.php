<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/VentaModel.php';

class PanelController {

    public function index(): void {
        $this->requireAuth();
        $model  = new ClienteModel();
        $totales = [
            'clientes' => count($model->getAll()),
        ];
        $this->render('panel', ['totales' => $totales]);
    }

    // ── helpers ──────────────────────────────────────────────────

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
