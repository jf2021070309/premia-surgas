<?php
require_once __DIR__ . '/../models/ConfiguracionModel.php';

class ConfiguracionController {

    public function index(): void {
        $this->requireAdmin();
        $model = new ConfiguracionModel();
        $configuraciones = $model->getAll();
        $this->render('configuraciones/index', ['configuraciones' => $configuraciones]);
    }

    public function update(): void {
        $this->requireAdmin();
        $model = new ConfiguracionModel();
        
        $exito = true;
        if (isset($_POST['config'])) {
            foreach ($_POST['config'] as $id => $valor) {
                if (!$model->updateById((int)$id, $valor)) {
                    $exito = false;
                }
            }
        }

        if ($exito) {
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Configuraciones actualizadas correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'Algunas configuraciones no pudieron actualizarse.'];
        }

        header('Location: ' . BASE_URL . 'configuraciones');
        exit;
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
