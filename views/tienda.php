<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Premios — PremiaSurgas</title>
    <!-- Bootstrap 5 for the modal and layout -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --low: #2ecc71;
            --medium: #f1c40f;
            --high: #3498db;
            --vip: #e74c3c;
            --glass: rgba(255, 255, 255, 0.9);
        }
        
        body { font-family: 'Outfit', sans-serif; background: #f8f9fa; }
        [v-cloak] { display: none; }
        .container { max-width: 1400px !important; margin: 0 auto; padding: 1.5rem; }

        .store-header {
            padding: 3rem 1rem; text-align: center;
            background: linear-gradient(135deg, #4a0c0c, #821515);
            color: white; border-radius: 0 0 3rem 3rem;
            margin-bottom: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            position: relative;
        }

        .user-balance-pill {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            margin-top: 1rem;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .level-section { margin-bottom: 4rem; }
        .level-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid #eee; padding-bottom: 0.5rem; }
        .level-badge { width: 15px; height: 15px; border-radius: 50%; }
        .level-title { font-size: 1.8rem; font-weight: 700; color: #333; }

        .prizes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; }
        
        .prize-card {
            background: white; border-radius: 2rem; overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(0,0,0,0.03);
            display: flex; flex-direction: column;
        }
        .prize-card:hover { transform: translateY(-15px); box-shadow: 0 25px 50px rgba(0,0,0,0.1); }
        
        .prize-image-container { height: 200px; display: flex; align-items: center; justify-content: center; padding: 1.5rem; background: #fff; }
        .prize-image { width: 100%; height: 100%; object-fit: contain; }
        
        .prize-info { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; }
        .prize-name { font-size: 1.1rem; font-weight: 700; color: #2d3436; margin-bottom: 0.5rem; }
        .prize-points { font-size: 1.4rem; font-weight: 800; color: #e67e22; }
        .prize-points span { font-size: 0.7rem; text-transform: uppercase; color: #999; }

        .btn-redeem {
            margin-top: auto; width: 100%; padding: 0.8rem; border: none;
            border-radius: 1rem; background: #2d3436; color: white;
            font-weight: 700; transition: all 0.3s;
        }
        .btn-redeem:hover:not(:disabled) { background: #000; transform: scale(1.02); }

        /* Level Colors */
        .level-low .level-badge { background: var(--low); }
        .level-low .prize-card { border-top: 5px solid var(--low); }
        .level-medium .level-badge { background: var(--medium); }
        .level-medium .prize-card { border-top: 5px solid var(--medium); }
        .level-high .level-badge { background: var(--high); }
        .level-high .prize-card { border-top: 5px solid var(--high); }
        .level-vip .level-badge { background: var(--vip); }
        .level-vip .prize-card { border-top: 5px solid var(--vip); }

        .back-nav { position: absolute; top: 20px; left: 20px; }
        .btn-back { background: rgba(255,255,255,0.1); color: white; padding: 0.5rem 1.2rem; border-radius: 50px; text-decoration: none; border: 1px solid rgba(255,255,255,0.2); }

        /* Modal Styles */
        .modal-content { border-radius: 2rem; border: none; overflow: hidden; }
        .modal-header { background: #f8f9fa; border: none; padding: 2rem 2rem 1rem; }
        .option-card {
            border: 2px solid #eee; border-radius: 1.2rem; padding: 1.2rem; cursor: pointer;
            transition: all 0.3s; margin-bottom: 1rem; position: relative;
        }
        .option-card.active { border-color: var(--primary); background: rgba(130, 21, 21, 0.05); }
        .option-card:hover:not(.disabled) { border-color: #ddd; }
        .option-card.disabled { opacity: 0.5; cursor: not-allowed; }
        .option-check { position: absolute; top: 1rem; right: 1rem; color: var(--primary); font-size: 1.2rem; display: none; }
        .option-card.active .option-check { display: block; }
    </style>
</head>
<body>
<div id="app" v-cloak>
    <div class="store-header">
        <div class="back-nav">
            <?php 
            $urlVolver = BASE_URL . 'panel';
            if (isset($_SESSION['id_cliente']) && isset($_SESSION['codigo_cliente'])) {
                $urlVolver = BASE_URL . 'scan?c=' . $_SESSION['codigo_cliente'] . '&t=' . $_SESSION['token_cliente'];
            }
            ?>
            <a href="<?= $urlVolver ?>" class="btn-back">← Volver</a>
            <a href="<?= BASE_URL ?>tienda/historial" class="btn-back ms-2">🕒 Historial</a>
        </div>
        <h1 class="display-5 fw-bold">Tienda de Premios</h1>
        <p class="lead opacity-75">¡Gracias por tu preferencia! Elige tu recompensa.</p>
        
        <?php if (isset($_SESSION['id_cliente']) && !isset($_SESSION['rol'])): ?>
        <div class="user-balance-pill shadow-sm">
            <span style="font-size: 1.5rem;">⭐</span>
            <div class="text-start">
                <div class="small opacity-75 fw-600">TU SALDO</div>
                <div class="h5 mb-0 fw-800"><?= number_format($cliente['puntos'] ?? 0) ?> Puntos</div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="container mt-4">
        <?php foreach ($premios as $key => $nivel): ?>
            <?php if (!empty($nivel['items'])): ?>
            <section class="level-section <?= $nivel['clase'] ?>">
                <div class="level-header">
                    <span class="level-badge shadow-sm"></span>
                    <h2 class="level-title"><?= $nivel['titulo'] ?></h2>
                    <span class="ms-auto text-muted small fw-600"><i><?= $nivel['puntos'] ?></i></span>
                </div>

                <div class="prizes-grid">
                    <?php foreach ($nivel['items'] as $item): ?>
                        <div class="prize-card shadow-sm h-100">
                            <div class="prize-image-container">
                                <img src="<?= BASE_URL ?>assets/premios/<?= $item['imagen'] ?>" alt="<?= $item['nombre'] ?>" class="prize-image">
                            </div>
                            <div class="prize-info">
                                <h3 class="prize-name"><?= $item['nombre'] ?></h3>
                                <p class="text-muted small mb-3"><?= htmlspecialchars($item['descripcion']) ?></p>
                                <div class="prize-points mb-2">
                                    <?= number_format($item['puntos']) ?> <span>puntos</span>
                                </div>
                                <div class="small fw-600 mb-3 <?= $item['stock'] > 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= $item['stock'] > 0 ? "Stock: {$item['stock']} unidades" : "Agotado" ?>
                                </div>
                                <button class="btn-redeem" 
                                        @click="abrirCanje(<?= htmlspecialchars(json_encode($item)) ?>)"
                                        <?= $item['stock'] <= 0 ? 'disabled' : '' ?>>
                                    Canjear Premio
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Modal canje -->
    <div class="modal fade" id="modalCanje" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Opciones de Canje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <img :src="'<?= BASE_URL ?>assets/premios/' + selected.imagen" class="mb-3" style="height: 100px; object-fit: contain;">
                        <h4 class="fw-bold mb-1">{{ selected.nombre }}</h4>
                        <p class="text-muted small">{{ selected.puntos }} Puntos requeridos</p>
                    </div>

                    <div :class="['option-card', tipo === 'total' ? 'active' : '', saldoInsuficiente ? 'disabled' : '']"
                         @click="!saldoInsuficiente && (tipo = 'total')">
                        <span class="option-check">✓</span>
                        <div class="fw-bold h6 mb-1">Canje Total</div>
                        <p class="small text-muted mb-0">Usa todos tus puntos para obtener el premio gratis.</p>
                        <div class="mt-2 fw-bold text-success" v-if="!saldoInsuficiente">
                            Costo: {{ selected.puntos }} Puntos
                        </div>
                        <div class="mt-2 fw-bold text-danger" v-else>
                            Puntos insuficientes
                        </div>
                    </div>

                    <div :class="['option-card', tipo === 'descuento' ? 'active' : '']"
                         @click="tipo = 'descuento'">
                        <span class="option-check">✓</span>
                        <div class="fw-bold h6 mb-1">Puntos + Efectivo</div>
                        <p class="small text-muted mb-2">Paga una parte con puntos y el resto en efectivo.</p>
                        
                        <div class="bg-light p-2 rounded small">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Puntos ({{ pct }}%)</span>
                                <b>{{ puntosDcto }} pts</b>
                            </div>
                            <input type="range" class="form-range" v-model="pct" min="10" max="90" step="10">
                            <div class="d-flex justify-content-between mt-2">
                                <span>Pagarás:</span>
                                <b class="text-danger">S/ {{ montoEfectivo }}</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <form action="<?= BASE_URL ?>tienda/canjear" method="POST" style="width: 100%;">
                        <input type="hidden" name="premio_id" :value="selected.id">
                        <input type="hidden" name="tipo" :value="tipo">
                        <input type="hidden" name="puntos" :value="tipo === 'total' ? selected.puntos : puntosDcto">
                        <input type="hidden" name="monto" :value="tipo === 'total' ? 0 : montoEfectivo">
                        
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 shadow-sm" style="font-weight: 700;">
                            Confirmar Canje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script>
    new Vue({
        el: '#app',
        data: {
            saldo: <?= (int) ($cliente['puntos'] ?? 0) ?>,
            montoPorPunto: <?= (float) ($montoPorPunto ?? 0.05) ?>,
            selected: {},
            tipo: 'total',
            pct: 50,
            modal: null
        },
        computed: {
            saldoInsuficiente() {
                return this.saldo < this.selected.puntos;
            },
            puntosDcto() {
                return Math.round(this.selected.puntos * (this.pct / 100));
            },
            montoEfectivo() {
                // Cálculo dinámico según parámetro del sistema
                const puntosRestantes = this.selected.puntos - this.puntosDcto;
                return (puntosRestantes * this.montoPorPunto).toFixed(2);
            }
        },
        methods: {
            abrirCanje(prize) {
                this.selected = prize;
                this.tipo = this.saldo < prize.puntos ? 'descuento' : 'total';
                this.modal = new bootstrap.Modal(document.getElementById('modalCanje'));
                this.modal.show();
            }
        },
        mounted() {
            if (this.saldo < this.selected.puntos) this.tipo = 'descuento';
        }
    });
</script>

<?php if (isset($_SESSION['flash'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        timer: 3000,
        timerProgressBar: true
    });
</script>
<?php unset($_SESSION['flash']); endif; ?>
</body>
</html>
