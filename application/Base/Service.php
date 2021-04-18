<?php

class Base_Service
{
  const STORE_HOUR = 3600;
  const STORE_DAY = 86400;

  const USER_SIGN_COOKIE = 'user_sign';

  public static function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

  public static function getCookie($name)
  {
    if (!empty($_COOKIE[$name])) {
      return $_COOKIE[$name];
    }

    return null;
  }

  public static function getUserSign()
  {
    if ($sign = self::getCookie(self::USER_SIGN_COOKIE)) {
      return $sign;
    }

    return self::generateUserSign();
  }

  private static function generateUserSign()
  {
    /*
     * we can use use ip address or user agent with salt for generate more individual sign
     * but we don't need this for stat
     */
    $rand_salt = bin2hex(random_bytes(8));

    setcookie(self::USER_SIGN_COOKIE, $rand_salt, self::STORE_DAY, '/');
    return $rand_salt;
  }
}