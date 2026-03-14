<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — PremiaSurgas</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            background: radial-gradient(circle at center, #520006 0%, #3a0004 100%);
            margin: 0;
            font-family: 'Outfit', sans-serif;
            color: #333;
        }
        .login-wrap { width: 100%; max-width: 420px; padding: 1.5rem; box-sizing: border-box; }
        .login-header { text-align: center; margin-bottom: 2.2rem; }
        .login-logo { 
            width: 100%; 
            max-width: 360px; 
            height: auto; 
            margin-bottom: 1rem;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.2));
            animation: fadeInScale 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        .login-header p { 
            color: rgba(255,255,255,0.7); 
            font-size: 1rem; 
            margin: 0; 
            font-weight: 300;
            opacity: 0;
            letter-spacing: 0.5px;
            animation: fadeIn 1s ease 0.3s forwards;
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .card { 
            background: white; 
            border-radius: 0.8rem; 
            padding: 2.5rem; 
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .form-label { display: block; font-weight: 500; font-size: 0.85rem; color: #666; margin-bottom: 0.7rem; text-transform: uppercase; letter-spacing: 0.8px; }
        
        .input-group { position: relative; margin-bottom: 1.8rem; }
        .input-group i { 
            position: absolute; 
            left: 1.25rem; 
            top: 50%; 
            transform: translateY(-50%); 
            font-size: 1.3rem; 
            color: #cbd5e0;
            transition: all 0.3s ease;
        }
        .form-control {
            width: 100%;
            padding: 1.1rem 1rem 1.1rem 3.4rem !important;
            border: 1.5px solid #edf2f7 !important;
            background: #f8fafc !important;
            border-radius: 0.6rem !important;
            box-sizing: border-box;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1rem;
            color: #2d3748;
            font-family: inherit;
        }
        .form-control:focus {
            background: white !important;
            border-color: #821515 !important;
            outline: none;
            box-shadow: 0 0 0 4px rgba(130, 21, 21, 0.08);
        }
        .input-group:focus-within i { color: #821515; }

        .btn-premium-submit {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, #821515, #b31919);
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(130, 21, 21, 0.25);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-premium-submit:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(130, 21, 21, 0.35);
            background: linear-gradient(135deg, #961a1a, #c71d1d);
        }
        .btn-premium-submit:active:not(:disabled) {
            transform: scale(0.98);
        }
        .btn-premium-submit:disabled {
            background: #d1d5db;
            box-shadow: none;
            cursor: not-allowed;
        }
        
        [v-cloak] { display: none; }
        
        @media (max-width: 600px) {
            .login-wrap { padding: 1.5rem; }
            .card { padding: 2rem 1.5rem; border-radius: 0.75rem; }
            .login-logo { max-width: 320px; }
            .login-header p { font-size: 0.9rem; }
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

                <button type="submit" class="btn-premium-submit" :disabled="loading">
                    <span v-if="loading">VERIFICANDO...</span>
                    <span v-else>INICIAR SESIÓN</span>
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
