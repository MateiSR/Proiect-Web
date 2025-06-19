<?php

require_once __DIR__ . '/../config/database.php';

class Review
{
  private $conn;
  private $table_name = "reviews";

  public function __construct()
  {
    $database = Database::getInstance();
    $this->conn = $database->connect();
  }

  public function getReviewsByBookId(int $book_id)
  {
    $query = "SELECT r.id, r.rating, r.comment, r.created_at, u.username
                  FROM " . $this->table_name . " r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.book_id = :book_id
                  ORDER BY r.created_at DESC";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  public function createReview(int $book_id, int $user_id, int $rating, ?string $comment): bool
  {
    $query = "INSERT INTO " . $this->table_name . " (book_id, user_id, rating, comment)
                  VALUES (:book_id, :user_id, :rating, :comment)";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
      $stmt->bindParam(':comment', $comment);
      return $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
  }

  public function hasUserReviewedBook(int $user_id, int $book_id): bool
  {
    $query = "SELECT id FROM " . $this->table_name . " WHERE user_id = :user_id AND book_id = :book_id LIMIT 1";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch() !== false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getLatestReviews(int $limit = 10)
  {
    $query = "SELECT r.id, r.comment, r.created_at, u.username, b.id as book_id, b.title as book_title
                  FROM " . $this->table_name . " r
                  JOIN users u ON r.user_id = u.id
                  JOIN books b ON r.book_id = b.id
                  ORDER BY r.created_at DESC
                  LIMIT :limit";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }
}