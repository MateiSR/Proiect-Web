<div class="center">
  <h1 class="color-red mb-0">Error</h1>
  <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
    <h2 class="h2-error"><?php echo htmlspecialchars($errorMessage); ?></h2>
  <?php else: ?>
    <h2 class="h2-error">An unexpected error occurred.</h2>
  <?php endif; ?>
  <p>
    <a href="/">Go to Homepage</a>
    <a href="/books">View Books List</a>
  </p>
</div>