<?php
class Database {
    private $host = "dpg-d4rtqo3uibrs73clll4g-a"; 
    private $port = "5432";
    private $db_name = "empanadas_db_uxos";
    private $username = "empanadas_db_uxos_user";
    private $password = "pKXCkmhx1hwOxTXSnfWXdpMXagwvxxIF";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
