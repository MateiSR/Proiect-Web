<div class="form-container">
  <h2>Admin - Import Books</h2>

  <?php
  if (!empty($message)) {
    $color = ($message_type === 'success') ? "green" : "red";
    echo "<p style='color: " . $color . ";'>" . $message . "</p>";
  }
  ?>

  <div class="import-section">
    <h3>Import from CSV</h3>
    <form action="/admin/import-books/csv" method="post" enctype="multipart/form-data">
      <label for="csv_file">Select CSV File:</label><br>
      <input type="file" id="csv_file" name="csv_file" accept=".csv" required><br><br>
      <button type="submit">Import CSV</button>
    </form>
  </div>

  <div class="import-section">
    <h3>Import from JSON</h3>
    <form action="/admin/import-books/json" method="post" enctype="multipart/form-data">
      <label for="json_file">Select JSON File:</label><br>
      <input type="file" id="json_file" name="json_file" accept=".json" required><br><br>
      <button type="submit">Import JSON</button>
    </form>
  </div>
  <p><a href="/admin">Back to Admin Home</a></p>
</div>