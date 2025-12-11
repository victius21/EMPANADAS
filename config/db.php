<?php
// config/db.php

class Database {

    // 🔹 Datos correctos del Session Pooler Supabase
    private $host = "aws-1-us-east-2.pooler.supabase.com";
    private $port = "5432";
    private $db_name = "postgres";

    // OJO: el usuario NO es "postgres" esta vez,
    // Supabase lo muestra así:
    // postgres.fjlsgephvzmbldhqxmvu
    private $username = "postgres.fjlsgephvzmbldhqxmvu";

    // Tu contraseña real
    private $password = "123456";

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Supabase exige SSL
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";

            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
