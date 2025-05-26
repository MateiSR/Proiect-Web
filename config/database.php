<?php

class Database
{
    private $host = 'localhost';
    private $db_name = 'books_db';
    private $username = 'postgres';
    private $password = 'postgres';
    private $port = '5432';
    private $conn;

    public function connect()
    {
        $this->conn = null;

        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // actually throw error so they can be caught
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // fetch as associative array (map column names to values)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
            exit;
        }

        return $this->conn;
    }
}
?>