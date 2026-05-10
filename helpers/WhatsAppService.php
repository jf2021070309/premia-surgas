<?php

/**
 * WhatsAppService — Integración con Meta WhatsApp Cloud API
 * Permite enviar mensajes automáticos (plantillas) desde el servidor.
 */
class WhatsAppService
{
    private static $apiUrl = "https://graph.facebook.com/";

    /**
     * Envía un mensaje de plantilla (Template) a un número específico.
     * 
     * @param string $to Número de teléfono con código de país (ej: 51987654321)
     * @param string $templateName Nombre de la plantilla aprobada en Meta
     * @param array $params Variables dinámicas para la plantilla (components/parameters)
     * @param string $language Código de lenguaje (ej: es)
     * @return array [success => bool, response => mixed, error => string]
     */
    public static function sendTemplate(string $to, string $templateName, array $params = [], string $language = 'es')
    {
        // Limpiar el número (solo dígitos)
        $to = preg_replace('/\D/', '', $to);
        
        // Si no tiene el 51 (Perú) y tiene 9 dígitos, agregarlo por defecto (ajustar según país)
        if (strlen($to) === 9) {
            $to = '51' . $to;
        }

        $url = self::$apiUrl . WABA_VERSION . "/" . WABA_PHONE_ID . "/messages";

        // Estructura de parámetros para Meta Cloud API
        $formattedParams = [];
        foreach ($params as $p) {
            $formattedParams[] = [
                'type' => 'text',
                'text' => (string) $p
            ];
        }

        $body = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $language
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => $formattedParams
                    ]
                ]
            ]
        ];

        return self::executePost($url, $body);
    }

    /**
     * Envía un mensaje de texto libre (Solo funciona si hay una sesión activa de 24h)
     * 
     * @param string $to Número de teléfono
     * @param string $message Texto del mensaje
     * @return array
     */
    public static function sendText(string $to, string $message)
    {
        $to = preg_replace('/\D/', '', $to);
        if (strlen($to) === 9) $to = '51' . $to;

        $url = self::$apiUrl . WABA_VERSION . "/" . WABA_PHONE_ID . "/messages";

        $body = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'body' => $message
            ]
        ];

        return self::executePost($url, $body);
    }

    /**
     * Ejecuta la petición cURL
     */
    private static function executePost($url, $body)
    {
        $ch = curl_init($url);
        
        $jsonBody = json_encode($body);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . WABA_TOKEN
        ]);
        
        // Para entornos locales con problemas de SSL (opcional pero común en XAMPP)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

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

        $resDecoded = json_decode($response, true);

        return [
            'success'  => ($httpCode >= 200 && $httpCode < 300),
            'code'     => $httpCode,
            'response' => $resDecoded,
            'error'    => ($httpCode >= 400) ? ($resDecoded['error']['message'] ?? 'Unknown Error') : null
        ];
    }
}
