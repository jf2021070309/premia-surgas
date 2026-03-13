<?php
// ─── Bootstrap ───────────────────────────────────────────
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

session_start();

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
    ['GET',  'panel',       'PanelController',   'index'],

    // —— Clientes ——
    ['GET',  'clientes/nuevo',    'ClienteController', 'nuevo'],
    ['POST', 'clientes/create',   'ClienteController', 'create'],
    ['GET',  'clientes/exito',    'ClienteController', 'exito'],
    ['GET',  'clientes/imprimir', 'ClienteController', 'imprimir'],
    ['GET',  'clientes/lista',    'ClienteController', 'lista'],
    ['GET',  'clientes/editar',   'ClienteController', 'editar'],
    ['POST', 'clientes/update',   'ClienteController', 'update'],
    ['GET',  'clientes/estado',   'ClienteController', 'cambiarEstado'],

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

    // —— Reportes ——
    ['GET',  'reportes',     'ReporteController', 'index'],

    // —— Configuración ——
    ['GET',  'configuraciones',        'ConfiguracionController', 'index'],
    ['POST', 'configuraciones/update', 'ConfiguracionController', 'update'],

    // —— Tipos de Operaciones ——
    ['GET',    'operaciones',        'OperacionController', 'index'],
    ['POST',   'operaciones/create', 'OperacionController', 'create'],
    ['POST',   'operaciones/update', 'OperacionController', 'update'],
    ['GET',    'operaciones/delete', 'OperacionController', 'delete'],
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
