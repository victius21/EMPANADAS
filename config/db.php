<?php
// config/db.php

class Database {

    // 🔹 Datos de Supabase usando Session Pooler
    private $host = "aws-0-XXXXXX.pooler.supabase.com"; // <-- PON AQUÍ EL HOST NUEVO
    private $port = "5432";
    private $db_name = "postgres";
    private $username = "postgres";
    private $password = "123456";  // tu pass

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";

            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // $this->createSchema($this->conn);  // NO lo usamos ya

        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }

    private function createSchema(PDO $pdo) {}
}
