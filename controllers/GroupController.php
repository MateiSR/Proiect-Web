<?php

require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/GroupDiscussion.php';
require_once __DIR__ . '/../config/utils.php';

class GroupController
{
  private $groupModel;
  private $bookModel;
  private $discussionModel;

  public function __construct()
  {
    $this->groupModel = new Group();
    $this->bookModel = new Book();
    $this->discussionModel = new GroupDiscussion();
  }

  public function index(): string
  {
    $groups = $this->groupModel->getAllGroups();
    $template = new Template('views/groups_list_view.tpl');
    $template->groups = $groups;
    return $template->render();
  }

  public function show(int $id): string
  {
    $loggedInUser = Utils::getLoggedInUser();
    if (!$loggedInUser) {
      header("Location: /login");
      exit;
    }

    $group = $this->groupModel->getGroupById($id);
    if (!$group) {
      http_response_code(404);
      $template = new Template('views/error_view.php');
      $template->errorMessage = "Group with ID " . htmlspecialchars($id) . " not found.";
      return $template->render();
    }

    $isMember = $this->groupModel->isUserMember($id, $loggedInUser['user_id']);
    $members = $this->groupModel->getGroupMembers($id);
    $groupBooks = $this->groupModel->getGroupBooks($id);
    $allBooks = $this->bookModel->getAllBooks();

    $discussions = [];
    foreach ($groupBooks as $book) {
      $discussions[$book['book_id']] = $this->discussionModel->getPostsForBookInGroup($id, $book['book_id']);
    }

    $template = new Template('views/group_detail_view.tpl');
    $template->group = $group;
    $template->isMember = $isMember;
    $template->members = $members;
    $template->groupBooks = $groupBooks;
    $template->allBooks = $allBooks;
    $template->discussions = $discussions;
    $template->loggedInUser = $loggedInUser;

    return $template->render();
  }

  public function create(): string
  {
    $loggedInUser = Utils::getLoggedInUser();
    if (!$loggedInUser) {
      header("Location: /login");
      exit;
    }

    $template = new Template('views/create_group_view.tpl');
    $template->message = null;
    $template->name_value = '';
    $template->description_value = '';
    return $template->render();
  }

  public function store(): string
  {
    $loggedInUser = Utils::getLoggedInUser();
    if (!$loggedInUser) {
      header("Location: /login");
      exit;
    }

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $errors = [];

    if (empty($name)) {
      $errors[] = "Group name is required.";
    }

    if (empty($errors)) {
      $newGroupId = $this->groupModel->createGroup($name, $description, $loggedInUser['user_id']);
      if ($newGroupId) {
        header("Location: /group?id=" . $newGroupId);
        exit;
      } else {
        $message = "Failed to create group.";
      }
    } else {
      $message = implode("<br>", $errors);
    }

    $template = new Template('views/create_group_view.tpl');
    $template->message = $message;
    $template->name_value = $name;
    $template->description_value = $description;
    return $template->render();
  }

  public function join()
  {
    $loggedInUser = Utils::getLoggedInUser();
    if (!$loggedInUser) {
      header("Location: /login");
      exit;
    }

    $group_id = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);
    if ($group_id) {
      $this->groupModel->addUserToGroup($group_id, $loggedInUser['user_id']);
    }

    header("Location: /group?id=" . $group_id);
    exit;
  }

  public function addBook()
  {
    $loggedInUser = Utils::getLoggedInUser();
    if (!$loggedInUser) {
      header("Location: /login");
      exit;
    }

    $group_id = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);
    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

    if ($group_id && $book_id && $this->groupModel->isUserMember($group_id, $loggedInUser['user_id'])) {
      $this->groupModel->addBookToGroup($group_id, $book_id, $loggedInUser['user_id']);
    }

    header("Location: /group?id=" . $group_id);
    exit;
  }

  public function postDiscussion()
  {
    $loggedInUser = Utils::getLoggedInUser();
    if (!$loggedInUser) {
      header("Location: /login");
      exit;
    }

    $group_id = filter_input(INPUT_POST, 'group_id', FILTER_VALIDATE_INT);
    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
    $comment = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS));

    if ($group_id && $book_id && !empty($comment) && $this->groupModel->isUserMember($group_id, $loggedInUser['user_id'])) {
      $this->discussionModel->createPost($group_id, $book_id, $loggedInUser['user_id'], $comment);
    }

    header("Location: /group?id=" . $group_id);
    exit;
  }
}