<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-submit {
            background: #0f172a;
            color: #fff;
            border: none;
            padding: 0.85rem 2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-submit:hover {
            background: #1e293b;
        }
        
        .btn-cancel {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .btn-cancel:hover {
            color: #0f172a;
        }
    </style>
</head>
<body>
<div id="app">

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión FISE';
            $pageSubtitle = $titulo;
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="form-container">
                <h2 style="margin-top:0; margin-bottom: 1.5rem; color:#0f172a; font-weight: 800;"><i class='bx bx-barcode'></i> <?= $titulo ?></h2>
                
                <form action="<?= BASE_URL ?>fise/<?= $fise ? 'update' : 'create' ?>" method="POST">
                    <?php if ($fise): ?>
                        <input type="hidden" name="id" value="<?= $fise['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Código FISE</label>
                        <input type="text" name="codigo" class="form-control" placeholder="Ej. FISE-123456" value="<?= $fise ? htmlspecialchars($fise['codigo']) : '' ?>" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Precio (S/)</label>
                            <input type="number" step="0.01" min="20" max="40" name="precio" class="form-control" placeholder="Ej. 25.50" value="<?= $fise ? htmlspecialchars($fise['precio']) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Puntos a Otorgar</label>
                            <input type="number" name="puntos" class="form-control" placeholder="Ej. 100" value="<?= $fise ? htmlspecialchars($fise['puntos']) : '' ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1" <?= ($fise && $fise['estado'] == 1) ? 'selected' : '' ?>>Activo (Canjeable)</option>
                            <option value="0" <?= ($fise && $fise['estado'] == 0) ? 'selected' : '' ?>>Inactivo / Usado</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-submit"><?= $fise ? 'Guardar Cambios' : 'Crear Código FISE' ?></button>
                    <a href="<?= BASE_URL ?>fise" class="btn-cancel">Cancelar y Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
