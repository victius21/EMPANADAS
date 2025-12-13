<?php
// controllers/InventarioController.php

class InventarioController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function panel() {
        $sql = "
            SELECT 
                pr.id,
                pr.nombre,
                pr.descripcion,
                pr.precio,
                pr.disponible,
                COALESCE(SUM(dp.cantidad), 0) AS total_vendido
            FROM productos pr
            LEFT JOIN detalle_pedido dp ON dp.producto_id = pr.id
            GROUP BY pr.id, pr.nombre, pr.descripcion, pr.precio, pr.disponible
            ORDER BY pr.nombre ASC;
        ";

        $stmt      = $this->pdo->query($sql);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/inventario_panel.php';
    }

    public function corte() {
        $resumen = $this->pdo->query("
            SELECT 
                COUNT(*) AS total_pedidos,
                COALESCE(SUM(total), 0) AS total_ventas
            FROM pedidos
        ")->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->query("
            SELECT 
                DATE(fecha_pedido) AS dia,
                COUNT(*) AS pedidos_dia,
                COALESCE(SUM(total), 0) AS ventas_dia
            FROM pedidos
            GROUP BY DATE(fecha_pedido)
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
