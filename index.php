<?php
// ─── Bootstrap ───────────────────────────────────────────
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/helpers/WhatsAppService.php';

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
    ['POST', 'panel/validar-venta',       'PanelController',   'validarVenta'],

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
    ['POST', 'clientes/promover-afiliado', 'ClienteController', 'promoverAfiliado'],
    ['POST', 'clientes/changePassword', 'ClienteController', 'changePassword'],
    ['POST', 'clientes/updateProfile',  'ClienteController', 'updateProfile'],

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

    // —— Afiliados (Admin) ——
    ['GET',  'afiliados',              'AfiliadoController', 'index'],
    ['GET',  'afiliados/nuevo',        'AfiliadoController', 'nuevo'],
    ['POST', 'afiliados/create',       'AfiliadoController', 'create'],
    ['GET',  'afiliados/editar',       'AfiliadoController', 'editar'],
    ['POST', 'afiliados/update',       'AfiliadoController', 'update'],
    ['GET',  'afiliados/delete',       'AfiliadoController', 'delete'],
    ['GET',  'afiliados/mi-historial', 'AfiliadoController', 'miHistorial'],
    ['GET',  'afiliados/miAnuncio',    'AfiliadoController', 'miAnuncio'],
    ['POST', 'afiliados/guardarAnuncio', 'AfiliadoController', 'guardarAnuncio'],
    ['GET',  'afiliados/perfil',       'AfiliadoController', 'perfil'],
    ['POST', 'afiliados/actualizarPerfil', 'AfiliadoController', 'actualizarPerfil'],

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

    // —— Gestión de Puntos Pendientes (Admin) ——
    ['GET',  'puntos-admin',              'PuntosAdminController', 'index'],
    ['POST', 'puntos-admin/actualizar',   'PuntosAdminController', 'actualizarEstado'],

    // —— Sistema de Incentivos (Admin) ——
    ['GET',  'incentivos/reglas',           'IncentivoController', 'reglas'],
    ['POST', 'incentivos/reglas/create',    'IncentivoController', 'createRegla'],
    ['POST', 'incentivos/reglas/update',    'IncentivoController', 'updateRegla'],
    ['GET',  'incentivos/reglas/delete',    'IncentivoController', 'deleteRegla'],
    ['GET',  'incentivos/vales',            'IncentivoController', 'vales'],
    ['POST', 'incentivos/vales/usar',       'IncentivoController', 'marcarUsado'],
    ['POST', 'incentivos/vales/cancelar',   'IncentivoController', 'cancelarVale'],
    ['GET',  'incentivos/progresoJson',     'IncentivoController', 'progresoJson'],

    // —— Configuración General (Unificada) ——
    ['GET',  'ajustes',               'AjustesController', 'index'],
    ['POST', 'ajustes/update-puntos', 'AjustesController', 'updatePuntos'],

    // —— Tipos de Operaciones ——
    ['GET',    'operaciones',        'OperacionController', 'index'],
    ['POST',   'operaciones/create', 'OperacionController', 'create'],
    ['POST',   'operaciones/update', 'OperacionController', 'update'],
    ['GET',    'operaciones/delete', 'OperacionController', 'delete'],
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
