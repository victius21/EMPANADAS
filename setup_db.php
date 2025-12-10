<?php
// setup_db.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';

$db  = new Database();
$pdo = $db->getConnection();

if (!$pdo) {
    die('No se pudo conectar a la base de datos.');
}

try {
    // --- TABLA USUARIOS (coincidiendo con AuthController) ---
    $sql = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id SERIAL PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        correo VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        rol VARCHAR(20) NOT NULL,      -- 'admin', 'cocina', 'repartidor'
        activo BOOLEAN DEFAULT TRUE
    );

    -- Usuario admin de prueba: admin@local / 1234 (TEXTO PLANO)
    INSERT INTO usuarios (nombre, correo, password_hash, rol, activo)
    VALUES ('Administrador', 'admin@local', '1234', 'admin', TRUE)
    ON CONFLICT (correo) DO NOTHING;

    -- TABLA PRODUCTOS
    CREATE TABLE IF NOT EXISTS productos (
        id SERIAL PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        descripcion TEXT,
        precio NUMERIC(10,2) NOT NULL,
        activo BOOLEAN DEFAULT TRUE
    );

    -- TABLA PEDIDOS
    CREATE TABLE IF NOT EXISTS pedidos (
        id SERIAL PRIMARY KEY,
        nombre_cliente VARCHAR(100) NOT NULL,
        direccion TEXT,
        telefono VARCHAR(20),
        estado VARCHAR(20) DEFAULT 'pendiente',  -- 'pendiente','preparando','en_camino','entregado'
        fecha TIMESTAMP DEFAULT NOW()
    );

    -- TABLA DETALLE DE PEDIDOS
    CREATE TABLE IF NOT EXISTS pedido_detalle (
        id SERIAL PRIMARY KEY,
        id_pedido INT REFERENCES pedidos(id) ON DELETE CASCADE,
        id_producto INT REFERENCES productos(id),
        cantidad INT NOT NULL,
        subtotal NUMERIC(10,2) NOT NULL
    );
    ";

    $pdo->exec($sql);
    echo "✅ Tablas creadas / actualizadas y usuario admin agregado.<br>";
    echo "Puedes iniciar sesión con:<br>";
    echo "Correo: <b>admin@local</b><br>";
    echo "Contraseña: <b>1234</b><br>";

} catch (PDOException $e) {
    echo "❌ Error al crear tablas: " . $e->getMessage();
}
