<?php
class Stats_Service_Stat
{
  public static function incrementCountry($code)
  {
    $userFakeId = Base_Service::getUserSign();
    (new Stats_Dao_CountryStats())->incrementCountryStats($code, $userFakeId);
  }
}