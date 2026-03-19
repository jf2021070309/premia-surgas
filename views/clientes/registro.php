<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body { 
            display: flex; align-items: center; justify-content: center; 
            min-height: 100vh; margin: 0; font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at center, #52060a 0%, #1a0203 100%);
            color: #333;
        }
        .login-wrap { width: 100%; max-width: 640px; padding: 1.5rem; box-sizing: border-box; }
        .login-header { text-align: center; margin-bottom: 1.5rem; }
        .login-logo { width: 100%; max-width: 250px; height: auto; margin-bottom: 0.5rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.4)); }
        
        .card { 
            background: #fff; border-radius: 28px; padding: 2.2rem 2.5rem; 
            box-shadow: 0 40px 100px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1);
            animation: fadeInScale 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards;
        }
        
        @keyframes fadeInScale {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .section-title { 
            font-size: 1.8rem; 
            font-weight: 700; 
            text-align: center; 
            margin-bottom: 3rem; 
            color: #000; 
            letter-spacing: 2px; 
            text-transform: uppercase;
        }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1rem; }
        
        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; gap: 0; }
        }

        .form-label { display: block; font-weight: 600; font-size: 0.7rem; color: #aaa; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1.5px; }
        .input-group { position: relative; margin-bottom: 1.5rem; }
        .input-group i { position: absolute; left: 0; top: 50%; transform: translateY(-50%); font-size: 1.1rem; color: #ddd; transition: all 0.3s; }
        
        .form-control {
            width: 100%; padding: 0.7rem 0 0.7rem 2.2rem !important; border: none !important;
            border-bottom: 1.5px solid #eee !important; background: transparent !important;
            border-radius: 0 !important; box-sizing: border-box; transition: all 0.4s ease;
            font-size: 1rem; color: #2d3748; font-family: inherit;
        }
        .form-control:focus { border-bottom-color: #000 !important; outline: none; }
        .input-group:focus-within i { color: #000; }

        .btn-premium-submit {
            width: 100%; max-width: 320px; margin: 1.5rem auto 0; padding: 1.1rem; 
            background: #000; border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px; color: white; font-weight: 500; font-size: 1.05rem;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4); transition: all 0.4s ease;
            position: relative; overflow: hidden;
        }
        .btn-premium-submit::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.12) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-25deg); animation: shine 3s infinite ease-in-out;
        }
        @keyframes shine { 0% { left: -120%; } 20% { left: 180%; } 100% { left: 180%; } }
        
        .btn-premium-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5); }
        .btn-premium-submit:disabled { background: #666; cursor: not-allowed; }

        select.form-control { appearance: none; cursor: pointer; }
        
        [v-cloak] { display: none; }
    </style>
</head>
<body>
<div id="app" v-cloak>
    <div class="login-wrap">
        <div class="card">
            <h2 class="section-title">Crear Cuenta</h2>
            <form @submit.prevent="handleRegistro">
                
                <div class="form-row">
                    <div class="input-group">
                        <label class="form-label">DNI</label>
                        <i class='bx bx-id-card'></i>
                        <input type="text" v-model="form.dni" class="form-control" maxlength="8" required placeholder="8 dígitos" @input="onDniInput">
                    </div>
                    <div class="input-group">
                        <label class="form-label">Celular</label>
                        <i class='bx bx-phone'></i>
                        <input type="text" v-model="form.celular" class="form-control" maxlength="9" required placeholder="9 dígitos">
                    </div>
                </div>

                <div class="input-group">
                    <label class="form-label">
                        Nombre Completo 
                        <span v-if="buscandoDni" style="color: #000; font-size: 0.65rem; margin-left: 10px; text-transform: none;">
                            <i class='bx bx-loader-alt bx-spin'></i> Buscando...
                        </span>
                    </label>
                    <i class='bx bx-user'></i>
                    <input type="text" v-model="form.nombre" class="form-control" required placeholder="Se llenará solo al poner el DNI">
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label class="form-label">Departamento</label>
                        <i class='bx bx-map'></i>
                        <select v-model="form.departamento" class="form-control" required>
                            <option value="Tacna">Tacna</option>
                            <option value="Ilo">Ilo</option>
                            <option value="Camaná">Camaná</option>
                            <option value="Mollendo">Mollendo</option>
                            <option value="Moquegua">Moquegua</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="form-label">Nueva Contraseña</label>
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" v-model="form.password" class="form-control" required placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn-premium-submit" :disabled="loading">
                    <span v-if="loading">Registrando...</span>
                    <span v-else>Registrarse</span>
                </button>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <p style="color: #999; font-size: 0.9rem;">
                        ¿Ya tienes cuenta? 
                        <a href="<?= BASE_URL ?>login" style="color: #000; font-weight: 700; text-decoration: none; margin-left: 5px;">Inicia sesión</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                form: {
                    dni: '',
                    nombre: '',
                    celular: '',
                    departamento: 'Tacna',
                    password: ''
                },
                loading: false,
                buscandoDni: false
            }
        },
        methods: {
            onDniInput() {
                if(this.form.dni.length === 8) {
                    this.buscarDni();
                }
            },
            async buscarDni() {
                this.buscandoDni = true;
                this.form.nombre = ''; 
                try {
                    const res = await axios.get('<?= BASE_URL ?>clientes/consultarDni?dni=' + this.form.dni);
                    if(res.data.success) {
                        this.form.nombre = res.data.data.nombre_completo;
                    }
                } catch(e) {
                } finally {
                    this.buscandoDni = false;
                }
            },
            async handleRegistro() {
                this.loading = true;
                try {
                    const res = await axios.post('<?= BASE_URL ?>clientes/register', this.form);
                    if (res.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bienvenido!',
                            text: res.data.message,
                            confirmButtonText: 'Ir al Login',
                            confirmButtonColor: '#000'
                        }).then(() => {
                            window.location.href = '<?= BASE_URL ?>login';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.data.message,
                            confirmButtonColor: '#000'
                        });
                    }
                } catch (e) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo procesar el registro.' });
                } finally {
                    this.loading = false;
                }
            }
        }
    }).mount('#app');
</script>
</body>
</html>
