<div class="book-detail-container">
  <?php if (isset($book) && $book): ?>
    <h2><?php echo htmlspecialchars($book['title']); ?></h2>
    <div class="book-meta">
      <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
      <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre'] ?? 'N/A'); ?></p>
      <?php if (!empty($book['pages'])): ?>
        <p><strong>Pages:</strong> <?= htmlspecialchars($book['pages']) ?></p>
      <?php endif; ?>
      <p><strong>Average Rating:</strong>
        <?php echo number_format($book['avg_rating'], 2); ?> / 5 (from <?php echo $book['review_count']; ?> reviews)
      </p>
      <p><strong>Added to Library:</strong>
        <?php echo htmlspecialchars(date('j F Y, H:i', strtotime($book['created_at']))); ?></p>
    </div>

    <?php if ($loggedInUser && !empty($book['pages'])): ?>
      <div class="progress-section">
        <h3>Your Progress</h3>
        <?php
        $currentPage = $progress['current_page'] ?? 0;
        $totalPages = $book['pages'];
        $percentage = $totalPages > 0 ? round(($currentPage / $totalPages) * 100) : 0;
        ?>
        <p>You are on page <?= $currentPage ?> of <?= $totalPages ?> (<?= $percentage ?>%).</p>
        <div class="progress-bar-container">
          <div class="progress-bar" style="width: <?= $percentage ?>%;"></div>
        </div>

        <form action="/progress/update" method="POST" class="progress-form">
          <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
          <label for="current_page">Update your current page:</label>
          <input type="number" id="current_page" name="current_page" min="0" max="<?= $totalPages ?>"
            value="<?= $currentPage ?>">
          <button type="submit">Update Progress</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="book-description">
      <p><strong>Description:</strong>
        <?php echo (htmlspecialchars($book['description'] ?? 'No description available.')); ?></p>
    </div>

    <div class="book-actions">
      <a href="/books" class="btn-back">Back to Library</a>
    </div>

    <div class="reviews-section">
      <?php if (isset($loggedInUser)): ?>
        <?php if ($userHasReviewed): ?>
          <p style="text-decoration: underline;">You have already reviewed this book.</p>
        <?php else: ?>
          <div class=" review-form">
            <h3>Leave a Review</h3>
            <form action="/review/add" method="post">
              <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['id']); ?>">
              <div>
                <label for="rating">Rating:</label><br>
                <select name="rating" id="rating" required>
                  <option value="" disabled selected>Select a rating</option>
                  <option value="5">5 - Excellent</option>
                  <option value="4">4 - Very Good</option>
                  <option value="3">3 - Good</option>
                  <option value="2">2 - Fair</option>
                  <option value="1">1 - Poor</option>
                </select>
              </div>
              <br>
              <div>
                <label for="comment">Comment (optional):</label><br>
                <textarea name="comment" id="comment" rows="4" style="width:100%;"></textarea>
              </div>
              <br>
              <button type="submit">Submit Review</button>
            </form>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <p><a href="/login">Log in</a> to leave a review.</p>
      <?php endif; ?>

      <div class="reviews-container">
        <h3>User Reviews</h3>
        <?php if (!empty($reviews)): ?>
          <?php foreach ($reviews as $review): ?>
            <div class="review">
              <p><strong><?php echo htmlspecialchars($review['username']); ?></strong>
                rated it <strong><?php echo htmlspecialchars($review['rating']); ?>/5</strong>
              </p>
              <?php if (!empty($review['comment'])): ?>
                <p><?php echo htmlspecialchars($review['comment']); ?></p>
              <?php endif; ?>
              <small>on <?php echo date('j F Y', strtotime($review['created_at'])); ?></small>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>This book has no reviews yet.</p>
        <?php endif; ?>
      </div>
    </div>

  <?php else: ?>
    <p class="color-red">Book details were not found in database</p>
  <?php endif; ?>
</div>