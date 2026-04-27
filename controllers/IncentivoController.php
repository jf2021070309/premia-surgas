<?php
require_once __DIR__ . '/../models/IncentivoModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class IncentivoController {
    private IncentivoModel $model;
    private AuditoriaModel $audit;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->model = new IncentivoModel();
        $this->audit = new AuditoriaModel();
    }

    // ═══════════════════════════════════════════════════════════
    // REGLAS (Admin)
    // ═══════════════════════════════════════════════════════════

    public function reglas(): void {
        $this->requireAdmin();
        $reglas = $this->model->getAllReglas();
        $stats  = $this->model->getEstadisticas();
        $this->render('incentivos/reglas', [
            'reglas' => $reglas,
            'stats'  => $stats
        ]);
    }

    public function createRegla(): void {
        $this->requireAdmin();

        $data = [
            'nombre'            => trim($_POST['nombre'] ?? ''),
            'descripcion'       => trim($_POST['descripcion'] ?? ''),
            'tipo_cliente'      => $_POST['tipo_cliente'] ?? 'Todos',
            'meta_cantidad'     => (int) ($_POST['meta_cantidad'] ?? 1),
            'periodo'           => $_POST['periodo'] ?? 'mensual',
            'tipo_premio'       => $_POST['tipo_premio'] ?? 'vale_descuento',
            'valor_premio'      => (float) ($_POST['valor_premio'] ?? 0),
            'descripcion_premio'=> trim($_POST['descripcion_premio'] ?? ''),
            'vigencia_dias'     => (int) ($_POST['vigencia_dias'] ?? 30),
            'estado'            => (int) ($_POST['estado'] ?? 1),
        ];

        if (!$data['nombre'] || !$data['meta_cantidad'] || !$data['valor_premio']) {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'Completa todos los campos obligatorios.'];
            $this->redirect('incentivos/reglas');
        }

        $id = $this->model->createRegla($data);
        if ($id) {
            $this->audit->registrar($_SESSION['id_usuario'], 'NUEVA_REGLA_INCENTIVO', "Creó regla de incentivo: {$data['nombre']} (meta: {$data['meta_cantidad']} ops/{$data['periodo']})", 'INCENTIVOS');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Regla de incentivo creada correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo crear la regla.'];
        }
        $this->redirect('incentivos/reglas');
    }

    public function updateRegla(): void {
        $this->requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        $original = $this->model->findRegla($id);

        if (!$original) {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'Regla no encontrada.'];
            $this->redirect('incentivos/reglas');
        }

        $data = [
            'nombre'            => trim($_POST['nombre'] ?? ''),
            'descripcion'       => trim($_POST['descripcion'] ?? ''),
            'tipo_cliente'      => $_POST['tipo_cliente'] ?? 'Todos',
            'meta_cantidad'     => (int) ($_POST['meta_cantidad'] ?? 1),
            'periodo'           => $_POST['periodo'] ?? 'mensual',
            'tipo_premio'       => $_POST['tipo_premio'] ?? 'vale_descuento',
            'valor_premio'      => (float) ($_POST['valor_premio'] ?? 0),
            'descripcion_premio'=> trim($_POST['descripcion_premio'] ?? ''),
            'vigencia_dias'     => (int) ($_POST['vigencia_dias'] ?? 30),
            'estado'            => (int) ($_POST['estado'] ?? 1),
        ];

        if ($this->model->updateRegla($id, $data)) {
            $this->audit->registrar($_SESSION['id_usuario'], 'ACTUALIZAR_REGLA_INCENTIVO', "Actualizó regla: {$data['nombre']}", 'INCENTIVOS');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Actualizado!', 'message' => 'Regla de incentivo actualizada.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar.'];
        }
        $this->redirect('incentivos/reglas');
    }

    public function deleteRegla(): void {
        $this->requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $regla = $this->model->findRegla($id);

        try {
            if ($this->model->deleteRegla($id)) {
                $this->audit->registrar($_SESSION['id_usuario'], 'ELIMINAR_REGLA_INCENTIVO', "Eliminó regla: " . ($regla['nombre'] ?? "ID $id"), 'INCENTIVOS');
                $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Eliminado!', 'message' => 'Regla eliminada correctamente.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo eliminar.'];
            }
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'warning', 'title' => 'Restricción', 'message' => 'No se puede eliminar porque tiene vales generados. Desactívala en su lugar.'];
        }
        $this->redirect('incentivos/reglas');
    }

    // ═══════════════════════════════════════════════════════════
    // VALES (Admin)
    // ═══════════════════════════════════════════════════════════

    public function vales(): void {
        $this->requireAdmin();
        $this->model->expirarValesVencidos(); // auto-limpieza
        $vales = $this->model->getAllVales();
        $stats = $this->model->getEstadisticas();
        $this->render('incentivos/vales', [
            'vales' => $vales,
            'stats' => $stats
        ]);
    }

    public function marcarUsado(): void {
        $this->requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        $vale = $this->model->findVale($id);

        if ($vale && $this->model->marcarValeUsado($id, $_SESSION['id_usuario'])) {
            $this->audit->registrar($_SESSION['id_usuario'], 'USAR_VALE', "Marcó como usado el vale {$vale['codigo']} de {$vale['cliente_nombre']}", 'INCENTIVOS');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Vale Usado!', 'message' => "El vale {$vale['codigo']} ha sido marcado como usado."];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar el vale.'];
        }
        $this->redirect('incentivos/vales');
    }

    public function cancelarVale(): void {
        $this->requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        $vale = $this->model->findVale($id);

        if ($vale && $this->model->cancelarVale($id)) {
            $this->audit->registrar($_SESSION['id_usuario'], 'CANCELAR_VALE', "Canceló el vale {$vale['codigo']} de {$vale['cliente_nombre']}", 'INCENTIVOS');
            $_SESSION['flash'] = ['type' => 'success', 'title' => 'Cancelado', 'message' => "Vale {$vale['codigo']} cancelado."];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo cancelar el vale.'];
        }
        $this->redirect('incentivos/vales');
    }

    // ═══════════════════════════════════════════════════════════
    // API JSON (para el perfil del cliente)
    // ═══════════════════════════════════════════════════════════

    public function progresoJson(): void {
        header('Content-Type: application/json');
        $clienteId = $_GET['cliente_id'] ?? $_SESSION['id_cliente'] ?? $_SESSION['id_usuario'] ?? null;
        
        if (!$clienteId) {
            echo json_encode(['success' => false, 'message' => 'Cliente no identificado.']);
            exit;
        }

        $progreso = $this->model->getProgresoCliente((int) $clienteId);
        $vales    = $this->model->getValesActivosByCliente((int) $clienteId);

        echo json_encode([
            'success'  => true,
            'progreso' => $progreso,
            'vales'    => $vales
        ]);
        exit;
    }

    // ═══════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════

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
