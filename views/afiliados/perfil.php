<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/clientes_nuevo.css">
</head>
<body class="admin-body">

<?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

<div class="admin-layout">
    <?php
    $pageTitle = "Mi Perfil";
    $pageSubtitle = "Gestiona tu información personal y de acceso";
    include __DIR__ . '/../partials/header_admin.php';
    ?>

    <div class="container py-5">
        
        <?php if (isset($_SESSION['flash'])): ?>
            <script>
                Swal.fire({
                    icon: '<?= $_SESSION['flash']['type'] ?>',
                    title: '<?= $_SESSION['flash']['title'] ?>',
                    text: '<?= $_SESSION['flash']['message'] ?>',
                    confirmButtonColor: '#FF6B00'
                });
            </script>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- row g-4 controla el espacio horizontal y vertical entre columnas -->
        <div class="row g-4">
            
            <!-- TARJETA 1: Perfil (Identidad) -->
            <div class="col-xl-4 col-lg-5 mb-4 mb-lg-0">
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="profile-avatar-wrapper">
                            <?php 
                            $avatarInitial = strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1));
                            ?>
                            <div class="profile-avatar-main"><?= $avatarInitial ?></div>
                            <div class="profile-badge"><i class='bx bxs-check-shield'></i></div>
                        </div>
                        <h2 class="profile-name-title"><?= htmlspecialchars($usuario['nombre'] ?? 'Usuario') ?></h2>
                        <p class="profile-role-tag">Afiliado Comercial</p>
                    </div>
                    
                    <div class="profile-card-body">
                        <div class="profile-info-item">
                            <div class="info-icon"><i class='bx bx-calendar-event'></i></div>
                            <div class="info-details">
                                <span class="info-label">Miembro desde</span>
                                <?php $fechaRegistro = isset($usuario['fecha_creacion']) ? date('d/m/Y', strtotime($usuario['fecha_creacion'])) : 'No disponible'; ?>
                                <span class="info-value"><?= $fechaRegistro ?></span>
                            </div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-icon"><i class='bx bx-map-pin'></i></div>
                            <div class="info-details">
                                <span class="info-label">Sede / Región</span>
                                <span class="info-value"><?= htmlspecialchars($usuario['departamento'] ?? 'No especificado') ?></span>
                            </div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-icon"><i class='bx bx-id-card'></i></div>
                            <div class="info-details">
                                <span class="info-label">Usuario de acceso</span>
                                <span class="info-value">@<?= htmlspecialchars($usuario['usuario'] ?? '---') ?></span>
                            </div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-icon"><i class='bx bx-map'></i></div>
                            <div class="info-details">
                                <span class="info-label">Dirección Fiscal/Local</span>
                                <span class="info-value"><?= htmlspecialchars($usuario['direccion'] ?? 'No registrada') ?></span>
                            </div>
                        </div>
                        <div class="profile-info-item">
                            <div class="info-icon"><i class='bx bx-phone'></i></div>
                            <div class="info-details">
                                <span class="info-label">Número Celular</span>
                                <span class="info-value"><?= htmlspecialchars($usuario['celular'] ?? 'No registrado') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card-footer">
                        <div class="security-indicator">
                            <div class="indicator-bar"><div class="indicator-progress" style="width: 100%"></div></div>
                            <span>Cuenta Verificada y Segura</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-xl-8 col-lg-7">
                
                <!-- TARJETA 2: Edición de Datos -->
                <div class="elite-form-card mb-5"> <!-- Aumentado mb-5 para mayor espacio vertical con la 3 -->
                    <div class="card-header-premium">
                        <i class='bx bx-edit-alt'></i>
                        Configuración de Cuenta
                    </div>

                    <form action="<?= BASE_URL ?>afiliados/actualizarPerfil" method="POST" class="premium-form">
                        
                        <div class="form-group-modern">
                            <label><i class='bx bx-user'></i> Nombre Completo / Razón Social</label>
                            <div class="input-wrapper">
                                <i class='bx bx-pencil'></i>
                                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label><i class='bx bx-at'></i> Nombre de Usuario</label>
                            <div class="input-wrapper">
                                <i class='bx bx-user-circle'></i>
                                <input type="text" name="usuario" value="<?= htmlspecialchars($usuario['usuario'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label><i class='bx bx-building'></i> Sede / Departamento</label>
                            <div class="input-wrapper">
                                <i class='bx bx-map'></i>
                                <select name="departamento" class="form-control-modern">
                                    <option value="Tacna" <?= ($usuario['departamento'] ?? '') === 'Tacna' ? 'selected' : '' ?>>Tacna</option>
                                    <option value="Moquegua" <?= ($usuario['departamento'] ?? '') === 'Moquegua' ? 'selected' : '' ?>>Moquegua</option>
                                    <option value="Arequipa" <?= ($usuario['departamento'] ?? '') === 'Arequipa' ? 'selected' : '' ?>>Arequipa</option>
                                    <option value="Ilo" <?= ($usuario['departamento'] ?? '') === 'Ilo' ? 'selected' : '' ?>>Ilo</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label><i class='bx bx-map-pin'></i> Dirección / Local</label>
                            <div class="input-wrapper">
                                <i class='bx bx-map-alt'></i>
                                <input type="text" name="direccion" value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>" placeholder="Ej: Calle San Roman 914">
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label><i class='bx bx-phone'></i> Número Celular / WhatsApp</label>
                            <div class="input-wrapper">
                                <i class='bx bx-mobile-alt'></i>
                                <input type="text" name="celular" value="<?= htmlspecialchars($usuario['celular'] ?? '') ?>" placeholder="Ej: 987654321">
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label><i class='bx bx-lock-alt'></i> Nueva Contraseña <small class="text-muted">(Opcional)</small></label>
                            <div class="input-wrapper">
                                <i class='bx bx-key'></i>
                                <input type="password" name="password" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="form-footer-actions">
                            <button type="submit" class="btn-premium-submit">
                                <i class='bx bx-save'></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TARJETA 3: Seguridad -->
                <div class="elite-form-card">
                    <div class="card-header-premium">
                        <i class='bx bx-shield-quarter'></i>
                        Recomendación de Seguridad
                    </div>
                    <div class="premium-form-content">
                        <div class="d-flex align-items-center gap-4">
                            <div class="security-info-icon">
                                <i class='bx bxs-bulb'></i>
                            </div>
                            <div class="security-info-text">
                                <h4>Protege tu cuenta</h4>
                                <p>
                                    Te recomendamos cambiar tu contraseña periódicamente y no compartir tu usuario con terceros para garantizar la integridad de tu negocio en la plataforma.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* Forzar que el layout ocupe el espacio correcto y tenga el fondo del sistema */
.admin-layout {
    background: var(--bg) !important;
    min-height: 100vh;
}

/* Espacio vertical base para el contenedor */
.container {
    padding-top: 2rem !important;
    padding-bottom: 4rem !important;
}

/* Sobrescribir iconos de cabecera para que sean naranjas */
.card-header-premium i {
    background: rgba(255, 107, 0, 0.15) !important;
    color: #FF6B00 !important;
    width: 45px !important;
    height: 45px !important;
    font-size: 1.6rem !important;
}

/* Contenedor de contenido para tarjetas que no son formularios */
.premium-form-content {
    padding: 2.5rem 3rem;
    background: #ffffff;
}

.security-info-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 107, 0, 0.1);
    color: #FF6B00;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    flex-shrink: 0;
}

.security-info-text h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 800;
    color: #333;
    letter-spacing: -0.02em;
}

.security-info-text p {
    margin: 8px 0 0;
    font-size: 0.9rem;
    color: #666;
    line-height: 1.6;
}

/* --- Profile Card Styles --- */
.profile-card {
    background: #ffffff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    height: 100%;
    border: 1px solid var(--outline);
}

.profile-card-header {
    background: linear-gradient(135deg, #FF6B00 0%, #FF8A00 100%);
    padding: 40px 20px;
    text-align: center;
    position: relative;
}

.profile-avatar-wrapper {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 15px;
}

.profile-avatar-main {
    width: 100%;
    height: 100%;
    background: white;
    color: #FF6B00;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    font-weight: 800;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.profile-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    background: #00D1FF;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #FF7B00;
}

.profile-name-title {
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.profile-role-tag {
    color: rgba(255,255,255,0.9);
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.profile-card-body {
    padding: 30px;
}

.profile-info-item {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f1f1f1;
}

.profile-info-item:last-child {
    border-bottom: none;
}

.info-icon {
    width: 45px;
    height: 45px;
    background: #FFF5EE;
    color: #FF6B00;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-right: 15px;
}

.info-details {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 0.75rem;
    color: #888;
    text-transform: uppercase;
    font-weight: 600;
}

.info-value {
    color: #333;
    font-weight: 700;
}

.profile-card-footer {
    padding: 0 30px 30px;
}

.security-indicator {
    text-align: center;
}

.indicator-bar {
    height: 6px;
    background: #f1f1f1;
    border-radius: 10px;
    margin-bottom: 8px;
    overflow: hidden;
}

.indicator-progress {
    height: 100%;
    background: linear-gradient(to right, #00D1FF, #00FFA3);
}

.security-indicator span {
    font-size: 0.75rem;
    color: #666;
    font-weight: 600;
}

/* Estilo del botón consistente */
.btn-premium-submit {
    background: #FF6B00 !important;
    padding: 12px 35px !important;
    border-radius: 12px !important;
    font-weight: 700 !important;
    border: none !important;
    color: white !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(255, 107, 0, 0.2) !important;
}

.btn-premium-submit:hover {
    background: #E65A00 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(255, 107, 0, 0.3) !important;
}

/* Ajuste responsive para stacking vertical con más espacio */
@media (max-width: 991px) {
    .mb-lg-0 {
        margin-bottom: 3rem !important; /* Espacio extra entre tarjeta 1 y 2 al apilarse */
    }
}
</style>

<script src="<?= BASE_URL ?>assets/js/admin.js"></script>
</body>
</html>
