<?php
// ─── Base de datos ───────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'surgas');
define('DB_USER', 'root');
define('DB_PASS', '');

// ─── Seguridad ───────────────────────────────────────────
define('SECRET_KEY', 'Surgas_2024_$ecure_T0ken_Key!');

// ─── URL base (con slash final) ──────────────────────────
define('BASE_URL', 'http://localhost/PremiaSurgas/');

// ─── Ruta QR ─────────────────────────────────────────────
define('QR_PATH', __DIR__ . '/../qr/');
define('QR_URL',  BASE_URL . 'qr/');
