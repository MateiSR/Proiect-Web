<div class="form">
  <form method="post" action="/admin/add-book">
    <h2>Add New Book</h2>

    <?php
    if (!empty($message)) {
      $color = ($message_type === 'success') ? "green" : "red";
      echo "<p style='color: " . $color . ";'>" . $message . "</p>";
    }
    ?>

    <div>
      <label for="title">Title:</label><br>
      <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title_value ?? ''); ?>"
        required><br><br>
    </div>
    <div>
      <label for="author">Author:</label><br>
      <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($author_value ?? ''); ?>"
        required><br><br>
    </div>
    <div>
      <label for="genre">Genre (Optional):</label><br>
      <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($genre_value ?? ''); ?>"><br><br>
    </div>
    <div>
      <label for="description">Description (Optional):</label><br>
      <textarea id="description" name="description" rows="5"
        cols="50"><?php echo htmlspecialchars($description_value ?? ''); ?></textarea><br><br>
    </div>
    <button type="submit">Add Book</button>
  </form>
</div>