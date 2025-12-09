<?php
// controllers/CocinaController.php

class CocinaController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function auth() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['rol'], ['cocina','admin'])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    public function panel() {
        $this->auth();

        $sql = "SELECT 
                    p.id,
                    p.estado,
                    p.total,
                    p.fecha_creacion,
                    c.nombre AS cliente_nombre,
                    c.numero_whatsapp,
                    GROUP_CONCAT(CONCAT(pd.cantidad,'x ',pr.nombre) SEPARATOR ' | ') AS items
                FROM pedidos p
                JOIN clientes c       ON c.id = p.cliente_id
                JOIN pedido_detalle pd ON pd.pedido_id = p.id
                JOIN productos pr      ON pr.id = pd.producto_id
                WHERE p.estado IN ('pendiente','en_preparacion','listo')
                GROUP BY p.id
                ORDER BY p.fecha_creacion ASC";

        $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        // Separar por estado para mostrar en columnas
        $pedidosPendientes   = [];
        $pedidosPreparacion  = [];
        $pedidosListos       = [];

        foreach ($rows as $p) {
            if ($p['estado'] === 'pendiente') {
                $pedidosPendientes[] = $p;
            } elseif ($p['estado'] === 'en_preparacion') {
                $pedidosPreparacion[] = $p;
            } elseif ($p['estado'] === 'listo') {
                $pedidosListos[] = $p;
            }
        }

        include __DIR__ . '/../views/cocina_panel.php';
    }

    public function cambiarEstado() {
        $this->auth();

        $id     = $_POST['id'] ?? null;
        $estado = $_POST['estado'] ?? null;

        if ($id && $estado && in_array($estado, ['pendiente','en_preparacion','listo','cancelado'])) {
            $stmt = $this->pdo->prepare("UPDATE pedidos SET estado = :e WHERE id = :id");
            $stmt->execute([':e' => $estado, ':id' => $id]);
        }

        header("Location: index.php?action=cocina");
        exit;
    }
}
