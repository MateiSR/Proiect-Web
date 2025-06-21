<?php

class Database
{
    private static $instance = null;
    private $conn;

    public function __construct()
    {
        // default values if environment variables are not set
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '5432';
        $db_name = $_ENV['DB_NAME'] ?? 'books_db';
        $username = $_ENV['DB_USER'] ?? 'postgres';
        $password = $_ENV['DB_PASS'] ?? 'postgres';

        $dsn = "pgsql:host={$host};port={$port};dbname={$db_name}";

        try {
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
            exit;
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function connect()
    {
        return $this->conn;
    }

}
?>