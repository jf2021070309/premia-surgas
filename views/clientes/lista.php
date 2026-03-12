<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>[v-cloak]{display:none}</style>
</head>
<body>
<div id="app" v-cloak>
    <div class="topbar">
        <a href="<?= BASE_URL ?>panel" style="color:#fff; font-size:1.3rem; margin-right:.8rem">←</a>
        <span class="topbar-logo">📋 Lista de Clientes</span>
        <div class="topbar-actions">
            <a href="<?= BASE_URL ?>clientes/nuevo" class="btn btn-primary" style="padding:.4rem 1rem;font-size:.85rem">+ Nuevo</a>
        </div>
    </div>

    <div class="container">
        <div class="card" style="margin-top:1.5rem; padding:1rem">
            <input type="text" v-model="busqueda" placeholder="🔍 Buscar por nombre, celular o código..."
                   style="width:100%; padding:.7rem 1rem; border:1.5px solid #dde0e6; border-radius:8px; font-family:inherit; font-size:.95rem; margin-bottom:1rem; box-sizing:border-box">

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Celular</th>
                            <th>Distrito</th>
                            <th>Puntos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in filtrados" :key="c.id">
                            <td><span class="badge-code" style="font-size:.75rem; padding:.2rem .7rem">{{ c.codigo }}</span></td>
                            <td>{{ c.nombre }}</td>
                            <td>{{ c.celular }}</td>
                            <td>{{ c.distrito || '—' }}</td>
                            <td><strong style="color:var(--primary)">{{ c.puntos }}</strong></td>
                            <td>
                                <a :href="'<?= BASE_URL ?>clientes/exito?id=' + c.id" title="Ver QR">🎫</a>
                                &nbsp;
                                <a :href="'<?= BASE_URL ?>clientes/imprimir?id=' + c.id" target="_blank" title="Imprimir">🖨</a>
                            </td>
                        </tr>
                        <tr v-if="filtrados.length === 0">
                            <td colspan="6" style="text-align:center; color:var(--muted); padding:2rem">Sin resultados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script>
var CLIENTES = <?= json_encode($clientes) ?>;
</script>
<script src="<?= BASE_URL ?>assets/js/clientes_lista.js"></script>
</body>
</html>
