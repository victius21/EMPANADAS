<?php
require_once __DIR__ . '/config/db.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // SQL para crear tablas
    $sql = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id SERIAL PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        usuario VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        rol VARCHAR(20) NOT NULL  -- 'admin', 'cocina', 'repartidor'
    );

    CREATE TABLE IF NOT EXISTS productos (
        id SERIAL PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        descripcion TEXT,
        precio NUMERIC(10,2) NOT NULL,
        activo BOOLEAN DEFAULT TRUE
    );

    CREATE TABLE IF NOT EXISTS pedidos (
        id SERIAL PRIMARY KEY,
        nombre_cliente VARCHAR(100) NOT NULL,
        direccion TEXT,
        telefono VARCHAR(20),
        estado VARCHAR(20) DEFAULT 'pendiente',  -- 'pendiente', 'preparando', 'en_camino', 'entregado'
        fecha TIMESTAMP DEFAULT NOW()
    );

    CREATE TABLE IF NOT EXISTS pedido_detalle (
        id SERIAL PRIMARY KEY,
        id_pedido INT REFERENCES pedidos(id) ON DELETE CASCADE,
        id_producto INT REFERENCES productos(id),
        cantidad INT NOT NULL,
        subtotal NUMERIC(10,2) NOT NULL
    );
    ";

    $conn->exec($sql);
    echo "✅ Tablas creadas / actualizadas correctamente.";
} catch (PDOException $e) {
    echo "❌ Error al crear tablas: " . $e->getMessage();
}
