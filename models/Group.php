<?php

require_once __DIR__ . '/../config/database.php';

class Group
{
  private $conn;

  public function __construct()
  {
    $database = Database::getInstance();
    $this->conn = $database->connect();
  }

  public function createGroup(string $name, string $description, int $creator_id): ?int
  {
    $this->conn->beginTransaction();
    try {
      $query = "INSERT INTO groups (name, description, creator_id) VALUES (:name, :description, :creator_id)";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':creator_id', $creator_id, PDO::PARAM_INT);
      $stmt->execute();
      $groupId = $this->conn->lastInsertId();

      $this->addUserToGroup($groupId, $creator_id);

      $this->conn->commit();
      return (int) $groupId;
    } catch (PDOException $e) {
      $this->conn->rollBack();
      return null;
    }
  }

  public function addUserToGroup(int $group_id, int $user_id): bool
  {
    try {
      $query = "INSERT INTO group_members (group_id, user_id) VALUES (:group_id, :user_id)";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getAllGroups()
  {
    $query = "SELECT g.id, g.name, g.description, u.username as creator_name,
                     (SELECT COUNT(*) FROM group_members gm WHERE gm.group_id = g.id) as member_count
              FROM groups g
              JOIN users u ON g.creator_id = u.id
              ORDER BY g.created_at DESC";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  public function getGroupById(int $id)
  {
    $query = "SELECT g.id, g.name, g.description, u.username as creator_name, g.created_at
              FROM groups g
              JOIN users u ON g.creator_id = u.id
              WHERE g.id = :id
              LIMIT 1";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function isUserMember(int $group_id, int $user_id): bool
  {
    $query = "SELECT id FROM group_members WHERE group_id = :group_id AND user_id = :user_id LIMIT 1";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch() !== false;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getGroupMembers(int $group_id)
  {
    $query = "SELECT u.id, u.username FROM users u JOIN group_members gm ON u.id = gm.user_id WHERE gm.group_id = :group_id";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  public function addBookToGroup(int $group_id, int $book_id, int $user_id): bool
  {
    $query = "INSERT INTO group_books (group_id, book_id, added_by_user_id) VALUES (:group_id, :book_id, :user_id)";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
      $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getGroupBooks(int $group_id)
  {
    $query = "SELECT b.id as book_id, b.title, b.author
              FROM books b
              JOIN group_books gb ON b.id = gb.book_id
              WHERE gb.group_id = :group_id
              ORDER BY gb.added_at DESC";
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }
}