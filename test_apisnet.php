<?php
$dni = '76032957';
$url = 'https://api.apis.net.pe/v1/dni?numero=' . $dni;
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$res = curl_exec($ch);
curl_close($ch);
echo "RESPONSE FROM APIS.NET.PE: " . $res . "\n";
