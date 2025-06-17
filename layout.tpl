<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Books on Web</title>
  <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>
  <header>
    <div class="nav">
      <div class="nav-brand">
        <a href="/">
          <h1>Books on Web</h1>
        </a>
      </div>
      <div class="nav-links">
        <a href="/">Home</a>
        <a href="/books">Books</a>
        <?php if (isset($loggedInUser)): ?>
          <a href="/groups">Groups</a>
          <?php if ($loggedInUser['is_admin']): ?>
            <a href="/admin" class="highlight-link">Admin</a>
          <?php endif; ?>
          <a href="/logout">Logout</a>
        <?php else: ?>
          <a href="/login">Login</a>
          <a href="/register">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </header>
  <main>
    <?php echo $content; ?>
  </main>
</body>

</html>