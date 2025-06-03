<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
require_once __DIR__ . '/../vendor/autoload.php';

class Utils
{
  public static function getLoggedInUser()
  {
    if (isset($_COOKIE['auth_token'])) {
      $jwt = $_COOKIE['auth_token'];
      $key = 'CHEIA MEA SUPER SECRETA';

      try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        return [
          'username' => $decoded->username,
          'is_admin' => isset($decoded->is_admin) ? (bool) $decoded->is_admin : false,
          'user_id' => $decoded->user_id
        ];
      } catch (ExpiredException $e) {
        // Expired token
        setcookie('auth_token', '', time() - 3600, '/'); // Clear cookie
      } catch (Exception $e) {
        // Invalid token
        setcookie('auth_token', '', time() - 3600, '/'); // Clear cookie
      }
    }
    return null;
  }
}
?>