<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoW | Register</title>
</head>

<body>
    <div class="form">
        <form method="post" action="/register">
            <h2>Register</h2>

            <?php
            if (!empty($message)) {
                $color = ($message_type === 'success') ? "green" : "red";
                echo "<p style='color: " . $color . ";'>" . $message . "</p>";
            }
            ?>

            <div>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email_value); ?>"
                    required><br><br>
            </div>
            <div>
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username"
                    value="<?php echo htmlspecialchars($username_value); ?>" required><br><br>
            </div>
            <div>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>
            </div>
            <div>
                <label for="password_confirm">Confirm Password:</label><br>
                <input type="password" id="password_confirm" name="password_confirm" required><br><br>
            </div>
            <button type="submit" name="register_action">Register</button>
        </form>
        <p><a href="/login">Login here</a></p>
    </div>
</body>

</html>