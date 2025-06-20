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
      <button class="hamburger-menu" aria-label="Toggle navigation menu">☰</button>
      <div class="nav-links">
        <a href="/">Home</a>
        <a href="/books">Books</a>
        <a href="/statistics">Stats</a>
        <?php if (isset($loggedInUser)): ?>
          <a href="/groups">Groups</a>
          <?php if ($loggedInUser['is_admin']): ?>
            <a href="/admin" class="highlight-link">Admin Page</a>
          <?php endif; ?>
          <a href="/profile">Profile</a>
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
  <script src="/public/js/main.js"></script>
</body>

</html>