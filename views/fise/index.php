<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión FISE — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        .pts-badge {
            background: #e0f2fe; color: #0369a1;
            font-weight: 800; padding: 4px 10px;
            border-radius: 8px; font-size: 0.75rem;
            border: 1px solid #bae6fd;
        }
        .code-badge {
            background: #f1f5f9; color: #334155;
            font-weight: 700; padding: 4px 10px;
            border-radius: 8px; font-size: 0.85rem;
            border: 1px solid #cbd5e1;
            font-family: monospace;
        }
    </style>
</head>
<body>
<div id="app">

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
            $pageTitle    = 'Gestión FISE';
            $pageSubtitle = 'Administración de códigos de canje FISE';
            include __DIR__ . '/../partials/header_admin.php';
        ?>

        <div class="container">
            <div class="modern-section-header">
                <div class="section-title-flex">
                    <div class="section-title-text">
                        <h3>Códigos FISE</h3>
                        <span>Códigos estáticos para pruebas</span>
                    </div>
                </div>
                <div class="section-actions">
                    <a href="<?= BASE_URL ?>fise/nuevo" class="btn-primary-premium" style="text-decoration: none;">
                        Nuevo Código
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Código FISE</th>
                                <th class="text-center">Precio (S/)</th>
                                <th class="text-center">Equivalencia Puntos</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fises)): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-table">
                                        <i class='bx bx-barcode'></i>
                                        No hay códigos FISE registrados.
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($fises as $f): ?>
                            <tr>
                                <td>
                                    <span class="code-badge"><?= htmlspecialchars($f['codigo']) ?></span>
                                </td>
                                <td class="text-center"><span class="text-medium">S/ <?= number_format($f['precio'], 2) ?></span></td>
                                <td class="text-center"><span class="pts-badge">+<?= $f['puntos'] ?> pts</span></td>
                                <td>
                                    <span class="badge-status <?= $f['estado'] == 1 ? 'badge-approved' : 'badge-rejected' ?>">
                                        <?= $f['estado'] == 1 ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-flex" style="justify-content: center;">
                                        <a href="<?= BASE_URL ?>fise/editar?id=<?= $f['id'] ?>" class="btn-action blue" title="Editar">
                                            <i class='bx bx-edit-alt'></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>fise/delete?id=<?= $f['id'] ?>" class="btn-action red" title="Eliminar" onclick="return confirm('¿Eliminar código FISE?');">
                                            <i class='bx bx-trash'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer-premium">
                    <div class="footer-info">Mostrando <?= count($fises) ?> códigos FISE</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>
</body>
</html>
