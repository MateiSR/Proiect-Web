<div class="books-list-container">
  <h2>Admin - Manage Books</h2>

  <?php if (!empty($books)): ?>
    <div class="admin-books-grid">
      <?php foreach ($books as $book): ?>
        <div class="admin-book-card book-card">
          <h3>
            <?php echo htmlspecialchars($book['title']); ?>
          </h3>
          <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
          <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre'] ?? 'N/A'); ?></p>
          <p class="book-description"><strong>Description:</strong>
            <?php echo htmlspecialchars($book['description'] ?? 'No description available.'); ?></p>
          <p><strong>ID:</strong> <?php echo htmlspecialchars($book['id']); ?></p>
          <div class="admin-book-actions">
            <a href="/admin/books/edit?id=<?php echo htmlspecialchars($book['id']); ?>" class="button edit-button"
              style="color: white; text-decoration: none;">Edit</a>
            <form method="post" action="/admin/books/delete"
              onsubmit="return confirm('Are you sure you want to delete this book?');">
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($book['id']); ?>">
              <button type="submit" class="button delete-button">Delete</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No books found.</p>
  <?php endif; ?>
</div>