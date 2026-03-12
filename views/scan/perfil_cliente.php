<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #821515; --secondary: #4a0c0c; --accent: #f39c12; }
        body { font-family: 'Outfit', sans-serif; background: #f4f7f6; margin: 0; color: #333; }
        
        .header {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: white; padding: 2.5rem 1.5rem; text-align: center;
            border-radius: 0 0 2.5rem 2.5rem; box-shadow: 0 10px 30px rgba(130, 21, 21, 0.2);
        }
        .profile-avatar {
            width: 80px; height: 80px; background: white; color: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; font-weight: 700; margin: 0 auto 1rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header h1 { margin: 0; font-size: 1.6rem; }
        .header p { margin: 0.5rem 0 0; opacity: 0.8; font-size: 0.9rem; }

        .container { padding: 1.5rem; max-width: 500px; margin: -2rem auto 0; }
        
        .points-card {
            background: white; padding: 2rem; border-radius: 1.5rem; text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05); border: 1px solid #eee;
            margin-bottom: 1.5rem;
        }
        .points-label { font-size: 0.9rem; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .points-value { font-size: 4rem; font-weight: 800; color: var(--primary); display: block; line-height: 1; margin: 0.5rem 0; }
        .points-suffix { font-size: 1.2rem; color: #555; font-weight: 600; }

        .btn-store {
            background: linear-gradient(to right, #f39c12, #e67e22);
            color: white; text-decoration: none; padding: 1.2rem; border-radius: 1rem;
            display: flex; align-items: center; justify-content: center; gap: 0.8rem;
            font-weight: 700; font-size: 1.1rem; box-shadow: 0 10px 20px rgba(243, 156, 18, 0.3);
            transition: transform 0.2s;
        }
        .btn-store:active { transform: scale(0.98); }

        .section-title { font-size: 1rem; font-weight: 700; color: #555; margin: 2rem 0 1rem; padding-left: 0.5rem; border-left: 4px solid var(--primary); }

        .history-card { background: white; border-radius: 1rem; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
        .history-item { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 1rem; border-bottom: 1px solid #f5f5f5; 
        }
        .history-item:last-child { border-bottom: none; }
        .history-info b { display: block; font-size: 0.95rem; }
        .history-info span { font-size: 0.8rem; color: #999; }
        .history-pts { color: var(--primary); font-weight: 700; font-size: 1.1rem; }

        .footer { text-align: center; padding: 2rem; color: #aaa; font-size: 0.8rem; }
    </style>
</head>
<body>

    <div class="header">
        <div class="profile-avatar"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
        <h1>¡Hola, <?= explode(' ', $cliente['nombre'])[0] ?>!</h1>
        <p>Bienvenido a tu programa de puntos Surgas</p>
    </div>

    <div class="container">
        <div class="points-card">
            <span class="points-label">Tienes acumulados</span>
            <b class="points-value"><?= $cliente['puntos'] ?></b>
            <span class="points-suffix">Puntos Surgas</span>
        </div>

        <a href="<?= BASE_URL ?>tienda" class="btn-store">
            <span>🎁</span> Ver premios en la Tienda
        </a>

        <div class="section-title">Mi Historial Reciente</div>
        
        <div class="history-card">
            <?php if (empty($ventas)): ?>
                <div style="padding: 2rem; text-align: center; color: #ccc;">Aún no tienes movimientos registrados.</div>
            <?php else: ?>
                <?php foreach (array_slice($ventas, 0, 5) as $v): ?>
                <div class="history-item">
                    <div class="history-info">
                        <b><?= $v['monto'] > 0 ? 'Compra realizada' : 'Carga de puntos' ?></b>
                        <span><?= date('d/m/Y', strtotime($v['fecha'])) ?></span>
                    </div>
                    <div class="history-pts">+<?= $v['puntos'] ?></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            PremiaSurgas &copy; <?= date('Y') ?> <br>
            ¡Sigue acumulando para canjear increíbles premios!
        </div>
    </div>

</body>
</html>
