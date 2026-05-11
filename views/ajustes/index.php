<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración General — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-tables.css">
    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2.5rem;
            margin-bottom: 3rem;
        }

        .preview-img-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .hybrid-rules-grid {
            display: grid;
            grid-template-columns: 1.2fr 1.5fr 0.8fr;
            gap: 2rem;
            align-items: start;
        }

        .info-box-premium {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .info-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            flex-shrink: 0;
        }

        .info-box-premium p {
            margin: 0;
            font-size: 0.85rem;
            color: #475569;
            line-height: 1.5;
        }

        .code-highlight {
            background: #e0f2fe;
            color: #0369a1;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .hybrid-rules-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .action-container {
                margin-top: 0.5rem;
            }
        }

        [data-pagination] {
            display: none;
        }

        /* Hidden by default till JS runs */
    </style>
</head>

<body>

    <?php include __DIR__ . '/../partials/sidebar_admin.php'; ?>

    <div class="admin-layout">
        <?php
        $pageTitle = 'Configuración General';
        $pageSubtitle = 'Panel maestro de reglas, catálogo y equipo';
        $pageIcon = 'bx-cog';
        include __DIR__ . '/../partials/header_admin.php';
        ?>
        <style>
            @keyframes spin { to { transform: rotate(360deg); } }
            .loading-spinner {
                width: 24px;
                height: 24px;
                border: 3px solid #f1f5f9;
                border-top-color: #3b82f6;
                border-radius: 50%;
                animation: spin 0.8s linear infinite;
            }
        </style>

        <div class="container" style="max-width: 1200px; padding-top: 1rem;">

            <!-- SECTION 1: PREMIOS -->
            <div class="clientes-toolbar" style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
                <div class="clientes-toolbar-filters">
                    <div class="section-title-flex">
                        <i class='bx bx-gift'></i>
                        <div class="section-title-text">
                            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">Catálogo de Premios</h3>
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Productos disponibles y sus costos</span>
                        </div>
                    </div>
                </div>
                <div class="clientes-toolbar-search" style="flex: 1; justify-content: flex-end;">
                    <div class="header-search-modern clientes-search-input" style="max-width: 320px;">
                        <i class='bx bx-search'></i>
                        <input type="text" placeholder="Buscar..." onkeyup="handleSearch('tablePremios', this.value)">
                    </div>
                    <button class="btn-primary-premium btn-nuevo-cliente" onclick="openModalPremio()">
                        <i class='bx bxs-award'></i> <span>Nuevo Premio</span>
                    </button>
                </div>
            </div>

            <div class="card shadow-sm" id="cardPremios">
                <div class="table-wrapper">
                    <table class="data-table" id="tablePremios">

                        <thead>
                            <tr>
                                <th class="ps-3 text-start">Premio</th>
                                <th class="text-center">Base (S/)</th>
                                <th class="text-center">Ganancia (Pts)</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center" style="width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($premios as $p): ?>
                                <tr class="table-row">
                                    <td class="text-medium ps-3"><?= htmlspecialchars($p['nombre']) ?></td>
                                    <td class="text-center"><span class="text-medium">S/ <?= number_format($p['precio_base'] ?? 0, 2) ?></span></td>
                                    <td class="text-center"><span class="text-pts-plus"><?= $p['puntos'] ?> pts</span></td>
                                    <td class="text-center"><?= $p['stock'] ?></td>
                                    <td class="text-center">
                                        <span class="chip <?= $p['estado'] ? 'chip-approved' : 'chip-rejected' ?>">
                                            <i class='bx bxs-circle'></i> <?= $p['estado'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="actions-flex" style="justify-content: center;">
                                            <button class="btn-action indigo"
                                                onclick="viewPremioImage('<?= $p['nombre'] ?>', '<?= BASE_URL ?>assets/premios/<?= $p['imagen'] ?>')"><i
                                                    class='bx bx-show'></i></button>
                                            <button class="btn-action blue"
                                                onclick="editPremio(<?= htmlspecialchars(json_encode($p)) ?>)"><i
                                                    class='bx bx-edit-alt'></i></button>
                                            <button class="btn-action red"
                                                onclick="confirmDeletePremio('<?= BASE_URL ?>productos/delete?id=<?= $p['id'] ?>')"><i
                                                    class='bx bx-trash'></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer-premium" id="footerPremios">
                    <div class="footer-info">Mostrando <span class="range"></span> de <span class="total"></span></div>
                    <div class="pagination-elite" data-pagination="tablePremios"></div>
                </div>
            </div>

            <!-- SECTION: REGLAS DE CANJE HÍBRIDO -->
            <div class="clientes-toolbar" style="margin-top: 3.5rem; margin-bottom: 1.5rem;">
                <div class="clientes-toolbar-filters">
                    <div class="section-title-flex">
                        <i class='bx bx-coin-stack'></i>
                        <div class="section-title-text">
                            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">Reglas de Canje Híbrido</h3>
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Define la equivalencia de puntos a soles para pagos</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-5" style="border-radius: 24px; border: none; overflow: hidden; background: #fff;">
                <div style="padding: 2.5rem;">
                    <form action="<?= BASE_URL ?>ajustes/update-puntos" method="POST">
                        <div class="hybrid-rules-grid">
                            
                            <!-- Input Group -->
                            <div class="form-group">
                                <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.8rem;">
                                    Valor de 1 punto (en Soles)
                                </label>
                                <div style="position: relative; display: flex; align-items: center;">
                                    <i class='bx bx-money' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.25rem;"></i>
                                    <input type="number" name="valor" step="0.0001" 
                                           value="<?= $montoPorPunto ?>" 
                                           required
                                           style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 0.9rem 1rem 0.9rem 2.8rem; font-size: 1rem; color: #1e293b; background: #fff; outline: none; transition: all 0.2s;">
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="info-box-premium">
                                <div class="info-icon">
                                    <i class='bx bx-info-circle'></i>
                                </div>
                                <p>
                                    <b>Guía de conversión:</b> Si ingresas <code class="code-highlight">0.1</code>, el sistema calculará <b>10 puntos = S/ 1.00</b> (1000 pts = S/ 100.00).
                                </p>
                            </div>

                            <!-- Action -->
                            <div class="action-container">
                                <button type="submit" class="btn-premium-pill-black" style="width: 100%; background: #000; color: #fff; border: none; padding: 1rem; border-radius: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <i class='bx bx-save' style="font-size: 1.1rem;"></i>
                                    Actualizar Regla
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
                <div style="background: #fafbfc; padding: 0.8rem 2.5rem; border-top: 1px solid #f1f5f9;">
                    <p style="margin: 0; font-size: 0.7rem; color: #94a3b8; font-weight: 500;">Esta configuración afecta los canjes parciales (Puntos + Efectivo/Depósito).</p>
                </div>
            </div>

            <!-- SECTION 2: OPERACIONES -->
            <div class="clientes-toolbar" style="margin-top: 3.5rem; margin-bottom: 1.5rem;">
                <div class="clientes-toolbar-filters">
                    <div class="section-title-flex">
                        <i class='bx bx-calculator'></i>
                        <div class="section-title-text">
                            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">Reglas de Puntaje</h3>
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Puntos acumulados por cada operación</span>
                        </div>
                    </div>
                </div>
                <div class="clientes-toolbar-search" style="flex: 1; justify-content: flex-end;">
                    <div class="header-search-modern clientes-search-input" style="max-width: 320px;">
                        <i class='bx bx-search'></i>
                        <input type="text" placeholder="Buscar..." onkeyup="handleSearch('tableOp', this.value)">
                    </div>
                    <button class="btn-primary-premium btn-nuevo-cliente" onclick="openModalOp()">
                        <i class='bx bx-layer-plus'></i> <span>Nueva Regla</span>
                    </button>
                </div>
            </div>

            <div class="card shadow-sm" id="cardOp">
                <div class="table-wrapper">
                    <table class="data-table" id="tableOp">

                        <thead>
                            <tr>
                                <th>Nombre Operación</th>
                                <th class="text-center">Precio Base</th>
                                <th class="text-center">Dcto Tarjeta</th>
                                <th class="text-center">Puntaje Recibido</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center" style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($operaciones as $op): ?>
                                <tr class="table-row">
                                    <td class="text-medium"><?= htmlspecialchars($op['nombre']) ?></td>
                                    <td class="text-center"><span class="text-medium" style="font-weight: 700; color: #1e293b;">S/ <?= number_format($op['precio_estandar'] ?? 0, 2) ?></span></td>
                                    <td class="text-center"><span class="text-medium" style="color: #ef4444; font-weight: 700;">- S/ <?= number_format($op['descuento'] ?? 0, 2) ?></span></td>
                                    <td class="text-center"><span class="text-pts-plus"><?= $op['puntos'] ?> pts</span></td>
                                    <td class="text-center">
                                        <span class="chip <?= $op['estado'] ? 'chip-approved' : 'chip-rejected' ?>">
                                            <i class='bx bxs-circle'></i> <?= $op['estado'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="actions-flex" style="justify-content: center;">
                                            <button class="btn-action blue"
                                                onclick="editOp(<?= htmlspecialchars(json_encode($op)) ?>)"><i
                                                    class='bx bx-edit-alt'></i></button>
                                            <button class="btn-action red"
                                                onclick="confirmDeleteOp('<?= BASE_URL ?>operaciones/delete?id=<?= $op['id'] ?>')"><i
                                                    class='bx bx-trash'></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer-premium" id="footerOp">
                    <div class="footer-info">Mostrando <span class="range"></span> de <span class="total"></span></div>
                    <div class="pagination-elite" data-pagination="tableOp"></div>
                </div>
            </div>

            <!-- SECTION 3: CONDUCTORES -->
            <div class="clientes-toolbar" style="margin-top: 3.5rem; margin-bottom: 1.5rem;">
                <div class="clientes-toolbar-filters">
                    <div class="section-title-flex">
                        <i class='bx bxs-truck'></i>
                        <div class="section-title-text">
                            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">Directorio de Conductores</h3>
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Control de acceso y perfiles del personal</span>
                        </div>
                    </div>
                </div>
                <div class="clientes-toolbar-search" style="flex: 1; justify-content: flex-end;">
                    <div class="header-search-modern clientes-search-input" style="max-width: 320px;">
                        <i class='bx bx-search'></i>
                        <input type="text" placeholder="Buscar..." onkeyup="handleSearch('tableCond', this.value)">
                    </div>
                    <button class="btn-primary-premium btn-nuevo-cliente" onclick="openModalCond()">
                        <i class='bx bx-user-plus'></i> <span>Nuevo Conductor</span>
                    </button>
                </div>
            </div>

            <div class="card shadow-sm" id="cardCond">
                <div class="table-wrapper">
                    <table class="data-table" id="tableCond">

                        <thead>
                            <tr>
                                <th>Nombre Conductor</th>
                                <th class="text-center">Departamento</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center" style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($conductores as $c): ?>
                                <tr class="table-row">
                                    <td class="text-medium"><?= htmlspecialchars($c['nombre']) ?></td>
                                    <td class="text-center"><span class="badge" style="background:#f1f5f9; color:#475569; font-weight:700; font-size:0.7rem; padding:4px 10px; border-radius:6px;"><?= htmlspecialchars($c['departamento'] ?? 'N/A') ?></span></td>
                                    <td class="text-center text-muted"><?= htmlspecialchars($c['usuario']) ?></td>
                                    <td class="text-center">
                                        <span class="chip <?= $c['estado'] ? 'chip-approved' : 'chip-rejected' ?>">
                                            <i class='bx bxs-circle'></i> <?= $c['estado'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="actions-flex" style="justify-content: center;">
                                            <button class="btn-action blue"
                                                onclick="editCond(<?= htmlspecialchars(json_encode($c)) ?>)"><i
                                                    class='bx bx-edit-alt'></i></button>
                                            <button class="btn-action red"
                                                onclick="confirmDeleteCond('<?= BASE_URL ?>conductores/delete?id=<?= $c['id'] ?>')"><i
                                                    class='bx bx-trash'></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer-premium" id="footerCond">
                    <div class="footer-info">Mostrando <span class="range"></span> de <span class="total"></span></div>
                    <div class="pagination-elite" data-pagination="tableCond"></div>
                </div>
            </div>

        </div>
    </div>

    <div id="modalOp" class="modal-overlay"
        style="display: none; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 10000;"
        onclick="if(event.target===this) closeModalOp()">
        <div class="modal-content-wrapper"
            style="max-width: 480px; width: 95%; background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.3); animation: slideUp 0.3s ease;">
            <div class="modal-header-premium"
                style="padding: 2rem 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <h2 id="modalTitleOp" style="font-weight: 800; font-size: 1.25rem; color: #0f172a; margin: 0;">Nueva
                    Regla de Puntaje</h2>
                <div class="modal-close" onclick="closeModalOp()"
                    style="cursor: pointer; width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b;">
                    <i class='bx bx-x'></i></div>
            </div>
            <form id="formOp" method="POST" action="<?= BASE_URL ?>operaciones/create">
                <div class="modal-body-premium" style="padding: 2.5rem;">
                    <input type="hidden" name="id" id="op_id">
                    <input type="hidden" name="redir" value="ajustes">

                    <div style="margin-bottom: 1.5rem;">
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Nombre
                            de la Operación</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-rename'
                                style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" name="nombre" id="op_nombre" required
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none; transition: all 0.2s;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <div>
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Precio Base (S/)</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-money'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="number" step="0.01" name="precio_estandar" id="op_precio" value="0.00" required
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none;">
                            </div>
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Descuento Tarjeta (S/)</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bxs-discount'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="number" step="0.01" name="descuento" id="op_descuento" value="0.00" required
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none;">
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;">
                        <div id="group_estadoOp" style="display: none;">
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Estado</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-toggle-right'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                                <select name="estado" id="op_estado"
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; appearance: none; cursor: pointer; outline: none; background: #fff;">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                                <i class='bx bx-chevron-down'
                                    style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-premium"
                    style="padding: 1.5rem 2.5rem 2.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-premium-pill-black"
                        style="background: #000; color: #fff; border: none; padding: 0.85rem 2.2rem; border-radius: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s;">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PREMIOS -->
    <div id="modalPremio" class="modal-overlay"
        style="display: none; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 10000; padding: 2rem;"
        onclick="if(event.target===this) closeModalPremio()">
        <div class="modal-content-wrapper"
            style="max-width: 550px; width: 100%; max-height: 92vh; background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.3); animation: slideUp 0.3s ease; display: flex; flex-direction: column;">
            <div class="modal-header-premium"
                style="padding: 1.8rem 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <h2 id="modalTitlePremio" style="font-weight: 800; font-size: 1.25rem; color: #0f172a; margin: 0;">
                    Gestionar Premio</h2>
                <div class="modal-close" onclick="closeModalPremio()"
                    style="cursor: pointer; width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b;">
                    <i class='bx bx-x'></i>
                </div>
            </div>

            <form id="formPremio" method="POST" action="<?= BASE_URL ?>productos/create" enctype="multipart/form-data"
                style="display: flex; flex-direction: column; overflow: hidden; height: 100%;">
                <div class="modal-body-premium" style="padding: 2.5rem; overflow-y: auto; flex-grow: 1;">
                    <input type="hidden" name="id" id="premio_id">
                    <input type="hidden" name="redir" value="ajustes">

                    <div style="margin-bottom: 1.4rem;">
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Nombre
                            Comercial del Premio</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-shopping-bag'
                                style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" name="nombre" id="premio_nombre" required
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1.4rem;">
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Descripción / Detalles</label>
                        <div style="position: relative; display: flex;">
                            <i class='bx bx-detail'
                                style="position: absolute; left: 1.1rem; top: 0.9rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <textarea name="descripcion" id="premio_descripcion" rows="3"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none; resize: none; font-family: inherit; transition: border-color 0.2s;"></textarea>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1.4rem;">
                        <div>
                            <label style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Base (S/)</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-money' style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="number" step="0.01" name="precio_base" id="premio_precio_base" required style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none;">
                            </div>
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Ganancia (Pts)</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-star'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="number" name="puntos" id="premio_puntos" required
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none;">
                            </div>
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Stock
                                Actual</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <i class='bx bx-box'
                                    style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                                <input type="number" name="stock" id="premio_stock" required
                                    style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; outline: none;">
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 1.4rem;">
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Fotografía
                            del Premio</label>
                        <input type="file" name="imagen_file" id="premio_file_input" hidden accept="image/*" onchange="handleImagePreview(this)">
                        <div onclick="document.getElementById('premio_file_input').click()" id="preview_container_premio"
                            style="display: flex; align-items: center; gap: 1rem; border: 2.5px dashed #e2e8f0; border-radius: 16px; padding: 1.2rem; cursor: pointer; background: #fafbfc; transition: all 0.2s; min-height: 80px;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fff; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #94a3b8; flex-shrink: 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                                <i class='bx bx-image-add'></i>
                            </div>
                            <div style="flex: 1;">
                                <span style="display: block; font-size: 0.88rem; font-weight: 700; color: #1e293b;">Seleccionar imagen</span>
                                <span style="font-size: 0.72rem; color: #94a3b8; font-weight: 500;">PNG, JPG o WEBP</span>
                            </div>
                            <i class='bx bx-upload' style="color: #cbd5e1; font-size: 1.4rem;"></i>
                        </div>
                    </div>

                    <div>
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Estado
                            en Tienda</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-show-alt'
                                style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                            <select name="estado" id="premio_estado"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; appearance: none; cursor: pointer; outline: none; background: #fff;">
                                <option value="1">Activo / Visible</option>
                                <option value="0">Inactivo / Oculto</option>
                            </select>
                            <i class='bx bx-chevron-down'
                                style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-premium"
                    style="padding: 1.5rem 2.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; shrink: 0; background: #fafbfc;">
                    <button type="submit" class="btn-premium-pill-black"
                        style="background: #000; color: #fff; border: none; padding: 0.85rem 2.2rem; border-radius: 14px; font-weight: 700; cursor: pointer;">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONDUCTORES -->
    <div id="modalCond" class="modal-overlay"
        style="display: none; align-items: center; justify-content: center; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 10000; padding: 2rem;"
        onclick="if(event.target===this) closeModalCond()">
        <div class="modal-content-wrapper"
            style="max-width: 480px; width: 100%; max-height: 92vh; background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.3); animation: slideUp 0.3s ease; display: flex; flex-direction: column;">
            <div class="modal-header-premium"
                style="padding: 1.8rem 2.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <h2 id="modalTitleCond" style="font-weight: 800; font-size: 1.25rem; color: #0f172a; margin: 0;">Registrar Nuevo Conductor</h2>
                <div class="modal-close" onclick="closeModalCond()"
                    style="cursor: pointer; width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b;">
                    <i class='bx bx-x'></i></div>
            </div>
            <form id="formCond" method="POST" action="<?= BASE_URL ?>conductores/create" style="display: flex; flex-direction: column; overflow: hidden; height: 100%;">
                <div class="modal-body-premium" style="padding: 2.5rem; overflow-y: auto; flex-grow: 1;">
                    <input type="hidden" name="id" id="cond_id">
                    <input type="hidden" name="redir" value="ajustes">

                    <div style="margin-bottom: 1.4rem;">
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Nombre
                            y Apellidos</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-id-card'
                                style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" name="nombre" id="cond_nombre" required
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1.4rem;">
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Nombre
                            de Usuario</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-user'
                                style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="text" name="usuario" id="cond_usuario" required
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1.4rem;">
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Nueva
                            Contraseña</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-key'
                                style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem;"></i>
                            <input type="password" name="password" id="cond_pass"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; background: #fff; outline: none;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1.4rem;">
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Departamento</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-map'
                                style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                            <select name="departamento" id="cond_departamento" required
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; appearance: none; cursor: pointer; outline: none; background: #fff;">
                                <option value="">-- Seleccionar --</option>
                                <option value="Tacna">Tacna</option>
                                <option value="Moquegua">Moquegua</option>
                                <option value="Arequipa">Arequipa</option>
                                <option value="Ilo">Ilo</option>
                            </select>
                            <i class='bx bx-chevron-down'
                                style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                        </div>
                    </div>

                    <div>
                        <label
                            style="display: block; font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem;">Estado</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <i class='bx bx-toggle-right'
                                style="position: absolute; left: 1.1rem; color: #94a3b8; font-size: 1.2rem; pointer-events: none;"></i>
                            <select name="estado" id="cond_estado"
                                style="width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem 0.85rem 2.9rem; font-size: 0.92rem; color: #1e293b; appearance: none; cursor: pointer; outline: none; background: #fff;">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            <i class='bx bx-chevron-down'
                                style="position: absolute; right: 1rem; color: #94a3b8; pointer-events: none;"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-premium"
                    style="padding: 1.5rem 2.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; flex-shrink: 0; background: #fafbfc;">
                    <button type="submit" class="btn-premium-pill-black"
                        style="background: #000; color: #fff; border: none; padding: 0.85rem 2.2rem; border-radius: 14px; font-weight: 700; cursor: pointer;">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- PHOTO PREVIEW ---
        function viewPremioImage(name, url) {
            const existing = document.getElementById('premioPreviewOverlay');
            if (existing) existing.remove();

            const overlay = document.createElement('div');
            overlay.id = 'premioPreviewOverlay';
            overlay.style.cssText = `
                position: fixed; inset: 0; z-index: 9999;
                background: rgba(15, 23, 42, 0.7);
                backdrop-filter: blur(8px);
                display: flex; align-items: center; justify-content: center;
                animation: fadeInOverlay 0.2s ease;
            `;
            overlay.onclick = (e) => { if (e.target === overlay) overlay.remove(); };

            overlay.innerHTML = `
                <div style="
                    background: #fff; border-radius: 24px;
                    padding: 0; max-width: 400px; width: 90%;
                    box-shadow: 0 40px 80px rgba(0,0,0,0.25);
                    overflow: hidden;
                    animation: slideUpModal 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
                    position: relative;
                ">
                    <button onclick="document.getElementById('premioPreviewOverlay').remove()" style="
                        position: absolute; top: 12px; right: 12px;
                        width: 32px; height: 32px; border-radius: 50%;
                        border: none; background: rgba(0,0,0,0.06);
                        color: #64748b; font-size: 1.3rem; cursor: pointer;
                        display: flex; align-items: center; justify-content: center;
                        z-index: 10; transition: background 0.2s;
                    " onmouseover="this.style.background='rgba(0,0,0,0.12)'" onmouseout="this.style.background='rgba(0,0,0,0.06)'">
                        <i class='bx bx-x'></i>
                    </button>
                    <div style="
                        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                        padding: 44px 40px 32px;
                        display: flex; align-items: center; justify-content: center;
                        min-height: 250px;
                    ">
                        <img src="${url}" alt="${name}" style="
                            max-width: 200px; max-height: 200px; object-fit: contain;
                            filter: drop-shadow(0 16px 32px rgba(0,0,0,0.14));
                        " onerror="this.src='https://placehold.co/200x200?text=Sin+Imagen'">
                    </div>
                    <div style="padding: 20px 28px 26px; border-top: 1px solid #f1f5f9;">
                        <div style="font-size: 0.62rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px;">Premio</div>
                        <div style="font-size: 1.1rem; font-weight: 700; color: #1e293b;">${name}</div>
                    </div>
                </div>
                <style>
                    @keyframes fadeInOverlay { from { opacity:0; } to { opacity:1; } }
                    @keyframes slideUpModal  { from { opacity:0; transform: translateY(18px) scale(0.96); } to { opacity:1; transform: translateY(0) scale(1); } }
                </style>
            `;
            document.body.appendChild(overlay);
        }

        // --- PAGINATION MODULE ---
        const PagData = {
            tableOp: { page: 1, size: 5, search: '', footer: 'footerOp' },
            tablePremios: { page: 1, size: 10, search: '', footer: 'footerPremios' },
            tableCond: { page: 1, size: 5, search: '', footer: 'footerCond' }
        };

        function renderPagination(tableId) {
            const config = PagData[tableId];
            const table = document.getElementById(tableId);
            const tbody = table.querySelector('tbody');
            const allRows = Array.from(tbody.querySelectorAll('tr'));

            // Filter
            const visibleRows = allRows.filter(row => {
                const matchesSearch = row.innerText.toLowerCase().includes(config.search.toLowerCase());
                return matchesSearch;
            });

            const total = visibleRows.length;
            const totalPages = Math.ceil(total / config.size);

            if (config.page > totalPages) config.page = Math.max(1, totalPages);
            allRows.forEach(r => r.style.display = 'none');
            const start = (config.page - 1) * config.size;
            const end = start + config.size;
            const pageRows = visibleRows.slice(start, end);
            pageRows.forEach(r => r.style.display = '');

            const footer = document.getElementById(config.footer);
            const rangeSpan = footer.querySelector('.range');
            const totalSpan = footer.querySelector('.total');
            rangeSpan.innerText = total > 0 ? `${start + 1} - ${Math.min(end, total)}` : '0';
            totalSpan.innerText = total;

            const pagContainer = document.querySelector(`[data-pagination="${tableId}"]`);
            if (totalPages <= 1) {
                pagContainer.style.display = 'none';
                return;
            }

            pagContainer.style.display = 'flex';
            let html = `<a href="javascript:void(0)" class="page-btn nav-arrows ${config.page === 1 ? 'disabled' : ''}" onclick="changePage('${tableId}', ${config.page - 1})"><i class='bx bx-chevron-left'></i></a>`;
            for (let i = 1; i <= totalPages; i++) {
                html += `<a href="javascript:void(0)" class="page-btn ${i === config.page ? 'active' : ''}" onclick="changePage('${tableId}', i)">${i}</a>`;
            }
            html += `<a href="javascript:void(0)" class="page-btn nav-arrows ${config.page === totalPages ? 'disabled' : ''}" onclick="changePage('${tableId}', ${config.page + 1})"><i class='bx bx-chevron-right'></i></a>`;
            // Fix loop variable i check
            html = `<a href="javascript:void(0)" class="page-btn nav-arrows ${config.page === 1 ? 'disabled' : ''}" onclick="changePage('${tableId}', ${config.page - 1})"><i class='bx bx-chevron-left'></i></a>`;
            for (let i = 1; i <= totalPages; i++) {
                html += `<a href="javascript:void(0)" class="page-btn ${i === config.page ? 'active' : ''}" onclick="changePage('${tableId}', ${i})">${i}</a>`;
            }
            html += `<a href="javascript:void(0)" class="page-btn nav-arrows ${config.page === totalPages ? 'disabled' : ''}" onclick="changePage('${tableId}', ${config.page + 1})"><i class='bx bx-chevron-right'></i></a>`;
            pagContainer.innerHTML = html;
        }

        function changePage(tableId, targetPage) {
            const config = PagData[tableId];
            if (targetPage < 1) return;
            config.page = targetPage;
            renderPagination(tableId);
        }

        function handleSearch(tableId, val) {
            PagData[tableId].search = val;
            PagData[tableId].page = 1;
            renderPagination(tableId);
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderPagination('tableOp');
            renderPagination('tablePremios');
            renderPagination('tableCond');
        });

        function openModalOp() {
            document.getElementById('modalOp').style.display = 'flex';
            document.getElementById('formOp').action = '<?= BASE_URL ?>operaciones/create';
            document.getElementById('modalTitleOp').innerText = 'Nueva Regla de Puntaje';
            document.getElementById('formOp').reset();
            document.getElementById('group_estadoOp').style.display = 'none';
        }
        function closeModalOp() { document.getElementById('modalOp').style.display = 'none'; }

        function editOp(op) {
            document.getElementById('modalOp').style.display = 'flex';
            document.getElementById('formOp').action = '<?= BASE_URL ?>operaciones/update';
            document.getElementById('modalTitleOp').innerText = 'Editar Regla';
            document.getElementById('op_id').value = op.id;
            document.getElementById('op_nombre').value = op.nombre;
            document.getElementById('op_precio').value = op.precio_estandar;
            document.getElementById('op_descuento').value = op.descuento;
            document.getElementById('op_estado').value = op.estado;
            document.getElementById('group_estadoOp').style.display = 'block';
        }

        function confirmDeleteOp(url) {
            Swal.fire({
                title: '¿Eliminar regla?',
                text: "Esta acción es irreversible y la regla se borrará definitivamente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#800000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((r) => { if (r.isConfirmed) window.location.href = url + '&redir=ajustes'; });
        }

        function openModalPremio() {
            document.getElementById('modalPremio').style.display = 'flex';
            document.getElementById('formPremio').action = '<?= BASE_URL ?>productos/create';
            document.getElementById('modalTitlePremio').innerText = 'Registrar Nuevo Premio';
            document.getElementById('formPremio').reset();
            document.getElementById('premio_id').value = '';
        }
        function closeModalPremio() { document.getElementById('modalPremio').style.display = 'none'; }

        function editPremio(p) {
            document.getElementById('modalPremio').style.display = 'flex';
            document.getElementById('formPremio').action = '<?= BASE_URL ?>productos/update';
            document.getElementById('modalTitlePremio').innerText = 'Ajustar Inventario';
            document.getElementById('premio_id').value = p.id;
            document.getElementById('premio_nombre').value = p.nombre;
            document.getElementById('premio_descripcion').value = p.descripcion || '';
            document.getElementById('premio_puntos').value = p.puntos;
            document.getElementById('premio_precio_base').value = p.precio_base || 0;
            document.getElementById('premio_stock').value = p.stock;
            document.getElementById('premio_estado').value = p.estado;
            
            // Reset preview
            document.getElementById('preview_container_premio').innerHTML = `
                <div style="width: 48px; height: 48px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <i class='bx bx-image-add' style="font-size: 1.5rem; color: #94a3b8;"></i>
                </div>
                <div style="text-align: left;">
                    <p style="margin: 0; font-size: 0.875rem; font-weight: 600; color: #1e293b;">Seleccionar imagen</p>
                    <p style="margin: 0; font-size: 0.75rem; color: #64748b;">PNG, JPG o WEBP</p>
                </div>
                <i class='bx bx-upload' style="margin-left: auto; font-size: 1.25rem; color: #94a3b8;"></i>
            `;
        }

        function handleImagePreview(input) {
            const container = document.getElementById('preview_container_premio');
            if (input.files && input.files[0]) {
                // Show loading state
                container.innerHTML = `
                    <div class="loading-spinner"></div>
                    <div style="text-align: left;">
                        <p style="margin: 0; font-size: 0.875rem; font-weight: 700; color: #3b82f6;">Procesando...</p>
                        <p style="margin: 0; font-size: 0.75rem; color: #64748b;">Optimizando archivo</p>
                    </div>
                `;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    setTimeout(() => {
                        container.innerHTML = `
                            <div style="width: 48px; height: 48px; border-radius: 12px; overflow: hidden; border: 2.5px solid #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                                <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="text-align: left;">
                                <p style="margin: 0; font-size: 0.875rem; font-weight: 800; color: #059669;">¡Imagen Cargada!</p>
                                <p style="margin: 0; font-size: 0.75rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">${input.files[0].name}</p>
                            </div>
                            <i class='bx bxs-check-circle' style="margin-left: auto; font-size: 1.6rem; color: #10b981; animation: slideUpModal 0.3s ease;"></i>
                        `;
                    }, 800);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function confirmDeletePremio(url) {
            Swal.fire({
                title: '¿Eliminar producto?',
                text: "Esta acción es irreversible y se eliminará definitivamente del catálogo.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#800000',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((r) => { if (r.isConfirmed) window.location.href = url + '&redir=ajustes'; });
        }

        function openModalCond() {
            document.getElementById('modalCond').style.display = 'flex';
            document.getElementById('formCond').action = '<?= BASE_URL ?>conductores/create';
            document.getElementById('modalTitleCond').innerText = 'Registrar Nuevo Conductor';
            document.getElementById('formCond').reset();
            document.getElementById('cond_id').value = '';
        }
        function closeModalCond() { document.getElementById('modalCond').style.display = 'none'; }

        function editCond(c) {
            document.getElementById('modalCond').style.display = 'flex';
            document.getElementById('formCond').action = '<?= BASE_URL ?>conductores/update';
            document.getElementById('modalTitleCond').innerText = 'Modificar Perfil';
            document.getElementById('cond_id').value = c.id;
            document.getElementById('cond_nombre').value = c.nombre;
            document.getElementById('cond_usuario').value = c.usuario;
            document.getElementById('cond_departamento').value = c.departamento || '';
            document.getElementById('cond_estado').value = c.estado;
        }

        function confirmDeleteCond(url) {
            Swal.fire({
                title: '¿Eliminar conductor?',
                text: "El conductor será borrado permanentemente y no podrá acceder al sistema.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#800000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((r) => { if (r.isConfirmed) window.location.href = url + '&redir=ajustes'; });
        }
    </script>

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
