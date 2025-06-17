<div class="book-detail-container">
    <h2><?php echo htmlspecialchars($group['name']); ?></h2>
    <p><em><?php echo htmlspecialchars($group['description']); ?></em></p>
    <p><small>Created by <?php echo htmlspecialchars($group['creator_name']); ?> on <?php echo date('F j, Y', strtotime($group['created_at'])); ?></small></p>

    <?php if (!$isMember): ?>
        <form action="/group/join" method="post">
            <input type="hidden" name="group_id" value="<?php echo $group['id']; ?>">
            <button type="submit">Join Group</button>
        </form>
    <?php endif; ?>

    <div class="detail-list">
        <div class="flex-3">
            <h3>Group's Bookshelf</h3>

            <?php if ($isMember): ?>
            <div class="review-form">
                <form action="/group/add-book" method="post">
                    <input type="hidden" name="group_id" value="<?php echo $group['id']; ?>">
                    <label for="book_id">Add a book to the group:</label>
                    <select name="book_id" id="book_id" required>
                        <option value="">Select a book...</option>
                        <?php foreach($allBooks as $book): ?>
                            <option value="<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Add Book</button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (empty($groupBooks)): ?>
                <p>This group hasn't added any books yet.</p>
            <?php else: ?>
                <?php foreach ($groupBooks as $book): ?>
                    <div class="book-card mb-1rem">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p>by <?php echo htmlspecialchars($book['author']); ?></p>

                        <div class="reviews-section">
                            <h4>Group Discussion</h4>
                            <div class="reviews-container">
                                <?php if(empty($discussions[$book['book_id']])): ?>
                                    <p>No discussion for this book yet.</p>
                                <?php else: ?>
                                    <?php foreach($discussions[$book['book_id']] as $post): ?>
                                    <div class="review">
                                        <p><strong><?php echo htmlspecialchars($post['username']); ?>:</strong> <?php echo htmlspecialchars($post['comment']); ?></p>
                                        <small><?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></small>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <?php if($isMember): ?>
                            <div class="review-form">
                                <form action="/group/discuss" method="post">
                                    <input type="hidden" name="group_id" value="<?php echo $group['id']; ?>">
                                    <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                    <textarea name="comment" placeholder="Add to the discussion..." required rows="2" style="width: 100%;"></textarea>
                                    <button type="submit">Post</button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="flex-1">
            <h3>Members (<?php echo count($members); ?>)</h3>
            <ul>
                <?php foreach ($members as $member): ?>
                    <li><?php echo htmlspecialchars($member['username']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>