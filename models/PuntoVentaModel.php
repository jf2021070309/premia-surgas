<?php
// models/PuntoVentaModel.php
require_once __DIR__ . '/../config/config.php';

class PuntoVentaModel {

    public function getAll(): array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PUNTOS_API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $json = json_decode($response, true);
            if (isset($json['ok']) && $json['ok'] && isset($json['data'])) {
                return $json['data'];
            }
        }
        
        error_log("PuntosVenta API Error (getAll): http_code=$httpCode, response=$response");
        return [];
    }

    public function getById($id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PUNTOS_API_URL . '/' . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $json = json_decode($response, true);
            if (isset($json['ok']) && $json['ok'] && isset($json['data'])) {
                return $json['data'];
            }
        }
        
        error_log("PuntosVenta API Error (getById): id=$id, http_code=$httpCode, response=$response");
        return null;
    }

    public function create($nombre, $propietario, $latitud, $longitud, $foto): bool {
        // En PremiaSurgas, MapaController ya se encargaba de subir la foto y pasaba la URL o ruta local.
        // Mandamos los campos a la API Node.js de forma simple.
        $postData = [
            'nombre' => $nombre,
            'propietario' => $propietario,
            'latitud' => $latitud,
            'longitud' => $longitud,
        ];

        // Si tenemos un archivo local subido (ruta temporal de $_FILES o ruta local en disco)
        // o si es una URL ya procesada por ImgBB en MapaController, la API la recibirá.
        // Si MapaController ya subió la foto a ImgBB, pasamos la URL como string.
        // Si es un archivo físico en el servidor (aún no subido), lo enviamos como CURLFile.
        // Nota: para máxima simplicidad, como MapaController ya hace:
        // '$imgUrl = UploadHelper::uploadToImgBB($_FILES['foto']['tmp_name']);'
        // pasará la URL resultante en '$foto'. Si es una URL o ruta local en string, la API la almacenará.
        // Vamos a mandar todo por post.
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PUNTOS_API_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Headers de Autenticación
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: ' . PUNTOS_API_KEY
        ]);

        // Si es un archivo que se envió como archivo cargado
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $postData['foto'] = new CURLFile($_FILES['foto']['tmp_name'], $_FILES['foto']['type'], $_FILES['foto']['name']);
        } else if ($foto) {
            // Si es un path o url string ya pre-procesado
            $postData['foto'] = $foto;
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            $json = json_decode($response, true);
            return isset($json['ok']) && $json['ok'];
        }

        error_log("PuntosVenta API Error (create): http_code=$httpCode, response=$response");
        return false;
    }

    public function update($id, $nombre, $propietario, $latitud, $longitud, $foto = null): bool {
        // En Express, el endpoint PUT /api/puntos/:id recibe multipart/form-data o urlencoded.
        // cURL en PHP permite simular PUT con campos enviando POST y agregando un header personalizado o un campo '_method', 
        // pero la forma más limpia y compatible con Express (que usa multer/body-parser) es enviar un POST a un endpoint o 
        // mandar un PUT real por cURL. Mandaremos un PUT cURL con body.
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PUNTOS_API_URL . '/' . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: ' . PUNTOS_API_KEY
        ]);

        $postData = [
            'nombre' => $nombre,
            'propietario' => $propietario,
            'latitud' => $latitud,
            'longitud' => $longitud
        ];

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $postData['foto'] = new CURLFile($_FILES['foto']['tmp_name'], $_FILES['foto']['type'], $_FILES['foto']['name']);
        } else if ($foto) {
            $postData['foto'] = $foto;
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $json = json_decode($response, true);
            return isset($json['ok']) && $json['ok'];
        }

        error_log("PuntosVenta API Error (update): id=$id, http_code=$httpCode, response=$response");
        return false;
    }

    public function delete($id): bool {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PUNTOS_API_URL . '/' . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-api-key: ' . PUNTOS_API_KEY
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $json = json_decode($response, true);
            return isset($json['ok']) && $json['ok'];
        }

        error_log("PuntosVenta API Error (delete): id=$id, http_code=$httpCode, response=$response");
        return false;
    }
}
