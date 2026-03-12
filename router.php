<?php
// ============================================================
// router.php — Para el servidor built-in de PHP
// Este archivo NO afecta XAMPP (usa .htaccess).
// Sólo se usa cuando se arranca con: php -S ... router.php
// ============================================================

// Extraer path sin query string y normalizar dobles slashes
// NOTA: parse_url('//login', PHP_URL_PATH) devuelve null (lo trata como host)
//       por eso usamos explode directamente.
$raw  = $_SERVER['REQUEST_URI'] ?? '/';
$path = explode('?', $raw, 2)[0];           // quitar query string
$path = '/' . ltrim(rawurldecode($path), '/'); // normalizar //login → /login

// Servir archivos estáticos directamente (css, js, imágenes, etc.)
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false;
}

// Extraer la URL sin la barra inicial y pasarla como parámetro
$_GET['url'] = ltrim($path, '/');

// Delegar al front controller
require_once __DIR__ . '/index.php';
