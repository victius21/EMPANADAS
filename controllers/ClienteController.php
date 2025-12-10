<?php
// controllers/ClienteController.php

require_once __DIR__ . '/../config/MongoLogger.php';

class ClienteController
{
    private PDO $pdo;
    private MongoLogger $logger;

    public function __construct(PDO $pdo)
    {
        $this->pdo    = $pdo;
        $this->logger = new MongoLogger();
    }

    /* Página de inicio: Empanadas Colombianas + botones */
    public function home()
    {
        // Log: visita a la home de clientes
        $this->logger->logEvent('cliente_home_visit', [
            'sesion_cliente_id' => $_SESSION['cliente_id'] ?? null,
        ]);

        require __DIR__ . '/../views/home.php';
    }

    /* Registro de clientes */
    public function registro()
    {
        $mensaje = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre    = trim($_POST['nombre'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? '';
            $telefono  = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            // Log intento de registro
            $this->logger->logEvent('cliente_registro_intento', [
                'email'    => $email,
                'nombre'   => $nombre,
                'telefono' => $telefono,
            ]);

            if ($nombre === '' || $email === '' || $password === '') {
                $mensaje = "Nombre, correo y contraseña son obligatorios.";

                $this->logger->logEvent('cliente_registro_error_datos', [
                    'email'  => $email,
                    'nombre' => $nombre,
                ]);
            } else {
                $stmt = $this->pdo->prepare("SELECT id FROM clientes WHERE email = :email");
                $stmt->execute(['email' => $email]);

                if ($stmt->fetch()) {
                    $mensaje = "Ya existe una cuenta con ese correo.";

                    $this->logger->logEvent('cliente_registro_email_duplicado', [
                        'email' => $email,
                    ]);
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $this->pdo->prepare("
                        INSERT INTO clientes (nombre, email, password_hash, telefono, direccion)
                        VALUES (:nombre, :email, :password_hash, :telefono, :direccion)
                    ");

                    $ok = $stmt->execute([
                        'nombre'        => $nombre,
                        'email'         => $email,
                        'password_hash' => $hash,
                        'telefono'      => $telefono,
                        'direccion'     => $direccion
                    ]);

                    if ($ok) {
                        $mensaje = "Registro exitoso. Ahora puedes iniciar sesión.";

                        $this->logger->logEvent('cliente_registro_exitoso', [
                            'email'  => $email,
                            'nombre' => $nombre,
                        ]);
                    } else {
                        $mensaje = "Ocurrió un error al registrar.";

                        $this->logger->logEvent('cliente_registro_error_bd', [
                            'email'  => $email,
                            'nombre' => $nombre,
                        ]);
                    }
                }
            }
        }

        require __DIR__ . '/../views/cliente_registro.php';
    }

    /* Login de clientes */
    public function login()
    {
        $mensaje = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Log intento de login
            $this->logger->logEvent('cliente_login_intento', [
                'email' => $email,
            ]);

            if ($email === '' || $password === '') {
                $mensaje = "Ingresa tu correo y contraseña.";

                $this->logger->logEvent('cliente_login_error_datos', [
                    'email' => $email,
                ]);
            } else {
                $stmt = $this->pdo->prepare("SELECT id, nombre, password_hash FROM clientes WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($cliente && $cliente['password_hash'] && password_verify($password, $cliente['password_hash'])) {
                    $_SESSION['cliente_id']     = $cliente['id'];
                    $_SESSION['cliente_nombre'] = $cliente['nombre'];

                    $this->logger->logEvent('cliente_login_ok', [
                        'cliente_id' => $cliente['id'],
                        'email'      => $email,
                        'nombre'     => $cliente['nombre'],
                    ]);

                    header('Location: index.php?action=cliente-menu');
                    exit;
                } else {
                    $mensaje = "Correo o contraseña incorrectos.";

                    $this->logger->logEvent('cliente_login_error_credenciales', [
                        'email' => $email,
                    ]);
                }
            }
        }

        require __DIR__ . '/../views/cliente_login.php';
    }

    /* Menú del cliente (protegido) */
    public function menu()
    {
        if (!isset($_SESSION['cliente_id'])) {
            $this->logger->logEvent('cliente_menu_acceso_sin_sesion', []);
            header('Location: index.php?action=cliente-login');
            exit;
        }

        $nombre = $_SESSION['cliente_nombre'] ?? 'cliente';

        // Datos para WhatsApp
        $whatsappNumero = "15551744338";
        $mensajeWA      = urlencode("Hola, quiero hacer un pedido de empanadas colombianas.");
        $whatsappUrl    = "https://wa.me/$whatsappNumero?text=$mensajeWA";

        // Variables disponibles en la vista
        $clienteNombre = $nombre;
        $waUrl         = $whatsappUrl;

        // Log acceso al menú
        $this->logger->logEvent('cliente_menu_acceso', [
            'cliente_id' => $_SESSION['cliente_id'],
            'nombre'     => $nombre,
        ]);

        require __DIR__ . '/../views/cliente_menu.php';
    }

    public function logout()
    {
        $this->logger->logEvent('cliente_logout', [
            'cliente_id' => $_SESSION['cliente_id'] ?? null,
            'nombre'     => $_SESSION['cliente_nombre'] ?? null,
        ]);

        unset($_SESSION['cliente_id'], $_SESSION['cliente_nombre']);
        header('Location: index.php?action=home');
        exit;
    }
}
