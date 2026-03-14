<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — PremiaSurgas</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            background-color: #520006;
            margin: 0;
            font-family: 'Lexend', sans-serif;
        }
        .login-wrap { width: 100%; max-width: 410px; padding: 1.5rem; box-sizing: border-box; }
        .login-header { text-align: center; margin-bottom: 2rem; }
        .login-logo { width: 100%; max-width: 300px; height: auto; margin-bottom: 0.5rem; }
        .login-header p { color: rgba(255,255,255,0.7); font-size: 0.9rem; margin-top: 0.2rem; font-weight: 300; }
        
        .card { 
            background: white; 
            border-radius: 1rem; 
            padding: 2.2rem; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            border: 1px solid #eee;
        }
        
        .form-label { display: block; font-weight: 500; font-size: 0.9rem; color: #555; margin-bottom: 0.6rem; }
        
        .input-group { position: relative; margin-bottom: 1.5rem; }
        .input-group i { 
            position: absolute; 
            left: 1.1rem; 
            top: 50%; 
            transform: translateY(-50%); 
            font-size: 1.2rem; 
            color: #aaa;
            transition: color 0.3s;
        }
        .form-control {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 3rem !important;
            border: 1.5px solid #eaeaea !important;
            background: #fafafa !important;
            border-radius: 0.75rem !important;
            box-sizing: border-box;
            transition: all 0.3s;
            font-size: 0.95rem;
            color: #333;
            font-family: inherit;
        }
        .form-control:focus {
            background: white !important;
            border-color: #821515 !important;
            outline: none;
            box-shadow: 0 0 0 4px rgba(130, 21, 21, 0.05);
        }
        .input-group:focus-within i { color: #821515; }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.75rem;
            background: #821515;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
        }
        .btn-submit:hover { 
            background: #6b1111; 
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(130, 21, 21, 0.2);
        }
        .btn-submit:active { transform: translateY(0); }
        
        [v-cloak] { display: none; }
        
        @media (max-width: 600px) {
            .login-wrap { padding: 1.2rem; }
            .card { padding: 1.8rem 1.4rem; border-radius: 0.8rem; }
            .login-logo { max-width: 260px; }
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
                    <label class="form-label">Usuario</label>
                    <div class="input-group">
                        <i class='bx bx-user'></i>
                        <input type="text" v-model="form.usuario" class="form-control"
                               required autocomplete="username" placeholder="Ingresa tu usuario">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <div class="input-group">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" v-model="form.password" class="form-control"
                               required autocomplete="current-password" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn-submit" :disabled="loading">
                    <i class='bx bx-log-in-circle' v-if="!loading"></i>
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
