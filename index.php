<?php
// ─── Bootstrap ───────────────────────────────────────────
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

session_start();

// ─── Verificación de Sesión Única ────────────────────────
if (isset($_SESSION['id_usuario']) && isset($_SESSION['session_id'])) {
    $db = Database::getConnection();
    $id = $_SESSION['id_usuario'];
    $rol = $_SESSION['rol'] ?? 'cliente';
    
    $table = ($rol === 'cliente') ? 'clientes' : 'usuarios';
    $stmt = $db->prepare("SELECT session_id FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    $userSession = $stmt->fetchColumn();

    if ($userSession !== $_SESSION['session_id']) {
        session_destroy();
        
        // Si es una petición AJAX (Fetch/Axios/XHR)
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        if ($isAjax || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'session_expired']);
            exit;
        }

        header('Location: ' . BASE_URL . 'login?error=session_expired');
        exit;
    }
}

// ─── Resolve route ───────────────────────────────────────
$url    = trim($_GET['url'] ?? '', '/');
$method = $_SERVER['REQUEST_METHOD'];

// Normalizar: quitar query string del url param (strtok devuelve false con string vacío)
$url = (string) strtok($url, '?');

// ─── Router ──────────────────────────────────────────────
// Formato de rutas: [METHOD, pattern, Controlador, accion]
$routes = [
    // —— Auth ——
    ['GET',  '',            'AuthController',    'login'],
    ['GET',  'login',       'AuthController',    'login'],
    ['POST', 'login',       'AuthController',    'doLogin'],
    ['GET',  'logout',      'AuthController',    'logout'],

    // —— Panel ——
    ['GET',  'panel',                     'PanelController',   'index'],
    ['GET',  'panel/live-notifications',  'PanelController',   'liveNotifications'],
    ['GET',  'panel/conductor-history',   'PanelController',   'conductorHistory'],

    // —— Clientes ——
    ['GET',  'registro',          'ClienteController', 'registro'],
    ['POST', 'clientes/register', 'ClienteController', 'registerPublic'],
    ['GET',  'clientes/nuevo',    'ClienteController', 'nuevo'],
    ['POST', 'clientes/create',   'ClienteController', 'create'],
    ['GET',  'clientes/exito',    'ClienteController', 'exito'],
    ['GET',  'clientes/imprimir', 'ClienteController', 'imprimir'],
    ['GET',  'clientes/lista',    'ClienteController', 'lista'],
    ['GET',  'clientes/editar',   'ClienteController', 'editar'],
    ['POST', 'clientes/update',   'ClienteController', 'update'],
    ['GET',  'clientes/estado',   'ClienteController', 'cambiarEstado'],
    ['GET',  'clientes/consultarDni', 'ClienteController', 'consultarDni'],
    ['GET',  'clientes/consultarRuc', 'ClienteController', 'consultarRuc'],

    // —— Productos (Admin) ——
    ['GET',  'productos',          'ProductoController', 'index'],
    ['GET',  'productos/nuevo',    'ProductoController', 'nuevo'],
    ['POST', 'productos/create',   'ProductoController', 'create'],
    ['GET',  'productos/editar',   'ProductoController', 'editar'],
    ['POST', 'productos/update',   'ProductoController', 'update'],
    ['GET',  'productos/delete',   'ProductoController', 'delete'],

    // —— Conductores (Admin) ——
    ['GET',  'conductores',          'ConductorController', 'index'],
    ['GET',  'conductores/nuevo',    'ConductorController', 'nuevo'],
    ['POST', 'conductores/create',   'ConductorController', 'create'],
    ['GET',  'conductores/editar',   'ConductorController', 'editar'],
    ['POST', 'conductores/update',   'ConductorController', 'update'],
    ['GET',  'conductores/delete',   'ConductorController', 'delete'],
    ['GET',  'conductores/mi-historial', 'ConductorController', 'miHistorial'],

    // —— Aliados (Admin) ——
    ['GET',  'aliados',              'AliadoController', 'index'],
    ['GET',  'aliados/nuevo',        'AliadoController', 'nuevo'],
    ['POST', 'aliados/create',       'AliadoController', 'create'],
    ['GET',  'aliados/editar',       'AliadoController', 'editar'],
    ['POST', 'aliados/update',       'AliadoController', 'update'],
    ['GET',  'aliados/delete',       'AliadoController', 'delete'],
    ['GET',  'aliados/mi-historial', 'AliadoController', 'miHistorial'],

    // —— QR ——
    ['GET',  'qr/generate',  'QrController',     'generate'],

    // —— Scan (escaneo de QR) ——
    ['GET',  'scan',           'ScanController',   'index'],
    ['POST', 'scan/buscar',    'ScanController',   'buscar'],
    ['POST', 'scan/registrar', 'ScanController',   'registrar'],
    ['POST', 'scan/venta',     'ScanController',   'venta'],

    // —— Tienda ——
    ['GET',  'tienda',       'TiendaController', 'index'],
    ['GET',  'tienda/historial', 'TiendaController', 'historial'],
    ['POST', 'tienda/canjear', 'TiendaController', 'canjear'],
    ['POST', 'tienda/recargar', 'TiendaController', 'recargar'],
    ['GET',  'tienda/check-pendientes', 'TiendaController', 'checkPendientes'],

    // —— Reportes ——
    ['GET',  'reportes',          'ReporteController', 'index'],
    ['GET',  'reporte/auditoria', 'ReporteController', 'auditoria'],
    ['GET',  'reporte/getAuditLogsJson', 'ReporteController', 'getAuditLogsJson'],

    // —— Gestión de Canjes (Admin) ——
    ['GET',  'canjes-admin',            'CanjeAdminController', 'index'],
    ['POST', 'canjes-admin/actualizar', 'CanjeAdminController', 'actualizarEstado'],

    // —— Gestión de Recargas de Puntos (Admin) ——
    ['GET',  'recargas-admin',              'RecargaAdminController', 'index'],
    ['POST', 'recargas-admin/actualizar',   'RecargaAdminController', 'actualizarEstado'],
    ['POST', 'recargas-admin/subir-qr',     'RecargaAdminController', 'subirQr'],

    // —— Configuración General (Unificada) ——
    ['GET',  'ajustes',               'AjustesController', 'index'],
    ['POST', 'ajustes/update-puntos', 'AjustesController', 'updatePuntos'],

    // —— Tipos de Operaciones ——
    ['GET',    'operaciones',        'OperacionController', 'index'],
    ['POST',   'operaciones/create', 'OperacionController', 'create'],
    ['POST',   'operaciones/update', 'OperacionController', 'update'],
    ['GET',    'auth/check',          'AuthController',   'checkSession'],
];

$matched = false;
foreach ($routes as [$routeMethod, $pattern, $controllerName, $action]) {
    if ($method !== $routeMethod || $url !== $pattern) continue;


    // ── Ruta normal con controlador ───────────────────────
    $controllerFile = __DIR__ . "/controllers/{$controllerName}.php";
    if (!file_exists($controllerFile)) {
        http_response_code(500);
        die("Controlador no encontrado: {$controllerName}");
    }
    require_once $controllerFile;
    $controller = new $controllerName();
    $controller->$action();
    $matched = true;
    break;
}

if (!$matched) {
    // Archivo estático o ruta no definida
    http_response_code(404);
    echo "<h1>404 — Página no encontrada</h1><a href='" . BASE_URL . "panel'>Volver al Panel</a>";
}
