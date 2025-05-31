<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
require_once __DIR__ . '/../vendor/autoload.php';

class Utils
{
  public static function getLoggedInUser()
  {

    $loggedInUser = null;

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
    return $loggedInUser;
  }
}

