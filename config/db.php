<?php
// config/db.php

class Database {

    // ✅ Datos EXACTOS de tu URI
    private $host = "aws-1-us-east-2.pooler.supabase.com";
    private $port = "5432"; // ✅ el de tu URL
    private $db_name = "postgres";

    private $username = "postgres.fjlsgephvzmbldhqxmvu";
    private $password = "123456";

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";

            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

        } catch (PDOException $exception) {
            die("❌ Error de conexión: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
