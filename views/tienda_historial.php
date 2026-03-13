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
            margin-bottom: 1.5rem; transition: transform 0.3s;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .history-card:hover { transform: translateY(-5px); }

        .prize-thumb { width: 80px; height: 80px; object-fit: contain; background: #fff; padding: 10px; border-radius: 1rem; }
        
        .badge-points { background: #fff3e0; color: #e67e22; font-weight: 700; border-radius: 50px; padding: 0.4rem 1rem; }
        .badge-date { background: #f1f2f6; color: #747d8c; font-weight: 600; border-radius: 50px; padding: 0.4rem 1rem; }
        
        .empty-state { text-align: center; padding: 5rem 2rem; background: white; border-radius: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container">
        <div class="history-header">
            <div class="back-nav">
                <a href="<?= BASE_URL ?>tienda" class="btn-back">← Volver a Tienda</a>
            </div>
            <h1 class="display-6 fw-bold mb-1">Tu Historial de Canjes</h1>
            <p class="opacity-75">Revisa todos los premios que has obtenido</p>
        </div>

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
                    <div class="col-12">
                        <div class="history-card p-4">
                            <div class="d-flex align-items-center flex-wrap gap-4">
                                <img src="<?= BASE_URL ?>assets/premios/<?= $c['premio_imagen'] ?>" alt="<?= $c['premio_nombre'] ?>" class="prize-thumb shadow-sm">
                                
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1"><?= $c['premio_nombre'] ?></h5>
                                    <p class="text-muted small mb-0"><?= $c['premio_descripcion'] ?></p>
                                </div>

                                <div class="text-end d-flex flex-column gap-2">
                                    <span class="badge-points">⭐ <?= number_format($c['puntos_usados'] ?? 0) ?> Pts</span>
                                    <?php if ($c['monto'] > 0): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold">
                                            + S/ <?= number_format($c['monto'], 2) ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="badge-date small">🗓️ <?= date('d/m/Y', strtotime($c['fecha'])) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
