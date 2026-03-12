<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Inválido — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; }
    </style>
</head>
<body>
    <div class="container" style="max-width:400px; text-align:center">
        <div class="card">
            <div style="font-size:3rem; margin-bottom:1rem">❌</div>
            <h2 style="color:var(--error); margin-bottom:.5rem">QR No Válido</h2>
            <p style="color:var(--muted)"><?= htmlspecialchars($mensaje) ?></p>
            <div style="margin-top:1.5rem">
                <a href="<?= BASE_URL ?>panel" class="btn btn-primary">Volver al panel</a>
            </div>
        </div>
    </div>
</body>
</html>
