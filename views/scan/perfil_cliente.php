<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            color: white; text-decoration: none; padding: 1.2rem; border-radius: 1.25rem;
            display: flex; align-items: center; justify-content: center; gap: 0.8rem;
            font-weight: 700; font-size: 1.1rem; box-shadow: 0 10px 20px rgba(243, 156, 18, 0.3);
            transition: all 0.3s ease;
        }
        .btn-store i { font-size: 1.4rem; }
        .btn-store:active { transform: scale(0.98); }

        .section-title { 
            display: flex; align-items: center; gap: 10px;
            font-size: 1.1rem; font-weight: 700; color: #333; margin: 2rem 0 1rem; padding-left: 0.8rem; border-left: 5px solid var(--primary); 
        }
        .section-title i { color: var(--primary); font-size: 1.3rem; }

        .history-card { background: white; border-radius: 1.25rem; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #f0f0f0; }
        
        .history-item { 
            display: block; 
            padding: 1.25rem 1.5rem; 
            border-bottom: 1px solid #f6f6f6; 
            transition: all 0.3s ease;
            position: relative;
        }
        .history-item:last-child { border-bottom: none; }
        .history-item.has-details:hover { background: #fafafa; }
        
        .history-main-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-info-titles {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .history-info-titles i { color: #cbd5e0; font-size: 1.2rem; }

        .item-name { 
            font-size: 1rem; 
            font-weight: 700; 
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .item-pts { 
            color: var(--primary); 
            font-weight: 800; 
            font-size: 1.1rem;
            background: rgba(130, 21, 21, 0.05);
            padding: 4px 10px;
            border-radius: 8px;
        }
        
        .history-date { display: block; font-size: 0.85rem; color: #aab; margin-top: 6px; font-weight: 400; }

        .btn-expand {
            font-size: 0.7rem; 
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f8f9fa;
            color: #777;
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid #ebebeb;
            transition: all 0.2s;
        }
        .history-item.active .btn-expand {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .detail-list { 
            margin-top: 1rem; 
            padding: 1rem 0 0.5rem 0; 
            border-top: 1px dashed #eee;
            width: 100%;
        }
        .detail-subitem { 
            font-size: 0.95rem; 
            padding: 0.6rem 0; 
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sub-name { color: #555; font-weight: 500; }
        .sub-pts { 
            color: #555; 
            font-weight: 700; 
            font-size: 0.95rem;
            background: #f0f2f5;
            padding: 2px 8px;
            border-radius: 6px;
        }

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
            <span class="points-label"><i class='bx bx-medal'></i> Tienes acumulados</span>
            <b class="points-value"><?= $cliente['puntos'] ?></b>
            <span class="points-suffix">Puntos Surgas</span>
        </div>

        <a href="<?= BASE_URL ?>tienda" class="btn-store">
            <i class='bx bxs-gift'></i> Ver premios en la Tienda
        </a>

        <div class="section-title"><i class='bx bx-history'></i> Mi Historial Reciente</div>
        
        <div class="history-card">
            <?php if (empty($ventas)): ?>
                <div style="padding: 2rem; text-align: center; color: #ccc;">Aún no tienes movimientos registrados.</div>
            <?php else: ?>
                <?php foreach (array_slice($ventas, 0, 5) as $v): 
                    $detalleRaw = $v['detalle'] ?? '';
                    $conceptos = array_filter(explode(', ', $detalleRaw));
                    $esMultiple = count($conceptos) > 1;
                ?>
                <div class="history-item <?= $esMultiple ? 'has-details' : '' ?>" <?= $esMultiple ? 'onclick="toggleDetail(this)"' : '' ?>>
                    <div class="history-main-row">
                        <div class="history-info-titles">
                            <span class="item-name">
                                <i class='bx <?= $esMultiple ? 'bx-list-plus' : 'bx-badge-check' ?>'></i>
                                <?= $esMultiple ? 'Carga de puntos' : htmlspecialchars($detalleRaw ?: ($v['monto'] > 0 ? 'Compra realizada' : 'Carga de puntos')) ?>
                            </span>
                            <span class="item-pts">+<?= $v['puntos'] ?></span>
                        </div>
                        <?php if ($esMultiple): ?>
                            <span class="btn-expand">Ver más ▼</span>
                        <?php endif; ?>
                    </div>
                    
                    <span class="history-date"><?= date('d/m/Y', strtotime($v['fecha'])) ?></span>

                    <?php if ($esMultiple): ?>
                        <div class="detail-list" style="display: none;">
                            <?php foreach ($conceptos as $c): 
                                $nombreItem = htmlspecialchars($c);
                                $ptsItem = '';
                                if (preg_match('/(.*)\(\+([0-9]+)\s*pts\)/', $c, $matches)) {
                                    $nombreItem = trim($matches[1]);
                                    $ptsItem = '+' . $matches[2] . ' pts';
                                }
                            ?>
                                <div class="detail-subitem">
                                    <span class="sub-name"><?= $nombreItem ?></span>
                                    <?php if ($ptsItem): ?>
                                        <span class="sub-pts"><?= $ptsItem ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            PremiaSurgas &copy; <?= date('Y') ?> <br>
            ¡Sigue acumulando para canjear increíbles premios!
        </div>
    </div>

    <script>
        function toggleDetail(el) {
            const list = el.querySelector('.detail-list');
            const btn = el.querySelector('.btn-expand');
            if (list.style.display === 'none') {
                list.style.display = 'block';
                el.classList.add('active');
                if(btn) btn.innerHTML = 'contraer ▲';
            } else {
                list.style.display = 'none';
                el.classList.remove('active');
                if(btn) btn.innerHTML = 'ver más ▼';
            }
        }
    </script>
</body>
</html>
