<?php
require_once __DIR__ . '/../models/CanjeModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class CanjeAdminController {
    private AuditoriaModel $audit;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->requireAdmin();
        $this->audit = new AuditoriaModel();
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'administrador'])) {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    public function index(): void {
        $model = new CanjeModel();
        $canjes = $model->getRecientes(100); 

        require_once __DIR__ . '/../views/canjes_admin.php';
    }

    public function actualizarEstado(): void {
        $id = (int) ($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? 'pendiente';

        if ($id > 0) {
            $model = new CanjeModel();
            
            // Obtener datos del canje para el log
            $sql = "SELECT c.*, p.nombre as producto_nombre, cl.nombre as cliente_nombre 
                    FROM canjes c 
                    JOIN premios p ON c.premio_id = p.id 
                    JOIN clientes cl ON c.cliente_id = cl.id 
                    WHERE c.id = ?";
            $stmt = Database::getConnection()->prepare($sql);
            $stmt->execute([$id]);
            $canje = $stmt->fetch();

            $result = $model->actualizarEstado($id, $estado);
            if ($result) {
                $statusText = strtoupper(str_replace('_', ' ', $estado));
                $this->audit->registrar($_SESSION['id_usuario'], 'ESTADO_CANJE', "Cambió a $statusText el canje de {$canje['producto_nombre']} para {$canje['cliente_nombre']}", 'CANJES');
                $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => "Estado actualizado a $statusText."];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar el estado.'];
            }
        }
        
        header('Location: ' . BASE_URL . 'canjes-admin');
        exit;
    }
}
