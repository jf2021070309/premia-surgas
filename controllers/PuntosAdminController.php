<?php
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../models/ConfiguracionModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class PuntosAdminController {
    private AuditoriaModel $audit;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->requireAdmin();
        $this->audit = new AuditoriaModel();
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    public function index(): void {
        $model = new VentaModel();
        $ventas = $model->getPendientes();
        $historial = $model->getAllAdmin(); 

        $this->render('puntos_admin', [
            'ventas' => $ventas,
            'historial' => $historial
        ]);
    }

    public function actualizarEstado(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir(BASE_URL . 'puntos-admin');

        $id = (int) ($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? 'pendiente';
        $validador_id = $_SESSION['id_usuario'];

        if ($id > 0 && ($estado === 'aprobado' || $estado === 'rechazado')) {
            $model = new VentaModel();
            
            // Info para auditoria
            $venta = $model->getById($id);
            
            if ($model->validar($id, $estado, $validador_id)) {
                $statusText = strtoupper($estado);
                $this->audit->registrar($_SESSION['id_usuario'], 'MODERAR_PUNTOS', "$statusText la suma de {$venta['puntos']} puntos para el cliente #{$venta['cliente_id']} por el operador {$venta['conductor_id']}", 'RECARGAS');
                $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => "La operación ha sido marcada como $estado."];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo procesar la asignación de puntos.'];
            }
        }

        redirigir(BASE_URL . 'puntos-admin');
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }
}
