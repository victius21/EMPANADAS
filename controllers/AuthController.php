<?php
// controllers/AuthController.php

require_once __DIR__ . '/../config/MongoLogger.php';

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

            $mongo = new MongoLogger();

            // Comparación en texto plano (SOLO desarrollo)
            if ($user && $pass === $user['password_hash']) {

                $_SESSION['user'] = [
                    'id'   => $user['id'],
                    'rol'  => $user['rol'],
                    'name' => $user['nombre'],
                ];

                // 🔹 Log en Mongo: login exitoso
                $mongo->logEvent('login_exitoso', [
                    'user_id' => $user['id'],
                    'rol'     => $user['rol'],
                    'correo'  => $user['correo'],
                ]);

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

                // 🔹 Log en Mongo: intento fallido
                $mongo->logEvent('login_fallido', [
                    'correo' => $correo,
                ]);
            }
        }

        // Cargar vista de login
        include __DIR__ . '/../views/auth_login.php';
    }

    public function logout() {
        // Log de logout (opcional)
        if (isset($_SESSION['user'])) {
            $mongo = new MongoLogger();
            $mongo->logEvent('logout', [
                'user_id' => $_SESSION['user']['id'] ?? null,
                'rol'     => $_SESSION['user']['rol'] ?? null,
            ]);
        }

        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
