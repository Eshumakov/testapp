<?php

class Base_Config
{
  public static function getUploadImageLink()
  {
    return '/admin/index/easyUpload/';
  }

  public static function getRtfDirectory()
  {
    return '/share/rtf/';
  }

  public static function getUploadRtfLink()
  {
    return '/admin/index/saveTextFile/';
  }

  public static function getDbConfig()
  {
    return array(
      'user' => 'newuser',
      'db' => 'testapp',
      'pass' => 'sochi2014',
      'host' => 'localhost',
      'port' => 3306
    );
  }

  public static function getRoutes()
  {
    return array_reverse([
      '^/$' => ['Stats','Index','indexAction'],

    ]);
  }

  public function getDb()
  {
    $dbConfig = $this->getDbConfig();
    return new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['db'], $dbConfig['user'], $dbConfig['pass']);
  }

  public static function getMqConfig()
  {
    return array(
      'host' => 'localhost',
      'port' => 5672,
      'vhost' => '/',
      'login' => 'newuser',
      'password' => 'sochi2014'
    );
  }

  public static function getSiteDir()
  {

    return '/var/www/testapp/';
  }

}
