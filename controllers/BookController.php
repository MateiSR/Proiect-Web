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

  public function index(): string
  {
    $searchTerm = trim($_GET['search'] ?? '');
    $genre = trim($_GET['genre'] ?? '');
    $books = [];

    $genres = $this->bookModel->getGenreStatistics();

    if (!empty($searchTerm) || !empty($genre)) {
      $books = $this->bookModel->searchBooks($searchTerm, $genre);
    } else {
      $books = $this->bookModel->getAllBooks();
    }

    $template = new Template('views/books_list_view.tpl');
    $template->books = $books;
    $template->searchTerm = $searchTerm;
    $template->genres = $genres;
    $template->selectedGenre = $genre;
    return $template->render();
  }

  public function show(int $id): string
  {
    $book = $this->bookModel->findBookById($id);

    if ($book) {
      $loggedInUser = Utils::getLoggedInUser();
      require_once __DIR__ . '/../models/Review.php';
      $reviewModel = new Review();
      $reviews = $reviewModel->getReviewsByBookId($id);
      $userHasReviewed = false;
      if ($loggedInUser) {
        $userHasReviewed = $reviewModel->hasUserReviewedBook($loggedInUser['user_id'], $id);
      }

      $template = new Template('views/book_detail_view.tpl');
      $template->book = $book;
      $template->loggedInUser = $loggedInUser;
      $template->reviews = $reviews;
      $template->userHasReviewed = $userHasReviewed;
      return $template->render();
    } else {
      http_response_code(404);
      $template = new Template('views/error_view.php');
      $template->errorMessage = "Book with ID " . htmlspecialchars($id) . " not found.";
      return $template->render();
    }
  }

  public function create(): string
  {
    $template = new Template('views/add_book_form_view.tpl');
    $template->message = null;
    $template->message_type = '';
    $template->title_value = '';
    $template->author_value = '';
    $template->genre_value = '';
    $template->description_value = '';
    return $template->render();
  }

  public function store(): ?string
  {
    $title_value = trim($_POST['title'] ?? '');
    $author_value = trim($_POST['author'] ?? '');
    $genre_value = trim($_POST['genre'] ?? '');
    $genre_value = !empty($genre_value) ? $genre_value : null;
    $description_value = trim($_POST['description'] ?? '');
    $description_value = !empty($description_value) ? $description_value : null;

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
      if ($this->bookModel->createBook($title_value, $author_value, $genre_value, $description_value)) {
        header("Location: /books");
        exit;
      } else {
        $message = "Failed to add book. Please try again.";
      }
    } else {
      $message = implode("<br>", $errors);
    }

    $template = new Template('views/add_book_form_view.tpl');
    $template->message = $message;
    $template->message_type = $message_type;
    $template->title_value = $title_value;
    $template->author_value = $author_value;
    $template->genre_value = $genre_value;
    $template->description_value = $description_value;
    return $template->render();
  }

  public function adminIndex(): string
  {
    $books = $this->bookModel->getAllBooks();
    $template = new Template('views/admin_books_view.tpl');
    $template->books = $books;
    return $template->render();
  }

  public function edit(int $id): string
  {
    $book = $this->bookModel->findBookById($id);

    if (!$book) {
      http_response_code(404);
      $template = new Template('views/error_view.php');
      $template->errorMessage = "Book with ID " . htmlspecialchars($id) . " not found.";
      return $template->render();
    }

    $template = new Template('views/admin_edit_book_view.tpl');
    $template->book = $book;
    $template->message = null;
    $template->message_type = '';
    return $template->render();
  }

  public function update(int $id): string
  {
    $title_value = trim($_POST['title'] ?? '');
    $author_value = trim($_POST['author'] ?? '');
    $genre_value = trim($_POST['genre'] ?? '');
    $genre_value = !empty($genre_value) ? $genre_value : null;
    $description_value = trim($_POST['description'] ?? '');
    $description_value = !empty($description_value) ? $description_value : null;

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
      $template = new Template('views/error_view.php');
      $template->errorMessage = "Book with ID " . htmlspecialchars($id) . " not found.";
      return $template->render();
    }


    if (empty($errors)) {
      if ($this->bookModel->updateBook($id, $title_value, $author_value, $genre_value, $description_value)) {
        $message = "Book updated successfully!";
        $message_type = 'success';
        $book = $this->bookModel->findBookById($id);
      } else {
        $message = "Failed to update book. Please try again.";
      }
    } else {
      $message = implode("<br>", $errors);
    }

    $template = new Template('views/admin_edit_book_view.tpl');
    $template->book = $book;
    $template->message = $message;
    $template->message_type = $message_type;
    return $template->render();
  }

  public function delete(int $id): ?string
  {
    if ($this->bookModel->deleteBook($id)) {
      header("Location: /admin/books");
      exit;
    } else {
      http_response_code(500);
      $template = new Template('views/error_view.php');
      $template->errorMessage = "Failed to delete book with ID " . htmlspecialchars($id) . ".";
      return $template->render();
    }
  }

  public function importForm(): string
  {
    $template = new Template('views/admin_import_books_view.tpl');
    $template->message = null;
    $template->message_type = '';
    return $template->render();
  }

  public function importCsv(): string
  {
    $message = null;
    $message_type = 'error';
    $imported_count = 0;
    $template = new Template('views/admin_import_books_view.tpl');

    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
      $message = "Error uploading CSV file.";
    } else {
      $file_path = $_FILES['csv_file']['tmp_name'];
      if (($handle = fopen($file_path, "r")) !== FALSE) {
        $header = fgetcsv($handle);
        if ($header === false) {
          $message = "Could not read CSV header.";
        } else {
          $expected_headers = ['title', 'author', 'genre', 'description'];
          $header_map = [];
          foreach ($expected_headers as $expected_header) {
            $index = array_search($expected_header, $header);
            if ($index !== false) {
              $header_map[$expected_header] = $index;
            }
          }

          if (count($header_map) < 2 || !isset($header_map['title']) || !isset($header_map['author'])) {
            $message = "CSV file must contain 'title' and 'author' columns. 'genre' and 'description' are optional.";
          } else {
            while (($data = fgetcsv($handle)) !== FALSE) {
              $title = $data[$header_map['title']] ?? '';
              $author = $data[$header_map['author']] ?? '';
              $genre = $data[$header_map['genre']] ?? null;
              $description = $data[$header_map['description']] ?? null;

              if (!empty($title) && !empty($author)) {
                if ($this->bookModel->createBook($title, $author, $genre, $description)) {
                  $imported_count++;
                }
              }
            }
            if ($imported_count > 0) {
              $message = "Successfully imported " . $imported_count . " books from CSV.";
              $message_type = 'success';
            } else {
              $message = "No valid books found or imported from CSV.";
            }
          }
        }
        fclose($handle);
      } else {
        $message = "Failed to open CSV file.";
      }
    }

    $template->message = $message;
    $template->message_type = $message_type;
    return $template->render();
  }

  public function importJson(): string
  {
    $message = null;
    $message_type = 'error';
    $imported_count = 0;
    $template = new Template('views/admin_import_books_view.tpl');

    if (!isset($_FILES['json_file']) || $_FILES['json_file']['error'] !== UPLOAD_ERR_OK) {
      $message = "Error uploading JSON file.";
    } else {
      $file_content = file_get_contents($_FILES['json_file']['tmp_name']);
      if ($file_content === false) {
        $message = "Failed to read JSON file content.";
      } else {
        $books_data = json_decode($file_content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
          $message = "Invalid JSON format: " . json_last_error_msg();
        } elseif (!is_array($books_data)) {
          $message = "JSON must be an array of book objects.";
        } else {
          foreach ($books_data as $book) {
            $title = $book['title'] ?? null;
            $author = $book['author'] ?? null;
            $genre = $book['genre'] ?? null;
            $description = $book['description'] ?? null;

            if (!empty($title) && !empty($author)) {
              if ($this->bookModel->createBook($title, $author, $genre, $description)) {
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
        }
      }
    }

    $template->message = $message;
    $template->message_type = $message_type;
    return $template->render();
  }
}