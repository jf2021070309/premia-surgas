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
        <?php if ($_SESSION['rol'] === 'cliente'): ?>
            <div class="menu-label">Mi Cuenta</div>
            <a href="<?= BASE_URL ?>scan?c=<?= $_SESSION['codigo_cliente'] ?>&t=<?= $_SESSION['token_cliente'] ?>" class="sidebar-item <?= isActiveLink('scan', $current_url) ?>" onclick="window.location.hash=''; return true;">
                <i class='bx bx-user-circle'></i>
                <span>Mi Perfil</span>
            </a>
            <a href="#" class="sidebar-item" onclick="window.location.hash='actividad'; return false;">
                <i class='bx bx-history'></i>
                <span>Mi Actividad</span>
            </a>

            <!-- MÓDULOS OCULTOS TEMPORALMENTE:
            <a href="<?= BASE_URL ?>tienda" class="sidebar-item <?= isActiveLink('tienda', $current_url) ?>">
                <i class='bx bx-shopping-bag'></i>
                <span>Tienda de Premios</span>
            </a>
            <a href="#" class="sidebar-item" onclick="window.location.hash='canjes'; return false;">
                <i class='bx bx-gift'></i>
                <span>Mis Canjes</span>
            </a>
            <a href="#" class="sidebar-item" onclick="window.location.hash='incentivos'; return false;">
                <i class='bx bx-target-lock'></i>
                <span>Metas & Vales</span>
            </a>
            -->

            <a href="#" class="sidebar-item" onclick="window.location.hash='seguridad'; return false;">
                <i class='bx bx-lock-alt'></i>
                <span>Seguridad</span>
            </a>
        <?php else: ?>
            <div class="menu-label">Principal</div>
            <a href="<?= BASE_URL ?>panel" class="sidebar-item <?= isActiveLink('panel', $current_url) ?>">
                <i class='bx bx-grid-alt'></i>
                <span>Dashboard</span>
            </a>

            <?php if ($_SESSION['rol'] !== 'afiliado'): ?>
            <div class="menu-label">Gestión</div>
            <a href="<?= BASE_URL ?>clientes/nuevo" class="sidebar-item <?= isActiveLink('clientes/nuevo', $current_url) ?>">
                <i class='bx bx-user-plus'></i>
                <span>Nuevo Cliente</span>
            </a>
            <?php else: ?>
            <!-- MÓDULO OCULTO PARA AFILIADO:
            <div class="menu-label">Gestión</div>
            <a href="<?= BASE_URL ?>clientes/nuevo" class="sidebar-item <?= isActiveLink('clientes/nuevo', $current_url) ?>">
                <i class='bx bx-user-plus'></i>
                <span>Nuevo Cliente</span>
            </a>
            -->
            <?php endif; ?>

            <?php if (!in_array($_SESSION['rol'], ['conductor', 'afiliado'])): ?>
            <a href="<?= BASE_URL ?>clientes/lista" class="sidebar-item <?= isActiveLink('clientes/lista', $current_url) ?>">
                <i class='bx bx-group'></i>
                <span>Directorio</span>
            </a>
            <?php else: ?>
            <!-- MÓDULO OCULTO PARA CONDUCTOR Y AFILIADO:
            <a href="<?= BASE_URL ?>clientes/lista" class="sidebar-item <?= isActiveLink('clientes/lista', $current_url) ?>">
                <i class='bx bx-group'></i>
                <span>Directorio</span>
            </a>
            -->
            <?php endif; ?>

            <!-- MÓDULO OCULTO PARA AFILIADO Y ADMIN:
            <a href="<?= BASE_URL ?>afiliados/miAnuncio" class="sidebar-item <?= isActiveLink('afiliados/miAnuncio', $current_url) ?>">
                <i class='bx bxs-megaphone'></i>
                <span>Mi Anuncio</span>
            </a>
            -->

            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <!-- MÓDULOS OCULTOS TEMPORALMENTE:
                <div class="menu-label">Incentivos</div>
                <a href="<?= BASE_URL ?>incentivos/reglas" class="sidebar-item <?= isActiveLink('incentivos/reglas', $current_url) ?>">
                    <i class='bx bx-target-lock'></i>
                    <span>Reglas de Metas</span>
                </a>
                <a href="<?= BASE_URL ?>incentivos/vales" class="sidebar-item <?= isActiveLink('incentivos/vales', $current_url) ?>">
                    <i class='bx bx-receipt'></i>
                    <span>Vales Emitidos</span>
                </a>
                -->

                <div class="menu-label">Puntos y Recargas</div>
                <a href="<?= BASE_URL ?>puntos-admin" class="sidebar-item <?= isActiveLink('puntos-admin', $current_url) ?>">
                    <i class='bx bx-check-shield'></i>
                    <span>Gestión de Puntos</span>
                </a>
                <a href="<?= BASE_URL ?>recargas-admin" class="sidebar-item <?= isActiveLink('recargas-admin', $current_url) ?>">
                    <i class='bx bx-wallet'></i>
                    <span>Gestión Recargas</span>
                </a>
                
                <!-- MÓDULOS OCULTOS TEMPORALMENTE:
                <a href="<?= BASE_URL ?>canjes-admin" class="sidebar-item <?= isActiveLink('canjes-admin', $current_url) ?>">
                    <i class='bx bx-check-double'></i>
                    <span>Entregas Canjes</span>
                </a>
                <a href="<?= BASE_URL ?>afiliados" class="sidebar-item <?= isActiveLink('afiliados', $current_url) ?>">
                    <i class='bx bx-store-alt'></i>
                    <span>Gestión Afiliados</span>
                </a>
                -->
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
            <?php if ($_SESSION['rol'] === 'afiliado'): ?>
                <a href="<?= BASE_URL ?>afiliados/mi-historial" class="sidebar-item <?= isActiveLink('afiliados/mi-historial', $current_url) ?>">
                    <i class='bx bx-history'></i>
                    <span>Mi Historial</span>
                </a>
                <a href="<?= BASE_URL ?>afiliados/perfil" class="sidebar-item <?= isActiveLink('afiliados/perfil', $current_url) ?>">
                    <i class='bx bx-user-circle'></i>
                    <span>Mi Perfil</span>
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

    // --- Dynamic Sidebar Highlighting for Hashes ---
    function updateSidebarActive() {
        // Solo aplicar la lógica de hash si estamos en la vista de cliente (que tiene hashes en los onclick)
        const isClientView = document.querySelector('.sidebar-item[onclick*="actividad"]');
        if (!isClientView) return; // Si somos admin, dejamos que PHP maneje el .active

        const hash = window.location.hash;
        const sidebarItems = document.querySelectorAll('.sidebar-item');
        
        sidebarItems.forEach(item => {
            const onclick = item.getAttribute('onclick') || '';
            const href = item.getAttribute('href') || '';
            
            // Default: remove active
            item.classList.remove('active');

            if (hash === '#actividad') {
                if (onclick.includes('actividad')) item.classList.add('active');
            } else if (hash === '#canjes') {
                if (onclick.includes('canjes')) item.classList.add('active');
            } else if (!hash || hash === '') {
                // If we are in the main scan page without hash
                if (href.includes('scan') && !onclick.includes('actividad') && !onclick.includes('canjes')) {
                    item.classList.add('active');
                }
            }
        });
    }

    window.addEventListener('hashchange', updateSidebarActive);
    window.addEventListener('load', updateSidebarActive);
}
</script>
