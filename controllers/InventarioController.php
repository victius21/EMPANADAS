<?php
// controllers/InventarioController.php

class InventarioController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Panel de inventario:
     * - Lista productos y cuánto se ha vendido de cada uno
     */
    public function panel() {
        $sql = "
            SELECT 
                pr.id,
                pr.nombre,
                pr.descripcion,
                pr.precio,
                pr.activo,
                COALESCE(SUM(pd.cantidad), 0) AS total_vendido
            FROM productos pr
            LEFT JOIN pedido_detalle pd ON pd.id_producto = pr.id
            GROUP BY pr.id, pr.nombre, pr.descripcion, pr.precio, pr.activo
            ORDER BY pr.nombre ASC;
        ";

        $stmt      = $this->pdo->query($sql);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/inventario_panel.php';
    }

    /**
     * Corte de caja:
     * - Resumen general de pedidos y ventas
     * - Ventas agrupadas por día
     */
    public function corte() {
        // Resumen general
        $resumen = $this->pdo->query("
            SELECT 
                COUNT(*) AS total_pedidos,
                COALESCE(SUM(total), 0) AS total_ventas
            FROM pedidos
        ")->fetch(PDO::FETCH_ASSOC);

        // Ventas por día
        $stmt = $this->pdo->query("
            SELECT 
                DATE(fecha) AS dia,
                COUNT(*) AS pedidos_dia,
                COALESCE(SUM(total), 0) AS ventas_dia
            FROM pedidos
            GROUP BY DATE(fecha)
            ORDER BY dia DESC
        ");
        $cortes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'resumen' => $resumen,
            'cortes'  => $cortes,
        ];

        include __DIR__ . '/../views/inventario_corte.php';
    }
}
