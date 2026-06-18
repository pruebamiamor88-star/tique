<?php
header('Content-Type: application/json; charset=utf-8');

// Obtener la URI solicitada
$uri = $_SERVER['REQUEST_URI'];
$uri = explode('?', $uri)[0]; // Omitir parámetros URL si existen

// Normalizar la URI en desarrollo local (XAMPP) si se corre en la subcarpeta /tique/
if (strpos($uri, '/tique/') === 0) {
    $uri = substr($uri, 6); // Quitar '/tique' (6 caracteres)
}

// Ruta absoluta al archivo real solicitado dentro de /php_sources
$realFile = dirname(__DIR__) . '/php_sources' . $uri;

// Si el archivo físico existe y es PHP, lo ejecutamos
if (is_file($realFile) && pathinfo($realFile, PATHINFO_EXTENSION) === 'php') {
    require $realFile;
    exit;
}

http_response_code(404);
echo json_encode(array("error" => "404 Not Found (Vercel PHP Router)"));
