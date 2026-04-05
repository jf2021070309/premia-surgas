<?php
/**
 * Sidebar Admin Component
 * Versión Premium Surgas — Estandarizada
 */
$current_url = trim($_GET['url'] ?? '', '/');

if (!function_exists('isActiveLink')) {
    function isActiveLink($targetPath, $currentUrl) {
        $targetPath = trim($targetPath, '/');
        // Caso especial para el panel/home
        if ($targetPath === 'panel' && (empty($currentUrl) || $currentUrl === 'panel')) {
            return 'active';
        }
        // Match exacto o si el current URL empieza con el target (para subsecciones)
        if ($currentUrl === $targetPath || strpos($currentUrl, $targetPath . '/') === 0) {
            return 'active';
        }
        return '';
    }
}
?>

<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="Surgas" style="width: 140px; filter: drop-shadow(0 0 10px rgba(255,255,255,0.2));">
    </div>

    <nav class="sidebar-menu">
        <div class="menu-label">Principal</div>
        <a href="<?= BASE_URL ?>panel" class="sidebar-item <?= isActiveLink('panel', $current_url) ?>">
            <i class='bx bx-grid-alt'></i>
            <span>Dashboard</span>
        </a>

        <div class="menu-label">Gestión Administrativa</div>
        <a href="<?= BASE_URL ?>clientes/nuevo" class="sidebar-item <?= isActiveLink('clientes/nuevo', $current_url) ?>">
            <i class='bx bx-user-plus'></i>
            <span>Nuevo Cliente</span>
        </a>
        <a href="<?= BASE_URL ?>clientes/lista" class="sidebar-item <?= isActiveLink('clientes/lista', $current_url) ?>">
            <i class='bx bx-group'></i>
            <span>Directorio</span>
        </a>
        <a href="<?= BASE_URL ?>recargas-admin" class="sidebar-item <?= isActiveLink('recargas-admin', $current_url) ?>">
            <i class='bx bx-wallet'></i>
            <span>Gestión Recargas</span>
        </a>
        <a href="<?= BASE_URL ?>canjes-admin" class="sidebar-item <?= isActiveLink('canjes-admin', $current_url) ?>">
            <i class='bx bx-check-double'></i>
            <span>Entregas Canjes</span>
        </a>

        <div class="menu-label">Operaciones</div>
        <a href="<?= BASE_URL ?>scan" class="sidebar-item <?= isActiveLink('scan', $current_url) ?>">
            <i class='bx bx-qr-scan'></i>
            <span>Suma Puntos</span>
        </a>

        <div class="menu-label">Mantenimiento</div>
        <a href="<?= BASE_URL ?>ajustes" class="sidebar-item <?= isActiveLink('ajustes', $current_url) ?>">
            <i class='bx bx-cog'></i>
            <span>Configuración General</span>
        </a>
    </nav>
</aside>

