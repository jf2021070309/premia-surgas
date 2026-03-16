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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
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

        /* ── Balance Widget ── */
        .balance-widget {
            text-align: center;
            padding: 1.2rem 0 2rem;
        }
        .balance-pill {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
        }
        /* Fila superior: etiqueta */
        .balance-top-row {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.4rem;
        }
        .balance-label {
            font-size: 1rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            font-weight: 600;
        }
        /* Fila del medio: estrella + número */
        .balance-mid-row {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .balance-star-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(145deg, #fcd34d, #f59e0b);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 0 0 0 rgba(252,211,77,0.55);
            animation: starPulse 2.6s ease-in-out infinite;
        }
        .balance-star-wrap i {
            font-size: 2.2rem;
            color: #fff;
            filter: drop-shadow(0 1px 3px rgba(0,0,0,0.25));
        }
        @keyframes starPulse {
            0%  { box-shadow: 0 0 0 0    rgba(252,211,77,0.55); }
            55% { box-shadow: 0 0 0 16px rgba(252,211,77,0);     }
            100%{ box-shadow: 0 0 0 0    rgba(252,211,77,0);     }
        }
        .balance-number {
            font-size: 5.5rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            letter-spacing: -4px;
            text-shadow: 0 2px 40px rgba(252,211,77,0.35);
        }
        .balance-unit {
            font-size: 1.3rem;
            font-weight: 400;
            color: rgba(255,255,255,0.5);
            align-self: flex-end;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        /* Divisor */
        .balance-divider {
            width: 60px;
            height: 1px;
            background: rgba(255,255,255,0.15);
            margin: 0.55rem auto;
        }
        /* Enlace historial */
        .balance-hist {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.68rem;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: color 0.25s;
        }
        .balance-hist i { font-size: 0.9rem; }
        .balance-hist:hover { color: #fcd34d; }

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
    <div class="panel-header">
        <div class="header-top-row" style="position: relative; align-items: center; min-height: 60px;">
            <div class="header-logo-side">
                <?php 
                $urlVolver = BASE_URL . 'panel';
                if (isset($_SESSION['id_cliente']) && isset($_SESSION['codigo_cliente'])) {
                    $urlVolver = BASE_URL . 'scan?c=' . $_SESSION['codigo_cliente'] . '&t=' . $_SESSION['token_cliente'];
                }
                ?>
                <a href="<?= $urlVolver ?>" style="text-decoration:none; display:flex; align-items:center; gap:8px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); padding: 6px 16px; border-radius:100px; color: white; transition:0.3s;" title="Volver">
                    <i class='bx bx-arrow-back' style="font-size: 1.4rem;"></i>
                    <span style="font-weight: 700; font-size: 0.85rem; letter-spacing: 0.5px;">VOLVER</span>
                </a>
            </div>

            <div class="header-user-side">
                <?php if (isset($_SESSION['rol']) && in_array(strtolower($_SESSION['rol']), ['admin', 'conductor'])): ?>
                <!-- Conductores y Admins ven su tarjeta -->
                <div class="user-card-integrated">
                    <div class="u-avatar"><?= substr($_SESSION['nombre_usuario'] ?? $_SESSION['usuario'] ?? 'U', 0, 1) ?></div>
                    <div class="u-details">
                        <span class="u-role-tag"><?= htmlspecialchars(strtoupper($_SESSION['rol'])) ?></span>
                        <span class="u-name-val"><?= htmlspecialchars($_SESSION['usuario'] ?? $_SESSION['nombre_usuario'] ?? 'Usuario') ?></span>
                    </div>
                    <div class="u-divider"></div>
                    <button onclick="confirmarLogoutStore()" class="u-logout-btn" title="Cerrar Sesión">
                        <i class='bx bx-log-out'></i>
                    </button>
                </div>
                <?php elseif (isset($_SESSION['id_cliente'])): ?>
                <!-- Clientes ven su tarjeta -->
                <div class="user-card-integrated">
                    <div class="u-avatar"><?= strtoupper(substr($cliente['nombre'] ?? $cliente['codigo'] ?? 'C', 0, 1)) ?></div>
                    <div class="u-details">
                        <span class="u-role-tag">CLIENTE</span>
                        <span class="u-name-val"><?= htmlspecialchars($cliente['nombre'] ?? $cliente['codigo'] ?? 'Cliente') ?></span>
                    </div>
                    <div class="u-divider"></div>
                    <a href="<?= BASE_URL ?>scan?c=<?= $_SESSION['codigo_cliente'] ?>&t=<?= $_SESSION['token_cliente'] ?>" class="u-logout-btn" title="Salir de la tienda">
                        <i class='bx bx-log-out'></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!isset($_SESSION['id_cliente'])): ?>
        <div class="header-hero-content" style="padding-bottom: 2.5rem;">
            <h1 class="hero-main-title">Tienda de Premios</h1>
            <p class="hero-welcome-msg">Canjea tus puntos por increíbles recompensas.</p>
        </div>
        <?php else: ?>
        <div class="balance-widget">
            <div class="balance-pill">
                <!-- Label superior -->
                <div class="balance-top-row">
                    <span class="balance-label">Tu saldo actual</span>
                </div>
                <!-- Número central -->
                <div class="balance-mid-row">
                    <div class="balance-star-wrap"><i class='bx bxs-star'></i></div>
                    <span class="balance-number"><?= number_format($cliente['puntos'] ?? 0) ?></span>
                    <span class="balance-unit">puntos</span>
                </div>
                <!-- Divisor + historial -->
                <div class="balance-divider"></div>
                <a href="<?= BASE_URL ?>tienda/historial" class="balance-hist">
                    <i class='bx bx-history'></i> Ver historial
                </a>
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
                                <img src="<?= BASE_URL ?>assets/premios/<?= $item['imagen'] ?>" alt="<?= htmlspecialchars($item['nombre']) ?>" class="prize-image">
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
                            <input type="range" class="form-range" v-model="pct" min="0" :max="maxSliderPct" step="1">
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
            maxSliderPct() {
                if (!this.selected.puntos) return 0;
                // El porcentaje máximo es el menor entre 90% y lo que el saldo del cliente permita
                const pctSaldo = (this.saldo / this.selected.puntos) * 100;
                return Math.min(90, Math.floor(pctSaldo));
            },
            puntosDcto() {
                if (!this.selected.puntos) return 0;
                // Calculamos puntos según porcentaje del slider
                let pts = Math.round(this.selected.puntos * (this.pct / 100));
                // Pero nunca más de lo que tiene el cliente
                return Math.min(pts, this.saldo);
            },
            montoEfectivo() {
                if (!this.selected.puntos) return 0;
                const puntosRestantes = this.selected.puntos - this.puntosDcto;
                return (puntosRestantes * this.montoPorPunto).toFixed(2);
            }
        },
        methods: {
            abrirCanje(prize) {
                this.selected = prize;
                this.tipo = this.saldo < prize.puntos ? 'descuento' : 'total';
                
                // Al abrir, ajustamos el pct al máximo permitido
                this.$nextTick(() => {
                    this.pct = this.maxSliderPct;
                });

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
    <?php if ($_SESSION['flash']['type'] === 'success'): ?>
        const duration = 3 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 10000 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);
            // since particles fall down, start a bit higher than random
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
        }, 250);

        Swal.fire({
            title: '<h3 style="color: #821515; font-weight: 800; margin-bottom: 0; font-size: 1.4rem;">¡EXCELENTE CANJE!</h3>',
            html: `
                <div style="padding: 0.5rem;">
                    <p style="font-size: 1.1rem; color: #333; margin-bottom: 1rem;">
                        <b>¡Felicidades!</b> Acabas de canjear un premio increíble.
                    </p>
                    <div style="background: #f8f9fa; border-radius: 12px; padding: 1rem; border: 1px dashed #821515; margin-bottom: 1rem;">
                        <p style="margin-bottom: 0.3rem; color: #666; font-size: 0.9rem;">Para reclamar tu producto:</p>
                        <h5 style="color: #821515; font-weight: 700; margin-bottom: 0.8rem;">📍 Planta principal</h5>
                        <p style="margin-bottom: 0.3rem; color: #666; font-size: 0.85rem;">O escríbenos:</p>
                        <a href="https://wa.me/51931187102" target="_blank" style="text-decoration: none; background: #25D366; color: white; padding: 0.6rem 1.2rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.06 3.973L0 16l4.204-1.102a7.934 7.934 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                            </svg>
                            931187102
                        </a>
                    </div>
                    <p style="font-size: 0.8rem; color: #888;">¡Gracias por ser parte de PremiaSurgas!</p>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: '¡ENTENDIDO!',
            confirmButtonColor: '#821515',
            width: '90%',
            maxWidth: '380px',
            padding: '1rem',
            background: '#fff',
            backdrop: `rgba(130, 21, 21, 0.6)`,
            showClass: {
                popup: 'animate__animated animate__zoomIn'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown'
            },
            customClass: {
                title: 'text-dark',
                popup: 'rounded-5 border-0 shadow-lg'
            }
        });
    <?php else: ?>
    Swal.fire({
        icon: '<?= $_SESSION['flash']['type'] ?>',
        title: '<?= $_SESSION['flash']['title'] ?>',
        text: '<?= $_SESSION['flash']['message'] ?>',
        timer: 3000,
        timerProgressBar: true
    });
    <?php endif; ?>
</script>
<?php unset($_SESSION['flash']); endif; ?>

<script>
    function confirmarLogoutStore() {
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: "Saldrás del sistema.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#821515',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= BASE_URL ?>logout';
            }
        });
    }
</script>
</body>
</html>
