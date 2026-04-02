<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración General — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .chip-puntos { background: #fdf2f2; color: #800000; border: 1px solid #fee2e2; font-weight: 800; padding: 4px 10px; border-radius: 8px; font-size: 0.85rem; }
        .chip-active { background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; }
        .chip-inactive { background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; }
        
        .preview-img-circle {
            width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
            border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .header-actions-group {
            display: flex; gap: 0.75rem;
        }

        .section-separator {
            height: 1px; background: #e2e8f0; margin: 3rem 0;
            position: relative;
        }
        .section-separator::after {
            content: attr(data-label);
            position: absolute; left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            background: var(--bg); padding: 0 1.5rem;
            font-size: 0.7rem; font-weight: 800; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.15em;
        }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Configuración General';
            $pageSubtitle = 'Administra reglas, premios y equipo en un solo lugar';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container" style="max-width: 1200px; padding-top: 1rem;">
            
            <!-- SECTION 1: OPERACIONES -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title-icon red-premium">
                            <i class='bx bx-calculator'></i>
                        </div>
                        Reglas de Puntaje
                    </div>
                    <div class="header-actions">
                        <a href="<?= BASE_URL ?>operaciones" class="btn-primary-premium">
                            <i class='bx bx-edit-alt'></i> Gestionar Reglas
                        </a>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre Operación</th>
                                <th class="text-center">Puntaje</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($operaciones, 0, 5) as $op): ?>
                            <tr>
                                <td class="text-medium"><?= htmlspecialchars($op['nombre']) ?></td>
                                <td class="text-center"><span class="chip-puntos"><?= $op['puntos'] ?> pts</span></td>
                                <td class="text-center">
                                    <span class="chip <?= $op['estado'] ? 'chip-active' : 'chip-inactive' ?>">
                                        <?= $op['estado'] ? 'ACTIVO' : 'INACTIVO' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: PREMIOS -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title-icon" style="background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5;">
                            <i class='bx bx-gift'></i>
                        </div>
                        Catálogo de Premios
                    </div>
                    <div class="header-actions">
                        <a href="<?= BASE_URL ?>productos" class="btn-primary-premium">
                            <i class='bx bx-package'></i> Gestionar Inventario
                        </a>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="ps-3">Imagen</th>
                                <th>Premio</th>
                                <th class="text-center">Puntos</th>
                                <th class="text-center">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($premios, 0, 5) as $p): ?>
                            <tr>
                                <td class="ps-3">
                                    <img src="<?= BASE_URL ?>assets/premios/<?= $p['imagen_url'] ?>" class="preview-img-circle" onerror="this.src='<?= BASE_URL ?>assets/premios/default.png'">
                                </td>
                                <td class="text-medium"><?= htmlspecialchars($p['nombre']) ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark" style="border: 1px solid #e2e8f0;"><?= $p['puntos'] ?> pts</span></td>
                                <td class="text-center"><?= $p['stock'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 3: CONDUCTORES -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title-icon" style="background: #f0fdf4; color: #166534; border: 1px solid #dcfce7;">
                            <i class='bx bxs-truck'></i>
                        </div>
                        Directorio de Conductores
                    </div>
                    <div class="header-actions">
                        <a href="<?= BASE_URL ?>conductores" class="btn-primary-premium">
                            <i class='bx bx-group'></i> Gestionar Equipo
                        </a>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre Conductor</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($conductores, 0, 5) as $c): ?>
                            <tr>
                                <td class="text-medium"><?= htmlspecialchars($c['nombre']) ?></td>
                                <td class="text-center text-muted"><?= htmlspecialchars($c['usuario']) ?></td>
                                <td class="text-center">
                                    <span class="chip <?= $c['estado'] ? 'chip-active' : 'chip-inactive' ?>">
                                        <?= $c['estado'] ? 'ACTIVO' : 'INACTIVO' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- .container -->
    </div> <!-- .admin-layout -->

</body>
</html>
