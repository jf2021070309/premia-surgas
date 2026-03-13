<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../config/config.php';

class ClienteController {

    public function nuevo(): void {
        $this->requireAuth();
        $this->render('clientes/nuevo');
    }

    public function create(): void {
        $this->requireAuth();
        header('Content-Type: application/json');

        $data    = json_decode(file_get_contents('php://input'), true);
        $nombre  = trim($data['nombre']   ?? '');
        $celular = trim($data['celular']  ?? '');
        $dir     = trim($data['direccion'] ?? '');
        $dist    = trim($data['distrito'] ?? '');

        if (!$nombre || !$celular) {
            echo json_encode(['success' => false, 'message' => 'Nombre y celular son obligatorios.']);
            exit;
        }

        $model = new ClienteModel();

        // Celular duplicado → devolver el existente
        $existente = $model->findByCelular($celular);
        if ($existente) {
            echo json_encode([
                'success' => true,
                'existing' => true,
                'message' => 'El cliente ya estaba registrado.',
                'id'      => $existente['id'],
                'codigo'  => $existente['codigo'],
            ]);
            exit;
        }

        $codigo = $model->generarCodigo();
        $token  = hash_hmac('sha256', $codigo, SECRET_KEY);

        $id = $model->create([
            'codigo'     => $codigo,
            'nombre'     => $nombre,
            'celular'    => $celular,
            'direccion'  => $dir,
            'distrito'   => $dist,
            'token'      => $token,
            'creado_por' => $_SESSION['id_usuario'],
        ]);

        echo json_encode(['success' => true, 'id' => $id, 'codigo' => $codigo]);
        exit;
    }

    public function exito(): void {
        $this->requireAuth();
        $id     = (int)($_GET['id'] ?? 0);
        $model  = new ClienteModel();
        $cliente = $model->findById($id);
        if (!$cliente) { $this->redirect('panel'); }
        $this->render('clientes/exito', ['cliente' => $cliente]);
    }

    public function imprimir(): void {
        $this->requireAuth();
        $id     = (int)($_GET['id'] ?? 0);
        $model  = new ClienteModel();
        $cliente = $model->findById($id);
        if (!$cliente) { $this->redirect('panel'); }
        $this->render('clientes/imprimir', ['cliente' => $cliente]);
    }

    public function lista(): void {
        $this->requireAuth();
        $model   = new ClienteModel();
        $clientes = $model->getAll();
        $this->render('clientes/lista', ['clientes' => $clientes]);
    }

    public function editar(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $model = new ClienteModel();
        $cliente = $model->findById($id);

        if (!$cliente) {
            $this->redirect('clientes/lista');
        }

        $this->render('clientes/editar', ['cliente' => $cliente]);
    }

    public function update(): void {
        $this->requireAdmin();
        $id = (int)($_POST['id'] ?? 0);
        $model = new ClienteModel();

        $data = [
            'nombre'    => $_POST['nombre'] ?? '',
            'celular'   => $_POST['celular'] ?? '',
            'direccion' => $_POST['direccion'] ?? '',
            'distrito'  => $_POST['distrito'] ?? '',
            'estado'    => (int)($_POST['estado'] ?? 1),
        ];

        if ($model->update($id, $data)) {
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Cliente actualizado correctamente.'];
            $this->redirect('clientes/lista');
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar el cliente.'];
            $this->redirect('clientes/lista');
        }
    }

    public function cambiarEstado(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $estado = (int)($_GET['v'] ?? 1);
        $model = new ClienteModel();
        if ($model->setEstado($id, $estado)) {
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Hecho!', 'message' => ($estado ? 'Cliente activado.' : 'Cliente inactivado.')];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo cambiar el estado.'];
        }
        $this->redirect('clientes/lista');
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

    private function requireAuth(): void {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    private function requireAdmin(): void {
        $this->requireAuth();
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }
}
