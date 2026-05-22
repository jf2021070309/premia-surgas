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

                    $celular = trim($_POST['celular'] ?? '');
                    if (empty($celular)) {
                        $celular = $venta['cliente_celular'] ?? '';
                    }

                    if ($estado === 'aprobado' && !empty($celular)) {
                        $mensaje = trim($_POST['custom_message'] ?? '');
                        if (empty($mensaje)) {
                            $monto = number_format($venta['monto'], 2);
                            $resumen_items = '';
                            if (!empty($venta['items'])) {
                                $partes = [];
                                foreach ($venta['items'] as $item) {
                                    $partes[] = $item['cantidad'] . 'x ' . $item['nombre_item'];
                                }
                                $resumen_items = implode(', ', $partes);
                            } else {
                                $resumen_items = 'compra';
                            }
                            $mensaje = "🎉 *¡Pedido Aprobado!* \n\nHola *{$venta['cliente_nombre']}*, se te han asignado *{$venta['puntos']} puntos* por tu compra de *{$resumen_items}* (Total: S/ {$monto}). \n\n¡Sigue acumulando para grandes premios con *SURGAS*! 🛵💨";
                        }

                        $sendSms = isset($_POST['send_sms']) ? (int)$_POST['send_sms'] : 1;
                        $sendWsp = isset($_POST['send_wsp']) ? (int)$_POST['send_wsp'] : 1;

                        $notifSent = [];
                        if ($sendSms === 1) {
                            SmsService::send($celular, $mensaje);
                            $notifSent[] = 'SMS';
                        }
                        if ($sendWsp === 1) {
                            require_once __DIR__ . '/../helpers/WhatsAppService.php';
                            WhatsAppService::sendText($celular, $mensaje);
                            $notifSent[] = 'WhatsApp';
                        }

                        $msgExt = !empty($notifSent) ? " y notificado por " . implode(' y ', $notifSent) : " sin notificaciones";
                        $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Pedido Aprobado!', 'message' => "El pedido ha sido aprobado con éxito{$msgExt}."];
                    } else if ($estado === 'rechazado') {
                        $mensaje = trim($_POST['custom_message'] ?? '');
                        if (empty($mensaje)) {
                            $monto = number_format($venta['monto'], 2);
                            $resumen_items = '';
                            if (!empty($venta['items'])) {
                                $partes = [];
                                foreach ($venta['items'] as $item) {
                                    $partes[] = $item['cantidad'] . 'x ' . $item['nombre_item'];
                                }
                                $resumen_items = implode(', ', $partes);
                            } else {
                                $resumen_items = 'compra';
                            }
                            $mensaje = "❌ *Pedido Rechazado* \n\nHola *{$venta['cliente_nombre']}*, tu solicitud de puntos por tu compra de *{$resumen_items}* (Total: S/ {$monto}) fue rechazada por administración.";
                        }

                        if (!empty($celular)) {
                            $sendSms = isset($_POST['send_sms']) ? (int)$_POST['send_sms'] : 1;
                            $sendWsp = isset($_POST['send_wsp']) ? (int)$_POST['send_wsp'] : 1;

                            if ($sendSms === 1) {
                                SmsService::send($celular, $mensaje);
                            }
                            if ($sendWsp === 1) {
                                require_once __DIR__ . '/../helpers/WhatsAppService.php';
                                WhatsAppService::sendText($celular, $mensaje);
                            }
                        }

                        $_SESSION['flash'] = ['type' => 'success', 'title' => 'Pedido Rechazado', 'message' => "El pedido ha sido rechazado."];
                    }
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
