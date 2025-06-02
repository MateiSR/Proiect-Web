<div class="book-detail-container">
  <?php if (isset($book) && $book): ?>
    <h2><?php echo htmlspecialchars($book['title']); ?></h2>
    <div class="book-meta">
      <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
      <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre'] ?? 'N/A'); ?></p>
      <p><strong>Added to Library:</strong>
        <?php echo htmlspecialchars(date('j F Y, H:i', strtotime($book['created_at']))); ?></p>
    </div>

    <div class="book-description">
      <p>Book description!</p>
    </div>

    <div class="book-actions">
      <a href="/books" class="btn-back">Back to Library</a>
    </div>
  <?php else: ?>
    <p class="color-red">Book details were not found in database</p>
  <?php endif; ?>
</div>