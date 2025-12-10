<?php
// controllers/AuthController.php

class AuthController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function login() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo']   ?? '';
            $pass   = $_POST['password'] ?? '';

            // Buscar usuario por correo
            $stmt = $this->pdo->prepare("
                SELECT *
                FROM usuarios
                WHERE correo = :c AND activo = TRUE
                LIMIT 1
            ");
            $stmt->execute([':c' => $correo]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Comparación en TEXTO PLANO (solo desarrollo)
            if ($user && $pass === $user['password_hash']) {

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
                } else {
                    header("Location: index.php?action=repartidor");
                }
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos";
            }
        }

        // Vista de login (asegúrate de que exista este archivo)
        include __DIR__ . '/../views/auth_login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
