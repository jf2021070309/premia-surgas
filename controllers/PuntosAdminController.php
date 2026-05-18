<?php
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../models/ConfiguracionModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../helpers/SmsService.php';


class PuntosAdminController
{
    private AuditoriaModel $audit;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $this->requireAdmin();
        $this->audit = new AuditoriaModel();
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    public function index(): void
    {
        $model = new VentaModel();
        $ventas = $model->getPendientes();
        $historial = $model->getAllAdmin();

        $this->render('puntos_admin', [
            'ventas' => $ventas,
            'historial' => $historial
        ]);
    }

    public function actualizarEstado(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            redirigir(BASE_URL . 'puntos-admin');

        $id = (int) ($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? 'pendiente';
        $validador_id = $_SESSION['id_usuario'];

        if ($id > 0) {
            $model = new VentaModel();
            $venta = $model->getById($id);

            if (!$venta) {
                redirigir(BASE_URL . 'puntos-admin');
            }

            if ($estado === 'notificar') {
                $notifyMethod = $_POST['notify'] ?? 'none';
                $monto = number_format($venta['monto'], 2);
                
                if ($notifyMethod === 'sms') {
                    $msg = "Hola {$venta['cliente_nombre']}, se te han asignado {$venta['puntos']} puntos por tu compra de S/ {$monto}.";
                    SmsService::send('957084267', $msg);
                    $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => 'SMS de prueba enviado (Sin aprobar)'];
                } else if ($notifyMethod === 'wsp') {
                    require_once __DIR__ . '/../helpers/WhatsAppService.php';
                    WhatsAppService::sendTemplate('957084267', 'recepcion_completa', [], 'es_PE');
                    $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => 'WSP de prueba enviado (Sin aprobar)'];
                }
            } else if ($estado === 'aprobado' || $estado === 'rechazado') {
                if ($model->validar($id, $estado, $validador_id)) {
                    $statusText = strtoupper($estado);
                    $this->audit->registrar($_SESSION['id_usuario'], 'MODERAR_PUNTOS', "$statusText la suma de {$venta['puntos']} puntos para el cliente #{$venta['cliente_id']} por el operador {$venta['conductor_id']}", 'RECARGAS');

                    if ($estado === 'aprobado' && !empty($venta['cliente_celular'])) {
                        $monto = number_format($venta['monto'], 2);
                        $msg = "Hola {$venta['cliente_nombre']}, se te han asignado {$venta['puntos']} puntos por tu compra de S/ {$monto}. ¡Sigue acumulando para grandes premios!";
                        SmsService::send($venta['cliente_celular'], $msg);
                    } else if ($estado === 'rechazado' && !empty($venta['cliente_celular'])) {
                        $msg = "Hola {$venta['cliente_nombre']}, tu solicitud de puntos por S/ " . number_format($venta['monto'], 2) . " fue rechazada por administración.";
                        SmsService::send($venta['cliente_celular'], $msg);
                    }

                    $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => "La operación ha sido marcada como $estado."];
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo procesar la asignación de puntos.'];
                }
            }
        }

        redirigir(BASE_URL . 'puntos-admin');
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }
}
