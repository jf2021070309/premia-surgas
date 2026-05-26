<?php
require_once __DIR__ . '/../models/PuntoVentaModel.php';

class MapaController {

    // —— Cliente ——
    public function cliente(): void {
        $this->requireCliente();
        
        $modelo = new PuntoVentaModel();
        $puntos = $modelo->getAll();
        
        $this->render('mapa/cliente', [
            'puntosJson' => json_encode($puntos)
        ]);
    }

    // —— Admin ——
    public function admin(): void {
        $this->requireAdmin();
        
        $modelo = new PuntoVentaModel();
        $puntos = $modelo->getAll();
        
        $this->render('mapa/admin', [
            'puntos' => $puntos,
            'puntosJson' => json_encode($puntos)
        ]);
    }

    public function create(): void {
        $this->requireAdmin();
        
        $nombre = $_POST['nombre'] ?? '';
        $propietario = $_POST['propietario'] ?? '';
        $latitud = $_POST['latitud'] ?? '';
        $longitud = $_POST['longitud'] ?? '';
        $foto = null;

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            require_once __DIR__ . '/../helpers/UploadHelper.php';
            
            // Backup local y metadata EXIF
            $localFotoPath = UploadHelper::saveLocalAndEmbedMetadata(
                $_FILES['foto']['tmp_name'], 
                $_FILES['foto']['name'], 
                $nombre, 
                $propietario, 
                $latitud, 
                $longitud
            );

            // Subir a ImgBB para la app
            $imgUrl = UploadHelper::uploadToImgBB($_FILES['foto']['tmp_name']);
            if ($imgUrl) {
                $foto = $imgUrl;
            } elseif ($localFotoPath) {
                // Fallback a la foto local si falla ImgBB
                $foto = $localFotoPath;
            }
        }

        if ($nombre && $propietario && $latitud && $longitud) {
            $modelo = new PuntoVentaModel();
            $modelo->create($nombre, $propietario, $latitud, $longitud, $foto);
            $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => 'Punto de venta registrado.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'Faltan datos requeridos.'];
        }
        
        header('Location: ' . BASE_URL . 'mapa/admin');
        exit;
    }

    public function update(): void {
        $this->requireAdmin();
        
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $propietario = $_POST['propietario'] ?? '';
        $latitud = $_POST['latitud'] ?? '';
        $longitud = $_POST['longitud'] ?? '';
        $foto = null;

        if (!$id) {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'ID de punto de venta no proporcionado.'];
            header('Location: ' . BASE_URL . 'mapa/admin');
            exit;
        }

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            require_once __DIR__ . '/../helpers/UploadHelper.php';
            
            // Backup local y metadata EXIF
            $localFotoPath = UploadHelper::saveLocalAndEmbedMetadata(
                $_FILES['foto']['tmp_name'], 
                $_FILES['foto']['name'], 
                $nombre, 
                $propietario, 
                $latitud, 
                $longitud
            );

            // Subir a ImgBB para la app
            $imgUrl = UploadHelper::uploadToImgBB($_FILES['foto']['tmp_name']);
            if ($imgUrl) {
                $foto = $imgUrl;
            } elseif ($localFotoPath) {
                // Fallback a la foto local si falla ImgBB
                $foto = $localFotoPath;
            }
        }

        if ($nombre && $propietario && $latitud && $longitud) {
            $modelo = new PuntoVentaModel();
            $modelo->update($id, $nombre, $propietario, $latitud, $longitud, $foto);
            $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => 'Punto de venta actualizado.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'Faltan datos requeridos.'];
        }
        
        header('Location: ' . BASE_URL . 'mapa/admin');
        exit;
    }

    public function delete(): void {
        $this->requireAdmin();
        $id = $_GET['id'] ?? 0;
        
        if ($id) {
            $modelo = new PuntoVentaModel();
            $punto = $modelo->getById($id);
            if ($punto && $punto['foto'] && strpos($punto['foto'], 'http') !== 0 && file_exists(__DIR__ . '/../' . $punto['foto'])) {
                unlink(__DIR__ . '/../' . $punto['foto']);
            }
            $modelo->delete($id);
            $_SESSION['flash'] = ['type' => 'success', 'title' => 'Éxito', 'message' => 'Punto de venta eliminado.'];
        }
        
        header('Location: ' . BASE_URL . 'mapa/admin');
        exit;
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    private function requireCliente(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cliente') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
}
