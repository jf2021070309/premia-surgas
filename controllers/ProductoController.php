<?php
require_once __DIR__ . '/../models/PremioModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class ProductoController {
    private AuditoriaModel $audit;

    public function __construct() {
        $this->audit = new AuditoriaModel();
    }
    
    public function index(): void {
        $this->requireAdmin();
        $model = new PremioModel();
        $productos = $model->getAll();
        $this->render('productos/index', ['productos' => $productos]);
    }

    public function nuevo(): void {
        $this->requireAdmin();
        $this->render('productos/formulario', ['titulo' => 'Nuevo Producto', 'producto' => null]);
    }

    public function create(): void {
        $this->requireAdmin();
        $model = new PremioModel();
        
        $nombre_img = $_POST['nombre_imagen'] ?? uniqid();
        $imagen_final = $this->handleUpload($nombre_img);

        $data = [
            'nombre'      => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'puntos'      => (int)($_POST['puntos'] ?? 0),
            'stock'       => (int)($_POST['stock'] ?? 0),
            'imagen'      => $imagen_final,
            'estado'      => (int)($_POST['estado'] ?? 1),
        ];

        if ($model->create($data)) {
            $this->audit->registrar($_SESSION['id_usuario'], 'NUEVO_PRODUCTO', "Creó producto: " . ($data['nombre']), 'PRODUCTOS');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Producto creado correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo crear el producto.'];
        }
        
        $redir = $_POST['redir'] ?? 'productos';
        $this->redirect($redir);
    }

    public function editar(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $model = new PremioModel();
        $producto = $model->findById($id);
        
        if (!$producto) {
            $this->redirect('productos');
        }

        $this->render('productos/formulario', ['titulo' => 'Editar Producto', 'producto' => $producto]);
    }

    public function update(): void {
        $this->requireAdmin();
        $id = (int)($_POST['id'] ?? 0);
        $model = new PremioModel();
        $producto_actual = $model->findById($id);

        if (!$producto_actual) {
            $this->redirect('productos');
            return;
        }
        
        $nombre_img = $_POST['nombre_imagen'] ?? pathinfo($producto_actual['imagen'], PATHINFO_FILENAME);
        if (empty($nombre_img)) $nombre_img = uniqid();

        $imagen_final = $this->handleUpload($nombre_img, $producto_actual['imagen']);

        $data = [
            'nombre'      => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'puntos'      => (int)($_POST['puntos'] ?? 0),
            'stock'       => (int)($_POST['stock'] ?? 0),
            'imagen'      => $imagen_final,
            'estado'      => (int)($_POST['estado'] ?? 1),
        ];

        // Track cambios
        $changes = [];
        $fields = ['nombre', 'descripcion', 'puntos', 'stock', 'estado'];
        foreach($fields as $f) {
            if (trim((string)$producto_actual[$f]) !== trim((string)$data[$f])) {
                $changes[$f] = ['ant' => $producto_actual[$f], 'des' => $data[$f]];
            }
        }

        if ($model->update($id, $data)) {
            $desc = "Actualizó producto: " . ($data['nombre']);
            if(!empty($changes)) $desc .= " (" . count($changes) . " campos modificados)";
            $this->audit->registrar($_SESSION['id_usuario'], 'ACTUALIZAR_PRODUCTO', $desc, 'PRODUCTOS', $changes);
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Producto actualizado correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar the product.'];
        }
        
        $redir = $_POST['redir'] ?? 'productos';
        $this->redirect($redir);
    }

    private function handleUpload(string $nombre_base, string $imagen_anterior = ''): string {
        if (!isset($_FILES['imagen_file']) || $_FILES['imagen_file']['error'] !== UPLOAD_ERR_OK) {
            return $imagen_anterior;
        }

        $upload_dir = __DIR__ . '/../assets/premios/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        // Limpiar nombre y forzar .png
        $nombre_base = preg_replace('/[^a-z0-9_\-]/i', '_', $nombre_base);
        $filename = $nombre_base . '.png';
        $target_path = $upload_dir . $filename;

        // Si hay una imagen anterior y tiene nombre diferente, o si queremos borrar el archivo físico antes de reemplazarlo
        if ($imagen_anterior && file_exists($upload_dir . $imagen_anterior)) {
            @unlink($upload_dir . $imagen_anterior);
        }

        if (move_uploaded_file($_FILES['imagen_file']['tmp_name'], $target_path)) {
            return $filename;
        }

        return $imagen_anterior;
    }

    public function delete(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $model = new PremioModel();
        
        $p = $model->findById($id);

        if ($model->delete($id)) {
            $this->audit->registrar($_SESSION['id_usuario'], 'BAJA_PRODUCTO', "Inactivó el producto: " . ($p['nombre'] ?? 'ID '.$id), 'PRODUCTOS');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Producto inactivado.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo inactivar el producto.'];
        }
        
        $redir = $_GET['redir'] ?? 'productos';
        $this->redirect($redir);
    }

    // ── helpers ──────────────────────────────────────────────────

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function redirect(string $path): void {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }
}
