<?php
// config/db.php

class Database {
    private $host = "dpg-d4rtqo3uibrs73clll4g-a";
    private $port = "5432";
    private $db_name = "empanadas_db_uxos";
    private $username = "empanadas_db_uxos_user";
    private $password = "pKXCkmhx1hwOxTXSnfWXdpMXagwvxxIF";
    public  $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Crear esquema si no existe
            $this->createSchema($this->conn);

        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }

    private function createSchema(PDO $pdo) {
        $sql = "
        -- USUARIOS
        CREATE TABLE IF NOT EXISTS usuarios (
            id SERIAL PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            correo VARCHAR(100) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            rol VARCHAR(20) NOT NULL,
            activo BOOLEAN DEFAULT TRUE
        );

        INSERT INTO usuarios (nombre, correo, password_hash, rol, activo)
        VALUES ('Administrador', 'admin@local', '1234', 'admin', TRUE)
        ON CONFLICT (correo) DO NOTHING;

        -- CLIENTES (para el dashboard de Admin)
        CREATE TABLE IF NOT EXISTS clientes (
            id SERIAL PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            telefono VARCHAR(20),
            direccion TEXT
        );

        -- PRODUCTOS
        CREATE TABLE IF NOT EXISTS productos (
            id SERIAL PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            descripcion TEXT,
            precio NUMERIC(10,2) NOT NULL,
            activo BOOLEAN DEFAULT TRUE
        );

        -- PEDIDOS
        CREATE TABLE IF NOT EXISTS pedidos (
            id SERIAL PRIMARY KEY,
            nombre_cliente VARCHAR(100) NOT NULL,
            direccion TEXT,
            telefono VARCHAR(20),
            estado VARCHAR(20) DEFAULT 'pendiente',
            fecha TIMESTAMP DEFAULT NOW()
        );

        -- DETALLE PEDIDOS
        CREATE TABLE IF NOT EXISTS pedido_detalle (
            id SERIAL PRIMARY KEY,
            id_pedido INT REFERENCES pedidos(id) ON DELETE CASCADE,
            id_producto INT REFERENCES productos(id),
            cantidad INT NOT NULL,
            subtotal NUMERIC(10,2) NOT NULL
        );
        ";

        $pdo->exec($sql);
    }
}
