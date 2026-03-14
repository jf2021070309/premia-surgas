<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="panel-header">
        <div class="header-top-row">
            <div class="header-logo-side">
                <a href="<?= BASE_URL ?>clientes/lista" style="color:#fff; font-size:1.6rem; margin-right:1.2rem; display:flex; align-items:center; transition:0.3s;" title="Volver a la Lista">
                    <i class='bx bx-left-arrow-alt'></i>
                </a>
                <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="PremiaSurgas" class="header-main-logo">
            </div>
        </div>
        <div class="header-hero-content">
            <h1 class="hero-main-title">Editar Cliente</h1>
            <p class="hero-welcome-msg">Modifica el perfil del cliente y sus preferencias.</p>
        </div>
    </div>

    <div class="container" style="max-width: 500px">
        <div class="card">
            <form action="<?= BASE_URL ?>clientes/update" method="POST" style="padding: 1.5rem;">
                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required
                           pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" 
                           title="Solo se permiten letras y espacios"
                           oninput="this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '')">
                </div>

                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" name="celular" value="<?= htmlspecialchars($cliente['celular']) ?>" required
                           pattern="\d{9}" 
                           maxlength="9"
                           title="Debe tener exactamente 9 dígitos"
                           oninput="this.value = this.value.replace(/\D/g, '').slice(0, 9)">
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>">
                </div>

                <div class="form-group">
                    <label>Distrito</label>
                    <select name="distrito" class="form-control">
                        <option value="">-- Seleccionar --</option>
                        <?php 
                        $distritos = [
                            "Tacna (capital)", "Alto de la Alianza", "Calana", "Ciudad Nueva", 
                            "Coronel Gregorio Albarracín Lanchipa", "Inclán", "La Yarada-Los Palos", 
                            "Pachía", "Palca", "Pocollay", "Sama"
                        ];
                        foreach ($distritos as $d): ?>
                            <option value="<?= $d ?>" <?= $cliente['distrito'] == $d ? 'selected' : '' ?>><?= $d ?></option>
                        <?php endforeach; ?>
                    </select>
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
