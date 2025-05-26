<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/User.php';

use Firebase\JWT\JWT;

class LoginController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function index()
  {
    $err = null;
    $email_value = '';
    $username_value = '';
    require_once __DIR__ . '/../views/login_view.php';
  }

  public function login()
  {
    $err = null;
    $email_value = $_POST['email'] ?? '';
    $username_value = $_POST['username'] ?? '';

    // validate input

    if (empty($_POST["email"])) {
      $err = "Email is required";
    } elseif (empty($_POST["username"])) {
      $err = "Username is required";
    } elseif (empty($_POST["password"])) {
      $err = "Password is required";
    } else {
      $email = trim(strip_tags($_POST["email"]));
      $username = trim(strip_tags($_POST["username"]));
      $password = $_POST["password"];

      try {
        $user = $this->userModel->findUserByEmailOrUsername($email, $username);

        if ($user && $user['email'] === $email && $user['username'] === $username && password_verify($password, $user['password'])) {
          $key = 'CHEIA MEA SUPER SECRETA';
          $issuedAt = time();
          $expirationTime = $issuedAt + (60 * 60); // 1 hr exp

          $payload = [
            'iss' => 'http://localhost',
            'aud' => 'http://localhost',
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'user_id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username']
          ];

          $jwt = JWT::encode($payload, $key, 'HS256');

          // save as cookie
          $cookie_options = [
            'expires' => $expirationTime,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true
          ];
          setcookie("auth_token", $jwt, $cookie_options);

          header("Location: /");
          exit;
        } else {
          $err = "Invalid credentials";
        }
      } catch (PDOException $e) {
        $err = "Database error: " . $e->getMessage();
      } catch (Exception $e) {
        $err = "Unknown error: " . $e->getMessage();

      }
    }
    require_once __DIR__ . '/../views/login_view.php';
  }
}

?>