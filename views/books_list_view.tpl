<div class="books-list-container">
  <h2>Book Catalog</h2>

  <form action="/books" method="get" class="center">
    <input type="text" name="search" placeholder="Search book"
      value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>" class="search-box">

    <select name="genre" class="search-box">
      <option value="">All Genres</option>
      <?php if (isset($genres)): ?>
        <?php foreach ($genres as $genre): ?>
          <option value="<?= htmlspecialchars($genre['genre']) ?>" <?= (isset($selectedGenre) && $selectedGenre === $genre['genre']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($genre['genre']) ?>
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>

    <button type="submit" class="search-button">Search</button>
  </form>
  <?php if (!empty($books)): ?>
    <div class="books-grid">
      <?php foreach ($books as $book): ?>
        <div class="book-card">
          <h3>
            <a href="/book?id=<?php echo htmlspecialchars($book['id']); ?>">
              <?php echo htmlspecialchars($book['title']); ?>
            </a>
          </h3>
          <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
          <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genre'] ?? 'N/A'); ?></p>
          <p class="book-rating">
            <strong>Rating:</strong>
            <?php echo number_format($book['avg_rating'], 2); ?> / 5 (<?php echo $book['review_count']; ?> reviews)
          </p>
          <p><strong>Added On:</strong>
            <?php echo htmlspecialchars(date('j F Y', strtotime($book['created_at']))); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <?php if (!empty($searchTerm)): ?>
      <div class="center">
        <p>No books found for <strong>"<?php echo htmlspecialchars($searchTerm); ?>"</strong></a></p>
        <button id="fetch-libraries" class="search-button">Fetch Nearby Libraries</button>
        <div id="nearby-libraries"></div>
      </div>
    <?php else: ?>
      <p>No books found.</p>
    <?php endif; ?>
  <?php endif; ?>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const fetchButton = document.getElementById('fetch-libraries');
    const librariesListUl = document.getElementById('nearby-libraries');

    if (fetchButton) {
      fetchButton.addEventListener('click', function () {
        librariesListUl.innerHTML = '<ul></ul>';

        // try to get location
        $lat = 47.1585;
        $lon = 27.6014;
        if (!navigator.geolocation) {
          librariesListUl.innerHTML = '<li>Location permission not allowed, using Iasi, Romania as default.</li>';
        } else {
          navigator.geolocation.getCurrentPosition(
            position => {
              $lat = position.coords.latitude;
              $lon = position.coords.longitude;
              console.log('Current location:', $lat, $lon);
            },
            error => {
              console.error('Location error:', error);
              librariesListUl.innerHTML = '<li><strong>Location permission denied, using Iasi, Romania as default.</strong></li>';
            }
          );
        }

        fetch('/libraries?lat=' + $lat + '&lon=' + $lon)
          .then(response => {
            if (!response.ok) {
              throw new Error('Error while fetching libraries');
            }
            return response.json();
          })
          .then(data => {
            // console.log(data["libraries"]);
            if (data["libraries"]) {
              data["libraries"].forEach(library => {
                const li = document.createElement('li');
                li.textContent = `${library.name} - ${library.address}`;
                librariesListUl.appendChild(li);
              });
            } else {
              librariesListUl.innerHTML = '<li>No nearby libraries found.</li>';
            }
          })
          .catch(error => {
            console.error('Error fetching libraries:', error);
            librariesListUl.innerHTML = '<li>Error fetching libraries.</li>';
          });
      });
    }
  });
</script>