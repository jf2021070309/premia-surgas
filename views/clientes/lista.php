<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                <?php if ($_SESSION['rol'] === 'admin'): ?>
                                &nbsp;
                                <a :href="'<?= BASE_URL ?>clientes/editar?id=' + c.id" title="Editar">✏️</a>
                                &nbsp;
                                <a v-if="c.estado == 1" href="#" @click.prevent="toggleEstado(c.id, 0)" title="Inactivar">🚫</a>
                                <a v-else href="#" @click.prevent="toggleEstado(c.id, 1)" title="Activar">✅</a>
                                <?php endif; ?>
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

function toggleEstadoCli(id, v) {
    const text = v ? '¿Activar este cliente?' : '¿Inactivar este cliente?';
    Swal.fire({
        title: 'Confirmar',
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'var(--primary)',
        confirmButtonText: 'Sí, proceder'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASE_URL ?>clientes/estado?id=' + id + '&v=' + v;
        }
    });
}
</script>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>

<script src="<?= BASE_URL ?>assets/js/clientes_lista.js"></script>
</body>
</html>
