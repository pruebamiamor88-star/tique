<?php
// Archivo de conexión global a la base de datos
$config = include __DIR__ . '/config.php';
if (!$config) {
    $config = include __DIR__ . '/config/config.php';
}

$host = $config['db_host'] ?? 'localhost';
$port = $config['db_port'] ?? '3306';
$db = $config['db_name'] ?? 'aire';
$user = $config['db_user'] ?? 'root';
$pass = $config['db_pass'] ?? '';
$driver = $config['db_driver'] ?? 'mysql';

if ($driver === 'pgsql') {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    if (strpos($host, 'neon.tech') !== false) {
        $endpoint = explode('.', $host)[0];
        $dsn .= ";options=endpoint=$endpoint";
    }
} else {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
}

try {
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log("DB Connection failed: " . $e->getMessage());
    die("❌ Error Fatal: No se pudo conectar a la base de datos. Detalles: " . $e->getMessage());
}
?>
