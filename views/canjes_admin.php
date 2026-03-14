<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Canjes — PremiaSurgas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f8f9fa; }
        .container { max-width: 1000px; margin-top: 2rem; }
        .card { border-radius: 1.5rem; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .table thead th { border: none; color: #888; text-transform: uppercase; font-size: 0.75rem; }
        .badge-pendiente { background: #fef3c7; color: #92400e; }
        .badge-entregado { background: #dcfce7; color: #166534; }
        .btn-status { font-size: 0.8rem; font-weight: 600; border-radius: 50px; padding: 0.4rem 1rem; }
    </style>
</head>
<body>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="<?= BASE_URL ?>panel" class="btn btn-sm btn-outline-secondary rounded-pill px-3">← Volver</a>
            <h1 class="h3 fw-bold mt-2">Gestión de Entregas</h1>
        </div>
        <div class="text-end">
            <span class="badge bg-white text-dark shadow-sm p-2 rounded-3 border">
                Total canjes: <?= count($canjes) ?>
            </span>
        </div>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Premio</th>
                        <th>Estado</th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($canjes)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No hay canjes registrados.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($canjes as $c): ?>
                    <tr>
                        <td class="small"><?= date('d/m/Y H:i', strtotime($c['fecha'])) ?></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($c['cliente_nombre']) ?></div>
                            <div class="small text-muted"><?= $c['cliente_celular'] ?></div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= BASE_URL ?>assets/premios/<?= $c['premio_imagen'] ?>" style="width: 35px; height: 35px; object-fit: contain;">
                                <span><?= htmlspecialchars($c['premio_nombre']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill p-2 px-3 fw-bold <?= $c['estado'] === 'entregado' ? 'badge-entregado' : 'badge-pendiente' ?>">
                                <?= strtoupper($c['estado']) ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <?php if ($c['estado'] === 'pendiente'): ?>
                                <form action="<?= BASE_URL ?>canjes-admin/actualizar" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                    <input type="hidden" name="estado" value="entregado">
                                    <button type="submit" class="btn btn-success btn-status shadow-sm">Marcar Entregado</button>
                                </form>
                            <?php else: ?>
                                <span class="text-success fw-bold"><i class='bx bx-check-circle'></i> Listo</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        timer: 3000,
        showConfirmButton: false
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
