<style>
    /* Premium Responsive Styles for Surgas Chatbot */
    @media (max-width: 768px) {
        /* Stack chatbot box and order history sidebar */
        #pane-chat > div {
            flex-direction: column !important;
            gap: 1.5rem !important;
        }
        
        /* Adjust chat container card header padding and layout */
        .chat-header-bar {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 1rem !important;
            gap: 0.75rem !important;
        }

        /* Adjust header controls wrapper to wrap neatly */
        .chat-header-bar-controls {
            width: 100% !important;
            justify-content: flex-start !important;
            gap: 6px !important;
        }

        /* Set a flexible width for the voice select dropdown on mobile */
        #tts-voice-select {
            flex: 1 !important;
            min-width: 130px !important;
            max-width: none !important;
            height: 34px !important;
            font-size: 0.7rem !important;
        }

        #tts-toggle-btn {
            width: 34px !important;
            height: 34px !important;
        }

        .chat-header-bar-controls button {
            height: 34px !important;
            padding: 4px 10px !important;
            font-size: 0.65rem !important;
        }

        /* Reduce bubble padding to fit mobile screens better */
        .chat-bubble {
            max-width: 90% !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.8rem !important;
            border-radius: 16px 16px 16px 4px !important;
        }
        .user-bubble {
            border-radius: 16px 16px 4px 16px !important;
        }

        /* Adjust input padding and fonts on mobile */
        .chat-input-bar {
            padding: 0.75rem 1rem !important;
            gap: 0.5rem !important;
        }

        #chat-user-input {
            height: 42px !important;
            font-size: 0.82rem !important;
            padding: 0 0.88rem !important;
        }

        #chat-mic-btn, 
        .chat-input-bar button {
            width: 42px !important;
            height: 42px !important;
            font-size: 1.15rem !important;
        }
        
        /* Reduce fixed height slightly for mobile screen heights */
        .chat-container-card,
        .chat-history-sidebar {
            height: 520px !important;
        }
    }

    @media (max-width: 480px) {
        .chat-header-bar-controls {
            flex-wrap: wrap !important;
        }
        
        .chat-header-bar-controls button {
            flex-grow: 1;
        }
    }

    /* Pulsating microphone border for Modo Voz button */
    @keyframes pulse-border {
        0% {
            box-shadow: 0 8px 25px rgba(130, 21, 21, 0.4), 0 0 0 0 rgba(130, 21, 21, 0.7);
        }
        70% {
            box-shadow: 0 8px 25px rgba(130, 21, 21, 0.4), 0 0 0 10px rgba(130, 21, 21, 0);
        }
        100% {
            box-shadow: 0 8px 25px rgba(130, 21, 21, 0.4), 0 0 0 0 rgba(130, 21, 21, 0);
        }
    }
    
    /* Floating Action Button (FAB) for Voice Mode */
    #voice-agent-toggle-btn {
        position: fixed !important;
        bottom: 30px !important;
        right: 30px !important;
        width: 62px !important;
        height: 62px !important;
        border-radius: 50% !important;
        background: linear-gradient(135deg, #821515 0%, #a81c1c 100%) !important;
        border: 2px solid #f87171 !important;
        color: #fff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 8px 25px rgba(130, 21, 21, 0.4) !important;
        cursor: pointer !important;
        z-index: 9999 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        animation: pulse-border 2s infinite !important;
        padding: 0 !important;
    }
    #voice-agent-toggle-btn:hover {
        transform: scale(1.1) !important;
        background: linear-gradient(135deg, #991b1b 0%, #c22525 100%) !important;
        box-shadow: 0 12px 30px rgba(130, 21, 21, 0.6) !important;
    }
    #voice-agent-toggle-btn i {
        font-size: 1.85rem !important;
    }
    
    @media (max-width: 768px) {
        #voice-agent-toggle-btn {
            bottom: 24px !important;
            right: 24px !important;
            width: 56px !important;
            height: 56px !important;
        }
        #voice-agent-toggle-btn i {
            font-size: 1.6rem !important;
        }
    }
    
    /* Fade-in animation for overlay */
    @keyframes fadeInOverlay {
        from { opacity: 0; transform: scale(1.02); }
        to { opacity: 1; transform: scale(1); }
    }
    
    @keyframes scaleUpGlow {
        0%, 100% { transform: scale(0.92); opacity: 0.4; }
        50% { transform: scale(1.15); opacity: 0.7; }
    }

    #voice-agent-overlay {
        display: none;
        position: fixed;
        background: radial-gradient(circle at center, rgba(30, 5, 5, 0.98) 0%, rgba(9, 2, 2, 0.99) 100%);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        z-index: 99999;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        padding: 3rem 2rem;
        box-sizing: border-box;
        text-align: center;
        animation: fadeInOverlay 0.4s ease;
        
        /* Desktop Default: Fit content area */
        top: 64px;
        left: var(--sidebar-width, 240px);
        width: calc(100vw - var(--sidebar-width, 240px));
        height: calc(100vh - 64px);
    }
    
    @media (max-width: 900px) {
        #voice-agent-overlay {
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 99999999 !important; /* Above sidebar (5000) and headers */
        }
    }

    /* Custom CSS styles for the Gas Cylinder Mascot */
    .gas-cylinder-svg {
        transition: all 0.5s ease-in-out;
        filter: drop-shadow(0 8px 16px rgba(0,0,0,0.4));
        transform-origin: bottom center;
    }

    /* 1. IDLE STATE: Gentle floating */
    @keyframes cylinderFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
    .gas-cylinder-svg.state-idle {
        animation: cylinderFloat 3s infinite ease-in-out;
    }

    /* 2. LISTENING STATE: Gentle vibration/pulse */
    @keyframes cylinderVibrate {
        0%, 100% { transform: translate(0, 0) scale(1); }
        20% { transform: translate(-1.5px, 1.5px) scale(1.01); }
        40% { transform: translate(1.5px, -1.5px) scale(1); }
        60% { transform: translate(-1.5px, -1.5px) scale(1.01); }
        80% { transform: translate(1.5px, 1.5px) scale(1); }
    }
    .gas-cylinder-svg.state-listening {
        animation: cylinderVibrate 0.8s infinite linear;
        border-color: rgba(239, 68, 68, 0.6) !important;
        box-shadow: 0 0 25px rgba(239, 68, 68, 0.4) !important;
    }

    /* 3. RESPONDING STATE: Bouncing matching voice */
    @keyframes cylinderBounce {
        0%, 100% { transform: translateY(0) scaleY(1); }
        40% { transform: translateY(-12px) scaleY(1.06) scaleX(0.95); }
        75% { transform: translateY(3px) scaleY(0.94) scaleX(1.04); }
    }
    .gas-cylinder-svg.state-responding {
        animation: cylinderBounce 0.75s infinite ease-in-out;
        border-color: rgba(239, 68, 68, 0.4) !important;
    }
</style>

<!-- PANE 5: CHATBOT -->
<div id="pane-chat" class="tab-content-pane" style="max-width: 1080px; margin: 0 auto;">
    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        <!-- Chatbot Box -->
        <div class="chat-container-card" style="flex: 1.6; min-width: 320px; background: #fff; border-radius: 24px; border: 1.5px solid #e2e8f0; box-shadow: 0 20px 40px rgba(0,0,0,0.05); overflow: hidden; display: flex; flex-direction: column; height: 600px; position: relative;">
            
            <!-- Chat Header -->
            <div class="chat-header-bar" style="background: #0f172a; padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #1e293b;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="background: #821515; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.3rem;">
                        <i class='bx bx-bot'></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; color: #fff; font-size: 1rem; font-weight: 800;">Surgas Asistente</h4>
                        <span style="font-size: 0.72rem; color: #22c55e; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #22c55e; display: inline-block; animation: pulse 1.5s infinite;"></span>
                            En línea
                        </span>
                    </div>
                </div>
                <div class="chat-header-bar-controls" style="display: flex; gap: 8px; align-items: center;">
                    <button id="voice-agent-toggle-btn" onclick="enterVoiceMode()" title="Modo Voz (Asistente)">
                        <i class='bx bx-microphone'></i>
                    </button>
                    <select id="tts-voice-select" onchange="changeTTSVoice()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #cbd5e1; height: 36px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; padding: 0 10px; cursor: pointer; outline: none; transition: 0.3s; appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 fill=%22%23cbd5e1%22><path d=%22M6 8L2 4h8z%22/></svg>'); background-repeat: no-repeat; background-position: right 8px center; padding-right: 24px;">
                        <option value="es-es" style="background: #0f172a; color: #fff;">Voz España (Joven)</option>
                        <option value="es" style="background: #0f172a; color: #fff;" selected>Voz Latinoamericana</option>
                    </select>
                    <button id="tts-toggle-btn" onclick="toggleTTS()" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s;" title="Activar/Desactivar lectura por voz">
                        <i id="tts-icon" class='bx bx-volume-full' style="font-size: 1.2rem;"></i>
                    </button>
                    <button onclick="resetChat()" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; padding: 6px 12px; border-radius: 8px; font-size: 0.7rem; font-weight: 800; cursor: pointer; transition: 0.3s; text-transform: uppercase;">
                        <i class='bx bx-refresh' style="font-size: 0.95rem; vertical-align: middle;"></i> Reiniciar
                    </button>
                </div>
            </div>

            <!-- Chat Messages Window -->
            <div id="chat-messages" style="flex: 1; padding: 2rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem; background: #f8fafc;">
            </div>

            <!-- Chat Quick Action Buttons -->
            <div id="chat-actions-container" style="padding: 0.75rem 1.5rem; display: flex; flex-wrap: wrap; gap: 8px; background: #f1f5f9; border-top: 1px solid #e2e8f0; min-height: 50px;">
                <!-- Buttons rendered dynamically -->
            </div>

            <!-- Visualizer Waveform Container -->
            <div id="waveform-panel" style="padding: 0.5rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; gap: 10px; height: 50px;">
                <span id="waveform-status-text" style="font-size: 0.72rem; font-weight: 850; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; width: 100px; white-space: nowrap;">Asistente</span>
                <canvas id="waveform-canvas" style="flex: 1; height: 36px; border-radius: 8px;"></canvas>
            </div>

            <!-- Chat Input Bar -->
            <div class="chat-input-bar" style="padding: 1.25rem 1.5rem; border-top: 1px solid #e2e8f0; background: #fff; display: flex; gap: 0.75rem; align-items: center;">
                <input type="text" id="chat-user-input" placeholder="Escribe o habla aquí..." onkeydown="if(event.key === 'Enter') sendChatMessage()" style="flex: 1; height: 48px; border-radius: 12px; border: 1.5px solid #e2e8f0; padding: 0 1.25rem; font-size: 0.9rem; outline: none; transition: 0.3s; font-weight: 600; color: #1e293b;">
                
                <!-- Microphone Button -->
                <button id="chat-mic-btn" onclick="toggleSpeechRecognition()" style="width: 48px; height: 48px; border-radius: 12px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; cursor: pointer; transition: 0.3s;" title="Hablar (Activar micrófono)">
                    <i class='bx bx-microphone'></i>
                </button>

                <button onclick="sendChatMessage()" style="width: 48px; height: 48px; border-radius: 12px; background: #0f172a; color: white; border: none; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; cursor: pointer; transition: 0.3s;">
                    <i class='bx bx-send'></i>
                </button>
            </div>
        </div>

        <!-- Chat Sidebar (Historial) -->
        <div class="chat-history-sidebar" style="flex: 1; min-width: 300px; background: #fff; border-radius: 24px; border: 1.5px solid #e2e8f0; box-shadow: 0 20px 40px rgba(0,0,0,0.05); padding: 1.5rem; display: flex; flex-direction: column; height: 600px;">
            <div style="margin-bottom: 1rem;">
                <h4 style="margin: 0; color: #0f172a; font-size: 1.1rem; font-weight: 850; display: flex; align-items: center; gap: 8px;">
                    <i class='bx bx-history' style="color: #821515; font-size: 1.35rem;"></i> Mis Pedidos Chatbot
                </h4>
                <p style="margin: 4px 0 0 0; font-size: 0.75rem; color: #64748b; font-weight: 600;">Estado de tus solicitudes de recarga</p>
            </div>
            <div id="chat-orders-list" style="flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 0.75rem; padding-right: 4px;">
                <!-- Cargado dinámicamente -->
            </div>
        </div>
    </div>
</div>

<script>
    // --- Chatbot JS Implementation ---
    window.chatInitialized = false;
    
    // Voice & Audio visualizer state variables
    let micStream = null;
    let audioCtx = null;
    let analyser = null;
    let dataArray = null;
    let visualizerAnimationId = null;
    let currentVisualState = 'idle'; // 'idle', 'listening', 'responding'
    
    let recognition = null;
    let isListening = false;
    let interimBubble = null;
    let isBotProcessing = false; // Mutex: prevents double-send while bot is responding or audio is playing
    let finalSent = false;       // Guard: prevents duplicate final transcripts from same recognition session
    
    let synth = window.speechSynthesis;
    let isTTSEnabled = false; // Off by default — activates automatically only when voice mode is opened
    window.userHasSelectedVoice = false;
    window.lastBotReply = "Somos SURGAS\nDeseas tu recarga a :";

    // Listen to voiceschanged event
    if (synth) {
        if (synth.onvoiceschanged !== undefined) {
            synth.onvoiceschanged = populateVoiceSelect;
        }
    }
    
    function initChatbot() {
        window.chatInitialized = true;
        sendChatRequest('');
        loadClientePedidos();
        
        // Initialize canvas Visualizer
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        drawVisualizer();

        // Populate voices
        populateVoiceSelect();
    }

    // Web Audio API Visualizer resize
    function resizeCanvas() {
        const canvas = document.getElementById('waveform-canvas');
        if (!canvas) return;
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * (window.devicePixelRatio || 1);
        canvas.height = rect.height * (window.devicePixelRatio || 1);
    }

    // Update SVG Class based on current visual state
    function updateCylinderState() {
        const cylinder = document.getElementById('voice-gas-cylinder');
        if (cylinder) {
            const desiredClass = 'gas-cylinder-svg state-' + currentVisualState;
            if (cylinder.className.baseVal !== desiredClass) {
                cylinder.className.baseVal = desiredClass;
            }
        }
    }

    // Visualizer animation loop
    function drawVisualizer() {
        visualizerAnimationId = requestAnimationFrame(drawVisualizer);
        updateCylinderState();
        
        const canvas = document.getElementById('waveform-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const width = canvas.width;
        const height = canvas.height;
        
        ctx.clearRect(0, 0, width, height);
        
        if (currentVisualState === 'listening') {
            if (analyser) {
                // Mode 2: Listening (Real frequencies)
                const bufferLength = analyser.frequencyBinCount;
                analyser.getByteFrequencyData(dataArray);
                
                const barWidth = (width / bufferLength) * 2.5;
                let barHeight;
                let x = 0;
                
                for (let i = 0; i < bufferLength; i++) {
                    barHeight = (dataArray[i] / 255) * height * 0.8;
                    if (barHeight < 2) barHeight = 2; // Minimum height
                    
                    const gradient = ctx.createLinearGradient(0, height, 0, 0);
                    gradient.addColorStop(0, '#821515');
                    gradient.addColorStop(1, '#ef4444');
                    
                    ctx.fillStyle = gradient;
                    
                    const centerHeight = (height - barHeight) / 2;
                    ctx.fillRect(x, centerHeight > 0 ? centerHeight : 0, barWidth - 2, barHeight);
                    
                    x += barWidth;
                }
            } else {
                // Mode 2b: Listening (High-fidelity simulated frequencies to avoid mic conflicts)
                const barCount = 24;
                const barWidth = width / barCount;
                const time = Date.now() * 0.006;
                
                for (let i = 0; i < barCount; i++) {
                    const noise = Math.sin(i * 0.35 + time) * Math.cos(i * 0.08 - time * 0.4);
                    const baseHeight = Math.abs(noise) * height * 0.65;
                    const barHeight = Math.max(2, baseHeight + Math.sin(time * 6 + i) * 2.5);
                    
                    const gradient = ctx.createLinearGradient(0, height, 0, 0);
                    gradient.addColorStop(0, '#821515');
                    gradient.addColorStop(1, '#ef4444');
                    ctx.fillStyle = gradient;
                    
                    const centerHeight = (height - barHeight) / 2;
                    ctx.fillRect(i * barWidth, centerHeight > 0 ? centerHeight : 0, barWidth - 2, barHeight);
                }
            }
        } else if (currentVisualState === 'responding') {
            // Mode 3: Responding (Simulated voice wave)
            ctx.beginPath();
            ctx.lineWidth = 2.5;
            ctx.strokeStyle = '#821515';
            
            const time = Date.now() * 0.012;
            ctx.moveTo(0, height / 2);
            
            for (let x = 0; x < width; x++) {
                const angle = (x / width) * Math.PI * 8 + time;
                const amplitude = (height / 2.5) * (Math.sin(time * 0.5) * 0.4 + 0.6) * Math.sin(x / width * Math.PI);
                const y = height / 2 + Math.sin(angle) * amplitude;
                ctx.lineTo(x, y);
            }
            ctx.stroke();
        } else {
            // Mode 1: Idle (Gentle breathing wave)
            ctx.beginPath();
            ctx.lineWidth = 1.5;
            ctx.strokeStyle = '#cbd5e1';
            
            const time = Date.now() * 0.003;
            ctx.moveTo(0, height / 2);
            
            for (let x = 0; x < width; x++) {
                const angle = (x / width) * Math.PI * 4 + time;
                const amplitude = 3 * Math.sin(time);
                const y = height / 2 + Math.sin(angle) * amplitude;
                ctx.lineTo(x, y);
            }
            ctx.stroke();
        }
    }

    function populateVoiceSelect() {
        const select = document.getElementById('tts-voice-select');
        if (!select || !synth) return;
        
        // Remove existing locals to prevent duplicates on event trigger
        const existingLocals = select.querySelectorAll('.local-voice-option');
        existingLocals.forEach(opt => opt.remove());
        
        const voices = synth.getVoices();
        // Filter Spanish voices
        const spanishVoices = voices.filter(v => v.lang.toLowerCase().startsWith('es'));
        
        // Sort so premium natural voices are at the top
        spanishVoices.sort((a, b) => {
            const aNatural = a.name.toLowerCase().includes('natural') || a.name.toLowerCase().includes('online') || a.name.toLowerCase().includes('neural');
            const bNatural = b.name.toLowerCase().includes('natural') || b.name.toLowerCase().includes('online') || b.name.toLowerCase().includes('neural');
            if (aNatural && !bNatural) return -1;
            if (!aNatural && bNatural) return 1;
            return a.name.localeCompare(b.name);
        });
        
        if (spanishVoices.length > 0) {
            const groupOpt = document.createElement('optgroup');
            groupOpt.label = "Voces del Navegador (Naturales)";
            groupOpt.className = "local-voice-option";
            
            spanishVoices.forEach(voice => {
                const opt = document.createElement('option');
                opt.value = 'local_' + voice.name;
                opt.className = "local-voice-option";
                opt.style.background = "#0f172a";
                opt.style.color = "#fff";
                
                let displayName = voice.name;
                // Clean up voice names to make them look beautiful
                displayName = displayName.replace(/Microsoft/g, 'MS');
                displayName = displayName.replace(/Google/g, 'Google');
                if (voice.name.toLowerCase().includes('natural') || voice.name.toLowerCase().includes('online') || voice.name.toLowerCase().includes('neural')) {
                    displayName += ' ⭐';
                }
                opt.innerText = displayName;
                groupOpt.appendChild(opt);
            });
            select.appendChild(groupOpt);
        }
    }

    // Web Speech API - Recognition Setup
    function initSpeechRecognition() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            console.warn("Speech Recognition not supported in this browser.");
            const micBtn = document.getElementById('chat-mic-btn');
            if (micBtn) micBtn.style.display = 'none';
            return;
        }
        
        recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        
        // Use universal Latin American Spanish (es-419) which has high compatibility
        recognition.lang = 'es-419';
        
        recognition.onstart = () => {
            isListening = true;
            currentVisualState = 'listening';
            const statusText = document.getElementById('waveform-status-text');
            if (statusText) {
                statusText.innerText = 'Escuchando...';
                statusText.style.color = '#ef4444';
            }
            
            const micBtn = document.getElementById('chat-mic-btn');
            if (micBtn) {
                micBtn.style.background = '#fee2e2';
                micBtn.style.color = '#ef4444';
                micBtn.style.borderColor = '#fca5a5';
                micBtn.innerHTML = "<i class='bx bx-microphone bx-flashing'></i>";
            }

            // Sync with voice overlay
            if (window.isVoiceModeActive) {
                const voiceBadge = document.getElementById('voice-status-badge');
                if (voiceBadge) {
                    voiceBadge.innerText = 'Escuchando';
                    voiceBadge.style.color = '#22c55e';
                }
                const voiceSubtitle = document.getElementById('voice-agent-subtitle');
                if (voiceSubtitle) {
                    voiceSubtitle.innerText = 'Habla ahora...';
                }
                const voiceMicHint = document.getElementById('voice-mic-status-hint');
                if (voiceMicHint) {
                    voiceMicHint.innerText = 'Escuchando...';
                    voiceMicHint.style.color = '#ef4444';
                }
                const voiceMicBtn = document.getElementById('voice-mic-tap-btn');
                if (voiceMicBtn) {
                    voiceMicBtn.style.background = '#fee2e2';
                    voiceMicBtn.style.color = '#ef4444';
                    voiceMicBtn.style.borderColor = '#fca5a5';
                    const voiceIcon = voiceMicBtn.querySelector('i');
                    if (voiceIcon) voiceIcon.className = 'bx bx-microphone bx-flashing';
                }
                const voiceTxt = document.getElementById('voice-transcription-text');
                if (voiceTxt) {
                    voiceTxt.innerHTML = "<span style='color: #a3a3a3; font-style: normal;'>Di algo... el sistema te escuchará</span>";
                }
            }
            
            // Bypass startAudioSource() to prevent Web Audio mic lock-conflict with Speech Recognition.
            // This guarantees the browser captures clear microphone input for SpeechRecognition.
        };
        
        recognition.onresult = (event) => {
            let interimTranscript = '';
            let finalTranscript = '';
            
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                if (event.results[i].isFinal) {
                    finalTranscript += event.results[i][0].transcript;
                } else {
                    interimTranscript += event.results[i][0].transcript;
                }
            }
            
            if (interimTranscript) {
                showInterimMessage(interimTranscript);
                if (window.isVoiceModeActive) {
                    const voiceTxt = document.getElementById('voice-transcription-text');
                    if (voiceTxt) {
                        voiceTxt.innerHTML = "<span style='color: #22c55e; font-size: 1.1rem; font-weight: 800; font-style: normal;'>Escuchado:</span> <span style='color: #fff; font-size: 1.1rem; font-weight: 700; font-style: normal; text-shadow: 0 0 10px rgba(239,68,68,0.5);'>" + interimTranscript + "</span>";
                    }
                }
            }
            
            if (finalTranscript.trim()) {
                removeInterimMessage();
                // Guard against duplicate final results from the same utterance
                if (finalSent) return;
                finalSent = true;
                if (window.isVoiceModeActive) {
                    const voiceTxt = document.getElementById('voice-transcription-text');
                    if (voiceTxt) {
                        voiceTxt.innerHTML = "<span style='color: #ef4444; font-size: 1.1rem; font-weight: 800; font-style: normal;'>Enviado:</span> <span style='color: #f8fafc; font-size: 1.1rem; font-weight: 700; font-style: normal;'>" + finalTranscript.trim() + "</span>";
                    }
                    const voiceSubtitle = document.getElementById('voice-agent-subtitle');
                    if (voiceSubtitle) {
                        voiceSubtitle.innerText = "Pensando...";
                    }
                }
                sendChatMessage(finalTranscript.trim());
                stopSpeechRecognition();
            }
        };
        
        recognition.onerror = (event) => {
            console.error("Speech Recognition error", event.error);
            const voiceTxt = document.getElementById('voice-transcription-text');
            if (voiceTxt && window.isVoiceModeActive) {
                if (event.error === 'not-allowed') {
                    voiceTxt.innerHTML = "<span style='color: #f87171; font-size: 1rem; font-weight: 700; font-style: normal;'>⚠️ Permiso de micrófono denegado. Por favor permítelo en el navegador.</span>";
                } else if (event.error === 'no-speech') {
                    voiceTxt.innerHTML = "<span style='color: #94a3b8; font-size: 1rem; font-style: normal;'>No se detectó voz. Intenta hablar más fuerte o acércate.</span>";
                } else if (event.error === 'network') {
                    voiceTxt.innerHTML = "<span style='color: #f87171; font-size: 1rem; font-weight: 700; font-style: normal;'>⚠️ Error de conexión de red para dictado de voz.</span>";
                } else {
                    voiceTxt.innerHTML = "<span style='color: #f87171; font-size: 1rem; font-style: normal;'>Error de dictado: " + event.error + "</span>";
                }
            }
            if (event.error !== 'no-speech' && event.error !== 'aborted') {
                stopSpeechRecognition();
            }
        };
        
        recognition.onend = () => {
            if (isListening) {
                // Wrap in setTimeout to prevent chrome double-start InvalidStateError crashes
                setTimeout(() => {
                    if (isListening) {
                        try {
                            recognition.start();
                        } catch (e) {
                            console.error("Failed to restart speech recognition:", e);
                        }
                    }
                }, 300);
            } else {
                stopSpeechRecognition();
            }
        };
    }
    
    function toggleSpeechRecognition() {
        if (!recognition) {
            initSpeechRecognition();
        }
        
        if (!recognition) return;
        
        if (isListening) {
            isListening = false;
            recognition.stop();
            stopSpeechRecognition();
        } else {
            // Don't start mic while bot is still processing or playing audio
            if (isBotProcessing) return;
            if (currentAudio) {
                currentAudio.pause();
                currentAudio = null;
            }
            if (synth && synth.speaking) {
                synth.cancel();
            }
            finalSent = false; // Reset guard for new session
            isListening = true;
            try {
                recognition.start();
            } catch(e) {
                isListening = false;
                console.warn('Recognition start failed:', e);
            }
        }
    }

    function stopSpeechRecognition() {
        isListening = false;
        currentVisualState = 'idle';
        
        const micBtn = document.getElementById('chat-mic-btn');
        if (micBtn) {
            micBtn.style.background = '#f1f5f9';
            micBtn.style.color = '#475569';
            micBtn.style.borderColor = '#cbd5e1';
            micBtn.innerHTML = "<i class='bx bx-microphone'></i>";
        }
        
        const statusText = document.getElementById('waveform-status-text');
        if (statusText) {
            statusText.innerText = 'Asistente';
            statusText.style.color = '#64748b';
        }

        // Sync with voice overlay on stop
        if (window.isVoiceModeActive) {
            const voiceBadge = document.getElementById('voice-status-badge');
            if (voiceBadge) {
                voiceBadge.innerText = 'Silenciado';
                voiceBadge.style.color = '#94a3b8';
            }
            const voiceSubtitle = document.getElementById('voice-agent-subtitle');
            if (voiceSubtitle && currentVisualState !== 'responding') {
                voiceSubtitle.innerText = 'Conversación pausada';
            }
            const voiceMicHint = document.getElementById('voice-mic-status-hint');
            if (voiceMicHint) {
                voiceMicHint.innerText = 'Presiona para hablar';
                voiceMicHint.style.color = '#94a3b8';
            }
            const voiceMicBtn = document.getElementById('voice-mic-tap-btn');
            if (voiceMicBtn) {
                voiceMicBtn.style.background = '#821515';
                voiceMicBtn.style.color = '#fff';
                voiceMicBtn.style.borderColor = '#ef4444';
                const voiceIcon = voiceMicBtn.querySelector('i');
                if (voiceIcon) voiceIcon.className = 'bx bx-microphone';
            }
        }
        
        removeInterimMessage();
        stopAudioSource();
    }

    function showInterimMessage(text) {
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) return;
        
        if (!interimBubble) {
            interimBubble = document.createElement('div');
            interimBubble.id = 'interim-bubble';
            interimBubble.style.alignSelf = 'flex-end';
            interimBubble.style.maxWidth = '85%';
            interimBubble.style.background = 'rgba(59, 130, 246, 0.1)';
            interimBubble.style.color = '#3b82f6';
            interimBubble.style.border = '1px dashed #3b82f6';
            interimBubble.style.borderRadius = '20px 20px 4px 20px';
            interimBubble.style.padding = '0.85rem 1.15rem';
            interimBubble.style.fontSize = '0.88rem';
            interimBubble.style.lineHeight = '1.5';
            interimBubble.style.boxShadow = '0 4px 10px rgba(0,0,0,0.02)';
            interimBubble.style.fontStyle = 'italic';
            chatMessages.appendChild(interimBubble);
        }
        
        interimBubble.innerText = text + "...";
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function removeInterimMessage() {
        if (interimBubble) {
            interimBubble.remove();
            interimBubble = null;
        }
    }

    // Web Audio API Input Handling
    function startAudioSource() {
        if (audioCtx) return;
        
        navigator.mediaDevices.getUserMedia({ audio: true, video: false })
        .then(stream => {
            micStream = stream;
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            audioCtx = new AudioContextClass();
            analyser = audioCtx.createAnalyser();
            
            const source = audioCtx.createMediaStreamSource(stream);
            source.connect(analyser);
            
            analyser.fftSize = 64;
            const bufferLength = analyser.frequencyBinCount;
            dataArray = new Uint8Array(bufferLength);
        })
        .catch(err => {
            console.error("Error accessing microphone for visualizer:", err);
        });
    }

    function stopAudioSource() {
        if (micStream) {
            micStream.getTracks().forEach(track => track.stop());
            micStream = null;
        }
        if (audioCtx) {
            if (audioCtx.state !== 'closed') {
                audioCtx.close();
            }
            audioCtx = null;
        }
        analyser = null;
        dataArray = null;
    }

    // Speech Synthesis (Text to Speech) Setup
    let currentAudio = null;

    function changeTTSVoice() {
        window.userHasSelectedVoice = true;
        if (currentAudio) {
            currentAudio.pause();
            currentAudio = null;
        }
        if (synth && synth.speaking) {
            synth.cancel();
        }
        currentVisualState = 'idle';
        const statusText = document.getElementById('waveform-status-text');
        if (statusText) {
            statusText.innerText = 'Asistente';
            statusText.style.color = '#64748b';
        }

        // Preview the new voice with the last message
        if (window.lastBotReply && isTTSEnabled) {
            setTimeout(() => {
                speakBotResponse(window.lastBotReply);
            }, 150);
        }
    }

    function speakBotResponse(text) {
        window.lastBotReply = text;
        // Solo hablar si el modal de voz flotante está abierto
        if (!isTTSEnabled || !window.isVoiceModeActive) return;
        
        if (currentAudio) {
            currentAudio.pause();
            currentAudio = null;
        }
        if (synth && synth.speaking) {
            synth.cancel();
        }
        
        let cleanText = text.replace(/\*\*/g, '');
        cleanText = cleanText.replace(/•/g, '');
        cleanText = cleanText.replace(/S\/\.?\s*(\d+)/gi, 'Soles $1');
        cleanText = cleanText.trim();
        
        if (!cleanText) return;

        // Use the Google TTS backend proxy with selected accent/locale
        const voiceSelect = document.getElementById('tts-voice-select');
        const tl = voiceSelect ? voiceSelect.value : 'es-es';

        if (tl.startsWith('local_')) {
            fallbackSpeak(cleanText);
            return;
        }

        const ttsUrl = BASE_URL + 'api/chatbot/tts?text=' + encodeURIComponent(cleanText) + '&tl=' + tl;
        currentAudio = new Audio(ttsUrl);
        
        currentAudio.onplay = () => {
            currentVisualState = 'responding';
            const statusText = document.getElementById('waveform-status-text');
            if (statusText) {
                statusText.innerText = 'Hablando...';
                statusText.style.color = '#821515';
            }
            if (window.isVoiceModeActive && isListening) {
                recognition.stop();
                isListening = false;
            }
        };
        
        currentAudio.onended = () => {
            currentAudio = null; // Clear reference so next call doesn't conflict
            currentVisualState = 'idle';
            const statusText = document.getElementById('waveform-status-text');
            if (statusText) {
                statusText.innerText = 'Asistente';
                statusText.style.color = '#64748b';
            }
            // Wait 1.2s before re-enabling mic — lets the browser fully release the audio pipeline
            setTimeout(() => {
                isBotProcessing = false; // Unlock: bot is done, mic can open again
                finalSent = false;       // Reset duplicate guard
                if (window.isVoiceModeActive && !isListening) {
                    if (!recognition) initSpeechRecognition();
                    if (recognition) {
                        try {
                            isListening = true;
                            recognition.start();
                        } catch(e) {
                            isListening = false;
                            console.warn('Could not restart recognition after audio ended:', e);
                        }
                    }
                }
            }, 1200);
        };
        
        currentAudio.onerror = (e) => {
            console.error("Error playing audio via backend TTS, falling back to Web Speech API", e);
            currentAudio = null;
            currentVisualState = 'idle';
            const statusText = document.getElementById('waveform-status-text');
            if (statusText) {
                statusText.innerText = 'Asistente';
                statusText.style.color = '#64748b';
            }
            // Don't release mutex here — fallbackSpeak will handle it via utterance.onended
            fallbackSpeak(cleanText);
        };
        
        currentAudio.play().catch(err => {
            console.warn("Audio play blocked or failed, falling back to Web Speech API", err);
            fallbackSpeak(cleanText);
        });
    }

    let fallbackSpeakTimeout = null;

    function fallbackSpeak(cleanText) {
        if (!synth || !isTTSEnabled) return;
        
        // Cancel any active speaking
        synth.cancel();
        
        if (fallbackSpeakTimeout) {
            clearTimeout(fallbackSpeakTimeout);
        }
        
        fallbackSpeakTimeout = setTimeout(() => {
            const voiceSelect = document.getElementById('tts-voice-select');
            const selectedValue = voiceSelect ? voiceSelect.value : 'es-es';
            
            const utterance = new SpeechSynthesisUtterance(cleanText);
            const voices = synth.getVoices();
            let selectedVoice = null;
            
            if (selectedValue.startsWith('local_')) {
                const voiceName = selectedValue.substring(6);
                selectedVoice = voices.find(v => v.name === voiceName);
            }
            
            if (selectedVoice) {
                utterance.voice = selectedVoice;
                utterance.lang = selectedVoice.lang;
            } else {
                // Default fallback if not local_ or not found
                const tl = selectedValue;
                utterance.lang = tl;
                let matchedVoice = voices.find(voice => voice.lang.toLowerCase() === tl.toLowerCase());
                if (!matchedVoice) {
                    matchedVoice = voices.find(voice => voice.lang.toLowerCase().startsWith('es'));
                }
                if (matchedVoice) {
                    utterance.voice = matchedVoice;
                }
            }
            
            utterance.onstart = () => {
                currentVisualState = 'responding';
                const statusText = document.getElementById('waveform-status-text');
                if (statusText) {
                    statusText.innerText = 'Hablando...';
                    statusText.style.color = '#821515';
                }
                if (window.isVoiceModeActive && isListening) {
                    recognition.stop();
                    isListening = false;
                }
            };
            
            utterance.onended = () => {
                currentVisualState = 'idle';
                const statusText = document.getElementById('waveform-status-text');
                if (statusText) {
                    statusText.innerText = 'Asistente';
                    statusText.style.color = '#64748b';
                }
                // Wait 1.2s before re-enabling mic
                setTimeout(() => {
                    isBotProcessing = false; // Unlock mutex
                    finalSent = false;       // Reset duplicate guard
                    if (window.isVoiceModeActive && !isListening) {
                        if (!recognition) initSpeechRecognition();
                        if (recognition) {
                            try {
                                isListening = true;
                                recognition.start();
                            } catch(e) {
                                isListening = false;
                                console.warn('Could not restart recognition after utterance ended:', e);
                            }
                        }
                    }
                }, 1200);
            };
            
            utterance.onerror = (e) => {
                console.error("SpeechSynthesisUtterance error:", e);
                currentVisualState = 'idle';
                isBotProcessing = false; // Unlock mutex on error
                finalSent = false;
                const statusText = document.getElementById('waveform-status-text');
                if (statusText) {
                    statusText.innerText = 'Asistente';
                    statusText.style.color = '#64748b';
                }
            };
            
            synth.speak(utterance);
        }, 250);
    }

    function toggleTTS() {
        isTTSEnabled = !isTTSEnabled;
        const btn = document.getElementById('tts-toggle-btn');
        const icon = document.getElementById('tts-icon');
        if (!btn || !icon) return;
        
        if (isTTSEnabled) {
            btn.style.color = '#cbd5e1';
            btn.style.background = 'rgba(255,255,255,0.05)';
            btn.style.borderColor = 'rgba(255,255,255,0.1)';
            icon.className = 'bx bx-volume-full';
        } else {
            btn.style.color = '#f87171';
            btn.style.background = 'rgba(239, 68, 68, 0.1)';
            btn.style.borderColor = 'rgba(239, 68, 68, 0.2)';
            icon.className = 'bx bx-volume-mute';
            
            if (currentAudio) {
                currentAudio.pause();
                currentAudio = null;
            }
            if (synth && synth.speaking) {
                synth.cancel();
            }
            currentVisualState = 'idle';
            const statusText = document.getElementById('waveform-status-text');
            if (statusText) {
                statusText.innerText = 'Asistente';
                statusText.style.color = '#64748b';
            }
        }
    }

    function loadClientePedidos() {
        const listContainer = document.getElementById('chat-orders-list');
        if (!listContainer) return;

        listContainer.innerHTML = '<div style="text-align: center; color: #94a3b8; font-size: 0.85rem; padding: 2rem;"><i class="bx bx-loader-alt bx-spin" style="font-size: 1.5rem; color: #821515; margin-bottom: 8px;"></i><br>Cargando tus pedidos...</div>';

        fetch(BASE_URL + 'api/chatbot/pedidos-cliente')
        .then(r => r.json())
        .then(data => {
            listContainer.innerHTML = '';
            if (!data.success || !data.pedidos || data.pedidos.length === 0) {
                listContainer.innerHTML = '<div style="text-align: center; color: #94a3b8; font-size: 0.85rem; padding: 3rem;"><i class="bx bx-shopping-bag" style="font-size: 2rem; margin-bottom: 8px; display: block;"></i>Aún no has realizado pedidos por este medio.</div>';
                return;
            }

            data.pedidos.forEach(p => {
                const item = document.createElement('div');
                item.style.background = '#f8fafc';
                item.style.border = '1px solid #e2e8f0';
                item.style.borderRadius = '16px';
                item.style.padding = '1rem';
                item.style.display = 'flex';
                item.style.flexDirection = 'column';
                item.style.gap = '8px';
                item.style.transition = '0.2s';
                item.onmouseover = () => item.style.borderColor = '#cbd5e1';
                item.onmouseout = () => item.style.borderColor = '#e2e8f0';

                const dateObj = new Date(p.fecha_creacion);
                const formattedDate = dateObj.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });

                let chipStyle = "background: #f1f5f9; color: #475569;";
                if (p.estado === 'entregado') chipStyle = "background: #dcfce7; color: #15803d;";
                else if (p.estado === 'pendiente') chipStyle = "background: #fef3c7; color: #d97706;";
                else if (p.estado === 'cancelado') chipStyle = "background: #fee2e2; color: #b91c1c;";

                item.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.8rem; font-weight: 800; color: #0f172a;">Pedido #${String(p.id).padStart(5, '0')}</span>
                        <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; padding: 2px 8px; border-radius: 50px; ${chipStyle}">${p.estado}</span>
                    </div>
                    <div style="font-size: 0.82rem; font-weight: 700; color: #334155;">
                        ${p.modalidad === 'A Domicilio' ? `Balón ${p.producto} x ${p.cantidad}` : 'Retiro en Depósito'}
                    </div>
                    <div style="font-size: 0.72rem; color: #64748b; display: flex; align-items: center; gap: 4px;">
                        <i class='bx bx-map-pin' style="color: #821515;"></i>
                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;" title="${p.direccion}">${p.direccion}</span>
                    </div>
                    <div style="font-size: 0.68rem; color: #94a3b8; text-align: right; font-weight: 600;">
                        ${formattedDate}
                    </div>
                `;

                listContainer.appendChild(item);
            });
        })
        .catch(err => {
            listContainer.innerHTML = '<div style="text-align: center; color: #dc2626; font-size: 0.85rem; padding: 2rem;">Error al cargar el historial.</div>';
        });
    }

    function appendMessage(text, isBot) {
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) return;

        const bubble = document.createElement('div');
        bubble.className = `chat-bubble ${isBot ? 'bot-bubble' : 'user-bubble'}`;
        
        bubble.style.alignSelf = isBot ? 'flex-start' : 'flex-end';
        bubble.style.maxWidth = '85%';
        bubble.style.background = isBot ? '#fff' : '#0f172a';
        bubble.style.color = isBot ? '#1e293b' : '#fff';
        bubble.style.border = isBot ? '1px solid #e2e8f0' : 'none';
        bubble.style.borderRadius = isBot ? '20px 20px 20px 4px' : '20px 20px 4px 20px';
        bubble.style.padding = '0.85rem 1.15rem';
        bubble.style.fontSize = '0.88rem';
        bubble.style.lineHeight = '1.5';
        bubble.style.boxShadow = '0 4px 10px rgba(0,0,0,0.02)';
        bubble.style.animation = 'chatFadeIn 0.3s ease';
        bubble.style.whiteSpace = 'pre-line';

        let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        formattedText = formattedText.replace(/• /g, '<i class="bx bx-check" style="color: #22c55e;"></i> ');

        bubble.innerHTML = formattedText;
        chatMessages.appendChild(bubble);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function renderQuickActions(buttons) {
        const container = document.getElementById('chat-actions-container');
        if (!container) return;
        container.innerHTML = '';

        if (!buttons || buttons.length === 0) return;

        buttons.forEach(btnText => {
            const btn = document.createElement('button');
            
            if (btnText === 'Compartir Ubicación') {
                btn.className = 'chat-action-btn gps-btn';
                btn.innerHTML = "<i class='bx bx-map' style='font-size: 1.1rem; vertical-align: middle;'></i> Compartir Ubicación GPS";
                btn.style.background = '#821515';
                btn.style.color = '#fff';
                btn.style.border = '1px solid #821515';
                btn.onclick = () => shareLocation();
            } else {
                btn.className = 'chat-action-btn';
                btn.innerText = btnText;
                btn.style.background = '#fff';
                btn.style.color = '#0f172a';
                btn.style.border = '1px solid #cbd5e1';
                btn.onclick = () => sendChatMessage(btnText);
            }

            btn.style.padding = '8px 16px';
            btn.style.borderRadius = '100px';
            btn.style.fontSize = '0.8rem';
            btn.style.fontWeight = '750';
            btn.style.cursor = 'pointer';
            btn.style.transition = '0.2s';
            
            btn.onmouseover = () => {
                btn.style.transform = 'translateY(-2px)';
                if (btnText !== 'Compartir Ubicación') {
                    btn.style.background = '#f8fafc';
                }
            };
            btn.onmouseout = () => {
                btn.style.transform = 'translateY(0)';
                if (btnText !== 'Compartir Ubicación') {
                    btn.style.background = '#fff';
                }
            };

            container.appendChild(btn);
        });
    }

    function shareLocation() {
        if (!navigator.geolocation) {
            Swal.fire('Error', 'Tu navegador no soporta geolocalización', 'error');
            return;
        }

        Swal.fire({
            title: 'Obteniendo ubicación...',
            html: 'Por favor permite el acceso a tu GPS.',
            didOpen: () => { Swal.showLoading(); }
        });

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                Swal.close();
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                appendMessage("Ubicación GPS compartida", false);
                sendChatRequest("Compartí mi ubicación", lat, lng);
            },
            (err) => {
                Swal.close();
                Swal.fire('Error', 'No se pudo obtener tu ubicación. Por favor escribe tu dirección en el chat.', 'warning');
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    function sendChatMessage(text) {
        const inputEl = document.getElementById('chat-user-input');
        const msg = text || (inputEl ? inputEl.value.trim() : '');
        if (!msg) return;
        // Prevent double-send while bot is already processing a response
        if (isBotProcessing) {
            console.warn('Bot is already processing, ignoring duplicate message:', msg);
            return;
        }

        if (inputEl && !text) {
            inputEl.value = '';
        }

        appendMessage(msg, false);
        sendChatRequest(msg);
    }

    function sendChatRequest(msg, lat = null, lng = null) {
        const chatMessages = document.getElementById('chat-messages');
        isBotProcessing = true; // Lock: prevent new messages until this one is fully handled
        
        // Add loader
        const loader = document.createElement('div');
        loader.className = 'chat-bubble bot-bubble loader-bubble';
        loader.id = 'chat-loader-bubble';
        loader.style.alignSelf = 'flex-start';
        loader.style.background = '#fff';
        loader.style.border = '1px solid #e2e8f0';
        loader.style.borderRadius = '20px 20px 20px 4px';
        loader.style.padding = '0.75rem 1.25rem';
        loader.style.boxShadow = '0 4px 10px rgba(0,0,0,0.02)';
        loader.innerHTML = '<i class="bx bx-loader-alt bx-spin" style="font-size: 1.2rem; color: #821515;"></i>';
        chatMessages.appendChild(loader);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        fetch(BASE_URL + 'api/chatbot/message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: msg, latitud: lat, longitud: lng })
        })
        .then(r => r.json())
        .then(data => {
            const l = document.getElementById('chat-loader-bubble');
            if (l) l.remove();

            if (data.success) {
                appendMessage(data.reply, true);
                renderQuickActions(data.buttons);
                loadClientePedidos();

                if (window.isVoiceModeActive) {
                    // Voice mode: TTS will release the mutex via onended
                    speakBotResponse(data.reply);
                    const voiceTxt = document.getElementById('voice-transcription-text');
                    if (voiceTxt) {
                        voiceTxt.innerHTML = "<strong>Surgas:</strong> " + data.reply.replace(/\n/g, '<br>');
                    }
                    const voiceSubtitle = document.getElementById('voice-agent-subtitle');
                    if (voiceSubtitle) {
                        voiceSubtitle.innerText = "Respondiendo...";
                    }
                } else {
                    // Text-only chat: no audio, release mutex immediately
                    isBotProcessing = false;
                    finalSent = false;
                }
            } else {
                const errMsg = "Hubo un error al procesar el mensaje. Por favor intenta de nuevo.";
                appendMessage(errMsg, true);
                isBotProcessing = false; // Release mutex — no audio plays in text mode on error
                finalSent = false;
                if (window.isVoiceModeActive) {
                    speakBotResponse(errMsg); // voice mode: TTS will release mutex via onended
                    isBotProcessing = true;   // Re-lock since audio is now playing
                    const voiceTxt = document.getElementById('voice-transcription-text');
                    if (voiceTxt) {
                        voiceTxt.innerHTML = "<strong>Error:</strong> " + errMsg;
                    }
                }
            }
        })
        .catch(err => {
            const l = document.getElementById('chat-loader-bubble');
            if (l) l.remove();
            isBotProcessing = false; // Release mutex on network error
            finalSent = false;
            const connErrorMsg = "Error de conexión. Intente de nuevo.";
            appendMessage(connErrorMsg, true);
            if (window.isVoiceModeActive) {
                speakBotResponse(connErrorMsg);
                isBotProcessing = true; // Re-lock — audio will release it via onended
                const voiceTxt = document.getElementById('voice-transcription-text');
                if (voiceTxt) {
                    voiceTxt.innerHTML = "<strong>Error:</strong> " + connErrorMsg;
                }
            }
        });
    }

    function resetChat() {
        sendChatRequest('reset');
    }

    // Voice Mode Control logic
    window.isVoiceModeActive = false;
    let siriWaveAnimationId = null;

    function resizeSiriCanvas() {
        const canvas = document.getElementById('siri-wave-canvas');
        if (canvas && window.isVoiceModeActive) {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width * (window.devicePixelRatio || 1);
            canvas.height = rect.height * (window.devicePixelRatio || 1);
        }
    }

    function enterVoiceMode() {
        window.isVoiceModeActive = true;
        
        // Show overlay
        const overlay = document.getElementById('voice-agent-overlay');
        if (overlay) overlay.style.display = 'flex';
        
        // Enable TTS readout automatically for voice mode
        isTTSEnabled = true;
        const btn = document.getElementById('tts-toggle-btn');
        const icon = document.getElementById('tts-icon');
        if (btn && icon) {
            btn.style.color = '#cbd5e1';
            btn.style.background = 'rgba(255,255,255,0.05)';
            btn.style.borderColor = 'rgba(255,255,255,0.1)';
            icon.className = 'bx bx-volume-full';
        }

        // Initialize voice transcription text
        const voiceTxt = document.getElementById('voice-transcription-text');
        if (voiceTxt) {
            voiceTxt.innerHTML = "<strong>Surgas:</strong> " + window.lastBotReply.replace(/\n/g, '<br>');
        }

        // Setup Siri Wave Canvas sizing
        resizeSiriCanvas();
        window.addEventListener('resize', resizeSiriCanvas);

        // Start animation loop
        if (!siriWaveAnimationId) {
            drawSiriWave();
        }

        // Auto speak the last bot reply on enter, which will trigger microphone listening when ended
        if (window.lastBotReply) {
            speakBotResponse(window.lastBotReply);
        } else {
            setTimeout(() => {
                if (window.isVoiceModeActive && !isListening) {
                    toggleSpeechRecognition();
                }
            }, 300);
        }
    }

    function exitVoiceMode() {
        window.isVoiceModeActive = false;
        
        // Hide overlay
        const overlay = document.getElementById('voice-agent-overlay');
        if (overlay) overlay.style.display = 'none';

        window.removeEventListener('resize', resizeSiriCanvas);

        // Cancel siri wave animation
        if (siriWaveAnimationId) {
            cancelAnimationFrame(siriWaveAnimationId);
            siriWaveAnimationId = null;
        }

        // Stop microphone listening
        if (isListening) {
            toggleSpeechRecognition();
        }

        // Stop active TTS audio if speaking
        if (currentAudio) {
            currentAudio.pause();
            currentAudio = null;
        }
        if (synth && synth.speaking) {
            synth.cancel();
        }
    }

    function toggleVoiceMic() {
        toggleSpeechRecognition();
    }

    function drawSiriWave() {
        siriWaveAnimationId = requestAnimationFrame(drawSiriWave);
        updateCylinderState();
        const canvas = document.getElementById('siri-wave-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const width = canvas.width;
        const height = canvas.height;
        
        ctx.clearRect(0, 0, width, height);
        
        // Define wave parameters based on visual state
        let speed = 0.08;
        let baseAmplitude = height / 4;
        let count = 4; // Number of waves
        
        if (currentVisualState === 'listening') {
            if (analyser) {
                // Modulate amplitude based on real microphone volume
                const bufferLength = analyser.frequencyBinCount;
                analyser.getByteFrequencyData(dataArray);
                let sum = 0;
                for (let i = 0; i < bufferLength; i++) {
                    sum += dataArray[i];
                }
                let avg = sum / bufferLength;
                baseAmplitude = (height / 3.5) * (avg / 128 + 0.1);
                speed = 0.15;
            } else {
                // High-fidelity simulated active listening wave
                baseAmplitude = (height / 4.5) * (Math.sin(Date.now() * 0.006) * 0.3 + 0.7);
                speed = 0.14;
            }
        } else if (currentVisualState === 'responding') {
            // Responding simulated speech wave
            baseAmplitude = (height / 3) * (Math.sin(Date.now() * 0.005) * 0.3 + 0.7);
            speed = 0.12;
        } else {
            // Idle breathing wave
            baseAmplitude = 4;
            speed = 0.03;
        }
        
        const time = Date.now() * speed * 0.1;
        
        for (let i = 0; i < count; i++) {
            ctx.beginPath();
            
            // Adjust wave parameters for different overlapping lines
            const phase = time + i * Math.PI / 2;
            const frequency = 0.015 + i * 0.005;
            const alpha = 1.0 - (i / count) * 0.7;
            
            // Use different warm/Surgas colors for each wave
            let color = '';
            if (i === 0) color = `rgba(239, 68, 68, ${alpha})`; // Red
            else if (i === 1) color = `rgba(249, 115, 22, ${alpha})`; // Orange
            else if (i === 2) color = `rgba(236, 72, 153, ${alpha})`; // Pinkish
            else color = `rgba(130, 21, 21, ${alpha})`; // Dark Red
            
            ctx.strokeStyle = color;
            ctx.lineWidth = i === 0 ? 3 : 1.5;
            
            ctx.moveTo(0, height / 2);
            
            for (let x = 0; x < width; x++) {
                // Envelope function to pinch the wave at the edges (Siri style)
                const envelope = Math.sin((x / width) * Math.PI);
                const y = height / 2 + Math.sin(x * frequency + phase) * baseAmplitude * envelope;
                ctx.lineTo(x, y);
            }
            ctx.stroke();
        }
    }
</script>
