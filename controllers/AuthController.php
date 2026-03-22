<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';

class AuthController {

    public function login(): void {
        if (isset($_SESSION['id_usuario'])) {
            if ($_SESSION['rol'] === 'cliente' && isset($_SESSION['codigo_cliente'])) {
                $this->redirect('scan?c=' . $_SESSION['codigo_cliente'] . '&t=' . $_SESSION['token_cliente']);
            }
            $this->redirect('panel');
        }
        $this->render('login');
    }

    public function doLogin(): void {
        header('Content-Type: application/json');

        $data     = json_decode(file_get_contents('php://input'), true);
        $usuario  = trim($data['usuario'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$usuario || !$password) {
            echo json_encode(['success' => false, 'message' => 'Complete todos los campos.']);
            exit;
        }

        // 1. Intentar con tabla Usuarios (Admin/Conductor)
        $model = new UsuarioModel();
        $user  = $model->findByCredentials($usuario, $password);

        if ($user) {
            $sessId = session_id();
            $model->updateSessionId($user['id'], $sessId);

            $_SESSION['id_usuario']       = $user['id'];
            $_SESSION['nombre_usuario']   = $user['nombre'];
            $_SESSION['usuario']          = $user['usuario'];
            $_SESSION['rol']              = $user['rol'];
            $_SESSION['departamento']     = $user['departamento'];
            $_SESSION['session_id']       = $sessId;

            echo json_encode(['success' => true, 'redirect' => 'panel']);
            exit;
        }

        // 2. Intentar con tabla Clientes (DNI)
        $clienteModel = new ClienteModel();
        $cliente      = $clienteModel->loginCliente($usuario, $password);

        if ($cliente) {
            $sessId = session_id();
            $clienteModel->updateSessionId($cliente['id'], $sessId);

            $_SESSION['id_usuario']       = $cliente['id'];
            $_SESSION['nombre_usuario']   = $cliente['nombre'];
            $_SESSION['usuario']          = $cliente['dni'];
            $_SESSION['rol']              = 'cliente';
            $_SESSION['departamento']     = $cliente['departamento'];
            $_SESSION['session_id']       = $sessId;
            
            // Datos específicos de cliente para redirección
            $_SESSION['id_cliente']     = $cliente['id'];
            $_SESSION['nombre_cliente'] = $cliente['nombre'];
            $_SESSION['codigo_cliente'] = $cliente['codigo'];
            $_SESSION['token_cliente']  = $cliente['token'];

            echo json_encode(['success' => true, 'redirect' => 'scan?c=' . $cliente['codigo'] . '&t=' . $cliente['token']]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos.']);
        exit;
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
