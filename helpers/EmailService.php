<?php
// helpers/EmailService.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

class EmailService {

    /**
     * Envía una notificación detallada con evidencia fotográfica al administrador.
     * 
     * @param array $datos Información de la operación
     * @param string $destinatario Correo del administrador
     * @return array ['success' => bool, 'message' => string]
     */
    public static function notificarAdminEvidencia(array $datos, string $destinatario = 'jaimeelias.tacna.2016@gmail.com'): array {
        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = 'UTF-8';

            // Configuración SMTP si está disponible en DB / config / entorno
            $smtpHost = defined('SMTP_HOST') ? SMTP_HOST : getenv('SMTP_HOST');
            $smtpUser = defined('SMTP_USER') ? SMTP_USER : getenv('SMTP_USER');
            $smtpPass = defined('SMTP_PASS') ? SMTP_PASS : getenv('SMTP_PASS');
            $smtpPort = defined('SMTP_PORT') ? SMTP_PORT : (getenv('SMTP_PORT') ?: 587);
            $smtpSecure = defined('SMTP_SECURE') ? SMTP_SECURE : (getenv('SMTP_SECURE') ?: PHPMailer::ENCRYPTION_STARTTLS);

            if (!$smtpHost || !$smtpUser) {
                try {
                    require_once __DIR__ . '/../models/ConfiguracionModel.php';
                    $cfg = new ConfiguracionModel();
                    $dbHost = $cfg->getValor('smtp_host');
                    $dbUser = $cfg->getValor('smtp_user');
                    $dbPass = $cfg->getValor('smtp_pass');
                    $dbPort = $cfg->getValor('smtp_port');
                    if ($dbHost && $dbUser) {
                        $smtpHost = $dbHost;
                        $smtpUser = $dbUser;
                        $smtpPass = $dbPass;
                        if ($dbPort) $smtpPort = $dbPort;
                    }
                } catch (\Throwable $th) {}
            }

            if ($smtpHost && $smtpUser && $smtpPass) {
                $mail->isSMTP();
                $mail->Host       = $smtpHost;
                $mail->SMTPAuth   = true;
                $mail->Username   = $smtpUser;
                $mail->Password   = $smtpPass;
                $mail->SMTPSecure = $smtpSecure;
                $mail->Port       = (int) $smtpPort;
            } else {
                // Modo estándar (transporte del sistema)
                $mail->isMail();
            }

            // Remitente y Destinatario
            $remitenteEmail = $smtpUser ?: 'notificaciones@surgas.com.pe';
            $mail->setFrom($remitenteEmail, 'Surgas — Sistema de Puntos');
            $mail->addAddress($destinatario, 'Administración Surgas');
            $mail->addReplyTo($remitenteEmail, 'Surgas');

            // Adjuntar y Embeber Evidencia Fotográfica si existe
            $fotoRuta = $datos['evidencia_ruta'] ?? '';
            $cidEvidencia = 'evidencia_foto_' . time();
            $tieneFoto = false;

            if ($fotoRuta && file_exists($fotoRuta)) {
                $mail->addAttachment($fotoRuta, 'Evidencia_Balones_' . ($datos['id'] ?? 'op') . '.jpg');
                $mail->addEmbeddedImage($fotoRuta, $cidEvidencia, 'evidencia.jpg');
                $tieneFoto = true;
            }

            // Asunto
            $clienteNombre = $datos['cliente_nombre'] ?? 'Punto de Venta';
            $clienteRazon = $datos['cliente_razon_social'] ?? '—';
            $nombreParaAsunto = $clienteRazon !== '—' && !empty($clienteRazon) ? $clienteRazon : $clienteNombre;
            $puntos = $datos['puntos'] ?? 0;
            $balones = $datos['balones_cantidad'] ?? 0;
            $mail->Subject = "Asignacion de Puntos a P.V. (+$puntos)";

            // Cuerpo HTML
            $conductorNombre = $datos['conductor_nombre'] ?? 'Conductor no especificado';
            $clienteDoc = $datos['cliente_doc'] ?? '—';
            $clienteDir = $datos['cliente_direccion'] ?? '—';
            $clienteCel = $datos['cliente_celular'] ?? '—';
            $fecha = $datos['fecha'] ?? date('d/m/Y H:i');
            $balonesVerificados = $datos['balones_verificados'] ?? $balones;
            $ventaId = $datos['id'] ?? '—';

            $imgHtml = $tieneFoto
                ? "<div style='text-align: center; margin: 25px 0;'>
                     <p style='font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 8px;'>📸 Evidencia Fotográfica Capturada por Conductor:</p>
                     <img src='cid:$cidEvidencia' alt='Evidencia Fotográfica' style='max-width: 100%; max-height: 480px; border-radius: 14px; border: 2px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); display: inline-block;' />
                   </div>"
                : "<div style='background: #fef2f2; border: 1px dashed #f87171; padding: 15px; border-radius: 10px; color: #991b1b; text-align: center;'>No se adjuntó archivo físico de evidencia.</div>";

            $html = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
              <meta charset='UTF-8'>
              <title>Notificación de Entrega y Validación</title>
            </head>
            <body style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 30px 15px;'>
              <table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 620px; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;'>
                <tr>
                  <td style='background: #800000; padding: 30px 35px; text-align: center;'>
                    <h1 style='color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;'>SURGAS</h1>
                    <p style='color: rgba(255,255,255,0.85); margin: 6px 0 0 0; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;'>Validación de Entrega — Punto de Venta</p>
                  </td>
                </tr>
                <tr>
                  <td style='padding: 35px;'>
                    <div style='background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 15px 20px; margin-bottom: 25px; display: flex; align-items: center;'>
                      <span style='font-size: 20px; margin-right: 10px;'>✅</span>
                      <div>
                        <strong style='color: #166534; font-size: 14px;'># DE BALONES VERIFICADO (CHECK)</strong>
                        <p style='margin: 2px 0 0 0; color: #15803d; font-size: 13px;'>La entrega ha sido confirmada por el conductor y los puntos han sido asignados exitosamente.</p>
                      </div>
                    </div>

                    <table width='100%' style='border-collapse: collapse; font-size: 14px; margin-bottom: 25px;'>
                      <tr style='border-bottom: 1px solid #f1f5f9;'>
                        <td style='padding: 10px 0; color: #64748b; font-weight: 600; width: 40%;'>ID de Operación:</td>
                        <td style='padding: 10px 0; color: #0f172a; font-weight: 700;'>#$ventaId</td>
                      </tr>
                      <tr style='border-bottom: 1px solid #f1f5f9;'>
                        <td style='padding: 10px 0; color: #64748b; font-weight: 600;'>Nombre del Cliente:</td>
                        <td style='padding: 10px 0; color: #0f172a; font-weight: 700;'>$clienteNombre</td>
                      </tr>
                      <tr style='border-bottom: 1px solid #f1f5f9;'>
                        <td style='padding: 10px 0; color: #64748b; font-weight: 600;'>Razón Social:</td>
                        <td style='padding: 10px 0; color: #0f172a; font-weight: 700;'>$clienteRazon</td>
                      </tr>
                      <tr style='border-bottom: 1px solid #f1f5f9;'>
                        <td style='padding: 10px 0; color: #64748b; font-weight: 600;'>Conductor Responsable:</td>
                        <td style='padding: 10px 0; color: #800000; font-weight: 700;'>$conductorNombre</td>
                      </tr>
                      <tr style='border-bottom: 1px solid #f1f5f9;'>
                        <td style='padding: 10px 0; color: #64748b; font-weight: 600;'>Balones de 10kg:</td>
                        <td style='padding: 10px 0; color: #0f172a; font-weight: 800; font-size: 15px;'>$balones balones (Verificados: $balonesVerificados)</td>
                      </tr>
                      <tr style='border-bottom: 1px solid #f1f5f9;'>
                        <td style='padding: 10px 0; color: #64748b; font-weight: 600;'>Puntos Otorgados:</td>
                        <td style='padding: 10px 0; color: #16a34a; font-weight: 900; font-size: 18px;'>+$puntos PTS</td>
                      </tr>
                      <tr>
                        <td style='padding: 10px 0; color: #64748b; font-weight: 600;'>Fecha de Operación:</td>
                        <td style='padding: 10px 0; color: #475569; font-weight: 500;'>$fecha</td>
                      </tr>
                    </table>

                    $imgHtml

                    <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;'>
                      Este correo es una notificación automática generada por el sistema Surgas.<br>
                      Para revisar el registro completo, ingresa al panel administrativo.
                    </div>
                  </td>
                </tr>
              </table>
            </body>
            </html>
            ";

            $mail->isHTML(true);
            $mail->Body    = $html;
            $mail->AltBody = "Surgas: Entrega Verificada. Punto de Venta: $clienteNombre, Balones 10kg: $balones (Verificados: $balonesVerificados), Puntos: +$puntos pts, Conductor: $conductorNombre, Fecha: $fecha.";

            $mail->send();
            return ['success' => true, 'message' => 'Correo de notificación enviado exitosamente a ' . $destinatario];

        } catch (Exception $e) {
            error_log("Error enviando correo de evidencia: " . $mail->ErrorInfo);

            // Guardar copia del correo generado para inspección y respaldo local
            $backupDir = __DIR__ . '/../assets/uploads/evidencias';
            if (!is_dir($backupDir)) {
                @mkdir($backupDir, 0777, true);
            }
            if (isset($html)) {
                @file_put_contents($backupDir . '/ultimo_correo_notificacion.html', $html);
            }

            // En entornos locales/desarrollo (Windows/localhost sin sendmail configurado):
            $isLocal = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || php_sapi_name() === 'cli' || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost');
            if ($isLocal && (str_contains($mail->ErrorInfo, 'instantiate mail function') || empty($smtpHost))) {
                return [
                    'success' => true, 
                    'simulated' => true, 
                    'message' => 'Notificación generada para ' . $destinatario . ' y respaldada en servidor (Para envío por internet directo en local, configura SMTP).'
                ];
            }

            return ['success' => false, 'message' => 'No se pudo enviar el correo: ' . $mail->ErrorInfo];
        }
    }
}
