<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../config/db.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/CocinaController.php';
require_once __DIR__ . '/../controllers/RepartidorController.php';
require_once __DIR__ . '/../controllers/InventarioController.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {

    /* LOGIN / LOGOUT */
    case 'login':
        (new AuthController($pdo))->login();
        break;

    case 'logout':
        (new AuthController($pdo))->logout();
        break;

    /* PANEL ADMIN */
    case 'admin':
        (new AdminController($pdo))->dashboard();
        break;

    case 'admin-prod':
        (new AdminController($pdo))->productos();
        break;

    /* COCINA */
    case 'cocina':
        (new CocinaController($pdo))->panel();
        break;

    case 'cocina-estado':
        (new CocinaController($pdo))->cambiarEstado();
        break;

    /* REPARTIDOR */
    case 'repartidor':
        (new RepartidorController($pdo))->panel();
        break;

    case 'repartidor-entregar':
        (new RepartidorController($pdo))->confirmarEntrega();
        break;

    /* INVENTARIO */
    case 'inventario':
        (new InventarioController($pdo))->panel();
        break;

    /* CORTE DE CAJA */
    case 'inventario-corte':
        (new InventarioController($pdo))->corte();
        break;

    /* SI FALLA */
    default:
        http_response_code(404);
        echo "<h3>Página no encontrada</h3>";
        break;
}
