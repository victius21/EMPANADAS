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
require_once __DIR__ . '/../controllers/ClienteController.php';

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
$clienteController    = new ClienteController($pdo);

// 4) Router
$action = $_GET['action'] ?? 'home';

switch ($action) {

    /* HOME CLIENTES */
    case 'home':
        $clienteController->home();
        break;

    /* LOGIN / REGISTRO CLIENTES */
    case 'cliente-login':
        $clienteController->login();
        break;

    case 'cliente-registro':
        $clienteController->registro();
        break;

    case 'cliente-menu':
        $clienteController->menu();
        break;

    case 'cliente-logout':
        $clienteController->logout();
        break;

    /* LOGIN / LOGOUT PERSONAL (admin/cocina/repartidor) */
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

    case 'admin-prod-edit':
        $adminController->productoEdit();
        break;

    case 'admin-prod-update':
        $adminController->productoUpdate();
        break;

    case 'admin-prod-delete':
        $adminController->productoDelete();
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

    /* INVENTARIO (productos/ventas) */
    case 'inventario':
        $inventarioController->panel();
        break;

    case 'inventario-corte':
        $inventarioController->corte();
        break;

    /* INSUMOS (nuevo módulo) */
    case 'insumos':
        $inventarioController->insumos();
        break;

    case 'insumo-store':
        $inventarioController->insumoStore();
        break;

    case 'proveedor-store':
        $inventarioController->proveedorStore();
        break;

    case 'movimiento-store':
        $inventarioController->movimientoStore();
        break;

    /* SI FALLA */
    default:
        http_response_code(404);
        echo "<h3>Página no encontrada</h3>";
        break;
}
