<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($cliente['nombre']) ?> — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>[v-cloak]{display:none}</style>
</head>
<body>
<div id="app" v-cloak>
    <div class="topbar">
        <span class="topbar-logo">📷 Perfil del Cliente</span>
        <?php if (isset($_SESSION['id_usuario'])): ?>
        <div class="topbar-actions">
            <a href="<?= BASE_URL ?>panel"><button class="btn-logout" style="background:var(--dark2)">Panel</button></a>
        </div>
        <?php endif; ?>
    </div>

    <div class="container" style="max-width:560px">
        <div class="card" style="margin-top:1.5rem">
            <!-- Perfil -->
            <div class="profile-header">
                <div class="profile-avatar"><?= strtoupper(substr($cliente['nombre'], 0, 1)) ?></div>
                <div class="profile-info">
                    <h2><?= htmlspecialchars($cliente['nombre']) ?></h2>
                    <p><?= htmlspecialchars($cliente['celular']) ?> &nbsp;·&nbsp; <?= htmlspecialchars($cliente['distrito'] ?: '—') ?></p>
                </div>
            </div>

            <div class="badge-code" style="margin-bottom:1.2rem"><?= htmlspecialchars($cliente['codigo']) ?></div>

            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-box">
                    <strong><?= $cliente['puntos'] ?></strong>
                    <span>Puntos</span>
                </div>
                <div class="stat-box">
                    <strong><?= count($ventas) ?></strong>
                    <span>Compras</span>
                </div>
                <div class="stat-box">
                    <strong>S/ <?= number_format(array_sum(array_column($ventas, 'monto')), 2) ?></strong>
                    <span>Total gastado</span>
                </div>
            </div>

            <!-- Registrar venta (solo conductores logueados) -->
            <?php if (isset($_SESSION['id_usuario'])): ?>
            <div style="border-top:1px solid #eee; padding-top:1.2rem; margin-top:1rem">
                <div class="card-title" style="margin-bottom:1rem">Registrar Compra</div>
                <div v-if="ventaOk" class="alert alert-success">
                    ✅ Venta registrada — Se sumaron {{ puntosGanados }} punto(s)
                </div>
                <div v-if="ventaError" class="alert alert-error">{{ ventaError }}</div>
                <form @submit.prevent="registrarVenta" v-if="!ventaOk">
                    <div class="form-group">
                        <label>Monto de la compra (S/)</label>
                        <input type="number" v-model.number="monto" min="1" step="0.50"
                               required placeholder="Ej. 45.00">
                    </div>
                    <button type="submit" class="btn btn-primary btn-full" :disabled="loading">
                        {{ loading ? 'Registrando...' : 'Registrar Venta y Sumar Puntos' }}
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>

        <!-- Historial de ventas -->
        <div class="card" style="margin-top:1.2rem; padding:1rem" v-if="ventas.length">
            <div class="card-title">Historial de Compras</div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Fecha</th><th>Monto</th><th>Puntos</th><th>Conductor</th></tr></thead>
                    <tbody>
                        <tr v-for="v in ventas" :key="v.id">
                            <td>{{ formatDate(v.fecha) }}</td>
                            <td>S/ {{ parseFloat(v.monto).toFixed(2) }}</td>
                            <td><strong style="color:var(--primary)">+{{ v.puntos }}</strong></td>
                            <td>{{ v.conductor }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
var CLIENTE_ID = <?= (int)$cliente['id'] ?>;
var VENTAS     = <?= json_encode($ventas) ?>;
var BASE_URL   = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/scan_perfil.js"></script>
</body>
</html>
