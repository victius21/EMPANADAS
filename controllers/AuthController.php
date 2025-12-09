<?php
// controllers/AuthController.php

class AuthController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function login() {
        // Si viene por POST, intentamos iniciar sesión
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo']   ?? '';
            $pass   = $_POST['password'] ?? '';

            // Buscar usuario por correo
            $stmt = $this->pdo->prepare("
                SELECT * 
                FROM usuarios 
                WHERE correo = :c AND activo = 1
                LIMIT 1
            ");
            $stmt->execute([':c' => $correo]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // *** IMPORTANTE: comparación en TEXTO PLANO (solo para desarrollo) ***
            // password_hash se está usando como campo donde guardamos la contraseña tal cual
            if ($user && $pass === $user['password_hash']) {

                // Guardamos datos mínimos en sesión
                $_SESSION['user'] = [
                    'id'   => $user['id'],
                    'rol'  => $user['rol'],
                    'name' => $user['nombre'],
                ];

                // Redirigir según rol
                if ($user['rol'] === 'admin') {
                    header("Location: index.php?action=admin");
                } elseif ($user['rol'] === 'cocina') {
                    header("Location: index.php?action=cocina");
                } else { // repartidor
                    header("Location: index.php?action=repartidor");
                }
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos";
            }
        }

        // Mostrar vista de login
        include __DIR__ . '/../views/auth_login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
