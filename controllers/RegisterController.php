<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/utils.php';

class RegisterController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function index(): string
  {
    $template = new Template('views/register_view.tpl');
    $message = null;
    $message_type = '';

    $currentUser = Utils::getLoggedInUser();
    if ($currentUser && isset($currentUser['username'])) {
      $message = "You are already logged in as " . htmlspecialchars($currentUser['username']) . ".";
      $message_type = "error";
    }

    $template->message = $message;
    $template->message_type = $message_type;
    $template->email_value = '';
    $template->username_value = '';
    return $template->render();
  }

  public function register(): string
  {
    $message = null;
    $email_value = trim($_POST["email"] ?? '');
    $username_value = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    $password_confirm = $_POST["password_confirm"] ?? '';
    $errors = [];
    $message_type = 'error';

    $currentUser = Utils::getLoggedInUser();
    if ($currentUser && isset($currentUser['username'])) {
      $message = "You are already logged in as " . htmlspecialchars($currentUser['username']) . ".";
    } else {
      if (empty($email_value))
        $errors[] = "Email is required.";
      elseif (!filter_var($email_value, FILTER_VALIDATE_EMAIL))
        $errors[] = "Invalid email format.";

      if (empty($username_value))
        $errors[] = "Username is required.";
      elseif (strlen($username_value) < 3)
        $errors[] = "Username length must be > 3 chars.";

      if (empty($password))
        $errors[] = "Password is required.";
      elseif (strlen($password) < 6)
        $errors[] = "Password length must be > 6 chars.";

      if (empty($password_confirm))
        $errors[] = "Please enter your password again.";
      elseif ($password !== $password_confirm)
        $errors[] = "Password 1 and 2 do not match.";

      if (empty($errors)) {
        try {
          if ($this->userModel->findUserByEmail($email_value)) {
            $errors[] = "This email is taken..";
          }
          if ($this->userModel->findUserByUsername($username_value)) {
            $errors[] = "This username is taken.";
          }

          if (empty($errors)) {
            if ($this->userModel->createUser($email_value, $username_value, $password)) {
              $message = "Registration OK, you can <a href='/login'>login</a>.";
              $message_type = 'success';
              $email_value = '';
              $username_value = '';
            } else {
              $message = "Registration failed, try again.";
            }
          } else {
            $message = implode("<br>", $errors);
          }
        } catch (PDOException $e) {
          $message = "Database error during registration. Please try again later.";
        } catch (Exception $e) {
          $message = "An unknown error occurred during registration.";
        }
      } else {
        $message = implode("<br>", $errors);
      }
    }

    $template = new Template('views/register_view.tpl');
    $template->message = $message;
    $template->message_type = $message_type;
    $template->email_value = $email_value;
    $template->username_value = $username_value;
    return $template->render();
  }
}