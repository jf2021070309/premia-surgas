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

    public function liveNotifications(): void {
        header('Content-Type: application/json');
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false]);
            exit;
        }

        $db = Database::getConnection();
        
        // Pendientes de recargas
        $stmt_recargas = $db->prepare("SELECT r.id, r.puntos, r.monto, c.nombre as cliente_nombre, r.fecha FROM recargas r JOIN clientes c ON r.cliente_id = c.id WHERE r.estado = 'pendiente' ORDER BY r.id DESC");
        $stmt_recargas->execute();
        $recargas = $stmt_recargas->fetchAll(PDO::FETCH_ASSOC);

        // Pendientes de canjes
        $stmt_canjes = $db->prepare("SELECT cj.id, p.nombre as premio_nombre, cl.nombre as cliente_nombre, cj.fecha FROM canjes cj JOIN premios p ON cj.premio_id = p.id JOIN clientes cl ON cj.cliente_id = cl.id WHERE cj.estado = 'pendiente' ORDER BY cj.id DESC");
        $stmt_canjes->execute();
        $canjes = $stmt_canjes->fetchAll(PDO::FETCH_ASSOC);

        if (ob_get_length()) ob_clean();
        echo json_encode([
            'success'  => true,
            'recargas' => $recargas,
            'canjes'   => $canjes,
            'total'    => count($recargas) + count($canjes)
        ]);
        exit;
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
