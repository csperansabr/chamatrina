<?php
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$cnpj = '55343450000179';
$url  = 'https://brasilapi.com.br/api/cnpj/v1/' . $cnpj;

$ctx = stream_context_create([
    'http' => [
        'timeout'       => 10,
        'ignore_errors' => true,
        'header'        => "User-Agent: ChamaTrina/1.0\r\nAccept: application/json\r\n",
    ],
    'ssl' => [
        'verify_peer'      => false,
        'verify_peer_name' => false,
    ],
]);

$json = @file_get_contents($url, false, $ctx);

if ($json === false && function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_USERAGENT      => 'ChamaTrina/1.0',
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $json = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (!$json || $code >= 400) {
        $json = false;
    }
}

if ($json === false) {
    http_response_code(502);
    echo json_encode(['erro' => 'Serviço temporariamente indisponível. Tente novamente em instantes.']);
    exit;
}

echo $json;
