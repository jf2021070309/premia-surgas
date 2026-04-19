<?php
// helpers/UploadHelper.php

class UploadHelper {
    
    /**
     * Sube una imagen a ImgBB y retorna la URL de la imagen.
     * 
     * @param string $fileTmpPath Ruta temporal del archivo ($_FILES['tmp_name'])
     * @return string|null URL de la imagen o null si falla
     */
    public static function uploadToImgBB(string $fileTmpPath): ?string {
        if (!defined('IMGBB_API_KEY') || empty(IMGBB_API_KEY) || IMGBB_API_KEY === 'TU_API_KEY_AQUI') {
            error_log("ImgBB Error: API Key no configurada.");
            return null;
        }

        $imageData = base64_encode(file_get_contents($fileTmpPath));
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.imgbb.com/1/upload?key=" . IMGBB_API_KEY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'image' => $imageData
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            error_log("ImgBB CURL Error: " . $error);
            return null;
        }

        $json = json_decode($response, true);
        
        if (isset($json['success']) && $json['success'] && isset($json['data']['url'])) {
            return $json['data']['url'];
        }

        error_log("ImgBB API Error: " . ($json['error']['message'] ?? 'Error desconocido'));
        return null;
    }
}
