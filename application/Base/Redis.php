<?php
class Base_Redis
{
  protected static $_instance = null;
  protected $redisObj = null;

  protected $staticCache = null;


  private function __construct()
  {
    try {
      $this->redisObj = new Redis();
      $this->redisObj->connect('localhost', 6379);
    } catch(Exception $e) {
      exit('Connect error');
    }
  }

  public static function i()
  {
    if (empty(self::$_instance)) {
      self::$_instance = new self();
    }

    return self::$_instance;
  }

  public function getList($key)
  {
    return $this->redisObj->sMembers($key);
  }
  public function get($key)
  {
    if (!empty($this->staticCache[$key])) {
      return $this->staticCache[$key];
    }
    $res = $this->redisObj->get($key);
    $this->staticCache[$key] = $res;
    return $res;
  }

  public function mget($keys)
  {
    $res = [];
    foreach ($keys as $key) {
      if (!empty($this->staticCache[$key])) {
        $res[$key] = $this->staticCache[$key];
      }
    }
    $res = $this->redisObj->mget($keys);
    return $res;
  }

  public function set($key, $val, $expire = 90)
  {
    $this->staticCache[$key] = $val;
    $this->redisObj->set($key, $val);
    if ($expire === 0) {
      $this->redisObj->persist($key);
    } else {
      $this->redisObj->expire($key, $expire);
    }

  }

  public function add($key, $val, $expire = 90)
  {
    $res = $this->redisObj->setnx($key, $val);
    $this->redisObj->expire($key, $expire);

    if ($res) {
      $this->staticCache[$key] = $val;
    }

    return $res;
  }

  public function increment($key)
  {
    return $this->redisObj->incr($key);
  }

  public function sAdd($key, $val)
  {
    return $this->redisObj->sAdd($key, $val);
  }

  public function delete($key)
  {
    unset($this->staticCache[$key]);
    return $this->redisObj->delete($key);
  }
}