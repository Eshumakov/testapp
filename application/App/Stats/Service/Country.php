<?php
class Stats_Service_Country
{
  public static function checkCode(string $code)
  {
    //TODO use country codes dictionary if it possible
    return true;
  }

  public static function getCountryCodes(int $limit = 0, int $offset = 0)
  {
    $dao = new Stats_Dao_CountryStats();
    $codes = $dao->getCountryCodes();
    if ($limit || $offset) {
      $codes = array_slice($codes, $offset, $limit);
    }

    return $codes;
  }

  /**
   * @param string[] $codes
   * @param Stats_Model_Options $options
   * @return array
   */
  public static function loadData($codes, Stats_Model_Options $options) {
    $dao = new Stats_Dao_CountryStats();
    $all_data = $dao->getAllStat($codes, $options);
    $res = [];

    foreach ($all_data  as $code => $datum) {
      foreach ($datum as $type => $data_type) {
        $all = $data_type[0];
        $dayly = $data_type[date('Ymd')];

        $res[$code] = isset($res[$code]) ? $res[$code] : [];
        $res[$code][$type] = isset($res[$code][$type]) ? $res[$code][$type] : [];

        $res[$code][$type]['all'] = $all;
        $res[$code][$type]['daily'] = $dayly;
      }
    }

    return $res;
  }
}