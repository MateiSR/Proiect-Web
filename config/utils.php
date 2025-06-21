<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class Utils
{
  public static function getLoggedInUser()
  {
    if (isset($_COOKIE['auth_token'])) {
      $jwt = $_COOKIE['auth_token'];
      $key = $_ENV['JWT_SECRET_KEY'];

      try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        return [
          'username' => $decoded->username,
          'is_admin' => isset($decoded->is_admin) ? (bool) $decoded->is_admin : false,
          'user_id' => $decoded->user_id
        ];
      } catch (ExpiredException $e) {
        setcookie('auth_token', '', time() - 3600, '/');
      } catch (Exception $e) {
        setcookie('auth_token', '', time() - 3600, '/');
      }
    }
    return null;
  }
}
?>