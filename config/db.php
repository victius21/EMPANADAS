<?php
// Mostrar errores (útil en desarrollo; en producción podrías desactivarlo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dsn  = '';
$user = '';
$pass = '';

// Render normalmente pone la URL de la BD en DATABASE_URL
// Ejemplos:
//  - postgres://user:pass@host:5432/dbname
//  - mysql://user:pass@host:3306/dbname
$url = getenv('DATABASE_URL');

if ($url) {
    // MODO RENDER (PRODUCCIÓN)
    $dbopts = parse_url($url);

    $scheme = $dbopts['scheme'] ?? 'postgres';
    $host   = $dbopts['host']   ?? 'localhost';
    $port   = $dbopts['port']   ?? 5432;
    $dbname = ltrim($dbopts['path'] ?? '', '/');
    $user   = $dbopts['user']   ?? '';
    $pass   = $dbopts['pass']   ?? '';

    if ($scheme === 'postgres' || $scheme === 'postgresql') {
        // Conexión PDO a PostgreSQL
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    } elseif ($scheme === 'mysql') {
        // Conexión PDO a MySQL (por si usas MySQL remoto)
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    } else {
        die('Error BD: motor de base de datos no soportado: ' . $scheme);
    }
} else {
    // MODO LOCAL (XAMPP con MySQL)
    $host   = '127.0.0.1';
    $port   = '3306';
    $dbname = 'empanadas_db';   // cambia si tu BD local tiene otro nombre
    $user   = 'root';
    $pass   = '';
    $dsn    = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
}

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Error BD: ' . $e->getMessage());
}
