<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../models/AfiliadoAnuncioModel.php';
require_once __DIR__ . '/../helpers/UploadHelper.php';


class AfiliadoController
{
    private AuditoriaModel $audit;

    public function __construct() {
        $this->audit = new AuditoriaModel();
    }

    public function index(): void
    {
        $this->requireAdmin();
        $model = new UsuarioModel();
        $afiliados = $model->getAllAfiliados();
        $this->render('afiliados/index', ['afiliados' => $afiliados]);
    }

    public function nuevo(): void
    {
        $this->requireAdmin();
        $this->render('afiliados/formulario', ['titulo' => 'Nuevo Afiliado Comercial', 'afiliado' => null]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $model = new UsuarioModel();

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'usuario' => $_POST['usuario'] ?? '',
            'password' => $_POST['password'] ?? '',
            'rol' => 'afiliado',
            'estado' => (int) ($_POST['estado'] ?? 1),
            'departamento' => $_POST['departamento'] ?? null,
        ];

        if ($model->create($data)) {
            $this->audit->registrar($_SESSION['id_usuario'], 'NUEVO_AFILIADO', "Registró al afiliado: " . ($data['nombre']), 'AFILIADOS');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Afiliado comercial registrado correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo registrar al afiliado.'];
        }

        $redir = $_POST['redir'] ?? 'afiliados';
        $this->redirect($redir);
    }

    public function editar(): void
    {
        $this->requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new UsuarioModel();
        $afiliado = $model->findById($id);

        if (!$afiliado || $afiliado['rol'] !== 'afiliado') {
            $this->redirect('afiliados');
        }

        $this->render('afiliados/formulario', ['titulo' => 'Editar Afiliado', 'afiliado' => $afiliado]);
    }

    public function update(): void
    {
        $this->requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        $model = new UsuarioModel();
        
        $afiliadoOriginal = $model->findById($id);
        if (!$afiliadoOriginal || $afiliadoOriginal['rol'] !== 'afiliado') {
            $this->redirect('afiliados');
        }

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'usuario' => $_POST['usuario'] ?? '',
            'estado' => (int) ($_POST['estado'] ?? 1),
            'departamento' => $_POST['departamento'] ?? null,
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        // Tracking de cambios
        $changes = [];
        $fields = ['nombre', 'usuario', 'estado', 'departamento'];
        foreach($fields as $f) {
            $ant = $afiliadoOriginal[$f] ?? null;
            $des = $data[$f] ?? null;
            if (trim((string)$ant) !== trim((string)$des)) {
                $changes[$f] = ['ant' => $ant, 'des' => $des];
            }
        }

        if ($model->update($id, $data)) {
            $desc = "Actualizó datos del afiliado: " . ($data['nombre']);
            if(!empty($changes)) $desc .= " (" . count($changes) . " campos modificados)";
            $this->audit->registrar($_SESSION['id_usuario'], 'ACTUALIZAR_AFILIADO', $desc, 'AFILIADOS', $changes);
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Información del afiliado actualizada.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar la información.'];
        }

        $redir = $_POST['redir'] ?? 'afiliados';
        $this->redirect($redir);
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new UsuarioModel();
        
        $afiliado = $model->findById($id);

        try {
            if ($model->delete($id)) {
                $this->audit->registrar($_SESSION['id_usuario'], 'ELIMINAR_AFILIADO', "Eliminó definitivamente al afiliado: " . ($afiliado['nombre'] ?? 'ID '.$id), 'AFILIADOS');
                $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'El afiliado ha sido eliminado del sistema.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo eliminar al afiliado.'];
            }
        } catch (PDOException $e) {
            if ($e->getCode() == "23000") {
                $_SESSION['flash'] = [
                    'type' => 'warning',
                    'title' => 'Restricción de Seguridad',
                    'message' => 'Este afiliado no puede ser eliminado porque tiene registros de operaciones vinculados.'
                ];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error Crítico', 'message' => 'Ocurrió un error inesperado al eliminar.'];
            }
        }

        $redir = $_GET['redir'] ?? 'afiliados';
        $this->redirect($redir);
    }

    public function miHistorial(): void
    {
        $this->requireAfiliado();
        require_once __DIR__ . '/../models/VentaModel.php';
        
        $model = new VentaModel();
        
        $search = trim($_GET['search'] ?? '');
        $fechaDesde = trim($_GET['fecha_desde'] ?? '');
        $fechaHasta = trim($_GET['fecha_hasta'] ?? '');
        
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $result = $model->getByConductorPaginated($_SESSION['id_usuario'], $offset, $limit, $search, $fechaDesde, $fechaHasta);
        $historial = $result['data'];
        $totalPages = ceil($result['total'] / $limit);
        
        $this->render('afiliados/mi_historial', [
            'historial' => $historial,
            'totalRows' => $result['total'],
            'totalPuntosFiltrado' => $result['total_puntos'],
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
            'titulo' => 'Mi Historial de Asignaciones'
        ]);
    }

    public function miAnuncio(): void
    {
        $this->requireAfiliado();
        $model = new AfiliadoAnuncioModel();
        $userModel = new UsuarioModel();
        
        $anuncio = $model->getByUsuarioId($_SESSION['id_usuario']);
        $usuario = $userModel->findById($_SESSION['id_usuario']);

        $this->render('afiliados/mi_anuncio', [
            'anuncio' => $anuncio,
            'usuario' => $usuario,
            'titulo'  => 'Mi Anuncio Publicitario'
        ]);
    }

    public function perfil(): void
    {
        $this->requireAfiliado();
        $model = new UsuarioModel();
        $usuario = $model->findById($_SESSION['id_usuario']);

        $this->render('afiliados/perfil', [
            'usuario' => $usuario,
            'titulo'  => 'Mi Perfil'
        ]);
    }

    public function actualizarPerfil(): void
    {
        $this->requireAfiliado();
        $id = $_SESSION['id_usuario'];
        $model = new UsuarioModel();
        
        $data = [
            'nombre'  => $_POST['nombre'] ?? '',
            'usuario' => $_POST['usuario'] ?? '',
            'estado'  => 1, // Siempre activo si está editando su perfil
            'departamento' => $_POST['departamento'] ?? null,
            'direccion' => $_POST['direccion'] ?? null,
            'celular' => $_POST['celular'] ?? null,
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        if ($model->update($id, $data)) {
            $_SESSION['nombre_usuario'] = $data['nombre'];
            $this->audit->registrar($id, 'ACTUALIZAR_PERFIL', "El afiliado actualizó su información de perfil", 'PERFIL');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Perfil actualizado correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo actualizar el perfil.'];
        }

        $this->redirect('afiliados/perfil');
    }

    public function guardarAnuncio(): void
    {
        $this->requireAfiliado();
        $model = new AfiliadoAnuncioModel();
        
        $usuarioId = $_SESSION['id_usuario'];
        $nombreNegocio = trim($_POST['nombre_negocio'] ?? '');
        $ubicacion = trim($_POST['ubicacion'] ?? '');
        $celular = trim($_POST['celular'] ?? '');
        $estado = (int)($_POST['estado'] ?? 1);

        // Directorios de subida
        $uploadDir = __DIR__ . '/../assets/uploads/anuncios/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $existing = $model->getByUsuarioId($usuarioId);
        $imagenPath = $existing['imagen_negocio'] ?? null;
        $cartaPath = $existing['carta_pdf'] ?? null;

        // Subir Imagen a ImgBB
        if (isset($_FILES['imagen_negocio']) && $_FILES['imagen_negocio']['error'] === UPLOAD_ERR_OK) {
            $tempPath = $_FILES['imagen_negocio']['tmp_name'];
            $imgUrl = UploadHelper::uploadToImgBB($tempPath);
            if ($imgUrl) {
                $imagenPath = $imgUrl; // Guardamos la URL externa
            }
        }

        // Subir PDF
        if (isset($_FILES['carta_pdf']) && $_FILES['carta_pdf']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['carta_pdf']['name'], PATHINFO_EXTENSION);
            if (strtolower($ext) === 'pdf') {
                $fileName = "pdf_" . $usuarioId . "_" . time() . ".pdf";
                if (move_uploaded_file($_FILES['carta_pdf']['tmp_name'], $uploadDir . $fileName)) {
                    $cartaPath = 'assets/uploads/anuncios/' . $fileName;
                }
            }
        }

        $data = [
            'usuario_id'     => $usuarioId,
            'nombre_negocio' => $nombreNegocio,
            'imagen_negocio' => $imagenPath,
            'carta_pdf'      => $cartaPath,
            'ubicacion'      => $ubicacion,
            'celular'        => $celular,
            'estado'         => $estado,
            'color_fondo'    => trim($_POST['color_fondo'] ?? '#A7D8F5')
        ];

        if ($model->upsert($data)) {
            $this->audit->registrar($usuarioId, 'ACTUALIZAR_ANUNCIO', "Actualizó su anuncio publicitario: $nombreNegocio", 'AFILIADOS');
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Éxito!', 'message' => 'Anuncio actualizado correctamente.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo guardar el anuncio.'];
        }

        $this->redirect('afiliados/miAnuncio');
    }


    // ── helpers ──────────────────────────────────────────────────

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }

    private function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    private function requireAfiliado(): void
    {
        if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'afiliado' && $_SESSION['rol'] !== 'admin')) {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }
}
