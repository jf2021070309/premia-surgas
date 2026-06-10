<?php
// controllers/ChatbotController.php
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/PuntoVentaModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/AuditoriaModel.php';

class ChatbotController {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->requireAuth();
    }

    private function requireAuth(): void {
        if (!isset($_SESSION['id_usuario'])) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            exit;
        }
    }

    public function message(): void {
        header('Content-Type: application/json');
        
        $inputData = json_decode(file_get_contents('php://input'), true);
        $message = trim($inputData['message'] ?? '');
        $lat = isset($inputData['latitud']) ? (float)$inputData['latitud'] : null;
        $lng = isset($inputData['longitud']) ? (float)$inputData['longitud'] : null;

        // Initialize state if not exists
        if (!isset($_SESSION['chat_state'])) {
            $_SESSION['chat_state'] = 'esperando_saludo';
            $_SESSION['chat_data'] = [];
        }

        $state = $_SESSION['chat_state'];
        $data = $_SESSION['chat_data'];

        $reply = "";
        $buttons = [];
        $nextState = $state;

        // Command to reset chatbot at any time
        if (strtolower($message) === 'reset' || strtolower($message) === 'cancelar' || strtolower($message) === 'menu' || strtolower($message) === 'nuevo pedido') {
            $_SESSION['chat_state'] = 'esperando_saludo';
            $_SESSION['chat_data'] = [];
            echo json_encode([
                'success' => true,
                'reply' => "Somos SURGAS\nDeseas tu recarga a :",
                'speech' => "Hola, somos Surgas. ¿Deseas tu recarga a domicilio o recoger en depósito?",
                'buttons' => ['A Domicilio', 'En Depósito']
            ]);
            $_SESSION['chat_state'] = 'esperando_modalidad';
            exit;
        }

        $speech = null;
        switch ($state) {
            case 'esperando_saludo':
                $reply = "Somos SURGAS\nDeseas tu recarga a :";
                $speech = "Hola, somos Surgas. ¿Deseas tu recarga a domicilio o recoger en depósito?";
                $buttons = ['A Domicilio', 'En Depósito'];
                $nextState = 'esperando_modalidad';
                break;

            case 'esperando_modalidad':
                $modalidad = $this->matchOption($message, ['domicilio', 'deposito']);
                if ($modalidad === 'domicilio') {
                    $data['modalidad'] = 'A Domicilio';
                    $reply = "Valor a domicilio S/. 62 x 10 kg\n\nTipo de Balon que usa?";
                    $speech = "El valor a domicilio es de sesenta y dos soles por el balón de diez kilogramos. ¿Qué tipo de balón usas, normal o premium?";
                    $buttons = ['Normal', 'Premium'];
                    $nextState = 'esperando_producto';
                } elseif ($modalidad === 'deposito') {
                    $data['modalidad'] = 'En Depósito';
                    $reply = "Por favor comparte tu ubicación GPS o escribe tu dirección actual para buscar el depósito más cercano:";
                    $speech = "Por favor comparte tu ubicación de G P S o escribe tu dirección actual para buscar el depósito más cercano.";
                    $buttons = ['Compartir Ubicación'];
                    $nextState = 'esperando_ubicacion_punto';
                } else {
                    $reply = "Por favor selecciona una opción válida de modalidad:";
                    $speech = "Por favor selecciona una opción válida. ¿Deseas tu recarga a domicilio o recoger en depósito?";
                    $buttons = ['A Domicilio', 'En Depósito'];
                }
                break;

            case 'esperando_producto':
                $productoOpt = $this->matchOption($message, ['normal', 'premium']);
                if ($productoOpt) {
                    $data['producto'] = ($productoOpt === 'premium') ? 'Premium' : 'Normal';
                    $reply = "cuantos balones desea? (digite)";
                    $speech = "Perfecto. ¿Cuántos balones de gas deseas solicitar?";
                    $buttons = ['1', '2', '3'];
                    $nextState = 'esperando_cantidad';
                } else {
                    $reply = "Tipo de balón no válido. Elige una opción:";
                    $speech = "El tipo de balón seleccionado no es válido. Por favor elige entre normal o premium.";
                    $buttons = ['Normal', 'Premium'];
                }
                break;

            case 'esperando_cantidad':
                $qty = $this->parseNumberInput($message);
                if ($qty > 0 && $qty <= 10) {
                    $data['cantidad'] = $qty;
                    $price = $qty * 62;
                    $reply = "{$qty} Balon de 10 kg precio S/.{$price}\n\nCompartenos tu ubicacion y la unidad estará llevando tu pedido.";
                    $speech = "Entendido, " . $qty . " " . ($qty == 1 ? "balón" : "balones") . ". El precio total es de " . $price . " soles. Ahora, por favor compártenos tu ubicación para que la unidad lleve tu pedido.";
                    $buttons = ['Compartir Ubicación'];
                    $nextState = 'esperando_ubicacion';
                } else {
                    $reply = "Por favor, ingresa una cantidad válida de balones (un número entre 1 y 10).";
                    $speech = "Por favor, ingresa una cantidad válida de balones, entre uno y diez.";
                }
                break;

            case 'esperando_ubicacion':
                if ($lat !== null && $lng !== null) {
                    $data['latitud'] = $lat;
                    $data['longitud'] = $lng;
                    $data['direccion'] = "Ubicación GPS compartida ({$lat}, {$lng})";
                } else {
                    $data['direccion'] = $message;
                }

                // Save the order directly
                $pedidoModel = new PedidoModel();
                $orderData = [
                    'cliente_id' => $_SESSION['id_usuario'],
                    'modalidad' => $data['modalidad'],
                    'producto' => $data['producto'] ?? 'Normal',
                    'cantidad' => $data['cantidad'] ?? 1,
                    'direccion' => $data['direccion'],
                    'latitud' => $data['latitud'] ?? null,
                    'longitud' => $data['longitud'] ?? null,
                    'punto_venta_id' => null
                ];
                
                $pedidoId = $pedidoModel->create($orderData);
                
                // Audit logs
                $audit = new AuditoriaModel();
                $audit->registrar($_SESSION['id_usuario'], 'NUEVO_PEDIDO_CHATBOT', "Se creó el pedido chatbot ID #$pedidoId a domicilio", 'CLIENTES', null, 'cliente');

                $reply = "Tenemos metodos de pago efectivo y yape, coordine con el conductor asignado. Gracias por su pedido.";
                $speech = "Tu pedido ha sido registrado correctamente. Contamos con métodos de pago en efectivo y Yape, puedes coordinar directamente con el conductor asignado. Gracias por tu pedido.";
                $buttons = ['Nuevo Pedido'];
                $nextState = 'esperando_saludo';
                $data = []; // Reset state data
                break;

            case 'esperando_ubicacion_punto':
                // Fetch points of sale
                $puntosModel = new PuntoVentaModel();
                $puntos = $puntosModel->getAll();

                if (empty($puntos)) {
                    $reply = "Lo sentimos, no pudimos encontrar puntos de venta disponibles en este momento. Escribe 'menu' para volver a empezar.";
                    $speech = "Lo sentimos, no pudimos encontrar depósitos disponibles en este momento. Por favor reintenta más tarde.";
                    $buttons = ['Volver al inicio'];
                    $nextState = 'esperando_saludo';
                    break;
                }

                // Filter out closed or empty schedules if they have no defined horario
                // If GPS coordinates are available, sort by distance
                if ($lat !== null && $lng !== null) {
                    usort($puntos, function($a, $b) use ($lat, $lng) {
                        $distA = $this->getDistance($lat, $lng, (float)$a['latitud'], (float)$a['longitud']);
                        $distB = $this->getDistance($lat, $lng, (float)$b['latitud'], (float)$b['longitud']);
                        return $distA <=> $distB;
                    });
                }

                $data['puntos_cercanos'] = $puntos;
                $data['punto_index'] = 0;

                $punto = $puntos[0];
                $data['punto_venta_id'] = $punto['id'];
                $data['direccion'] = $punto['nombre'] . " - " . ($punto['propietario'] ?? '');

                $reply = "📍 Hemos encontrado este depósito de gas cercano para ti:\n\n" .
                         "• **Depósito**: " . $punto['nombre'] . "\n" .
                         "• **Propietario/Dirección**: " . ($punto['propietario'] ?? 'Sin dirección') . "\n" .
                         "• **Horario**: " . ($punto['horario_atencion'] ?? 'No especificado') . "\n\n" .
                         "¿Deseas confirmar tu pedido para recoger en este depósito?";
                $speech = "Hemos encontrado el depósito cercano " . $punto['nombre'] . " en " . ($punto['propietario'] ?? 'su dirección registrada') . ". ¿Deseas confirmar tu pedido para recoger en este depósito?";
                
                $buttons = ['✅ Confirmar Depósito', 'Buscar Otro', '❌ Cancelar'];
                $nextState = 'viendo_puntos';
                break;

            case 'viendo_puntos':
                $decision = $this->matchOption($message, ['si', 'no']);
                $isBuscarOtro = (str_contains(strtolower($message), 'otro') || str_contains(strtolower($message), 'buscar'));

                if ($decision === 'si') {
                    // Save order in deposit
                    $pedidoModel = new PedidoModel();
                    $orderData = [
                        'cliente_id' => $_SESSION['id_usuario'],
                        'modalidad' => $data['modalidad'],
                        'producto' => null,
                        'cantidad' => 1,
                        'direccion' => $data['direccion'],
                        'latitud' => null,
                        'longitud' => null,
                        'punto_venta_id' => $data['punto_venta_id']
                    ];
                    
                    $pedidoId = $pedidoModel->create($orderData);

                    // Audit logs
                    $audit = new AuditoriaModel();
                    $audit->registrar($_SESSION['id_usuario'], 'NUEVO_PEDIDO_CHATBOT', "Se creó el pedido chatbot ID #$pedidoId en depósito", 'CLIENTES', null, 'cliente');

                    $reply = "🎉 ¡Pedido #{$pedidoId} registrado! Puedes acercarte a recoger tu balón en el depósito seleccionado. ¡Gracias por tu preferencia!";
                    $speech = "Excelente, tu pedido número " . $pedidoId . " ha sido registrado. Puedes acercarte a recoger tu balón de gas en el depósito seleccionado. Gracias por tu preferencia.";
                    $buttons = ['Nuevo Pedido'];
                    $nextState = 'esperando_saludo';
                    $data = [];
                } elseif ($decision === 'no' || $isBuscarOtro) {
                    $puntos = $data['puntos_cercanos'] ?? [];
                    $nextIndex = ($data['punto_index'] ?? 0) + 1;

                    if ($nextIndex >= count($puntos)) {
                        $reply = "No hay más depósitos de gas en nuestra lista en este momento. ¿Deseas seleccionar el primer depósito sugerido?";
                        $speech = "No tenemos más depósitos cercanos en nuestra lista por ahora. ¿Deseas seleccionar el primer depósito sugerido?";
                        $buttons = ['✅ Confirmar Depósito', '❌ Cancelar'];
                        $data['punto_index'] = 0;
                        $punto = $puntos[0];
                        $data['punto_venta_id'] = $punto['id'];
                        $data['direccion'] = $punto['nombre'] . " - " . ($punto['propietario'] ?? '');
                    } else {
                        $data['punto_index'] = $nextIndex;
                        $punto = $puntos[$nextIndex];
                        $data['punto_venta_id'] = $punto['id'];
                        $data['direccion'] = $punto['nombre'] . " - " . ($punto['propietario'] ?? '');

                        $reply = "📍 Siguiente depósito cercano:\n\n" .
                                 "• **Depósito**: " . $punto['nombre'] . "\n" .
                                 "• **Propietario/Dirección**: " . ($punto['propietario'] ?? 'Sin dirección') . "\n" .
                                 "• **Horario**: " . ($punto['horario_atencion'] ?? 'No especificado') . "\n\n" .
                                 "¿Deseas confirmar tu pedido para recoger en este depósito?";
                        $speech = "Aquí tienes el siguiente depósito cercano, " . $punto['nombre'] . " en " . ($punto['propietario'] ?? 'su dirección registrada') . ". ¿Deseas confirmar tu pedido para recoger en este local?";
                        $buttons = ['✅ Confirmar Depósito', 'Buscar Otro', '❌ Cancelar'];
                    }
                } else {
                    $reply = "Pedido cancelado. Escribe 'hola' o 'menu' para iniciar un nuevo pedido.";
                    $speech = "Pedido cancelado. ¿Deseas iniciar un nuevo pedido?";
                    $buttons = ['Nuevo Pedido'];
                    $nextState = 'esperando_saludo';
                    $data = [];
                }
                break;
        }

        $_SESSION['chat_state'] = $nextState;
        $_SESSION['chat_data'] = $data;

        echo json_encode([
            'success' => true,
            'reply' => $reply,
            'speech' => $speech ?? $reply,
            'buttons' => $buttons
        ]);
        exit;
    }

    /**
     * Matches a user message against valid options using keywords and Levenshtein distance.
     * Returns the matched option string or null.
     */
    private function matchOption(string $message, array $optionsValidas): ?string {
        $text = mb_strtolower(trim($message), 'UTF-8');
        // Normalize accents
        $text = strtr($text, [
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u',
            'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u',
        ]);

        $mapeoKeywords = [
            'domicilio' => ['domicilio', 'casa', 'delivery', 'llevar', 'traer', 'hogar', 'envio', 'despacho'],
            'deposito'  => ['deposito', 'depósito', 'tienda', 'recoger', 'ir', 'local', 'punto', 'almacen', 'sucursal'],
            'normal'    => ['normal', 'corriente', 'regular', 'básico', 'basico', 'estandar', 'comun'],
            'premium'   => ['premium', 'premiun', 'mejor', 'calidad', 'plus', 'lujo', 'gold'],
            'si'        => ['si', 'sí', 'claro', 'dale', 'ok', 'bueno', 'correcto', 'exacto', 'afirmativo', 'confirmar', 'confirmo'],
            'no'        => ['no', 'nope', 'negativo', 'cancelar', 'incorrecto', 'rechazar', 'anular']
        ];

        // 1. Keyword matching
        foreach ($mapeoKeywords as $opcion => $keywords) {
            if (in_array($opcion, $optionsValidas)) {
                foreach ($keywords as $keyword) {
                    if (str_contains($text, $keyword)) {
                        return $opcion;
                    }
                }
            }
        }

        // 2. Levenshtein matching (for typos or speech transcription errors)
        $mejorOpcion = null;
        $menorDistancia = 9999;
        
        foreach ($optionsValidas as $opcion) {
            $words = preg_split('/[\s,.]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
            $keywordsToCheck = isset($mapeoKeywords[$opcion]) ? $mapeoKeywords[$opcion] : [$opcion];
            
            foreach ($words as $word) {
                foreach ($keywordsToCheck as $kw) {
                    $dist = levenshtein($word, $kw);
                    $umbral = max(1, (int)floor(strlen($kw) * 0.4)); // 40% tolerance, at least 1 edit
                    if ($dist < $menorDistancia && $dist <= $umbral) {
                        $menorDistancia = $dist;
                        $mejorOpcion = $opcion;
                    }
                }
            }
        }

        return $mejorOpcion;
    }

    /**
     * Converts a user message to an integer.
     * Supports spoken Spanish number words (uno, dos, tres...) and plain digits.
     */
    private function parseNumberInput(string $input): int {
        $input = trim(mb_strtolower($input, 'UTF-8'));

        // First try plain numeric cast
        if (is_numeric($input)) {
            return (int)$input;
        }

        // Map Spanish number words → int
        $map = [
            'un'       => 1, 'uno'      => 1, 'una'      => 1,
            'dos'      => 2,
            'tres'     => 3,
            'cuatro'   => 4,
            'cinco'    => 5,
            'seis'     => 6,
            'siete'    => 7,
            'ocho'     => 8,
            'nueve'    => 9,
            'diez'     => 10,
            'once'     => 11,
            'doce'     => 12,
            'trece'    => 13,
            'catorce'  => 14,
            'quince'   => 15,
            'veinte'   => 20,
        ];

        // Split input into words and check each word
        $words = preg_split('/[\s,.]+/', $input, -1, PREG_SPLIT_NO_EMPTY);
        if ($words) {
            foreach ($words as $word) {
                if (array_key_exists($word, $map)) {
                    return $map[$word];
                }
            }
        }

        // Try to extract the first number found in the string (e.g. "quiero 2 balones")
        if (preg_match('/\d+/', $input, $m)) {
            return (int)$m[0];
        }

        return 0;
    }

    private function getDistance(float $lat1, float $lon1, float $lat2, float $lon2): float {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    // ====== ADMIN VIEWS ======
    
    public function adminIndex(): void {
        $this->requireAdmin();
        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->getAll();

        $this->render('pedidos_admin', [
            'pedidos' => $pedidos
        ]);
    }

    public function updateEstado(): void {
        $this->requireAdmin();
        header('Content-Type: application/json');

        $inputData = json_decode(file_get_contents('php://input'), true);
        $id = (int)($inputData['id'] ?? 0);
        $estado = $inputData['estado'] ?? '';

        if ($id > 0 && in_array($estado, ['pendiente', 'entregado', 'cancelado'])) {
            $pedidoModel = new PedidoModel();
            $result = $pedidoModel->updateEstado($id, $estado);
            if ($result) {
                // Audit log
                $audit = new AuditoriaModel();
                $audit->registrar($_SESSION['id_usuario'], 'MODERAR_PEDIDO', "Actualizó estado del pedido chatbot #$id a $estado", 'ADMINISTRACION');

                echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente.']);
                exit;
            }
        }

        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado.']);
        exit;
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . 'panel');
            exit;
        }
    }

    public function clientePedidos(): void {
        header('Content-Type: application/json');
        $clienteId = $_SESSION['id_usuario'] ?? 0;
        if (!$clienteId) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }

        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->getByCliente($clienteId);

        echo json_encode(['success' => true, 'pedidos' => $pedidos]);
        exit;
    }

    public function tts(): void {
        $text = trim($_GET['text'] ?? '');
        if ($text === '') {
            http_response_code(400);
            echo "Falta el parámetro 'text'.";
            exit;
        }

        $tl = trim($_GET['tl'] ?? 'es');
        // Whitelist of supported locales
        if (!in_array($tl, ['es', 'es-es'])) {
            $tl = 'es';
        }

        // Split text into small chunks to avoid Google's limits (approx 150 characters per request)
        $chunks = $this->splitTextForTTS($text, 150);
        $finalAudioData = '';
        
        $cacheDir = __DIR__ . '/../tmp/tts_cache';
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0777, true);
        }

        $edgeTts = new \Afaya\EdgeTTS\Service\EdgeTTS();

        foreach ($chunks as $chunk) {
            $chunk = trim($chunk);
            if ($chunk === '') continue;

            $chunkHash = md5($tl . '_v3_edge_' . $chunk);
            $cacheFile = $cacheDir . '/' . $chunkHash . '.mp3';

            if (file_exists($cacheFile) && filesize($cacheFile) > 0) {
                $chunkAudio = @file_get_contents($cacheFile);
                if ($chunkAudio) {
                    $finalAudioData .= $chunkAudio;
                    continue;
                }
            }

            $chunkAudio = null;
            try {
                $voiceName = ($tl === 'es') ? 'es-PE-CamilaNeural' : 'es-ES-ElviraNeural';
                $edgeTts->synthesize($chunk, $voiceName);
                $chunkAudio = $edgeTts->toRaw();
            } catch (\Exception $e) {
                // If Microsoft Edge TTS fails, fallback to Google Translate TTS
                $domain = ($tl === 'es') ? 'translate.google.com.pe' : 'translate.google.es';
                $encodedText = urlencode($chunk);
                $url = "https://" . $domain . "/translate_tts?ie=UTF-8&tl=es&client=tw-ob&q=" . $encodedText;
                $chunkAudio = $this->fetchUrlContent($url);
            }

            if ($chunkAudio) {
                $finalAudioData .= $chunkAudio;
                @file_put_contents($cacheFile, $chunkAudio);
            }
        }

        if ($finalAudioData === '') {
            http_response_code(500);
            echo "Error al generar el audio";
            exit;
        }

        header('Content-Type: audio/mpeg');
        header('Content-Length: ' . strlen($finalAudioData));
        header('Cache-Control: public, max-age=86400');
        echo $finalAudioData;
        exit;
    }

    private function fetchUrlContent(string $url): ?string {
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $data = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($httpCode === 200) {
                return $data;
            }
        }

        // Fallback to file_get_contents with stream context
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n"
            ]
        ];
        $context = stream_context_create($options);
        $data = @file_get_contents($url, false, $context);
        return $data ?: null;
    }

    private function splitTextForTTS(string $text, int $maxLength = 150): array {
        if (mb_strlen($text, 'UTF-8') <= $maxLength) {
            return [$text];
        }

        $chunks = [];
        // Regex to split on spaces but keep them in the resulting array
        $words = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $currentChunk = '';

        foreach ($words as $word) {
            if (mb_strlen($currentChunk . $word, 'UTF-8') > $maxLength) {
                $trimmed = trim($currentChunk);
                if ($trimmed !== '') {
                    $chunks[] = $trimmed;
                }
                $currentChunk = $word;
            } else {
                $currentChunk .= $word;
            }
        }

        $trimmed = trim($currentChunk);
        if ($trimmed !== '') {
            $chunks[] = $trimmed;
        }

        return $chunks;
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }
}
