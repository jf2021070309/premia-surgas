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
    <div class="topbar">
        <span class="topbar-logo">🔥 Premia<span>Surgas</span></span>
        <div class="topbar-actions">
            <span class="topbar-user">👤 <?= htmlspecialchars($_SESSION['nombre_usuario']) ?></span>
            <a href="<?= BASE_URL ?>logout"><button class="btn-logout">Salir</button></a>
        </div>
    </div>

    <div class="container">
        <h2 class="panel-title" style="margin: 1.2rem 0 .4rem; color: var(--dark);">Panel del Conductor</h2>
        <p class="panel-subtitle" style="color: var(--muted); margin-bottom: 1rem;">Elige una acci&oacute;n</p>

        <div class="menu-grid">
            <a href="<?= BASE_URL ?>clientes/nuevo" class="menu-card">
                <div class="menu-card-icon">👤</div>
                <div class="menu-card-label">Registrar Cliente</div>
            </a>
            <a href="<?= BASE_URL ?>clientes/lista" class="menu-card">
                <div class="menu-card-icon">📋</div>
                <div class="menu-card-label">Lista de Clientes</div>
            </a>
            <a href="<?= BASE_URL ?>scan" class="menu-card">
                <div class="menu-card-icon">📷</div>
                <div class="menu-card-label">Registrar Puntos</div>
            </a>
            <a href="<?= BASE_URL ?>tienda" class="menu-card">
                <div class="menu-card-icon">🎁</div>
                <div class="menu-card-label">Tienda de Premios</div>
            </a>
            <div class="menu-card" style="position:relative;">
                <div class="menu-card-icon">📊</div>
                <div class="menu-card-label">Estadísticas</div>
                <div style="font-size:.75rem;color:var(--muted);margin-top:.4rem;">
                    <?= $totales['clientes'] ?> clientes registrados
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/panel.js"></script>
</body>
</html>
