<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoW | Login</title>
</head>

<body>
    <div class="form">
        <form method="post" action="/login">
            <h2>Login</h2>
            <?php
            if (!empty($err)) {
                echo "<p style='color: red;'>" . htmlspecialchars($err) . "</p>";
            }
            ?>
            <div>
                <label for="identifier">Email or Username:</label><br>
                <input type="text" id="identifier" name="identifier"
                    value="<?php echo htmlspecialchars($identifier_value ?? ''); ?>" required><br><br>
            </div>

            <div>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>
            </div>

            <button type="submit">Login</button>
        </form>
        <p><a href="/register">Register here</a></p>
    </div>
</body>

</html>