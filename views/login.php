<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>
        body { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            background-color: #520006;
            margin: 0;
        }
        .login-wrap { width: 100%; max-width: 440px; padding: 1.5rem; box-sizing: border-box; }
        .login-header { text-align: center; margin-bottom: 2.5rem; }
        .login-logo { width: 100%; max-width: 320px; height: auto; margin-bottom: 0.5rem; }
        .login-header p { color: rgba(255,255,255,0.8); font-size: .95rem; margin-top: .3rem; }
        [v-cloak] { display: none; }
        @media (max-width: 600px) {
            .login-wrap  { padding: 1.2rem; }
            .login-header { margin-bottom: 2rem; }
            .login-logo { max-width: 280px; }
            /* Evita zoom auto de iOS en inputs */
            input { font-size: 16px !important; }
        }
    </style>
</head>
<body>
<div id="app" v-cloak>
    <div class="login-wrap">
        <div class="login-header">
            <img src="<?= BASE_URL ?>assets/premios/PREMIASURGASLOGO.png" alt="Logo PremiaSurgas" class="login-logo">
            <p>Acceso para conductores y administradores</p>
        </div>

        <div class="card">

            <form @submit.prevent="handleLogin">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input id="usuario" type="text" v-model="form.usuario"
                           required autocomplete="username" placeholder="Ej. conductor1">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" v-model="form.password"
                           required autocomplete="current-password" placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary btn-full" :disabled="loading">
                    <span v-if="loading">Verificando...</span>
                    <span v-else>Iniciar Sesión</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/login.js"></script>
</body>
</html>
