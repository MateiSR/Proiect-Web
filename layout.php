<?php
$content = $content ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Books on Web</title>
  <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>
  <div class="nav">
    <a href="/">
      <p>Books on Web</p>
    </a>
    <div class="nav-links">
      <a href="/books" class="highlight-link">Books</a>
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
  <main>
    <?php echo $content; ?>
  </main>

</body>

</html>