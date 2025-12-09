<?php
// controllers/AdminController.php

class AdminController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function auth() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
    }

    // DASHBOARD
    public function dashboard() {
        $this->auth();

        // Variables que usa admin_dashboard.php
        $totalClientes = (int) $this->pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
        $totalPedidos  = (int) $this->pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
        $totalVentas   = (float) $this->pdo->query("SELECT IFNULL(SUM(total),0) FROM pedidos")->fetchColumn();

        include __DIR__ . '/../views/admin_dashboard.php';
    }

    // GESTIONAR PRODUCTOS
    public function productos() {
        $this->auth();

        // Crear producto nuevo
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $precio = $_POST['precio'] ?? 0;
            $desc   = $_POST['descripcion'] ?? '';

            if ($nombre !== '' && $precio !== '') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO productos (nombre, descripcion, precio) 
                    VALUES (:n, :d, :p)
                ");
                $stmt->execute([
                    ':n' => $nombre,
                    ':d' => $desc,
                    ':p' => $precio,
                ]);
            }
        }

        // Listar productos activos
        $productos = $this->pdo
            ->query("SELECT * FROM productos WHERE activo = 1 ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/admin_productos.php';
    }
}
