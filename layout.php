<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/utils.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

$loggedInUser = Utils::getLoggedInUser();

if (isset($_COOKIE['auth_token'])) {
  $jwt = $_COOKIE['auth_token'];
  $key = 'CHEIA MEA SUPER SECRETA';

  try {
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $loggedInUser = $decoded->username;
  } catch (ExpiredException $e) {
    // expired token
    $loggedInUser = null;
    setcookie('auth_token', '', time() - 3600, '/');
  } catch (Exception $e) {
    // invalid token
    $loggedInUser = null;
    setcookie('auth_token', '', time() - 3600, '/');
  }
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="public/css/style.css">
</head>
<div class="nav">
  <a href="/">
    <p>Books on Web</p>
  </a>
  <div class="nav-links">
    <?php if (isset($loggedInUser)): ?>
      <span class="color-gray">
        Hello, <?= htmlspecialchars($loggedInUser) ?>!
      </span>
      <a href="/logout">Logout</a>
    <?php else: ?>
      <a href="/login">Login</a>
      <a href="/register">Register</a>
    <?php endif; ?>
  </div>
</div>