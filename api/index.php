<?php
// Obtener la URI solicitada
$uri = $_SERVER['REQUEST_URI'];
$uri = explode('?', $uri)[0]; // Omitir parámetros URL si existen

// Ruta absoluta al archivo real solicitado (un nivel arriba de /api)
$realFile = dirname(__DIR__) . $uri;

// Si el archivo físico existe y es PHP, lo ejecutamos
if (is_file($realFile) && pathinfo($realFile, PATHINFO_EXTENSION) === 'php') {
    require $realFile;
    exit;
}

http_response_code(404);
echo "404 Not Found (Vercel PHP Router)";
