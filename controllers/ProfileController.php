<?php
require_once __DIR__ . '/../config/utils.php';
require_once __DIR__ . '/../models/UserBookProgress.php';
require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/../config/Template.php';

class ProfileController
{
  private $userBookProgressModel;
  private $groupModel;

  public function __construct()
  {
    $this->userBookProgressModel = new UserBookProgress();
    $this->groupModel = new Group();
  }

  public function index(): string
  {
    $loggedInUser = Utils::getLoggedInUser();
    if (!$loggedInUser) {
      header("Location: /login");
      exit;
    }

    $userBooks = $this->userBookProgressModel->getBooksForUser($loggedInUser['user_id']);
    $userGroups = $this->groupModel->getGroupsForUser($loggedInUser['user_id']);

    $template = new Template('views/profile_view.tpl');
    $template->user = $loggedInUser;
    $template->userBooks = $userBooks;
    $template->userGroups = $userGroups;

    return $template->render();
  }
}