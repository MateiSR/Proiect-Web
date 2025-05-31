<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/utils.php';

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
    $loggedInUser = Utils::getLoggedInUser();
    $err = null;
    if ($loggedInUser != null) {
      $err = "You are already logged in as " . htmlspecialchars($loggedInUser) . ".";
    }
    $identifier_value = '';
    require_once __DIR__ . '/../views/login_view.php';
  }

  public function login()
  {
    $loggedInUser = Utils::getLoggedInUser();
    if ($loggedInUser != null) {
      $err = "You are already logged in as " . htmlspecialchars($loggedInUser) . ".";
      require_once __DIR__ . '/../views/login_view.php';
      return;
    }
    $err = null;
    $identifier_value = trim(strip_tags($_POST['identifier'] ?? ''));
    $password = $_POST["password"] ?? '';

    if (empty($identifier_value)) {
      $err = "Email or Username is required";
    } elseif (empty($password)) {
      $err = "Password is required";
    } else {
      try {
        $user = $this->userModel->findUserByEmailOrUsername($identifier_value, $identifier_value);

        if ($user && password_verify($password, $user['password'])) {
          $key = 'CHEIA MEA SUPER SECRETA';
          $issuedAt = time();
          $expirationTime = $issuedAt + (60 * 60);

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