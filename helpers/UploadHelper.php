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

    /**
     * Guarda una copia local de la imagen y embebe metadata (EXIF para JPG/TIFF, JSON al final para otros)
     */
    public static function saveLocalAndEmbedMetadata(string $tmpPath, string $originalName, string $nombre, string $propietario, string $latitud, string $longitud): ?string {
        $uploadDir = __DIR__ . '/../assets/uploads/puntos_venta/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $filename = uniqid('punto_') . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        if (!copy($tmpPath, $targetPath)) {
            error_log("No se pudo copiar el archivo a " . $targetPath);
            return null;
        }

        // Si es JPEG/TIFF, usar PEL para incrustar metadata
        if (in_array($extension, ['jpg', 'jpeg', 'tiff', 'tif'])) {
            try {
                require_once __DIR__ . '/../vendor/autoload.php';
                $jpeg = new \lsolesen\pel\PelJpeg($targetPath);
                $exif = $jpeg->getExif();

                if ($exif == null) {
                    $exif = new \lsolesen\pel\PelExif();
                    $jpeg->setExif($exif);
                }

                $tiff = $exif->getTiff();
                if ($tiff == null) {
                    $tiff = new \lsolesen\pel\PelTiff();
                    $exif->setTiff($tiff);
                }

                $ifd0 = $tiff->getIfd();
                if ($ifd0 == null) {
                    $ifd0 = new \lsolesen\pel\PelIfd(\lsolesen\pel\PelIfd::IFD0);
                    $tiff->setIfd($ifd0);
                }

                // Add Description with Nombre and Propietario
                $desc = "Punto de Venta: $nombre | Propietario: $propietario";
                $entryDesc = $ifd0->getEntry(\lsolesen\pel\PelTag::IMAGE_DESCRIPTION);
                if ($entryDesc) {
                    $entryDesc->setValue($desc);
                } else {
                    $ifd0->addEntry(new \lsolesen\pel\PelEntryAscii(\lsolesen\pel\PelTag::IMAGE_DESCRIPTION, $desc));
                }

                // Add GPS Data
                $gps = $ifd0->getSubIfd(\lsolesen\pel\PelIfd::GPS);
                if ($gps == null) {
                    $gps = new \lsolesen\pel\PelIfd(\lsolesen\pel\PelIfd::GPS);
                    $ifd0->addSubIfd($gps);
                }

                $lat = (float) $latitud;
                $lng = (float) $longitud;

                $gps->addEntry(new \lsolesen\pel\PelEntryAscii(\lsolesen\pel\PelTag::GPS_LATITUDE_REF, $lat < 0 ? 'S' : 'N'));
                $gps->addEntry(new \lsolesen\pel\PelEntryRational(\lsolesen\pel\PelTag::GPS_LATITUDE, ...self::convertDecimalToDMS(abs($lat))));
                $gps->addEntry(new \lsolesen\pel\PelEntryAscii(\lsolesen\pel\PelTag::GPS_LONGITUDE_REF, $lng < 0 ? 'W' : 'E'));
                $gps->addEntry(new \lsolesen\pel\PelEntryRational(\lsolesen\pel\PelTag::GPS_LONGITUDE, ...self::convertDecimalToDMS(abs($lng))));

                $jpeg->saveFile($targetPath);
            } catch (\Exception $e) {
                error_log("Error al guardar metadata EXIF con PEL: " . $e->getMessage());
            }
        } else {
            // Fallback para PNG u otros formatos: inyectar metadata al final del archivo (como JSON)
            $metadata = json_encode([
                'nombre' => $nombre,
                'propietario' => $propietario,
                'latitud' => $latitud,
                'longitud' => $longitud
            ]);
            file_put_contents($targetPath, "\n---METADATA_START---\n" . $metadata . "\n---METADATA_END---\n", FILE_APPEND);
        }

        return 'assets/uploads/puntos_venta/' . $filename;
    }

    private static function convertDecimalToDMS($decimal) {
        $degrees = floor($decimal);
        $minutes = floor(($decimal - $degrees) * 60);
        $seconds = ($decimal - $degrees - $minutes / 60) * 3600;
        
        return [
            [$degrees, 1],
            [$minutes, 1],
            [round($seconds * 10000), 10000] // Precisión de 4 decimales
        ];
    }
}
