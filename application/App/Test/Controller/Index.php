<?php
class Test_Controller_Index extends Base_Controller
{
  public function preProcess()
  {
    $this->view->setTemplatesPath('Test/Templates/');
    return parent::preProcess();
  }

  public function indexAction()
  {
    $this->view->tpl = 'test.phtml';
  }
}