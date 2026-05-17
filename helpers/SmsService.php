<?php

/**
 * SmsService — Integración con SMS Gateway (Android)
 * Permite enviar mensajes SMS usando un celular Android como pasarela.
 */
class SmsService
{
    /**
     * Encola un mensaje SMS para ser enviado por la App Android.
     * 
     * @param string $to Número de teléfono
     * @param string $message Texto del mensaje
     * @return array [success => bool, message => string]
     */
    public static function send(string $to, string $message)
    {
        // Limpiar el número (solo dígitos)
        $to = preg_replace('/\D/', '', $to);
        
        // Ajustar formato según sea necesario (Perú por defecto)
        if (strlen($to) === 9) {
            $to = '51' . $to;
        }

        try {
            require_once __DIR__ . '/../config/Database.php';
            $db = Database::getConnection();
            
            $sql = "INSERT INTO sms_queue (celular, mensaje, estado) VALUES (?, ?, 'pendiente')";
            $stmt = $db->prepare($sql);
            $success = $stmt->execute([$to, $message]);

            return [
                'success' => $success,
                'message' => $success ? 'SMS encolado correctamente' : 'Error al encolar SMS'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }
}

