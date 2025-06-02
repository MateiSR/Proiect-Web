<div class="books-list-container-responsive">
  <h2>Library Catalog</h2>
  <?php if (!empty($books)): ?>
    <div class="books-grid">
      <?php foreach ($books as $book): ?>
        <div class="book-card">
          <h3>
            <a href="/book?id=<?php echo htmlspecialchars($book['id']); ?>">
              <?php echo htmlspecialchars($book['title']); ?>
            </a>
          </h3>
          <p class="book-author"><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
          <p class="book-genre"><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre'] ?? 'N/A'); ?></p>
          <p class="book-date"><strong>Added On:</strong>
            <?php echo htmlspecialchars(date('j F Y', strtotime($book['created_at']))); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No books found. <a href="/add-book">Add a book!</a></p>
  <?php endif; ?>
</div>