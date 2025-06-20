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
    $query = "SELECT b.id, b.title, b.author, b.genre, b.description, b.pages, b.created_at,
                     COALESCE(AVG(r.rating), 0) as avg_rating, COUNT(r.id) as review_count
              FROM " . $this->table_name . " b
              LEFT JOIN reviews r ON b.id = r.book_id
              GROUP BY b.id
              ORDER BY b.title ASC";
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
    $query = "SELECT b.id, b.title, b.author, b.genre, b.description, b.pages, b.created_at,
                     COALESCE(AVG(r.rating), 0) as avg_rating, COUNT(r.id) as review_count
              FROM " . $this->table_name . " b
              LEFT JOIN reviews r ON b.id = r.book_id
              WHERE b.id = :id
              GROUP BY b.id
              LIMIT 1";
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

  public function createBook(string $title, string $author, ?string $genre, ?string $description = null, ?int $pages = null): bool
  {
    $query = "INSERT INTO " . $this->table_name . " (title, author, genre, description, pages) VALUES (:title, :author, :genre, :description, :pages)";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':author', $author);
      $stmt->bindParam(':genre', $genre);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':pages', $pages, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
  }
  public function searchBooks(string $searchTerm, string $genre = null)
  {
    $query = "SELECT b.id, b.title, b.author, b.genre, b.description, b.pages, b.created_at,
                     AVG(r.rating) as avg_rating, COUNT(r.id) as review_count
              FROM " . $this->table_name . " b LEFT JOIN reviews r ON b.id = r.book_id";

    $conditions = [];
    $params = [];

    if (!empty($searchTerm)) {
      $conditions[] = "(b.title ILIKE :searchTerm OR b.author ILIKE :searchTerm)";
      $params[':searchTerm'] = '%' . $searchTerm . '%';
    }

    if (!empty($genre)) {
      $conditions[] = "b.genre = :genre";
      $params[':genre'] = $genre;
    }

    if (!empty($conditions)) {
      $query .= " WHERE " . implode(' AND ', $conditions);
    }

    $query .= " GROUP BY b.id
              ORDER BY b.title ASC";

    try {
      $stmt = $this->conn->prepare($query);
      $stmt->execute($params);
      $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($books as &$book) {
        if ($book['avg_rating'] === null) {
          $book['avg_rating'] = 0;
        }
      }

      return $books;
    } catch (PDOException $e) {
      return [];
    }
  }

  public function updateBook(int $id, string $title, string $author, ?string $genre, ?string $description, ?int $pages): bool
  {
    $query = "UPDATE " . $this->table_name . " SET title = :title, author = :author, genre = :genre, description = :description, pages = :pages WHERE id = :id";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':author', $author);
      $stmt->bindParam(':genre', $genre);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':pages', $pages, PDO::PARAM_INT);
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

  public function getLatestBooks(int $limit = 10)
  {
    $query = "SELECT id, title, author, description, pages, created_at
              FROM " . $this->table_name . "
              ORDER BY created_at DESC
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

  public function getGenreStatistics()
  {
    $query = "SELECT genre, COUNT(id) as book_count
              FROM " . $this->table_name . "
              WHERE genre IS NOT NULL AND genre <> ''
              GROUP BY genre
              ORDER BY book_count DESC";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }
}