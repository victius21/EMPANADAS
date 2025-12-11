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
                    // En la tabla productos de Supabase tenemos:
                    // id, nombre, descripcion, precio, categoria_id, disponible, ...
                    // Insertamos solo estos campos (disponible queda en TRUE por default)
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
        //    Usamos disponible AS activo para que la vista siga usando 'activo'
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

        // 3) Pasamos también los mensajes a la vista
        $flash = [
            'exito' => $mensajeExito,
            'error' => $mensajeError,
        ];

        include __DIR__ . '/../views/admin_productos.php';
    }
}
