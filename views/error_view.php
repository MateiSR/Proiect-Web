<div class="center" style="padding: 2rem;">
  <h1 class="color-red mb-0" style="font-size: 2.5rem;">Error</h1>
  <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
    <h2 style="font-size: 1.5rem; color: #555;"><?php echo htmlspecialchars($errorMessage); ?></h2>
  <?php else: ?>
    <h2 style="font-size: 1.5rem; color: #555;">An unexpected error occurred.</h2>
  <?php endif; ?>
  <p style="margin-top: 1.5rem;">
    <a href="/" style="margin-right: 1rem; color: #007bff; text-decoration:none;">Go to Homepage</a>
    <a href="/books" style="color: #007bff; text-decoration:none;">View Books List</a>
  </p>
</div>