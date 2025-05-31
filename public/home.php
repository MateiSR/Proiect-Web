<?php
require_once __DIR__ . '/../config/utils.php';
$loggedInUser = Utils::getLoggedInUser();
?>
<h1>Home</h1>

<?php
if ($loggedInUser) {
    echo '<p>Welcome, ' . htmlspecialchars($loggedInUser) . '!</p>';
} else {
    echo '<p>You are not logged in. Please <a href="/login">login</a> or <a href="/register">register</a>.</p>';
}
?>