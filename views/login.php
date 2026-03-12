<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — PremiaSurgas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-wrap { width: 100%; max-width: 420px; padding: 1.5rem; }
        .login-header { text-align: center; margin-bottom: 2rem; }
        .login-header .logo-icon { font-size: 3rem; }
        .login-header h1 { font-size: 1.7rem; color: var(--dark); }
        .login-header h1 span { color: var(--primary); }
        .login-header p { color: var(--muted); font-size: .9rem; margin-top: .3rem; }
        [v-cloak] { display: none; }
        @media (max-width: 600px) {
            body { align-items: flex-start; padding-top: 2rem; }
            .login-wrap  { padding: 1rem; }
            .login-header { margin-bottom: 1.5rem; }
            .login-header .logo-icon { font-size: 2.2rem; }
            .login-header h1 { font-size: 1.4rem; }
            /* Evita zoom auto de iOS en inputs */
            input { font-size: 16px !important; }
        }
    </style>
</head>
<body>
<div id="app" v-cloak>
    <div class="login-wrap">
        <div class="login-header">
            <div class="logo-icon">🔥</div>
            <h1>Premia<span>Surgas</span></h1>
            <p>Acceso para conductores y administradores</p>
        </div>

        <div class="card">
            <div v-if="error" class="alert alert-error">{{ error }}</div>

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

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/login.js"></script>
</body>
</html>
