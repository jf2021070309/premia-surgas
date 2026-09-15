<?php
/**
 * PremiaSurgas — Validador de Evidencias de Balones GLP
 * Conecta PHP con el microservicio interno Python (127.0.0.1:8001/detectar)
 */

class DetectorBalonesHelper
{
    private static $serviceUrl = 'http://127.0.0.1:8001/detectar';
    private static $timeout = 15; // segundos

    /**
     * Valida una foto de evidencia contra el número de balones declarados.
     *
     * @param string $fotoPath Ruta temporal absoluta de la foto ($_FILES['foto']['tmp_name'])
     * @param int $balonesDeclarados Cantidad indicada por el conductor/afiliado
     * @return array [
     *    'ok' => bool,
     *    'detectados' => int,
     *    'declarados' => int,
     *    'coincide' => bool,
     *    'servicio_disponible' => bool,
     *    'cajas' => array,
     *    'error' => string|null
     * ]
     */
    public static function validar($fotoPath, $balonesDeclarados)
    {
        if (!file_exists($fotoPath) || !is_readable($fotoPath)) {
            return [
                'ok' => false,
                'detectados' => 0,
                'declarados' => $balonesDeclarados,
                'coincide' => false,
                'servicio_disponible' => true,
                'error' => 'El archivo de imagen no existe o no es legible'
            ];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fotoPath) ?: 'image/jpeg';
        finfo_close($finfo);

        $curl = curl_init(self::$serviceUrl);
        $cfile = new CURLFile($fotoPath, $mimeType, basename($fotoPath));

        $postData = [
            'foto' => $cfile,
            'balones_declarados' => (int)$balonesDeclarados
        ];

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => self::$timeout,
            CURLOPT_CONNECTTIMEOUT => 3,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        // Si el microservicio está caído o no responde, no bloquear la operación
        if ($response === false || $httpCode !== 200) {
            return [
                'ok' => false,
                'detectados' => 0,
                'declarados' => (int)$balonesDeclarados,
                'coincide' => false,
                'servicio_disponible' => false,
                'error' => $curlError ?: ("HTTP Error: " . $httpCode)
            ];
        }

        $data = json_decode($response, true);
        if (!$data || !isset($data['detectados'])) {
            return [
                'ok' => false,
                'detectados' => 0,
                'declarados' => (int)$balonesDeclarados,
                'coincide' => false,
                'servicio_disponible' => true,
                'error' => 'Respuesta inválida del microservicio de IA'
            ];
        }

        return [
            'ok' => true,
            'detectados' => (int)$data['detectados'],
            'declarados' => (int)$balonesDeclarados,
            'coincide' => (bool)$data['coincide'],
            'servicio_disponible' => true,
            'cajas' => $data['cajas'] ?? [],
            'error' => null
        ];
    }
}
