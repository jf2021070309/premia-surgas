<?php

/**
 * SmsService — Integración con SMS Gateway (Android)
 * Permite enviar mensajes SMS usando un celular Android como pasarela.
 */
class SmsService
{
    /**
     * Envía un mensaje SMS a un número específico.
     * 
     * @param string $to Número de teléfono (ej: 987654321)
     * @param string $message Texto del mensaje
     * @return array [success => bool, response => mixed, error => string]
     */
    public static function send(string $to, string $message)
    {
        // Limpiar el número (solo dígitos)
        $to = preg_replace('/\D/', '', $to);
        
        // Ajustar formato según sea necesario (ej: prefijo del país)
        if (strlen($to) === 9) {
            $to = '51' . $to; // Perú por defecto
        }

        $url = SMS_GATEWAY_URL;

        // Estructura común para Apps de SMS Gateway
        $body = [
            'phone'   => $to,
            'message' => $message
        ];

        return self::executePost($url, $body);
    }

    /**
     * Ejecuta la petición cURL enviando JSON
     */
    private static function executePost($url, $body)
    {
        $ch = curl_init();
        
        $jsonBody = json_encode($body);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'error'   => $error,
                'response' => null
            ];
        }

        // Intentar decodificar si la respuesta es JSON
        $resDecoded = json_decode($response, true) ?: $response;

        return [
            'success'  => ($httpCode >= 200 && $httpCode < 300),
            'code'     => $httpCode,
            'response' => $resDecoded,
            'error'    => ($httpCode >= 400) ? 'Error del servidor gateway' : null
        ];
    }
}
