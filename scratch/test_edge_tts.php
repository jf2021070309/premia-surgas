<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Afaya\EdgeTTS\Service\EdgeTTS;

echo "Instantiating EdgeTTS...\n";
$tts = new EdgeTTS();

$text = "Hola, bienvenido a la central de pedidos Surgas.";
$voice = "es-PE-CamilaNeural";

echo "Synthesizing text: '$text' with voice '$voice'...\n";
try {
    $tts->synthesize($text, $voice);
    $audio = $tts->toRaw();
    echo "Success! Generated " . strlen($audio) . " bytes of audio.\n";
    file_put_contents(__DIR__ . '/edge_test.mp3', $audio);
    echo "Audio saved to scratch/edge_test.mp3\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
