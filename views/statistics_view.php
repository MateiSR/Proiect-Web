<div class="books-list-container">
  <h2>Stats - Book Reviews and Ratings</h2>

  <?php if (isset($bookStatistics) && !empty($bookStatistics)): ?>
    <div class="books-grid">
      <?php foreach ($bookStatistics as $stat): ?>
        <div class="book-card">
          <h3><?= htmlspecialchars($stat['title']) ?></h3>
          <p><strong>Author:</strong> <?= htmlspecialchars($stat['author']) ?></p>
          <p><strong>Genre:</strong> <?= htmlspecialchars($stat['genre']) ?></p>
          <p><strong>Reviews:</strong> <?= htmlspecialchars($stat['review_count']) ?></p>
          <p><strong>Avg. Rating:</strong> <?= number_format($stat['avg_rating'], 2) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="center" style="margin-top: 20px;">
      <a href="/statistics/export/csv" class="button edit-button no-decoration">Export as CSV</a>
      <a href="/statistics/export/docbook" class="button delete-button no-decoration">Export as DocBook</a>
    </div>
  <?php else: ?>
    <p>No book stats available.</p>
  <?php endif; ?>
</div>