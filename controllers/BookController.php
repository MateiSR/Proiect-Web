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
    $searchTerm = trim($_GET['search'] ?? '');
    $books = [];

    if (!empty($searchTerm)) {
      $books = $this->bookModel->searchBooks($searchTerm);
    } else {
      $books = $this->bookModel->getAllBooks();
    }
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

    // admin check by router

    $message = null;
    $message_type = '';
    $title_value = '';
    $author_value = '';
    $genre_value = '';
    require_once __DIR__ . '/../views/add_book_form_view.php';
  }

  public function store()
  {
    // admin check by router

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

  public function adminIndex()
  {
    $books = $this->bookModel->getAllBooks();
    require_once __DIR__ . '/../views/admin_books_view.php';
  }

  public function edit(int $id)
  {
    $book = $this->bookModel->findBookById($id);

    if (!$book) {
      http_response_code(404);
      $errorMessage = "Book with ID " . htmlspecialchars($id) . " not found.";
      require_once __DIR__ . '/../views/error_view.php';
      return;
    }

    $message = null;
    $message_type = '';
    require_once __DIR__ . '/../views/admin_edit_book_view.php';
  }

  public function update(int $id)
  {
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

    $book = $this->bookModel->findBookById($id);
    if (!$book) {
      http_response_code(404);
      $errorMessage = "Book with ID " . htmlspecialchars($id) . " not found.";
      require_once __DIR__ . '/../views/error_view.php';
      return;
    }


    if (empty($errors)) {
      if ($this->bookModel->updateBook($id, $title_value, $author_value, $genre_value)) {
        $message = "Book updated successfully!";
        $message_type = 'success';
        // Refresh book data after update
        $book = $this->bookModel->findBookById($id);
      } else {
        $message = "Failed to update book. Please try again.";
      }
    } else {
      $message = implode("<br>", $errors);
    }

    require_once __DIR__ . '/../views/admin_edit_book_view.php';
  }

  public function delete(int $id)
  {
    if ($this->bookModel->deleteBook($id)) {
      header("Location: /admin/books");
      exit;
    } else {
      http_response_code(500);
      $errorMessage = "Failed to delete book with ID " . htmlspecialchars($id) . ".";
      require_once __DIR__ . '/../views/error_view.php';
    }
  }

  public function importForm()
  {
    $message = null;
    $message_type = '';
    require_once __DIR__ . '/../views/admin_import_books_view.php';
  }

  public function importCsv()
  {
    $message = null;
    $message_type = 'error';
    $imported_count = 0;

    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
      $message = "Error uploading CSV file.";
      require_once __DIR__ . '/../views/admin_import_books_view.php';
      return;
    }

    $file_path = $_FILES['csv_file']['tmp_name'];
    if (($handle = fopen($file_path, "r")) !== FALSE) {
      $header = fgetcsv($handle); // Read header row
      if ($header === false) {
        $message = "Could not read CSV header.";
        fclose($handle);
        require_once __DIR__ . '/../views/admin_import_books_view.php';
        return;
      }
      $expected_headers = ['title', 'author', 'genre'];
      $header_map = [];
      foreach ($expected_headers as $expected_header) {
        $index = array_search($expected_header, $header);
        if ($index !== false) {
          $header_map[$expected_header] = $index;
        }
      }

      if (count($header_map) < 2 || !isset($header_map['title']) || !isset($header_map['author'])) {
        $message = "CSV file must contain 'title' and 'author' columns. 'genre' is optional.";
        fclose($handle);
        require_once __DIR__ . '/../views/admin_import_books_view.php';
        return;
      }

      while (($data = fgetcsv($handle)) !== FALSE) {
        $title = $data[$header_map['title']] ?? '';
        $author = $data[$header_map['author']] ?? '';
        $genre = $data[$header_map['genre']] ?? null;

        if (!empty($title) && !empty($author)) {
          if ($this->bookModel->createBook($title, $author, $genre)) {
            $imported_count++;
          }
        }
      }
      fclose($handle);

      if ($imported_count > 0) {
        $message = "Successfully imported " . $imported_count . " books from CSV.";
        $message_type = 'success';
      } else {
        $message = "No valid books found or imported from CSV.";
      }
    } else {
      $message = "Failed to open CSV file.";
    }
    require_once __DIR__ . '/../views/admin_import_books_view.php';
  }

  public function importJson()
  {
    $message = null;
    $message_type = 'error';
    $imported_count = 0;

    if (!isset($_FILES['json_file']) || $_FILES['json_file']['error'] !== UPLOAD_ERR_OK) {
      $message = "Error uploading JSON file.";
      require_once __DIR__ . '/../views/admin_import_books_view.php';
      return;
    }

    $file_content = file_get_contents($_FILES['json_file']['tmp_name']);
    if ($file_content === false) {
      $message = "Failed to read JSON file content.";
      require_once __DIR__ . '/../views/admin_import_books_view.php';
      return;
    }

    $books_data = json_decode($file_content, true);


    // check for correct JSON format
    if (json_last_error() !== JSON_ERROR_NONE) {
      $message = "Invalid JSON format: " . json_last_error_msg();
      require_once __DIR__ . '/../views/admin_import_books_view.php';
      return;
    }

    if (!is_array($books_data)) {
      $message = "JSON must be an array of book objects.";
      require_once __DIR__ . '/../views/admin_import_books_view.php';
      return;
    }

    foreach ($books_data as $book) {
      $title = $book['title'] ?? null;
      $author = $book['author'] ?? null;
      $genre = $book['genre'] ?? null;

      if (!empty($title) && !empty($author)) {
        if ($this->bookModel->createBook($title, $author, $genre)) {
          $imported_count++;
        }
      }
    }

    if ($imported_count > 0) {
      $message = "Successfully imported " . $imported_count . " books from JSON.";
      $message_type = 'success';
    } else {
      $message = "No valid books found or imported from JSON.";
    }
    require_once __DIR__ . '/../views/admin_import_books_view.php';
  }
}