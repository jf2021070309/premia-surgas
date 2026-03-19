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
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            background: radial-gradient(circle at center, #52060a 0%, #1a0203 100%);
            margin: 0;
            font-family: 'Outfit', sans-serif;
            color: #333;
        }
        .login-wrap { width: 100%; max-width: 440px; padding: 1.5rem; box-sizing: border-box; }
        .login-header { text-align: center; margin-bottom: 1.8rem; }
        .login-logo { 
            width: 100%; 
            max-width: 280px; 
            height: auto; 
            margin-bottom: 1rem;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.4));
            animation: fadeInScale 1s cubic-bezier(0.2, 1, 0.3, 1) forwards;
        }
        .login-header p { 
            color: rgba(255,255,255,0.45); 
            font-size: 0.85rem; 
            margin: 0; 
            font-weight: 300;
            letter-spacing: 1px;
            text-transform: uppercase;
            animation: fadeIn 1.2s ease 0.5s forwards;
            opacity: 0;
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9) translateY(-15px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .card { 
            background: #fff; 
            border-radius: 28px; 
            padding: 2.5rem 2.8rem; 
            box-shadow: 0 40px 100px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .form-label { 
            display: block; 
            font-weight: 600; 
            font-size: 0.7rem; 
            color: #aaa; 
            margin-bottom: 0.6rem; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
        }
        
        .input-group { position: relative; margin-bottom: 1.6rem; }
        .input-group i { 
            position: absolute; 
            left: 0; 
            top: 50%; 
            transform: translateY(-50%); 
            font-size: 1.1rem; 
            color: #ddd;
            transition: all 0.3s ease;
        }
        .form-control {
            width: 100%;
            padding: 0.7rem 0 0.7rem 2.5rem !important;
            border: none !important;
            border-bottom: 1.5px solid #eee !important;
            background: transparent !important;
            border-radius: 0 !important;
            box-sizing: border-box;
            transition: all 0.4s ease;
            font-size: 1rem;
            color: #2d3748;
            font-family: inherit;
        }
        .form-control:focus {
            border-bottom-color: #000 !important;
            outline: none;
        }
        .input-group:focus-within i { color: #000; }
        .btn-premium-submit {
            width: 100%;
            padding: 1.2rem;
            background: #0a0a0a;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            color: white;
            font-weight: 500;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            font-family: 'Outfit', sans-serif;
        }
        .btn-premium-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.12) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: skewX(-25deg);
            animation: shine 3s infinite ease-in-out;
        }
        @keyframes shine {
            0% { left: -120%; }
            20% { left: 180%; }
            100% { left: 180%; }
        }
        .btn-premium-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
            border-color: rgba(255,255,255,0.2);
        }
        .btn-premium-submit:active:not(:disabled) {
            transform: scale(0.98);
        }
        .btn-premium-submit:disabled {
            background: #222;
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        [v-cloak] { display: none; }
        
        @media (max-width: 600px) {
            .login-wrap { padding: 1.5rem; }
            .card { padding: 2.5rem 1.8rem; border-radius: 1.2rem; }
            .login-logo { max-width: 320px; }
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
                    <span v-if="loading">Verificando...</span>
                    <span v-else>Iniciar sesión</span>
                </button>
            </form>
        </div>
    </div>
</div>>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="<?= BASE_URL ?>views/login.js"></script>
</body>
</html>
