<?php

require_once __DIR__ . '/../config/database.php';

class GroupDiscussion
{
  private $conn;

  public function __construct()
  {
    $database = Database::getInstance();
    $this->conn = $database->connect();
  }

  public function createPost(int $group_id, int $book_id, int $user_id, string $comment): bool
  {
    $query = "INSERT INTO group_discussions (group_id, book_id, user_id, comment)
                  VALUES (:group_id, :book_id, :user_id, :comment)";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
      $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->bindParam(':comment', $comment);
      return $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getPostsForBookInGroup(int $group_id, int $book_id)
  {
    $query = "SELECT d.id, d.comment, d.created_at, u.username
                  FROM group_discussions d
                  JOIN users u ON d.user_id = u.id
                  WHERE d.group_id = :group_id AND d.book_id = :book_id
                  ORDER BY d.created_at ASC";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
      $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }
}