<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Recargas — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #821515;
            --secondary: #2c3e50;
            --accent: #f1c40f;
            --bg-page: #f8fafc;
            --card-bg: #fff;
        }

        body { background-color: var(--bg-page); font-family: 'Inter', sans-serif; }
        .header-admin {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white; padding: 2rem 1rem; text-align: center; border-radius: 0 0 2rem 2rem;
            margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .container { max-width: 1000px; margin: 0 auto; padding: 0 1rem; }

        .card-management {
            background: white; border-radius: 1.5rem; padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.02); margin-bottom: 2rem;
            border: 1px solid #edf2f7;
        }

        .section-title { font-weight: 800; font-size: 1.3rem; color: #1a202c; display: flex; align-items: center; gap: .8rem; margin-bottom: 1.5rem; }

        .recarga-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.2rem; border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }
        .recarga-item:last-child { border-bottom: none; }
        .recarga-item:hover { background: #fdfdfd; }

        .client-info { display: flex; align-items: center; gap: 1rem; flex: 1; }
        .avatar-mini { width: 45px; height: 45px; border-radius: 12px; background: #fee2e2; color: #821515; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; }
        .info-txt b { display: block; color: #2d3748; font-size: 0.95rem; }
        .info-txt span { color: #718096; font-size: 0.8rem; }

        .mount-info { text-align: center; margin: 0 2rem; }
        .mount-pts { font-weight: 800; color: var(--primary); font-size: 1.1rem; display: block; }
        .mount-price { font-size: 0.8rem; color: #718096; }

        .actions { display: flex; gap: 0.8rem; }
        .btn-action { width: 40px; height: 40px; border-radius: 10px; border: none; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; transition: all 0.2s ease; }
        .btn-eye { background: #e0f2fe; color: #0369a1; }
        .btn-check { background: #dcfce7; color: #15803d; }
        .btn-x { background: #fee2e2; color: #b91c1c; }

        .btn-action:hover { transform: translateY(-3px); }

        .badge { padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .badge-pendiente { background: #fffbeb; color: #d97706; }
        .badge-aprobado { background: #f0fdf4; color: #166534; }
        .badge-rechazado { background: #fef2f2; color: #991b1b; }

        .empty-state { text-align: center; padding: 3rem 1rem; color: #a0aec0; }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; display: block; }

        .back-btn { display: inline-flex; align-items: center; gap: .5rem; color: #fff; text-decoration: none; font-size: 0.9rem; opacity: 0.8; transition: all 0.3s; margin-top: 1rem; }
        .back-btn:hover { opacity: 1; transform: translateX(-5px); }

        /* Modal simple para imagen */
        #imgModal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; padding: 2rem; }
        #imgModal.active { display: flex; }
        .modal-body { max-width: 500px; width: 100%; background: white; border-radius: 1.5rem; overflow: hidden; position: relative; animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-body img { width: 100%; height: auto; display: block; max-height: 70vh; object-fit: contain; background: #f0f0f0; }
        .modal-close { position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.5); color: white; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; }
    </style>
</head>
<body>

    <div class="header-admin">
        <a href="<?= BASE_URL ?>panel" class="back-btn"><i class='bx bx-chevron-left'></i> Volver al Panel</a>
        <h1 style="margin: 0.5rem 0 0; font-weight: 900; letter-spacing: -1px; font-size: 1.8rem;">Verificación de Recargas</h1>
        <p style="opacity: 0.7; font-size: 0.9rem;">Valida los comprobantes de pago de los clientes</p>
    </div>

    <div class="container" id="app">
        
        <?php if (isset($_SESSION['flash'])): ?>
            <script>
                Swal.fire({
                    icon: '<?= $_SESSION['flash']['type'] ?>',
                    title: '<?= $_SESSION['flash']['title'] ?>',
                    text: '<?= $_SESSION['flash']['message'] ?>',
                    confirmButtonColor: '#821515'
                });
            </script>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- Pendientes -->
        <div class="card-management">
            <div class="section-title">
                <i class='bx bxs-time-five' style="color: var(--accent);"></i> Pendientes de Revisión
            </div>

            <?php if (empty($recargas)): ?>
                <div class="empty-state">
                    <i class='bx bx-check-circle'></i>
                    No hay recargas pendientes por hoy. ¡Buen trabajo!
                </div>
            <?php else: ?>
                <div class="recarga-list">
                    <?php foreach ($recargas as $r): ?>
                        <div class="recarga-item">
                            <div class="client-info">
                                <div class="avatar-mini"><?= substr($r['cliente_nombre'], 0, 1) ?></div>
                                <div class="info-txt">
                                    <b><?= htmlspecialchars($r['cliente_nombre']) ?></b>
                                    <span><?= htmlspecialchars($r['cliente_celular']) ?> | DNI: <?= htmlspecialchars($r['cliente_dni']) ?></span>
                                </div>
                            </div>

                            <div class="mount-info">
                                <span class="mount-pts">+<?= number_format($r['puntos']) ?> pts</span>
                                <span class="mount-price">S/ <?= number_format($r['monto'], 2) ?></span>
                            </div>

                            <div class="actions">
                                <button class="btn-action btn-eye" title="Ver Comprobante" onclick="abrirFoto('<?= BASE_URL ?>assets/uploads/comprobantes/<?= $r['comprobante'] ?>')">
                                    <i class='bx bx-image-alt'></i>
                                </button>
                                
                                <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="display:inline;" onsubmit="return confirmar('¿Aprobar esta recarga? Se sumarán los puntos al cliente.')">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="estado" value="aprobado">
                                    <button type="submit" class="btn-action btn-check" title="Aprobar">
                                        <i class='bx bx-check'></i>
                                    </button>
                                </form>

                                <form action="<?= BASE_URL ?>recargas-admin/actualizar" method="POST" style="display:inline;" onsubmit="return confirmar('¿Rechazar esta recarga? El cliente no recibirá los puntos.')">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="estado" value="rechazado">
                                    <button type="submit" class="btn-action btn-x" title="Rechazar">
                                        <i class='bx bx-x'></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Historial Reciente -->
        <div class="card-management" style="opacity: 0.9;">
            <div class="section-title" style="font-size: 1.1rem; color: #718096;">
                <i class='bx bx-history'></i> Historial Reciente (Últimas 50)
            </div>

            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                    <thead>
                        <tr style="text-align: left; color: #a0aec0; border-bottom: 2px solid #f1f5f9;">
                            <th style="padding: 1rem 0;">Cliente</th>
                            <th>Puntos</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Validador</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $h): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 1rem 0;"><b><?= htmlspecialchars($h['cliente_nombre']) ?></b></td>
                            <td><span style="color:var(--primary); font-weight:700;">+<?= number_format($h['puntos']) ?></span></td>
                            <td>S/ <?= number_format($h['monto'], 2) ?></td>
                            <td><span class="badge badge-<?= $h['estado'] ?>"><?= $h['estado'] ?></span></td>
                            <td><small><?= htmlspecialchars($h['validador_nombre'] ?? '-') ?></small></td>
                            <td><small style="color:#999;"><?= date('d/m H:i', strtotime($h['fecha'])) ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Foto -->
    <div id="imgModal">
        <div class="modal-body">
            <button class="modal-close" onclick="cerrarFoto()"><i class='bx bx-x'></i></button>
            <div style="padding: 1.5rem; text-align: center; border-bottom: 1px solid #eee;">
                <h4 style="margin: 0; color: #333;">Evidencia de Pago</h4>
            </div>
            <img id="fotoZoom" src="" alt="Comprobante">
            <div style="padding: 1rem; text-align: center; background: #f8fafc;">
                <p style="font-size: 0.8rem; color: #888; margin: 0;">Verifica que el monto y la fecha coincidan antes de aprobar.</p>
            </div>
        </div>
    </div>

    <script>
        function abrirFoto(url) {
            document.getElementById('fotoZoom').src = url;
            document.getElementById('imgModal').style.display = 'flex';
        }

        function cerrarFoto() {
            document.getElementById('imgModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            if (event.target == document.getElementById('imgModal')) {
                cerrarFoto();
            }
        }

        function confirmar(msg) {
            return confirm(msg);
        }
    </script>
</body>
</html>
