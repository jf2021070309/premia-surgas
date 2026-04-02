<?php
/**
 * Sidebar Admin Component
 * Versión Premium Surgas — Estandarizada
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/premia-surgas/', '', $uri);

if (!function_exists('isActiveLink')) {
    function isActiveLink($path, $uri) {
        if ($path === '/panel' && (empty($uri) || $uri === 'panel')) {
            return 'active';
        }
        return strpos($uri, $path) === 0 ? 'active' : '';
    }
}
?>

<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="Surgas" style="width: 140px; filter: drop-shadow(0 0 10px rgba(255,255,255,0.2));">
    </div>

    <nav class="sidebar-menu">
        <div class="menu-label">Principal</div>
        <a href="<?= BASE_URL ?>panel" class="sidebar-item <?= isActiveLink('/panel', $uri) ?>">
            <i class='bx bx-grid-alt'></i>
            <span>Dashboard</span>
        </a>

        <div class="menu-label">Gestión Administrativa</div>
        <a href="<?= BASE_URL ?>clientes/nuevo" class="sidebar-item <?= isActiveLink('clientes/nuevo', $uri) ?>">
            <i class='bx bx-user-plus'></i>
            <span>Nuevo Cliente</span>
        </a>
        <a href="<?= BASE_URL ?>clientes/lista" class="sidebar-item <?= isActiveLink('clientes/lista', $uri) ?>">
            <i class='bx bx-group'></i>
            <span>Directorio</span>
        </a>
        <a href="<?= BASE_URL ?>recargas-admin" class="sidebar-item <?= isActiveLink('recargas-admin', $uri) ?>">
            <i class='bx bx-wallet'></i>
            <span>Gestión Recargas</span>
        </a>
        <a href="<?= BASE_URL ?>canjes-admin" class="sidebar-item <?= isActiveLink('canjes-admin', $uri) ?>">
            <i class='bx bx-check-double'></i>
            <span>Entregas Canjes</span>
        </a>

        <div class="menu-label">Operaciones</div>
        <a href="<?= BASE_URL ?>scan" class="sidebar-item <?= isActiveLink('scan', $uri) ?>">
            <i class='bx bx-qr-scan'></i>
            <span>Suma Puntos</span>
        </a>
        <a href="<?= BASE_URL ?>operaciones" class="sidebar-item <?= isActiveLink('operaciones', $uri) ?>">
            <i class='bx bx-wrench'></i>
            <span>Gestión Operaciones</span>
        </a>

        <div class="menu-label">Mantenimiento</div>
        <a href="<?= BASE_URL ?>productos" class="sidebar-item <?= isActiveLink('productos', $uri) ?>">
            <i class='bx bx-gift'></i>
            <span>Gestionar Premios</span>
        </a>
        <a href="<?= BASE_URL ?>conductores" class="sidebar-item <?= isActiveLink('conductores', $uri) ?>">
            <i class='bx bxs-truck'></i>
            <span>Conductores</span>
        </a>

    </nav>
</aside>
