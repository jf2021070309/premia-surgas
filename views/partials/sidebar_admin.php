<?php
/**
 * Sidebar Admin Component
 * Versión Premium Surgas — Estandarizada
 */
$uri = $_SERVER['REQUEST_URI'] ?? '';
if (!function_exists('isActiveLink')) {
    function isActiveLink($path, $uri) {
        return (strpos($uri, $path) !== false) ? 'active' : '';
    }
}
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="PremiaSurgas">
    </div>

    <nav class="sidebar-menu">
        <div class="menu-label">Principal</div>
        <a href="<?= BASE_URL ?>panel" class="sidebar-item <?= isActiveLink('/panel', $uri) ?>">
            <i class='bx bx-grid-alt'></i>
            <span>Dashboard</span>
        </a>

        <div class="menu-label">Gestión Administrativa</div>
        <a href="<?= BASE_URL ?>recargas-admin" class="sidebar-item <?= isActiveLink('recargas-admin', $uri) ?>">
            <i class='bx bx-wallet'></i>
            <span>Solicitudes</span>
        </a>
        <a href="<?= BASE_URL ?>canjes-admin" class="sidebar-item <?= isActiveLink('canjes-admin', $uri) ?>">
            <i class='bx bx-gift'></i>
            <span>Canjes</span>
        </a>
        <a href="<?= BASE_URL ?>clientes/lista" class="sidebar-item <?= isActiveLink('clientes', $uri) ?>">
            <i class='bx bx-group'></i>
            <span>Clientes</span>
        </a>

        <div class="menu-label">Catálogo</div>
        <a href="<?= BASE_URL ?>productos" class="sidebar-item <?= isActiveLink('productos', $uri) ?>">
            <i class='bx bx-package'></i>
            <span>Productos</span>
        </a>
        <a href="<?= BASE_URL ?>conductores" class="sidebar-item <?= isActiveLink('conductores', $uri) ?>">
            <i class='bx bx-id-card'></i>
            <span>Conductores</span>
        </a>

        <div class="menu-label">Operaciones</div>
        <a href="<?= BASE_URL ?>scan" class="sidebar-item <?= isActiveLink('scan', $uri) ?>">
            <i class='bx bx-qr-scan'></i>
            <span>Escaneo QR</span>
        </a>
        <a href="<?= BASE_URL ?>configuraciones" class="sidebar-item <?= isActiveLink('configuraciones', $uri) ?>">
            <i class='bx bx-cog'></i>
            <span>Configuración</span>
        </a>
    </nav>
</aside>
