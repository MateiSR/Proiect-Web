<?php

require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../config/utils.php';

class ReviewController
{
  private $reviewModel;

  public function __construct()
  {
    $this->reviewModel = new Review();
  }

  public function add()
  {
    $currentUser = Utils::getLoggedInUser();
    if (!$currentUser) {
      header("Location: /login");
      exit;
    }

    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS));
    $user_id = $currentUser['user_id'];

    if ($book_id === false || $rating === false || $rating < 1 || $rating > 5) {
      header("Location: /book?id=" . $book_id);
      exit;
    }

    if ($this->reviewModel->hasUserReviewedBook($user_id, $book_id)) {
      header("Location: /book?id=" . $book_id);
      exit;
    }

    $this->reviewModel->createReview($book_id, $user_id, $rating, $comment);

    header("Location: /book?id=" . $book_id);
    exit;
  }
}