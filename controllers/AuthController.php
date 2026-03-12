<?php
require_once __DIR__ . '/../models/UsuarioModel.php';

class AuthController {

    public function login(): void {
        if (isset($_SESSION['id_usuario'])) {
            $this->redirect('panel');
        }
        $this->render('login');
    }

    public function doLogin(): void {
        $this->requireJson();

        $data     = json_decode(file_get_contents('php://input'), true);
        $usuario  = trim($data['usuario'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$usuario || !$password) {
            $this->json(['success' => false, 'message' => 'Complete todos los campos.']);
        }

        $model  = new UsuarioModel();
        $user   = $model->findByCredentials($usuario, $password);

        if ($user) {
            $_SESSION['id_usuario']       = $user['id'];
            $_SESSION['nombre_usuario']   = $user['nombre'];
            $_SESSION['usuario']          = $user['usuario'];
            $_SESSION['rol']              = $user['rol'];
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Usuario o contraseña incorrectos.']);
        }
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('login');
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

    private function json(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function requireJson(): void {
        header('Content-Type: application/json');
    }
}
