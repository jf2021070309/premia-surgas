<?php
$pageTitle    = $pageTitle    ?? 'Panel Admin';
$pageSubtitle = $pageSubtitle ?? '';
$pageIcon     = $pageIcon     ?? ($pageTitle === 'Mi Perfil' ? 'bx-user-circle' : 'bx-grid-alt');

// Nombre del usuario de sesión
$adminName = $_SESSION['nombre_usuario'] ?? $_SESSION['nombre_cliente'] ?? $_SESSION['usuario'] ?? 'Usuario';
$rawRole   = $_SESSION['rol'] ?? 'admin';

// Mapeo de roles para visualización elegante
$roleMap = [
    'admin'     => 'ADMINISTRADOR',
    'conductor' => 'CONDUCTOR',
    'cliente'   => 'CLIENTE VIP'
];
$displayRole = $roleMap[strtolower($rawRole)] ?? strtoupper($rawRole);
$adminInitial = strtoupper(substr($adminName, 0, 1));
?>

<header class="top-nav">
    <div class="nav-left">
        <button class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Abrir menú">
            <i class='bx bx-menu'></i>
        </button>

        <!-- Brand logo for mobile/collapsed views -->
        <div class="header-mobile-logo">
            <img src="<?= BASE_URL ?>assets/premios/icono.png" alt="Surgas" style="width: 28px; height: 28px; object-fit: contain;">
        </div>
        
        <div class="header-title-group">
            <?php if ($pageIcon): ?>
                <div class="header-page-icon">
                    <i class='bx <?= htmlspecialchars($pageIcon) ?>'></i>
                </div>
            <?php endif; ?>
            
            <div class="header-text-column">
                <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
                <?php if ($pageSubtitle): ?>
                    <p class="page-subtitle"><?= htmlspecialchars($pageSubtitle) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="nav-right">
        <div class="admin-pill">
            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <i class='bx bxs-bell nav-bell'></i>
            <?php endif; ?>

            <div class="admin-avatar"><?= $adminInitial ?></div>

            <div class="admin-pill-text">
                <span class="admin-pill-role"><?= $displayRole ?></span>
                <span class="admin-pill-name"><?= htmlspecialchars($adminName) ?></span>
            </div>

            <div class="nav-divider"></div>

            <a href="<?= BASE_URL ?>logout" class="header-logout-btn" title="Cerrar Sesión">
                <i class='bx bx-log-out-circle'></i>
            </a>
        </div>
    </div>
</header>
