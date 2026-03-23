<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../models/CanjeModel.php';

class PanelController {

    public function index(): void {
        $this->requireAuth();
        
        if ($_SESSION['rol'] === 'cliente' && isset($_SESSION['codigo_cliente'])) {
            header('Location: ' . BASE_URL . 'scan?c=' . $_SESSION['codigo_cliente'] . '&t=' . $_SESSION['token_cliente']);
            exit;
        }
        $model   = new ClienteModel();
        $totales = [
            'clientes' => count($model->getAll()),
        ];

        $notificaciones_recargas = [];
        if ($_SESSION['rol'] === 'admin') {
            $canjeModel = new CanjeModel();
            $notificaciones = $canjeModel->getRecientes(5);

            // Nuevas Recargas Pendientes
            $db = Database::getConnection();
            $notificaciones_recargas = $db->query("SELECT r.*, c.nombre as cliente_nombre FROM recargas r JOIN clientes c ON r.cliente_id = c.id WHERE r.estado = 'pendiente' ORDER BY r.fecha DESC LIMIT 5")->fetchAll();
        }

        $this->render('panel', [
            'totales'        => $totales,
            'notificaciones' => $notificaciones,
            'notificaciones_recargas' => $notificaciones_recargas
        ]);
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
