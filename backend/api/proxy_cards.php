<?php
// backend/api/proxy_cards.php
set_time_limit(300);

require_once '../config.php';

header('Content-Type: application/json');

// Get query string from current request
$queryString = $_SERVER['QUERY_STRING'];
$apiUrl = "https://api.pokemontcg.io/v2/cards?" . $queryString;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 180);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 180);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Api-Key: ' . POKEMON_API_KEY
]);
error_log($apiUrl);
// Forward the response
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => curl_error($ch)]);
} else {
    http_response_code($httpCode);
    echo $response;
}

curl_close($ch);
