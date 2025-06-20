<?php

require_once __DIR__ . '/../models/UserBookProgress.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../config/utils.php';

class ProgressController
{
  private $progressModel;
  private $bookModel;

  public function __construct()
  {
    $this->progressModel = new UserBookProgress();
    $this->bookModel = new Book();
  }

  public function update()
  {
    $currentUser = Utils::getLoggedInUser();
    if (!$currentUser) {
      http_response_code(403);
      echo "You must be logged in to update progress.";
      exit;
    }

    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
    $current_page = filter_input(INPUT_POST, 'current_page', FILTER_VALIDATE_INT);
    $user_id = $currentUser['user_id'];

    if ($book_id === false || $current_page === false || $current_page < 0) {
      header("Location: /book?id=" . $book_id);
      exit;
    }

    $book = $this->bookModel->findBookById($book_id);
    if (!$book || $current_page > $book['pages']) {
      header("Location: /book?id=" . $book_id);
      exit;
    }

    $this->progressModel->setProgress($user_id, $book_id, $current_page);

    header("Location: /book?id=" . $book_id);
    exit;
  }
}