<div class="center">
  <h2>Stats - Book Reviews and Ratings</h2>

  <?php if (isset($bookStatistics) && !empty($bookStatistics)): ?>
    <table border="1" style="width: 80%; text-align: left; margin-top: 20px; border-collapse: collapse;">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th style="padding: 8px;">Book Title</th>
          <th style="padding: 8px;">Author</th>
          <th style="padding: 8px;">Genre</th>
          <th style="padding: 8px;">Number of Reviews</th>
          <th style="padding: 8px;">Average Score</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookStatistics as $stat): ?>
          <tr>
            <td style="padding: 8px;"><?= htmlspecialchars($stat['title']) ?></td>
            <td style="padding: 8px;"><?= htmlspecialchars($stat['author']) ?></td>
            <td style="padding: 8px;"><?= htmlspecialchars($stat['genre']) ?></td>
            <td style="padding: 8px;"><?= htmlspecialchars($stat['review_count']) ?></td>
            <td style="padding: 8px;"><?= number_format($stat['avg_rating'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div style="margin-top: 20px;">
      <a href="/statistics/export/csv" class="button edit-button no-decoration">Export as CSV</a>
      <a href="/statistics/export/docbook" class="button delete-button no-decoration">Export as DocBook</a>
    </div>
  <?php else: ?>
    <p>No book stats available.</p>
  <?php endif; ?>
</div>