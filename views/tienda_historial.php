<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Canjes — PremiaSurgas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f8f9fa; }
        .container { max-width: 1000px; padding: 2rem 1.5rem; }
        
        .history-header {
            padding: 3rem 1rem; text-align: center;
            background: linear-gradient(135deg, #2d3436, #000);
            color: white; border-radius: 2rem;
            margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
        }

        .back-nav { position: absolute; top: 20px; left: 20px; }
        .btn-back { background: rgba(255,255,255,0.1); color: white; padding: 0.5rem 1.2rem; border-radius: 50px; text-decoration: none; border: 1px solid rgba(255,255,255,0.2); }

        .history-card {
            background: white; border-radius: 1.5rem; overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem; transition: 0.3s;
            border: 1px solid rgba(0,0,0,0.05);
            display: flex; align-items: center; padding: 1.2rem; gap: 1.2rem;
        }
        .history-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }

        .prize-thumb { width: 70px; height: 70px; object-fit: contain; background: #f8fafc; padding: 8px; border-radius: 1rem; border: 1px solid #edf2f7; }
        
        .badge-pts-red { color: #e11d48; font-weight: 850; font-size: 1.1rem; }
        .modality-badge {
            font-size: 0.6rem; font-weight: 800; color: #821515; 
            background: #ffebeb; padding: 2px 8px; border-radius: 4px;
            text-transform: uppercase; letter-spacing: 0.5px;
            display: inline-block; margin-top: 4px;
        }
        .status-badge {
            font-size: 0.65rem; font-weight: 800; padding: 4px 10px; border-radius: 50px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .st-pendiente { background: #fff7ed; color: #d97706; }
        .st-pago_aprobado { background: #f0fdf4; color: #16a34a; }
        .st-pago_pendiente { background: #eff6ff; color: #1d4ed8; }
        .st-entregado { background: #f1f5f9; color: #64748b; }
        .st-canjeado { background: #f1f5f9; color: #64748b; }
        .st-rechazado { background: #fef2f2; color: #dc2626; }
        .st-pago_rechazado { background: #fef2f2; color: #dc2626; }
        
        .empty-state { text-align: center; padding: 5rem 2rem; background: white; border-radius: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container">
    <div class="panel-header" style="margin-bottom: 2rem;">
        <div class="header-top-row">
            <div class="header-logo-side">
                <a href="<?= BASE_URL ?>tienda" style="color:#fff; font-size:1.6rem; margin-right:1.2rem; display:flex; align-items:center; transition:0.3s;" title="Volver a la Tienda">
                    <i class='bx bx-left-arrow-alt'></i>
                </a>
                <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="PremiaSurgas" class="header-main-logo">
            </div>

            <div class="header-user-side">
                <div class="user-card-integrated">
                    <div class="u-avatar"><?= substr($_SESSION['nombre_usuario'] ?? $_SESSION['usuario'] ?? 'U', 0, 1) ?></div>
                    <div class="u-details">
                        <span class="u-role-tag">CLIENTE</span>
                        <span class="u-name-val"><?= htmlspecialchars($_SESSION['usuario'] ?? $_SESSION['nombre_usuario'] ?? 'Usuario') ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-hero-content" style="padding-bottom: 2rem;">
            <h1 class="hero-main-title" style="font-size: 2rem !important;">Tu Historial de Canjes</h1>
            <p class="hero-welcome-msg">Revisa todos los premios que has obtenido.</p>
        </div>
    </div>

    <div class="container" style="max-width: 800px; padding-top: 0;">
        <?php if (empty($canjes)): ?>
            <div class="empty-state">
                <span style="font-size: 4rem;">📦</span>
                <h3 class="mt-3 fw-bold">Aún no tienes canjes</h3>
                <p class="text-muted">¡Empieza a sumar puntos y canjea increíbles premios!</p>
                <a href="<?= BASE_URL ?>tienda" class="btn btn-primary rounded-pill px-4 mt-2">Ir a la Tienda</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($canjes as $c): ?>
                    <?php 
                        $modStr = 'Canje Total';
                        if ($c['monto'] > 0) {
                            $modStr = !empty($c['comprobante_url']) ? 'Puntos + Depósito' : 'Puntos + Efectivo';
                        }
                    ?>
                    <div class="col-12 col-lg-6">
                        <div class="history-card">
                            <img src="<?= BASE_URL ?>assets/premios/<?= $c['premio_imagen'] ?>" alt="<?= $c['premio_nombre'] ?>" class="prize-thumb">
                            
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-dark"><?= $c['premio_nombre'] ?></h6>
                                <div class="modality-badge mb-2"><?= $modStr ?></div>
                                <div class="text-muted small">
                                    <i class='bx bx-calendar-alt'></i> <?= date('d/m/Y', strtotime($c['fecha'])) ?>
                                </div>
                                <div class="mt-2">
                                    <span class="status-badge st-<?= $c['estado'] ?>">
                                        <?= str_replace('_', ' ', $c['estado']) ?>
                                    </span>
                                </div>
                            </div>
 
                            <div class="text-end">
                                <div class="badge-pts-red">-<?= number_format($c['puntos_usados'] ?? 0) ?> pts</div>
                                <?php if ($c['monto'] > 0): ?>
                                    <div class="fw-bold text-dark small">+ S/ <?= number_format($c['monto'], 2) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <script> const BASE_URL = '<?= BASE_URL ?>'; </script>
    <script src="<?= BASE_URL ?>assets/js/session_check.js"></script>
</body>
</html>
