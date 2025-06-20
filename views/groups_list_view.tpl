<div class="books-list-container">
    <div style="list-view">
        <h2>All Groups</h2>
        <a href="/group/create" class="button no-decoration btn-new-group">Create New Group</a>
    </div>

    <?php if (empty($groups)): ?>
        <p>No groups found.</p>
    <?php else: ?>
        <div class="books-grid">
            <?php foreach ($groups as $group): ?>
                <div class="book-card">
                    <h3><a href="/group?id=<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></a></h3>
                    <p><?php echo htmlspecialchars($group['description']); ?></p>
                    <small>Creator: <?php echo htmlspecialchars($group['creator_name']); ?> | Members:
                        <?php echo $group['member_count']; ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>