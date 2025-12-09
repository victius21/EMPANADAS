<?php
require __DIR__ . '/config/db.php';

$nombre = 'Admin';
$correo = 'victor@admin.com';
$password_plano = '123456'; // esta será tu contraseña
$hash = password_hash($password_plano, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, password_hash, rol, activo) VALUES (:n,:c,:h,'admin',1)");
$stmt->execute([
    ':n' => $nombre,
    ':c' => $correo,
    ':h' => $hash,
]);

echo "Admin creado: $correo / $password_plano";
