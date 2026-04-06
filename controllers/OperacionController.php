<?php
require_once __DIR__ . '/../models/TipoOperacionModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class OperacionController {
    private AuditoriaModel $audit;

    public function __construct() {
        $this->audit = new AuditoriaModel();
    }

    public function index(): void {
        $this->requireAdmin();
        $model = new TipoOperacionModel();
        $operaciones = $model->getAll();
        $this->render('operaciones/index', ['operaciones' => $operaciones]);
    }

    public function create(): void {
        $this->requireAdmin();
        $model = new TipoOperacionModel();
        
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'puntos' => (int) ($_POST['puntos'] ?? 0),
            'estado' => 1
        ];

        if ($model->create($data)) {
            $this->audit->registrar($_SESSION['id_usuario'], 'NUEVA_REGLA_PUNTOS', "Creó regla: " . $data['nombre'] . " (" . $data['puntos'] . " pts)", 'LOGISTICA');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Operación creada correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo crear la operación.'];
        }
        
        $redir = $_POST['redir'] ?? 'ajustes';
        redirigir(BASE_URL . $redir);
    }

    public function update(): void {
        $this->requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        $model = new TipoOperacionModel();
        
        $original = $model->findById($id);

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'puntos' => (int) ($_POST['puntos'] ?? 0),
            'estado' => (int) ($_POST['estado'] ?? 1)
        ];

        if ($model->update($id, $data)) {
            $changes = [];
            foreach (['nombre', 'puntos', 'estado'] as $f) {
                if ($original[$f] != $data[$f]) $changes[$f] = ['ant' => $original[$f], 'des' => $data[$f]];
            }
            $desc = "Actualizó regla de puntos: " . $data['nombre'];
            if(!empty($changes)) $desc .= " (" . count($changes) . " campos modificados)";
            
            $this->audit->registrar($_SESSION['id_usuario'], 'ACTUALIZAR_REGLA_PUNTOS', $desc, 'LOGISTICA', $changes);
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Operación actualizada correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar la operación.'];
        }
        
        $redir = $_POST['redir'] ?? 'ajustes';
        redirigir(BASE_URL . $redir);
    }

    public function delete(): void {
        $this->requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new TipoOperacionModel();
        
        $original = $model->findById($id);

        if ($model->delete($id)) {
            $this->audit->registrar($_SESSION['id_usuario'], 'INACTIVAR_REGLA_PUNTOS', "Inactivó regla: " . ($original['nombre'] ?? 'ID '.$id), 'LOGISTICA');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Operación inactivada.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo inactivar la operación.'];
        }
        
        $redir = $_GET['redir'] ?? 'ajustes';
        redirigir(BASE_URL . $redir);
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
