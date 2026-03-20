<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — PremiaSurgas</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/premios/icono.png?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body { 
            display: flex; align-items: center; justify-content: center; 
            min-height: 100vh; margin: 0; font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at center, #52060a 0%, #1a0203 100%);
            color: #333; overflow-x: hidden;
        }
        .login-wrap { width: 100%; transition: max-width 0.5s cubic-bezier(0.4, 0, 0.2, 1); padding: 1.5rem; box-sizing: border-box; }
        .login-wrap.mode-login { max-width: 440px; }
        .login-wrap.mode-register { max-width: 680px; }
        
        .login-header { text-align: center; margin-bottom: 2rem; }
        .login-logo { width: 100%; max-width: 250px; height: auto; margin-bottom: 0.8rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.4)); animation: fadeInScale 1s ease; }
        .login-header p { color: rgba(255,255,255,0.45); font-size: 0.85rem; margin: 0; font-weight: 300; letter-spacing: 1px; text-transform: uppercase; }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        
        .card { 
            background: #fff; border-radius: 28px; padding: 2.5rem 2.8rem; 
            box-shadow: 0 40px 100px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1);
            position: relative; overflow: hidden;
            animation: fadeInCard 0.8s cubic-bezier(0.2, 1, 0.3, 1) 0.2s forwards;
            opacity: 0;
        }
        @keyframes fadeInCard { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .section-title { font-size: 1.6rem; font-weight: 700; text-align: center; margin-bottom: 2.2rem; color: #000; letter-spacing: 2px; text-transform: uppercase; }

        .form-label { display: block; font-weight: 600; font-size: 0.7rem; color: #aaa; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1.5px; }
        .input-group { position: relative; margin-bottom: 1.5rem; }
        .input-group i.main-icon { position: absolute; left: 0; top: 50%; transform: translateY(-50%); font-size: 1.1rem; color: #ddd; transition: all 0.3s; }
        .form-control {
            width: 100%; padding: 0.7rem 0 0.7rem 2.2rem !important; border: none !important;
            border-bottom: 1.5px solid #eee !important; background: transparent !important;
            border-radius: 0 !important; box-sizing: border-box; transition: all 0.4s ease;
            font-size: 1rem; color: #2d3748; font-family: inherit;
        }
        .form-control:focus { border-bottom-color: #000 !important; outline: none; }
        .input-group:focus-within i.main-icon { color: #000; }
        .password-toggle { 
            position: absolute; right: 0; top: 50%; transform: translateY(-50%); 
            font-size: 1.2rem !important; color: #ccc !important; cursor: pointer; 
            transition: color 0.3s; z-index: 10;
        }
        .password-toggle:hover { color: #000 !important; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; gap: 0; } }

        .btn-premium-submit {
            width: 100%; max-width: 300px; margin: 1.5rem auto 0; padding: 1.1rem; 
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
        
        .fade-slide-enter-active, .fade-slide-leave-active { transition: all 0.3s ease; }
        .fade-slide-enter-from { opacity: 0; transform: translateX(20px); }
        .fade-slide-leave-to { opacity: 0; transform: translateX(-20px); }

        .fade-header-enter-active, .fade-header-leave-active { transition: opacity 0.6s ease; }
        .fade-header-enter-from, .fade-header-leave-to { opacity: 0; }

        [v-cloak] { display: none; }
    </style>
</head>
<body>
<div id="app" v-cloak>
    <div class="login-wrap" :class="'mode-' + mode">
        <div class="login-header">
            <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="Logo PremiaSurgas" class="login-logo">
            <transition name="fade-header" mode="out-in">
                <p v-if="mode === 'login'" key="l">Bienvenido a la red de beneficios Surgas</p>
                <p v-else key="r">Crea tu cuenta y empieza a acumular puntos</p>
            </transition>
        </div>

        <div class="card">
            <transition name="fade-slide" mode="out-in">
                <!-- FORM LOGIN -->
                <div v-if="mode === 'login'" key="login">
                    <form @submit.prevent="handleLogin">
                        <div class="input-group">
                            <label class="form-label">Usuario / DNI</label>
                            <i class='bx bx-user main-icon'></i>
                            <input type="text" v-model="form.usuario" class="form-control" required placeholder="Ingresa tu usuario o DNI">
                        </div>
                        
                        <div class="input-group">
                            <label class="form-label">Contraseña</label>
                            <i class='bx bx-lock-alt main-icon'></i>
                            <input :type="showPassword ? 'text' : 'password'" v-model="form.password" class="form-control" required placeholder="••••••••">
                            <i :class="['bx', showPassword ? 'bx-hide' : 'bx-show', 'password-toggle']" @click="showPassword = !showPassword"></i>
                        </div>

                        <button type="submit" class="btn-premium-submit" :disabled="loading">
                            <span v-if="loading">Verificando...</span>
                            <span v-else>Iniciar sesión</span>
                        </button>

                        <div style="text-align: center; margin-top: 2rem;">
                            <p style="color: #666; font-size: 0.9rem; font-weight: 300;">
                                ¿Eres cliente nuevo? 
                                <a href="javascript:void(0)" @click="mode = 'register'" style="color: #000; font-weight: 700; text-decoration: none; margin-left: 5px;">Regístrate aquí</a>
                            </p>
                        </div>
                    </form>
                </div>

                <!-- FORM REGISTRO -->
                <div v-else key="register">
                    <form @submit.prevent="handleRegistro">
                        <div class="form-row">
                            <div class="input-group">
                                <label class="form-label">DNI</label>
                                <i class='bx bx-id-card main-icon'></i>
                                <input type="text" v-model="regForm.dni" class="form-control" maxlength="8" required placeholder="8 dígitos" @input="onDniInput">
                            </div>
                            <div class="input-group">
                                <label class="form-label">Celular</label>
                                <i class='bx bx-phone main-icon'></i>
                                <input type="text" v-model="regForm.celular" class="form-control" maxlength="9" required placeholder="9 dígitos">
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="form-label">
                                Nombre Completo 
                                <span v-if="buscandoDni" style="color:#000;font-size:0.65rem;margin-left:10px;"><i class='bx bx-loader-alt bx-spin'></i></span>
                            </label>
                            <i class='bx bx-user main-icon'></i>
                            <input type="text" v-model="regForm.nombre" class="form-control" required placeholder="Se llenará solo">
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label class="form-label">Departamento</label>
                                <i class='bx bx-map main-icon'></i>
                                <select v-model="regForm.departamento" class="form-control" required>
                                    <option value="Tacna">Tacna</option>
                                    <option value="Ilo">Ilo</option>
                                    <option value="Camaná">Camaná</option>
                                    <option value="Mollendo">Mollendo</option>
                                    <option value="Moquegua">Moquegua</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label class="form-label">Contraseña</label>
                                <i class='bx bx-lock-alt main-icon'></i>
                                <input :type="showPasswordReg ? 'text' : 'password'" v-model="regForm.password" class="form-control" required placeholder="••••••••">
                                <i :class="['bx', showPasswordReg ? 'bx-hide' : 'bx-show', 'password-toggle']" @click="showPasswordReg = !showPasswordReg"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn-premium-submit" :disabled="loading">
                            <span v-if="loading">Registrando...</span>
                            <span v-else>Registrarse</span>
                        </button>

                        <div style="text-align: center; margin-top: 2rem;">
                            <p style="color: #666; font-size: 0.9rem; font-weight: 300;">
                                ¿Ya tienes cuenta? 
                                <a href="javascript:void(0)" @click="mode = 'login'" style="color: #000; font-weight: 700; text-decoration: none; margin-left: 5px;">Inicia sesión</a>
                            </p>
                        </div>
                    </form>
                </div>
            </transition>
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
                mode: 'login',
                form: { usuario: '', password: '' },
                regForm: { dni: '', nombre: '', celular: '', departamento: 'Tacna', password: '' },
                showPassword: false,
                showPasswordReg: false,
                loading: false,
                buscandoDni: false
            };
        },
        methods: {
            async handleLogin() {
                this.loading = true;
                try {
                    const res = await axios.post('login', this.form);
                    if (res.data.success) {
                        Swal.fire({ icon: 'success', title: '¡Bienvenido!', timer: 1500, showConfirmButton: false })
                            .then(() => window.location.href = res.data.redirect || 'panel');
                    } else {
                        Swal.fire({ icon: 'error', title: 'Acceso Denegado', text: res.data.message });
                    }
                } catch (e) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Conexión fallida.' });
                } finally { this.loading = false; }
            },
            onDniInput() {
                if(this.regForm.dni.length === 8) this.buscarDni();
            },
            async buscarDni() {
                this.buscandoDni = true;
                this.regForm.nombre = '';
                try {
                    const res = await axios.get('clientes/consultarDni?dni=' + this.regForm.dni);
                    if(res.data.success) this.regForm.nombre = res.data.data.nombre_completo;
                } catch(e) {} finally { this.buscandoDni = false; }
            },
            async handleRegistro() {
                this.loading = true;
                try {
                    const res = await axios.post('clientes/register', this.regForm);
                    if (res.data.success) {
                        Swal.fire({ icon: 'success', title: '¡Registro Exitoso!', text: res.data.message })
                            .then(() => { 
                                this.mode = 'login'; 
                                this.form.usuario = this.regForm.dni; 
                            });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.data.message });
                    }
                } catch (e) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error en el proceso.' });
                } finally { this.loading = false; }
            }
        }
    }).mount('#app');
</script>
</body>
</html>
