<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Premios — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --low: #2ecc71;
            --medium: #f1c40f;
            --high: #3498db;
            --vip: #e74c3c;
            --glass: rgba(255, 255, 255, 0.9);
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: #f8f9fa;
        }

        [v-cloak] { display: none; }

        /* Aumentar ancho del contenedor para la tienda */
        .container {
            max-width: 1400px !important;
            margin: 0 auto;
            padding: 1.5rem;
        }

        .store-header {
            padding: 2rem 1rem;
            text-align: center;
            background: linear-gradient(135deg, #4a0c0c, #821515);
            color: white;
            border-radius: 0 0 2rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .store-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .store-subtitle {
            opacity: 0.9;
            font-weight: 300;
        }

        .level-section {
            margin-bottom: 3rem;
            padding: 0 1rem;
        }

        .level-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eee;
        }

        .level-badge {
            width: 15px;
            height: 15px;
            border-radius: 50%;
        }

        .level-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3436;
        }

        .level-desc {
            font-size: 0.9rem;
            color: #636e72;
            margin-left: auto;
            font-style: italic;
        }

        .prizes-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
        }

        .prize-card {
            background: white;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
        }

        .prize-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .prize-image-container {
            height: 150px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            padding: 1rem;
        }

        .prize-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .prize-card:hover .prize-image {
            transform: scale(1.1);
        }

        .prize-info {
            padding: 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .prize-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2d3436;
            margin: 0;
            line-height: 1.1;
            height: 2.2em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .prize-points {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-weight: 700;
            color: #e67e22;
            font-size: 1.1rem;
        }

        .prize-points span {
            font-size: 0.65rem;
            color: #95a5a6;
            font-weight: 400;
            text-transform: uppercase;
        }

        .btn-redeem {
            width: 100%;
            padding: 0.6rem;
            border: none;
            border-radius: 0.8rem;
            background: #2d3436;
            color: white;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.8rem;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }

        .btn-redeem:hover {
            background: #000;
        }

        /* Border colors for levels */
        .level-low .level-badge { background: var(--low); }
        .level-low .prize-card { border-top: 4px solid var(--low); }
        
        .level-medium .level-badge { background: var(--medium); }
        .level-medium .prize-card { border-top: 4px solid var(--medium); }
        
        .level-high .level-badge { background: var(--high); }
        .level-high .prize-card { border-top: 4px solid var(--high); }
        
        .level-vip .level-badge { background: var(--vip); }
        .level-vip .prize-card { border-top: 4px solid var(--vip); }

        .back-nav {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .btn-back {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.3);
            transform: translateX(-5px);
        }

        @media (max-width: 1100px) {
            .prizes-grid { grid-template-columns: repeat(4, 1fr); }
        }

        @media (max-width: 992px) {
            .prizes-grid { grid-template-columns: repeat(3, 1fr); }
        }

        @media (max-width: 768px) {
            .prizes-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            .store-title { font-size: 1.6rem; }
            .prizes-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="store-header">
        <div class="back-nav">
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <a href="<?= BASE_URL ?>panel" class="btn-back">← Volver al Panel</a>
            <?php else: ?>
                <a href="javascript:history.back()" class="btn-back">← Volver</a>
            <?php endif; ?>
        </div>
        <h1 class="store-title">Portal de Premios</h1>
        <p class="store-subtitle">Canjea tus puntos por recompensas increíbles</p>
    </div>

    <div class="container">
        <?php foreach ($premios as $key => $nivel): ?>
            <section class="level-section <?= $nivel['clase'] ?>">
                <div class="level-header">
                    <span class="level-badge"></span>
                    <h2 class="level-title"><?= $nivel['titulo'] ?></h2>
                    <span class="level-desc"><?= $nivel['puntos'] ?></span>
                </div>

                <div class="prizes-grid">
                    <?php foreach ($nivel['items'] as $item): ?>
                        <div class="prize-card">
                            <div class="prize-image-container">
                                <img src="<?= BASE_URL ?>assets/premios/<?= $item['imagen'] ?>" alt="<?= $item['nombre'] ?>" class="prize-image">
                            </div>
                            <div class="prize-info">
                                <h3 class="prize-name"><?= $item['nombre'] ?></h3>
                                <p style="font-size: 0.8rem; color: #7f8c8d; margin: 0.3rem 0; line-height: 1.3; height: 2.6em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    <?= htmlspecialchars($item['descripcion']) ?>
                                </p>
                                <div class="prize-points">
                                    <?= number_format($item['puntos']) ?> <span>puntos</span>
                                </div>
                                <div style="font-size: 0.8rem; color: <?= $item['stock'] > 0 ? '#27ae60' : '#e74c3c' ?>; font-weight: 600; margin-top: 0.3rem;">
                                    Stock: <?= $item['stock'] ?> unidades
                                </div>
                                <button class="btn-redeem" <?= $item['stock'] <= 0 ? 'disabled style="background:#ccc"' : '' ?>>
                                    <?= $item['stock'] > 0 ? 'Canjear Premio' : 'Agotado' ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <div style="text-align: center; padding: 4rem 1rem; color: #95a5a6; font-size: 0.9rem;">
        &copy; 2026 PremiaSurgas — Elevando tu experiencia
    </div>
</body>
</html>
