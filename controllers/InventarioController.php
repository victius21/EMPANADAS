<?php
// controllers/InventarioController.php

class InventarioController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // =========================================================
    // INVENTARIO (productos / ventas) - (tu módulo existente)
    // =========================================================
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

    // =========================================================
    // INSUMOS (nuevo módulo)
    // =========================================================
    public function insumos() {
        // lista insumos
        $stmt = $this->pdo->query("
            SELECT id, nombre, unidad, stock_actual, stock_minimo, costo_unitario
            FROM insumos
            ORDER BY nombre ASC
        ");
        $insumos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // lista proveedores
        $stmt = $this->pdo->query("
            SELECT id, nombre
            FROM proveedores
            ORDER BY nombre ASC
        ");
        $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // movimientos recientes
        $stmt = $this->pdo->query("
            SELECT 
              m.id, m.tipo, m.cantidad, m.costo_unitario, m.nota, m.creado_en,
              i.nombre AS insumo,
              p.nombre AS proveedor
            FROM movimientos_insumos m
            JOIN insumos i ON i.id = m.insumo_id
            LEFT JOIN proveedores p ON p.id = m.proveedor_id
            ORDER BY m.creado_en DESC
            LIMIT 20
        ");
        $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $flash = $_SESSION['flash'] ?? ['exito'=>null,'error'=>null];
        unset($_SESSION['flash']);

        include __DIR__ . '/../views/insumos_panel.php';
    }

    public function insumoStore() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=insumos");
            exit;
        }

        $nombre       = trim($_POST['nombre'] ?? '');
        $unidad       = trim($_POST['unidad'] ?? '');
        $stock_minimo = (int)($_POST['stock_minimo'] ?? 0);
        $costo_unit   = (float)($_POST['costo_unitario'] ?? 0);

        if ($nombre === '' || $unidad === '') {
            $_SESSION['flash'] = ['exito'=>null,'error'=>'Nombre y unidad son obligatorios.'];
            header("Location: index.php?action=insumos");
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO insumos (nombre, unidad, stock_minimo, costo_unitario)
                VALUES (:n, :u, :sm, :cu)
            ");
            $stmt->execute([
                ':n'  => $nombre,
                ':u'  => $unidad,
                ':sm' => $stock_minimo,
                ':cu' => $costo_unit
            ]);

            $_SESSION['flash'] = ['exito'=>'Insumo guardado ✅', 'error'=>null];
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['exito'=>null,'error'=>'Error al guardar insumo: '.$e->getMessage()];
        }

        header("Location: index.php?action=insumos");
        exit;
    }

    public function proveedorStore() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=insumos");
            exit;
        }

        $nombre   = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $notas    = trim($_POST['notas'] ?? '');

        if ($nombre === '') {
            $_SESSION['flash'] = ['exito'=>null,'error'=>'Nombre de proveedor es obligatorio.'];
            header("Location: index.php?action=insumos");
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO proveedores (nombre, telefono, notas)
                VALUES (:n, :t, :no)
            ");
            $stmt->execute([
                ':n'  => $nombre,
                ':t'  => $telefono,
                ':no' => $notas
            ]);

            $_SESSION['flash'] = ['exito'=>'Proveedor guardado ✅', 'error'=>null];
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['exito'=>null,'error'=>'Error al guardar proveedor: '.$e->getMessage()];
        }

        header("Location: index.php?action=insumos");
        exit;
    }

    public function movimientoStore() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=insumos");
            exit;
        }

        $insumo_id    = (int)($_POST['insumo_id'] ?? 0);
        $tipo         = trim($_POST['tipo'] ?? 'entrada');
        $cantidad     = (float)($_POST['cantidad'] ?? 0);
        $costo_unit   = ($_POST['costo_unitario'] ?? '') !== '' ? (float)$_POST['costo_unitario'] : null;
        $proveedor_id = ($_POST['proveedor_id'] ?? '') !== '' ? (int)$_POST['proveedor_id'] : null;
        $nota         = trim($_POST['nota'] ?? '');

        if ($insumo_id <= 0 || $cantidad <= 0 || !in_array($tipo, ['entrada','salida'], true)) {
            $_SESSION['flash'] = ['exito'=>null,'error'=>'Movimiento inválido. Revisa insumo/tipo/cantidad.'];
            header("Location: index.php?action=insumos");
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            // 1) Guardar movimiento
            $stmt = $this->pdo->prepare("
                INSERT INTO movimientos_insumos (insumo_id, tipo, cantidad, costo_unitario, proveedor_id, nota)
                VALUES (:i, :t, :c, :cu, :p, :n)
            ");
            $stmt->execute([
                ':i'  => $insumo_id,
                ':t'  => $tipo,
                ':c'  => $cantidad,
                ':cu' => $costo_unit,
                ':p'  => $proveedor_id,
                ':n'  => $nota
            ]);

            // 2) Actualizar stock
            $delta = ($tipo === 'entrada') ? $cantidad : (-1 * $cantidad);

            $stmt = $this->pdo->prepare("
                UPDATE insumos
                SET stock_actual = stock_actual + :d
                WHERE id = :id
            ");
            $stmt->execute([':d' => $delta, ':id' => $insumo_id]);

            $this->pdo->commit();

            $_SESSION['flash'] = ['exito'=>'Movimiento guardado ✅', 'error'=>null];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $_SESSION['flash'] = ['exito'=>null,'error'=>'Error al guardar movimiento: '.$e->getMessage()];
        }

        header("Location: index.php?action=insumos");
        exit;
    }
}
