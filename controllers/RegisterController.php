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

  public function index()
  {
    $message = null;
    $message_type = '';
    $loggedInUser = Utils::getLoggedInUser();
    if ($loggedInUser != null) {
      $message = "You are already logged in as " . htmlspecialchars($loggedInUser) . ".";
      $message_type = "error";
    }
    $email_value = '';
    $username_value = '';
    require_once __DIR__ . '/../views/register_view.php';
  }

  public function register()
  {
    $message = null;
    $email_value = trim($_POST["email"] ?? '');
    $username_value = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    $password_confirm = $_POST["password_confirm"] ?? '';
    $errors = [];
    $message_type = 'error';

    $loggedInUser = Utils::getLoggedInUser();
    if ($loggedInUser != null) {
      $message = "You are already logged in as " . htmlspecialchars($loggedInUser) . ".";
      require_once __DIR__ . '/../views/register_view.php';
      return;
    }

    // validate inptu

    if (empty($email_value))
      $errors[] = "Email is required.";

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
        $err = "Database error: " . $e->getMessage();
      } catch (Exception $e) {
        $err = "Unknown error: " . $e->getMessage();

      }
    } else {
      $message = implode("<br>", $errors);
    }
    require_once __DIR__ . '/../views/register_view.php';
  }
}

?>