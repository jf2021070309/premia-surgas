<?php
$dni = '76032957';
$url = 'https://eldni.com/pe/buscar-datos-por-dni';
$cookieFile = tempnam(sys_get_temp_dir(), 'cookie');
$userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'; // Simplified agent

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR      => $cookieFile,
    CURLOPT_USERAGENT      => $userAgent,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$html = curl_exec($ch);
curl_close($ch);

if (!preg_match('/name="_token"\s+value="([^"]+)"/i', $html, $matches)) {
    echo "NO TOKEN FOUND\n";
    exit;
}
$token = $matches[1];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query(['_token' => $token, 'dni' => $dni]),
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_USERAGENT      => $userAgent,
    CURLOPT_REFERER        => $url,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$response = curl_exec($ch);
curl_close($ch);
unlink($cookieFile);

echo "Response length: " . strlen($response) . "\n";
echo "--- TABLE MATCHER ---\n";
if (preg_match('/<td>'.$dni.'<\/td>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>/is', $response, $m)) {
    echo "TABLE MATCH FOUND:\n";
    var_dump($m);
} else {
    echo "NO TABLE MATCH\n";
}

echo "\n--- SAMP MATCHER ---\n";
if (preg_match('/<samp[^>]*>(.*?)<\/samp>/is', $response, $sm)) {
    echo "SAMP MATCH FOUND:\n";
    var_dump($sm);
} else {
    echo "NO SAMP MATCH\n";
}
