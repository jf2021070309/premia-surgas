<?php
require_once __DIR__ . '/../models/RecargaModel.php';

class RecargaAdminController {

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
        $model = new RecargaModel();
        $recargas = $model->getAllPending();
        $historial = $model->getAll(); 

        $this->render('recargas_admin', [
            'recargas' => $recargas,
            'historial' => $historial
        ]);
    }

    public function actualizarEstado(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir(BASE_URL . 'recargas-admin');

        $id = (int) ($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? 'pendiente';
        $validador_id = $_SESSION['id_usuario'];

        if ($id > 0 && ($estado === 'aprobado' || $estado === 'rechazado')) {
            $model = new RecargaModel();
            $result = $model->actualizarEstado($id, $estado, $validador_id);
            
            if ($result) {
                $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => "La recarga ha sido marcada como $estado."];
            } else {
                $error = $_SESSION['db_error'] ?? 'No se pudo procesar la recarga.';
                unset($_SESSION['db_error']);
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => $error];
            }
        }

        redirigir(BASE_URL . 'recargas-admin');
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }
}
