<?php
// ============================================================
// router.php — Para el servidor built-in de PHP
// Este archivo NO afecta XAMPP (usa .htaccess).
// Sólo se usa cuando se arranca con: php -S ... router.php
// ============================================================

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Servir archivos estáticos directamente (css, js, imágenes, etc.)
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Extraer la URL sin la barra inicial y pasarla como parámetro
$_GET['url'] = ltrim($uri, '/');

// Delegar al front controller
require_once __DIR__ . '/index.php';
