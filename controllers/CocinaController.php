<?php
// controllers/CocinaController.php

require_once __DIR__ . '/../config/MongoLogger.php';

class CocinaController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Panel principal de cocina:
     * - Lista pedidos en estado 'pendiente' o 'en_preparacion'
     * - Muestra detalle de productos por pedido
     *
     * Ahora se ajusta al modelo:
     * pedidos (cliente_id, estado_id, direccion_entrega, fecha_pedido, total, ...)
     * clientes (nombre, telefono, direccion)
     * estados_pedido (codigo, nombre)
     * detalle_pedido (pedido_id, producto_id, cantidad, subtotal)
     */
    public function panel() {
        $sql = "
            SELECT 
                p.id,
                COALESCE(c.nombre, 'Cliente sin nombre') AS nombre_cliente,
                COALESCE(p.direccion_entrega, c.direccion) AS direccion,
                c.telefono,
                e.codigo AS estado,
                p.fecha_pedido AS fecha,
                COALESCE(
                    STRING_AGG(
                        (dp.cantidad::text || 'x ' || pr.nombre),
                        ', '
                    ),
                    ''
                ) AS detalle_productos
            FROM pedidos p
            LEFT JOIN clientes c       ON c.id = p.cliente_id
            LEFT JOIN estados_pedido e ON e.id = p.estado_id
            LEFT JOIN detalle_pedido dp ON dp.pedido_id = p.id
            LEFT JOIN productos pr      ON pr.id = dp.producto_id
            WHERE e.codigo IN ('pendiente', 'en_preparacion')
            GROUP BY 
                p.id, 
                c.nombre, 
                c.direccion, 
                c.telefono, 
                e.codigo, 
                p.fecha_pedido,
                p.direccion_entrega
            ORDER BY p.fecha_pedido ASC;
        ";

        $stmt    = $this->pdo->query($sql);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/cocina_panel.php';
    }

    /**
     * Cambiar estado de un pedido
     *
     * Antes actualizaba directamente un campo texto "estado" en pedidos.
     * Ahora usamos la tabla estados_pedido y guardamos estado_id.
     *
     * Se espera que en el formulario se envíe en $_POST['estado']
     * el código del estado (ej: 'pendiente', 'en_preparacion', 'listo', 'en_camino', 'entregado', 'cancelado').
     */
    public function cambiarEstado() {
        $id     = $_POST['id']     ?? null;
        $estado = $_POST['estado'] ?? null;  // aquí esperamos el código del estado (pendiente, en_preparacion, etc.)

        if ($id && $estado) {
            // 1) Leer estado anterior (como código) mediante join a estados_pedido
            $stmt = $this->pdo->prepare("
                SELECT e.codigo
                FROM pedidos p
                LEFT JOIN estados_pedido e ON e.id = p.estado_id
                WHERE p.id = :id
            ");
            $stmt->execute([':id' => $id]);
            $estadoAnterior = $stmt->fetchColumn();

            // 2) Buscar el id del nuevo estado a partir de su código
            $stmtEstado = $this->pdo->prepare("
                SELECT id 
                FROM estados_pedido 
                WHERE codigo = :codigo
                LIMIT 1
            ");
            $stmtEstado->execute([':codigo' => $estado]);
            $estadoId = $stmtEstado->fetchColumn();

            if ($estadoId) {
                // 3) Actualizar pedidos.estado_id
                $stmtUpdate = $this->pdo->prepare("
                    UPDATE pedidos
                    SET estado_id = :estado_id
                    WHERE id = :id
                ");
                $stmtUpdate->execute([
                    ':estado_id' => $estadoId,
                    ':id'        => $id,
                ]);

                // 4) Log en Mongo del cambio de estado (guardamos códigos)
                $mongo = new MongoLogger();
                $mongo->logCambioEstadoPedido(
                    (int)$id,
                    $estadoAnterior ? (string)$estadoAnterior : null,
                    (string)$estado
                );
            }
        }

        header("Location: index.php?action=cocina");
        exit;
    }
}
