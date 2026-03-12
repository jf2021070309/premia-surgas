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

    // —— QR ——
    ['GET',  'qr/generate',  'QrController',     'generate'],

    // —— Scan (escaneo de QR) ——
    ['GET',  'scan',         'ScanController',   'index'],
    ['POST', 'scan/venta',   'ScanController',   'venta'],
];

$matched = false;
foreach ($routes as [$routeMethod, $pattern, $controllerName, $action]) {
    if ($method === $routeMethod && $url === $pattern) {
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
}

if (!$matched) {
    // Archivo estático o ruta no definida
    http_response_code(404);
    echo "<h1>404 — Página no encontrada</h1><a href='" . BASE_URL . "panel'>Volver al Panel</a>";
}
