<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>[v-cloak]{display:none}</style>
</head>
<body>
<div id="app" v-cloak>
    <div class="topbar">
        <a href="<?= BASE_URL ?>panel" style="color:#fff; font-size:1.3rem; margin-right:.8rem">←</a>
        <span class="topbar-logo">📝 Registrar Cliente</span>
    </div>

    <div class="container" style="max-width:560px">

        <!-- Éxito después de guardar -->
        <div v-if="clienteGuardado" class="card" style="text-align:center; margin-top:2rem">
            <div style="font-size:3rem; margin-bottom:1rem">✅</div>
            <div class="alert alert-success" v-if="esExistente">{{ mensaje }}</div>
            <h2 style="color:var(--dark); margin-bottom:.5rem">{{ form.nombre }}</h2>
            <div class="badge-code">{{ codigoGenerado }}</div>
            <p style="color:var(--muted); font-size:.88rem; margin:.8rem 0">Cliente registrado correctamente</p>
            <div class="action-row">
                <a :href="'<?= BASE_URL ?>clientes/exito?id=' + clienteId" class="btn btn-primary">Ver QR</a>
                <a :href="'<?= BASE_URL ?>clientes/imprimir?id=' + clienteId" target="_blank" class="btn btn-dark">Imprimir</a>
                <a href="<?= BASE_URL ?>clientes/nuevo" class="btn btn-outline">Nuevo</a>
            </div>
        </div>

        <!-- Formulario -->
        <div v-else class="card" style="margin-top:2rem">
            <div class="card-title">Datos del Cliente</div>

            <div v-if="error" class="alert alert-error">{{ error }}</div>

            <form @submit.prevent="guardar">
                <div class="form-group">
                    <label>Nombre Completo *</label>
                    <input type="text" v-model="form.nombre" required placeholder="Ej. Juan Pérez">
                </div>
                <div class="form-group">
                    <label>Celular *</label>
                    <input type="tel" v-model="form.celular" required placeholder="987 654 321">
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" v-model="form.direccion" placeholder="Av. Principal 123">
                </div>
                <div class="form-group">
                    <label>Distrito</label>
                    <input type="text" v-model="form.distrito" placeholder="Ej. Los Olivos">
                </div>
                <button type="submit" class="btn btn-primary btn-full" :disabled="loading">
                    {{ loading ? 'Guardando...' : 'Guardar Cliente' }}
                </button>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    var BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/clientes_nuevo.js"></script>
</body>
</html>
