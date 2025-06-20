<?php

require_once __DIR__ . '/../config/database.php';

class UserBookProgress
{
  private $conn;

  public function __construct()
  {
    $database = Database::getInstance();
    $this->conn = $database->connect();
  }

  public function getProgress(int $user_id, int $book_id)
  {
    $query = "SELECT current_page, updated_at FROM user_book_progress WHERE user_id = :user_id AND book_id = :book_id";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function setProgress(int $user_id, int $book_id, int $current_page): bool
  {
    $query = "INSERT INTO user_book_progress (user_id, book_id, current_page)
              VALUES (:user_id, :book_id, :current_page)
              ON CONFLICT (user_id, book_id)
              DO UPDATE SET current_page = EXCLUDED.current_page, updated_at = CURRENT_TIMESTAMP";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
      $stmt->bindParam(':current_page', $current_page, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
  }
}