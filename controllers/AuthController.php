<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class AuthController {
    private AuditoriaModel $audit;

    public function __construct() {
        $this->audit = new AuditoriaModel();
    }

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

            // Registro de Auditoría
            $this->audit->registrar($user['id'], 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', 'SEGURIDAD', 'trabajador');

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
            $_SESSION['usuario']          = $cliente['dni'] ?: $cliente['ruc'];
            $_SESSION['rol']              = 'cliente';
            $_SESSION['departamento']     = $cliente['departamento'];
            $_SESSION['session_id']       = $sessId;
            
            // Datos específicos de cliente para redirección
            $_SESSION['id_cliente']     = $cliente['id'];
            $_SESSION['nombre_cliente'] = $cliente['nombre'];
            $_SESSION['codigo_cliente'] = $cliente['codigo'];
            $_SESSION['token_cliente']  = $cliente['token'];

            // Registro de Auditoría
            $this->audit->registrar($cliente['id'], 'INICIO_SESION', 'Inicio de sesión exitoso (Cliente)', 'SEGURIDAD', 'cliente');

            echo json_encode(['success' => true, 'redirect' => 'scan?c=' . $cliente['codigo'] . '&t=' . $cliente['token']]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos.']);
        exit;
    }

    public function logout(): void {
        if (isset($_SESSION['id_usuario'])) {
            // Pasamos null para que el AuditoriaModel detecte automáticamente el ID y el Tipo desde la sesión
            $this->audit->registrar(null, 'CIERRE_SESION', 'El usuario cerró su sesión', 'SEGURIDAD');
        }
        
        // Limpiar el array de sesión
        $_SESSION = [];

        // Si se desea eliminar la cookie de sesión también
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        session_write_close(); // Forzar el guardado y cierre del archivo de sesión
        $this->redirect('login');
    }

    public function checkSession(): void {
        header('Content-Type: application/json');
        echo json_encode(['valid' => true]);
        exit;
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
