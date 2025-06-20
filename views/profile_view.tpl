<div>
  <h1><?php echo htmlspecialchars($user['username']); ?>'s Profile</h1>

  <section>
    <h2>My Books</h2>
    <?php if (!empty($userBooks)): ?>
      <div class="books-grid">
        <?php foreach ($userBooks as $book): ?>
          <div class="book-card">
            <h3><a href="/book?id=<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['title']); ?></a></h3>
            <p>by <?php echo htmlspecialchars($book['author']); ?></p>
            <p>Progress: <?php echo htmlspecialchars($book['current_page']); ?> /
              <?php echo htmlspecialchars($book['pages']); ?> pages
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>You have no books with tracked progress. <a href="/books" Go to books!</a></p>
    <?php endif; ?>
  </section>

  <section>
    <h2>My Groups</h2>
    <?php if (!empty($userGroups)): ?>
      <div class="books-grid">
        <?php foreach ($userGroups as $group): ?>
          <div class="book-card">
            <h3><a href="/group?id=<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></a></h3>
            <p><?php echo htmlspecialchars($group['description']); ?></p>
            <p><?php echo htmlspecialchars($group['member_count']); ?> member(s)</p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>You are not a member of any group. <a href="/groups">Find new groups!</a></p>
    <?php endif; ?>
  </section>
</div>