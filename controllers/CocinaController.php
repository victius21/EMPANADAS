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
     * - Lista pedidos 'pendiente' o 'preparando'
     * - Muestra detalle de productos por pedido
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
            WHERE p.estado IN ('pendiente', 'preparando')
            GROUP BY p.id, p.nombre_cliente, p.direccion, p.telefono, p.estado, p.fecha
            ORDER BY p.fecha ASC;
        ";

        $stmt    = $this->pdo->query($sql);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/cocina_panel.php';
    }

    /**
     * Cambiar estado de un pedido
     */
    public function cambiarEstado() {
        $id     = $_POST['id']     ?? null;
        $estado = $_POST['estado'] ?? null;

        if ($id && $estado) {
            // 1) Leer estado anterior
            $stmt = $this->pdo->prepare("SELECT estado FROM pedidos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $estadoAnterior = $stmt->fetchColumn();

            // 2) Actualizar al nuevo estado
            $stmt = $this->pdo->prepare("
                UPDATE pedidos
                SET estado = :estado
                WHERE id = :id
            ");
            $stmt->execute([
                ':estado' => $estado,
                ':id'     => $id,
            ]);

            // 3) Log en Mongo del cambio de estado
            $mongo = new MongoLogger();
            $mongo->logCambioEstadoPedido((int)$id, $estadoAnterior ? (string)$estadoAnterior : null, (string)$estado);
        }

        header("Location: index.php?action=cocina");
        exit;
    }
}
