<?php
// controllers/InventarioController.php

class InventarioController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function auth() {
        // Solo admin por ahora
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
    }

    // PANEL DE INVENTARIO (insumos, proveedores, movimientos)
    public function panel() {
        $this->auth();

        // Crear insumo nuevo
        if (isset($_POST['accion']) && $_POST['accion'] === 'nuevo_insumo') {
            $nombre = $_POST['nombre'] ?? '';
            $unidad = $_POST['unidad'] ?? '';
            $stock_minimo = $_POST['stock_minimo'] ?? 0;
            $costo_unitario = $_POST['costo_unitario'] ?? 0;

            if ($nombre !== '' && $unidad !== '') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO insumos (nombre, unidad, stock_minimo, costo_unitario)
                    VALUES (:n, :u, :sm, :cu)
                ");
                $stmt->execute([
                    ':n'  => $nombre,
                    ':u'  => $unidad,
                    ':sm' => $stock_minimo,
                    ':cu' => $costo_unitario,
                ]);
            }
        }

        // Registrar movimiento de inventario
        if (isset($_POST['accion']) && $_POST['accion'] === 'movimiento') {
            $insumo_id = $_POST['insumo_id'] ?? null;
            $tipo      = $_POST['tipo'] ?? null;
            $cantidad  = $_POST['cantidad'] ?? 0;
            $costo_u   = $_POST['costo_unitario'] ?? 0;
            $nota      = $_POST['nota'] ?? null;
            $prov_id   = $_POST['proveedor_id'] ?? null;

            if ($insumo_id && $tipo && $cantidad > 0) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO insumo_movimientos (insumo_id, tipo, cantidad, costo_unitario, proveedor_id, nota)
                    VALUES (:i, :t, :c, :cu, :p, :n)
                ");
                $stmt->execute([
                    ':i'  => $insumo_id,
                    ':t'  => $tipo,
                    ':c'  => $cantidad,
                    ':cu' => $costo_u,
                    ':p'  => $prov_id ?: null,
                    ':n'  => $nota,
                ]);

                // Actualizar stock_actual en insumos
                if ($tipo === 'entrada') {
                    $this->pdo->prepare("UPDATE insumos SET stock_actual = stock_actual + :c WHERE id = :i")
                        ->execute([':c' => $cantidad, ':i' => $insumo_id]);
                } else { // salida
                    $this->pdo->prepare("UPDATE insumos SET stock_actual = stock_actual - :c WHERE id = :i")
                        ->execute([':c' => $cantidad, ':i' => $insumo_id]);
                }
            }
        }

        // Crear proveedor rápido
        if (isset($_POST['accion']) && $_POST['accion'] === 'nuevo_proveedor') {
            $nombre = $_POST['nombre'] ?? '';
            $tel    = $_POST['telefono'] ?? '';
            $notas  = $_POST['notas'] ?? '';
            if ($nombre !== '') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO proveedores (nombre, telefono, notas)
                    VALUES (:n, :t, :no)
                ");
                $stmt->execute([':n'=>$nombre, ':t'=>$tel, ':no'=>$notas]);
            }
        }

        // Datos para la vista
        $insumos = $this->pdo->query("
            SELECT *,
                   (stock_actual <= stock_minimo) AS bajo
            FROM insumos
            WHERE activo = 1
            ORDER BY nombre
        ")->fetchAll(PDO::FETCH_ASSOC);

        $proveedores = $this->pdo->query("
            SELECT * FROM proveedores ORDER BY nombre
        ")->fetchAll(PDO::FETCH_ASSOC);

        $movimientosRecientes = $this->pdo->query("
            SELECT m.*, i.nombre AS insumo_nombre, p.nombre AS proveedor_nombre
            FROM insumo_movimientos m
            JOIN insumos i     ON i.id = m.insumo_id
            LEFT JOIN proveedores p ON p.id = m.proveedor_id
            ORDER BY m.fecha DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/inventario_panel.php';
    }

    // PANEL DE CORTE DE CAJA + GASTOS
    public function corte() {
        $this->auth();

        // Registrar gasto
        if (isset($_POST['accion']) && $_POST['accion'] === 'gasto') {
            $fecha    = $_POST['fecha'] ?? date('Y-m-d');
            $concepto = $_POST['concepto'] ?? '';
            $cat      = $_POST['categoria'] ?? '';
            $monto    = $_POST['monto'] ?? 0;

            if ($concepto !== '' && $monto > 0) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO gastos_negocio (fecha, concepto, categoria, monto)
                    VALUES (:f, :c, :ca, :m)
                ");
                $stmt->execute([
                    ':f'=>$fecha, ':c'=>$concepto, ':ca'=>$cat, ':m'=>$monto
                ]);
            }
        }

        // Registrar corte diario
        if (isset($_POST['accion']) && $_POST['accion'] === 'corte') {
            $fecha    = $_POST['fecha'] ?? date('Y-m-d');
            $efectivo = $_POST['efectivo'] ?? 0;
            $tarjeta  = $_POST['tarjeta'] ?? 0;
            $impuestos = $_POST['impuestos'] ?? 0;
            $gastos   = $_POST['gastos'] ?? 0;
            $notas    = $_POST['notas'] ?? '';

            $ganancia = ($efectivo + $tarjeta) - $impuestos - $gastos;

            $stmt = $this->pdo->prepare("
                INSERT INTO corte_caja (fecha, efectivo, tarjeta, impuestos, gastos, ganancia_neta, notas)
                VALUES (:f, :e, :t, :i, :g, :gn, :n)
            ");
            $stmt->execute([
                ':f'=>$fecha, ':e'=>$efectivo, ':t'=>$tarjeta,
                ':i'=>$impuestos, ':g'=>$gastos, ':gn'=>$ganancia, ':n'=>$notas
            ]);
        }

        // Datos para mostrar
        $gastosRecientes = $this->pdo->query("
            SELECT * FROM gastos_negocio
            ORDER BY fecha DESC, id DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);

        $cortesRecientes = $this->pdo->query("
            SELECT * FROM corte_caja
            ORDER BY fecha DESC, id DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/inventario_corte.php';
    }
}
