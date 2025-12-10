<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 1) Conexión y controladores
require_once __DIR__ . '/../config/db.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/CocinaController.php';
require_once __DIR__ . '/../controllers/RepartidorController.php';
require_once __DIR__ . '/../controllers/InventarioController.php';

// 2) Crear la conexión PDO
$db  = new Database();
$pdo = $db->getConnection();

if (!$pdo) {
    die('Error: no se pudo conectar a la base de datos.');
}

// 3) Instanciar controladores
$authController       = new AuthController($pdo);
$adminController      = new AdminController($pdo);
$cocinaController     = new CocinaController($pdo);
$repartidorController = new RepartidorController($pdo);
$inventarioController = new InventarioController($pdo);

// 4) Router
$action = $_GET['action'] ?? 'login';

switch ($action) {

    /* LOGIN / LOGOUT */
    case 'login':
        $authController->login();
        break;

    case 'logout':
        $authController->logout();
        break;

    /* PANEL ADMIN */
    case 'admin':
        $adminController->dashboard();
        break;

    case 'admin-prod':
        $adminController->productos();
        break;

    /* COCINA */
    case 'cocina':
        $cocinaController->panel();
        break;

    case 'cocina-estado':
        $cocinaController->cambiarEstado();
        break;

    /* REPARTIDOR */
    case 'repartidor':
        $repartidorController->panel();
        break;

    case 'repartidor-entregar':
        $repartidorController->confirmarEntrega();
        break;

    /* INVENTARIO */
    case 'inventario':
        $inventarioController->panel();
        break;

    case 'inventario-corte':
        $inventarioController->corte();
        break;

    /* SI FALLA */
    default:
        http_response_code(404);
        echo "<h3>Página no encontrada</h3>";
        break;
}
