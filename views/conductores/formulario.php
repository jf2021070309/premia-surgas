<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
</head>
<body>
    <div class="topbar">
        <a href="<?= BASE_URL ?>conductores" style="color:#fff; font-size:1.3rem; margin-right:.8rem">←</a>
        <span class="topbar-logo">🚚 <?= $titulo ?></span>
    </div>

    <div class="container" style="max-width: 500px; margin-top: 2rem;">
        <div class="card">
            <form action="<?= BASE_URL ?>conductores/<?= $conductor ? 'update' : 'create' ?>" method="POST" style="padding: 1.5rem;">
                <?php if($conductor): ?>
                    <input type="hidden" name="id" value="<?= $conductor['id'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($conductor['nombre'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Usuario (Login)</label>
                    <input type="text" name="usuario" value="<?= htmlspecialchars($conductor['usuario'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Contraseña <?= $conductor ? '(dejar en blanco para no cambiar)' : '' ?></label>
                    <input type="password" name="password" <?= $conductor ? '' : 'required' ?>>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="1" <?= ($conductor['estado'] ?? 1) == 1 ? 'selected' : '' ?>>Activo</option>
                        <option value="0" <?= ($conductor['estado'] ?? 1) == 0 ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                    <?= $conductor ? 'Actualizar Conductor' : 'Crear Conductor' ?>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
