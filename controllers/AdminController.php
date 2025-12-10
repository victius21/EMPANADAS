<?php
// controllers/AdminController.php

class AdminController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function dashboard() {
        // Total de clientes
        $totalClientes = $this->pdo
            ->query("SELECT COUNT(*) FROM clientes")
            ->fetchColumn();

        // Total de pedidos
        $totalPedidos = $this->pdo
            ->query("SELECT COUNT(*) FROM pedidos")
            ->fetchColumn();

        // Total de ventas (suma de columna total en pedidos)
        $totalVentas = $this->pdo
            ->query("SELECT COALESCE(SUM(total), 0) FROM pedidos")
            ->fetchColumn();

        $data = [
            'totalClientes' => $totalClientes,
            'totalPedidos'  => $totalPedidos,
            'totalVentas'   => $totalVentas,
        ];

        include __DIR__ . '/../views/admin_dashboard.php';
    }

    public function productos() {
        $stmt = $this->pdo->query("
            SELECT id, nombre, descripcion, precio, activo
            FROM productos
            ORDER BY id DESC
        ");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/admin_productos.php';
    }
}
