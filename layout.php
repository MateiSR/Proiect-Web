<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/utils.php';

$loggedInUser = Utils::getLoggedInUser();

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