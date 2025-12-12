<?php
// controllers/AdminController.php

class AdminController {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Dashboard: totales generales
     */
    public function dashboard() {
        // Total de clientes
        $totalClientes = $this->pdo
            ->query("SELECT COUNT(*) FROM clientes")
            ->fetchColumn();

        // Total de pedidos
        $totalPedidos = $this->pdo
            ->query("SELECT COUNT(*) FROM pedidos")
            ->fetchColumn();

        // Total de ventas (suma de columna total en pedidos)
        $totalVentas = $this->pdo
            ->query("SELECT COALESCE(SUM(total), 0) FROM pedidos")
            ->fetchColumn();

        $data = [
            'totalClientes' => $totalClientes,
            'totalPedidos'  => $totalPedidos,
            'totalVentas'   => $totalVentas,
        ];

        include __DIR__ . '/../views/admin_dashboard.php';
    }

    /**
     * Gestión de productos:
     * - Si viene POST, inserta un nuevo producto en la BD (Supabase)
     * - Luego lista todos los productos
     */
    public function productos() {
        $mensajeExito = null;
        $mensajeError = null;

        // 1) Si enviaron el formulario, guardamos el producto
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre      = trim($_POST['nombre']      ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precioRaw   = trim($_POST['precio']      ?? '');

            // Convertir precio a número (float)
            $precio = $precioRaw !== '' ? (float)$precioRaw : null;

            if ($nombre === '' || $precio === null) {
                $mensajeError = "Nombre y precio son obligatorios.";
            } else {
                try {
                    $stmt = $this->pdo->prepare("
                        INSERT INTO productos (nombre, descripcion, precio)
                        VALUES (:nombre, :descripcion, :precio)
                    ");

                    $stmt->execute([
                        ':nombre'      => $nombre,
                        ':descripcion' => $descripcion,
                        ':precio'      => $precio,
                    ]);

                    $mensajeExito = "Producto guardado correctamente.";
                } catch (PDOException $e) {
                    $mensajeError = "Error al guardar el producto: " . $e->getMessage();
                }
            }
        }

        // 2) Siempre listamos productos para mostrarlos en la tabla
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    id,
                    nombre,
                    descripcion,
                    precio,
                    disponible AS activo
                FROM productos
                ORDER BY id DESC
            ");
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $productos   = [];
            $mensajeError = "Error al cargar productos: " . $e->getMessage();
        }

        $flash = [
            'exito' => $mensajeExito,
            'error' => $mensajeError,
        ];

        include __DIR__ . '/../views/admin_productos.php';
    }

    // ============================================================
    // ✅ EDITAR PRODUCTO (FORM)
    // ============================================================
    public function productoEdit() {
        $id = (int)($_GET['id'] ?? 0);

        try {
            $stmt = $this->pdo->prepare("
                SELECT id, nombre, descripcion, precio, disponible
                FROM productos
                WHERE id = :id
            ");
            $stmt->execute([':id' => $id]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                header("Location: index.php?action=admin-prod");
                exit;
            }

            include __DIR__ . '/../views/admin_producto_edit.php';

        } catch (PDOException $e) {
            header("Location: index.php?action=admin-prod");
            exit;
        }
    }

    // ============================================================
    // ✅ ACTUALIZAR PRODUCTO (UPDATE)
    // ============================================================
    public function productoUpdate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=admin-prod");
            exit;
        }

        $id          = (int)($_POST['id'] ?? 0);
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precioRaw   = trim($_POST['precio'] ?? '');
        $precio      = $precioRaw !== '' ? (float)$precioRaw : null;

        // checkbox: si viene marcado -> 1, si no -> 0
        $disponible  = isset($_POST['disponible']) ? 1 : 0;

        if ($id <= 0 || $nombre === '' || $precio === null) {
            header("Location: index.php?action=admin-prod");
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE productos
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio,
                    disponible = :disponible
                WHERE id = :id
            ");
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':disponible' => $disponible,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            // opcional: log
        }

        header("Location: index.php?action=admin-prod");
        exit;
    }

    // ============================================================
    // ✅ ELIMINAR PRODUCTO (DELETE o borrado lógico)
    // ============================================================
    public function productoDelete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=admin-prod");
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);

        try {
            // DELETE REAL:
            $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id = :id");
            $stmt->execute([':id' => $id]);

        } catch (PDOException $e) {
            // ✅ Si falla por FK, haz borrado lógico:
            $stmt = $this->pdo->prepare("UPDATE productos SET disponible = FALSE WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }

        header("Location: index.php?action=admin-prod");
        exit;
    }
}
