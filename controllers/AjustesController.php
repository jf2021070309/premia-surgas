<?php
require_once __DIR__ . '/../models/TipoOperacionModel.php';
require_once __DIR__ . '/../models/PremioModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/ConfiguracionModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class AjustesController {

    public function index(): void {
        $this->requireAdmin();
        
        $opModel = new TipoOperacionModel();
        $premioModel = new PremioModel();
        $userModel = new UsuarioModel();
        $configModel = new ConfiguracionModel();

        $operaciones = $opModel->getAll();
        $premios     = $premioModel->getAll();
        $conductores = $userModel->getAllConductores();
        $montoPorPunto = $configModel->getValor('monto_por_punto') ?? 0.05;

        $this->render('ajustes/index', [
            'operaciones' => $operaciones,
            'premios'     => $premios,
            'conductores' => $conductores,
            'montoPorPunto' => $montoPorPunto
        ]);
    }

    public function updatePuntos(): void {
        $this->requireAdmin();
        
        $valor = $_POST['valor'] ?? '0.05';
        $configModel = new ConfiguracionModel();
        
        if ($configModel->update('monto_por_punto', $valor)) {
            $audit = new AuditoriaModel();
            $audit->registrar($_SESSION['id_usuario'], 'ACTUALIZAR_CONFIG', "Actualizó monto por punto a: $valor", 'AJUSTES');
            
            $_SESSION['flash'] = [
                'type' => 'success',
                'title' => '¡Actualizado!',
                'message' => 'La regla de puntaje ha sido actualizada correctamente.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'No se pudo actualizar la configuración.'
            ];
        }
        
        header('Location: ' . BASE_URL . 'ajustes');
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
