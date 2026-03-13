<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
</head>
<body>
    <div class="topbar">
        <a href="<?= BASE_URL ?>clientes/lista" style="color:#fff; font-size:1.3rem; margin-right:.8rem">←</a>
        <span class="topbar-logo">✏️ Editar Cliente</span>
    </div>

    <div class="container" style="max-width: 500px; margin-top: 2rem;">
        <div class="card">
            <form action="<?= BASE_URL ?>clientes/update" method="POST" style="padding: 1.5rem;">
                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" name="celular" value="<?= htmlspecialchars($cliente['celular']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>">
                </div>

                <div class="form-group">
                    <label>Distrito</label>
                    <input type="text" name="distrito" value="<?= htmlspecialchars($cliente['distrito']) ?>">
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="1" <?= $cliente['estado'] == 1 ? 'selected' : '' ?>>Activo</option>
                        <option value="0" <?= $cliente['estado'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Guardar Cambios</button>
            </form>
        </div>
    </div>
</body>
</html>
