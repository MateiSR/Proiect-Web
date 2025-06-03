<div class="form">
  <form method="post" action="/admin/books/edit?id=<?php echo htmlspecialchars($book['id']); ?>">
    <h2>Edit Book</h2>

    <?php
    if (!empty($message)) {
      $color = ($message_type === 'success') ? "green" : "red";
      echo "<p style='color: " . $color . ";'>" . $message . "</p>";
    }
    ?>

    <div>
      <label for="title">Title:</label><br>
      <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title'] ?? ''); ?>"
        required><br><br>
    </div>
    <div>
      <label for="author">Author:</label><br>
      <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author'] ?? ''); ?>"
        required><br><br>
    </div>
    <div>
      <label for="genre">Genre (Optional):</label><br>
      <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($book['genre'] ?? ''); ?>"><br><br>
    </div>
    <button type="submit">Update Book</button>
    <p><a href="/admin/books">Back to Manage Books</a></p>
  </form>
</div>