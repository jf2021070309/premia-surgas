<?php
/**
 * Header Admin Component — Reutilizable
 * 
 * Variables requeridas antes del include:
 *   $pageTitle    — Título principal (ej. "Gestión Recargas")
 *   $pageSubtitle — Subtítulo descriptivo (ej. "Panel de verificación administrativa")
 */
$pageTitle    = $pageTitle    ?? 'Panel Admin';
$pageSubtitle = $pageSubtitle ?? '';

// Nombre del usuario de sesión
$adminName = $_SESSION['nombre_usuario'] ?? $_SESSION['usuario'] ?? 'Usuario';
$rawRole   = $_SESSION['rol'] ?? 'admin';

// Mapeo de roles para visualización elegante
$roleMap = [
    'admin'     => 'ADMINISTRADOR',
    'conductor' => 'CONDUCTOR'
];
$displayRole = $roleMap[strtolower($rawRole)] ?? strtoupper($rawRole);

$adminInitial = strtoupper(substr($adminName, 0, 1));
?>

<header class="top-nav">
    <!-- El botón de menú ahora vive en el sidebar directamente -->

    <div class="nav-left">
        <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
        <?php if ($pageSubtitle): ?>
            <p class="page-subtitle"><?= htmlspecialchars($pageSubtitle) ?></p>
        <?php endif; ?>
    </div>

    <div class="nav-right">
        <div class="admin-pill">
            <i class='bx bxs-bell nav-bell'></i>

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
