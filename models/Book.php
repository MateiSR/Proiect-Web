<?php

require_once __DIR__ . '/../config/database.php';

class Book
{
  private $conn;
  private $table_name = "books";

  public function __construct()
  {
    $database = Database::getInstance();
    $this->conn = $database->connect();
  }

  public function getAllBooks()
  {
    $query = "SELECT id, title, author, genre, description, created_at FROM " . $this->table_name . " ORDER BY title ASC";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  public function findBookById(int $id)
  {
    $query = "SELECT id, title, author, genre, description, created_at FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $book = $stmt->fetch(PDO::FETCH_ASSOC);
      return $book ? $book : false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function createBook(string $title, string $author, ?string $genre, ?string $description = null): bool
  {
    $query = "INSERT INTO " . $this->table_name . " (title, author, genre, description) VALUES (:title, :author, :genre, :description)";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':author', $author);
      $stmt->bindParam(':genre', $genre);
      $stmt->bindParam(':description', $description);
      return $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
  }
  public function searchBooks(string $searchTerm)
  {
    // ilike = like but not case sensitive
    $query = "SELECT id, title, author, genre, description, created_at FROM " . $this->table_name . "
                  WHERE title ILIKE :searchTerm OR author ILIKE :searchTerm
                  ORDER BY title ASC";

    $likeTerm = '%' . $searchTerm . '%';
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':searchTerm', $likeTerm, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  public function updateBook(int $id, string $title, string $author, ?string $genre, ?string $description): bool
  {
    $query = "UPDATE " . $this->table_name . " SET title = :title, author = :author, genre = :genre, description = :description WHERE id = :id";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':author', $author);
      $stmt->bindParam(':genre', $genre);
      $stmt->bindParam(':description', $description);
      return $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
  }

  public function deleteBook(int $id): bool
  {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
  }
}