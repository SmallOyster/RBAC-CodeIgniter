<?php
/**
* @name C-显示
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-06
* @version V1.0-B180207 2018-02-07
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Show extends CI_Controller {

  public $allMenu; 

  function __construct()
  {
    parent::__construct();
    $this->load->model('RBAC_model');
    $this->allMenu=$this->RBAC_model->getAllMenu();
  }


  public function blank()
  {
    $this->load->view('show/blank',["navData"=>$this->allMenu]);
  }


  public function login()
  {
    $this->load->view('show/login');
  }
  

  public function jumpOut($url){
    $url=urldecode($url);
    $this->load->view('show/jumpOut',["url"=>$url,"navData"=>$this->allMenu]);
  }
}
