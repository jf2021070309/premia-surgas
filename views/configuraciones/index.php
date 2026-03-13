<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Parámetros — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f0f2f5; }
        .container { max-width: 900px; margin: 3rem auto; padding: 0 1.5rem; }
        .config-card {
            background: white; border-radius: 2rem; padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.05);
        }
        .header { margin-bottom: 2rem; }
        .title { font-size: 1.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.5rem; }
        .subtitle { color: #666; font-size: 0.95rem; }
        
        .param-item {
            background: #f8fafc; border-radius: 1.2rem; padding: 1.5rem;
            margin-bottom: 1.2rem; border: 1px solid #edf2f7;
            transition: all 0.3s;
        }
        .param-item:focus-within { border-color: #821515; background: white; box-shadow: 0 10px 20px rgba(130, 21, 21, 0.05); }

        .param-label { display: block; font-weight: 600; color: #4a5568; margin-bottom: 0.5rem; font-size: 0.9rem; }
        .param-desc { display: block; color: #718096; font-size: 0.8rem; margin-bottom: 1rem; }
        .param-input {
            width: 100%; padding: 0.8rem 1.2rem; border: 2px solid #e2e8f0;
            border-radius: 0.8rem; outline: none; transition: 0.2s;
            font-family: inherit; font-weight: 600; color: #2d3436;
        }
        .param-input:focus { border-color: #821515; }

        .btn-save {
            width: 100%; padding: 1rem; border: none; border-radius: 1rem;
            background: #821515; color: white; font-weight: 700;
            cursor: pointer; transition: 0.3s; font-size: 1rem; margin-top: 1rem;
            box-shadow: 0 10px 20px rgba(130, 21, 21, 0.2);
        }
        .btn-save:hover { background: #4a0c0c; transform: translateY(-3px); box-shadow: 0 15px 30px rgba(130, 21, 21, 0.3); }

        .back-nav { margin-bottom: 1.5rem; }
        .btn-back { color: #821515; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-nav">
            <a href="<?= BASE_URL ?>panel" class="btn-back">← Volver al Panel</a>
        </div>

        <div class="config-card">
            <div class="header">
                <h1 class="title">Parámetros del Sistema</h1>
                <p class="subtitle">Configura la relación de puntos y equivalencias para las operaciones.</p>
            </div>

            <form action="<?= BASE_URL ?>configuraciones/update" method="POST">
                <?php foreach ($configuraciones as $config): ?>
                    <div class="param-item">
                        <label class="param-label" for="config_<?= $config['id'] ?>"><?= htmlspecialchars($config['clave']) ?></label>
                        <span class="param-desc"><?= htmlspecialchars($config['descripcion']) ?></span>
                        <input type="text" 
                               name="config[<?= $config['id'] ?>]" 
                               id="config_<?= $config['id'] ?>" 
                               class="param-input" 
                               value="<?= htmlspecialchars($config['valor']) ?>"
                               required>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn-save">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['flash']['type'] ?>',
            title: '<?= $_SESSION['flash']['title'] ?>',
            text: '<?= $_SESSION['flash']['message'] ?>',
            timer: 3000,
            timerProgressBar: true
        });
    </script>
    <?php unset($_SESSION['flash']); endif; ?>
</body>
</html>
