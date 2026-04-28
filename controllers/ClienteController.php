<?php
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';
require_once __DIR__ . '/../config/config.php';

class ClienteController
{
    private AuditoriaModel $audit;

    public function __construct() {
        $this->audit = new AuditoriaModel();
    }

    public function nuevo(): void
    {
        $this->requireAuth();
        $this->render('clientes/nuevo');
    }

    public function registro(): void
    {
        $this->render('clientes/registro');
    }

    public function registerPublic(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $dni = trim($data['dni'] ?? '');
        $nombre = trim($data['nombre'] ?? '');
        $celular = trim($data['celular'] ?? '');
        $password = trim($data['password'] ?? '');
        $dep = trim($data['departamento'] ?? 'Tacna');

        if (!$dni || !$nombre || !$celular || !$password) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        if (strlen($dni) !== 8) {
            echo json_encode(['success' => false, 'message' => 'El DNI debe tener 8 dígitos.']);
            exit;
        }

        $model = new ClienteModel();

        // Validar si ya existe
        if ($model->findByDni($dni)) {
            echo json_encode(['success' => false, 'message' => 'Este DNI ya está registrado. Por favor inicia sesión.']);
            exit;
        }

        $codigo = $model->generarCodigo();
        $token = hash_hmac('sha256', $codigo, SECRET_KEY);

        $id = $model->create([
            'codigo' => $codigo,
            'dni' => $dni,
            'nombre' => $nombre,
            'razon_social' => null,
            'tipo_cliente' => 'Normal',
            'ruc' => null,
            'celular' => $celular,
            'direccion' => '',
            'departamento' => $dep,
            'token' => $token,
            'password' => hash('sha256', $password),
            'creado_por' => null,
        ]);

        if ($id) {
            $this->audit->registrar($id, 'AUTOREGISTRO_CLIENTE', "Nuevo cliente registrado vía web: $nombre ($codigo)", 'SEGURIDAD');
        }

        echo json_encode(['success' => true, 'message' => 'Registro exitoso. Ya puedes iniciar sesión.']);
        exit;
    }

    public function create(): void
    {
        $this->requireAuth();
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $tipo_cliente = trim($data['tipo_cliente'] ?? 'Normal');

        // Backend Restriction: Only admins can register non-Normal clients
        if ($_SESSION['rol'] !== 'admin' && $tipo_cliente !== 'Normal') {
            echo json_encode(['success' => false, 'message' => 'No tienes permisos para registrar este tipo de cliente.']);
            exit;
        }
        $dni = trim($data['dni'] ?? '');
        $ruc = trim($data['ruc'] ?? '');
        $razon_social = trim($data['razon_social'] ?? '');
        $nombre = trim($data['nombre'] ?? '');
        $celular = trim($data['celular'] ?? '');
        $dir = trim($data['direccion'] ?? '');
        $dep = $_SESSION['departamento'] ?? null;

        if (!$nombre || !$celular) {
            echo json_encode(['success' => false, 'message' => 'El Nombre del Contacto y el celular son obligatorios.']);
            exit;
        }

        if ($tipo_cliente === 'Normal') {
            if (!$dni || !preg_match('/^\d{8}$/', $dni)) {
                echo json_encode(['success' => false, 'message' => 'El DNI debe tener exactamente 8 dígitos.']);
                exit;
            }
            $ruc = null; // Clean ruc for Normal
            $razon_social = null;
        }
        else {
            if (!$ruc || !preg_match('/^\d{11}$/', $ruc)) {
                echo json_encode(['success' => false, 'message' => 'El RUC debe tener exactamente 11 dígitos.']);
                exit;
            }
            if (!$razon_social) {
                echo json_encode(['success' => false, 'message' => 'La Razón Social es obligatoria para empresas.']);
                exit;
            }
            $dni = null; // Clean dni for Empresas
        }

        // Validaciones extra
        if ($tipo_cliente === 'Normal' && !preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u', $nombre)) {
            echo json_encode(['success' => false, 'message' => 'El nombre de persona solo debe contener letras.']);
            exit;
        }

        if (!preg_match('/^\d{9}$/', $celular)) {
            echo json_encode(['success' => false, 'message' => 'El celular debe tener exactamente 9 dígitos.']);
            exit;
        }

        $model = new ClienteModel();

        // Validar DNI / RUC duplicado
        if ($tipo_cliente === 'Normal') {
            $existenteDni = $model->findByDni($dni);
            if ($existenteDni) {
                echo json_encode([
                    'success' => true,
                    'existing' => true,
                    'message' => 'El cliente ya estaba registrado por DNI.',
                    'id' => $existenteDni['id'],
                    'codigo' => $existenteDni['codigo'],
                ]);
                exit;
            }
        }
        else {
            $existenteRuc = $model->findByRuc($ruc);
            if ($existenteRuc) {
                echo json_encode([
                    'success' => true,
                    'existing' => true,
                    'message' => 'El cliente ya estaba registrado por RUC.',
                    'id' => $existenteRuc['id'],
                    'codigo' => $existenteRuc['codigo'],
                ]);
                exit;
            }
        }

        // Celular duplicado → devolver el existente
        $existente = $model->findByCelular($celular);
        if ($existente) {
            echo json_encode([
                'success' => true,
                'existing' => true,
                'message' => 'El cliente ya estaba registrado por celular.',
                'id' => $existente['id'],
                'codigo' => $existente['codigo'],
            ]);
            exit;
        }

        $codigo = $model->generarCodigo();
        $token = hash_hmac('sha256', $codigo, SECRET_KEY);

        $defaultPassword = ($tipo_cliente === 'Normal') ? $dni : $ruc;

        $id = $model->create([
            'codigo' => $codigo,
            'dni' => $dni,
            'nombre' => $nombre,
            'razon_social' => $razon_social,
            'tipo_cliente' => $tipo_cliente,
            'ruc' => $ruc,
            'celular' => $celular,
            'direccion' => $dir,
            'departamento' => $dep,
            'token' => $token,
            'password' => hash('sha256', $defaultPassword),
            'creado_por' => $_SESSION['id_usuario'],
        ]);

        if ($id) {
            $this->audit->registrar($_SESSION['id_usuario'], 'REGISTRO_CLIENTE', "Nuevo cliente: $nombre ($codigo)", 'CLIENTES');
        }

        echo json_encode(['success' => true, 'id' => $id, 'codigo' => $codigo]);
        exit;
    }

    public function exito(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['id'] ?? 0);
        $model = new ClienteModel();
        $cliente = $model->findById($id);
        if (!$cliente) {
            $this->redirect('panel');
        }
        $this->render('clientes/exito', ['cliente' => $cliente]);
    }

    public function imprimir(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['id'] ?? 0);
        $model = new ClienteModel();
        $cliente = $model->findById($id);
        if (!$cliente) {
            $this->redirect('panel');
        }
        $this->render('clientes/imprimir', ['cliente' => $cliente]);
    }

    public function lista(): void
    {
        $this->requireAuth();
        $model = new ClienteModel();
        $clientes = $model->getAll();
        $this->render('clientes/lista', ['clientes' => $clientes]);
    }

    public function editar(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $model = new ClienteModel();
        $cliente = $model->findById($id);

        if (!$cliente) {
            $this->redirect('clientes/lista');
        }

        $this->render('clientes/editar', ['cliente' => $cliente]);
    }

    public function update(): void
    {
        $this->requireAdmin();
        $id = (int)($_POST['id'] ?? 0);
        $model = new ClienteModel();

        $clienteOriginal = $model->findById($id);
        if (!$clienteOriginal) {
            $this->redirect('clientes/lista');
        }

        $data = [
            'tipo_cliente' => trim($_POST['tipo_cliente'] ?? 'Normal'),
            'dni' => trim($_POST['dni'] ?? ''),
            'ruc' => trim($_POST['ruc'] ?? ''),
            'razon_social' => trim($_POST['razon_social'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'celular' => trim($_POST['celular'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'departamento' => $clienteOriginal['departamento'], // Keep orginal department
            'estado' => (int)($_POST['estado'] ?? 1),
        ];

        if (!$data['nombre'] || !$data['celular']) {
            echo json_encode(['success' => false, 'message' => 'El nombre del contacto y celular son obligatorios.']);
            exit;
        }

        if ($data['tipo_cliente'] === 'Normal') {
            if (!$data['dni'] || !preg_match('/^\d{8}$/', $data['dni'])) {
                echo json_encode(['success' => false, 'message' => 'El DNI debe tener exactamente 8 dígitos.']);
                exit;
            }
            $data['ruc'] = null;
            $data['razon_social'] = null;
        }
        else {
            if (!$data['ruc'] || !preg_match('/^\d{11}$/', $data['ruc'])) {
                echo json_encode(['success' => false, 'message' => 'El RUC debe tener exactamente 11 dígitos.']);
                exit;
            }
            if (!$data['razon_social']) {
                echo json_encode(['success' => false, 'message' => 'La Razón Social es obligatoria.']);
                exit;
            }
            $data['dni'] = null;
        }

        if ($data['tipo_cliente'] === 'Normal' && !preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+$/u', $data['nombre'])) {
            echo json_encode(['success' => false, 'message' => 'El nombre solo debe contener letras.']);
            exit;
        }

        if (!preg_match('/^\d{9}$/', $data['celular'])) {
            echo json_encode(['success' => false, 'message' => 'El celular debe tener exactamente 9 dígitos.']);
            exit;
        }

        // Detectar cambios antes vs después
        $changes = [];
        $fieldsToTrack = ['tipo_cliente', 'dni', 'ruc', 'razon_social', 'nombre', 'celular', 'direccion', 'estado'];
        foreach ($fieldsToTrack as $f) {
            $valAnt = $clienteOriginal[$f] ?? null;
            $valDev = $data[$f] ?? null;
            if (trim((string)$valAnt) !== trim((string)$valDev)) {
                $changes[$f] = ['ant' => $valAnt, 'des' => $valDev];
            }
        }

        if ($model->update($id, $data)) {
            $desc = "Editó datos del cliente: " . $data['nombre'];
            if (!empty($changes)) $desc .= " (" . count($changes) . " campos modificados)";
            $this->audit->registrar($_SESSION['id_usuario'], 'ACTUALIZAR_CLIENTE', $desc, 'CLIENTES', $changes);
            echo json_encode(['success' => true, 'message' => 'Cliente actualizado correctamente.']);
        }
        else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el cliente.']);
        }
        exit;
    }

    public function cambiarEstado(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $estado = (int)($_GET['v'] ?? 1);
        $model = new ClienteModel();
        
        $c = $model->findById($id);

        if ($model->setEstado($id, $estado)) {
            $accion = $estado ? 'ALTA_CLIENTE' : 'BAJA_CLIENTE';
            $msgStatus = $estado ? 'Activó' : 'Inactivó';
            $this->audit->registrar($_SESSION['id_usuario'], $accion, "$msgStatus al cliente: " . ($c['nombre'] ?? 'ID '.$id), 'CLIENTES');
            
            $_SESSION['flash'] = ['type' => 'success', 'title' => '¡Hecho!', 'message' => ($estado ? 'Cliente activado.' : 'Cliente inactivado.')];
        }
        else {
            $_SESSION['flash'] = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo cambiar el estado.'];
        }
        $this->redirect('clientes/lista');
    }

    public function consultarDni(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $dni = $_GET['dni'] ?? null;

        if (!$dni || strlen($dni) !== 8 || !is_numeric($dni)) {
            echo json_encode(['success' => false, 'message' => 'DNI inválido.']);
            exit;
        }

        $url = 'https://api.apis.net.pe/v1/dni?numero=' . $dni;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (isset($data['nombres'])) {
                $nombreC = trim($data['nombres']) . ' ' . trim($data['apellidoPaterno'] ?? '') . ' ' . trim($data['apellidoMaterno'] ?? '');
                $nombreC = mb_convert_case(mb_strtolower(trim($nombreC), 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                echo json_encode(['success' => true, 'data' => ['nombre_completo' => $nombreC]]);
                exit;
            }
            else if (isset($data['nombre'])) {
                $nombreC = mb_convert_case(mb_strtolower(trim($data['nombre']), 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                echo json_encode(['success' => true, 'data' => ['nombre_completo' => $nombreC]]);
                exit;
            }
        }

        echo json_encode(['success' => false, 'message' => 'No se encontraron resultados o API inactiva para el DNI ' . $dni]);
        exit;
    }

    public function consultarRuc(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $ruc = $_GET['ruc'] ?? null;
        if (!$ruc || strlen($ruc) !== 11 || !is_numeric($ruc)) {
            echo json_encode(['success' => false, 'message' => 'RUC inválido.']);
            exit;
        }

        $url = 'https://api.apis.net.pe/v1/ruc?numero=' . $ruc;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (isset($data['nombre']) && $data['nombre']) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'razon_social' => trim($data['nombre']),
                        'direccion' => trim($data['direccion'] ?? '')
                    ]
                ]);
                exit;
            }
        }

        echo json_encode(['success' => false, 'message' => 'RUC no encontrado o API no disponible. Digite manualmente.']);
        exit;
    }

    public function updateProfile(): void
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['id_cliente'])) {
            echo json_encode(['success' => false, 'message' => 'Sesión no válida.']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $celular = trim($data['celular'] ?? '');
        $direccion = trim($data['direccion'] ?? '');

        if (!$celular) {
            echo json_encode(['success' => false, 'message' => 'El celular es obligatorio.']);
            exit;
        }

        if (!preg_match('/^\d{9}$/', $celular)) {
            echo json_encode(['success' => false, 'message' => 'El celular debe tener 9 dígitos.']);
            exit;
        }

        $model = new ClienteModel();
        $updateData = [
            'celular' => $celular,
            'direccion' => $direccion
        ];

        if ($model->updateBasicInfo($_SESSION['id_cliente'], $updateData)) {
            $this->audit->registrar($_SESSION['id_cliente'], 'ACTUALIZAR_PERFIL_PROPIO', 'Cliente actualizó sus datos de contacto', 'SEGURIDAD');
            echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el perfil.']);
        }
        exit;
    }

    public function changePassword(): void
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['id_cliente'])) {
            echo json_encode(['success' => false, 'message' => 'Sesión no válida.']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $newPass = trim($data['password'] ?? '');

        if (strlen($newPass) < 4) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 4 caracteres.']);
            exit;
        }

        $model = new ClienteModel();
        if ($model->updatePassword($_SESSION['id_cliente'], $newPass)) {
            $this->audit->registrar($_SESSION['id_cliente'], 'CAMBIO_PASSWORD', 'Cliente actualizó su contraseña', 'SEGURIDAD');
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la contraseña.']);
        }
        exit;
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

    private function requireAuth(): void
    {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    private function requireAdmin(): void
    {
        $this->requireAuth();
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }
}
