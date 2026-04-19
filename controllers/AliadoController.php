<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class AliadoController
{
    private AuditoriaModel $audit;

    public function __construct() {
        $this->audit = new AuditoriaModel();
    }

    public function index(): void
    {
        $this->requireAdmin();
        $model = new UsuarioModel();
        $aliados = $model->getAllAliados();
        $this->render('aliados/index', ['aliados' => $aliados]);
    }

    public function nuevo(): void
    {
        $this->requireAdmin();
        $this->render('aliados/formulario', ['titulo' => 'Nuevo Aliado Comercial', 'aliado' => null]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $model = new UsuarioModel();

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'usuario' => $_POST['usuario'] ?? '',
            'password' => $_POST['password'] ?? '',
            'rol' => 'aliado',
            'estado' => (int) ($_POST['estado'] ?? 1),
            'departamento' => $_POST['departamento'] ?? null,
        ];

        if ($model->create($data)) {
            $this->audit->registrar($_SESSION['id_usuario'], 'NUEVO_ALIADO', "Registró al aliado: " . ($data['nombre']), 'ALIADOS');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Aliado comercial registrado correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo registrar al aliado.'];
        }

        $redir = $_POST['redir'] ?? 'aliados';
        $this->redirect($redir);
    }

    public function editar(): void
    {
        $this->requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new UsuarioModel();
        $aliado = $model->findById($id);

        if (!$aliado || $aliado['rol'] !== 'aliado') {
            $this->redirect('aliados');
        }

        $this->render('aliados/formulario', ['titulo' => 'Editar Aliado', 'aliado' => $aliado]);
    }

    public function update(): void
    {
        $this->requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        $model = new UsuarioModel();
        
        $aliadoOriginal = $model->findById($id);
        if (!$aliadoOriginal || $aliadoOriginal['rol'] !== 'aliado') {
            $this->redirect('aliados');
        }

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'usuario' => $_POST['usuario'] ?? '',
            'estado' => (int) ($_POST['estado'] ?? 1),
            'departamento' => $_POST['departamento'] ?? null,
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        // Tracking de cambios
        $changes = [];
        $fields = ['nombre', 'usuario', 'estado', 'departamento'];
        foreach($fields as $f) {
            $ant = $aliadoOriginal[$f] ?? null;
            $des = $data[$f] ?? null;
            if (trim((string)$ant) !== trim((string)$des)) {
                $changes[$f] = ['ant' => $ant, 'des' => $des];
            }
        }

        if ($model->update($id, $data)) {
            $desc = "Actualizó datos del aliado: " . ($data['nombre']);
            if(!empty($changes)) $desc .= " (" . count($changes) . " campos modificados)";
            $this->audit->registrar($_SESSION['id_usuario'], 'ACTUALIZAR_ALIADO', $desc, 'ALIADOS', $changes);
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Información del aliado actualizada.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar la información.'];
        }

        $redir = $_POST['redir'] ?? 'aliados';
        $this->redirect($redir);
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new UsuarioModel();
        
        $aliado = $model->findById($id);

        try {
            if ($model->delete($id)) {
                $this->audit->registrar($_SESSION['id_usuario'], 'ELIMINAR_ALIADO', "Eliminó definitivamente al aliado: " . ($aliado['nombre'] ?? 'ID '.$id), 'ALIADOS');
                $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'El aliado ha sido eliminado del sistema.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo eliminar al aliado.'];
            }
        } catch (PDOException $e) {
            if ($e->getCode() == "23000") {
                $_SESSION['flash'] = [
                    'type' => 'warning',
                    'title' => 'Restricción de Seguridad',
                    'message' => 'Este aliado no puede ser eliminado porque tiene registros de operaciones vinculados.'
                ];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error Crítico', 'message' => 'Ocurrió un error inesperado al eliminar.'];
            }
        }

        $redir = $_GET['redir'] ?? 'aliados';
        $this->redirect($redir);
    }

    public function miHistorial(): void
    {
        $this->requireAliado();
        require_once __DIR__ . '/../models/VentaModel.php';
        
        $model = new VentaModel();
        
        $search = trim($_GET['search'] ?? '');
        $fechaDesde = trim($_GET['fecha_desde'] ?? '');
        $fechaHasta = trim($_GET['fecha_hasta'] ?? '');
        
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $result = $model->getByConductorPaginated($_SESSION['id_usuario'], $offset, $limit, $search, $fechaDesde, $fechaHasta);
        $historial = $result['data'];
        $totalPages = ceil($result['total'] / $limit);
        
        $this->render('aliados/mi_historial', [
            'historial' => $historial,
            'totalRows' => $result['total'],
            'totalPuntosFiltrado' => $result['total_puntos'],
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
            'titulo' => 'Mi Historial de Asignaciones'
        ]);
    }

    // ── helpers ──────────────────────────────────────────────────

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    private function requireAliado(): void
    {
        if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'aliado' && $_SESSION['rol'] !== 'admin')) {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }
}
