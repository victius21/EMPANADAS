<?php
// controllers/RepartidorController.php

require_once __DIR__ . '/../config/MongoLogger.php';

class RepartidorController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Panel del repartidor:
     * - Lista pedidos en 'preparando' o 'en_camino'
     */
    public function panel() {
        $sql = "
            SELECT 
                p.id,
                p.nombre_cliente,
                p.direccion,
                p.telefono,
                p.estado,
                p.fecha,
                COALESCE(
                    STRING_AGG(
                        (pd.cantidad::text || 'x ' || pr.nombre),
                        ', '
                    ),
                    ''
                ) AS detalle_productos
            FROM pedidos p
            LEFT JOIN pedido_detalle pd ON pd.id_pedido = p.id
            LEFT JOIN productos pr ON pr.id = pd.id_producto
            WHERE p.estado IN ('preparando', 'en_camino')
            GROUP BY p.id, p.nombre_cliente, p.direccion, p.telefono, p.estado, p.fecha
            ORDER BY p.fecha ASC;
        ";

        $stmt    = $this->pdo->query($sql);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/repartidor_panel.php';
    }

    /**
     * Marcar pedido como entregado
     */
    public function confirmarEntrega() {
        $id = $_POST['id'] ?? null;

        if ($id) {
            // 1) Leer estado anterior
            $stmt = $this->pdo->prepare("SELECT estado FROM pedidos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $estadoAnterior = $stmt->fetchColumn();

            // 2) Actualizar a 'entregado'
            $stmt = $this->pdo->prepare("
                UPDATE pedidos
                SET estado = 'entregado'
                WHERE id = :id
            ");
            $stmt->execute([':id' => $id]);

            // 3) Log en Mongo
            $mongo = new MongoLogger();
            $mongo->logCambioEstadoPedido((int)$id, $estadoAnterior ? (string)$estadoAnterior : null, 'entregado');
        }

        header("Location: index.php?action=repartidor");
        exit;
    }
}
