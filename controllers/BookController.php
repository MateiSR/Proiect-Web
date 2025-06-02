<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../config/utils.php';

class BookController
{
  private $bookModel;

  public function __construct()
  {
    $this->bookModel = new Book();
  }

  public function index()
  {
    $books = $this->bookModel->getAllBooks();
    require_once __DIR__ . '/../views/books_list_view.php';
  }

  public function show(int $id)
  {
    $book = $this->bookModel->findBookById($id);

    if ($book) {
      require_once __DIR__ . '/../views/book_detail_view.php';
    } else {
      http_response_code(404);
      $errorMessage = "Book with ID " . htmlspecialchars($id) . " not found.";
      require_once __DIR__ . '/../views/error_view.php';
    }
  }

  public function create()
  {
    $loggedInUser = Utils::getLoggedInUser();
    if (!$loggedInUser) {
      header("Location: /login");
      exit;
    }
    $message = null;
    $message_type = '';
    $title_value = '';
    $author_value = '';
    $genre_value = '';
    require_once __DIR__ . '/../views/add_book_form_view.php';
  }

  public function store()
  {
    $loggedInUser = Utils::getLoggedInUser();
    if (!$loggedInUser) {
      http_response_code(403);
      $errorMessage = "You must be logged in to add a book.";
      require __DIR__ . '/../views/error_view.php';
      return;
    }

    $title_value = trim($_POST['title'] ?? '');
    $author_value = trim($_POST['author'] ?? '');
    $genre_value = trim($_POST['genre'] ?? '');
    $genre_value = !empty($genre_value) ? $genre_value : null;

    $errors = [];
    $message = null;
    $message_type = 'error';

    if (empty($title_value)) {
      $errors[] = "Title is required.";
    }
    if (empty($author_value)) {
      $errors[] = "Author is required.";
    }

    if (empty($errors)) {
      if ($this->bookModel->createBook($title_value, $author_value, $genre_value)) {
        header("Location: /books");
        exit;
      } else {
        $message = "Failed to add book. Please try again.";
      }
    } else {
      $message = implode("<br>", $errors);
    }

    require_once __DIR__ . '/../views/add_book_form_view.php';
  }
}
?>