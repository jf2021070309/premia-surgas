<?php
require_once __DIR__ . '/../models/RecargaModel.php';
require_once __DIR__ . '/../models/ConfiguracionModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class RecargaAdminController {
    private AuditoriaModel $audit;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->requireAdmin();
        $this->audit = new AuditoriaModel();
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    public function index(): void {
        $model = new RecargaModel();
        $recargas = $model->getAllPending();
        $historial = $model->getAll(); 

        $configModel = new ConfiguracionModel();
        $qrActual = $configModel->getValor('yape_qr_imagen');
        $nombreTitular = $configModel->getValor('yape_nombre_titular') ?? 'Paga aquí con Yape';
 
        $this->render('recargas_admin', [
            'recargas' => $recargas,
            'historial' => $historial,
            'qrActual'  => $qrActual,
            'nombreTitular' => $nombreTitular
        ]);
    }

    public function subirQr(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir(BASE_URL . 'recargas-admin');

        $configModel = new ConfiguracionModel();
        
        // 1. Guardar Nombre del Titular
        $nombre = $_POST['yape_nombre'] ?? '';
        if ($nombre) {
            $configModel->upsert('yape_nombre_titular', $nombre, 'Nombre que aparece en el botón de Yape');
        }

        // 2. Manejar Imagen si se subió una
        if (isset($_FILES['qr_imagen']) && $_FILES['qr_imagen']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['qr_imagen'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!in_array($file['type'], $allowed)) {
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Formato inválido', 'message' => 'Solo se aceptan JPG, PNG, GIF o WebP.'];
                redirigir(BASE_URL . 'recargas-admin');
            }

            $dir = __DIR__ . '/../assets/uploads/qr/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            // Borrar anterior
            $oldFile = $configModel->getValor('yape_qr_imagen');
            if ($oldFile && file_exists($dir . $oldFile)) {
                @unlink($dir . $oldFile);
            }

            $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'yape_qr_' . time() . '.' . $ext;
            $dest     = $dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $configModel->upsert('yape_qr_imagen', $filename, 'Imagen QR de Yape para pagos');
                $this->audit->registrar($_SESSION['id_usuario'], 'ACTUALIZAR_QR_PAGO', "Actualizó el código QR de Yape y nombre titular ($nombre)", 'RECARGAS');
            }
        }

        $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Configuración Actualizada!', 'message' => 'Los datos de Yape han sido actualizados correctamente.'];
        redirigir(BASE_URL . 'recargas-admin');
    }

    public function actualizarEstado(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirigir(BASE_URL . 'recargas-admin');

        $id = (int) ($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? 'pendiente';
        $validador_id = $_SESSION['id_usuario'];

        if ($id > 0 && ($estado === 'aprobado' || $estado === 'rechazado')) {
            $model = new RecargaModel();

            // Info para auditoria
            $recarga = $model->findById($id);

            $result = $model->actualizarEstado($id, $estado, $validador_id);
            if ($result) {
                $statusText = strtoupper($estado);
                $this->audit->registrar($_SESSION['id_usuario'], 'MODERAR_RECARGA', "$statusText la recarga de {$recarga['puntos']} puntos del cliente #{$recarga['cliente_id']}", 'RECARGAS');
                $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => "La recarga ha sido marcada como $estado."];
            } else {
                $error = $_SESSION['db_error'] ?? 'No se pudo procesar la recarga.';
                unset($_SESSION['db_error']);
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => $error];
            }
        }

        redirigir(BASE_URL . 'recargas-admin');
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }
}
