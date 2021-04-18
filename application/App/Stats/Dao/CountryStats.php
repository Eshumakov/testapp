<?php
class Stats_Dao_CountryStats
{
  const COUNTER_KEY = 'country_counter:';
  const UNIQUES = 'country_unique_counter:';
  const COUNTRY_LIST = 'country_list';

  public function incrementCountryStats(string $code, string $user_id)
  {
    $country_key = self::COUNTER_KEY . '0:' . $code;
    $country_key_unique = self::UNIQUES . '0:' . $code;
    $country_key_daily = self::COUNTER_KEY . date('Ymd') . ':' . $code;
    $country_key_daily_unique = self::UNIQUES . date('Ymd')  . ':' . $code;

    $country_user_key = self::COUNTER_KEY . $user_id;

    $redis = Base_Redis::i();
    $redis->increment($country_key);
    $redis->increment($country_key_daily);

    $redis->sAdd(self::COUNTRY_LIST, $code);

    //write uniques
    if ($redis->add($country_user_key, Base_Service::STORE_DAY)) {
      $redis->increment($country_key_unique);
      $redis->increment($country_key_daily_unique);
    }
  }

  /**
   * @return string[]
   */
  public static function getCountryCodes()
  {
    $list = Base_Redis::i()->getList(self::COUNTRY_LIST);
    return $list;
  }


  /**
   * @param string[] $codes
   * @param Stats_Model_Options $options
   * @return mixed [
   *  C_CODE => [type => [all => 23, daily => 12]]]
   * ]
   */
  public static function getAllStat($codes, Stats_Model_Options $options) {
    $res = [];
    $keys = [];
    foreach ($codes as $code) {
      if ($options->need_all) {
        $keys[] = self::COUNTER_KEY . '0:' . $code;
      }

      if ($options->need_current_day) {
        $keys[] = self::COUNTER_KEY . date('Ymd') . ':' .  $code;
      }

      if ($options->need_all_unique) {
        $keys[] = self::UNIQUES . '0:' . $code;
      }

      if ($options->need_current_day_unique) {
        $keys[] = self::UNIQUES . date('Ymd') . ':' .  $code;
      }
    }

    $data = Base_Redis::i()->mget($keys);

    /*
     * тупое решение, надо бы заменить на геты и ссеты через филды (hincry, hget...)
     */
    foreach($data as $index => $row) {
      $key = $keys[$index];
      [$keyType, $date, $code] = explode(':', $key);

      if (!isset($res[$code])) {
        $res[$code] = [];
      }

      if (!isset($res[$code][$keyType])) {
        $res[$code][$keyType] = [];
      }
      $res[$code][$keyType][$date] = (int)$row;

    }

    return $res;
  }
}