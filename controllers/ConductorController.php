<?php
require_once __DIR__ . '/../models/UsuarioModel.php';

class ConductorController {
    
    public function index(): void {
        $this->requireAdmin();
        $model = new UsuarioModel();
        $conductores = $model->getAllConductores();
        $this->render('conductores/index', ['conductores' => $conductores]);
    }

    public function nuevo(): void {
        $this->requireAdmin();
        $this->render('conductores/formulario', ['titulo' => 'Nuevo Conductor', 'conductor' => null]);
    }

    public function create(): void {
        $this->requireAdmin();
        $model = new UsuarioModel();
        
        $data = [
            'nombre'   => $_POST['nombre'] ?? '',
            'usuario'      => $_POST['usuario'] ?? '',
            'password'     => $_POST['password'] ?? '',
            'rol'          => 'conductor',
            'estado'       => (int)($_POST['estado'] ?? 1),
            'departamento' => $_POST['departamento'] ?? null,
        ];

        if ($model->create($data)) {
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Conductor registrado correctamente.'];
            $this->redirect('conductores');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo registrar al conductor.'];
            $this->redirect('conductores');
        }
    }

    public function editar(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $model = new UsuarioModel();
        $conductor = $model->findById($id);
        
        if (!$conductor) {
            $this->redirect('conductores');
        }

        $this->render('conductores/formulario', ['titulo' => 'Editar Conductor', 'conductor' => $conductor]);
    }

    public function update(): void {
        $this->requireAdmin();
        $id = (int)($_POST['id'] ?? 0);
        $model = new UsuarioModel();
        
        $data = [
            'nombre'       => $_POST['nombre'] ?? '',
            'usuario'      => $_POST['usuario'] ?? '',
            'estado'       => (int)($_POST['estado'] ?? 1),
            'departamento' => $_POST['departamento'] ?? null,
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        if ($model->update($id, $data)) {
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Información del conductor actualizada.'];
            $this->redirect('conductores');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar la información.'];
            $this->redirect('conductores');
        }
    }

    public function delete(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $model = new UsuarioModel();
        if ($model->setEstado($id, 0)) {
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Hecho!', 'message' => 'Conductor inactivado correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo inactivar al conductor.'];
        }
        $this->redirect('conductores');
    }

    // ── helpers ──────────────────────────────────────────────────

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function redirect(string $path): void {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }
}
