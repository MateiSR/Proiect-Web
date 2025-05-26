<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

$loggedInUser = null;
$loginLink = '<a href="/login">Login</a>';
$registerLink = '<a href="/register">Register</a>';
$logoutLink = '<a href="/logout">Logout</a>';

if (isset($_COOKIE['auth_token'])) {
    $jwt = $_COOKIE['auth_token'];
    $key = 'CHEIA MEA SUPER SECRETA';

    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        $loggedInUser = $decoded->username;
    } catch (ExpiredException $e) {
        // expired token
        $loggedInUser = null;
        setcookie('auth_token', '', time() - 3600, '/');
    } catch (Exception $e) {
        // invalid token
        $loggedInUser = null;
        setcookie('auth_token', '', time() - 3600, '/');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books on Web</title>
</head>

<body>
    <h1>Home</h1>

    <?php
    if ($loggedInUser) {
        echo '<p>Welcome, ' . htmlspecialchars($loggedInUser) . '!</p>';
        echo '<p>' . $logoutLink . '</p>';
    } else {
        echo '<p>' . $loginLink . '</p>';
        echo '<p>' . $registerLink . '</p>';
    }
    ?>

</body>

</html>