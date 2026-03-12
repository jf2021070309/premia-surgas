<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>
        [v-cloak]{display:none}
        @media (max-width: 600px) {
            .panel-title   { font-size: 1.1rem !important; margin: .8rem 0 .3rem !important; }
            .panel-subtitle { font-size: .82rem !important; margin-bottom: .7rem !important; }
            /* Formularios dentro del panel: evitar zoom iOS */
            input, select, textarea { font-size: 16px !important; }
        }
    </style>
</head>
<body>
<div id="app" v-cloak>
    <div class="panel-header">
        <div class="panel-header-actions">
            <div class="user-profile-compact">
                <div class="user-avatar-circle"><?= substr($_SESSION['nombre_usuario'], 0, 1) ?></div>
                <div class="user-details">
                    <span class="role"><?= htmlspecialchars(strtoupper($_SESSION['rol'])) ?></span>
                    <span class="name" style="font-weight: 800;"><?= htmlspecialchars($_SESSION['usuario'] ?? $_SESSION['nombre_usuario']) ?></span>
                </div>
                <a href="<?= BASE_URL ?>logout">
                    <button class="btn-logout-minimal">Salir</button>
                </a>
            </div>
        </div>
        
        <div class="logo-text" style="font-size: 1.6rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 0.5rem;">
            <span style="font-size: 1.4rem; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">🔥</span>
            Premia<span style="color: #ffbc58;">Surgas</span>
        </div>
        
        <h2 style="font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 0.4rem;">Panel del Conductor</h2>
        <p style="opacity: 0.9; font-size: 1rem; font-weight: 400;">Bienvenido de nuevo. ¿Qué haremos hoy?</p>
    </div>

    <div class="container" style="max-width: 1000px; padding-bottom: 3rem;">
        
        <div class="section-header">
            <h3 class="section-title">Acciones Principales</h3>
        </div>

        <div class="menu-grid">
            <a href="<?= BASE_URL ?>clientes/nuevo" class="menu-card">
                <div class="menu-card-icon">➕</div>
                <div class="menu-card-label">Nuevo Cliente</div>
                <p style="font-size: 0.72rem; color: #888; margin-top: 0.3rem;">Suma nuevos clientes al equipo.</p>
            </a>
            <a href="<?= BASE_URL ?>clientes/lista" class="menu-card">
                <div class="menu-card-icon">👥</div>
                <div class="menu-card-label">Directorio</div>
                <p style="font-size: 0.72rem; color: #888; margin-top: 0.3rem;">Toda tu base de datos a un clic.</p>
            </a>
            <a href="<?= BASE_URL ?>scan" class="menu-card">
                <div class="menu-card-icon">🎯</div>
                <div class="menu-card-label">Suma Puntos</div>
                <p style="font-size: 0.72rem; color: #888; margin-top: 0.3rem;">¡Premiarlos es muy sencillo!</p>
            </a>
            <a href="<?= BASE_URL ?>tienda" class="menu-card">
                <div class="menu-card-icon">🛍️</div>
                <div class="menu-card-label">Tienda</div>
                <p style="font-size: 0.72rem; color: #888; margin-top: 0.3rem;">Descubre recompensas exclusivas.</p>
            </a>
        </div>

        <div class="section-header">
            <h3 class="section-title">Resumen de Gesti&oacute;n</h3>
        </div>

        <!-- Estadísticas integradas de forma elegante -->
        <div class="stats-container">
            <div class="stats-flex">
                <div class="stat-item">
                    <div class="stat-icon">📊</div>
                    <div class="stat-content">
                        <span class="stat-lbl">Alcance Total</span>
                        <b class="stat-val"><?= $totales['clientes'] ?></b>
                        <span style="font-size: 0.75rem; color: #999;">Clientes Activos</span>
                    </div>
                </div>
                
                <div class="divider-v"></div>

                <div class="stat-item">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-content">
                        <span class="stat-lbl">&Uacute;ltima Actividad</span>
                        <b class="stat-val" style="font-size: 1.1rem; color: var(--muted);"><?= date('H:i') ?></b>
                        <span style="font-size: 0.75rem; color: #999;">Hoy, <?= date('d M') ?></span>
                    </div>
                </div>

                <div class="divider-v"></div>

                <div class="stat-item" style="flex: 0.5; justify-content: flex-end;">
                    <a href="#" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.7rem 1.2rem; border-radius: 50px; box-shadow: 0 4px 15px rgba(130, 21, 21, 0.2);">
                        Ver Reportes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/panel.js"></script>
</body>
</html>
