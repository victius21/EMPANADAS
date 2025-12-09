<?php
$dsn = '';
$user = '';
$pass = '';

$url = getenv('DATABASE_URL'); // Render suele poner aquí la URL tipo postgres://...

if ($url) {
    // Ej: postgres://user:pass@host:5432/dbname
    $dbopts = parse_url($url);

    $scheme = $dbopts['scheme'];     // postgres
    $host   = $dbopts['host'];
    $port   = $dbopts['port'];
    $dbname = ltrim($dbopts['path'], '/');
    $user   = $dbopts['user'];
    $pass   = $dbopts['pass'];

    if ($scheme === 'postgres') {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    } else {
        // Por si algún día usas MySQL remoto
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    }
} else {
    // MODO LOCAL (XAMPP)
    $host   = '127.0.0.1';
    $dbname = 'empanadas_db';
    $user   = 'root';
    $pass   = '';
    $dsn    = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
}

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Error BD: '.$e->getMessage());
}
