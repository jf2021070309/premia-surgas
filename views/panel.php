<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <!-- Boxicons for elegant icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [v-cloak]{display:none}
        .notif-card {
            background: white; border-radius: 1.5rem; padding: 1.2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #f0f0f0;
            margin-bottom: 1rem; cursor: pointer; transition: all 0.3s ease;
            display: flex; align-items: center; gap: 1rem;
        }
        .notif-card:hover { transform: translateX(5px); border-color: var(--primary); }
        .notif-icon { 
            width: 45px; height: 45px; border-radius: 12px; background: #fffcf0; 
            color: #f39c12; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
        }
        .notif-icon.full { background: #f0fdf4; color: #22c55e; }
        .notif-icon.mix { background: #fdf2f2; color: #ef4444; }
        .notif-content { flex: 1; }
        .notif-title { font-weight: 700; color: #333; font-size: 0.95rem; margin-bottom: 0.1rem; }
        .notif-sub { color: #888; font-size: 0.8rem; }
        
        /* Modal Detallado */
        .modal-mask {
            position: fixed; z-index: 9998; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); display: flex; transition: opacity 0.3s ease; backdrop-filter: blur(5px);
        }
        .modal-wrapper { width: 100%; max-width: 450px; margin: auto; padding: 1.5rem; }
        .modal-container {
            background-color: #fff; border-radius: 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            transition: all 0.3s ease; overflow: hidden;
        }
        .modal-header-notif { background: var(--primary); color: white; padding: 2rem 1.5rem; text-align: center; }
        .modal-body-notif { padding: 2rem; }
        
        @media (max-width: 600px) {
            .panel-title   { font-size: 1.1rem !important; margin: .8rem 0 .3rem !important; }
            .panel-subtitle { font-size: .82rem !important; margin-bottom: .7rem !important; }
            input, select, textarea { font-size: 16px !important; }
        }
    </style>
</head>
<body>
<div id="app" v-cloak>
    <div class="panel-header">
        <!-- Header Top Row: Logo, Search, Profile -->
        <div class="header-top-row">
            <div class="header-logo-side">
                <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="PremiaSurgas" class="header-main-logo">
            </div>

            <!-- Search Bar Removed -->
            <div class="header-search-side" style="display:none;"></div> 

            <div class="header-user-side">
                <div class="user-card-integrated">
                    <div class="u-avatar"><?= substr($_SESSION['nombre_usuario'], 0, 1) ?></div>
                    <div class="u-details">
                        <span class="u-role-tag"><?= htmlspecialchars(strtoupper($_SESSION['rol'])) ?></span>
                        <span class="u-name-val"><?= htmlspecialchars($_SESSION['usuario'] ?? $_SESSION['nombre_usuario']) ?></span>
                    </div>
                    <div class="u-divider"></div>
                    <button @click="logout" class="u-logout-btn" title="Cerrar Sesión">
                        <i class='bx bx-log-out'></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Header Hero Content -->
        <div class="header-hero-content">
            <h1 class="hero-main-title">
                <?= $_SESSION['rol'] === 'admin' ? 'Panel de Control' : 'Panel del Conductor' ?>
            </h1>
            <p class="hero-welcome-msg">Bienvenido de nuevo. ¿Qué haremos hoy?</p>
        </div>
    </div>

    <div class="container">
        
        <div class="section-header">
            <h3 class="section-title light">Acciones Principales</h3>
        </div>

        <div class="menu-grid">
            <a href="<?= BASE_URL ?>clientes/nuevo" class="menu-card">
                <div class="menu-card-icon"><i class='bx bx-user-plus'></i></div>
                <div class="menu-card-label">Nuevo Cliente</div>
                <p>Suma nuevos clientes al equipo.</p>
            </a>
            <a href="<?= BASE_URL ?>clientes/lista" class="menu-card">
                <div class="menu-card-icon"><i class='bx bx-group'></i></div>
                <div class="menu-card-label">Directorio</div>
                <p>Toda tu base de datos a un clic.</p>
            </a>
            <a href="<?= BASE_URL ?>scan" class="menu-card">
                <div class="menu-card-icon"><i class='bx bx-qr-scan'></i></div>
                <div class="menu-card-label">Suma Puntos</div>
                <p>¡Premiarlos es muy sencillo!</p>
            </a>
            <a href="<?= BASE_URL ?>tienda" class="menu-card">
                <div class="menu-card-icon"><i class='bx bx-shopping-bag'></i></div>
                <div class="menu-card-label">Tienda</div>
                <p>Descubre recompensas exclusivas.</p>
            </a>
            <?php if ($_SESSION['rol'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>productos" class="menu-card">
                <div class="menu-card-icon"><i class='bx bx-gift'></i></div>
                <div class="menu-card-label">Gestionar Premios</div>
                <p>CRUD de productos y stock.</p>
            </a>
            <a href="<?= BASE_URL ?>conductores" class="menu-card">
                <div class="menu-card-icon"><i class='bx bxs-truck'></i></div>
                <div class="menu-card-label">Conductores</div>
                <p>Gestionar equipo de reparto.</p>
            </a>
            <a href="<?= BASE_URL ?>configuraciones" class="menu-card">
                <div class="menu-card-icon"><i class='bx bx-cog'></i></div>
                <div class="menu-card-label">Parámetros</div>
                <p>Configurar puntos y equivalencias.</p>
            </a>
            <a href="<?= BASE_URL ?>operaciones" class="menu-card">
                <div class="menu-card-icon"><i class='bx bx-wrench'></i></div>
                <div class="menu-card-label">Gestión Operaciones</div>
                <p>Personalizar tipos de canje.</p>
            </a>
            <a href="<?= BASE_URL ?>canjes-admin" class="menu-card">
                <div class="menu-card-icon"><i class='bx bx-check-double'></i></div>
                <div class="menu-card-label">Entregas Canjes</div>
                <p>Controlar productos entregados.</p>
            </a>
            <?php endif; ?>
        </div>

        <div class="section-header">
            <h3 class="section-title">Resumen de Gesti&oacute;n</h3>
        </div>

        <!-- Estadísticas integradas de forma elegante -->
        <div class="stats-container">
            <div class="stats-flex">
                <div class="stat-item">
                    <div class="stat-icon"><i class='bx bx-pie-chart-alt-2'></i></div>
                    <div class="stat-content">
                        <span class="stat-lbl">Alcance Total</span>
                        <b class="stat-val"><?= $totales['clientes'] ?></b>
                        <span style="font-size: 0.75rem; color: #999;">Clientes Activos</span>
                    </div>
                </div>
                
                <div class="divider-v"></div>

                <div class="stat-item">
                    <div class="stat-icon"><i class='bx bx-time-five'></i></div>
                    <div class="stat-content">
                        <span class="stat-lbl">&Uacute;ltima Actividad</span>
                        <b class="stat-val" style="font-size: 1.1rem; color: var(--muted);"><?= date('H:i') ?></b>
                        <span style="font-size: 0.75rem; color: #999;">Hoy, <?= date('d M') ?></span>
                    </div>
                </div>

                <?php if ($_SESSION['rol'] === 'admin'): ?>
                <div class="divider-v"></div>

                <div class="stat-item" style="flex: 0.5; justify-content: flex-end;">
                    <a href="<?= BASE_URL ?>reportes" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.7rem 1.2rem; border-radius: 50px; box-shadow: 0 4px 15px rgba(130, 21, 21, 0.2);">
                        Ver Reportes
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($_SESSION['rol'] === 'admin' && !empty($notificaciones)): ?>
        <div class="section-header" style="margin-top: 3rem;">
            <h3 class="section-title">Notificaciones de Canjes</h3>
            <p style="font-size: 0.8rem; color: #888; margin: 0;">Últimos canjes solicitados por clientes</p>
        </div>

        <div class="notif-list">
            <?php foreach ($notificaciones as $n): 
                $esMix = $n['monto'] > 0;
            ?>
            <div class="notif-card" @click="verDetalle(<?= htmlspecialchars(json_encode($n)) ?>)">
                <div class="notif-icon <?= $esMix ? 'mix' : 'full' ?>">
                    <i class='bx <?= $esMix ? 'bx-coin-stack' : 'bx-gift' ?>'></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title"><?= htmlspecialchars($n['cliente_nombre']) ?></div>
                    <div class="notif-sub">Canjeó: <b><?= htmlspecialchars($n['premio_nombre']) ?></b></div>
                </div>
                <div style="text-align: right;">
                    <div class="badge <?= $esMix ? 'badge-danger' : 'badge-success' ?>" style="font-size: 0.65rem; padding: 0.3rem 0.6rem;">
                        <?= $esMix ? 'Puntos + S/' . $n['monto'] : 'Canje Full' ?>
                    </div>
                    <div style="font-size: 0.7rem; color: #bbb; margin-top: 0.3rem;"><?= date('H:i', strtotime($n['fecha'])) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal Detalle Canje -->
    <transition name="modal" v-if="showModal">
        <div class="modal-mask" @click="showModal = false">
            <div class="modal-wrapper" @click.stop>
                <div class="modal-container">
                    <div class="modal-header-notif">
                        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem;">
                            <i class='bx bx-receipt'></i>
                        </div>
                        <h3 style="margin: 0; font-weight: 800;">Detalle del Canje</h3>
                    </div>
                    <div class="modal-body-notif">
                        <div style="margin-bottom: 1.5rem; border-bottom: 1px dashed #eee; padding-bottom: 1rem;">
                            <small style="color: #999; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Cliente</small>
                            <div style="font-size: 1.2rem; font-weight: 800; color: #333; margin-top: 0.2rem;">{{ detail.cliente_nombre }}</div>
                            <div style="color: #666; font-size: 0.9rem;"><i class='bx bx-phone'></i> {{ detail.cliente_celular }}</div>
                        </div>

                        <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center;">
                            <img :src="'<?= BASE_URL ?>assets/premios/' + detail.premio_imagen" style="width: 60px; height: 60px; object-fit: contain; background: #f8fafc; border-radius: 12px; padding: 0.5rem;">
                            <div>
                                <small style="color: #999; text-transform: uppercase; font-weight: 700; font-size: 0.7rem;">Premio Solicitado</small>
                                <div style="font-weight: 700; color: #333;">{{ detail.premio_nombre }}</div>
                            </div>
                        </div>

                        <div style="background: #f8fafc; border-radius: 1.5rem; padding: 1.2rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="color: #666;">Puntos Usados:</span>
                                <b style="color: var(--primary);">{{ detail.puntos_usados }} pts</b>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #666;">Monto Pagado:</span>
                                <b :style="{ color: detail.monto > 0 ? '#ef4444' : '#22c55e' }">
                                    {{ detail.monto > 0 ? 'S/ ' + detail.monto : 'GRATIS (Full)' }}
                                </b>
                            </div>
                        </div>

                        <div style="margin-top: 2rem;">
                            <button class="btn btn-primary w-100" style="padding: 1rem; border-radius: 1rem;" @click="showModal = false">Cerrar Notificación</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/panel.js"></script>
</body>
</html>
