<?php
// controllers/RepartidorController.php

require_once __DIR__ . '/../config/MongoLogger.php';

class RepartidorController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function panel() {
        $sql = "
            SELECT 
                p.id,
                COALESCE(c.nombre, 'Cliente sin nombre') AS nombre_cliente,
                COALESCE(p.direccion_entrega, c.direccion) AS direccion,
                c.telefono,
                ep.codigo AS estado,
                p.fecha_pedido AS fecha,
                COALESCE(
                    STRING_AGG(
                        (dp.cantidad::text || 'x ' || pr.nombre),
                        ', ' ORDER BY pr.nombre
                    ),
                    ''
                ) AS detalle_productos
            FROM pedidos p
            JOIN clientes c ON c.id = p.cliente_id
            JOIN estados_pedido ep ON ep.id = p.estado_id
            LEFT JOIN detalle_pedido dp ON dp.pedido_id = p.id
            LEFT JOIN productos pr ON pr.id = dp.producto_id
            WHERE ep.codigo IN ('preparando', 'en_camino')
            GROUP BY 
                p.id, c.nombre, p.direccion_entrega, c.direccion, c.telefono, ep.codigo, p.fecha_pedido
            ORDER BY p.fecha_pedido ASC;
        ";

        $stmt    = $this->pdo->query($sql);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/repartidor_panel.php';
    }

    public function confirmarEntrega() {
        $id = $_POST['id'] ?? null;

        if ($id) {
            // estado anterior
            $stmt = $this->pdo->prepare("
                SELECT ep.codigo
                FROM pedidos p
                JOIN estados_pedido ep ON ep.id = p.estado_id
                WHERE p.id = :id
            ");
            $stmt->execute([':id' => $id]);
            $estadoAnterior = $stmt->fetchColumn();

            // id de entregado
            $stmt = $this->pdo->prepare("
                SELECT id
                FROM estados_pedido
                WHERE codigo = 'entregado'
                LIMIT 1
            ");
            $stmt->execute();
            $entregadoId = $stmt->fetchColumn();

            if ($entregadoId) {
                $stmt = $this->pdo->prepare("
                    UPDATE pedidos
                    SET estado_id = :estado_id
                    WHERE id = :id
                ");
                $stmt->execute([':estado_id' => $entregadoId, ':id' => $id]);

                $mongo = new MongoLogger();
                $mongo->logCambioEstadoPedido(
                    (int)$id,
                    $estadoAnterior ? (string)$estadoAnterior : null,
                    'entregado'
                );
            }
        }

        header("Location: index.php?action=repartidor");
        exit;
    }
}
