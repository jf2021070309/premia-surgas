<?php
// ============================================================
// config/conexion.php
// Compatible con Railway, XAMPP, Hosting y Localhost
// ============================================================

define('DB_CHARSET', 'utf8mb4');

// ============================================================
// Detectar Railway mediante MYSQL_URL
// ============================================================

$mysql_url = getenv('MYSQL_URL');

if ($mysql_url) {

    // ✅ PRODUCCIÓN (Railway)
    $parts = parse_url($mysql_url);

    $host = $parts['host'];
    $port = $parts['port'] ?? 3306;
    $user = $parts['user'];
    $pass = $parts['pass'];
    $db   = ltrim($parts['path'], '/');

} else {

    // ✅ LOCAL (XAMPP / Hosting normal)

    $host = 'localhost';
    $port = 3306;
    $user = 'root';
    $pass = '';
    $db   = 'surgas';

}

// ============================================================
// CONSTANTES DE BD (compatibilidad con Database.php / PDO)
// ============================================================

define('DB_HOST', $host);
define('DB_PORT', (int)$port);
define('DB_NAME', $db);
define('DB_USER', $user);
define('DB_PASS', $pass);

// ============================================================
// CONFIGURACIÓN GLOBAL
// ============================================================

date_default_timezone_set('America/Lima');

define('SECRET_KEY', 'Surgas_2024_$ecure_T0ken_Key!');

// BASE_URL: automática según entorno
if (getenv('MYSQL_URL')) {
    $proto    = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'https';
    $httpHost = $_SERVER['HTTP_X_FORWARDED_HOST']  ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
    define('BASE_URL', $proto . '://' . $httpHost . '/');
} else {
    $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('BASE_URL', $proto . '://' . $host . '/premia-surgas/');
}

define('QR_PATH', __DIR__ . '/../qr/');
define('QR_URL',  BASE_URL . 'qr/');



// ============================================================
// HELPERS
// ============================================================

function json_response(bool $ok, $data = null, int $status = 200, string $message = ''): void {

    http_response_code($status);

    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    echo json_encode([
        'ok'      => $ok,
        'data'    => $data,
        'message' => $message,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    exit;
}

function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function hoy(): string {
    return date('Y-m-d');
}

function redirigir(string $url): void {
    header('Location: ' . $url);
    exit;
}

function moneda(float $valor): string {
    return 'S/ ' . number_format($valor, 2);
}

function get_json_body(): array {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}