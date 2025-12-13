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

    /**
     * Corte de caja:
     * - Resumen general de pedidos y ventas
     * - Ventas agrupadas por día
     */
    public function corte() {
        $resumen = $this->pdo->query("
            SELECT 
