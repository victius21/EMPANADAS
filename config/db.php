<?php
// config/db.php

class Database {

    // 🔹 Datos EXACTOS del Session Pooler Supabase
    private $host = "aws-1-us-east-2.pooler.supabase.com";
    private $port = "5432";
    private $db_name = "postgres";

    // Usuario que sale en tu URI: postgres.fjlsgephvzmbldhqxmvu
    private $username = "postgres.fjlsgephvzmbldhqxmvu";

    // Tu contraseña (la que dijiste): 123456
    private $password = "123456";

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";

            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
