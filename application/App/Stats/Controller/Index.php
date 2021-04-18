<?php
class Stats_Controller_Index extends Base_Controller
{
  public function indexAction_PUT()
  {
    $code = (string) $this->getParam('code');
    $code = strtoupper($code);

    if (!$code) {
      $this->view->ajaxError('no country code');
    }

    if (!Stats_Service_Country::checkCode($code)) {
      $this->view->ajaxError('invalid country code');
    }

    try {
      Stats_Service_Stat::incrementCountry($code);
    } catch (Exception $e) {
      $this->view->ajaxError($e->getMessage());
    }

    return $this->view->setAjax(['code' => $code]);
  }

  public function indexAction()
  {
    $limit = (string) $this->getParam('limit');
    $offset = (string) $this->getParam('offset', 0);

    $countryCodes = Stats_Service_Country::getCountryCodes($limit, $offset);

    $options = new Stats_Model_Options();
    $options->need_all = true;
    $options->need_current_day = true;
    $options->need_all_unique = true;
    $options->need_current_day_unique = true;

    $res = Stats_Service_Country::loadData($countryCodes, $options);
    return $this->view->setAjax($res);
  }
}
