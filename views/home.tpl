<?php
require_once __DIR__ . '/../config/utils.php';
$loggedInUser = Utils::getLoggedInUser();
?>
<h1>Home</h1>

<?php
if ($loggedInUser) {
    echo '<p>Welcome, ' . htmlspecialchars($loggedInUser['username']) . '!</p>';
    echo '<p>You are an ' . ($loggedInUser['is_admin'] ? 'admin' : 'user') . '.</p>';
} else {
    echo '<p>You are not logged in. Please <a href="/login">login</a> or <a href="/register">register</a>.</p>';
}
?>

<div class="feed-container">
    <h2>Recent Activity</h2>
    <?php if (isset($feedError) && $feedError): ?>
        <p class="color-red">Could not fetch RSS feed: <?= htmlspecialchars($feedError) ?></p>
    <?php elseif (isset($feedItems) && !empty($feedItems)): ?>
        <ul class="feed-list">
            <?php foreach ($feedItems as $item): ?>
                <li class="feed-item">
                    <div class="feed-item-title">
                        <a
                            href="<?= htmlspecialchars($item->get_permalink()) ?>"><?= htmlspecialchars($item->get_title()) ?></a>
                    </div>
                    <div class="feed-item-meta">
                        <span class="feed-item-date"><?= $item->get_date('F j, Y, g:i a') ?></span>
                    </div>
                    <p class="feed-item-description"><?= htmlspecialchars($item->get_description()) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No RSS feed to display.</p>
    <?php endif; ?>
</div>