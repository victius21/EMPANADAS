<?php
// config/db.php

class Database {

    // 🔹 Datos de Supabase (los que vimos en tu captura)
    private $host = "db.fjlsgephvzmblqhxmvu.supabase.co";
    private $port = "5432";
    private $db_name = "postgres";
    private $username = "postgres";
    private $password = "123456";  // ✔ Tu contraseña real de Supabase

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Supabase requiere SSL
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";

            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Ya NO creamos el esquema desde PHP.
            // La base de datos ya vive en Supabase con los scripts que ejecutaste.
            // $this->createSchema($this->conn);

        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }

    // Dejado vacío para no modificar nada en Supabase desde código
    private function createSchema(PDO $pdo) {
        // Intencionalmente vacío
    }
}
