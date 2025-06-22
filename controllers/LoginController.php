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

  public function index(): string
  {
    $template = new Template('views/login_view.tpl');
    $loggedInUser = Utils::getLoggedInUser();
    $err = null;
    if ($loggedInUser != null) {
      $err = "You are already logged in as " . htmlspecialchars($loggedInUser['username']) . ".";
    }
    $template->err = $err;
    $template->identifier_value = '';
    return $template->render();
  }

  public function login(): ?string
  {
    $template = new Template('views/login_view.tpl');
    $err = null;
    $identifier_value = trim(strip_tags($_POST['identifier'] ?? ''));

    $loggedInUser = Utils::getLoggedInUser();
    if ($loggedInUser != null) {
      $err = "You are already logged in as " . htmlspecialchars($loggedInUser['username']) . ".";
      $template->err = $err;
      $template->identifier_value = $identifier_value;
      return $template->render();
    }

    $password = $_POST["password"] ?? '';

    if (empty($identifier_value)) {
      $err = "Email or Username is required";
    } elseif (empty($password)) {
      $err = "Password is required";
    } else {
      try {
        $user = $this->userModel->findUserByEmailOrUsername($identifier_value, $identifier_value);

        if ($user && password_verify($password, $user['password'])) {
          $key = $_ENV['JWT_SECRET_KEY'];
          $issuedAt = time();
          $expirationTime = $issuedAt + (60 * 60);

          $payload = [
            'iss' => $_ENV['DOMAIN'],
            'aud' => $_ENV['DOMAIN'],
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'user_id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'is_admin' => ((bool) $user['is_admin']) ?? false
          ];

          $jwt = JWT::encode($payload, $key, 'HS256');

          $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

          $cookie_options = [
            'expires' => $expirationTime,
            'path' => '/',
            'domain' => '',
            'secure' => $isHttps,
            'httpOnly' => true, // cookie cannot be accessed by js
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
    $template->err = $err;
    $template->identifier_value = $identifier_value;
    return $template->render();
  }
}