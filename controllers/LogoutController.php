<?php

class LogoutController {
    public function logout() {
        if (isset($_COOKIE['auth_token'])) {
            // expire in the past
            setcookie('auth_token', '', time() - 3600, '/');
            // clear the cookie
            unset($_COOKIE['auth_token']);
        }

        // redirect to homepage
        header("Location: /");
        exit;
    }
}

?>