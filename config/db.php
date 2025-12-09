<?php
$useEnv = getenv('DATABASE_URL');
try {
    if ($useEnv) {
        $url = parse_url($useEnv);
        $host = $url['host'];
        $port = $url['port'] ?? 5432;
        $user = $url['user'];
        $pass = $url['pass'];
        $db   = ltrim($url['path'], '/');
        $dsn  = "pgsql:host=$host;port=$port;dbname=$db";
        $pdo  = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } else {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=empanadas_db;charset=utf8mb4", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
} catch (PDOException $e) { die("Error BD: " . $e->getMessage()); }
