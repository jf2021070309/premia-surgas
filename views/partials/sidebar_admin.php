<?php
/**
 * Sidebar Admin Component
 * Versión Premium Surgas — Estandarizada + Responsive Collapsible
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

<!-- Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="mainSidebar">
    <div class="sidebar-brand" style="justify-content: center; padding: 2rem 1.5rem;">
        <!-- Brand img -->
        <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="Surgas" style="width: 140px; height: auto;">
        
        <!-- Toggle button inside sidebar (visible when mini) -->
        <button class="sidebar-toggle-mini-btn" id="sidebarToggleMiniBtn" title="Abrir menú">
            <i class='bx bx-menu'></i>
        </button>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-label">Principal</div>
        <a href="<?= BASE_URL ?>panel" class="sidebar-item <?= isActiveLink('panel', $current_url) ?>">
            <i class='bx bx-grid-alt'></i>
            <span>Dashboard</span>
        </a>

        <div class="menu-label">Gestión</div>
        <a href="<?= BASE_URL ?>clientes/nuevo" class="sidebar-item <?= isActiveLink('clientes/nuevo', $current_url) ?>">
            <i class='bx bx-user-plus'></i>
            <span>Nuevo Cliente</span>
        </a>
        <a href="<?= BASE_URL ?>clientes/lista" class="sidebar-item <?= isActiveLink('clientes/lista', $current_url) ?>">
            <i class='bx bx-group'></i>
            <span>Directorio</span>
        </a>

        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>recargas-admin" class="sidebar-item <?= isActiveLink('recargas-admin', $current_url) ?>">
                <i class='bx bx-wallet'></i>
                <span>Gestión Recargas</span>
            </a>
            <a href="<?= BASE_URL ?>canjes-admin" class="sidebar-item <?= isActiveLink('canjes-admin', $current_url) ?>">
                <i class='bx bx-check-double'></i>
                <span>Entregas Canjes</span>
            </a>
            <a href="<?= BASE_URL ?>aliados" class="sidebar-item <?= isActiveLink('aliados', $current_url) ?>">
                <i class='bx bx-store-alt'></i>
                <span>Gestión Aliados</span>
            </a>
        <?php endif; ?>

        <div class="menu-label">Operaciones</div>
        <a href="<?= BASE_URL ?>scan" class="sidebar-item <?= isActiveLink('scan', $current_url) ?>">
            <i class='bx bx-qr-scan'></i>
            <span>Suma Puntos</span>
        </a>
        <?php if ($_SESSION['rol'] === 'conductor' || $_SESSION['rol'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>conductores/mi-historial" class="sidebar-item <?= isActiveLink('conductores/mi-historial', $current_url) ?>">
                <i class='bx bx-history'></i>
                <span>Mi Historial</span>
            </a>
        <?php endif; ?>
        <?php if ($_SESSION['rol'] === 'aliado'): ?>
            <a href="<?= BASE_URL ?>aliados/mi-historial" class="sidebar-item <?= isActiveLink('aliados/mi-historial', $current_url) ?>">
                <i class='bx bx-history'></i>
                <span>Mi Historial</span>
            </a>
        <?php endif; ?>

        <?php if ($_SESSION['rol'] === 'admin'): ?>
            <div class="menu-label">Mantenimiento</div>
            <a href="<?= BASE_URL ?>ajustes" class="sidebar-item <?= isActiveLink('ajustes', $current_url) ?>">
                <i class='bx bx-cog'></i>
                <span>Configuración General</span>
            </a>
            <a href="<?= BASE_URL ?>reporte/auditoria" class="sidebar-item <?= isActiveLink('reporte/auditoria', $current_url) ?>">
                <i class='bx bx-history'></i>
                <span>Auditoría de Sistema</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>

<script>
// Evitar reinicializaciones si el script se carga múltiples veces
if (!window._sidebarInitialized) {
    window._sidebarInitialized = true;

    // Funciones globales de apertura y cierre
    window.openAdminSidebar = function() {
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay) {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Evita scroll
        }
    };

    window.closeAdminSidebar = function() {
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay) {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    // Usar event delegation a nivel de document para sobrevivir reacondicionamiento del DOM (ej. Vue)
    document.addEventListener('click', function(e) {
        // Cierre al hacer click en el overlay
        if (e.target && e.target.id === 'sidebarOverlay') {
            window.closeAdminSidebar();
        }
        
        // Cierre al hacer click en el botón "X" o dentro de él
        if (e.target && e.target.closest('#sidebarCloseBtn')) {
            window.closeAdminSidebar();
        }

        // Apertura al hacer click en el toggle del sidebar mini
        if (e.target && (e.target.closest('#sidebarToggleBtn') || e.target.closest('#sidebarToggleMiniBtn'))) {
            // Evitar comportamiento por defecto
            e.preventDefault();
            window.openAdminSidebar();
        }
    });
}
</script>
