<?php
class FiseController {
    
    public function __construct() {
        // Initialize mock data if not exists
        if (!isset($_SESSION['fise_data'])) {
            $_SESSION['fise_data'] = [
                1 => ['id' => 1, 'codigo' => 'FISE-1234', 'precio' => 20, 'puntos' => 200, 'estado' => 1],
                2 => ['id' => 2, 'codigo' => 'FISE-5678', 'precio' => 40, 'puntos' => 400, 'estado' => 1]
            ];
            $_SESSION['fise_ai'] = 3; // auto-increment
        }
    }
    
    public function index(): void {
        $this->requireAdmin();
        $fises = $_SESSION['fise_data'];
        $this->render('fise/index', ['fises' => $fises]);
    }

    public function nuevo(): void {
        $this->requireAdmin();
        $this->render('fise/formulario', ['titulo' => 'Nuevo FISE', 'fise' => null]);
    }

    public function create(): void {
        $this->requireAdmin();
        
        $id = $_SESSION['fise_ai']++;
        $data = [
            'id' => $id,
            'codigo' => $_POST['codigo'] ?? '',
            'precio' => (float)($_POST['precio'] ?? 0),
            'puntos' => (int)($_POST['puntos'] ?? 0),
            'estado' => (int)($_POST['estado'] ?? 1),
        ];

        $_SESSION['fise_data'][$id] = $data;
        $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Código FISE creado correctamente.'];
        
        $this->redirect('fise');
    }

    public function editar(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        
        if (!isset($_SESSION['fise_data'][$id])) {
            $this->redirect('fise');
            return;
        }

        $fise = $_SESSION['fise_data'][$id];
        $this->render('fise/formulario', ['titulo' => 'Editar FISE', 'fise' => $fise]);
    }

    public function update(): void {
        $this->requireAdmin();
        $id = (int)($_POST['id'] ?? 0);
        
        if (!isset($_SESSION['fise_data'][$id])) {
            $this->redirect('fise');
            return;
        }

        $data = [
            'id' => $id,
            'codigo' => $_POST['codigo'] ?? '',
            'precio' => (float)($_POST['precio'] ?? 0),
            'puntos' => (int)($_POST['puntos'] ?? 0),
            'estado' => (int)($_POST['estado'] ?? 1),
        ];

        $_SESSION['fise_data'][$id] = $data;
        $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Código FISE actualizado correctamente.'];
        
        $this->redirect('fise');
    }

    public function delete(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        
        if (isset($_SESSION['fise_data'][$id])) {
            unset($_SESSION['fise_data'][$id]);
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Eliminado!', 'message' => 'El código FISE ha sido eliminado.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo eliminar el código.'];
        }
        
        $this->redirect('fise');
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
