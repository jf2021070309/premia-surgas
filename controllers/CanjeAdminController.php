<?php
require_once __DIR__ . '/../models/CanjeModel.php';

class CanjeAdminController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->requireAdmin();
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    public function index(): void {
        $model = new CanjeModel();
        // Usamos el método que ya tiene pero con un límite más alto o quizás uno nuevo si fuera necesario
        // Pero para gestión, mejor traer todos los recientes o implementar listado completo
        $canjes = $model->getRecientes(100); 

        require_once __DIR__ . '/../views/canjes_admin.php';
    }

    public function actualizarEstado(): void {
        $id = (int) ($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? 'pendiente';

        if ($id > 0) {
            $model = new CanjeModel();
            $result = $model->actualizarEstado($id, $estado);
            if ($result) {
                $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => 'Estado actualizado.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar el estado.'];
            }
        }
        
        header('Location: ' . BASE_URL . 'canjes-admin');
        exit;
    }
}
